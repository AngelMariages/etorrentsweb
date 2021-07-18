<?php

extract(get_single_background_vars($post, 'wpzoom_posts_single'));

$template = get_post_meta($post->ID, 'wpzoom_post_template', true);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( ( (option::is_on( 'single_post_header_image' )) && (has_post_thumbnail() || !empty( $has_video_background ) ) ? ' has-post-cover' : '') ); ?>>
    <?php if (option::is_on( 'single_post_header_image' ) ) { ?><div
        <?php if($is_vimeo_pro) echo $vimeo_style; ?>
        class="<?php if($is_vimeo_pro) echo 'is-vimeo-pro-slide'; ?> entry-cover<?php if ( option::get( 'post_overlay' ) == 'off' ) { echo " no-overlay"; } ?><?php if ( option::get( 'single_post_fullheader' ) == 'on' ) { echo " cover-fullheight"; } ?>"
        <?php if ( $is_formstone && ( $is_video_slide || $is_video_external ) ): ?>
            data-formstone-options='<?php echo json_encode( $encode_array ); ?>'
        <?php endif; ?>
        <?php if ( $is_vimeo_pro ): ?>
            class="is-vimeo-pro-slide"
            data-vimeo-options='<?php echo json_encode( $vimeo_player_args ); ?>'
        <?php endif; ?>
    >
        <?php if ( $has_video_background && option::get( 'post_overlay' ) == 'on' ) : ?>
            <div class="slide-background-overlay"></div>
        <?php endif; ?><?php } ?>
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
            <div class="entry-info<?php if ( is_active_sidebar( 'blog-sidebar' ) ) { ?> wpz_full_info<?php } ?>">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                <div class="entry-meta">
                    <?php if ( option::is_on( 'post_category' ) ) : ?><span class="entry-category"><?php _e( 'in', 'wpzoom' ); ?> <?php the_category( ', ' ); ?></span><?php endif; ?>
                    <?php if ( option::is_on( 'post_date' ) )     : ?><p class="entry-date"><?php _e( 'on', 'wpzoom' ); ?> <?php printf( '<time class="entry-date" datetime="%1$s">%2$s</time> ', esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) ); ?></p> <?php endif; ?>
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



    <?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>
        <div class="entry_wrapper">
    <?php endif; ?>

        <div class="entry-content">
            <?php the_content(); ?>
        </div><!-- .entry-content -->


    <?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>

            <div class="wpz_post_sidebar">
                <?php dynamic_sidebar( 'blog-sidebar' ); ?>
            </div>

        </div>

        <div class="clear"></div>

    <?php endif; ?>

    <footer class="entry-footer">

        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'wpzoom' ),
            'after'  => '</div>',
        ) );
        ?>


        <?php if ( option::is_on( 'post_tags' ) ) : ?>

            <?php
            the_tags(
                '<div class="tag_list"><h4 class="section-title">' . __( 'Tags', 'wpzoom' ). '</h4>',
                '<span class="separator">,</span>',
                '</div>'
            );
            ?>

        <?php endif; ?>

        <?php if ( option::is_on( 'post_share' ) ) : ?>

            <div class="share">

                <h4 class="section-title"><?php _e( 'Share', 'wpzoom' ); ?></h4>

                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" title="<?php esc_attr_e( 'Tweet this on Twitter', 'wpzoom' ); ?>" class="twitter"><?php echo esc_html( option::get( 'post_share_label_twitter' ) ); ?></a>

                <a href="https://facebook.com/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>&t=<?php echo urlencode( get_the_title() ); ?>" target="_blank" title="<?php esc_attr_e( 'Share this on Facebook', 'wpzoom' ); ?>" class="facebook"><?php echo esc_html( option::get( 'post_share_label_facebook' ) ); ?></a>

                <a href="https://www.linkedin.com/cws/share?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" title="<?php esc_attr_e( 'Share on LinkedIn', 'wpzoom' ); ?>" class="wpz_linkedin"><?php echo option::get( 'post_share_label_linkedin' ); ?></a>

            </div>

        <?php endif; ?>


        <?php if ( option::is_on( 'post_author' ) ) : ?>

            <div class="post_author">

                <?php echo get_avatar( get_the_author_meta( 'ID' ) , 65 ); ?>

                <span><?php _e( 'Written by', 'wpzoom' ); ?></span>

                <?php the_author_posts_link(); ?>

            </div>

        <?php endif; ?>


        <?php edit_post_link( __( 'Edit', 'wpzoom' ), '<span class="edit-link">', '</span>' ); ?>

    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
