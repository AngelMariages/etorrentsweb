<?php
/**
 * WPZOOM_Admin
 *
 * @package WPZOOM
 * @subpackage Admin
 */

new WPZOOM_Admin();

class WPZOOM_Admin {

    /**
     * Initialize wp-admin options page
     */
    public function __construct() {
        add_action('after_switch_theme', array($this, 'start_page_redirect'));

        if (isset($_GET['page']) && $_GET['page'] == 'wpzoom_options') {
            add_action('init', array('WPZOOM_Admin_Settings_Page', 'init'));
        }

        add_action('admin_menu', array($this, 'register_admin_pages'));
        add_action('admin_footer', array($this, 'activate'));

        add_action('wp_ajax_wpzoom_ajax_post',       array('WPZOOM_Admin_Settings_Page', 'ajax_options'));
        add_action('wp_ajax_wpzoom_widgets_default', array('WPZOOM_Admin_Settings_Page', 'ajax_widgets_default'));
        add_action('wp_ajax_wpzoom_demo_content',    array('WPZOOM_Admin_Settings_Page', 'ajax_demo_content'));
        add_action('wp_ajax_wpzoom_erase_demo_content',    array('WPZOOM_Admin_Settings_Page', 'ajax_erase_demo_content'));
        add_action('wp_ajax_wpzoom_update_nav_menu_location', array('WPZOOM_Admin_Settings_Page', 'ajax_update_nav_menu_location'));

        add_action('admin_print_scripts-widgets.php', array($this, 'widgets_styling_script'));
        add_action('admin_print_scripts-widgets.php', array($this, 'widgets_styling_css'));

        add_action('admin_enqueue_scripts', array($this, 'wpadmin_script'));
        add_action('admin_enqueue_scripts',  array($this, 'wpadmin_css'));
        add_action('admin_enqueue_scripts',  array($this, 'load_gutenberg_scripts'));
    }

    function start_page_redirect(){
        $ignored_themes = get_deprecated_themes();
        $redirect_page = in_array(WPZOOM::$theme_raw_name, $ignored_themes) ? 'wpzoom_options' : 'wpzoom-license';
        header('Location: admin.php?page='.$redirect_page);
    }
    public function widgets_styling_script() {
        wp_enqueue_script('wpzoom_widgets_styling', WPZOOM::$assetsPath . '/js/widgets-styling.js', array('jquery'));
    }

    public function widgets_styling_css() {
        wp_enqueue_style('wpzoom_widgets_styling', WPZOOM::$assetsPath . '/css/widgets-styling.css');
    }

    public function wpadmin_script() {
        wp_enqueue_script('zoom-wp-admin', WPZOOM::$assetsPath . '/js/wp-admin.js', array('jquery', 'wp-util'), WPZOOM::$wpzoomVersion);
        wp_localize_script('zoom-wp-admin', 'zoomFramework', array(
            'rootUri'           => WPZOOM::get_root_uri(),
            'assetsUri'         => WPZOOM::get_assets_uri(),
        ));
        wp_enqueue_style( 'zoom-font-awesome', WPZOOM::$assetsPath . '/css/font-awesome.min.css' );
    }

    public function wpadmin_css() {
        wp_enqueue_style('zoom-wp-admin', WPZOOM::get_assets_uri() . '/css/wp-admin.css', array(), WPZOOM::$wpzoomVersion);
    }

    /**
     * Load Gutenberg Metaboxes script compatibility
     *
     * @package WPZOOM
     * @subpackage Admin
     *
     **/
    public function load_gutenberg_scripts() {

        if ( function_exists('gutenberg_get_block_categories') || function_exists('get_block_categories')  ) {
            wp_enqueue_script(
                'zoom-wp-admin-gutenberg-metaboxes',
                WPZOOM::$assetsPath . '/js/admin.gutenberg-metabox-compatibility.js',
                array( 'jquery' ),
                WPZOOM::$wpzoomVersion
            );
        }

    }

    public function activate() {
        if (option::get('wpzoom_activated') != 'yes') {
            option::set('wpzoom_activated', 'yes');
            option::set('wpzoom_activated_time', time());
        } else {
            $activated_time = option::get('wpzoom_activated_time');
            if ((time() - $activated_time) < 2592000) {
                return;
            }
        }

        option::set('wpzoom_activated_time', time());

        $ignored_themes = get_deprecated_themes();

        if ( ! in_array( WPZOOM::$theme_raw_name, $ignored_themes ) ) {
            require_once( WPZOOM_INC . '/pages/welcome.php' );
        }
    }

    public function admin() {
        require_once(WPZOOM_INC . '/pages/admin.php');
    }

    public function themes() {
        require_once(WPZOOM_INC . '/pages/themes.php');
    }

    public function update() {
        require_once(WPZOOM_INC . '/pages/update.php');
    }

    /**
     * WPZOOM custom menu for wp-admin
     */
    public function register_admin_pages() {
        add_menu_page( __('Page Title', 'wpzoom'), __('WPZOOM', 'wpzoom'), 'manage_options','wpzoom_options', array($this, 'admin'), 'none', 40);

        add_submenu_page('wpzoom_options', __('WPZOOM', 'wpzoom'), __('Theme Options', 'wpzoom'), 'manage_options', 'wpzoom_options', array($this, 'admin'));

        if ( file_exists( get_template_directory() . '/functions/customizer' ) ) {
            $customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
            add_submenu_page( 'wpzoom_options', __('Customize', 'wpzoom'), __('Customize', 'wpzoom'), 'customize', esc_url( $customize_url ) );
        }

        if (option::is_on('framework_update_enable')) {
            add_submenu_page('wpzoom_options', __('Update Framework', 'wpzoom'), __('Update Framework', 'wpzoom'), 'update_themes', 'wpzoom_update', array($this, 'update'));
        }

        if (option::is_on('framework_newthemes_enable') && !wpzoom::$tf) {
            add_submenu_page('wpzoom_options', __('New Themes', 'wpzoom'), __('New Themes', 'wpzoom'), 'manage_options', 'wpzoom_themes', array($this, 'themes'));
        }
    }
}
