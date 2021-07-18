<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Text
 *
 * Input type 'text'
 *
 *
 * @since 1.7.1.
 */
class WPZOOM_Customizer_Control_Text extends WPZOOM_Customize_Control {
    /**
     * The control type.
     *
     * @since 1.7.1.
     *
     * @var string
     */
    public $type = 'zoom_text';

    /**
     * WPZOOM_Customizer_Control_Text constructor.
     *
     * @since 1.7.1.
     *
     * @param WP_Customize_Manager $manager
     * @param string               $id
     * @param array                $args
     */
    public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
        parent::__construct( $manager, $id, $args );

        // Ensure this instance maintains the proper type value.
        $this->type = 'zoom_text';
    }

    /**
     * Enqueue necessary scripts for this control.
     *
     * @since 1.7.1.
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
        $this->json['value'] = $this->value();
        $this->json['link'] = $this->get_link();
    }

    /**
     * Define the JS template for the control.
     *
     * @since 1.7.1.
     *
     * @return void
     */
    protected function content_template() { ?>
        <#
            data.input_id = '_customize-input-' + data.id;
            data.description_id = '_customize-description-' + data.id;
        #>
        <span class="zoom-text-container">
            <# if (data.label) { #>
                <label for="{{ data.input_id }}" class="customize-control-title">{{{ data.label }}}</label>
            <# } #>
            
            <# if (data.description) { #>
                <span id="{{{ data.description_id }}}" class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>

            <input
                id="{{ data.input_id }}"
                type="text"
                <# for (key in data.input_attrs) { #> {{ key }}="{{ data.input_attrs[ key ] }}" <# } #>
                value="{{ data.value }}"
                {{{ data.link }}}
            />
        </span>
    <?php
    }

    
}