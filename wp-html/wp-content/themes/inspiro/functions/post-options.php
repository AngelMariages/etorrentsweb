<?php


/* Registering metaboxes
============================================*/

add_action( 'admin_head-post-new.php', array( 'WPZOOM_Video_Thumb', 'admin_newpost_head' ), 100 );
add_action( 'admin_head-post.php', array( 'WPZOOM_Video_Thumb', 'admin_newpost_head' ), 100 );

add_action( 'admin_menu', 'wpzoom_options_box' );

function wpzoom_options_box() {

    add_meta_box( 'wpzoom_top_button', 'Slideshow Options', 'wpzoom_top_button_options', 'slider', 'side', 'high' );

    add_meta_box( 'inspiro_portfolio_item_meta', 'Optional Details', 'su_portfolio_meta_box_options', 'portfolio_item', 'side', 'high' );


    // add_meta_box( 'wpzoom_post_layout', 'Post Layout', 'wpzoom_post_layout_options', 'post', 'normal', 'high' );

    add_meta_box(
        'wpzoom_slideshow_video_settings',
        'Video Settings',
        'wpzoom_slideshow_video_settings',
        'slider',
        'normal',
        'high'
    );

    add_meta_box(
        'wpzoom_portfolio_video_settings',
        'Video Settings',
        'wpzoom_portfolio_video_settings',
        'portfolio_item',
        'normal',
        'high'
    );

    add_meta_box(
        'wpzoom_single_post_video_settings',
        'Video Settings',
        'wpzoom_single_post_video_settings',
        array('post','page'),
        'normal',
        'high'
    );

    $template_file = '';
    // get the id of current post/page
    if ( isset( $_GET['post'] ) || isset( $_POST['post_ID'] ) ) {
        $post_id = isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post_ID'];
    }

    // get the template file used (if a page)
    if ( isset( $post_id ) ) {
        $template_file = get_post_meta( $post_id, '_wp_page_template', true );
    }
    // if we are using the portfolio page template, add an additional meta box
    if ( isset($template_file) && ($template_file == 'portfolio/archive.php' || $template_file == 'portfolio/archive-clean.php' || $template_file == 'portfolio/archive-isotope.php' || $template_file == 'portfolio/archive-isotope-masonry.php' || $template_file == 'portfolio/archive-infinite.php'  ) ) {
        add_meta_box( 'wpzoom_dir_list_meta', 'Portfolio Options', 'wpzoom_dir_list_meta', 'page', 'normal', 'high' );
    }

    // if we are using the homepage page template, add an additional meta box
    if ( isset($template_file) && ($template_file == 'page-templates/homepage-builder.php' || $template_file == 'page-templates/homepage-builder-bb.php' || $template_file == 'page-templates/template-home-widgets.php' ) ) {
        add_meta_box( 'wpzoom_slide_category', 'Slider Category', 'wpzoom_slide_category', 'page', 'normal', 'high' );
    }
}


/**
 * Callback that prints out the HTML for the edit screen section on portfolio_item
 * post/post-edit pages. Extends ZOOM_Portfolio.
 *
 * @return void
 */

if ( ! function_exists( 'su_portfolio_meta_box_options' ) ) {

    function su_portfolio_meta_box_options() {
        global $post; ?>
        <fieldset>

            <p>
                <label for="su_portfolio_item_director" ><?php _e('Director:', 'wpzoom'); ?></label><br />
                <input style="width: 255px;" type="text" name="su_portfolio_item_director" id="su_portfolio_item_director" value="<?php echo get_post_meta( $post->ID, 'su_portfolio_item_director', true ); ?>"/>
            </p>

            <p>
                <label for="su_portfolio_item_year" ><?php _e('Year of Production:', 'wpzoom'); ?></label><br />
                <input style="width: 255px;" type="text" name="su_portfolio_item_year" id="su_portfolio_item_year" value="<?php echo get_post_meta( $post->ID, 'su_portfolio_item_year', true ); ?>"/>
            </p>

            <p>
                <label for="su_portfolio_item_client" ><?php _e('Client:', 'wpzoom'); ?></label><br />
                <input style="width: 255px;" type="text" name="su_portfolio_item_client" id="su_portfolio_item_client" value="<?php echo get_post_meta( $post->ID, 'su_portfolio_item_client', true ); ?>"/>
            </p>

            <p>
                <label for="su_portfolio_item_skills" ><?php _e('What we did:', 'wpzoom'); ?></label><br />
                <textarea style="height: 90px; width: 255px;" name="su_portfolio_item_skills" id="su_portfolio_item_skills"><?php echo get_post_meta($post->ID, 'su_portfolio_item_skills', true); ?></textarea>
            </p>

        </fieldset>
        <?php
    }

}

/**
 * Hook for saving custom fields for portfolio_item post type.
 *
 * @param  int $post_id The ID of the post which contains the field you will edit.
 * @return void
 */

if ( ! function_exists( 'su_portfolio_meta_box_options_save_post' ) ) {

    function su_portfolio_meta_box_options_save_post( $post_id ) {

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
            return $post_id;
        }

        // called after a post or page is saved
        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }

       if ( isset( $_POST['post_type'] ) && ( $post_type_object = get_post_type_object( $_POST['post_type'] ) ) && $post_type_object->public ) {
               if ( current_user_can( 'edit_post', $post_id ) ) {

                update_custom_meta( $post_id, $_POST['su_portfolio_item_director'], 'su_portfolio_item_director' );
                update_custom_meta( $post_id, $_POST['su_portfolio_item_year'], 'su_portfolio_item_year' );
                update_custom_meta( $post_id, $_POST['su_portfolio_item_client'], 'su_portfolio_item_client' );
                update_custom_meta( $post_id, $_POST['su_portfolio_item_skills'], 'su_portfolio_item_skills' );

            }
         }
    }

}

add_action( 'save_post', 'su_portfolio_meta_box_options_save_post' );



/* Custom Post Layouts
==================================== */

function wpzoom_post_layout_options() {
    global $post;
    $postLayouts = array( 'full' => 'Full Width', 'side-right' => 'Sidebar on the right');
    ?>

    <style>
    .RadioClass { display: none !important; }
    .RadioLabelClass { margin-right: 10px; }
    img.layout-select { border: solid 3px #c0cdd6; border-radius: 5px; }
    .RadioSelected img.layout-select { border: solid 3px #3173b2; }
    #wpzoom_post_embed_code { color: #444444; font-size: 11px; margin: 3px 0 10px; padding: 5px; height:135px; font-family: Consolas,Monaco,Courier,monospace; }

    </style>

    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $(".RadioClass").change(function(){
            if($(this).is(":checked")){
                $(".RadioSelected:not(:checked)").removeClass("RadioSelected");
                $(this).next("label").addClass("RadioSelected");
            }
        });
    });
    </script>

    <fieldset>
        <div>
            <p>
            <?php
            foreach ($postLayouts as $key => $value)
            {
                ?>
                <input id="<?php echo $key; ?>" type="radio" class="RadioClass" name="wpzoom_post_template" value="<?php echo $key; ?>"<?php if (get_post_meta($post->ID, 'wpzoom_post_template', true) == $key) { echo' checked="checked"'; } ?> />
                <label for="<?php echo $key; ?>" class="RadioLabelClass<?php if (get_post_meta($post->ID, 'wpzoom_post_template', true) == $key) { echo' RadioSelected"'; } ?>">
                <img src="<?php echo wpzoom::$wpzoomPath; ?>/assets/images/layout-<?php echo $key; ?>.png" alt="<?php echo $value; ?>" title="<?php echo $value; ?>" class="layout-select" /></label>
            <?php
            }
            ?>
            </p>
        </div>
    </fieldset>
    <?php
}



/* Portfolio Page Template Options
============================================*/

function wpzoom_dir_list_meta() {
    global $post;
    ?>
    <fieldset>
        <input type="hidden" name="saveDirList" id="saveDirList" value="1"/>

            <p>

                <label for="wpzoom_portfolio_page_category_name"><?php _e('Portfolio Category to show:', 'wpzoom'); ?></label>
                <?php
                $categories = wp_dropdown_categories( array(
                    'taxonomy'        => 'portfolio',
                    'show_option_all' => 'All',
                    'show_count'      => 1,
                    'hierarchical'    => 1,
                    'selected'        => get_post_meta( $post->ID, 'wpzoom_portfolio_page_category_name', true ),
                    'name'            => 'wpzoom_portfolio_page_category_name',
                    'id'              => 'wpzoom_portfolio_page_category_name'
                ) );
                ?>
            </p>

            <p class="description"><?php _e('You can choose here a category from which posts are shown. Sub-categories will be shown in the filter at the top.', 'wpzoom'); ?></p>

    </fieldset>
<?php
}


/* Homepage Slideshow Category selector
============================================*/

