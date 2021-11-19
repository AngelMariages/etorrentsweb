<?php
/**
 * Template Name: Homepage (Widgetized)
 */

get_header(); ?>

<?php if ( option::is_on( 'featured_posts_show' ) ) : ?>

    <?php get_template_part( 'wpzoom-slider' ); ?>

<?php endif; ?>

<div class="widgetized-section">
    <?php dynamic_sidebar( 'home-full' ); ?>
</div>

<?php
get_footer();
