<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$cfg = array(
	'page_builder' => array(
		'tab'         => esc_html__( 'Layout Elements', 'fw' ),
		'title'       => esc_html__( 'Section', 'fw' ),
		'description' => esc_html__( 'Add a Section', 'fw' ),
		'popup_size'  => 'medium',
		'type'        => 'section' // WARNING: Do not edit this
	)
);