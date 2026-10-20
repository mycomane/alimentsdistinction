<?php
/** Scoped activation and page preparation. Executed via WP-CLI, never HTTP. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit(1); }
$adOpsLocal = defined('AD_OPS_ISOLATED_TEST') && AD_OPS_ISOLATED_TEST && wp_get_environment_type()==='local' && get_option('siteurl')==='http://127.0.0.1:8787';
if (!$adOpsLocal && get_option('siteurl')!=='https://1302636.us22.myftpupload.com') { throw new RuntimeException('Unauthorized site'); }
if (get_stylesheet()!=='aliments-distinction') { throw new RuntimeException('Unexpected theme'); }
require_once ABSPATH.'wp-admin/includes/plugin.php';
$adOpsMode=$args[0]??''; $adOpsPath=$args[1]??'';
if (!$adOpsPath || !is_dir(dirname($adOpsPath)) || is_link($adOpsPath)) { throw new RuntimeException('Invalid private state path'); }
if (!$adOpsLocal && !str_starts_with(realpath(dirname($adOpsPath)),realpath(getenv('HOME')).'/.ad-private-backups/')) { throw new RuntimeException('State must remain in private backup directory'); }
$adOpsPages=[
 'shop'=>['catalogue','Catalogue','', ['catalogue','boutique','shop']],
 'cart'=>['panier','Panier','[woocommerce_cart]', ['panier','cart']],
 'checkout'=>['commande','Commande','[woocommerce_checkout]', ['commande','checkout']],
 'myaccount'=>['mon-compte','Mon compte','[woocommerce_my_account]', ['mon-compte','my-account','compte']],
];
$adOpsSave=static function($state)use($adOpsPath){if(file_put_contents($adOpsPath,wp_json_encode($state,JSON_PRETTY_PRINT),LOCK_EX)===false)throw new RuntimeException('Cannot save private state');chmod($adOpsPath,0600);};
$adOpsProtect=['blog_public','woocommerce_currency','woocommerce_default_country','woocommerce_calc_taxes','woocommerce_prices_include_tax','woocommerce_tax_based_on','woocommerce_tax_display_shop','woocommerce_tax_display_cart'];
if($adOpsMode==='capture'){
 if(is_file($adOpsPath)||is_plugin_active('woocommerce/woocommerce.php'))throw new RuntimeException('Already initialized or WooCommerce already active; no blind retry');
 $meta=get_file_data(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php',['Version'=>'Version','PHP'=>'Requires PHP','WP'=>'Requires at least']);
 global $wp_version,$wpdb;
 if($meta['Version']!=='11.2.1'||version_compare(PHP_VERSION,max('8.1',$meta['PHP']),'<')||version_compare($wp_version,$meta['WP'],'<'))throw new RuntimeException('Version preconditions failed');
 foreach(['bacs','cheque','cod','paypal','stripe','woocommerce_payments'] as $gateway){$value=get_option('woocommerce_'.$gateway.'_settings',[]);if(is_array($value)&&($value['enabled']??'no')==='yes')throw new RuntimeException('A stored payment gateway is enabled; stop before activation');}
 $state=['created_ids'=>[],'page_options'=>[],'protected'=>[],'original_page_ids'=>array_map('intval',$wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='page'")),'content_counts'=>[]];
 foreach($adOpsPages as $key=>$spec){$name='woocommerce_'.$key.'_page_id';$value=get_option($name,null);$state['page_options'][$name]=['exists'=>$value!==null,'value'=>$value];}
 foreach($adOpsProtect as $name){$value=get_option($name,null);$state['protected'][$name]=['exists'=>$value!==null,'hash'=>hash('sha256',serialize($value)),'value'=>$value];}
 foreach(['product','product_variation','shop_order'] as $type)$state['content_counts'][$type]=(int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type=%s",$type));
 $adOpsSave($state);echo "PASS: exact inactive WooCommerce version, no stored enabled gateway; private baseline captured.\n";return;
}
if(!is_file($adOpsPath))throw new RuntimeException('Missing private baseline');
$state=json_decode(file_get_contents($adOpsPath),true,512,JSON_THROW_ON_ERROR);
if($adOpsMode==='activate'){
 if(is_plugin_active('woocommerce/woocommerce.php'))throw new RuntimeException('WooCommerce changed since baseline');
 // Core activation is allowed to create schema/options/roles, but page creation is strictly scoped below.
 add_filter('woocommerce_create_pages','__return_empty_array',PHP_INT_MAX);
 $result=activate_plugin('woocommerce/woocommerce.php');
 if(is_wp_error($result))throw new RuntimeException('WooCommerce activation failed');
 if(!class_exists('WC_Install'))throw new RuntimeException('WooCommerce installer unavailable');
 WC_Install::install();
 foreach($adOpsPages as $key=>$spec){
  $option='woocommerce_'.$key.'_page_id';$id=(int)get_option($option,0);$page=$id?get_post($id):null;
  if($page&&($page->post_type!=='page'||$page->post_status!=='publish'))throw new RuntimeException('Assigned page is not a published page');
  if(!$page){
   $matches=[];
   foreach($spec[3] as $slug){$candidate=get_page_by_path($slug,OBJECT,'page');if($candidate){if($candidate->post_status!=='publish')throw new RuntimeException('Existing matching page is not published; refusing overwrite');$matches[$candidate->ID]=$candidate;}}
   if(count($matches)>1)throw new RuntimeException('Ambiguous existing technical pages');
   $page=$matches?reset($matches):null;
   if($page){$id=$page->ID;}else{
    $id=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$spec[1],'post_name'=>$spec[0],'post_content'=>$spec[2],'comment_status'=>'closed'],true);
    if(is_wp_error($id)||!$id)throw new RuntimeException('Technical page creation failed');
    $state['created_ids'][]=(int)$id;$adOpsSave($state);
   }
   update_option($option,(int)$id);
  }
  $page=get_post($id);
  if($key!=='shop'&&!str_contains($page->post_content,$spec[2])&&!has_block('woocommerce/'.($key==='cart'?'cart':($key==='checkout'?'checkout':'my-account')),$page))throw new RuntimeException('Existing technical page has incompatible content; refusing overwrite');
  echo 'PAGE '.$key.' ID='.(int)$id."\n";
 }
 echo "PASS: WooCommerce activated and four technical pages assigned; no content imported.\n";return;
}
if($adOpsMode==='verify'){
 global $wpdb;
 if(!is_plugin_active('woocommerce/woocommerce.php')||!function_exists('WC'))throw new RuntimeException('WooCommerce not active');
 $ids=[];
 foreach($adOpsPages as $key=>$spec){$id=(int)get_option('woocommerce_'.$key.'_page_id');$page=get_post($id);if(!$page||$page->post_type!=='page'||$page->post_status!=='publish')throw new RuntimeException('Technical page missing');$ids[]=$id;echo 'PAGE '.$key.' ID='.$id."\n";}
 if(count(array_unique($ids))!==4)throw new RuntimeException('Technical page mappings are not distinct');
 foreach($state['protected'] as $name=>$before){if($before['exists']&&hash('sha256',serialize(get_option($name,null)))!==$before['hash'])throw new RuntimeException('Existing commercial/visibility option changed: '.$name);}
 foreach($state['content_counts'] as $type=>$count){if((int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type=%s",$type))!==$count)throw new RuntimeException('Unexpected product/order post count change');}
 foreach(WC()->payment_gateways()->payment_gateways() as $gateway){if($gateway->enabled==='yes')throw new RuntimeException('A payment gateway is enabled; stop and roll back activation');}
 if(get_stylesheet()!=='aliments-distinction')throw new RuntimeException('Theme changed');
 echo 'PASS: WooCommerce '.WC_VERSION.' active, four pages valid, all registered gateways disabled, existing protected settings and content counts preserved.' . "\n";return;
}
if($adOpsMode==='rollback'){
 deactivate_plugins('woocommerce/woocommerce.php');
 foreach($state['page_options'] as $name=>$before){if($before['exists'])update_option($name,$before['value']);else delete_option($name);}
 foreach($state['protected'] as $name=>$before){if($before['exists'])update_option($name,$before['value']);}
 foreach($state['created_ids'] as $id){if(!in_array((int)$id,$state['original_page_ids'],true)&&get_post_type($id)==='page')wp_trash_post($id);}
 echo "ROLLBACK: WooCommerce deactivated, original page mappings restored, only job-created pages trashed. Activation schema/default options remain; full SQL restoration requires a separate recovery decision.\n";return;
}
throw new RuntimeException('Unknown operation');
