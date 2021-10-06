<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Range
 *
 * Specialized range control to enable a slider with an accompanying number field.
 *
 * Inspired by Kirki.
 * @link https://github.com/aristath/kirki/blob/0.5/includes/controls/class-Kirki_Customize_Sliderui_Control.php
 *
 * @since 1.5.0.
 * @since 1.7.0. Converted to content_template().
 */
class WPZOOM_Customizer_Control_Range extends WPZOOM_Customize_Control {
    /**
     * The control type.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    public $type = 'zoom_range';

    /**
     * WPZOOM_Customizer_Control_Range constructor.
     *
     * @since 1.7.0.
     *
     * @param WP_Customize_Manager $manager
     * @param string               $id
     * @param array                $args
     */
    public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
        parent::__construct( $manager, $id, $args );

        // Ensure this instance maintains the proper type value.
        $this->type = 'zoom_range';
    }

    /**
     * Enqueue necessary scripts for this control.
     *
     * @since 1.5.0.
     *
     * @return void
     */
    public function enqueue() {
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

        $this->json['default'] = $this->setting->default;
        if ( isset( $this->default ) ) {
            $this->json['default'] = $this->default;
        }

        $val = $this->value();

        if ( is_array( $val ) ) {
            $val = isset( $val['desktop'] ) ? (int)$val['desktop'] : $this->json['default'];
        }

        $this->json['id'] = $this->id;
        $this->json['value'] = $val;
        $this->json['link'] = $this->get_link();
        $this->json['input_attrs'] = $this->input_attrs;
    }

    /**
     * Define the JS template for the control.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    protected function content_template() { ?>
        <label>
            <# if (data.label) { #>
                <span class="customize-control-title">{{ data.label }}</span>
            <# } #>
            <# if (data.description) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>

            <div id="range_{{ data.id }}" class="zoom-range-container">
                <div id="slider_{{ data.id }}" class="zoom-range-slider"></div>
                <input
                    id="input_{{ data.id }}"
                    class="zoom-range-input"
                    type="number"
                    <# for (key in data.input_attrs) { #> {{ key }}="{{ data.input_attrs[ key ] }}" <# } #>
                    value="{{ data.value }}"
                    data-value="{{ data.value }}"
                    {{{ data.link }}}
                />
            </div>
        </label>
    <?php }
}