function wpzoom_slide_category() {
    global $post;
    ?>
    <fieldset>
        <input type="hidden" name="saveDirList" id="saveDirList" value="1"/>

            <p>

                <label for="wpzoom_slide_page_category_name"><?php _e('Slideshow Category to show:', 'wpzoom'); ?></label>
                <?php
                $categories = wp_dropdown_categories( array(
                    'taxonomy'        => 'slide-category',
                    'show_option_all' => 'All',
                    'show_count'      => 1,
                    'hierarchical'    => 1,
                    'selected'        => get_post_meta( $post->ID, 'wpzoom_slide_page_category_name', true ),
                    'name'            => 'wpzoom_slide_page_category_name',
                    'id'              => 'wpzoom_slide_page_category_name'
                ) );
                ?>
            </p>

            <p class="description"><?php _e('You can choose here a category from which slides are shown.', 'wpzoom'); ?></p>

    </fieldset>
<?php
}



function wpz_newpost_head() {
    ?>
    <style type="text/css">
        fieldset.fieldset-show {
            padding: 0.3em 0.8em 1em;
            margin-top: 20px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
        }

        fieldset.fieldset-show:first-child {
            margin-top: 0 !important;
        }

        fieldset.fieldset-show p {
            margin: 0 0 1em;
        }

        fieldset.fieldset-show p:last-child {
            margin-bottom: 0;
        }

        .wpz_flexbox {
            display: flex;
            justify-content: space-between;
            max-width: 90%;
        }

        .wpz_list {
            font-size: 12px;
        }

        .wpz_border {
            border-bottom: 1px solid #EEEEEE;
            padding: 0 0 10px;
        }



    </style><?php
}

add_action( 'admin_head-post-new.php', 'wpz_newpost_head', 100 );
add_action( 'admin_head-post.php', 'wpz_newpost_head', 100 );

/**
 * Inline styles for tabs that a rendered in metaboxes.
 */
function get_tabs_inline_style(){
    ?>
    <style type="text/css">
        ul.metabox-tabs {
            margin-bottom: 0;
            padding: 0 10px 0 10px;
            height: 30px;
            padding-top: 5px;
        }

        ul.metabox-tabs li {
            list-style: none;
            display: inline;
            font-size: 12px;
        }

        ul.metabox-tabs li.tab a {
            display: block;
            float: left;
            margin-right: 4px;
            margin-bottom: -1px;
            padding: 6px 10px;
            filter: alpha(opacity=50);
            opacity: .5;
            -webkit-opacity: .5;
            -moz-opacity: .5;
            background: #fff;
            color: #000;
            outline: none;
            font-weight: bold;
            text-decoration: none;
            zoom: 1;
            border: 1px solid #e1e1e1;
            border-bottom: none;
            background: #e1e1e1;
        }

        ul.metabox-tabs li.tab a.active,
        ul.metabox-tabs li.tab a:hover {
            background: #fff;
            opacity: 1;
            -webkit-opacity: 1;
            -moz-opacity: 1;
            filter: alpha(opacity=100);
        }

        ul.metabox-tabs li.tab a:hover {
            background: #e1e1e1;
        }

        ul.metabox-tabs li.tab a.active:hover {
            background: #fff;
        }

        ul.metabox-tabs li.link {
            margin-left: 4px;
        }

        ul.metabox-tabs li.link a {
            text-decoration: none;
        }

        ul.metabox-tabs li.link a:hover {
            text-decoration: underline;
        }

        ul.metabox-tabs {
        }

        ul.metabox-tabs li.tab a {
            color: #4545459;
        }

        ul.metabox-tabs li.tab a {
            background: #f0f0f09; \9;
        }

        ul.metabox-tabs li.tab a.active,
        ul.metabox-tabs li.tab a:hover {
            color: #454545;
        }

        .zoom-tab {
            border: 1px solid #e1e1e1;
            padding: 10px 15px 15px;
        }

        .zoom-tab .dnt{
            display: none;
        }

        .preview-video-input-span {
            position: relative;
        }

        .preview-video-input-span img.wpzoom-preloader {
            position: absolute;
            right: 6px;
            top: 0;
        }


        .wpz_video_embed_icons i {
            font-size: 20px;
            margin: 0 5px 0 0;
            vertical-align: middle;
        }

        .wpz_video_embed_icons .fa-youtube-play {
            color: #cc181e;
        }

        .wpz_video_embed_icons .fa-vimeo {
            color: #1ab7ea;
        }

        .wpz_embed_sep {
            color: #ccc;
            font-weight: normal;
            margin: 0 5px;
        }

    </style>
    <?php
}
/**
 * Portfolio metabox content
 */

