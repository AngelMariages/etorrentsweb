<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Image
 *
 * Extended Customize Color Control class.
 *
 *
 * @since 1.7.1.
 */
class WPZOOM_Customizer_Control_Image extends WP_Customize_Image_Control {
    /**
     * The control contextual dependency.
     *
     * @since 1.7.1.
     *
     * @var string
     */
    public $dependency = false;

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 1.7.1.
     * @uses WP_Customize_Control::to_json()
     */
    public function to_json() {
        parent::to_json();

        $this->json['dependency'] = $this->dependency;
    }
    
}