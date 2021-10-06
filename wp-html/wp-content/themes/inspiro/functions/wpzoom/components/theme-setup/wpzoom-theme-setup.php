<?php
/**
 * Theme Setup
 *
 * This class helps user to setup theme step by step.
 *
 */

class WPZOOM_Theme_Setup
{

    public $ajax_action_names = array(
        'regenerate_thumbnails' => 'zoom_regenerate_thumbnails',
        'get_thumbnails' => 'zoom_get_thumbnails',
        'set_front_page_option' => 'zoom_set_front_page_option',
        'set_setup_options' => 'zoom_set_setup_options',
        'set_demo_theme_setup_complete' => 'zoom_set_demo_theme_setup_complete'
    );

    public $default_demo;
    public $imported_demo;

    protected $options;
    protected $settings_fields;

    public function __construct()
    {
        add_action('wp_ajax_' . $this->ajax_action_names['regenerate_thumbnails'], array($this, 'regenerate_thumbnail'));
        add_action('wp_ajax_' . $this->ajax_action_names['get_thumbnails'], array($this, 'get_thumbnails'));
        add_action('wp_ajax_' . $this->ajax_action_names['set_front_page_option'], array($this, 'set_front_page_option'));
        add_action('wp_ajax_' . $this->ajax_action_names['set_setup_options'], array($this, 'set_setup_options'));
        add_action('wp_ajax_' . $this->ajax_action_names['set_demo_theme_setup_complete'], array($this, 'set_demo_theme_setup_complete'));

        add_action('load-toplevel_page_wpzoom_options', array($this, 'wpzoom_page_options_callback'));

        add_action('init', array($this, 'ob_start_action'), -1);

        // Set default & imported demo value
        $demos = get_demos_details();
        $this->default_demo = $demos['default'];
        $this->imported_demo = $demos['selected'];

        if ( class_exists('WPZOOM_Admin_Settings_Fields') ) {
            $this->settings_fields = new WPZOOM_Admin_Settings_Fields();
        }
    }

    public function ob_start_action()
    {
        if (defined('DOING_AJAX') &&
            DOING_AJAX &&
            isset($_REQUEST['action']) &&
            in_array($_REQUEST['action'], $this->ajax_action_names)
        ) {
            ob_start(NULL, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE);
        }
    }

    public function wpzoom_page_options_callback()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function get_assets_uri($endpoint = '')
    {
        return WPZOOM::$wpzoomPath . '/components/theme-setup/assets/' . $endpoint;
    }

    public function get_js_uri($endpoint = '')
    {
        return $this->get_assets_uri('js/' . $endpoint);
    }

    public function get_css_uri($endpoint = '')
    {
        return $this->get_assets_uri('css/' . $endpoint);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('zoom-theme-setup', $this->get_js_uri('general.js'), array('jquery', 'underscore', 'wp-util'), '1.0.0', true);
        wp_enqueue_script('zoom-magnific-popup', $this->get_js_uri('jquery.magnific-popup.min.js'));
        wp_enqueue_script('zoom-retry-ajax', $this->get_js_uri('jquery.ajax.retry.js'));
        wp_enqueue_style('zoom-magnific-popup', $this->get_css_uri('magnific-popup.css'));

        $translation_array = array(
            'nonce_regenerate_thumbnail' => wp_create_nonce('regenerate_thumbnail'),
            'nonce_get_thumbnails' => wp_create_nonce('get_thumbnails'),
            'nonce_set_front_page_option' => wp_create_nonce('set_front_page_option'),
            'nonce_update_nav_menu_location' => wp_create_nonce('update_nav_menu_location'),
            'nonce_widgets_default' => wp_create_nonce('wpzoom-ajax-save'),
            'nonce_set_setup_options' => wp_create_nonce('wpzoom-ajax-set-setup-options'),
            'nonce_set_demo_theme_setup_complete' => wp_create_nonce('wpzoom-ajax-set-demo-theme-setup-complete'),
            'site_url' => site_url(),
            'theme_raw_name' => WPZOOM::$theme_raw_name,
            'demo_imported' => $this->imported_demo,
            'front_page_type' => $this->get_front_page_type(),
            'strings' => array(
                'on_leave_alert' => __( 'Attention, the importing process was not finished yet!', 'wpzoom' ),
                'open_theme_setup' => __( 'Open Theme Setup', 'wpzoom' ),
                'theme_setup_non_complete' => __( 'You have not finished setup your theme.', 'wpzoom' ),
                'theme_setup_complete' => __( 'You have complete setup your theme.', 'wpzoom' ),
                'images_progress' => __('Image {1} of {2}', 'wpzoom'),
                'thumbnail_finished' => __('Thumbnails have been successfully regenerated', 'wpzoom'),
                'widget_finished' => __('Default widgets have been successfully loaded.', 'wpzoom'),
                'starting_message' => __('Please wait, the process will start in a couple of seconds...', 'wpzoom'),
                'demo_imported_success' => __('Done! - Demo content has been succesfully imported.', 'wpzoom'),
                'delete_demo_content' => '
                        <div class="popup-modal-message success-message">
                            <div class="icon-wrap">
                                <span><i class="fa fa-check"></i></span>
                            </div>
                            <p class="description">'. __('You have successfully deleted the demo content!', 'wpzoom') .'</p>
                        </div>',
            )
        );

        wp_localize_script('zoom-theme-setup', 'zoom_theme_setup', $translation_array);

    }

