<?php if (!defined('FW')) die( 'Forbidden' ); ?>

<div class="portfolio-showcase">

    <?php

    $term_id   = (int) $atts['category'];

    $posts_per_page = (int) $atts['posts_number'];
    if ( $posts_per_page == 0 ) {
        $posts_per_page = - 1;
    }


    if ( $term_id == 0 ) {
        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'portfolio_item',
            'orderby' =>'menu_order date'
        );
    } else {
        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'portfolio_item',
            'orderby'        => 'menu_order date',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'portfolio',
                    'field'    => 'id',
                    'terms'    => $term_id
                )
            )
        );
    }


    $wp_query = new WP_Query( $args );

    $col_number                  = (int) $atts['columns'];
    $show_lightbox_image_caption = (int) ( $atts['lightbox_caption'] == 'lightbox_caption_enable' );

    ?>

    <?php if ( $wp_query->have_posts() ) : ?>


            <?php if ( $atts['masonry'] == 'masonry_show') { ?>
                <div id="portfolio-masonry">
            <?php } ?>


                <div class="portfolio-grid<?php if ( $atts['spacing'] == 'spacing_show') { ?> portfolio_with_space<?php } ?> col_no_<?php echo $col_number; ?>">

                    <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

                        <?php

                        $post_thumbnail = get_the_post_thumbnail_url(get_the_ID());
                        $video_background_popup_url = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_popup_url', true);
                        $background_popup_url = !empty($video_background_popup_url) ? $video_background_popup_url : $post_thumbnail;
                        $video_background_popup_url_mp4 = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_popup_url_mp4', true);
                        $video_background_popup_url_webm = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_popup_url_webm', true);
                        $video_background_popup_video_type = get_post_meta(get_the_ID(), 'wpzoom_portfolio_popup_video_type', true);
                        $popup_video_type = !empty($video_background_popup_video_type) ? $video_background_popup_video_type : 'external_hosted';

                        $video_background_mp4 = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_background_mp4', true);
                        $video_background_webm = get_post_meta(get_the_ID(), 'wpzoom_portfolio_video_background_webm', true);
                        $is_video_popup = $video_background_popup_url_mp4 || $video_background_popup_url_webm;
                        $is_video_background_source = $video_background_mp4 || $video_background_webm;

                        $background_video = $atts['video_bg'] == 'video_bg_enable';
                        $is_video_background = $background_video && $is_video_background_source;

                        $final_background_src = !empty($video_background_mp4) ? $video_background_mp4 : $video_background_webm;
                        $popup_final_external_src = !empty($video_background_popup_url_mp4) ? $video_background_popup_url_mp4 : $video_background_popup_url_webm;

                        $articleClass = ( ! has_post_thumbnail() && !$is_video_background ) ? 'no-thumbnail ' : '';

                        $portfolios = wp_get_post_terms( get_the_ID(), 'portfolio' );

                        if ( $atts['masonry'] == 'masonry_show') {
                            $size = "portfolio_item-masonry";
                        }
                        else {
                            $size = "portfolio_item-thumbnail";
                        }


                        if ( $is_video_background ) {
                            $filetype             = wp_check_filetype( $final_background_src );
                            $video_atts           = array(
                                'loop',
                                'muted',
                                //'preload="auto"',
                                'poster="' . esc_attr( get_the_post_thumbnail_url( get_the_ID(), $size ) ) . '"'
                            );
                            $video_atts           = implode( ' ', $video_atts );
                            $is_video_popup_class = $is_video_background ? ' is-portfolio-gallery-video-background' : '';
                            $articleClass .= $is_video_popup_class;
                        }

                        if ( is_array( $portfolios ) ) {
                            foreach ( $portfolios as $portfolio ) {
                                $articleClass .= ' portfolio_' . $portfolio->term_id . '_item ';
                            }
                        }
                        ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class( $articleClass ); ?>>

                            <?php if ( $atts['lightbox'] == 'lightbox_enable') { ?>

                               <div class="entry-thumbnail-popover">
                                   <div class="entry-thumbnail-popover-content lightbox_popup_insp popover-content--animated" data-show-caption="<?php echo $show_lightbox_image_caption ?>"><!-- start lightbox --><?php if ( $popup_video_type === 'self_hosted' && $is_video_popup ): ?>
                                           <div id="zoom-popup-<?php echo the_ID(); ?>" class="animated slow mfp-hide">

                                               <div class="mfp-iframe-scaler">

                                                   <?php
                                                   echo wp_video_shortcode(
                                                       array(
                                                           'src'  => $popup_final_external_src,
                                                           'preload' => 'none',
                                                           //'autoplay' => 'on'
                                                       ) );
                                                   ?>

                                                   <?php if ( $show_lightbox_image_caption ): ?>
                                                       <div class="mfp-bottom-bar">
                                                           <div class="mfp-title">
                                                               <a href="<?php echo esc_url( get_permalink() ); ?>"
                                                                  title="<?php echo esc_attr( get_the_title() ); ?>">
                                                                   <?php the_title(); ?>
                                                               </a>
                                                           </div>
                                                       </div>
                                                   <?php endif; ?>

                                               </div>
                                           </div>
                                           <a href="#zoom-popup-<?php echo the_ID(); ?>"
                                              class="mfp-inline portfolio-popup-video"></a>
                                       <?php elseif ( ! empty( $video_background_popup_url ) ): ?><a
                                              class="mfp-iframe portfolio-popup-video"
                                              href="<?php echo $video_background_popup_url ?>"></a>
                                      <?php else: ?>
                                           <?php if(has_post_thumbnail() && !option::is_on('lightbox_video_only') ): ?>
                                               <a
                                                  class="mfp-image portfolio-popup-video popup_image_insp"
                                                  href="<?php echo $post_thumbnail ?>"></a>
                                           <?php endif; ?>
                                       <?php endif; ?>

                                      <div class="entry-meta">
                                           <h3 class="portfolio_item-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h3>
                                       </div>

                                   </div>
                                </div>

                                <?php if ( $is_video_background ): ?>
                                    <video class="portfolio-gallery-video-background" <?php echo $video_atts ?>
                                           style=" width:100%; height:auto;vertical-align: middle; display:block;">
                                        <source src="<?php echo $final_background_src ?>" type="<?php echo $filetype['type'] ?>">
                                    </video>

                                    <?php the_post_thumbnail( $size ); ?>

                                <?php elseif ( has_post_thumbnail() ): ?>

                                    <?php the_post_thumbnail( $size ); ?>

                               <?php else: ?>

                                   <img width="600" height="400" src="<?php echo get_template_directory_uri() . '/images/portfolio_item-placeholder.gif'; ?>">

                               <?php endif; ?>

                            <?php } else { ?>

                                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">

                                    <div class="entry-thumbnail-popover">
                                        <div class="entry-thumbnail-popover-content popover-content--animated">

                                            <?php the_title( '<h3 class="portfolio_item-title">', '</h3>' ); ?>

                                            <?php if ( $atts['excerpt'] == 'excerpt_show') { ?>
                                                <?php the_excerpt(); ?>
                                            <?php } ?>


                                            <?php if ( $atts['button'] == 'button_show') { ?>
                                                <span class="btn">
                                                    <?php echo $atts['button_label']; ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <?php if ( $is_video_background ): ?>
                                           <video class="portfolio-gallery-video-background" <?php echo $video_atts ?>
                                                  style=" width:100%; height:auto;vertical-align: middle; display:block;">
                                               <source src="<?php echo $final_background_src ?>" type="<?php echo $filetype['type'] ?>">
                                           </video>

                                           <?php the_post_thumbnail( $size ); ?>

                                    <?php elseif ( has_post_thumbnail() ): ?>

                                       <?php the_post_thumbnail( $size ); ?>

                                    <?php else: ?>

                                        <img width="600" height="400" src="<?php echo get_template_directory_uri() . '/images/portfolio_item-placeholder.gif'; ?>">

                                    <?php endif; ?>

                                </a>

                            <?php } ?>

                        </article><!-- #post-## -->

                    <?php endwhile; ?>

                </div>


            <?php if ( $atts['masonry'] == 'masonry_show') { ?>
                </div>
            <?php } ?>


    <?php endif; ?>


    <?php if ( $atts['button_all'] == 'button_all_show') { ?>

        <div class="portfolio-view_all-link">
            <a href="<?php echo esc_attr($atts['link']) ?>" target="<?php echo esc_attr($atts['target']) ?>" class="btn">
            <?php echo $atts['label']; ?>
            </a>
        </div>

    <?php } ?>

</div>