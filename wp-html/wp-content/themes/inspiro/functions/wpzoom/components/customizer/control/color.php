<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Color
 *
 * Extended Customize Color Control class.
 *
 *
 * @since 1.7.1.
 */
class WPZOOM_Customizer_Control_Color extends WPZOOM_Customize_Control {
    /**
     * The control contextual dependency.
     *
     * @since 1.7.1.
     *
     * @var string
     */
    public $dependency = false;

    /**
     * The control type.
     *
     * @since 1.8.5
     *
     * @var string
     */
    public $type = 'zoom_color_picker';

    /**
     * WPZOOM_Customizer_Control_Color constructor.
     *
     * @since 1.8.5
     *
     * @param WP_Customize_Manager $manager
     * @param string               $id
     * @param array                $args
     */
    public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
        parent::__construct( $manager, $id, $args );

        // Ensure this instance maintains the proper type value.
        $this->type = 'zoom_color_picker';
    }

    /**
     * Enqueue necessary scripts for this control.
     *
     * @since 1.8.5
     *
     * @return void
     */
    public function enqueue() {
        wp_enqueue_script(
            'alpha-color-picker',
            $this->get_js_uri('libs/alpha-color-picker/alpha-color-picker.js'),
            array( 'jquery', 'wp-color-picker' ),
            WPZOOM::$wpzoomVersion,
            true
        );

        wp_enqueue_style(
            'alpha-color-picker',
            $this->get_css_uri('libs/alpha-color-picker/alpha-color-picker.css'),
            array( 'wp-color-picker' )
        );
    }

    /**
     * Wrapper for getting the path to the customizer assets directory.
     *
     * @since 1.8.5
     *
     * @return string
     */
    public function get_assets_uri( $endpoint = '' )
    {
        return WPZOOM::$wpzoomPath . '/components/customizer/assets/' . $endpoint;
    }

    /**
     * Wrapper for getting the URL for the customizer assets CSS directory.
     *
     * @since 1.8.5
     *
     * @return string
     */
    public function get_css_uri( $endpoint = '' )
    {
        return $this->get_assets_uri('css/' . $endpoint);
    }

    /**
     * Wrapper for getting the URL for the customizer assets JS directory.
     *
     * @since 1.8.5
     *
     * @return string
     */
    public function get_js_uri( $endpoint = '' )
    {
        return $this->get_assets_uri('js/' . $endpoint);
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 1.7.1.
     * @uses WP_Customize_Control::to_json()
     */
    public function to_json() {
        parent::to_json();

        $this->json['defaultValue'] = $this->setting->default;
        $this->json['showOpacity']  = 'true';
        $this->json['palette']      = ! empty($this->choices) ? implode( '|', $this->choices ) : 'true';
        $this->json['value']        = $this->value();
        $this->json['dependency']   = $this->dependency;
    }

    /**
     * Render a JS template for the content of the color picker control.
     *
     * @since 1.8.5
     */
    public function content_template() { ?>

        <# if ( data.label ) { #>
            <span class="customize-control-title">{{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>
        <div class="zoom-color-picker-container">
            <label>
                <input type="text" class="color-picker-hex zoom-alpha-color-picker" value="{{ data.value }}" data-palette="{{ data.palette }}" data-default-color="{{ data.defaultValue }}" data-show-opacity="{{ data.showOpacity }}" data-customize-setting-link="{{ data.settings.default }}" />
            </label>
        </div>

    <?php
    }
    
}