function wpzoom_portfolio_video_settings() {
    global $post;

    $postmeta_videotype = get_post_meta( $post->ID, 'wpzoom_portfolio_popup_video_type', true );
    $post_meta          = empty( $postmeta_videotype ) ? 'self_hosted' : $postmeta_videotype;
    $tab_order          = get_post_meta( $post->ID, 'wpzoom_portfolio_tab_order', true ) === false ? 0 : get_post_meta( $post->ID, 'wpzoom_portfolio_tab_order', true );
    # single portfolio video background vars
    $postmeta_single_post_videotype   = get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_type', true );
    $post_meta_single_post_background = empty( $postmeta_single_post_videotype ) ? 'self_hosted' : $postmeta_single_post_videotype;
    # #single portfolio video background vars
    $postmeta_background_videotype = get_post_meta( $post->ID, 'wpzoom_portfolio_background_type', true );
    $post_meta_background          = empty( $postmeta_background_videotype ) ? 'self_hosted' : $postmeta_background_videotype;

    ?>
    <?php get_tabs_inline_style() ?>

    <div class="portfolio-tabs">
        <ul class="metabox-tabs">
            <li data-tab-order="0" class="tab">
                <a class="<?php echo $tab_order == 0 ? 'active': '' ?>" href="#portfolio-popup"><?php _e( 'Video Lightbox', 'wpzoom'); ?></a>
            </li>
            <li data-tab-order="1" class="tab">
                <a class="<?php echo $tab_order == 1 ? 'active': '' ?>" href="#portfolio-background"><?php _e( 'Video Background on Hover', 'wpzoom'); ?></a>
            </li>
            <!-- single portfolio video background tab -->
            <li data-tab-order="2" class="tab">
                <a class="<?php echo $tab_order == 2 ? 'active': '' ?>" href="#portfolio-background-single-post"><?php _e( 'Video Background on Single Post', 'wpzoom'); ?></a>
            </li>
            <!-- #single portfolio video background tab -->
            <input type="hidden" name="wpzoom_portfolio_tab_order" value="<?php echo esc_attr( $tab_order ); ?>" />
        </ul>

        <div class="zoom-tab" id="portfolio-popup">

            <div class="radio-switcher">

                <p class="description">Using this option you can display a video in a lightbox which can be opened clicking on the <strong>Play</strong> button in galleries.</p>

                <h3><?php _e('Select Video Source:', 'wpzoom'); ?></h3>

                <input type="radio" name="wpzoom_portfolio_popup_video_type" id="video_6" value="self_hosted" <?php checked( $post_meta, 'self_hosted' ); ?>>
                <label for="video_6" class="label_vid_self"><?php _e( 'Self-Hosted Video', 'wpzoom' ) ?></label>

                &nbsp;&nbsp;&nbsp;<input type="radio" id="video_7" name="wpzoom_portfolio_popup_video_type" value="external_hosted" <?php checked( $post_meta, 'external_hosted' ); ?>>
                <label class="label_vid_url" for="video_7"><strong class="wpz_video_embed_icons"><i class="fa fa-youtube-play"></i><?php _e( 'YouTube', 'wpzoom' ) ?> <span class="wpz_embed_sep">/</span> <i class="fa fa-vimeo"></i><?php _e( 'Vimeo', 'wpzoom' ) ?></strong></label>

            </div>

            <div class="wpzoom_self_hosted switch-wrapper">

                <br/>
                <div class="wp-media-buttons" data-button="Set Video" data-title="Set Video" data-target="#wpzoom_portfolio_video_popup_url">
                    <a href="#" class="button add_media wpz-upload-video-control" title="Upload Video">
                        <span class="wp-media-buttons-icon"></span>
                        <?php _e( 'Upload Video', 'wpzoom' ); ?>
                    </a>
                </div>

                <div class="clear"></div>

                <p>
                    <label>
                        <strong><?php _e( 'MP4 (h.264) video URL', 'wpzoom' ); ?></strong>
                        <input type="text" name="wpzoom_portfolio_video_popup_url_mp4" id="wpzoom_portfolio_video_popup_url_mp4" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_video_popup_url_mp4', true ) ); ?>"/>

                        <p class="description"><?php _e( 'This format is supported by most of the browsers and mobile devices. Video can be hosted anywhere, but make sure to link to MP4 file.', 'wpzoom' ); ?>
                    </label>
                </p>

                <div class="wpz_border"></div>

                <p>
                    <label>
                        <strong><?php _e( 'WebM video URL', 'wpzoom' ); ?></strong> <em>(optional)</em>
                        <input type="text" name="wpzoom_portfolio_video_popup_url_webm" id="wpzoom_portfolio_video_popup_url_webm" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_video_popup_url_webm', true ) ); ?>"/>

                        <p class="description"><?php _e( 'This format is optional for old versions of <strong>Mozilla Firefox</strong> that don\'t support <strong>MP4</strong> (v.21 and older).', 'wpzoom' ); ?>
                    </label>
                </p>


                <div class="wpz_border"></div>

                <p>
                    <em><strong>Tips:</strong></em><br/>
                    <ol class="wpz_list">
                        <li>If your server can't play MP4 videos, check this <a
                                href="https://www.wpzoom.com/docs/enable-mp4-video-support-linuxapache-server/"
                                target="_blank">tutorial</a> for a fix.
                        </li>
                        <li>Your <strong>MP4</strong> videos must have the <em>H.264</em> encoding. You can convert your videos with <a
                                href="https://handbrake.fr/downloads.php" target="_blank">HandBrake</a> video converter. Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.
                        </li>
                    </ol>
                </p>

            </div>

            <div class="wpzoom_external_hosted switch-wrapper" style="display: inline-block; width: 100%;">
                <p>
                    <label for="wpzoom_portfolio_video_popup_url"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong>
                        <em><?php _e( '(YouTube and Vimeo only)', 'wpzoom' ); ?></em>
                    </label>

                    <span class="preview-video-input-span">
                        <input type="text" id="wpzoom_portfolio_video_popup_url" class="preview-video-input widefat" name="wpzoom_portfolio_video_popup_url" data-response-type="thumb" value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_video_popup_url', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16" alt="" class="wpzoom-preloader hidden"/>
                    </span>
                </p>
                <div class="wpzoom_video_external_preview">

                </div>
            </div>

        </div>
        <div class="zoom-tab" id="portfolio-background">
            <?php do_action('wpz_render_background_video_on_hover', $post->ID); ?>
        </div>

        <!-- single portfolio video background tab content -->
        <div class="zoom-tab" id="portfolio-background-single-post">
            <p class="description"><?php _e( 'In this area you can configure a video which will play in the background of the header area when viewing a page with an individual portfolio post.', 'wpzoom' ); ?></p>

            <div class="radio-switcher">

                <h3><?php _e('Select Video Source:', 'wpzoom'); ?></h3>


                <input type="radio" name="wpzoom_portfolio_single_post_video_type" id="video_3" value="self_hosted" <?php checked( $post_meta_single_post_background, 'self_hosted' ); ?>>
                <label class="label_vid_self" for="video_3"><?php _e( 'Self-Hosted Video', 'wpzoom' ) ?>
                </label>

                 &nbsp;&nbsp;&nbsp;<input type="radio" id="video_4" name="wpzoom_portfolio_single_post_video_type" value="external_hosted" <?php checked( $post_meta_single_post_background, 'external_hosted' ); ?>>
                <label class="label_vid_url" for="video_4"><strong class="wpz_video_embed_icons"><i class="fa fa-youtube-play"></i><?php _e( 'YouTube', 'wpzoom' ) ?>  </strong></label>

                &nbsp;&nbsp;&nbsp;<input type="radio" id="video_5" name="wpzoom_portfolio_single_post_video_type" value="vimeo_pro" <?php checked( $post_meta_single_post_background, 'vimeo_pro' ); ?>>
                <label class="label_vid_url" for="video_5"><strong class="wpz_video_embed_icons"><i class="fa fa-vimeo"></i><?php _e( 'Vimeo', 'wpzoom' ) ?>  </strong></label>

            </div>


            <div class="wpzoom_self_hosted switch-wrapper">

                <br/>
                <div class="wp-media-buttons" data-button="Set Video" data-title="Set Video"
                     data-target="#wpzoom_portfolio_single_post_video_bg_url">
                    <a href="#" class="button add_media wpz-upload-video-control" title="Upload Video">
                        <span class="wp-media-buttons-icon"></span>
                        <?php _e( 'Upload Video', 'wpzoom' ); ?>
                    </a>
                </div>

                <div class="clear"></div>

                <p>
                    <label>
                        <strong><?php _e( 'MP4 (h.264) video URL', 'wpzoom' ); ?></strong>
                        <input type="text" name="wpzoom_portfolio_single_post_video_bg_url_mp4" id="wpzoom_portfolio_single_post_video_bg_url_mp4"
                               class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_bg_url_mp4', true ) ); ?>"/>

                               <p class="description"><?php _e( 'This format is supported by most of the browsers and mobile devices.', 'wpzoom' ); ?> Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.</p>
                    </label>
                </p>

                <div class="wpz_border"></div>

                <p>
                    <label>
                        <strong><?php _e( 'WebM video URL', 'wpzoom' ); ?></strong> <em>(optional)</em>
                        <input type="text" name="wpzoom_portfolio_single_post_video_bg_url_webm" id="wpzoom_portfolio_single_post_video_bg_url_webm"
                               class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_bg_url_webm', true ) ); ?>"/>

                <p class="description"><?php _e( 'This format is optional for old versions of <strong>Mozilla Firefox</strong> that don\'t support <strong>MP4</strong> (v.21 and older).', 'wpzoom' ); ?>
                    </label>
                </p>

            </div>


            <div class="wpzoom_external_hosted switch-wrapper" style="display: inline-block; width: 100%;">
                <p>
                    <label
                        for="wpzoom_portfolio_single_post_video_external_url"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong>
                        <em>(YouTube videos only)</em></label>
                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_portfolio_single_post_video_external_url"
                           name="wpzoom_portfolio_single_post_video_external_url"
                           data-response-type="thumb"
                           class="preview-video-input widefat"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_external_url', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>

                <p class="description"><?php _e( '<strong>IMPORTANT:</strong> YouTube videos aren\'t supported in video background on mobile and tablet devices.', 'wpzoom' ); ?></p>

                <div class="wpzoom_video_external_preview">
                </div>
            </div>

            <div class="wpzoom_vimeo_pro switch-wrapper" style="display: inline-block; width: 100%;">
                <p>

                    <div class="description"><em>This method works best if you have a <strong>Vimeo PLUS, PRO or Business</strong> account. <br />If you have a <strong>Vimeo PRO</strong> account, you can also insert direct link to video file (MP4) hosted on Vimeo in the <strong>Self Hosted</strong> option.</em></div>

                    <br />

                    <label
                        for="wpzoom_portfolio_single_post_video_vimeo_pro"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong> <em>(e.g. https://vimeo.com/295116835)</em>
                        </label>
                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_portfolio_single_post_video_vimeo_pro"
                           name="wpzoom_portfolio_single_post_video_vimeo_pro"
                           data-response-type="thumb"
                           class="preview-video-input widefat"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_vimeo_pro', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>
                <div class="wpzoom_video_external_preview">
                </div>
            </div>

            <div class="wpz_border"></div>

            <h3><?php _e( 'Video Background Controls', 'wpzoom' ) ?></h3>

            <p class="description">Video controls will appear in the bottom right corner</p>

            <p>
                <label>

                    <input type="hidden" name="wpzoom_portfolio_single_post_video_play_button" value="0"/>
                    <input type="checkbox" name="wpzoom_portfolio_single_post_video_play_button" id="wpzoom_portfolio_single_post_video_play_button" class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_play_button', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_play_button', true ) ); ?>/> <?php _e( 'Show Play/Pause Button', 'wpzoom' ) ?>

                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_portfolio_single_post_video_mute_button" value="0"/>
                    <input type="checkbox" name="wpzoom_portfolio_single_post_video_mute_button" id="wpzoom_portfolio_single_post_video_mute_button" class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_mute_button', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_mute_button', true ) ); ?>/> <?php _e( 'Show Mute/Unmute Button', 'wpzoom' ) ?>
                </label>
            </p>

            <div class="wpz_border"></div>


            <h3><?php _e( 'Video Background Options', 'wpzoom' ) ?></h3>

            <p>
                <label>


                    <input type="hidden" name="wpzoom_portfolio_single_post_video_autoplay" value="0"/>
                    <input type="checkbox" name="wpzoom_portfolio_single_post_video_autoplay" id="wpzoom_portfolio_single_post_video_autoplay"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_autoplay', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_autoplay', true ) ); ?>/> <?php _e( 'Autoplay Video', 'wpzoom' ) ?>
                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_portfolio_single_post_video_mute" value="0"/>
                    <input type="checkbox" name="wpzoom_portfolio_single_post_video_mute" id="wpzoom_portfolio_single_post_video_mute"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_mute', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_mute', true ) ); ?>/> <?php _e( 'Mute Video <em>(we recommend to keep your video Muted, as some browsers may block autoplay in videos with sound enabled, especially on mobile devices.)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_portfolio_single_post_video_loop" value="0"/>
                    <input type="checkbox" name="wpzoom_portfolio_single_post_video_loop" id="wpzoom_portfolio_single_post_video_loop"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_loop', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_loop', true ) ); ?>/> <?php _e( 'Loop Video <em>(if unchecked, then the video will play just once)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            <p class="dnt">
                <label>

                    <input type="hidden" name="wpzoom_portfolio_single_post_video_dnt" value="0"/>
                    <input type="checkbox" name="wpzoom_portfolio_single_post_video_dnt" id="wpzoom_portfolio_single_post_video_dnt"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_dnt', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_portfolio_single_post_video_dnt', true ) ); ?>/> <?php _e( 'Track Session Data <em>(if unchecked, then will block the player from tracking any session data, including all cookies and stats)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            </fieldset>


            <div class="wpz_border"></div>
            <p>
                <em><strong>Good to know:</strong></em><br/>
            <ol class="wpz_list">
                <li>If your server can't play MP4 videos, check this <a
                        href="http://www.wpzoom.com/docs/enable-mp4-video-support-linuxapache-server/"
                        target="_blank">tutorial</a> for a fix.
                </li>
                <li>Your <strong>MP4</strong> videos must have the <em>H.264</em> encoding. You can convert your videos with <a
                        href="https://handbrake.fr/downloads.php" target="_blank">HandBrake</a> video converter. Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.
                </li>
                <li>Only <strong>self-hosted</strong> and <strong>Vimeo</strong> videos are supported on some mobile devices & tablets to play in background. Fo self-hosted videos, we recommended to use a small video file in order to prevent unsolicited data consumption, or you can <a href="https://www.wpzoom.com/documentation/inspiro/inspiro-video-background-support-in-slideshow/" target="_blank">set a GIF file</a> as Featured Image, and this will act as a video fallback on these devices.</li>
                </li>

            </ol>
            </p>
            <br/>
        </div>
        <!-- #single portfolio video background tab -->
    </div>

    <?php
}

