<?php
/** Catalog, category and single-product shell. WooCommerce retains its native templates and hooks. */
defined( 'ABSPATH' ) || exit;
get_header(); ?>
<main id="main-content" class="container ad-content ad-commerce">
<?php if ( ! is_product() ) { get_product_search_form(); } ?>
<?php if ( function_exists( 'woocommerce_content' ) ) { woocommerce_content(); } ?>
</main>
<?php get_footer(); ?>
