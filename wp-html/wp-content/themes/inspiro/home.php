<?php
/**
 * The main template file.
 */

get_header(); ?>

<?php if ( option::is_on( 'featured_posts_show' ) && is_front_page() && $paged < 2) : ?>

    <?php get_template_part( 'wpzoom-slider' ); ?>

<?php endif; ?>

<main id="main" <?php if ( ! is_front_page() ) {  post_class( (has_post_thumbnail(get_option( 'page_for_posts' )) ? ' page-with-cover blog-with-post-cover' : '') ); } ?> role="main">

    <section class="blog-archive">

        <?php if ( ! is_front_page() ) { ?>

            <div class="blog-header-cover">
                <?php $entryCoverBackground = wp_get_attachment_image_src( get_post_thumbnail_id( get_option( 'page_for_posts' ) ), 'entry-cover' );  ?>

                <?php if ( !empty( $entryCoverBackground ) ) : ?>

                    <div class="blog-header-cover-image" style="background-image: url('<?php echo $entryCoverBackground[0] ?>');"></div>

                <?php endif; ?>

                <div class="blog-header-info">
                    <div class="entry-info">
                        <h2 class="section-title"> <?php echo get_the_title( get_option( 'page_for_posts' ) ); ?></h2>

                        <div class="entry-header-excerpt">
                            <?php $blog_page = get_post( get_option( 'page_for_posts' ) );

                            $excerpt = ( $blog_page->post_excerpt ) ? $blog_page->post_excerpt : $blog_page->post_content;

                            echo $excerpt; ?>
                        </div>

                    </div>
                </div><!-- .blog-header-info -->

            </div><!-- .blog-header-cover -->

        <?php } ?>


        <?php if ( option::get( 'layout_blog_page' ) !== 'full' && is_active_sidebar( 'blog-sidebar' ) ) : ?>
            <div class="entry_wrapper">
        <?php endif; ?>

            <section class="recent-posts<?php if (option::get('post_view_blog') == '3-columns') { echo " blog_3_col"; } ?>">

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

    </section><!-- .blog-archive -->


</main><!-- .site-main -->

<?php
get_footer();
