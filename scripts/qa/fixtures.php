<?php
$_SERVER['HTTP_HOST']='127.0.0.1:8787'; $_SERVER['REQUEST_METHOD']='GET';
$root=realpath(getenv('RUNNER_TEMP').'/ad-isolated-qa/wordpress');
require $root.'/wp-load.php';
if(wp_get_environment_type()!=='local' || home_url()!=='http://127.0.0.1:8787') throw new RuntimeException('Not a disposable local installation');
WC_Install::install(); switch_theme('aliments-distinction'); update_option('blog_public',0);
$ids=[];
foreach(['shop'=>'Catalogue','cart'=>'Panier','checkout'=>'Commande','myaccount'=>'Compte'] as $key=>$title){
 $content=['shop'=>'','cart'=>'[woocommerce_cart]','checkout'=>'[woocommerce_checkout]','myaccount'=>'[woocommerce_my_account]'][$key];
 $id=wp_insert_post(['post_title'=>$title,'post_type'=>'page','post_status'=>'publish','post_content'=>$content]);
 update_option('woocommerce_'.$key.'_page_id',$id); $ids[$key]=$id;
}
foreach(['viandes','fruits-de-mer-poissons','veau-volaille-agneau','produits-gourmets','desserts'] as $slug) wp_insert_term($slug,'product_cat',['slug'=>$slug]);
$simple=new WC_Product_Simple(); $simple->set_name('QA — Produit simple'); $simple->set_regular_price('225'); $simple->set_status('publish'); $simple->set_virtual(true); $simple->set_manage_stock(true); $simple->set_stock_quantity(5); $simple->save(); $ids['simple']=$simple->get_id();
$out=new WC_Product_Simple(); $out->set_name('QA — Rupture'); $out->set_regular_price('30'); $out->set_stock_status('outofstock'); $out->set_status('publish'); $out->save(); $ids['out']=$out->get_id();
$variable=new WC_Product_Variable(); $variable->set_name('QA — Variations'); $variable->set_status('publish');
$a=new WC_Product_Attribute(); $a->set_name('Portion'); $a->set_options(['250 g','500 g']); $a->set_variation(true); $variable->set_attributes([$a]); $variable->save(); $ids['variable']=$variable->get_id();
foreach(['250 g'=>'20','500 g'=>'35'] as $portion=>$price){$v=new WC_Product_Variation();$v->set_parent_id($variable->get_id());$v->set_status('publish');$v->set_attributes(['portion'=>$portion]);$v->set_regular_price($price);$v->save();}
WC_Product_Variable::sync($variable->get_id());
$bits=wp_upload_bits('qa-filet.webp',null,file_get_contents(get_theme_file_path('/assets/images/filet.webp')));
$attachment=wp_insert_attachment(['post_title'=>'QA image','post_mime_type'=>'image/webp','post_status'=>'inherit'],$bits['file']);
wp_update_attachment_metadata($attachment,['width'=>720,'height'=>475,'file'=>_wp_relative_upload_path($bits['file'])]);
foreach(['simple','out','variable'] as $key) set_post_thumbnail($ids[$key],$attachment);
file_put_contents(getenv('RUNNER_TEMP').'/ad-isolated-qa/fixtures.json',json_encode($ids));
if(count(WC()->payment_gateways()->get_available_payment_gateways())!==0) throw new RuntimeException('Unexpected active gateway');
echo "Fictional runner fixtures ready. No payment gateway. No hosted WordPress changes.\n";
