<?php
/**
 Template Name: Full-width (Unyson Builder)
 */

get_header(); ?>

<main id="main" class="site-main <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?>" role="main">

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="builder-wrap full-width">

            <?php get_template_part( 'content', 'page' ); ?>

        </div><!-- .full-width -->

    <?php endwhile; // end of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
