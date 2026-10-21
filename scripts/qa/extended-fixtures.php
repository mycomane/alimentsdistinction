<?php
/** Extra synthetic fixtures ONLY in the disposable runner; does not accept remote credentials. */
$_SERVER['HTTP_HOST']='127.0.0.1:8787'; $_SERVER['REQUEST_METHOD']='GET';
$root=realpath(getenv('RUNNER_TEMP').'/ad-isolated-qa/wordpress');
require $root.'/wp-load.php';
if(wp_get_environment_type()!=='local'||get_option('siteurl')!=='http://127.0.0.1:8787')throw new RuntimeException('Isolated installation required');
$file=getenv('RUNNER_TEMP').'/ad-isolated-qa/extended-fixtures.json';
if(file_exists($file))throw new RuntimeException('Fresh fixture state required');
$ids=json_decode(file_get_contents(getenv('RUNNER_TEMP').'/ad-isolated-qa/fixtures.json'),true,512,JSON_THROW_ON_ERROR);
$term=wp_insert_term('QA recette isolée','product_cat',['slug'=>'qa-recette']);
if(is_wp_error($term))throw new RuntimeException('Synthetic category creation failed');
$products=[];
for($i=1;$i<=12;$i++){
 $p=new WC_Product_Simple();$p->set_name('QA isolée — article '.str_pad((string)$i,2,'0',STR_PAD_LEFT));$p->set_status('publish');$p->set_regular_price((string)(5+$i*5));$p->set_virtual(true);$p->set_manage_stock(true);$p->set_stock_quantity(7);$p->set_category_ids([(int)$term['term_id']]);$p->save();
 $products[]=['id'=>$p->get_id(),'price'=>5+$i*5];
}
// Local-only catalog size makes four genuine pagination pages, without a theme filter.
update_option('woocommerce_catalog_columns',3);update_option('woocommerce_catalog_rows',1);
$variable=wc_get_product($ids['variable']);$attrs=$variable->get_attributes();$attrs['portion']->set_options(['250 g','500 g','1 kg']);$variable->set_attributes($attrs);$variable->save();
$v=new WC_Product_Variation();$v->set_parent_id($variable->get_id());$v->set_status('publish');$v->set_attributes(['portion'=>'1 kg']);$v->set_regular_price('60');$v->set_stock_status('outofstock');$v->save();WC_Product_Variable::sync($variable->get_id());
file_put_contents($file,json_encode(['products'=>$products,'stockout_variation'=>$v->get_id(),'category'=>'qa-recette'],JSON_THROW_ON_ERROR));
foreach([0,-1,6] as $quantity){
 if(WC()->cart->add_to_cart($ids['simple'],$quantity))throw new RuntimeException('Invalid synthetic quantity accepted');
 wc_clear_notices();
 echo 'PASS: isolated cart rejects quantity '.$quantity.".\n";
}
if(count(WC()->payment_gateways()->get_available_payment_gateways())!==0)throw new RuntimeException('Payments must remain disabled');
echo "PASS: 12 synthetic category products, 3 variations, local pagination and no payment gateway prepared.\n";
