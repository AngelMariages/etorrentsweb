<?php
/**
 * The main template file.
 */

get_header(); ?>

<?php if ( option::is_on( 'featured_posts_show' ) && is_front_page() && $paged < 2) : ?>

    <?php get_template_part( 'wpzoom-slider' ); ?>

<?php endif; ?>

<main id="main" class="site-main<?php if (inspiro_maybeWithCover()) echo ' page-with-cover'; ?>" role="main">

    <?php if ( option::get( 'layout_blog_page' ) !== 'full' && is_active_sidebar( 'blog-sidebar' ) ) : ?>
        <div class="entry_wrapper">
    <?php endif; ?>

        <section class="recent-posts<?php if (option::get('post_view_blog') == '3-columns') { echo " blog_3_col"; } ?>">

            <h2 class="section-title">
                <?php if ( is_front_page() ) : ?>

                    <?php _e( 'Our Blog', 'wpzoom' ); ?>

                <?php else: ?>

                    <?php echo get_the_title( get_option( 'page_for_posts' ) ); ?>

                <?php endif; ?>
            </h2>

            <?php if ( have_posts() ) : ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php

                    get_template_part( 'content', get_post_format() );
                    ?>

                <?php endwhile; ?>

                <?php get_template_part( 'pagination' ); ?>

            <?php else: ?>

                <?php get_template_part( 'content', 'none' ); ?>

            <?php endif; ?>

        </section><!-- .recent-posts -->


    <?php if ( option::get( 'layout_blog_page' ) !== 'full' && is_active_sidebar( 'blog-sidebar' ) ) : ?>

            <div class="wpz_post_sidebar">
                <?php dynamic_sidebar( 'blog-sidebar' ); ?>
            </div>

        </div>

        <div class="clear"></div>

    <?php endif; ?>

</main><!-- .site-main -->

<?php
get_footer();