/* Slideshow Options
============================================*/
function wpzoom_top_button_options() {
    global $post;

    ?>

    <fieldset class="fieldset-show">
        <legend><strong><label for="wpzoom_slide_url"><?php _e( 'Slide URL', 'wpzoom' ); ?></label></strong> <?php _e( '(optional)', 'wpzoom' ); ?></legend>
        <p><input type="text" name="wpzoom_slide_url" id="wpzoom_slide_url" class="widefat" placeholder="<?php _e( 'e.g. http://example.com', 'wpzoom' ); ?>"
                  value="<?php echo esc_url( get_post_meta( $post->ID, 'wpzoom_slide_url', true ) ); ?>" /></p>
        <p class="description"><?php _e( 'When a URL is added, the title of the current slide will become clickable', 'wpzoom' ); ?></p>
    </fieldset>

    <fieldset class="fieldset-show">
        <legend><strong><?php _e( 'Slide Button', 'wpzoom' ); ?></strong> <?php _e( '(optional)', 'wpzoom' ); ?></legend>


        <p class="description"><?php _e( 'Optional clickable button on the slide', 'wpzoom' ); ?></p>

        <p>
            <label>
                <strong><?php _e( 'Title', 'wpzoom' ); ?></strong>
                <input type="text" name="wpzoom_slide_button_title" id="wpzoom_slide_button_title" class="widefat" placeholder="<?php _e( 'e.g. Awesome Button!', 'wpzoom' ); ?>"
                       value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_slide_button_title', true ) ); ?>"/>
            </label>
        </p>

        <p>
            <label>
                <strong><?php _e( 'URL', 'wpzoom' ); ?></strong>
                <input type="text" name="wpzoom_slide_button_url" id="wpzoom_slide_button_url" class="widefat" placeholder="<?php _e( 'e.g. http://example.com', 'wpzoom' ); ?>"
                       value="<?php echo esc_url( get_post_meta( $post->ID, 'wpzoom_slide_button_url', true ) ); ?>"/>
            </label>
        </p>

        <p class="description"><strong>TIP:</strong><br/><?php _e( 'You can also include a <strong>Button Block</strong> or even more directly in the content editor. Need more than 1 button? Add a <strong>Column Block</strong> with 2 or 3 columns and place a Button block in each of them.', 'wpzoom' ); ?></p>

    </fieldset>

    <?php
}


