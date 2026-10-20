<?php
/** Theme integration. No activation-time content or commerce settings mutations. */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'aliments-distinction', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'woocommerce', array( 'product_grid' => array( 'default_columns' => 3, 'default_rows' => 4 ) ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

function ad_asset( $path ) {
    return get_theme_file_uri( '/assets/' . ltrim( $path, '/' ) );
}
add_action( 'wp_enqueue_scripts', function () {
    $version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'ad-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500&display=swap', array(), null );
    wp_enqueue_style( 'ad-design', ad_asset( 'css/design.css' ), array( 'ad-fonts' ), $version );
    wp_enqueue_style( 'ad-theme', get_stylesheet_uri(), array( 'ad-design' ), $version );
    wp_enqueue_style( 'ad-commerce', ad_asset( 'css/commerce.css' ), array( 'ad-theme' ), $version );
    wp_enqueue_script( 'ad-theme', ad_asset( 'js/theme.js' ), array(), $version, true );
} );

function ad_home_section( $section ) { return home_url( '/#' . sanitize_title( $section ) ); }
function ad_shop_url() {
    $url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
    return $url && home_url( '/' ) !== $url ? $url : ad_home_section( 'collections' );
}
function ad_cart_url() { return function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : ad_home_section( 'collections' ); }
function ad_account_url() { return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : ad_home_section( 'contact' ); }
function ad_search_url() { return add_query_arg( array( 's' => '', 'post_type' => 'product' ), home_url( '/' ) ); }
function ad_cart_count() { return function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0; }
function ad_collection_url( $slug ) {
    $term = taxonomy_exists( 'product_cat' ) ? get_term_by( 'slug', $slug, 'product_cat' ) : false;
    $url = $term ? get_term_link( $term ) : false;
    return $url && ! is_wp_error( $url ) ? $url : ad_shop_url();
}
function ad_page_url( $slug, $fallback ) {
    $page = get_page_by_path( $slug );
    return $page && 'publish' === $page->post_status ? get_permalink( $page ) : ad_home_section( $fallback );
}
function ad_featured_product() {
    if ( ! function_exists( 'wc_get_product' ) ) { return false; }
    $product = wc_get_product( absint( get_theme_mod( 'ad_featured_product_id', 0 ) ) );
    return $product && 'publish' === $product->get_status() && $product->is_visible() ? $product : false;
}
add_action( 'customize_register', function ( $customizer ) {
    $customizer->add_section( 'ad_home', array( 'title' => __( 'Accueil Distinction', 'aliments-distinction' ) ) );
    $customizer->add_setting( 'ad_featured_product_id', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
    $customizer->add_control( 'ad_featured_product_id', array( 'section' => 'ad_home', 'type' => 'number', 'label' => __( 'Identifiant du produit vedette WooCommerce', 'aliments-distinction' ) ) );
    $customizer->add_setting( 'ad_newsletter_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $customizer->add_control( 'ad_newsletter_url', array( 'section' => 'ad_home', 'type' => 'url', 'label' => __( 'Lien vers le formulaire d’infolettre', 'aliments-distinction' ) ) );
} );
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    $fragments['span.ad-cart-count'] = '<span class="ad-cart-count">' . esc_html( ad_cart_count() ) . '</span>';
    return $fragments;
} );
add_filter( 'body_class', function ( $classes ) {
    if ( ! is_front_page() ) { $classes[] = 'ad-interior'; }
    return $classes;
} );
