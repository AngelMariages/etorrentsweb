<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Controls
 *
 * Set up the Customizer interface with the theme's settings.
 *
 * @since 1.7.0.
 */

require_once get_template_directory() . '/functions/wpzoom/components/customizer/helpers/helpers.php';
require_once get_template_directory() . '/functions/wpzoom/components/customizer/wpzoom-customizer-css-parser.php';


class WPZOOM_Customizer_Controls {

    /**
     * Array to hold the Section definitions.
     *
     * @since 1.7.0.
     *
     * @var array
     */
    private $sections = array();

    /**
     * Array to hold the Panel definitions.
     *
     * @since 1.7.0.
     *
     * @var array
     */
    private $panels = array();

    /**
     * Prefix string for panels, sections, and controls.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    private $prefix;

    /**
     * Indicator of whether the hook routine has been run.
     *
     * @since 1.7.0.
     *
     * @var bool
     */
    private static $hooked = false;

    /**
     * Store all font families with allowed preview
     *
     * @since 1.8.7.
     *
     * @var array
     */
    public $fonts_families_preview = array();

    private $typography_settings = array();


    public function __construct( $data )
    {
        if ( empty($data) ) {
            return;
        }

        $this->prefix = WPZOOM::$theme_raw_name . '_';
        $this->sections = (array)$data;

        $this->set_typography_settings( $data );

        $this->hook();
    }

    /**
     * Hook into WordPress.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    public function hook()
    {
        if ( $this->is_hooked() ) {
            return;
        }

        add_action( 'init', 'zoom_customizer_get_css', 1 );

        // Set all font families with preview
        add_action( 'init', array( $this, 'set_font_families_with_preview' ), 10 );

        // Display custimization
        add_action( 'wp_head', array($this, 'display_customization') );

        if ( is_customize_preview() ) {
            // Register control types
            add_action( 'customize_register', array( $this, 'setup_control_types' ), 1 );

            // Load section definitions
            add_action( 'customize_register', array( $this, 'load_definitions' ), 5 );

            // Load section definitions
            add_action( 'customize_register', array( $this, 'load_controls' ) );

            // Add panels
            add_action( 'customize_register', array( $this, 'add_panels' ) );

            // Add sections
            add_action( 'customize_register', array( $this, 'add_sections' ) );

            // Print Fonts Families preview
            add_action( 'admin_print_styles', array( $this, 'fonts_families_preview' ) );
        }

        // Control styles
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_styles' ) );
        // Control scripts
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ) );

        // Preview styles
        add_action( 'customize_preview_init', array( $this, 'enqueue_preview_styles' ), 9999 );
        // Preview scripts
        add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ), 9999 );

        // Hooking has occurred.
        self::$hooked = true;
    }

    /**
     * Check if the hook routine has been run.
     *
     * @since 1.7.0.
     *
     * @return bool
     */
    public function is_hooked() {
        return self::$hooked;
    }

    /**
     * Enqueue styles for Customizer controls.
     *
     * @since 1.8.5
     *
     * @hooked action 'customize_controls_enqueue_scripts'
     *
     * @return void
     */
    public function enqueue_control_styles() {
        // jQuery UI styles are for our custom Range and Buttonset controls.
        wp_enqueue_style( 'zoom-jquery-ui-custom' );
        wp_enqueue_style( 'zoom-customizer-controls' );
    }

    /**
     * Enqueue scripts for Customizer controls.
     *
     * @since 1.7.0.
     *
     * @hooked action 'customize_controls_enqueue_scripts'
     *
     * @return void
     */
    public function enqueue_control_scripts() {
        wp_enqueue_script( 'zoom-customizer-controls' );
        wp_enqueue_script( 'zoom-customizer-helper' );

        // Collect localization data
        $data = array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'fontChoises'   => zoom_customizer_all_font_choices(),
            'fontParams'    => zoom_customizer_get_all_fonts(),
            'fontSettings'  => $this->get_typography_settings(),
            'l10n'          => array(
                'chosen_loading'          => esc_html__( 'Loading&hellip;', 'wpzoom' ),
                'chosen_no_results_fonts' => esc_html__( 'No matching fonts', 'wpzoom' ),
                'reset_button'            => esc_html__( 'Reset', 'wpzoom' ),
                'reset_tooltip'           => esc_html__( 'Reset the below values to their defaults', 'wpzoom' ),
                'dialog_confirm'          => esc_html__( 'Are you sure?', 'wpzoom' ),
                'modal_font_preview_text' => esc_html__( 'This is how selected Font Family will look like on your website.', 'wpzoom' )
            ),
            'font_families_with_preview' => $this->fonts_families_preview
        );

