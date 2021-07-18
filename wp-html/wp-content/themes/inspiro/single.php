<?php
/**
 * The Template for displaying all single posts.
 */

get_header(); ?>

    <main id="main" class="site-main container-fluid <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?>" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'content', 'single' ); ?>

            <?php if (option::get('post_comments') == 'on') : ?>

                <?php comments_template(); ?>

            <?php endif; ?>

        <?php endwhile; // end of the loop. ?>


        <?php if ( option::is_on( 'post_nextprev' ) ) { ?>

            <?php

                $previous_post = get_previous_post();

                if ($previous_post != NULL ) {

                $prev_image = wp_get_attachment_image_src( get_post_thumbnail_id($previous_post->ID), 'entry-cover' );

                if (!empty ($prev_image))  { ?>

                    <div class="previous-post-cover">

                        <a href="<?php echo get_permalink($previous_post->ID); ?>" title="<?php echo $previous_post->post_title; ?>">

                            <div class="previous-info">

                                <?php if (!empty ($prev_image)) { ?>

                                    <div class="previous-cover" style="background-image: url('<?php echo $prev_image[0]; ?>')"></div><!-- .previous-cover -->

                                <?php } ?>

                                <div class="previous-content">

                                    <h4><?php _e('Previous Post', 'wpzoom'); ?></h4>

                                    <h3><span><?php echo $previous_post->post_title; ?></span></h3>

                                </div>

                            </div>

                        </a>

                    </div><!-- /.nextprev -->

            <?php }

            } ?>

        <?php } ?>

    </main><!-- #main -->

<?php get_footer(); ?>