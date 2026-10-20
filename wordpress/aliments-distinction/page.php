<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content" class="container ad-content">
<?php while ( have_posts() ) : the_post(); ?>
<article <?php post_class(); ?>>
<h1 class="t-h2 ad-page-title"><?php the_title(); ?></h1>
<?php the_content(); wp_link_pages(); ?>
</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