function wpzoom_slideshow_video_settings() {
    global $post;

    $tab_order                     = get_post_meta( $post->ID, 'wpzoom_slider_tab_order', true ) === false ? 0 : get_post_meta( $post->ID, 'wpzoom_slider_tab_order', true );
    $postmeta_videotype_background = get_post_meta( $post->ID, 'wpzoom_home_slider_video_type', true );
    $post_meta_background          = empty( $postmeta_videotype_background ) ? 'self_hosted' : $postmeta_videotype_background;
    $postmeta_videotype_popup      = get_post_meta( $post->ID, 'wpzoom_home_slider_popup_video_type', true );
    $post_meta_popup               = empty( $postmeta_videotype_popup ) ? 'self_hosted' : $postmeta_videotype_popup;
    ?>
    <?php get_tabs_inline_style() ?>

    <div class="slider-tabs">
        <ul class="metabox-tabs">
            <li data-tab-order="0" class="tab">
                <a class="<?php echo $tab_order == 0 ? 'active': '' ?>" href="#slider-popup">Video Lightbox</a>
            </li>
            <li data-tab-order="1" class="tab">
                <a class="<?php echo $tab_order == 1 ? 'active': '' ?>" href="#slider-background">Video Background</a>
            </li>
            <input type="hidden" name="wpzoom_slider_tab_order" value="<?php echo esc_attr( $tab_order ); ?>" />
        </ul>
        <div class="zoom-tab" id="slider-popup">
            <div class="radio-switcher">


                <p class="description">Using this option you can display a video in a lightbox which can be opened clicking on
                    the <strong>Play</strong> button.</p>

                    <?php /*
                <?php $play_icon_text = !(FALSE == get_post_meta( $post->ID, 'wpzoom_slider_icon_text', true ))  ? get_post_meta( $post->ID, 'wpzoom_slider_icon_text', true ) : ''; ?>

                <p>
                    <label>
                        <strong>&#9654; <?php _e( 'Play Icon Text', 'wpzoom' ); ?></strong> <em><?php _e('(Optional)', 'wpzoom'); ?></em>
                        <input type="text" name="wpzoom_slider_icon_text"
                               id="wpzoom_slider_icon_text"
                               class="widefat"
                               value="<?php echo esc_attr( $play_icon_text ); ?>"/>

                <p class="description"><?php _e( 'Example: Watch Video', 'wpzoom' ); ?>
                    </label>
                </p>

                <div class="wpz_border"></div><br/>
                */ ?>


                <h3><?php _e('Select Video Source:', 'wpzoom'); ?></h3>

                <input type="radio" name="wpzoom_home_slider_popup_video_type" id="video_1" value="self_hosted" <?php checked( $post_meta_popup, 'self_hosted' ); ?>>
                <label class="label_vid_self" for="video_1"><?php _e( 'Self-Hosted Video', 'wpzoom' ) ?>
                </label>

                &nbsp;&nbsp;&nbsp;<input type="radio" id="video_2" name="wpzoom_home_slider_popup_video_type" value="external_hosted" <?php checked( $post_meta_popup, 'external_hosted' ); ?>>
                <label class="label_vid_url" for="video_2"><strong class="wpz_video_embed_icons"><i class="fa fa-youtube-play"></i><?php _e( 'YouTube', 'wpzoom' ) ?> <span class="wpz_embed_sep">/</span> <i class="fa fa-vimeo"></i><?php _e( 'Vimeo', 'wpzoom' ) ?></strong>
                </label>
            </div>

            <div class="wpzoom_self_hosted switch-wrapper">

                <br/>
                <div class="wp-media-buttons" data-button="Set Video" data-title="Set Video"
                     data-target="#wpzoom_home_slider_video_popup_url">
                    <a href="#" id="wpzoom-home-slider-video-bg-insert-media-button"
                       class="button add_media wpz-upload-video-control" title="Upload Video">
                        <span class="wp-media-buttons-icon"></span>
                        <?php _e( 'Upload Video', 'wpzoom' ); ?>
                    </a>
                </div>

                <div class="clear"></div>

                <p>
                    <label>
                        <strong><?php _e( 'MP4 (h.264) video URL', 'wpzoom' ); ?></strong>
                        <input type="text" name="wpzoom_home_slider_video_popup_url_mp4"
                               id="wpzoom_home_slider_video_popup_url_mp4" class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_popup_url_mp4', true ) ); ?>"/>

                <p class="description"><?php _e( 'This format is supported by most of the browsers and mobile devices. Video can be hosted anywhere, but make sure to link to MP4 file.', 'wpzoom' ); ?>
                    </label>
                </p>

                <div class="wpz_border"></div>

                <p>
                    <label>
                        <strong><?php _e( 'WebM video URL', 'wpzoom' ); ?></strong> <em>(optional)</em>
                        <input type="text" name="wpzoom_home_slider_video_popup_url_webm"
                               id="wpzoom_home_slider_video_popup_url_webm" class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_popup_url_webm', true ) ); ?>"/>

                <p class="description"><?php _e( 'This format is optional for old versions of <strong>Mozilla Firefox</strong> that don\'t support <strong>MP4</strong> (v.21 and older).', 'wpzoom' ); ?>
                    </label>
                </p>

            </div>

            <div class="wpzoom_external_hosted switch-wrapper" style="display: inline-block; width: 100%;">
                <p>
                    <label
                        for="wpzoom_home_slider_video_popup_url"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong>
                        <em>(YouTube and Vimeo only)</em></label>
                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_home_slider_video_popup_url"
                           class="preview-video-input widefat"
                           name="wpzoom_home_slider_video_popup_url"
                           data-response-type="thumb"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_popup_url', true ) ); ?>"/>

                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>
                <div class="wpzoom_video_external_preview" >

                </div>
            </div>
        </div>
        <div class="zoom-tab" id="slider-background">
            <p class="description"><?php _e( 'In this area you can upload a video which will play in the background of the current slide continuously and muted by default.', 'wpzoom' ); ?></p>

            <div class="radio-switcher">

                <h3>Select Video Source:</h3>

                <input type="radio" name="wpzoom_home_slider_video_type" id="video_3" value="self_hosted" <?php checked( $post_meta_background, 'self_hosted' ); ?>>
                <label class="label_vid_self" for="video_3"><?php _e( 'Self-Hosted Video', 'wpzoom' ) ?>
                </label>

                 &nbsp;&nbsp;&nbsp;<input type="radio" id="video_4" name="wpzoom_home_slider_video_type" value="external_hosted" <?php checked( $post_meta_background, 'external_hosted' ); ?>>
                <label class="label_vid_url" for="video_4"><strong class="wpz_video_embed_icons"><i class="fa fa-youtube-play"></i><?php _e( 'YouTube', 'wpzoom' ) ?>  </strong></label>

                &nbsp;&nbsp;&nbsp;<input type="radio" id="video_5" name="wpzoom_home_slider_video_type" value="vimeo_pro" <?php checked( $post_meta_background, 'vimeo_pro' ); ?>>
                <label class="label_vid_url" for="video_5"><strong class="wpz_video_embed_icons"><i class="fa fa-vimeo"></i><?php _e( 'Vimeo', 'wpzoom' ) ?>  </strong></label>


            </div>


            <div class="wpzoom_self_hosted switch-wrapper">

                <br/>
                <div class="wp-media-buttons" data-button="Set Video" data-title="Set Video"
                     data-target="#wpzoom_home_slider_video_bg_url">
                    <a href="#" id="wpzoom-home-slider-video-bg-insert-media-button"
                       class="button add_media wpz-upload-video-control" title="Upload Video">
                        <span class="wp-media-buttons-icon"></span>
                        <?php _e( 'Upload Video', 'wpzoom' ); ?>
                    </a>
                </div>

                <div class="clear"></div>

                <p>
                    <label>
                        <strong><?php _e( 'MP4 (h.264) video URL', 'wpzoom' ); ?></strong>
                        <input type="text" name="wpzoom_home_slider_video_bg_url_mp4" id="wpzoom_home_slider_video_bg_url_mp4"
                               class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_bg_url_mp4', true ) ); ?>"/>
                    </label>
                </p>

                <p class="description"><?php _e( 'This format is supported by most of the browsers and mobile devices.', 'wpzoom' ); ?> Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.</p>



                <div class="wpz_border"></div>

                <p>
                    <label>
                        <strong><?php _e( 'WebM video URL', 'wpzoom' ); ?></strong> <em>(optional)</em>
                        <input type="text" name="wpzoom_home_slider_video_bg_url_webm" id="wpzoom_home_slider_video_bg_url_webm"
                               class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_bg_url_webm', true ) ); ?>"/>
                    </label>
                </p>

                <p class="description"><?php _e( 'This format is optional for old versions of <strong>Mozilla Firefox</strong> that don\'t support <strong>MP4</strong> (v.21 and older).', 'wpzoom' ); ?></p>
                <!--                new option start-->
                <div class="wpz_border"></div>
                <br/>

                <h3><?php _e( '📱 Mobile Video Source:', 'wpzoom' ); ?></h3>

                <p class="description"><?php _e('Optionally, you can upload a different video which will be displayed on mobile devices.', 'wpzoom'); ?></p>
                <p class="description"><?php _e('For example, it can be am optimized video with a smaller resolution, or in a different orientation (e.g. portrait).', 'wpzoom'); ?></p>

                <div class="wp-media-buttons" data-button="Set Video" data-title="Set Video"
                     data-target="#wpzoom_home_slider_mobile_video_bg_url">
                    <a href="#" id="wpzoom-home-slider-mobile-video-bg-insert-media-button"
                       class="button add_media wpz-upload-video-control" title="Upload Video">
                        <span class="wp-media-buttons-icon"></span>
                        <?php _e('Upload Mobile Video', 'wpzoom'); ?>
                    </a>
                </div>

                <div class="clear"></div>

                <p>
                    <label>
                        <strong><?php _e('MP4 (h.264) video URL', 'wpzoom'); ?></strong>
                        <input type="text" name="wpzoom_home_slider_mobile_video_bg_url_mp4"
                               id="wpzoom_home_slider_mobile_video_bg_url_mp4"
                               class="widefat"
                               value="<?php echo esc_attr(get_post_meta($post->ID, 'wpzoom_home_slider_mobile_video_bg_url_mp4', true)); ?>"/>
                    </label>
                </p>


            </div>


            <div class="wpzoom_external_hosted switch-wrapper" style="display: inline-block; width: 100%;">
                <p>
                    <label
                        for="wpzoom_home_slider_video_external_url"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong>
                        <em>(YouTube videos only)</em></label>
                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_home_slider_video_external_url"
                           name="wpzoom_home_slider_video_external_url"
                           data-response-type="thumb"
                           class="preview-video-input widefat"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_external_url', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>

                <p class="description"><?php _e( '<strong>IMPORTANT:</strong> YouTube videos aren\'t supported in video background on mobile and tablet devices.', 'wpzoom' ); ?></p>


                <div class="wpzoom_video_external_preview">
                </div>
            </div>

            <div class="wpzoom_vimeo_pro switch-wrapper" style="display: inline-block; width: 100%;">
                <p>

                    <div class="description"><em>This method works best if you have a <strong>Vimeo PLUS, PRO or Business</strong> account. <br />If you have a <strong>Vimeo PRO</strong> account, you can also insert direct link to video file (MP4) hosted on Vimeo in the <strong>Self Hosted</strong> option.</em></div>

                    <br />


                    <label
                        for="wpzoom_home_slider_video_vimeo_pro"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong> <em>(e.g. https://vimeo.com/295116835)</em>
                        </label>

                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_home_slider_video_vimeo_pro"
                           name="wpzoom_home_slider_video_vimeo_pro"
                           data-response-type="thumb"
                           class="preview-video-input widefat"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_home_slider_video_vimeo_pro', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>
                <div class="wpzoom_video_external_preview">
                </div>
            </div>

            <div class="wpz_border"></div>

            <h3><?php _e( 'Video Background Controls', 'wpzoom' ) ?></h3>

            <p class="description">Video controls will appear in the bottom right corner</p>

            <p>
                <label>

                    <input type="hidden" name="wpzoom_slide_play_button" value="0"/>
                    <input type="checkbox" name="wpzoom_slide_play_button" id="wpzoom_slide_play_button" class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_slide_play_button', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_slide_play_button', true ) ); ?>/> <?php _e( 'Show Play/Pause Button', 'wpzoom' ) ?>

                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_slide_mute_button" value="0"/>
                    <input type="checkbox" name="wpzoom_slide_mute_button" id="wpzoom_slide_mute_button" class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_slide_mute_button', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_slide_mute_button', true ) ); ?>/> <?php _e( 'Show Mute/Unmute Button', 'wpzoom' ) ?>
                </label>
            </p>

            <div class="wpz_border"></div>


            <h3><?php _e( 'Video Background Options', 'wpzoom' ) ?></h3>

            <p>
                <label>


                    <input type="hidden" name="wpzoom_slide_autoplay_video_action" value="0"/>
                    <input type="checkbox" name="wpzoom_slide_autoplay_video_action" id="wpzoom_slide_autoplay_video_action"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_slide_autoplay_video_action', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_slide_autoplay_video_action', true ) ); ?>/> <?php _e( 'Autoplay Video', 'wpzoom' ) ?>
                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_slide_mute_video_action" value="0"/>
                    <input type="checkbox" name="wpzoom_slide_mute_video_action" id="wpzoom_slide_mute_video_action"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_slide_mute_video_action', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_slide_mute_video_action', true ) ); ?>/> <?php _e( 'Mute Video <em>(we recommend to keep your video Muted, as some browsers may block autoplay in videos with sound enabled, especially on mobile devices.)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_slide_loop_video_action" value="0"/>
                    <input type="checkbox" name="wpzoom_slide_loop_video_action" id="wpzoom_slide_loop_video_action"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_slide_loop_video_action', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_slide_loop_video_action', true ) ); ?>/> <?php _e( 'Loop Video <em>(if unchecked, then the video will play just once)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            <p class="dnt">
                <label>

                    <input type="hidden" name="wpzoom_slide_dnt_video_action" value="0"/>
                    <input type="checkbox" name="wpzoom_slide_dnt_video_action" id="wpzoom_slide_dnt_video_action"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_slide_dnt_video_action', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_slide_dnt_video_action', true ) ); ?>/> <?php _e( ' Track Session Data <em>(if unchecked, then will block the player from tracking any session data, including all cookies and stats)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            </fieldset>


            <div class="wpz_border"></div>
            <p>
                <em><strong>Good to know:</strong></em><br/>
            <ol class="wpz_list">
                <li>If your server can't play MP4 videos, check this <a
                        href="http://www.wpzoom.com/docs/enable-mp4-video-support-linuxapache-server/"
                        target="_blank">tutorial</a> for a fix.
                </li>
                <li>Your <strong>MP4</strong> videos must have the <em>H.264</em> encoding. You can convert your videos with <a
                        href="https://handbrake.fr/downloads.php" target="_blank">HandBrake</a> video converter. Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.
                </li>
                <li>Only <strong>self-hosted</strong> and <strong>Vimeo</strong> videos are supported on some mobile devices & tablets to play in background. Fo self-hosted videos, we recommended to use a small video file in order to prevent unsolicited data consumption, or you can <a href="https://www.wpzoom.com/documentation/inspiro/inspiro-video-background-support-in-slideshow/" target="_blank">set a GIF file</a> as Featured Image, and this will act as a video fallback on these devices.</li>
                </li>

            </ol>
            </p>
            <br/>
        </div>
    </div>
    <?php
}

