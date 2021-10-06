<?php
/**
 Template Name: Sidebar on the Right
 */

get_header(); ?>

<main id="main" class="site-main <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?>" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php
            /**
             * The template used for displaying page content in page.php
             */

            extract(get_single_background_vars($post, 'wpzoom_posts_single'));
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( (has_post_thumbnail() || !empty( $has_video_background ) ? ' has-post-cover' : '') ); ?>>
                <div <?php if($is_vimeo_pro) echo $vimeo_style; ?> class="<?php if($is_vimeo_pro) echo 'is-vimeo-pro-slide'; ?> entry-cover<?php if ( option::get( 'page_overlay' ) == 'off' ) { echo " no-overlay"; } ?><?php if ( option::get( 'page_post_fullheader' ) == 'on' ) { echo " cover-fullheight"; } ?>"
                    <?php if ( $is_formstone && ( $is_video_slide || $is_video_external ) ): ?>
                        data-formstone-options='<?php echo json_encode( $encode_array ); ?>'
                    <?php endif; ?>
                    <?php if ( $is_vimeo_pro ): ?>
                        class="is-vimeo-pro-slide"
                        data-vimeo-options='<?php echo json_encode( $vimeo_player_args ); ?>'
                    <?php endif; ?>
                >
                    <?php if ( $has_video_background && option::get( 'page_overlay' ) == 'on' ) : ?>
                        <div class="slide-background-overlay"></div>
                    <?php endif; ?>
                    <?php
                        $entryCoverBackground = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'entry-cover' );
                        $small_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'featured-small');
                        $style = ' data-smallimg="' . $small_image_url[0] . '" data-bigimg="' . $entryCoverBackground[0] . '"';
                        $style .= 'style="background-image:url(\''. $small_image_url[0] .'\')"';
                    ?>

                    <?php if ( !empty( $entryCoverBackground ) || !empty( $has_video_background ) ) : ?>

                        <div class="entry-cover-image" <?php echo $style; ?>></div>

                    <?php endif; ?>

                    <header class="entry-header">
                        <div class="entry-info wpz_full_info">
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </div>
                    </header><!-- .entry-header -->
                    <!-- single portfolio video background controls -->
                    <div class="background-video-buttons-wrapper">

                        <?php if ( $show_play_button || ! $autoplay ): ?>
                            <a class="wpzoom-button-video-background-play display-none"><?php _e( 'Play', 'wpzoom' ); ?></a>
                            <a class="wpzoom-button-video-background-pause display-none"><?php _e( 'Pause', 'wpzoom' ); ?></a>
                        <?php endif; ?>

                        <?php if ( $show_sound_button ): ?>
                            <a class="wpzoom-button-sound-background-unmute display-none"><?php _e( 'Unmute', 'wpzoom' ); ?></a>
                            <a class="wpzoom-button-sound-background-mute display-none"><?php _e( 'Mute', 'wpzoom' ); ?></a>
                        <?php endif; ?>

                    </div>
                    <!-- #single portfolio video background controls -->
                </div><!-- .entry-cover -->

                <div class="entry_wrapper">

                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'wpzoom' ),
                                'after'  => '</div>',
                            ) );
                        ?>
                    </div><!-- .entry-content -->

                    <div class="wpz_post_sidebar">
                        <?php dynamic_sidebar( 'blog-sidebar' ); ?>
                    </div>

                </div>

            </article><!-- #post-## -->

            <?php if (option::get('comments_page') == 'on') { ?>
                <?php comments_template(); ?>
            <?php } ?>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->

<?php get_footer(); ?>
