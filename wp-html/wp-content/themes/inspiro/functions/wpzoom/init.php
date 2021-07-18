<?php
/**
 * WPZOOM Framework Integration
 */

require_once WPZOOM_INC . "/functions.php";
require_once WPZOOM_INC . "/wpzoom.php";
require_once WPZOOM_INC . "/components/option.php";

/* Initialize WPZOOM Framework */
WPZOOM::init();

/* Only WordPress dashboard needs these files */
if (is_admin()) {
    require_once WPZOOM_INC . "/components/medialib-uploader.php";
    require_once WPZOOM_INC . "/components/admin/admin.php";
    require_once WPZOOM_INC . "/components/admin/settings-fields.php";
    require_once WPZOOM_INC . "/components/admin/settings-interface.php";
    require_once WPZOOM_INC . "/components/admin/settings-page.php";
    require_once WPZOOM_INC . "/components/admin/settings-sanitization.php";
    require_once WPZOOM_INC . "/components/dashboard/dashboard.php";

    require_once WPZOOM_INC . "/components/updater/updater.php";
    require_once WPZOOM_INC . "/components/updater/framework-updater.php";
    require_once WPZOOM_INC . "/components/updater/theme-updater.php";
}

/* Video API */
require_once WPZOOM_INC . "/components/video-api.php";
if (is_admin()) {
    require_once WPZOOM_INC . "/components/video-thumb.php";
}

/* Load get the image file only when it's not installed as a plugin */
if (!function_exists('get_the_image')) {
    require_once WPZOOM_INC . "/components/get-the-image.php";
}

require_once WPZOOM_INC . "/components/theme/ui.php";

if (!is_admin()) {
    require_once WPZOOM_INC . "/components/theme/theme.php";
    WPZOOM_Theme::init();
}

/**
 * Delay `zoom_load_components` function to run after `functions.php` file is
 * executed. This is needed because we load components code iff somewhere is
 * stated that this theme supports a zoom component via `add_theme_support`.
 *
 * In `functions.php` and other code loaded directly by this file we need to
 * wrap access to option values (option::get) into functions that run in
 * `after_setup_theme` action with priority higher than 10, otherwise it will
 * get wrong value on first load.
 */
add_action('after_setup_theme', 'zoom_load_components', 5);

