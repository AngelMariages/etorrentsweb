<?php
/**
 * Customize API: WPZOOM_Customizer_Control_Background_Gradient class
 *
 * @package WPZOOM
 */

/**
 * Customize Background Gradient Color Control class.
 *
 * @since 1.7.1
 *
 * @see WP_Customize_Control
 */
class WPZOOM_Customizer_Control_Background_Gradient extends WP_Customize_Control {
    /**
     * Type.
     *
     * @var string
     */
    public $type = 'zoom_background_gradient';

    /**
     * Translation labels.
     *
     * @var string
     */
    public $translation = array();

    /**
     * Statuses.
     *
     * @var array
     */
    public $statuses;

    /**
     * The control contextual dependency.
     *
     * @since 1.7.1.
     *
     * @var string
     */
    public $dependency = false;

    /**
     * Directions.
     *
     * @since 1.7.1.
     *
     * @var string
     */
    public $directions = array();

    /**
     * Constructor.
     *
     * @since 1.7.1.
     * @uses WP_Customize_Control::__construct()
     *
     * @param WP_Customize_Manager $manager
     * @param string               $id
     * @param array                $args
     */
    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );

        $this->statuses = array( '' => __( 'Default', 'wpzoom' ) );

        $this->translation = array(
            'start_color'   => __('Start color', 'wpzoom'),
            'end_color'     => __('End color', 'wpzoom'),
            'direction'     => __('Direction', 'wpzoom'),
            'start_opacity' => __('Start color opacity', 'wpzoom'),
            'end_opacity'   => __('End color opacity', 'wpzoom'),
            'start_location' => __('Start location', 'wpzoom'),
            'end_location'  => __('End location', 'wpzoom'),
        );

        $this->directions = array(
            'horizontal'    => __('Horizontal', 'wpzoom') . '&nbsp;&nbsp;&rarr;',
            'vertical'      => __('Vertical', 'wpzoom') . '&nbsp;&nbsp;&darr;',
            'diagonal-lt'   => __('Diagonal', 'wpzoom') . '&nbsp;&nbsp;&nearr;',
            'diagonal-lb'   => __('Diagonal', 'wpzoom') . '&nbsp;&nbsp;&searr;',
        );

        // Ensure this instance maintains the proper type value.
        $this->type = 'zoom_background_gradient';
    }

    /**
     * Enqueue scripts/styles for the color picker.
     *
     * @since 1.7.1.
     */
    public function enqueue() {
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'jquery-ui-slider' );
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 1.7.1.
     * @uses WP_Customize_Control::to_json()
     */
    public function to_json() {
        parent::to_json();

        $value = $this->value();
        $json_decode = is_JSON( $value ) ? json_decode( $value, true ) : '';

        $this->json['statuses']     = $this->statuses;
        $this->json['defaultValue'] = $this->setting->default;
        $this->json['value']        = !empty( $json_decode ) ? $json_decode[0] : $value;
        $this->json['dependency']   = $this->dependency;
        $this->json['translation']  = $this->translation;
        $this->json['directions']   = $this->directions;
    }

    /**
     * Render a JS template for the content of the color picker control.
     *
     * @since 1.7.1.
     */
    public function content_template() { ?>

        <# var defaultValue = {}, defaultValueAttr = {};
        if ( data.defaultValue && _.isObject( data.defaultValue ) ) {
            _.each( data.defaultValue, function(value, id) {
                if ( id == 'start_color' || id == 'end_color' ) {
                    if ( '#' !== value.substring( 0, 1 ) ) {
                        defaultValue[ id ] = '#' + value;
                    } else {
                        defaultValue[ id ] = value;
                    }

                }

                defaultValue[ id ] = value;

                defaultValueAttr[ id ] = ' data-default-color=' + defaultValue[ id ]; // Quotes added automatically.
            });
        } #>

        <# if ( data.label ) { #>
            <span class="customize-control-title">{{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>
        <div class="zoom-background-gradient-container">
            <ul>
            <li><strong>{{{ data.translation[ 'start_color' ] }}}</strong>
            <label>
                <span class="screen-reader-text">{{{ data.translation[ 'start_color' ] }}}</span>
                <input id="start_color" class="color-picker-hex zoom-background-gradient-start_color" type="text" maxlength="7" placeholder="{{ defaultValue[ 'start_color' ] }}" {{ defaultValueAttr[ 'start_color' ] }} />
            </label></li>

            <li><strong>{{{ data.translation[ 'end_color' ] }}}</strong>
            <label>
                <span class="screen-reader-text">{{{ data.translation[ 'end_color' ] }}}</span>
                <input id="end_color" class="color-picker-hex zoom-background-gradient-end_color" type="text" maxlength="7" placeholder="{{ defaultValue[ 'end_color' ] }}" {{ defaultValueAttr[ 'end_color' ] }} />
            </label></li>

            <li><label>
                <strong>{{{ data.translation[ 'direction' ] }}}</strong>
                <select name="_customize-bg-gradient-directions" id="directions" class="zoom-background-gradient-direction">
                    <# for (key in data.directions) { #>
                        <option value="{{ key }}"<# if (key == data.value[ 'direction' ]) { #> selected="selected" <# } #>>{{{ data.directions[ key ] }}}</option>
                    <# } #>
                </select>
            </label></li>

            <li><strong>{{{ data.translation[ 'start_opacity' ] }}}</strong>
            <div id="range_start_opacity" class="range-opacity-container">
                <div id="slider_start_opacity" class="zoom-range-slider"></div>
                <input
                    id="start_opacity"
                    class="zoom-range-input zoom-background-gradient-start_opacity"
                    type="number"
                    min="0"
                    max="1"
                    step="0.1"
                    value="{{ data.value[ 'start_opacity' ] }}"
                />
            </div></li>

            <li><strong>{{{ data.translation[ 'end_opacity' ] }}}</strong>
            <div id="range_end_opacity" class="range-opacity-container">
                <div id="slider_end_opacity" class="zoom-range-slider"></div>
                <input
                    id="end_opacity"
                    class="zoom-range-input zoom-background-gradient-end_opacity"
                    type="number"
                    min="0"
                    max="1"
                    step="0.1"
                    value="{{ data.value[ 'end_opacity' ] }}"
                />
            </div></li>

            <li><strong>{{{ data.translation[ 'start_location' ] }}}</strong>
            <div id="range_start_location" class="range-opacity-container">
                <div id="slider_start_location" class="zoom-range-slider"></div>
                <input
                    id="start_location"
                    class="zoom-range-input zoom-background-gradient-start_location"
                    type="number"
                    min="0"
                    max="100"
                    step="1"
                    value="{{ data.value[ 'start_location' ] }}"
                />
            </div></li>

            <li><strong>{{{ data.translation[ 'end_location' ] }}}</strong>
            <div id="range_end_location" class="range-opacity-container">
                <div id="slider_end_location" class="zoom-range-slider"></div>
                <input
                    id="end_location"
                    class="zoom-range-input zoom-background-gradient-end_location"
                    type="number"
                    min="0"
                    max="100"
                    step="1"
                    value="{{ data.value[ 'end_location' ] }}"
                />
            </div></li>
        </ul>
        </div>

    <?php
    }
}