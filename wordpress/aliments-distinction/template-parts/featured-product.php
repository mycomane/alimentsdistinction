<?php defined( 'ABSPATH' ) || exit;
$ad_featured = ad_featured_product();
if ( ! $ad_featured ) { return; } ?>
<section class="featured"><div class="container"><div class="featured__inner">
<div class="featured__image"><?php echo wp_kses_post( $ad_featured->get_image( 'large' ) ); ?></div>
<div><span class="t-label">Coup de cœur</span>
<h2 class="t-h2"><?php echo esc_html( $ad_featured->get_name() ); ?></h2>
<div class="featured__price"><?php echo wp_kses_post( $ad_featured->get_price_html() ); ?></div>
<div class="t-body"><?php echo wp_kses_post( wpautop( $ad_featured->get_short_description() ) ); ?></div>
<a class="btn btn-blush" href="<?php echo esc_url( get_permalink( $ad_featured->get_id() ) ); ?>">Voir le produit</a>
</div></div></div></section>
