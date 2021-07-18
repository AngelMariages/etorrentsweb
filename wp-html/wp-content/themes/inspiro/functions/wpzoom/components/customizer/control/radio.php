<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Radio
 *
 * Specialized radio control to enable buttonset-style choices.
 *
 * Inspired by Kirki.
 * @link https://github.com/aristath/kirki/blob/0.5/includes/controls/class-Kirki_Customize_Radio_Control.php
 *
 * @since 1.7.0.
 */
class WPZOOM_Customizer_Control_Radio extends WPZOOM_Customize_Control {
    /**
     * The control type.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    public $type = 'zoom_radio';

    /**
     * The control mode.
     *
     * Possible values are 'buttonset', 'image', and 'radio'.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    public $mode = 'radio';

    /**
     * The styles for 'buttonset' mode.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    public $styles = array();

    /**
     * WPZOOM_Customizer_Control_Radio constructor.
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
        $this->type = 'zoom_radio';
    }

    /**
     * Enqueue necessary scripts for this control.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    public function enqueue() {
        if ( 'buttonset' === $this->mode || 'image' === $this->mode ) {
            wp_enqueue_script( 'jquery-ui-button' );
        }
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 1.7.1.
     * @uses WP_Customize_Control::to_json()
     */
    public function to_json() {
        parent::to_json();

        // Define styles
        if ( strpos( $this->id, 'font-weight' ) ) {
            $this->styles = array(
                'normal' => 'font-weight: normal',
                'bold' => 'font-weight: bold',
                '100' => 'font-weight: 100',
                '200' => 'font-weight: 200',
                '300' => 'font-weight: 300',
                '400' => 'font-weight: 400',
                '500' => 'font-weight: 500',
                '600' => 'font-weight: 600',
                '700' => 'font-weight: 700',
                '800' => 'font-weight: 800',
                '900' => 'font-weight: 900'
            );
        } elseif ( strpos( $this->id, 'font-style' ) ) {
            $this->styles = array(
                'italic' => 'font-style: italic',
                'normal' => 'font-style: normal',
                'oblique' => 'font-style: oblique'
            );
        } elseif ( strpos( $this->id, 'text-transform' ) ) {
            $this->styles = array(
                'none' => 'text-transform: none',
                'capitalize' => 'text-transform: capitalize',
                'lowercase' => 'text-transform: lowercase',
                'uppercase' => 'text-transform: uppercase',
            );
        }

        $this->json['id'] = $this->id;
        $this->json['mode'] = $this->mode;
        $this->json['choices'] = $this->choices;
        $this->json['styles'] = $this->styles;
        $this->json['value'] = $this->value();
        $this->json['link'] = $this->get_link();
    }

    /**
     * Define the JS template for the control.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    protected function content_template() { ?>
        <# if (data.label) { #>
            <span class="customize-control-title">{{ data.label }}</span>
        <# } #>
        <# if (data.description) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>

        <div id="input_{{ data.id }}" class="zoom-radio-container<# if (0 <= ['buttonset', 'image'].indexOf( data.mode )) { #> zoom-radio-{{ data.mode }}-container<# } #>">
            <# if ('buttonset' === data.mode) { #>
                <# for (key in data.choices) { #>
                    <input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key == data.value) { #> checked="checked" <# } #> />
                    <label for="{{ data.id }}{{ key }}" <# if ( data.styles[ key ] ) { #> style="{{ data.styles[ key ] }}" <# } #> >{{ data.choices[ key ] }}</label>
                <# } #>
            <# } else if ('image' === data.mode) { #>
                <# for (key in data.choices) { #>
                    <input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" class="image-select" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key == data.value) { #> checked="checked" <# } #> />
                    <label for="{{ data.id }}{{ key }}"><img src="{{ data.choices[ key ] }}" alt="{{ key }}" /></label>
                <# } #>
            <# } else { #>
                <# for (key in data.choices) { #>
                    <label for="{{ data.id }}{{ key }}" class="customizer-radio">
                        <input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key == data.value) { #> checked="checked" <# } #> />
                        {{ data.choices[ key ] }}<br />
                    </label>
                <# } #>
            <# } #>
        </div>
    <?php
    }
}