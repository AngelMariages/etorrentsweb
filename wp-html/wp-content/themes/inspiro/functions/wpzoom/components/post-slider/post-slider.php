<?php

class ZOOM_Post_Slider {
    private $sliders = array();

    public function __construct( $args ) {
        foreach ( $args as $sliders ) {
            if ( is_string( $sliders ) ) {
                $this->sliders[ $sliders ] = array();
            } else {
                foreach ($sliders as $key => $slider_args) {
                    $this->sliders[ $key ] = $slider_args;
                }
            }
        }

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_head-post-new.php', array( $this, 'admin_head' ), 100);
        add_action( 'admin_head-post.php', array( $this, 'admin_head' ), 100);
        add_action( 'wp_ajax_wpzoom_sliderthumb_get', array( $this, 'sliderthumb_get' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
    }

    /**
     * Register slider meta boxes.
     *
     * @return void
     */
    public function add_meta_boxes() {
        foreach ( $this->sliders as $page => $args ) {
            add_meta_box(
                'page-slider_' . $page,         // $id
                __('Slider', 'wpzoom'),         // $title
                array( $this, 'show_metabox' ), // $callback
                $page,                          // $page
                'normal',                       // $context
                'high',                         // $priority
                $args
            );
        }
    }

    /**
     * Displays custom metabox with slider options.
     *
     * @param $post
     * @param $metabox
     * 
     * @return void
     */
    public function show_metabox( $post, $metabox ) {
        $defaults = array(
            'image' => true,
            'video' => true
        );

        $args = wp_parse_args( $metabox['args'], $defaults );

        $meta = get_post_meta( $post->ID, 'wpzoom_slider', true );

        $i = 1;

        $default_image = WPZOOM::$assetsPath . '/images/components/post-slider/image.png';

        $html = '<li id="wpzoom_slider_%1$d" class="%2$s">
                    <span class="sort hndle button" title="' . __('Click and drag to reorder this slide', 'wpzoom') . '"><img src="' . WPZOOM::$assetsPath . '/images/components/post-slider/move.png" /></span>
                    <span class="wpzoom_slide_remove button" title="' . __('Click to remove this slide', 'wpzoom') . '">&times;</span>
                    <div class="wpzoom_slide_type">
                        <input name="wpzoom_slider[%1$d][slideType]" type="hidden" class="wpzoom_slide_type_input" value="%2$s" />'

                        . '<span class="button">' . __('Type: ', 'wpzoom')
                            . ($args['image'] ? '<a href="" class="wpzoom_slide_type_image">' . __('Image', 'wpzoom') . '</a>' : '')
                            . ($args['video'] ? ' | <a href="" class="wpzoom_slide_type_video">' . __('Video', 'wpzoom') . '</a>' : '')
                        . '</span>'

                    . '</div>

                    <div class="wpzoom_slide_preview"%6$s>
                        <img src="%4$s" height="180" width="250" class="wpzoom_slide_preview_image" data-defaultimg="' . $default_image . '" />
                        <textarea name="wpzoom_slider[%1$d][embedCode]" class="wpzoom_slide_embed_code code" placeholder="' . __('Paste embed code here...', 'wpzoom') . '">%5$s</textarea>

                        <div class="wpzoom_slide_actions">
                            <input name="wpzoom_slider[%1$d][imageId]" type="hidden" class="wpzoom_slide_upload_image" value="%3$s" />
                            <span class="wpzoom_slide_upload_image_button button">' . __('Choose Image', 'wpzoom') . '</span>
                            <span class="wpzoom_slide_clear_image_button button%8$s">' . __('Remove Image', 'wpzoom') . '</span>
                        </div>
                    </div>

                    <textarea name="wpzoom_slider[%1$d][caption]" rows="1" placeholder="' . __('Enter title', 'wpzoom') . '" class="wpzoom_slide_caption">%7$s</textarea><br />
                    <textarea name="wpzoom_slider[%1$d][description]" rows="3" placeholder="' . __('Enter description (HTML allowed)&hellip;', 'wpzoom') . '" class="wpzoom_slide_description">%9$s</textarea>
                </li>';

        echo '<input type="hidden" name="wpzoom_slider_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';

        echo '<div class="slider_btn_add"><a class="wpzoom_slide_add button" href="#">' . __('+ Add Slide', 'wpzoom') . '</a><br class="clear"></div><ul class="wpzoom_slider' . (is_array($meta) && count($meta) <= 1 ? ' onlyone' : '') . '">';

        if ( !empty( $meta ) ) {

            foreach ( $meta as $item ) {

                $type = isset( $item['slideType'] ) && $item['slideType'] == 'video' ? 'video' : 'image';
                $id = isset( $item['imageId'] ) && is_numeric( $item['imageId'] ) ? intval( $item['imageId'] ) : 0;
                $src = wp_get_attachment_image_src( $id, 'medium' );
                $image = $id > 0 ? $src[0] : '';
                $embed = isset( $item['embedCode'] ) && !empty( $item['embedCode'] ) ? trim( $item['embedCode'] ) : '';
                $embedimg = !empty( $embed ) && false !== ( $url = WPZOOM_Video_API::extract_url_from_embed( $embed ) ) && false !== ( $img = WPZOOM_Video_Thumb::fetch_video_thumbnail( $url, $post->ID ) ) && isset( $img['thumb_url'] ) && !empty( $img['thumb_url'] ) ? ' style="background-image:url(\'' . esc_url( $img['thumb_url'] ) . '\')"' : '';
                $disabled = empty( $image ) || $image == $default_image ? ' button-disabled' : '';
                $caption = isset( $item['caption'] ) && !empty( $item['caption'] ) ? trim( $item['caption'] ) : '';
                $description = isset( $item['description'] ) && !empty( $item['description'] ) ? trim( $item['description'] ) : '';

                printf( $html, $i, $type, ( $type == 'image' ? $id : '' ), ( $type == 'image' && !empty( $image ) ? $image : $default_image ), ( $type == 'video' ? $embed : '' ), ( $type == 'video' ? $embedimg : '' ), $caption, $disabled, $description );

                $i++;

            }

        } else {

            printf( $html, $i, 'image', 0, $default_image, '', '', '', ' button-disabled', '');

        }

        echo '</ul><br class="clear" />';
    }

    public function save_post( $post_id ) {
        // verify nonce
        if (! isset($_POST['wpzoom_slider_meta_box_nonce']) || ! wp_verify_nonce($_POST['wpzoom_slider_meta_box_nonce'], basename(__FILE__)))
            return $post_id;
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
        }


        $slides = isset($_POST['wpzoom_slider']) ? (array)$_POST['wpzoom_slider'] : array();
        $new = array();

        foreach ( $slides as $slide ) {
            $type = isset( $slide['slideType'] ) && $slide['slideType'] == 'video' ? 'video' : 'image';
            $id = isset( $slide['imageId'] ) && is_numeric( $slide['imageId'] ) ? intval( $slide['imageId'] ) : 0;
            $embed = isset( $slide['embedCode'] ) && !empty( $slide['embedCode'] ) ? trim( $slide['embedCode'] ) : '';
            $caption = isset( $slide['caption'] ) && !empty( $slide['caption'] ) ? trim( $slide['caption'] ) : '';
            $description = isset( $slide['description'] ) && !empty( $slide['description'] ) ? trim( $slide['description'] ) : '';

            $new_arr = array( 'slideType' => $type );

            if ( $type == 'image' && $id > 0 )
                $new_arr['imageId'] = $id;
            elseif ( $type == 'video' && !empty( $embed ) )
                $new_arr['embedCode'] = $embed;

            if ( !empty( $caption ) ) $new_arr['caption'] = $caption;
            if ( !empty( $description ) ) $new_arr['description'] = $description;

            if ( !isset( $new_arr['imageId'] ) && !isset( $new_arr['embedCode'] ) && !isset( $new_arr['caption'] ) && !isset( $new_arr['description'] ) ) continue;

            $new[] = $new_arr;
        }

        update_post_meta($post_id, 'wpzoom_slider', $new);
    }

    public function admin_head() {
        ?>
        <style type="text/css">
        .slider_btn_add { margin:22px 0 0 10px; }
        #poststuff .inside, .wpzoom_slider { position: relative;  }
        .wpzoom_slider li, .wpzoom_slider li * { margin: 0; }
        .wpzoom_slider li {  display: inline-block; vertical-align: top; position: relative; text-align: center; background-color: #eee; padding: 5px; border: 1px solid #e0e0e0; border-radius: 4px; margin: 10px !important; }
        .wpzoom_slider li .hndle, .wpzoom_slider li .wpzoom_slide_remove { display: none; position: absolute; top: -6px; z-index: 10; height: auto; padding: 4px; border-radius: 50%; }
        .wpzoom_slider li:hover .hndle, .wpzoom_slider li:hover .wpzoom_slide_remove { display: block; }
        .wpzoom_slider li .hndle { left: -6px; }
        .wpzoom_slider li .hndle img { display: block; height: 16px; width: 16px; }
        .wpzoom_slider li .wpzoom_slide_remove { right: -6px; font-size: 18px !important; line-height: 11px; }
        .wpzoom_slider.onlyone li .wpzoom_slide_remove { display: none; }
        .wpzoom_slider li .wpzoom_slide_remove:hover, .wpzoom_slider li .wpzoom_slide_remove:active { color: red; border-color: red; }
        .wpzoom_slide_preview { display: block; position: relative; background: #f7f7f7 url('<?php echo WPZOOM::$assetsPath . '/images/components/post-slider/image.png' ?>') center no-repeat; background-size: cover; min-height: 167px; width: 250px; border: 1px solid #ccc; margin-bottom: 8px !important; }
        .video .wpzoom_slide_preview { background-image: url('<?php echo WPZOOM::$assetsPath . '/images/components/post-slider/video.png' ?>'); }
        .wpzoom_slide_preview_image { display: block; top: 0; left: 0; right: 0; bottom: 0; background: #fff; height: 100%; width: 100%; border: 0; outline: none; }
        .video .wpzoom_slide_preview_image { display: none; }
        .wpzoom_slide_embed_code { display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; text-shadow: 0 0 3px #f7f7f7, 0 0 3px #f7f7f7, 0 0 3px #f7f7f7, 0 0 3px #f7f7f7, 0 0 3px #f7f7f7, 0 0 3px #f7f7f7, 0 0 3px #f7f7f7; background: rgba(247, 247, 247, 0.7); height: 100%; width: 100%; resize: none; padding: 8px; border: 0; -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0; }
        .wpzoom_slider li.video:hover .wpzoom_slide_embed_code, .wpzoom_slide_embed_code:focus { display: block; }
        .wpzoom_slide_type, .wpzoom_slide_actions { display: none; position: absolute; left: 0; right: 0; text-align: center; }
        .wpzoom_slider li:hover .wpzoom_slide_actions, .wpzoom_slider li:hover .wpzoom_slide_type { display: block; }
        .wpzoom_slider li.video:hover .wpzoom_slide_actions { display: none; }
        .wpzoom_slide_actions { top: 78px; }
        .video .wpzoom_slide_actions { display: none; }
        .wpzoom_slide_type { top: -6px; z-index: 9; }
        .wpzoom_slide_type a { text-decoration: none; color: #21759b; }
        .wpzoom_slide_actions span, .wpzoom_slide_type span { /*line-height: 16px; color: #555; background: #f3f3f3; background: -moz-linear-gradient(top, #fefefe, #f4f4f4); background: -webkit-linear-gradient(top, #fefefe, #f4f4f4); background: linear-gradient(to bottom, #fefefe, #f4f4f4); padding: 2px 6px; border: 1px solid #ccc;*/ }
        .wpzoom_slide_actions span { /*cursor: pointer; border-top: 0;*/ }
        .wpzoom_slide_actions span.wpzoom_slide_upload_image_button { /*-moz-border-radius-bottomleft: 4px; -webkit-border-bottom-left-radius: 4px; border-bottom-left-radius: 4px;*/ }
        .wpzoom_slide_actions span.wpzoom_slide_clear_image_button { /*border-left: 0; -moz-border-radius-bottomright: 4px; -webkit-border-bottom-right-radius: 4px; border-bottom-right-radius: 4px;*/ }
        .wpzoom_slide_type span { cursor: default !important; font-size: 10px !important; line-height: 16px !important; height: auto !important; }
        a.wpzoom_slide_type_image { cursor: default; font-weight: bold; color: #555; }
        a.wpzoom_slide_type_video:hover, a.wpzoom_slide_type_video:active { color: #d54e21; }
        .video a.wpzoom_slide_type_image { cursor: pointer; font-weight: normal; color: #21759b; }
        .video a.wpzoom_slide_type_image:hover, .video a.wpzoom_slide_type_image:active { color: #d54e21; }
        .video a.wpzoom_slide_type_video { cursor: default; font-weight: bold; color: #555; }
        .wpzoom_slider li .wpzoom_slide_caption { width: 100%; resize:vertical; }
        .wpzoom_slider li .wpzoom_slide_url { width: 100%; margin:6px 0 0; }
        .wpzoom_slider li .wpzoom_slide_description { width:250px; resize:vertical; margin:6px 0 0; }
        .wpzoom_slider li br { display: block; }
        </style>
    <?php
    }

    public function assets( $hook ) {
        global $typenow;

        if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) return;
        if ( isset( $typenow ) && ! array_key_exists( $typenow, $this->sliders ) ) return;

        // Register the script
        wp_register_script( 'zoom-post-slider', WPZOOM::$assetsPath . '/js/post-slider.js', array( 'jquery', 'media-upload', 'thickbox' ), WPZOOM::$wpzoomVersion );

        // Localize the script with new data
        $localize = array(
            'strings' => array(
                'pick_image' => __('Pick an Image for This Slide', 'wpzoom'),
                'btn_text' => __('Use This Image', 'wpzoom'),
            )
        );
        wp_localize_script( 'zoom-post-slider', 'zoomPostSlider', $localize );

        // Enqueued script with localized data.
        wp_enqueue_script( 'zoom-post-slider' );
    }

    public function sliderthumb_get() {
        if ( isset( $_POST['wpzoom_sliderthumb_embedcode'] ) && isset( $_POST['wpzoom_sliderthumb_postid'] ) ) {
            $url = WPZOOM_Video_API::extract_url_from_embed( trim( stripslashes( $_POST['wpzoom_sliderthumb_embedcode'] ) ) );
            $postid = intval( $_POST['wpzoom_sliderthumb_postid'] );

            if ( empty( $url ) || filter_var( $url, FILTER_VALIDATE_URL ) === false || $postid < 1 ) {
                wp_send_json_error();
            }

            $thumb_url = WPZOOM_Video_Thumb::fetch_video_thumbnail( $url, $postid );
            header( 'Content-type: application/json' );
            if ( $thumb_url === false ) {
                wp_send_json_error();
            } else {
                wp_send_json_success( $thumb_url );
            }
        }
    }
}
