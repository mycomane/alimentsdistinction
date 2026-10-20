<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content" class="container ad-content">
<?php if ( is_search() ) : ?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="ad-search">
<label for="ad-search-field"><?php esc_html_e( 'Rechercher des produits', 'aliments-distinction' ); ?></label>
<input id="ad-search-field" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">
<input type="hidden" name="post_type" value="product"><button type="submit" class="btn btn-blush">Rechercher</button>
</form>
<?php endif; ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article <?php post_class( 'ad-article' ); ?>>
<h1 class="t-h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
<?php if ( is_singular() ) { the_content(); wp_link_pages(); } else { the_excerpt(); } ?>
</article>
<?php endwhile; the_posts_pagination(); else : ?>
<h1 class="t-h2"><?php esc_html_e( 'Aucun résultat', 'aliments-distinction' ); ?></h1>
<p><?php esc_html_e( 'Essayez un autre terme ou découvrez nos collections.', 'aliments-distinction' ); ?></p>
<a class="btn btn-outline-blush" href="<?php echo esc_url( ad_shop_url() ); ?>">Nos collections</a>
<?php endif; ?>
</main>
<?php get_footer(); ?>
