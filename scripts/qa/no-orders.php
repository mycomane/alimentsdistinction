<?php
$_SERVER['HTTP_HOST']='127.0.0.1:8787';$_SERVER['REQUEST_METHOD']='GET';
require getenv('RUNNER_TEMP').'/ad-isolated-qa/wordpress/wp-load.php';
if(wp_get_environment_type()!=='local'||get_option('siteurl')!=='http://127.0.0.1:8787')throw new RuntimeException('Isolated root required');
$orders=wc_get_orders(['limit'=>1,'return'=>'ids','status'=>array_keys(wc_get_order_statuses())]);
if($orders)throw new RuntimeException('Unexpected order in disposable test installation');
if(count(WC()->payment_gateways()->get_available_payment_gateways())!==0)throw new RuntimeException('Unexpected active payment gateway');
echo "PASS: no WooCommerce order created and no payment gateway active after all isolated browser tests.\n";