function zoom_load_components()
{
    if (current_theme_supports('zoom-portfolio')) {
        require_once WPZOOM_INC . "/components/portfolio/portfolio.php";
        new ZOOM_Portfolio;
    }

    if (current_theme_supports('zoom-post-slider')) {
        require_once WPZOOM_INC . "/components/post-slider/post-slider.php";
        new ZOOM_Post_Slider(get_theme_support('zoom-post-slider'));
    }

    $ignored_themes = get_deprecated_themes();

    if (!in_array(WPZOOM::$theme_raw_name, $ignored_themes)) {

        if ( is_admin() ) {
            require_once WPZOOM_INC . "/components/demo-importer/demo-importer.php";
            new ZOOM_Demo_Importer;

            require_once WPZOOM_INC . "/components/child-theme/child-theme.php";
        }

        require_once WPZOOM_INC . "/components/theme-setup/wpzoom-theme-setup.php";
        new WPZOOM_Theme_Setup;


        require_once WPZOOM_INC . "/components/theme-updater/theme-updater.php";

        require_once WPZOOM_INC . "/components/customizer/setup.php";

        require_once WPZOOM_INC . "/components/customizer/controls.php";

        if( is_readable( get_template_directory(). "/functions/customizer/customizer-data.php") ) {
            require_once get_template_directory() . "/functions/customizer/customizer-data.php";
        }

        if( is_readable( get_template_directory(). "/functions/customizer/customizer-style-kits.php") ) {
            require_once get_template_directory() . "/functions/customizer/customizer-style-kits.php";
        }

        new WPZOOM_Customizer_Controls( apply_filters( 'wpzoom_customizer_data_add_stylekits', apply_filters( 'wpzoom_customizer_data', array() ) ) );

        require_once WPZOOM_INC . "/components/theme-tour/theme-tour.php";
    }


    if ( current_theme_supports( 'wpz-featured-posts-settings' ) && current_user_can( 'edit_posts' ) ) {

        require_once WPZOOM_INC . "/components/featured-posts/wpzoom-featured-posts.php";

        $featured_posts_directory_uri      = get_template_directory_uri() . '/functions/wpzoom/components/featured-posts/';
        $list_table_checkbox_directory_uri = get_template_directory_uri() . '/functions/wpzoom/components/featured-posts/list-table-checkbox';
        $wrapped_settings                  = get_theme_support( 'wpz-featured-posts-settings' );
        $settings                          = array_pop( $wrapped_settings );

        foreach ( $settings as $setting ) {
            if ( $setting['show'] ) {

                if ( ! empty( $setting['name'] ) ) {
                    new WPZOOM_List_Table_Checkbox_Option_Type( $setting, $list_table_checkbox_directory_uri );
                }

                new WPZOOM_Featured_Posts( $setting, $featured_posts_directory_uri );
            }
        }
    }

    if( file_exists(get_template_directory() . '/functions/helper_guide.md')){
        require_once WPZOOM_INC . "/components/helper-guide/wpzoom-helper-guide.php";
        $helper_guide_directory_uri      = get_template_directory_uri() . '/functions/wpzoom/components/helper-guide/';

        $markdown_url = get_template_directory_uri() . '/functions/helper_guide.md';
        $helper_guide = new WPZOOM_Helper_Guide($helper_guide_directory_uri);

        if( ! option::is_on( 'framework_helper_guide' )){
            $helper_guide->init( $markdown_url );
        }
    }

    if ( current_theme_supports( 'wpz-background-video-on-hover' ) ) {

        init_video_background_on_hover_module();
    }


    /**
     * Enabled wisdom tracker for framework.
     */
    if ( ! option::is_on( 'framework_track_data_enable' ) && ! class_exists( 'Plugin_Usage_Tracker' ) ) {
        require_once WPZOOM_INC . "/components/tracking/class-plugin-usage-tracker.php";

        $server_url = 'https://wpzoom.com';

        new Plugin_Usage_Tracker(
            get_template_directory() . '/functions.php',
            $server_url,
            array(),
            true,
            true,
            2
        );

    }

    /**
     * Disable Block-based Widgets Screen
     */
    if ( current_theme_supports( 'widgets-block-editor' ) && option::is_on( 'disable_widgets_block_editor' ) ) {
        remove_theme_support( 'widgets-block-editor' );
    }

}


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once WPZOOM_INC . "/components/tgmpa/class-tgm-plugin-activation.php";

add_action('tgmpa_register', 'zoom_register_theme_required_plugins');

/**
 * Register the required plugins for this theme.
 */
function zoom_register_theme_required_plugins()
{

    /**
     * Filter `zoom_register_theme_required_plugins` to add your custom plugins from themes.
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = apply_filters('zoom_register_theme_required_plugins', array(

        array(
            'name' => 'Social Icons Widget by WPZOOM', // The plugin name.
            'slug' => 'social-icons-widget-by-wpzoom', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),

        array(
            'name' => 'Contact Form by WPForms', // The plugin name.
            'slug' => 'wpforms-lite', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        )
    ));

    /**
     * Filter `zoom_tgmpa_config_filter` to change your tgmpa config from themes.
     * Array of configuration settings. Amend each line as needed.
     */
    $config = apply_filters('zoom_tgmpa_config_filter', array(
        'id' => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'wpzoom_options',            // Parent menu slug.
        'capability' => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true,                    // Show admin notices or not.
        'dismissable' => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message' => '',                      // Message to output right before the plugins table.

    ));

    tgmpa($plugins, $config);
}


/**
 * Inject into Featured Plugins tab , our list of plugins.
 */
add_filter('install_plugins_table_api_args_featured', 'zoom_callback_for_featured_plugins_tab');


/**
 * Beaver Builder Integration
 */

function wpz_bb_upgrade_link() {
    return 'https://www.wpbeaverbuilder.com/wpzoom/?fla=463';
}

add_filter( 'fl_builder_upgrade_url', 'wpz_bb_upgrade_link' );