    /**
     *
     * Define theme setup options
     */
    public function get_theme_setup_options($merge = array())
    {
        $theme = wp_get_theme();
        $slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', $theme->template ) );
        $child_theme = get_option( 'zoom_' . $slug . '_child' );
        $child_theme_options = array();
        $child_theme_file_exists = ZOOM_Child_Theme::child_theme_file_exists();

        // Check if child theme .zip file exists and append options for step 6
        if ( $child_theme_file_exists ) {

            $child_theme_options = array(
                'title' => '6',
                'description' => __('Install Child Theme', 'wpzoom'),
                'header' => __( 'Install Child Theme', 'wpzoom' ),
                'content' => sprintf( '<strong>%s</strong> %s', __( 'A Child Theme', 'wpzoom' ), __( 'allows you to apply custom code changes to your theme without actually changing the code or the files in the parent theme. Using a child theme ensures that all your custom changes will not be overwritten even when you update the parent theme. In the <strong>Advanced Settings</strong> you can find additional options or you can Skip this step and install it later.', 'wpzoom' ) ),
                'id' => 'step6',
                'std' => 'undone',
                'controls' => array(
                    array(
                        'action' => 'skip',
                        'class' => 'button-secondary',
                        'float' => 'left',
                        'id' => 'zoom-skip-step6',
                        'text' => __('Skip Step', 'wpzoom'),
                    ),
                    array(
                        'action' => 'settings',
                        'class' => 'button-secondary',
                        'float' => 'left',
                        'id' => 'zoom-advanced-settings',
                        'text' => '<i class="fa fa-cog"></i>' . ' ' . __('Advanced settings', 'wpzoom'),
                    ),
                    array(
                        'action' => 'next',
                        'class' => 'button-primary',
                        'float' => 'right',
                        'id' => 'zoom-install-child-theme',
                        'text' => __('Install Child Theme', 'wpzoom'),
                    )
                ),
                'is_done' => array(
                    'content' => '<div class="popup-modal-message success-message">
                                    <div class="icon-wrap">
                                        <span><i class="fa fa-check"></i></span>
                                    </div>
                                    <p class="description">'. ( $child_theme ? esc_html__( 'Your child theme has been installed.', 'wpzoom' ) : '' ) .'</p>
                                </div>',
                    'controls' => array(
                        array(
                            'action' => 'next',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-install-child-theme-done',
                            'text' => __('Next Step', 'wpzoom'),
                        )
                    )
                )
            );

        }

        $options = array(
            'steps' => array(
                array(
                    'title' => '1', // Step title
                    'description' => __('Demo Import', 'wpzoom'), // Step description
                    'id' => 'step1',
                    'std' => 'undone', // Default value
                    'content' => '<div class="popup-modal-message success-message">
                                        <div class="icon-wrap">
                                            <span><i class="fa fa-check"></i></span>
                                        </div>
                                        <p class="description">'. sprintf(__('%s has been successfully imported!', 'wpzoom'), zoom_get_beauty_demo_title($this->imported_demo)) .'</p>
                                    </div>',
                    'controls' => array(
                        array(
                            'action' => 'close',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-close-popup-modal',
                            'text' => __('Close', 'wpzoom'),
                        ),
                        array(
                            'action' => 'settings',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-advanced-settings',
                            'text' => '<i class="fa fa-cog"></i>' . ' ' . __('Advanced settings', 'wpzoom'),
                        ),
                        array(
                            'action' => 'next',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-show-regenerate-thumbnails',
                            'text' => __('Next Step', 'wpzoom'),
                        )
                    )
                ),
                array(
                    'title' => '2',
                    'description' => __('Regen. Thumbnails', 'wpzoom'),
                    'header' => __('Regenerate Thumbnails', 'wpzoom'),
                    'id' => 'step2',
                    'std' => 'undone',
                    'content' => '<p class="description">'. __('Click on the blue button to start to regenerate the thumbnails for the images that were imported.<br/>By default only <strong>Featured Images</strong> will be regenerated, but you can change that in the <strong>Advanced Settings</strong> below.<br/>Skipping this step will result in some of the images appearing deformed or stretched.', 'wpzoom') .'</p>',
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step2',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'settings',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-advanced-settings',
                            'text' => '<i class="fa fa-cog"></i>' . ' ' . __('Advanced settings', 'wpzoom'),
                        ),
                        array(
                            'action' => 'next',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-regenerate-thumbnails',
                            'text' => __('Regenerate Thumbnails', 'wpzoom'),
                        )
                    ),
                    'is_done' => array(
                        'content' => '<div class="popup-modal-message success-message">
                                        <div class="icon-wrap">
                                            <span><i class="fa fa-check"></i></span>
                                        </div>
                                        <p class="description">'. __('Thumbnails have been successfully regenerated!', 'wpzoom') .'</p>
                                    </div>',
                        'controls' => array(
                            array(
                                'action' => 'next',
                                'class' => 'button-primary',
                                'float' => 'right',
                                'id' => 'zoom-regenerate-thumbnails-done',
                                'text' => __('Next Step', 'wpzoom'),
                            )
                        )
                    )
                ),
                array(
                    'title' => '3',
                    'description' => __('Load Widgets', 'wpzoom'),
                    'header' => __('Load Default Widget Settings', 'wpzoom'),
                    'id' => 'step3',
                    'std' => 'undone',
                    'content' => '<p class="description">'. __('Click on the blue button to load the default widget settings (as in theme demo).<br/><br/><strong>NOTICE:</strong> Some widgets may require additional configuration, so make sure to check the Widgets page after the set up process ends if you encounter any problems.', 'wpzoom') .'</p>',
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step3',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'next',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-load-widgets',
                            'text' => __('Load default widget settings', 'wpzoom'),
                        )
                    ),
                    'is_done' => array(
                        'content' => '<div class="popup-modal-message success-message">
                                        <div class="icon-wrap">
                                            <span><i class="fa fa-check"></i></span>
                                        </div>
                                        <p class="description">'. __('Default Widgets settings have been successfully imported!', 'wpzoom') .'</p>
                                    </div>',
                        'controls' => array(
                            array(
                                'action' => 'next',
                                'class' => 'button-primary',
                                'float' => 'right',
                                'id' => 'zoom-load-widgets-done',
                                'text' => __('Next Step', 'wpzoom'),
                            )
                        )
                    )
                ),
                array(
                    'title' => '4',
                    'description' => __('Theme Menus', 'wpzoom'),
                    'header' => __('Configure Theme Menus', 'wpzoom'),
                    'id' => 'step4',
                    'std' => 'undone',
                    'content' => $this->get_configure_menus_content(),
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step4',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'next',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-configure-menus',
                            'text' => __('Next Step', 'wpzoom'),
                        )
                    )
                ),
                array(
                    'title' => '5',
                    'description' => __('Homepage ', 'wpzoom'),
                    'header' => __('Configure Front Page', 'wpzoom'),
                    'id' => 'step5',
                    'std' => 'undone',
                    'content' => $this->get_front_page_content(),
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step5',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'next',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-complete-setup',
                            'text' => __('Next Step', 'wpzoom'),
                        )
                    )
                ),
                $child_theme_options,
                array(
                    'title' => __('Congratulations!', 'wpzoom'),
                    'id' => 'final',
                    'content' => '<div class="popup-modal-message success-message">
                                        <div class="icon-wrap">
                                            <span><i class="fa fa-check"></i></span>
                                        </div>
                                        <p class="description">'. __('The theme has been successfully configured!', 'wpzoom') .'</p>
                                    </div>',
                    'controls' => array(
                        array(
                            'action' => 'close',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-close-modal',
                            'text' => __('Close', 'wpzoom'),
                        ),
                        array(
                            'action' => 'link',
                            'class' => 'button-primary dashicons-before dashicons-admin-appearance',
                            'href' => admin_url('customize.php?return='. urlencode(admin_url('admin.php?page=wpzoom_options&welcome_tour=1'))),
                            'target' => '_blank',
                            'float' => 'left',
                            'id' => 'zoom-open-customizer',
                            'text' => __('Customize Theme', 'wpzoom'),
                        ),
                        array(
                            'action' => 'link',
                            'class' => '',
                            'float' => 'right',
                            'href' => site_url(),
                            'target' => '_blank',
                            'id' => 'zoom-view-site',
                            'text' => __('View your website', 'wpzoom') . ' &raquo;',
                        ),
                    )
                )
            ),
            'advanced_settings' => array(
                array(
                    "type" => "startsub",
                    "name" => __('Advanced Settings', 'wpzoom'),
                ),
                array(
                    "name" => __( 'Automatically activate Child Theme?', 'wpzoom' ),
                    "desc" => __( 'Activate Child Theme after it was installed.', 'wpzoom' ),
                    "id" => "child_theme_auto_activate",
                    "std" => "off",
                    "type" => "checkbox",
                ),
                array(
                    "name" => __( 'Copy existing Widgets, Menus and Customizer options to Child Theme?', 'wpzoom' ),
                    "desc" => sprintf( '<strong>%s</strong> %s', __( 'Note:', 'wpzoom' ), __( 'This option replaces the Child Theme\'s existing Widgets, Menus and Customizer options with those from the Parent Theme. You should only need to use this option the first time you install a Child Theme.', 'wpzoom' ) ),
                    "id" => "child_theme_keep_parent_settings",
                    "std" => "on",
                    "type" => "checkbox",
                ),
                array(
                    "name" => __("Delete \"Hello World!\" post and \"Sample Page\"", "wpzoom"),
                    "desc" => __("WordPress creates by default a new page \"Sample page\" and a post \"Hello World!\". If you check this option, they will be deleted automatically. <br />Note: If you have edited the sample page and post, they will not be deleted.", "wpzoom"),
                    "id" => "delete_wp_defaults",
                    "std" => "on",
                    "type" => "checkbox",
                ),
                array(
                    "name" => __("Regenerate only featured images", "wpzoom"),
                    "desc" => __("This option will regenerate only featured images on your site. This is a good feature if you have a lot of images uploaded, but want to rebuild only post thumbnails.", "wpzoom"),
                    "id" => "regenerate_only_feat_img",
                    "std" => "on",
                    "type" => "checkbox",
                ),
                array(
                    "type"  => "endsub"
                ),
            )
        );

        // Prepare steps, remove empty elements
        $options['steps'] = array_filter( $options['steps'] );

        return array_merge($options, $merge);
    }

    public function set_setup_options()
    {
        check_ajax_referer('wpzoom-ajax-set-setup-options', 'nonce_set_setup_options');

        $action_name = isset($_POST['action_name']) ? $_POST['action_name'] : 'demo_content_done';

        if ( $action_name === 'demo_content_done' ) {
            // Set imported demo
            set_theme_mod('wpz_demo_imported', $this->imported_demo);
            set_theme_mod('wpz_demo_imported_timestamp', current_time('timestamp'));
        }

        $this->options = $this->get_theme_setup_options();
        $this->options['advanced_settings']['content'] = $this->get_advanced_settings_content();

        // Check if theme setup for selected demo is complete
        $demo_ts_complete = get_option('wpzoom_'. $this->imported_demo .'_theme_setup_complete');

        do_action( 'wpzoom_demo_theme_setup_options' );

        if ( $demo_ts_complete == 'complete' ) {
            wp_send_json_success(array(
                    'setup_is_complete' => true,
                    'content' => '<div class="popup-modal-message success-message">
                                        <div class="icon-wrap">
                                            <span><i class="fa fa-check"></i></span>
                                        </div>
                                        <p class="description">'. __('You have already configured the settings for this demo!<br/>If you want to configure it again, please Erase it first.', 'wpzoom') .'</p>
                                    </div>'
                )
            );
        }

        wp_send_json_success($this->options);
    }

    public function set_demo_theme_setup_complete()
    {
        check_ajax_referer('wpzoom-ajax-set-demo-theme-setup-complete', 'nonce_set_demo_theme_setup_complete');

        update_option('wpzoom_'. $this->imported_demo .'_theme_setup_complete', 'complete');

        $this->advanced_settings_actions($_POST['advanced_settings']);

        do_action('wpzoom_demo_theme_setup_complete');

        wp_send_json_success();
    }


    public function advanced_settings_actions($data)
    {
        $deleteDefaults = isset($data['delete_wp_defaults']) && $data['delete_wp_defaults'] === 'true' ? 1 : 0;

        if ( $deleteDefaults ) {
            // Check if post wasn't edited
            if ( get_the_modified_date('', 1) == get_the_date('', 1) ) {
                wp_delete_post( 1, true ); // 'Hello World!' post
            }

            if ( get_the_modified_date('', 2) == get_the_date('', 2) ) {
                wp_delete_post( 2, true ); // 'Sample page' page
            }
        }
    }

    public function get_advanced_settings_content()
    {
        $content = '<div id="advanced-settings-popup" class="zoomForms" style="display: none">';

        foreach ($this->options['advanced_settings'] as $field) {

            $skipfor = array('preheader', 'startsub', 'endsub');
            $skipforend = array('endsub');

            $defaults_args = array(
                'id'    => '',
                'type'  => '',
                'name'  => '',
                'std'   => '',
                'desc'  => '',
                'value' => '',
                'out'   => ''
            );

            $args = wp_parse_args($field, $defaults_args);
            extract($args);

            if (option::get($id) != "" && !is_array(option::get($id))) {
                $value = $args['value'] = stripslashes(option::get($id));
            } else {
                $value = $args['value'] = $std;
            }

            if (!in_array($type, $skipfor)) {
                $content .= '<div class="wpz_option_container clear">';
            }

            $content .= call_user_func_array(
                array($this->settings_fields, $type),
                array(apply_filters('zoom_field_' . $args['id'], $args))
            );

            if (!in_array($type, $skipforend)) {
                $content .= '<div class="cleaner"></div>';
            }

            if (!in_array($type, $skipfor)) {
                $content .= '</div>'; // .wpz_option_container
            }

        }

        $content .= '</div>'; // .zoomForms

        return $content;
    }



    /**
     *
     * Configure Theme Menus
     */
    public function get_menus_select($id = '')
    {
        // Get existing menu locations assignments
        $theme_locations = get_nav_menu_locations();
        $nav_menus = wp_get_nav_menus(); // Get created nav menus

        $out = '<select id="menu-location-'. esc_attr($id) .'" name="wpz_menu['. esc_attr($id) .']" data-location="'. esc_attr($id) .'">';

        $out .= '<option value="0">'. __('-- Select --', 'wpzoom') .'</option>';

        foreach ($nav_menus as $key => $menu) {
            $out .= '<option value="'. $menu->term_id .'" '. (isset($theme_locations[$id]) && $theme_locations[$id] == $menu->term_id ? 'selected="selected"' : '') .'>'. $menu->name .'</option>';
        }

        $out .= '</select><div class="cleaner"></div>';

        return force_balance_tags($out);
    }

    public function get_configure_menus_content()
    {
        // Get existing menu locations assignments
        $locations = get_registered_nav_menus();

        $out = '<div class="zoomForms">';
        $out .= '<div class="wpz_option_container clearfix">';
        $out .= '<p class="description nofloat text-left">'. __('Your theme includes the following menu locations. Please select an existing menu for each location', 'wpzoom') .'</p>';

        foreach ( $locations as $location => $description ) {
            $out .= '<label for="location-'. $location .'">'. $description .'</label>';
            $out .= $this->get_menus_select($location);
        }

        $out .= '</div></div>';
        $out .= sprintf(__('<p><a href="%s" target="_blank">Edit Menus structure</a></p><p>You can find more instructions in this <a href="https://www.wpzoom.com/docs/set-up-navigation-menus/" target="_blank">video tutorial</a> <em>(opens in new tab)</em>.</p>', 'wpzoom'), admin_url('nav-menus.php'));

        return $out;
    }

    public function get_menu_by_location( $theme_location )
    {
        $theme_locations = get_nav_menu_locations();
        $menu_obj = get_term( $theme_locations[ $theme_location ], 'nav_menu' );

        if ( $menu_obj )
            return wp_get_nav_menu_items( $menu_obj->term_id, $args );
        else
            return $menu_obj;
    }


    /**
     *
     * Configure front page
     */
    public function get_front_page_type()
    {
        $type = 'latest_posts';

        $themes = array(
            'angle',
            'capital',
            'diamond',
            'wpzoom-diamond',
            'inspiro',
            'medicus',
            'venture'
        );

        if (in_array(WPZOOM::$theme_raw_name, $themes)) {
            $type = 'static_page';
        }

        $theme_front_page_type = get_theme_support('zoom-front-page-type');

        if (!empty($theme_front_page_type[0]) and
            array_key_exists('type', $theme_front_page_type[0]) and
            in_array($theme_front_page_type[0]['type'], array('static_page', 'latest_posts'))
        ) {
            $type = $theme_front_page_type[0]['type'];
        }

        return $type;
    }

    public function set_front_page_option()
    {
        //clear buffer from error notices , it not clear fatal errors
        ob_end_clean();
        check_ajax_referer('set_front_page_option', 'nonce_set_front_page_option');

        $data = isset($_POST['data']) ? $_POST['data'] : array();

        $show_on_front = isset($data['show_on_front']) && ! empty($data['show_on_front']) ? $data['show_on_front'] : ($this->get_front_page_type() == 'static_page' ? 'page' : 'posts');
        $page_on_front = isset($data['page_on_front']) && ! empty($data['page_on_front']) ? $data['page_on_front'] : get_option('page_on_front');
        $page_for_posts = isset($data['page_for_posts']) && ! empty($data['page_for_posts']) ? $data['page_for_posts'] : get_option('page_for_posts');

        update_option('show_on_front', $show_on_front);
        update_option('page_on_front', $page_on_front);
        update_option('page_for_posts', $page_for_posts);

        wp_send_json_success();
    }

    public function get_front_page_content()
    {
        $front_page_type = $this->get_front_page_type();
        $front_page_option = get_option('show_on_front');
        $frontpage_id = get_option('page_on_front');
        $blog_id = get_option('page_for_posts');
        $pages = get_pages();

        $out = '<div class="zoomForms">';
        $out .= '<div class="wpz_option_container clearfix">';
        $out .= '<label for="front-static-pages" class="nofloat">'. __('Configure what Front Page displays', 'wpzoom') .'</label>';

        if ( $front_page_type == 'latest_posts' && $front_page_option == 'posts' ) {
            $out .= '<p class="description nofloat text-left">'. __('This theme works best when displaying your <strong>Latest Posts</strong> on the front page.', 'wpzoom') .'</p>';
        }

        if ( $front_page_type == 'latest_posts' && $front_page_option == 'page' ) {
            $out .= '<p class="description nofloat text-left" style="color: #cc0b0b">'. __('Your theme displays a <strong>Static Page</strong> on the front page, but this theme works best when displaying your <strong>Latest posts</strong> on the front page!', 'wpzoom') .'</p>';
        }

        if ( $front_page_type == 'static_page' && $front_page_option == 'posts' ) {
            $out .= '<p class="description nofloat text-left" style="color: #cc0b0b">'. __('Your website currently displays <strong>Latest blog posts</strong> on the front page, but this theme works best when you set a <strong>Static Page</strong> with a special page template as your front page.<br/> We recommend you to select <strong>A static page</strong> option below and then select the <strong>"Homepage"</strong> and <strong>"Blog"</strong> pages from the lists below.', 'wpzoom') .'</p>';
        }

        if ( $front_page_type == 'static_page' && $front_page_option == 'page' ) {
            $out .= '<p class="description nofloat text-left">'. __('This theme works best when displaying a static page as your front page. We recommend you to select <strong>"Homepage"</strong> and <strong>"Blog"</strong> pages from the lists below.', 'wpzoom') .'</p>';
        }

        $out .= '<div id="front-static-pages">';
        $out .= '<fieldset>';
        $out .= '
            <p><label class="nofloat">
                <input name="show_on_front" type="radio" value="page" class="tog" '. ($front_page_option == 'page' ? 'checked="checked"' : '') .'> '. sprintf(__('A <a href="%s">static page</a> (select below)', 'wpzoom'), get_admin_url('page')) .'</label>
            </p>
            <p><label class="nofloat">
                <input name="show_on_front" type="radio" value="posts" class="tog" '. ($front_page_option == 'posts' ? 'checked="checked"' : '') .'> '. __('Your latest posts', 'wpzoom') .'</label>
            </p>';

        $out .= '<ul><li>';
        $out .= '<label class="nofloat" for="page_on_front">'. __('Homepage:', 'wpzoom') .' <select id="page_on_front" name="page_on_front" '. ($front_page_option == 'posts' ? 'disabled=""' : '') .'>';
        $out .= '<option value="0">'. __('-- Select --', 'wpzoom') .'</option>';

        foreach ($pages as $key => $page) {
            $out .= '<option value="'. $page->ID .'" '. ($page->ID == $frontpage_id ? 'selected="selected"' : '') .'>'. $page->post_title .'</option>';
        }

        $out .= '</select></label></li><li>';
        $out .= '<label class="nofloat" for="page_for_posts">'. __('Posts page:', 'wpzoom') .' <select name="page_for_posts" id="page_for_posts" '. ($front_page_option == 'posts' ? 'disabled=""' : '') .'>';
        $out .= '<option value="0">'. __('-- Select --', 'wpzoom') .'</option>';

        foreach ($pages as $key => $page) {
            $out .= '<option value="'. $page->ID .'" '. ($page->ID == $blog_id ? 'selected="selected"' : '') .'>'. $page->post_title .'</option>';
        }

        $out .= '</select></label></li></ul>';
        $out .= '</fieldset>';
        $out .= '</div>';

        $out .= '<p class="description nofloat text-left">'. sprintf(__('You can also configure your front page and posts page later from the %sReading Settings%s', 'wpzoom'), '<a href="'. admin_url('options-reading.php') .'" target="_blank" title="'. __('Open Reading settings page', 'wpzoom') .'">', '</a>') .'</p>';

        $out .= '</div></div>';

        return force_balance_tags($out);
    }

    /**
     *
     * Regenerate Thumbnails
     */
    public function get_attachment_images($data = array())
    {
        global $wpdb;

        $onlyFeatured = isset($data['regenerate_only_feat_img']) && $data['regenerate_only_feat_img'] === 'true' ? 1 : 0;

        if ( $onlyFeatured ) {

            // Get all featured images
            $sql = "SELECT p1.ID AS post_ID, wm2.post_id AS thumb_ID
                    FROM {$wpdb->posts} p1
                    LEFT JOIN {$wpdb->postmeta} wm1 ON (
                        wm1.post_id = p1.id
                        AND wm1.meta_value IS NOT NULL
                        AND wm1.meta_key = '_thumbnail_id'
                    )
                    LEFT JOIN {$wpdb->postmeta} wm2 ON (
                        wm1.meta_value = wm2.post_id
                        AND wm2.meta_key = '_wp_attached_file'
                        AND wm2.meta_value IS NOT NULL
                    )
                    WHERE p1.post_status = 'publish'
                        AND wm2.meta_value IS NOT NULL
                    GROUP BY thumb_ID
                    ORDER BY p1.post_date DESC";

        } else {

            $sql = "SELECT ID as thumb_ID FROM {$wpdb->posts}
    				WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'
    				ORDER BY ID DESC";
        }

        $result = $wpdb->get_results($sql);
        $result = wp_list_pluck($result, 'thumb_ID');

        return $result;
    }

    public function get_thumbnails()
    {
        //clear buffer from error notices , it not clear fatal errors
        ob_end_clean();
        check_ajax_referer('get_thumbnails', 'nonce_get_thumbnails');

        $data = array(
            'thumbs' => $this->get_attachment_images($_POST['advanced_settings'])
        );

        wp_send_json_success($data);
    }

    public function regenerate_thumbnail()
    {

        check_ajax_referer('regenerate_thumbnail', 'nonce_regenerate_thumbnail');

        $thumb_id = (int)$_POST['thumb_id'];
        $image = get_post($thumb_id);

        //clear buffer from error notices , it not clear fatal errors
        ob_end_clean();

        if (!$image || 'attachment' != $image->post_type || 'image/' != substr($image->post_mime_type, 0, 6)) {
            wp_send_json_error(
                array(
                    'status' => 'error',
                    'thumb_id' => $thumb_id,
                    'image' => $image,
                    'heading' => sprintf(
                        __('&quot;%1$s&quot; (ID %2$s) is Failed.', 'wpzoom'), esc_html(get_the_title($image->ID)), $image->ID
                    ),
                    'message' => sprintf( __('Failed resize: %d is an invalid image ID.', 'wpzoom'), $thumb_id ),
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step2',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'skip-attachment',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-attachment',
                            'text' => sprintf( __('Skip image (ID %1$s)', 'wpzoom'), $image->ID ),
                        ),
                    ),
                )
            );
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error(
                array(
                    'halt' => true,
                    'thumb_id' => $thumb_id,
                    'image' => $image,
                    'message' => __("Your user account doesn't have permission to resize images", 'wpzoom')
                )
            );
        }

        $fullsizepath = get_attached_file($image->ID);

        if (false === $fullsizepath || !file_exists($fullsizepath)) {
            wp_send_json_error(
                array(
                    'status' => 'error',
                    'thumb_id' => $thumb_id,
                    'image' => $image,
                    'heading' => sprintf(
                        __('&quot;%1$s&quot; (ID %2$s) is Failed.', 'wpzoom'), esc_html(get_the_title($image->ID)), $image->ID
                    ),
                    'message' => sprintf(
                        __('The originally uploaded image file cannot be found at %s', 'wpzoom'), '<code>' . esc_html($fullsizepath) . '</code>'
                    ),
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step2',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'skip-attachment',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-attachment',
                            'text' => sprintf( __('Skip image (ID %1$s)', 'wpzoom'), $image->ID ),
                        ),
                    ),
                )
            );
        }

        @set_time_limit(900); // 5 minutes per image should be PLENTY

        $metadata = wp_generate_attachment_metadata($image->ID, $fullsizepath);

        if (is_wp_error($metadata)) {
            wp_send_json_error(
                array(
                    'status' => 'warning',
                    'thumb_id' => $thumb_id,
                    'image' => $image,
                    'heading' => sprintf(
                        __('&quot;%1$s&quot; (ID %2$s) is Failed.', 'wpzoom'), esc_html(get_the_title($image->ID)), $image->ID
                    ),
                    'message' => $metadata->get_error_message(),
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step2',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'skip-attachment',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-attachment',
                            'text' => sprintf( __('Skip image (ID %1$s)', 'wpzoom'), $image->ID ),
                        ),
                        array(
                            'action' => 'retry-attachment',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-retry-attachment',
                            'text' => __('Retry', 'wpzoom'),
                        )
                    ),
                )
            );
        }

        if (empty($metadata)) {
            wp_send_json_error(
                array(
                    'status' => 'warning',
                    'thumb_id' => $thumb_id,
                    'image' => $image,
                    'heading' => sprintf(
                        __('&quot;%1$s&quot; (ID %2$s) is Failed.', 'wpzoom'), esc_html(get_the_title($image->ID)), $image->ID
                    ),
                    'message' => __('Unknown failure reason.', 'wpzoom'),
                    'controls' => array(
                        array(
                            'action' => 'skip',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-step2',
                            'text' => __('Skip Step', 'wpzoom'),
                        ),
                        array(
                            'action' => 'skip-attachment',
                            'class' => 'button-secondary',
                            'float' => 'left',
                            'id' => 'zoom-skip-attachment',
                            'text' => sprintf( __('Skip image (ID %1$s)', 'wpzoom'), $image->ID ),
                        ),
                        array(
                            'action' => 'retry-attachment',
                            'class' => 'button-primary',
                            'float' => 'right',
                            'id' => 'zoom-retry-attachment',
                            'text' => __('Retry', 'wpzoom'),
                        )
                    ),
                )
            );
        }

        wp_update_attachment_metadata($image->ID, $metadata);

        wp_send_json_success(array(
                'thumb_id' => $thumb_id,
                'image' => $image,
                'message' => sprintf(
                    __('&quot;%1$s&quot; (ID %2$s) was successfully resized in %3$s seconds.', 'wpzoom'),
                    esc_html(get_the_title($image->ID)), $image->ID, timer_stop()
                )
            )
        );
    }
}