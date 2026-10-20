<?php
/** Template Name: Commande Distinction (classique) */
defined( 'ABSPATH' ) || exit;
get_header(); ?>
<main id="main-content" class="container ad-content ad-commerce">
<h1 class="t-h2 ad-page-title"><?php the_title(); ?></h1>
<?php if ( class_exists( 'WooCommerce' ) ) { echo do_shortcode( '[woocommerce_checkout]' ); } else { esc_html_e( 'La commande sera disponible prochainement.', 'aliments-distinction' ); } ?>
</main>
<?php get_footer(); ?>