        // Extra data for reorderable post meta feature, if enabled
        if ( current_theme_supports( 'wpz-reorderable-post-meta' ) ) {
            $default = '[{"field":"category","enabled":true},{"field":"title","enabled":true},{"field":"date","enabled":true},{"field":"comments_count","enabled":true},{"field":"author","enabled":false},{"field":"content","enabled":true},{"field":"read_more_button","enabled":true}]';
            $default_single = '[{"field":"category","enabled":true},{"field":"title","enabled":true},{"field":"date","enabled":true},{"field":"comments_count","enabled":true},{"field":"author","enabled":false},{"field":"content","enabled":true},{"field":"tags","enabled":true},{"field":"sharing_buttons","enabled":true},{"field":"author_info_box","enabled":true},{"field":"previous_next_posts_navigation","enabled":true},{"field":"comments","enabled":true}]';
            $data['rpm'] = array(
                'homepage_post_meta' => json_decode( get_theme_mod( 'homepage-post-meta', $default ), true ),
                'archive_post_meta' => json_decode( get_theme_mod( 'archive-post-meta', $default ), true ),
                'single_post_meta' => json_decode( get_theme_mod( 'single-post-meta', $default_single ), true )
            );
        }

        // Extra data for responsive slider height feature, if enabled
        if ( current_theme_supports( 'wpz-responsive-slider-height' ) ) {
            $data[ 'rsh' ] = true;
        }

