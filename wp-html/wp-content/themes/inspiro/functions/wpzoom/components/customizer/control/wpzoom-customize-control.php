<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customize_Control
 *
 * Extended Customize Color Control class.
 *
 *
 * @since 1.7.1.
 */
class WPZOOM_Customize_Control extends WP_Customize_Control {
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