<?php
/**
 Template Name: Sidebar on the Right
 Template Post Type: portfolio_item
 */

get_header(); ?>

<main id="main" class="site-main container-fluid" role="main">

    <?php while ( have_posts() ) : the_post();

        $slides = get_post_meta( get_the_ID(), 'wpzoom_slider', true );
        $hasSlider = is_array( $slides ) && count( $slides ) > 0;
        $slide_counter = 0;
        $post_thumbnail = get_the_post_thumbnail_url(get_the_ID());
        $video_background_popup_url = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_popup_url', true);
        $background_popup_url = !empty($video_background_popup_url) ? $video_background_popup_url : $post_thumbnail;
        $video_background_popup_url_mp4 = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_popup_url_mp4', true);
        $video_background_popup_url_webm = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_popup_url_webm', true);
        $video_background_popup_video_type = get_post_meta(get_the_ID(), 'wpzoom_portfolio_popup_video_type', true);
        $popup_video_type = !empty($video_background_popup_video_type) ? $video_background_popup_video_type : 'external_hosted';
        $is_video_popup = $video_background_popup_url_mp4 || $video_background_popup_url_webm;
        $popup_final_external_src = !empty($video_background_popup_url_mp4) ? $video_background_popup_url_mp4 : $video_background_popup_url_webm;

        $video_director = get_post_meta( get_the_ID(), 'su_portfolio_item_director', true );
        $video_year = get_post_meta( get_the_ID(), 'su_portfolio_item_year', true );
        $video_client = get_post_meta( get_the_ID(), 'su_portfolio_item_client', true );
        $video_skills = get_post_meta( get_the_ID(), 'su_portfolio_item_skills', true );

        extract(get_single_background_vars($post, 'wpzoom_portfolio_single'));

        $has_thumb_class = '';
        $has_slider_class = '';

        if ( option::is_on( 'portfolio_post_header_image' ) ) {

            $has_thumb_class = has_post_thumbnail() || !empty( $has_video_background ) ? 'has-post-cover' : '';
            $has_slider_class = $hasSlider ? 'has-post-cover' : '' ;

        }

        $has_thumb_no_slider = ( !empty($has_thumb_class) && (!$hasSlider) ) ? 'full-noslider' : '';

        /* single portfolio video background template variables */
        $vimeo_background_img       = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'entry-cover' );
        $small_vimeo_background_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-small' );

        $vimeo_style = ' data-smallimg="' . $small_vimeo_background_img[0] . '" data-bigimg="' . $vimeo_background_img[0] . '"';
        $vimeo_style .= ' style="background-image:url(\'' . $small_vimeo_background_img[0] . '\')"';
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( array($has_thumb_class, $has_slider_class, $has_thumb_no_slider) ); ?>>
            <!-- single portfolio video background atts for vimeo and formstone -->
            <div
                <?php if($is_vimeo_pro) echo $vimeo_style; ?>
                class="<?php if($is_vimeo_pro && !$hasSlider ) echo 'is-vimeo-pro-slide'; ?> entry-cover<?php if ( option::get( 'portfolio_post_overlay' ) == 'off' ) { echo " no-overlay"; } ?><?php if ( option::get( 'portfolio_post_fullheader' ) == 'on' ) { echo " cover-fullheight"; } ?>"
                <?php if ( !$hasSlider && $is_formstone && ( $is_video_slide || $is_video_external ) ): ?>
                    data-formstone-options='<?php echo json_encode( $encode_array ); ?>'
                <?php endif; ?>
                <?php if ( !$hasSlider && $is_vimeo_pro ): ?>
                    class="is-vimeo-pro-slide"
                    data-vimeo-options='<?php echo json_encode( $vimeo_player_args ); ?>'
                <?php endif; ?>
            >
                <!-- #single portfolio video background atts for vimeo and formstone -->
                <?php if ( $has_video_background && option::get( 'portfolio_post_overlay' ) == 'on' ) : ?>
                    <div class="slide-background-overlay"></div>
                <?php endif; ?>
                <?php
                    $entryCoverBackground = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'entry-cover' );
                    $small_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'featured-small');
                    $style = ' data-smallimg="' . $small_image_url[0] . '" data-bigimg="' . $entryCoverBackground[0] . '"';
                    $style .= 'style="background-image:url(\''. $small_image_url[0] .'\')"';

                ?>

                <?php if ( $hasSlider ) :  ?>

                   <div id="slider">
                        <ul class="slides">

                            <?php foreach ( $slides as $slide ) : ?>

                                <?php if ( $slide['slideType'] == 'image' ) :
                                    $slide_counter++;
                                    $img = inspiro_get_slide_image( $slide );
                                    $style = ' data-smallimg="' . $img['small_image_url'] . '" data-bigimg="' . $img['large_image_url'] . '"';

                                    if ($slide_counter === 1) {
                                        $style .= ' style="background-image:url(\'' . $img['large_image_url'] . '\')"';
                                    }
                                    ?>

                                    <li<?php echo $style; ?>>
                                        <?php if ( option::is_on( 'portfolio_post_overlay' ) ) { ?><div class="slide-background-overlay"></div><?php } ?>
                                        <div class="li-wrap">

                                            <?php if (! empty( $img['caption'] ) ) { ?>
                                                <h3><?php echo esc_html( $img['caption'] ); ?></h3>
                                            <?php } ?>

                                            <?php if (! empty( $img['description'] )) { ?>
                                                <div class="excerpt"><?php echo $img['description']; ?></div>
                                            <?php } ?>

                                        </div>
                                    </li>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </ul>

                        <div id="scroll-to-content" title="<?php esc_attr_e( 'Scroll to Content', 'wpzoom' ); ?>">
                            <?php _e( 'Scroll to Content', 'wpzoom' ); ?>
                        </div>


                    </div>

                <?php elseif ( !empty( $entryCoverBackground ) ) : ?>

                    <?php if (option::is_on( 'portfolio_post_header_image' ) ) { ?>

                        <div class="entry-cover-image" <?php echo $style; ?>></div>

                    <?php } ?>

                <?php endif; ?>

                <header class="entry-header">

                    <?php if ( option::is_on( 'portfolio_post_lightbox' ) && option::is_on( 'portfolio_post_header_image' ) ) { ?>

                        <?php if ( $popup_video_type === 'self_hosted' && $is_video_popup ): ?>
                            <div id="zoom-popup-<?php echo the_ID(); ?>" class="animated slow mfp-hide"
                                 data-src="<?php echo $popup_final_external_src ?>">

                                <div class="mfp-iframe-scaler">

                                    <?php
                                    echo wp_video_shortcode(
                                        array(
                                            'src'  => $popup_final_external_src,
                                            'preload' => 'none',
                                            //'autoplay' => 'on'
                                        ) );
                                    ?>

                                </div>
                            </div>
                            <a href="#zoom-popup-<?php echo the_ID(); ?>" class="mfp-inline slow pulse animated portfolio-popup-video"></a>

                        <?php elseif ( ! empty( $video_background_popup_url ) ): ?>
                            <a class="mfp-iframe portfolio-popup-video animated slow animated pulse"
                               href="<?php echo $video_background_popup_url ?>"></a>
                        <?php endif; ?>

                    <?php } ?>

                    <div class="entry-info">

                        <div class="entry-meta">

                            <?php if ( option::is_on( 'portfolio_category' ) ) : ?>

                                <?php if ( is_array( $tax_menu_items = get_the_terms( get_the_ID(), 'portfolio' ) ) ) : ?>
                                    <?php foreach ( $tax_menu_items as $tax_menu_item ) : ?>
                                        <a href="<?php echo get_term_link( $tax_menu_item, $tax_menu_item->taxonomy ); ?>"><?php echo $tax_menu_item->name; ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>

                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                        <div class="entry-meta">
                            <?php if ( option::is_on( 'portfolio_date' ) ) printf( '<p class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></p>', esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) ); ?>
                        </div>
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


            <?php if ( is_active_sidebar( 'portfolio-sidebar' ) || ( $video_director || $video_year || $video_client || $video_skills ) ) : ?>
                <div class="entry_wrapper">
            <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->

            <?php if ( is_active_sidebar( 'portfolio-sidebar' ) || ( $video_director || $video_year || $video_client || $video_skills ) ) : ?>

                    <div class="wpz_post_sidebar">

                        <?php if (option::is_on('portfolio_details_top') && ( $video_director || $video_year || $video_client || $video_skills) ) { ?>

                            <div class="entry-details">

                                <div class="entry-meta">

                                    <ul>
                                        <?php if ($video_director) { ?>
                                           <li><span class="portfolio_item-director"><?php _e('Director', 'wpzoom'); ?></span> <?php echo $video_director; ?></li>
                                        <?php } ?>

                                        <?php if ($video_year) { ?>
                                           <li><span class="portfolio_item-year"><?php _e('Year of Production', 'wpzoom'); ?></span> <?php echo $video_year; ?></li>
                                        <?php } ?>

                                        <?php if ($video_client) { ?>
                                           <li><span class="portfolio_item-client"><?php _e('Client', 'wpzoom'); ?></span> <?php echo $video_client; ?></li>
                                        <?php } ?>

                                        <?php if ($video_skills) { ?>
                                           <li><span class="portfolio_item-skills"><?php _e('What we did', 'wpzoom'); ?></span> <?php echo nl2br (apply_filters('the_content', $video_skills) ); ?></li>
                                        <?php } ?>

                                    </ul>

                                </div>

                            </div>

                        <?php } ?>

                        <?php dynamic_sidebar( 'portfolio-sidebar' ); ?>
                    </div>

                </div>

                <div class="clear"></div>

            <?php endif; ?>


            <footer class="entry-footer">

                <?php if ( option::is_on( 'portfolio_share' ) ) : ?>

                    <div class="share">

                        <h4 class="section-title"><?php _e( 'Share', 'wpzoom' ); ?></h4>

                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" title="<?php esc_attr_e( 'Tweet this on Twitter', 'wpzoom' ); ?>" class="twitter"><?php echo esc_html( option::get( 'portfolio_share_label_twitter' ) ); ?></a>

                        <a href="https://facebook.com/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>&t=<?php echo urlencode( get_the_title() ); ?>" target="_blank" title="<?php esc_attr_e( 'Share this on Facebook', 'wpzoom' ); ?>" class="facebook"><?php echo esc_html( option::get( 'portfolio_share_label_facebook' ) ); ?></a>

                        <a href="https://www.linkedin.com/cws/share?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" title="<?php esc_attr_e( 'Share on LinkedIn', 'wpzoom' ); ?>" class="wpz_linkedin"><?php echo option::get( 'portfolio_share_label_linkedin' ); ?></a>


                    </div>

                <?php endif; ?>

                <?php if ( option::is_on( 'portfolio_author' ) ) : ?>

                    <div class="post_author">

                        <?php echo get_avatar( get_the_author_meta( 'ID' ) , 65 ); ?>

                        <span><?php _e( 'Written by', 'wpzoom' ); ?></span>

                        <?php the_author_posts_link(); ?>

                    </div>

                <?php endif; ?>


                <?php edit_post_link( __( 'Edit', 'wpzoom' ), '<span class="edit-link">', '</span>' ); ?>
            </footer><!-- .entry-footer -->
        </article><!-- #post-## -->

        <?php if ( option::is_on( 'portfolio_comments' ) ) : ?>

            <?php comments_template(); ?>

        <?php endif; ?>

    <?php endwhile; // end of the loop. ?>


        <?php if ( option::is_on( 'portfolio_nextprev' ) ) { ?>

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

                                    <h4><?php _e('Previous', 'wpzoom'); ?></h4>

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