        // Localize the script
        wp_localize_script(
            'zoom-customizer-controls',
            'WPZOOM_Controls',
            $data
        );
    }

    /**
     * Enqueue styles for Customizer preview.
     *
     * @since 1.8.5
     *
     * @hooked action 'customize_preview_init'
     *
     * @return void
     */
    public function enqueue_preview_styles() {
        wp_enqueue_style( 'zoom-customizer-preview' );
    }

    /**
     * Enqueue scripts for Customizer preview.
     *
     * @since 1.7.0.
     *
     * @hooked action 'customize_preview_init'
     *
     * @return void
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script( 'zoom-customizer-vein-js' );
        wp_enqueue_script( 'zoom-customizer-typekit-font-loader' );
        wp_enqueue_script( 'zoom-customizer-preview' );

        // Collect localization data
        $data = array(
            'cssRules'      => $this->get_css_rules(),
            'domRules'      => $this->get_dom_rules(),
            'themeName'     => WPZOOM::$theme_raw_name,
            'fontChoises'   => zoom_customizer_all_font_choices(),
            'fontParams'    => zoom_customizer_get_all_fonts(),
        );

        // Localize the script
        wp_localize_script(
            'zoom-customizer-preview',
            'WPZOOM_Preview',
            $data
        );
    }

    /**
     * Get prefix
     *
     * @return string Theme prefix
     */
    private function get_prefix()
    {
        return $this->prefix;
    }

    /**
     * Preliminary setup for custom control classes.
     *
     * @since 1.7.0.
     *
     * @hooked action customize_register
     *
     * @param WP_Customize_Manager $wp_customize
     *
     * @return void
     */
    public function setup_control_types( WP_Customize_Manager $wp_customize )
    {
        // The control types
        $types = array(
            'WPZOOM_Customizer_Control_Select',
            'WPZOOM_Customizer_Control_Color',
            'WPZOOM_Customizer_Control_Background_Gradient',
            'WPZOOM_Customizer_Control_Sortable',
            'WPZOOM_Customizer_Control_Range',
            'WPZOOM_Customizer_Control_Text',
            'WPZOOM_Customizer_Control_Checkbox',
            'WPZOOM_Customizer_Control_Checkbox_Multiple',
            'WPZOOM_Customizer_Control_Radio',
            'WPZOOM_Customizer_Control_HTML',
            'WPZOOM_Customizer_Control_Responsive',
        );

        // Register each type
        foreach ($types as $key => $type) {
            $wp_customize->register_control_type($type);
        }
    }

    /**
     * Check for valid control type
     *
     * @since 1.7.0
     *
     * @param string $control_type
     *
     * @return bool
     */
    private function is_valid_control_type( $control_type )
    {
        return class_exists( $control_type ) && in_array( $control_type, $this->get_custom_controls() );
    }

    /**
     * Get the array of custom controls.
     *
     * @since 1.7.0.
     *
     * @return mixed|void
     */
    private function get_custom_controls()
    {
        /**
         * Included dependency to Color, Image and Upload controls.
         * Created new customizer control Background Gradient
         *
         * @since 1.7.1.
         *
         */
        return apply_filters('wpzoom_customizer_get_custom_controls', array(
            'WP_Customize_Control',
            'WP_Customize_Color_Control',
            'WP_Customize_Upload_Control',
            'WP_Customize_Image_Control',
            'WP_Customize_Background_Image_Control',
            'WP_Customize_Header_Image_Control',
            'WPZOOM_Customize_Control',
            'WPZOOM_Customizer_Control_Select',
            'WPZOOM_Customizer_Control_Color',
            'WPZOOM_Customizer_Control_Background_Gradient',
            'WPZOOM_Customizer_Control_Image',
            'WPZOOM_Customizer_Control_Upload',
            'WPZOOM_Customizer_Control_Sortable',
            'WPZOOM_Customizer_Control_Range',
            'WPZOOM_Customizer_Control_Radio',
            'WPZOOM_Customizer_Control_Checkbox',
            'WPZOOM_Customizer_Control_Text',
            'WPZOOM_Customizer_Control_Checkbox_Multiple',
            'WPZOOM_Customizer_Control_HTML',
            'WPZOOM_Customizer_Control_Responsive',
        ));
    }

    /**
     * Load panels definition.
     *
     * @since 1.7.0.
     *
     * @hooked action customize_register
     *
     * @param WP_Customize_Manager $wp_customize
     *
     * @return void
     */
    public function load_definitions( WP_Customize_Manager $wp_customize )
    {
        /**
         * Load panels Layouts, Header.
         *
         * @since 1.7.1.
         *
         */
        $this->panels = array(
            'general' => array(
                'title'     => __('General', 'wpzoom'),
                'priority'  => 10
            ),
            'style-kits' => array(
                'title'     => __('Style Kits', 'wpzoom'),
                'priority'  => 20
            ),
            'layout' => array(
                'title'     => __('Layouts', 'wpzoom'),
                'priority'  => 25
            ),
            'post-options' => array(
                'title'     => __('Post Options', 'wpzoom'),
                'priority'  => 30
            ),
            'typography' => array(
                'title'     => __('Typography', 'wpzoom'),
                'priority'  => 35
            ),
            'color-scheme' => array(
                'title'     => __('Colors', 'wpzoom'),
                'priority'  => 40
            ),
            'header' => array(
                'title'     => __('Header', 'wpzoom'),
                'priority'  => 50
            ),
        );
    }

    /**
     * Load data files for controls.
     *
     * @since 1.7.0.
     *
     * @hooked action customize_register
     *
     * @param WP_Customize_Manager $wp_customize
     *
     * @return void
     */
    public function load_controls( WP_Customize_Manager $wp_customize ) {
        $file_bases = array(
            'wpzoom-customize-control',
            'text',
            'checkbox',
            'checkbox-multiple',
            'radio',
            'color',
            'background-gradient',
            'image',
            'upload',
            'range',
            'select',
            'sortable',
            'html',
            'responsive'
        );

        foreach ( $file_bases as $name ) {
            $file = dirname( __FILE__ ) . '/control/' . $name . '.php';
            if ( is_readable( $file ) ) {
                include_once $file;
            }
        }
    }

    /**
     * Get the array of panel definitions.
     *
     * @since 1.7.0.
     *
     * @return array
     */
    public function get_panels()
    {
        return apply_filters( 'wpzoom_customizer_get_panels', $this->panels );
    }


    /**
     * Register Customizer panels from the panel definitions array.
     *
     * @since 1.7.0.
     *
     * @hooked action customize_register
     *
     * @param WP_Customize_Manager $wp_customize
     *
     * @return void
     */
    public function add_panels( WP_Customize_Manager $wp_customize )
    {
        $wp_customize->remove_section('colors');

        // Add panels.
        foreach ( $this->get_panels() as $panel => $data ) {
            // Add panel.
            $wp_customize->add_panel( $panel, $data );
        }
    }

    /**
     * Get the array of section/control definitions.
     *
     * @since 1.7.0.
     *
     * @return mixed|void
     */
    public function get_sections()
    {
        return apply_filters( 'wpzoom_customizer_get_sections', $this->sections );
    }

    /**
     * Register Customizer sections and controls from the section definitions array.
     *
     * @since 1.7.0.
     *
     * @hooked action customize_register
     *
     * @param WP_Customize_Manager $wp_customize
     *
     * @return void
     */
    public function add_sections( WP_Customize_Manager $wp_customize )
    {
        // Section definitions.
        foreach ( $this->get_sections() as $section => $data ) {
            // Get the ID of the current section's panel
            $panel = ( isset( $data['panel'] ) ) ? $data['panel'] : 'none';

            // Store the options definitions for later
            if ( isset( $data['options'] ) ) {
                $options = $data['options'];
                unset( $data['options'] );
            }

            // Add the section.
            $wp_customize->add_section( $section, $data );

            // Add options to the section
            if ( isset( $options ) ) {
                $this->add_section_controls( $wp_customize, $section, $options );
                unset( $options );
            }
        }
    }

    /**
     * Register settings, controls, and partials for a section from the controls array in a section definition.
     *
     * @since 1.7.0.
     *
     * @param WP_Customize_Manager $wp_customize
     * @param string               $section
     * @param array                $args
     * @param int                  $initial_priority
     *
     * @return int
     */
    private function add_section_controls( WP_Customize_Manager $wp_customize, $section, array $args )
    {
        foreach ( $args as $setting_id => $definition ) {
            // Add setting
            if ( isset( $definition['setting'] ) && ( is_array( $definition['setting'] ) || true === $definition['setting'] ) ) {
                $defaults = array(
                    'type'                  => 'theme_mod',
                    'capability'            => 'edit_theme_options',
                    'theme_supports'        => '',
                    'default'               => '',
                    'transport'             => 'postMessage',
                    'sanitize_callback'     => '',
                    'sanitize_js_callback'  => '',
                );
                $setting = wp_parse_args( $definition['setting'], $defaults );

                // Add the setting arguments inline so Theme Check can verify the presence of sanitize_callback
                $wp_customize->add_setting( $setting_id, array(
                    'type'                 => $setting['type'],
                    'capability'           => $setting['capability'],
                    'theme_supports'       => $setting['theme_supports'],
                    'default'              => $setting['default'],
                    'transport'            => $setting['transport'],
                    'sanitize_callback'    => $setting['sanitize_callback'],
                    'sanitize_js_callback' => $setting['sanitize_js_callback'],
                ) );
            }

            // Add control
            if ( isset( $definition['control'] ) ) {
                $control_id = $setting_id;

                $defaults = array(
                    'settings' => $setting_id,
                    'section'  => $section,
                );

                // If this control is not linked to a specific setting, remove settings from defaults.
                if ( ! isset( $definition['setting'] ) || false === $definition['setting'] ) {
                    unset( $defaults['settings'] );
                }

                $control = wp_parse_args( $definition['control'], $defaults );

                // Check for a specialized control class
                if ( isset( $control['control_type'] ) && $this->is_valid_control_type( $control['control_type'] ) ) {
                    $class = $control['control_type'];

                    // Attempt to autoload the class
                    $reflection = new ReflectionClass( $class );

                    // If the class successfully loaded, create an instance in a PHP 5.2 compatible way.
                    if ( class_exists( $class ) ) {
                        unset( $control['control_type'] );

                        // Dynamically generate a new class instance
                        $class_instance = $reflection->newInstanceArgs( array( $wp_customize, $control_id, $control ) );

                        $wp_customize->add_control( $class_instance );
                    }
                } else {
                    $wp_customize->add_control( $control_id, $control );
                }
            }

            // Add partial, if selective refresh is supported
            if ( isset( $definition['partial'] ) && isset( $wp_customize->selective_refresh ) ) {
                $partial_id = 'partial_' . $setting_id;

                $defaults = array(
                    'settings' => array( $setting_id ),
                );

                $partial = wp_parse_args( $definition['partial'], $defaults );

                do_action( 'wpzoom_remove_partial', $wp_customize, $setting_id );

                $wp_customize->selective_refresh->add_partial( $partial_id, $partial );
            }
        }
    }

    /**
     * Get rules for DOM elements.
     *
     * @since 1.7.0.
     *
     * @return array
     */
    public function get_dom_rules()
    {
        static $collector = array();

        if (empty($collector)) {
            foreach ($this->sections as $section) {
                foreach ($section['options'] as $option_id => $option_data) {
                    if (!empty($option_data['dom'])) {
                        $collector[$option_id]['dom'] = $option_data['dom'];
                        $collector[$option_id]['default'] = $option_data['setting']['default'];
                    }
                }
            }
        }

        return $collector;
    }

    /**
     * Get CSS rules for elements.
     *
     * @since 1.7.0.
     *
     * @return array
     */
    public function get_css_rules()
    {
        static $collector = array();

        if (empty($collector)) {
            foreach ($this->sections as $section) {
                foreach ($section['options'] as $option_id => $option_data) {
                    if ( ! empty( $option_data['style'] ) ) {
                        $collector[$option_id]['style'] = $option_data['style'];
                    }

                    if ( ! isset( $option_data['dom'] ) && isset( $option_data['setting']['default'] ) ) {
                        $collector[$option_id]['default'] = $option_data['setting']['default'];
                    }
                }
            }
        }

        return $collector;
    }

    /**
     * Append CSS rules in header.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    public function display_customization()
    {
        do_action('zoom_customizer_display_customization_css', $this->get_css_rules());

        $css = zoom_customizer_get_css()->build();

        if (!empty($css)) {
            echo "\n<!-- Begin Theme Custom CSS -->\n<style type=\"text/css\" id=\"" . WPZOOM::$theme_raw_name . "-custom-css\">\n";
            echo $css;
            echo "\n</style>\n<!-- End Theme Custom CSS -->\n";
        }
    }

    /**
     * Description
     *
     * @since 1.8.7
     * @return type
     */
    public function set_font_families_with_preview() {
        $fonts_families = zoom_customizer_get_all_fonts();
        foreach ( $fonts_families as $family_group => $font_options ) {
            $fonts = isset( $font_options['fonts'] ) ? $font_options['fonts'] : array();
            $allow_preview = isset( $font_options['preview'] ) && $font_options['preview'] === true;

            if ( $allow_preview ) {
                foreach ( $fonts as $font_name => $font_options ) {
                    $this->fonts_families_preview[ $font_name ] = $font_options;
                    $this->fonts_families_preview[ $font_name ]['family_group'] = $family_group;
                }
            }
        }
    }

    /**
     * Description
     *
     * @since 1.8.7
     * @return type
     */
    public function get_font_families_with_preview() {
        return $this->fonts_families_preview;
    }

    /**
     * Generates CSS to preview Typography Fonts families
     *
     * @since 1.8.7
     * @return void
     */
    public function fonts_families_preview() {
        $css_output = '';
        $google_fonts = array();

        $font_families_with_preview = $this->get_font_families_with_preview();

        foreach ( $font_families_with_preview as $font_name => $font_options ) {
            $family_group = $font_options['family_group'];

            if ( 'standard-fonts' === $family_group ) {
                $css_output .= '.chosen-container .chosen-results [title="'. esc_attr( $font_name ) .'"] { font-family: '. $font_options['stack'] .'; } .zoom-font-family-preview-modal[data-font-family-name="'. esc_attr( $font_name ) .'"] { font-family: '. $font_options['stack'] .'; }';
            }
            if ( 'popular-google-fonts' === $family_group ) {
                $css_output .= '.chosen-container .chosen-results [title="'. esc_attr( $font_name ) .'"] { font-family: "'. $font_name .'"; } .zoom-font-family-preview-modal[data-font-family-name="'. esc_attr( $font_name ) .'"] { font-family: "'. $font_name .'"; }';
                $google_fonts[] = str_replace( ' ', '+', $font_name );
            }
        }

        if ( ! empty( $google_fonts ) ) {
            $import = '@import url("http'. (is_ssl() ? 's' : '') .'://fonts.googleapis.com/css?family=' . implode( '|' , $google_fonts ) . "\");\n";
            $css_output = $import . $css_output;
        }

        echo '<style type="text/css">';
            echo $css_output;
        echo '</style>';
    }

    public function get_typography_settings()
    {
        return $this->typography_settings;
    }

    public function set_typography_settings( $data )
    {
        foreach ( $data as $section_id => &$section_data ) {

            if ( isset( $section_data['options'] ) && !empty( $section_data['options'] ) ) {
                $this->typography_filter_options( $section_data['options'] );
            }

        }
    }

    public function typography_filter_options( &$options )
    {
        $rules = array('font-family', 'font-style', 'font-weight', 'font-subset');

        foreach ($options as $key => $option) {
            if ( isset($option['style']['rule']) && in_array( $option['style']['rule'], $rules ) ) {

                $value = get_theme_mod($key);

                if ( ! empty($value) ) {
                    $this->typography_settings[$key]['value'] = $value;
                }

                $this->typography_settings[$key]['rule'] = $option['style']['rule'];
                $this->typography_settings[$key]['setting_id'] = $key;
                $this->typography_settings[$key]['default'] = $option['setting']['default'];
            }
        }
    }
}

