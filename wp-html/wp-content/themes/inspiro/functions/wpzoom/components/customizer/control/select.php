<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Select
 *
 * Customize Select Control class
 *
 * @since 1.7.0.
 *
 * @see WP_Customize_Control
 */
class WPZOOM_Customizer_Control_Select extends WPZOOM_Customize_Control {
    /**
     * The control type.
     *
     * @since 1.7.0.
     *
     * @var string
     */
    public $type = 'zoom_select';

    /**
     * WPZOOM_Customizer_Control_Select constructor.
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
        $this->type = 'zoom_select';
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

        <# if (data.description) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>
        
        <div id="input_{{ data.id }}" class="zoom-select-container">
            <select id="{{ data.id }}" name="_customize-select-{{ data.id }}" {{{ data.link }}}>
            <# for (key in data.choices) { #>
                <option value="{{ key }}"<# if (key == data.value) { #> selected="selected" <# } #>>{{ data.choices[key] }}</option>
            <# } #>
            </select>
        </div>
    <?php
    }
}