add_filter( 'upload_mimes', 'inspiro_add_custom_mime_types' );
function inspiro_add_custom_mime_types( $mimes ) {
    return array_merge( $mimes, array(
        'webm' => 'video/webm',
    ) );
}

add_action( 'save_post', 'custom_add_save' );

function custom_add_save( $postID ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return $postID;
    }

    // called after a post or page is saved
    if ( $parent_id = wp_is_post_revision( $postID ) ) {
        $postID = $parent_id;
    }


    if ( isset( $_POST['saveDirList'] ) && $_POST['saveDirList'] ) {

        if ( isset( $_POST['wpzoom_portfolio_page_category_name'] ) )
        update_custom_meta( $postID, $_POST['wpzoom_portfolio_page_category_name'], 'wpzoom_portfolio_page_category_name' );

        if ( isset( $_POST['wpzoom_slide_page_category_name'] ) )
        update_custom_meta( $postID, $_POST['wpzoom_slide_page_category_name'], 'wpzoom_slide_page_category_name' );

    }


    if ( isset( $_POST['post_type'] ) && ( $post_type_object = get_post_type_object( $_POST['post_type'] ) ) && $post_type_object->public ) {
        if ( current_user_can( 'edit_post', $postID ) ) {

            if ( isset( $_POST['wpzoom_post_template'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_post_template'], 'wpzoom_post_template' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_type'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_home_slider_video_type'], 'wpzoom_home_slider_video_type' );
            }

            if ( isset( $_POST['wpzoom_home_slider_popup_video_type'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_home_slider_popup_video_type'], 'wpzoom_home_slider_popup_video_type' );
            }

            /*
            if ( isset( $_POST['wpzoom_slider_icon_text'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slider_icon_text'], 'wpzoom_slider_icon_text' );
            }
            */

            if ( isset( $_POST['wpzoom_slide_url'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_slide_url'] ), 'wpzoom_slide_url' );
            }

            if ( isset( $_POST['wpzoom_slide_button_title'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_button_title'], 'wpzoom_slide_button_title' );
            }

            if ( isset( $_POST['wpzoom_slide_button_url'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_slide_button_url'] ), 'wpzoom_slide_button_url' );
            }

            if ( isset( $_POST['wpzoom_slide_play_button'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_play_button'], 'wpzoom_slide_play_button' );
            }

            if ( isset( $_POST['wpzoom_slide_mute_button'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_mute_button'], 'wpzoom_slide_mute_button' );
            }

            if ( isset( $_POST['wpzoom_slide_autoplay_video_action'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_autoplay_video_action'], 'wpzoom_slide_autoplay_video_action' );
            }

            if ( isset( $_POST['wpzoom_slide_loop_video_action'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_loop_video_action'], 'wpzoom_slide_loop_video_action' );
            }

            if ( isset( $_POST['wpzoom_slide_dnt_video_action'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_dnt_video_action'], 'wpzoom_slide_dnt_video_action' );
            }

            if ( isset( $_POST['wpzoom_slide_mute_video_action'] ) ) {
                update_custom_meta( $postID, $_POST['wpzoom_slide_mute_video_action'], 'wpzoom_slide_mute_video_action' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_bg_url_mp4'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_bg_url_mp4'] ), 'wpzoom_home_slider_video_bg_url_mp4' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_bg_url_webm'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_bg_url_webm'] ), 'wpzoom_home_slider_video_bg_url_webm' );
            }

            if ( isset( $_POST['wpzoom_home_slider_mobile_video_bg_url_mp4'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_mobile_video_bg_url_mp4'] ), 'wpzoom_home_slider_mobile_video_bg_url_mp4' );
            }

            if ( isset( $_POST['wpzoom_home_slider_mobile_video_bg_url_webm'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_mobile_video_bg_url_webm'] ), 'wpzoom_home_slider_mobile_video_bg_url_webm' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_popup_url_mp4'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_popup_url_mp4'] ), 'wpzoom_home_slider_video_popup_url_mp4' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_popup_url_webm'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_popup_url_webm'] ), 'wpzoom_home_slider_video_popup_url_webm' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_external_url'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_external_url'] ), 'wpzoom_home_slider_video_external_url' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_vimeo_pro'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_vimeo_pro'] ), 'wpzoom_home_slider_video_vimeo_pro' );

                $oembed   = _wp_oembed_get_object();
                $data     = $oembed->get_data( $_POST['wpzoom_home_slider_video_vimeo_pro'] );
                $video_id = ! empty( $data->video_id ) ? $data->video_id : false;

                update_custom_meta( $postID, $video_id, 'wpzoom_home_slider_video_vimeo_pro_video_id' );
            }

            if ( isset( $_POST['wpzoom_home_slider_video_popup_url'] ) ) {
                update_custom_meta( $postID, esc_url_raw( $_POST['wpzoom_home_slider_video_popup_url'] ), 'wpzoom_home_slider_video_popup_url' );
            }

            if ( isset( $_POST['wpzoom_slider_tab_order'] ) ) {
                update_post_meta( $postID, 'wpzoom_slider_tab_order', $_POST['wpzoom_slider_tab_order'] );
            }


            // Porfolio metakeys
            if ( isset( $_POST['wpzoom_portfolio_tab_order'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_tab_order', $_POST['wpzoom_portfolio_tab_order'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_popup_video_type'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_popup_video_type', $_POST['wpzoom_portfolio_popup_video_type'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_video_popup_url_mp4'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_video_popup_url_mp4', esc_url_raw( $_POST['wpzoom_portfolio_video_popup_url_mp4'] ) );
            }

            if ( isset( $_POST['wpzoom_portfolio_video_popup_url_webm'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_video_popup_url_webm', esc_url_raw( $_POST['wpzoom_portfolio_video_popup_url_webm'] ) );
            }

            if ( isset( $_POST['wpzoom_portfolio_video_external_url'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_video_external_url', esc_url_raw( $_POST['wpzoom_portfolio_video_external_url'] ) );
            }

            if ( isset( $_POST['wpzoom_portfolio_video_popup_url'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_video_popup_url', esc_url_raw( $_POST['wpzoom_portfolio_video_popup_url'] ) );
            }

            # single portfolio video background save metadata

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_type'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_type', $_POST['wpzoom_portfolio_single_post_video_type'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_bg_url_mp4'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_bg_url_mp4', esc_url_raw($_POST['wpzoom_portfolio_single_post_video_bg_url_mp4']) );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_bg_url_webm'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_bg_url_webm', esc_url_raw($_POST['wpzoom_portfolio_single_post_video_bg_url_webm']) );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_external_url'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_external_url', esc_url_raw($_POST['wpzoom_portfolio_single_post_video_external_url']) );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_vimeo_pro'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_vimeo_pro', esc_url_raw( $_POST['wpzoom_portfolio_single_post_video_vimeo_pro'] ) );

                $oembed   = _wp_oembed_get_object();
                $data     = $oembed->get_data( esc_url_raw( $_POST['wpzoom_portfolio_single_post_video_vimeo_pro'] ) );
                $video_id = ! empty( $data->video_id ) ? $data->video_id : false;

                update_custom_meta( $postID, $video_id, 'wpzoom_portfolio_single_post_video_vimeo_pro_video_id' );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_play_button'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_play_button', $_POST['wpzoom_portfolio_single_post_video_play_button'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_mute_button'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_mute_button', $_POST['wpzoom_portfolio_single_post_video_mute_button'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_autoplay'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_autoplay', $_POST['wpzoom_portfolio_single_post_video_autoplay'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_mute'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_mute', $_POST['wpzoom_portfolio_single_post_video_mute'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_loop'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_loop', $_POST['wpzoom_portfolio_single_post_video_loop'] );
            }

            if ( isset( $_POST['wpzoom_portfolio_single_post_video_dnt'] ) ) {
                update_post_meta( $postID, 'wpzoom_portfolio_single_post_video_dnt', $_POST['wpzoom_portfolio_single_post_video_dnt'] );
            }

            # #single portfolio video background save metadata

            # single posts/pages video background save metadata

            if ( isset( $_POST['wpzoom_posts_single_post_video_type'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_type', $_POST['wpzoom_posts_single_post_video_type'] );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_bg_url_mp4'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_bg_url_mp4', esc_url_raw($_POST['wpzoom_posts_single_post_video_bg_url_mp4']) );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_bg_url_webm'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_bg_url_webm', esc_url_raw($_POST['wpzoom_posts_single_post_video_bg_url_webm']) );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_external_url'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_external_url', esc_url_raw($_POST['wpzoom_posts_single_post_video_external_url']) );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_vimeo_pro'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_vimeo_pro', esc_url_raw( $_POST['wpzoom_posts_single_post_video_vimeo_pro'] ) );

                $oembed   = _wp_oembed_get_object();
                $data     = $oembed->get_data( esc_url_raw( $_POST['wpzoom_posts_single_post_video_vimeo_pro'] ) );
                $video_id = ! empty( $data->video_id ) ? $data->video_id : false;

                update_custom_meta( $postID, $video_id, 'wpzoom_posts_single_post_video_vimeo_pro_video_id' );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_play_button'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_play_button', $_POST['wpzoom_posts_single_post_video_play_button'] );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_mute_button'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_mute_button', $_POST['wpzoom_posts_single_post_video_mute_button'] );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_autoplay'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_autoplay', $_POST['wpzoom_posts_single_post_video_autoplay'] );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_mute'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_mute', $_POST['wpzoom_posts_single_post_video_mute'] );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_loop'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_loop', $_POST['wpzoom_posts_single_post_video_loop'] );
            }

            if ( isset( $_POST['wpzoom_posts_single_post_video_dnt'] ) ) {
                update_post_meta( $postID, 'wpzoom_posts_single_post_video_dnt', $_POST['wpzoom_posts_single_post_video_dnt'] );
            }

            # #single posts/pages video background save metadata
        }
    }
}

function update_custom_meta( $postID, $value, $field ) {
    // To create new meta
    if ( ! get_post_meta( $postID, $field ) ) {
        add_post_meta( $postID, $field, $value );
    } else {
        // or to update existing meta
        update_post_meta( $postID, $field, $value );
    }
}

function load_admin_js() {

    global $post;

    wp_enqueue_script( 'slider-admin-js', get_template_directory_uri() . '/js/slider.admin.js', array( 'jquery', 'jquery-ui-tabs' ), WPZOOM::$themeVersion, true );
    wp_enqueue_script( 'wpzoom-home-slider-video-background', get_template_directory_uri() . '/js/admin-video-background.js', array( 'jquery' ), WPZOOM::$themeVersion );
    wp_localize_script( 'wpzoom-home-slider-video-background', 'inspiro_embed_option_type', array(
        'text-when-enabled'      => __( 'Use This as the Featured Image', 'wpzoom' ),
        'text-when-disabled'     => __( 'This is the Featured Image', 'wpzoom' ),
        'wpzoom_post_embed_info' => __( '<strong>NOTICE!&nbsp;&nbsp;Unable to fetch video thumbnail</strong><br/>' .
                                        'Either an invalid oembed code was provided, or there is no thumbnail available for the specified video&hellip;<br/>' .
                                        '<small id="wpz_autothumb_remind"><strong>REMINDER:</strong>' .
                                        'You can always manually upload a featured image via the WordPress Media Uploader.</small>', 'wpzoom' ),

        'nonce'          => wp_create_nonce( '_action_get_oembed_response' ),
        'nonce-button'   => wp_create_nonce( '_attach_remote_video_thumb' ),
        'nonce-featured' => wp_create_nonce( 'set_post_thumbnail-' . $post->ID )
    ) );
}

function check_current_screen() {
    $current_screen = get_current_screen();

    if ( $current_screen->id === 'slider' ||
         $current_screen->id === 'portfolio_item' ||
         $current_screen->id == 'post' ||
         $current_screen->id == 'page' ) {
        add_action( 'admin_enqueue_scripts', 'load_admin_js' );
    }
}

add_action( 'current_screen', 'check_current_screen' );

if ( ! function_exists( '_action_get_oembed_response' ) ) {
    function _action_get_oembed_response() {
        if ( wp_verify_nonce( $_POST['_nonce'], '_action_get_oembed_response' ) ) {
            $url      = ( filter_var( $_POST['url'], FILTER_VALIDATE_URL ) !== false ) ? $_POST['url'] : WPZOOM_Video_API::convert_embed_url( WPZOOM_Video_API::extract_url_from_embed( trim( stripslashes( $_POST['url'] ) ) ) );
            $width    = 460;
            $height   = 259;
            $instance = _wp_oembed_get_object();
            $provider = $instance->get_provider( $url, compact( 'width', 'height' ) );

            if ( ! $provider || false === $data = $instance->fetch( $provider, $url, compact( 'width', 'height' ) ) ) {
                $oembed_object = false;
            }

            $oembed_object = ! empty( $data->thumbnail_url ) ? $data->thumbnail_url : false;

            //if is youtube-url replace hqdefault with maxresdefault
            if ( $oembed_object && ( strpos( $provider, 'youtube' ) !== false ) ) {
                $stripped_url  = str_replace( basename( $oembed_object ), '', $oembed_object );
                $oembed_object = $stripped_url . 'maxresdefault.jpg';

                $response = wp_safe_remote_get( $oembed_object );

                if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
                    $oembed_object = $data->thumbnail_url;
                }
            }

            $iframe               = wp_oembed_get( $url, compact( 'width', 'height' ) );
            $embed_url            = WPZOOM_Video_API::extract_url_from_embed( $iframe );
            $is_featured_response = WPZOOM_Video_Thumb::fetch_video_thumbnail( $embed_url, $_POST['post_id'] );
            wp_send_json_success( array(
                'response'          => $iframe,
                'thumbnail'         => $oembed_object,
                'featured_response' => $is_featured_response
            ) );
        }
        wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
    }
}

add_action( 'wp_ajax_get_oembed_response', '_action_get_oembed_response' );

if ( ! function_exists( '_attach_remote_video_thumb' ) ):
    function _attach_remote_video_thumb() {

        if ( wp_verify_nonce( $_POST['_nonce'], '_attach_remote_video_thumb' ) ) {

            $url    = ( filter_var( $_POST['url'], FILTER_VALIDATE_URL ) !== false ) ? $_POST['url'] : WPZOOM_Video_API::convert_embed_url( WPZOOM_Video_API::extract_url_from_embed( trim( stripslashes( $_POST['url'] ) ) ) );
            $url    = WPZOOM_Video_API::extract_url_from_embed( wp_oembed_get( $url ) );
            $postid = $_POST['postid'];
            $id     = WPZOOM_Video_Thumb::attach_remote_video_thumb( $url, $postid, null );

            wp_send_json_success( array( 'response' => true, 'id' => $id ) );
        }

        wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
    }
endif;

add_action( 'wp_ajax_attach_remote_video_thumb', '_attach_remote_video_thumb' );

/**
 * Inspiro Single post video background functionality
 */

function wpzoom_single_post_video_settings() {
    global $post;

    $postmeta_videotype = get_post_meta( $post->ID, 'wpzoom_posts_popup_video_type', true );
    $post_meta          = empty( $postmeta_videotype ) ? 'self_hosted' : $postmeta_videotype;
    $tab_order          = get_post_meta( $post->ID, 'wpzoom_posts_tab_order', true ) === false ? 0 : get_post_meta( $post->ID, 'wpzoom_posts_tab_order', true );
    # single portfolio video background vars
    $postmeta_single_post_videotype   = get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_type', true );
    $post_meta_single_post_background = empty( $postmeta_single_post_videotype ) ? 'self_hosted' : $postmeta_single_post_videotype;
    # #single portfolio video background vars
    ?>
    <?php get_tabs_inline_style() ?>

    <div class="portfolio-tabs">
        <ul class="metabox-tabs">
            <!-- single portfolio video background tab -->
            <li data-tab-order="0" class="tab">
                <a class="<?php echo $tab_order == 0 ? 'active': '' ?>" href="#posts-background-single-post">Video Background in Header</a>
            </li>
            <!-- #single portfolio video background tab -->
            <input type="hidden" name="wpzoom_posts_tab_order" value="<?php echo esc_attr( $tab_order ); ?>" />
        </ul>
        <!-- single portfolio video background tab content -->
        <div class="zoom-tab" id="posts-background-single-post">
            <p class="description"><?php _e( 'In this area you can configure a video which will play in the background of the header area when viewing this page or post.', 'wpzoom' ); ?></p>

            <div class="radio-switcher">

                <h3><?php _e('Select Video Source:', 'wpzoom'); ?></h3>

                <input type="radio" name="wpzoom_posts_single_post_video_type" id="video_3" value="self_hosted" <?php checked( $post_meta_single_post_background, 'self_hosted' ); ?>>
                <label class="label_vid_self" for="video_3"><?php _e( 'Self-Hosted Video', 'wpzoom' ) ?>
                </label>

                 &nbsp;&nbsp;&nbsp;<input type="radio" id="video_4" name="wpzoom_posts_single_post_video_type" value="external_hosted" <?php checked( $post_meta_single_post_background, 'external_hosted' ); ?>>
                <label class="label_vid_url" for="video_4"><strong class="wpz_video_embed_icons"><i class="fa fa-youtube-play"></i><?php _e( 'YouTube', 'wpzoom' ) ?>  </strong></label>

                &nbsp;&nbsp;&nbsp;<input type="radio" id="video_5" name="wpzoom_posts_single_post_video_type" value="vimeo_pro" <?php checked( $post_meta_single_post_background, 'vimeo_pro' ); ?>>
                <label class="label_vid_url" for="video_5"><strong class="wpz_video_embed_icons"><i class="fa fa-vimeo"></i><?php _e( 'Vimeo', 'wpzoom' ) ?>  </strong></label>

            </div>


            <div class="wpzoom_self_hosted switch-wrapper">

                <br/>
                <div class="wp-media-buttons" data-button="Set Video" data-title="Set Video"
                     data-target="#wpzoom_posts_single_post_video_bg_url">
                    <a href="#" class="button add_media wpz-upload-video-control" title="Upload Video">
                        <span class="wp-media-buttons-icon"></span>
                        <?php _e( 'Upload Video', 'wpzoom' ); ?>
                    </a>
                </div>

                <div class="clear"></div>

                <p>
                    <label>
                        <strong><?php _e( 'MP4 (h.264) video URL', 'wpzoom' ); ?></strong>
                        <input type="text" name="wpzoom_posts_single_post_video_bg_url_mp4" id="wpzoom_posts_single_post_video_bg_url_mp4"
                               class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_bg_url_mp4', true ) ); ?>"/>

                               <p class="description"><?php _e( 'This format is supported by most of the browsers and mobile devices.', 'wpzoom' ); ?> Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.</p>
                    </label>
                </p>

                <div class="wpz_border"></div>

                <p>
                    <label>
                        <strong><?php _e( 'WebM video URL', 'wpzoom' ); ?></strong> <em>(optional)</em>
                        <input type="text" name="wpzoom_posts_single_post_video_bg_url_webm" id="wpzoom_posts_single_post_video_bg_url_webm"
                               class="widefat"
                               value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_bg_url_webm', true ) ); ?>"/>

                <p class="description"><?php _e( 'This format is optional for old versions of <strong>Mozilla Firefox</strong> that don\'t support <strong>MP4</strong> (v.21 and older).', 'wpzoom' ); ?>
                    </label>
                </p>

            </div>


            <div class="wpzoom_external_hosted switch-wrapper" style="display: inline-block; width: 100%;">
                <p>
                    <label
                        for="wpzoom_posts_single_post_video_external_url"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong>
                        <em>(YouTube videos only)</em></label>
                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_posts_single_post_video_external_url"
                           name="wpzoom_posts_single_post_video_external_url"
                           data-response-type="thumb"
                           class="preview-video-input widefat"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_external_url', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>

                <p class="description"><?php _e( '<strong>IMPORTANT:</strong> YouTube videos aren\'t supported in video background on mobile and tablet devices.', 'wpzoom' ); ?></p>

                <div class="wpzoom_video_external_preview">
                </div>
            </div>

            <div class="wpzoom_vimeo_pro switch-wrapper" style="display: inline-block; width: 100%;">
                <p>

                    <div class="description"><em>This method works best if you have a <strong>Vimeo PLUS, PRO or Business</strong> account. <br />If you have a <strong>Vimeo PRO</strong> account, you can also insert direct link to video file (MP4) hosted on Vimeo in the <strong>Self Hosted</strong> option.</em></div>

                    <br />

                    <label
                        for="wpzoom_posts_single_post_video_vimeo_pro"><strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong> <em>(e.g. https://vimeo.com/295116835)</em>
                    </label>
                    <span class="preview-video-input-span">
                    <input type="text"
                           id="wpzoom_posts_single_post_video_vimeo_pro"
                           name="wpzoom_posts_single_post_video_vimeo_pro"
                           data-response-type="thumb"
                           class="preview-video-input widefat"
                           value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_vimeo_pro', true ) ); ?>"/>
                        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
                             alt=""
                             class="wpzoom-preloader hidden"/>
                    </span>
                </p>
                <div class="wpzoom_video_external_preview">
                </div>
            </div>

            <div class="wpz_border"></div>

            <h3><?php _e( 'Video Background Controls', 'wpzoom' ) ?></h3>

            <p class="description">Video controls will appear in the bottom right corner</p>

            <p>
                <label>

                    <input type="hidden" name="wpzoom_posts_single_post_video_play_button" value="0"/>
                    <input type="checkbox" name="wpzoom_posts_single_post_video_play_button" id="wpzoom_posts_single_post_video_play_button" class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_play_button', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_play_button', true ) ); ?>/> <?php _e( 'Show Play/Pause Button', 'wpzoom' ) ?>

                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_posts_single_post_video_mute_button" value="0"/>
                    <input type="checkbox" name="wpzoom_posts_single_post_video_mute_button" id="wpzoom_posts_single_post_video_mute_button" class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_mute_button', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_mute_button', true ) ); ?>/> <?php _e( 'Show Mute/Unmute Button', 'wpzoom' ) ?>
                </label>
            </p>

            <div class="wpz_border"></div>


            <h3><?php _e( 'Video Background Options', 'wpzoom' ) ?></h3>

            <p>
                <label>


                    <input type="hidden" name="wpzoom_posts_single_post_video_autoplay" value="0"/>
                    <input type="checkbox" name="wpzoom_posts_single_post_video_autoplay" id="wpzoom_posts_single_post_video_autoplay"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_autoplay', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_autoplay', true ) ); ?>/> <?php _e( 'Autoplay Video', 'wpzoom' ) ?>
                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_posts_single_post_video_mute" value="0"/>
                    <input type="checkbox" name="wpzoom_posts_single_post_video_mute" id="wpzoom_posts_single_post_video_mute"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_mute', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_mute', true ) ); ?>/> <?php _e( 'Mute Video <em>(we recommend to keep your video Muted, as some browsers may block autoplay in videos with sound enabled, especially on mobile devices.)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            <p>
                <label>

                    <input type="hidden" name="wpzoom_posts_single_post_video_loop" value="0"/>
                    <input type="checkbox" name="wpzoom_posts_single_post_video_loop" id="wpzoom_posts_single_post_video_loop"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_loop', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_loop', true ) ); ?>/> <?php _e( 'Loop Video <em>(if unchecked, then the video will play just once)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            <p class="dnt">
                <label>

                    <input type="hidden" name="wpzoom_posts_single_post_video_dnt" value="0"/>
                    <input type="checkbox" name="wpzoom_posts_single_post_video_dnt" id="wpzoom_posts_single_post_video_dnt"
                           class="widefat"
                           value="1" <?php checked( get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_dnt', true ) == '' ? true : get_post_meta( $post->ID, 'wpzoom_posts_single_post_video_dnt', true ) ); ?>/> <?php _e( 'Track Session Data <em>(if unchecked, then will block the player from tracking any session data, including all cookies and stats)</em>', 'wpzoom' ) ?>
                </label>
            </p>
            </fieldset>


            <div class="wpz_border"></div>
            <p>
                <em><strong>Good to know:</strong></em><br/>
            <ol class="wpz_list">
                <li>If your server can't play MP4 videos, check this <a
                        href="http://www.wpzoom.com/docs/enable-mp4-video-support-linuxapache-server/"
                        target="_blank">tutorial</a> for a fix.
                </li>
                <li>Your <strong>MP4</strong> videos must have the <em>H.264</em> encoding. You can convert your videos with <a
                        href="https://handbrake.fr/downloads.php" target="_blank">HandBrake</a> video converter. Read <a href="https://www.wpzoom.com/docs/tips-for-optimizing-self-hosted-video-backgrounds/" target="_blank">this article</a> for <strong>self-hosted video optimization tips</strong>.
                </li>
                <li>Only <strong>self-hosted</strong> and <strong>Vimeo</strong> videos are supported on some mobile devices & tablets to play in background. Fo self-hosted videos, we recommended to use a small video file in order to prevent unsolicited data consumption, or you can <a href="https://www.wpzoom.com/documentation/inspiro/inspiro-video-background-support-in-slideshow/" target="_blank">set a GIF file</a> as Featured Image, and this will act as a video fallback on these devices.</li>
                </li>

            </ol>
            </p>
            <br/>
        </div>
        <!-- #single portfolio video background tab -->
    </div>

    <?php
}