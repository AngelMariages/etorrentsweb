<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Upload
 *
 * Extended Customize Color Control class.
 *
 *
 * @since 1.7.1.
 */
class WPZOOM_Customizer_Control_Upload extends WP_Customize_Upload_Control {
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

        $value = $this->value();

        if ( $value ) {
            // Get the attachment model for the existing file.
            $attachment_id = attachment_url_to_postid( $value );
            if ( $attachment_id ) {
                $this->json['attachment'] = wp_prepare_attachment_for_js( $attachment_id );
            }
        }
        
        $this->json['dependency'] = $this->dependency;
    }
    
}