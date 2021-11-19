<?php
/**
 * The template for displaying all pages.
 */

get_header(); ?>

<main id="main" class="site-main <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?>" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'content', 'page' ); ?>

            <?php if (option::get('comments_page') == 'on') { ?>
                <?php comments_template(); ?>
            <?php } ?>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->

<?php get_footer(); ?>