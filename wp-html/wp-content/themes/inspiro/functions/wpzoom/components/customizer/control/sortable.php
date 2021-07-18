<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Sortable
 *
 * Customize Select Control class
 *
 * @since 1.7.0.
 *
 * @see WP_Customize_Control
 */
class WPZOOM_Customizer_Control_Sortable extends WPZOOM_Customize_Control {
    /**
     * The control type.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    public $type = 'zoom_sortable';

    /**
     * WPZOOM_Customizer_Control_Sortable constructor.
     *
     * @since 1.7.0.
     *
     * @param WP_Customize_Manager $manager
     * @param string               $id
     * @param array                $args
     */
    public function __construct( WP_Customize_Manager $manager, $id, $args = array() ) {
        parent::__construct($manager, $id, $args);

        // Ensure this instance maintains the proper type value.
        $this->type = 'zoom_sortable';
    }
    
    /**
     * Enqueue necessary scripts for this control.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    public function enqueue() {
        
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 1.7.1.
     * @uses WP_Customize_Control::to_json()
     */
    public function to_json() {
        parent::to_json();

        $this->json['id'] = $this->id;
        $this->json['choices'] = $this->choices;
        $this->json['value'] = $this->value();
        $this->json['datalink'] = $this->get_link();
        $this->json['defaultValue'] = $this->setting->default;

        if ( ! empty( $this->json['defaultValue'] ) && empty( $this->json['value'] ) ) {
            $this->json['value'] = $this->json['defaultValue'];
        }

        if ( ! empty( $this->json['value'] ) ) {

            $value = str_split($this->json['value']);
            $newarray = array();

            foreach ($value as $key) {
                $newarray[ $key ] = $this->json['choices'][ $key ];
            }

            $this->json['choices'] = $newarray;

        } else {
            foreach ($this->json['choices'] as $key => $value) {
                $this->json['value'] .= $key;
            }
        }
    }

    /**
     * Define the JS template for the control.
     *
     * @since 1.7.0.
     *
     * @return void
     */
    protected function content_template() {
        ?>
        <# if (data.label) { #>
            <span class="customize-control-title">{{ data.label }}</span>
        <# } #>

        <div id="input_{{ data.id }}" class="zoom-sortable-container">
            <ol id="{{ data.id }}" class="zoom-elements-order-sortable jquery-sortable" tabindex="-1" {{{ data.link }}}>
                <# for (key in data.choices) { #>
                    <li id="order-item-{{ key }}" data-item-value="{{ key }}" class="zoom-elements-order-sortable-{{ key }}">{{ data.choices[key] }}</li>
                <# } #>
            </ol>
            <input type="hidden" name="_customize-select-{{ data.id }}" value="{{ data.value }}">
        </div>

        <# if (data.description) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>
    <?php
    }
}