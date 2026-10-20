<?php
/** Run only against an explicitly disposable, local WordPress installation. */
$root = getenv( 'AD_TEST_WP_ROOT' );
if ( ! $root || '1' !== getenv( 'AD_ALLOW_TEST_FIXTURES' ) ) {
    exit( "Set AD_TEST_WP_ROOT and AD_ALLOW_TEST_FIXTURES=1 for a disposable local installation.\n" );
}
$_SERVER['HTTP_HOST'] = getenv( 'AD_TEST_HOST' ) ?: 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
require rtrim( $root, '/' ) . '/wp-load.php';
if ( 'local' !== wp_get_environment_type() || ! class_exists( 'WooCommerce' ) || 'aliments-distinction' !== get_stylesheet() ) {
    throw new RuntimeException( 'Requires local WordPress, active WooCommerce and Aliments Distinction theme.' );
}
function ad_verify( $condition, $message ) {
    if ( ! $condition ) { throw new RuntimeException( $message ); }
    echo 'PASS ' . $message . "\n";
}
$ids = array();
$old_featured = get_theme_mod( 'ad_featured_product_id', 0 );
try {
    $simple = new WC_Product_Simple();
    $simple->set_name( 'AD temporary integration fixture' );
    $simple->set_status( 'publish' );
    $simple->set_regular_price( '225' );
    $simple->set_manage_stock( true );
    $simple->set_stock_quantity( 5 );
    $ids[] = $simple->save();
    $out = new WC_Product_Simple();
    $out->set_name( 'AD temporary unavailable fixture' );
    $out->set_status( 'publish' );
    $out->set_regular_price( '30' );
    $out->set_stock_status( 'outofstock' );
    $ids[] = $out->save();
    $variable = new WC_Product_Variable();
    $variable->set_name( 'AD temporary variation fixture' );
    $variable->set_status( 'publish' );
    $attribute = new WC_Product_Attribute();
    $attribute->set_name( 'Portion' );
    $attribute->set_options( array( '250 g' ) );
    $attribute->set_variation( true );
    $variable->set_attributes( array( $attribute ) );
    $ids[] = $variable->save();
    $variation = new WC_Product_Variation();
    $variation->set_parent_id( $variable->get_id() );
    $variation->set_attributes( array( 'portion' => '250 g' ) );
    $variation->set_regular_price( '20' );
    $variation->set_status( 'publish' );
    $ids[] = $variation->save();
    WC_Product_Variable::sync( $variable->get_id() );
    ad_verify( current_theme_supports( 'woocommerce' ), 'WooCommerce theme support' );
    ad_verify( ad_collection_url( 'ad-does-not-exist' ) === ad_shop_url(), 'Missing collection fallback' );
    set_theme_mod( 'ad_featured_product_id', 0 );
    ad_verify( ! ad_featured_product(), 'Unconfigured featured fallback' );
    set_theme_mod( 'ad_featured_product_id', $simple->get_id() );
    ad_verify( ad_featured_product()->get_id() === $simple->get_id(), 'Featured real product' );
    WC()->cart->empty_cart();
    ad_verify( (bool) WC()->cart->add_to_cart( $simple->get_id(), 2 ), 'Simple add to cart' );
    WC()->cart->calculate_totals();
    ad_verify( 450.0 === (float) WC()->cart->get_subtotal(), 'Subtotal 2 x 225' );
    ad_verify( ! WC()->cart->add_to_cart( $out->get_id() ), 'Out-of-stock rejection' );
    ad_verify( (bool) WC()->cart->add_to_cart( $variable->get_id(), 1, $variation->get_id(), $variation->get_variation_attributes() ), 'Variation add to cart' );
    ad_verify( 3 === ad_cart_count(), 'Header cart count' );
    $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );
    ad_verify( str_contains( $fragments['span.ad-cart-count'], '>3<' ), 'AJAX cart fragment' );
    WC()->cart->empty_cart();
    ad_verify( WC()->cart->is_empty(), 'Empty cart' );
} finally {
    WC()->cart->empty_cart();
    wc_clear_notices();
    set_theme_mod( 'ad_featured_product_id', $old_featured );
    foreach ( array_reverse( $ids ) as $id ) { $product = wc_get_product( $id ); if ( $product ) { $product->delete( true ); } }
}
echo 'WordPress ' . $wp_version . ' / WooCommerce ' . WC_VERSION . ' / PHP ' . PHP_VERSION . "\n";
