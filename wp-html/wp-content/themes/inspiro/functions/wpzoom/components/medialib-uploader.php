<?php
/**
 * WPZOOM_Medialib_Uploader Class
 *
 * @package WPZOOM
 * @subpackage Medialib_Uploader
 */

add_action('after_setup_theme', array('WPZOOM_Medialib_Uploader', 'init'));

class WPZOOM_Medialib_Uploader {
    public static function init() {
        register_post_type('wpzoom', array(
            'labels' => array(
                'name' => __('WPZOOM Exporter', 'wpzoom'),
            ),
            'public' => true,
            'show_ui' => false,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false,
            'supports' => array( 'title', 'editor' ),
            'query_var' => false,
            'can_export' => false,
            'show_in_nav_menus' => false
        ));
    }

    public static function action($id, $value, $desc = '', $postid = 0, $name = '') {
        $output = '';
        $class = '';
        $int = self::getSilentPost($id);

        if ( $value ) { $class = ' has-file'; }

        $output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="'.$id.'" value="' . $value . '" />' . "\n";
        if (function_exists('wp_enqueue_media')) {
            $output .= '<input id="upload_' . $id . '" class="upload_button button" data-name="'. esc_attr($name) .'" type="button" value="' . __( 'Upload', 'wpzoom' ) . '" rel="' . $int . '" />' . "\n";
        } else {
            $output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'wpzoom' ) . '</i></p>';
        }

        if ($desc != '') {
            $output .= '<p>' . $desc . '</p>' . "\n";
        }
        $output .= '<div class="clear">&nbsp;</div>';
        $output .= '<div class="screenshot" id="' . $id . '_image">' . "\n";

        if ( $value != '' ) {
            $remove = '<a href="#" class="mlu_remove"></a>';
            $image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
            if ( $image ) {
                $output .= '<img src="' . $value . '" alt="' . basename($value) . '" />'.$remove.'';
            } else {
                $parts = explode( "/", $value );
                for( $i = 0; $i < sizeof( $parts ); ++$i ) {
                    $title = $parts[$i];
                }

                $title = __( 'View File', 'wpzoom');
                $output .= '<div class="no_image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span>' . $remove . '</div>';
            }
        }
        $output .= "</div>\n";

        return $output;
    }

    public static function getSilentPost($token) {
        global $wpdb;

        $args = array( 'post_type' => 'wpzoom', 'post_name' => $token, 'post_status' => 'draft', 'comment_status' => 'closed', 'ping_status' => 'closed' );

        $query = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_parent = 0';
        foreach ( $args as $k => $v ) {
            $query .= ' AND ' . $k . ' = "' . $v . '"';
        }

        $query .= ' LIMIT 1';
        $posts = $wpdb->get_row( $query );

        if ( ! empty( $posts ) ) {
            $id = $posts->ID;
        } else {
            $words = explode('_', $token);
            $title = join(' ', $words);
            $title = ucwords($title);
            $post_data = array('post_title' => $title);
            $post_data = array_merge($post_data, $args);
            $id = wp_insert_post($post_data);
        }

        return $id;
    }
}
