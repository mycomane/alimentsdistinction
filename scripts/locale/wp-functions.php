<?php
/** Targeted WPLANG operations; protected commercial state is always compared. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit(1); }
function ad_locale_identity(bool $isolated=false):void {
 global $wp_version;
 $origin=$isolated?'http://127.0.0.1:8787':'https://1302636.us22.myftpupload.com';
 if(get_option('siteurl')!==$origin||get_option('home')!==$origin||($isolated&&wp_get_environment_type()!=='local'))throw new RuntimeException('Exact authorized identity required');
 if($wp_version!=='7.1.3'||version_compare(PHP_VERSION,'8.1','<')||!defined('WC_VERSION')||WC_VERSION!=='11.2.1'||get_stylesheet()!=='aliments-distinction'||get_template()!=='aliments-distinction'||wp_get_theme()->get('Version')!=='0.1.2')throw new RuntimeException('Locale environment/version drift');
 require_once ABSPATH.'wp-admin/includes/plugin.php';if(!is_plugin_active('woocommerce/woocommerce.php'))throw new RuntimeException('WooCommerce must already be active');
 if(defined('WPLANG')&&WPLANG!=='')throw new RuntimeException('Configuration constant controls locale; stop for review');
 if(WP_LANG_DIR!==ABSPATH.'wp-content/languages')throw new RuntimeException('Custom language root; review required');
}
function ad_locale_raw():?array {
 global $wpdb;$previous=$wpdb->suppress_errors(true);
 try{$row=$wpdb->get_row("SELECT option_value, autoload FROM {$wpdb->options} WHERE option_name='WPLANG'",ARRAY_A);$failed=$wpdb->last_error!=='';}finally{$wpdb->suppress_errors($previous);}
 if($failed)throw new RuntimeException('WPLANG_QUERY_FAILED');return $row;
}
function ad_locale_rows(string $sql):array {
 global $wpdb;$previous=$wpdb->suppress_errors(true);
 try{$rows=$wpdb->get_results($sql,ARRAY_A);$failed=$wpdb->last_error!=='';}finally{$wpdb->suppress_errors($previous);}
 if(!is_array($rows)||$failed)throw new RuntimeException('Protected rows query unavailable');return $rows;
}
function ad_locale_var(string $sql) {
 global $wpdb;$previous=$wpdb->suppress_errors(true);
 try{$value=$wpdb->get_var($sql);$failed=$wpdb->last_error!=='';}finally{$wpdb->suppress_errors($previous);}
 if($failed)throw new RuntimeException('Protected scalar query unavailable');return $value;
}
function ad_locale_count(string $sql):int {
 $value=ad_locale_var($sql);if(!is_scalar($value)||!preg_match('/\A(?:0|[1-9][0-9]*)\z/D',(string)$value)||strlen((string)$value)>15)throw new RuntimeException('Protected count unavailable');return (int)$value;
}
function ad_locale_protected(array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):array {
 global $wpdb;
$state = ['options' => [], 'pages' => [], 'counts' => [], 'tables' => []];
// Hash protected options directly from SQL; values never leave the private host file.
$rows = ad_locale_rows("SELECT option_name, option_value, autoload FROM {$wpdb->options} ORDER BY option_name");
if(!is_array($rows)||$wpdb->last_error!=='')throw new RuntimeException('Protected options query unavailable');
foreach ($rows as $row) {
    $name = $row['option_name'];
    if (preg_match('/^(woocommerce_|theme_mods_|sidebars_widgets$|widget_|active_plugins$|stylesheet$|template$|siteurl$|home$|blog_public$|show_on_front$|page_on_front$|page_for_posts$|permalink_structure$)/', $name)
        || preg_match('/coming.?soon|maintenance/i', $name)) {
        $state['options'][$name] = hash('sha256', serialize([$row['option_value'], $row['autoload']]));
    }
}
foreach ($pageIDs as $key => $expectedID) {
    $id = (int) get_option('woocommerce_' . $key . '_page_id');
    $page = get_post($id, ARRAY_A);
    if ($id !== $expectedID || !$page || $page['post_status'] !== 'publish' || $page['post_type'] !== 'page') { throw new RuntimeException('Technical page drift; refusing update'); }
    $state['pages'][$key] = hash('sha256', serialize([$page, get_post_meta($id)]));
}
foreach (['product', 'product_variation', 'shop_order', 'page'] as $type) {
    $state['counts'][$type] = ad_locale_count($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type=%s", $type));
}
$state['counts']['users'] = ad_locale_count("SELECT COUNT(*) FROM {$wpdb->users}");
foreach (['wc_orders', 'woocommerce_shipping_zones', 'woocommerce_shipping_zone_locations', 'woocommerce_shipping_zone_methods', 'woocommerce_tax_rates', 'woocommerce_tax_rate_locations'] as $suffix) {
    $table = $wpdb->prefix . $suffix;
    if (ad_locale_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table))) !== $table) { $state['tables'][$suffix] = null; continue; }
    $safe = '`' . str_replace('`', '``', $table) . '`';
    if ($suffix === 'wc_orders') { $state['counts']['hpos'] = ad_locale_count("SELECT COUNT(*) FROM $safe"); continue; }
    $hashes = array_map(static fn($row) => hash('sha256', serialize($row)), ad_locale_rows("SELECT * FROM $safe"));
    sort($hashes); $state['tables'][$suffix] = hash('sha256', serialize($hashes));
}
foreach (WC()->payment_gateways()->payment_gateways() as $gateway) {
    if ($gateway->enabled === 'yes') { throw new RuntimeException('Enabled gateway detected; review required'); }
}
if($wpdb->last_error!=='')throw new RuntimeException('Protected state query unavailable');
return $state;
}
function ad_locale_capture(array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):array {
 $raw=ad_locale_raw();if(get_locale()!=='en_US'||($raw!==null&&!in_array($raw['option_value'],['','en_US'],true)))throw new RuntimeException('Expected English baseline only');
 return ['locale_before'=>'en_US','WPLANG'=>$raw,'protected'=>ad_locale_protected($pageIDs)];
}
function ad_locale_observe(string $key,$value):void {
 $allowed=['CORE_READABLE','RECOGNIZED_BEFORE','RECOGNIZED_AFTER','API_CHANGED','WRITE_QUERY_OK','RAW_QUERY_OK','RAW_MATCH'];
 if(!in_array($key,$allowed,true)||!is_bool($value))throw new RuntimeException('LOCALE_STATE_REFUSED');
 echo 'WPLANG_OBSERVE '.$key.'='.($value?'1':'0')."\n";
}
function ad_locale_activate(array $baseline,array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):void {
 global $wpdb;
 if(ad_locale_raw()!==$baseline['WPLANG']||ad_locale_protected($pageIDs)!==$baseline['protected'])throw new RuntimeException('Pre-locale protected state drift');
 $readable=is_readable(WP_LANG_DIR.'/fr_CA.mo')||is_readable(WP_LANG_DIR.'/fr_CA.l10n.php');
 ad_locale_observe('CORE_READABLE',$readable);
 if(!$readable)throw new RuntimeException('FR_CA_NOT_AVAILABLE');
 ad_locale_observe('RECOGNIZED_BEFORE',in_array('fr_CA',get_available_languages(),true));
 ad_locale_refresh_language_files();
 $recognized=in_array('fr_CA',get_available_languages(),true);
 ad_locale_observe('RECOGNIZED_AFTER',$recognized);
 if(!$recognized)throw new RuntimeException('FR_CA_NOT_AVAILABLE');
 $old=$baseline['WPLANG'];$autoload=$old===null||in_array($old['autoload'],['yes','on','auto','auto-on'],true);
 $previous=$wpdb->suppress_errors(true);
 try{$changed=update_option('WPLANG','fr_CA',$autoload);$writeFailed=$wpdb->last_error!=='';}
 catch(Throwable $e){throw new RuntimeException('WPLANG_API_EXCEPTION');}
 finally{$wpdb->suppress_errors($previous);}
 ad_locale_observe('API_CHANGED',$changed);
 ad_locale_observe('WRITE_QUERY_OK',!$writeFailed);
 // Always perform the independent raw read, including when the API returns false.
 try{$raw=ad_locale_raw();}
 catch(Throwable $e){if($e->getMessage()==='WPLANG_QUERY_FAILED')ad_locale_observe('RAW_QUERY_OK',false);throw $e;}
 ad_locale_observe('RAW_QUERY_OK',true);
 $matches=$raw!==null&&($raw['option_value']??null)==='fr_CA';
 ad_locale_observe('RAW_MATCH',$matches);
 if($writeFailed)throw new RuntimeException('WPLANG_WRITE_FAILED');
 if(!$changed)throw new RuntimeException('WPLANG_API_FALSE');
 if(!$matches)throw new RuntimeException('WPLANG_RAW_MISMATCH');
 if(ad_locale_protected($pageIDs)!==$baseline['protected'])throw new RuntimeException('Unexpected protected change after language update');
}
function ad_locale_refresh_language_files():void {
 if(!is_readable(WP_LANG_DIR.'/fr_CA.mo')&&!is_readable(WP_LANG_DIR.'/fr_CA.l10n.php'))throw new RuntimeException('FR_CA_NOT_AVAILABLE');
 ad_locale_invalidate_language_discovery();
}
function ad_locale_invalidate_language_discovery():void {
 global $wp_textdomain_registry;
 if(!($wp_textdomain_registry instanceof WP_Textdomain_Registry)||!method_exists($wp_textdomain_registry,'invalidate_mo_files_cache'))throw new RuntimeException('LANGUAGE_CACHE_REFRESH_UNAVAILABLE');
 // Same core API as a language-pack upgrade: only core/plugin language-file
 // discovery entries are invalidated. No global object-cache flush or bypass.
 $wp_textdomain_registry->invalidate_mo_files_cache(null,['type'=>'translation','translations'=>[
  ['type'=>'core','language'=>'fr_CA','slug'=>'default','version'=>'7.1.3'],
  ['type'=>'plugin','language'=>'fr_CA','slug'=>'woocommerce','version'=>'11.2.1'],
 ]]);
}
function ad_locale_public_error(Throwable $error):string {
 $allowed=['FR_CA_NOT_AVAILABLE','LANGUAGE_CACHE_REFRESH_UNAVAILABLE','WPLANG_QUERY_FAILED','WPLANG_WRITE_FAILED','WPLANG_API_FALSE','WPLANG_RAW_MISMATCH','WPLANG_API_EXCEPTION','LOCALE_STATE_RUNTIME_WARNING'];
 return in_array($error->getMessage(),$allowed,true)?$error->getMessage():'LOCALE_STATE_REFUSED';
}
function ad_locale_restore(array $baseline):void {
 global $wpdb;$now=ad_locale_raw();
 if($now===$baseline['WPLANG'])return;
 if($now===null||$now['option_value']!=='fr_CA')throw new RuntimeException('Unknown WPLANG change; no blind rollback');
 if($baseline['WPLANG']===null){if(!delete_option('WPLANG'))throw new RuntimeException('Cannot remove newly created WPLANG');}
 else{
  $old=$baseline['WPLANG'];
  // Restore the exact original row: WordPress sanitizes legacy en_US to an empty value.
  if($wpdb->update($wpdb->options,['option_value'=>$old['option_value'],'autoload'=>$old['autoload']],['option_name'=>'WPLANG'],['%s','%s'],['%s'])===false)throw new RuntimeException('Cannot restore original WPLANG row');
  // Exact per-site option cache keys required for the targeted option restoration.
  wp_cache_delete('WPLANG','options');wp_cache_delete('alloptions','options');wp_cache_delete('notoptions','options');
 }
 if(ad_locale_raw()!==$baseline['WPLANG'])throw new RuntimeException('WPLANG original row not restored exactly');
}
function ad_locale_verify(array $baseline,string $phase,array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):void {
 if(!in_array($phase,['old','new'],true)||ad_locale_protected($pageIDs)!==$baseline['protected'])throw new RuntimeException('Locale verification/protected state mismatch');
 if($phase==='old'){
  if(ad_locale_raw()!==$baseline['WPLANG']||get_locale()!=='en_US')throw new RuntimeException('Old locale not restored');
 }elseif((ad_locale_raw()['option_value']??null)!=='fr_CA'||get_locale()!=='fr_CA'||__('Add to cart','woocommerce')!=='Ajouter au panier'||__('Choose an option','woocommerce')!=='Choisir une option')throw new RuntimeException('Effective French locale/labels not verified');
}
function ad_locale_audit():array {
 $packs=[];foreach(['fr_CA','fr_FR'] as $locale){foreach(['core'=>WP_LANG_DIR.'/'.$locale,'woocommerce'=>WP_LANG_DIR.'/plugins/woocommerce-'.$locale] as $domain=>$stem){$packs[$locale][$domain]=['mo'=>is_file($stem.'.mo'),'l10n_php'=>is_file($stem.'.l10n.php')];}}
 return ['locale'=>get_locale(),'packs_by_explicit_locale'=>$packs,'add_to_cart'=>__('Add to cart','woocommerce'),'choose_option'=>__('Choose an option','woocommerce'),'stored_checkout_privacy_has_english_marker'=>stripos((string)get_option('woocommerce_checkout_privacy_policy_text',''),'Your personal data')!==false];
}
