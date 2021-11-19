<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WPZOOM Child Theme
 */
class ZOOM_Child_Theme {
    /**
     * Current theme.
     *
     * @var object WP_Theme
     */
    public static $theme;

    /**
     * Current theme slug.
     *
     * @var string Theme slug
     */
    public static $slug;

    /**
     * The base path where parent theme is located.
     *
     * @var array $strings
     */
    public static $base_path = null;

    /**
     * The base url where parent theme is located.
     *
     * @var array $strings
     */
    public static $base_url = null;


    public function __construct() {

        // Set arguments
        self::$base_path = get_parent_theme_file_path();
        self::$base_url  = get_parent_theme_file_uri();

        // Retrieve a WP_Theme object.
        self::$theme = wp_get_theme();
        self::$slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', self::$theme->template ) );

        add_filter( 'zoom_option_files', array( $this, 'register_options' ) );
        add_action( 'wp_ajax_zoom_install_child_theme', array( $this, 'install_child_theme' ), 10, 0 );
        add_action( 'load-toplevel_page_wpzoom_options', array( $this, 'wpzoom_page_options_callback' ) );
        add_action( 'switch_theme', array( $this, 'switch_theme_update_mods' ) );
    }

    /**
     * Register component options for zoom framework.
     *
     * @param  array $zoom_options
     * @return array
     */
    public function register_options( $zoom_options ) {
        $child_theme_file_exists = self::child_theme_file_exists();

        if ( $child_theme_file_exists ) {
            $zoom_options[] = sprintf( '%s/options.php', dirname( __FILE__ ) );
        }

        return $zoom_options;
    }

    public function wpzoom_page_options_callback() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            'zoom-child-theme',
            self::get_js_uri( 'general.js' ),
            array( 'jquery', 'underscore' ),
            WPZOOM::$wpzoomVersion,
            true
        );
    }

    /**
     * Install the child theme via AJAX.
     */
    public function install_child_theme() {
        
        $name = self::$theme . ' Child';
        $slug = sanitize_title( $name );

        $themes_path = get_theme_root();
        $path        = $themes_path . '/' . $slug;
        $success     = '';
        $already     = '';

        $location    = isset( $_POST['location'] ) ? $_POST['location'] : '';

        if ( 'zoomForm-tab-content' === $location ) {

            $auto_activate           = isset( $_POST['child_theme_auto_activate'] ) && $_POST['child_theme_auto_activate'] === 'true' ? 'on' : 'off';
            $keep_parent_settings    = isset( $_POST['child_theme_keep_parent_settings'] ) && $_POST['child_theme_keep_parent_settings'] === 'true' ? 'on' : 'off';

            option::set( 'child_theme_auto_activate', $auto_activate );
            option::set( 'child_theme_keep_parent_settings', $keep_parent_settings );

        }

        if ( 'zoomForm-theme-setup' === $location ) {

            $advanced_settings_data = isset( $_POST['advanced_settings'] ) ? $_POST['advanced_settings'] : array();
            $auto_activate          = isset( $advanced_settings_data['child_theme_auto_activate'] ) && $advanced_settings_data['child_theme_auto_activate'] === 'true' ? 'on' : 'off';
            $keep_parent_settings   = isset( $advanced_settings_data['child_theme_keep_parent_settings'] ) && $advanced_settings_data['child_theme_keep_parent_settings'] === 'true' ? 'on' : 'off';

            option::set( 'child_theme_auto_activate', $auto_activate );
            option::set( 'child_theme_keep_parent_settings', $keep_parent_settings );
            
        }

        $auto_activate = option::is_on( 'child_theme_auto_activate' );

        // Check if we don't have already child theme created
        if ( ! file_exists( $path ) ) {

            WP_Filesystem();

            global $wp_filesystem;

            $child_theme_file_exists = self::child_theme_file_exists();

            if ( ! $child_theme_file_exists ) {
                wp_send_json_error(
                    array(
                        'done'    => 0,
                        'message' => sprintf( __( 'The .zip file %s doesn\'t exist.', 'wpzoom' ), $slug ),
                        'debug'   => __( 'The zip file could not be found in the parent theme directory.', 'wpzoom' ),
                    )
                );

                exit();
            }

            // Unzip child theme file to themes folder
            $destination_file = self::get_destination_file();
            $unzipfile = unzip_file( $destination_file . '/'. $slug .'.zip', $themes_path );
               
            if ( is_wp_error( $unzipfile ) ) {
                wp_send_json_error(
                    array(
                        'done'    => 0,
                        'message' => esc_html__( 'There was an error unzipping the file.', 'wpzoom' ),
                        'debug'   => sprintf( __( 'The .zip file %s doesn\'t exist.', 'wpzoom' ), $slug ),
                    )
                );

                exit();
            }

        } else {

            $already = esc_html__( 'Your child theme has already been installed.', 'wpzoom' );

            if ( self::$theme->template !== $slug ) :
                update_option( 'zoom_' . self::$slug . '_child', $name );

                if ( $auto_activate ) {
                    switch_theme( $slug );

                    $already = esc_html__( 'Your child theme has already been installed and is now activated.', 'wpzoom' );
                }
            endif;

            wp_send_json(
                array(
                    'done'    => 1,
                    'message' => $already,
                    'debug'   => sprintf( __( 'The existing child theme %s was activated', 'wpzoom' ), $slug ),
                )
            );

            exit();

        }


        $success = esc_html__( 'Your child theme has been installed.', 'wpzoom' );

        if ( self::$theme->template !== $slug ) :
            update_option( 'zoom_' . self::$slug . '_child', $name );

            if ( $auto_activate ) {
                switch_theme( $slug );

                $success = esc_html__( 'Your child theme has been installed and is now activated.', 'wpzoom' );
            }
        endif;

        wp_send_json(
            array(
                'done'    => 1,
                'message' => $success,
                'debug'   => sprintf( __( 'The child theme %s was activated', 'wpzoom' ), $slug ),
            )
        );
    }

    public function switch_theme_update_mods() {

        $keep_parent_settings = option::is_on( 'child_theme_keep_parent_settings' );

        if ( ! is_child_theme() && $keep_parent_settings ) {

            $mods = get_option( 'theme_mods_' . self::$slug );

            if ( false !== $mods ) {

                foreach ( (array) $mods as $mod => $value ) {
                    set_theme_mod( $mod, $value );
                }
            }
        }

    }

    public static function get_assets_uri( $endpoint = '' ) {
        return WPZOOM::$wpzoomPath . '/components/child-theme/assets/' . $endpoint;
    }

    public static function get_js_uri( $endpoint = '' ) {
        return self::get_assets_uri( 'js/' . $endpoint );
    }

    public static function child_theme_file_exists() {
        $name = self::$theme . ' Child';
        $slug = sanitize_title( $name );
        $destination_file = self::get_destination_file();

        return file_exists( $destination_file . '/'. $slug .'.zip' );
    }

    public static function get_destination_file() {
        $destination_file = get_template_directory();

        // The .zip file with child-theme doesn't exist in the theme root
        // otherwise check functions folder
        if ( ! file_exists( $destination_file ) ) {
            $destination_file = get_template_directory() . '/functions';
        }

        return $destination_file;
    }

}

new ZOOM_Child_Theme;