<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$admin_url           = admin_url();

$options = array(
	'title'    => array(
		'type'  => 'text',
		'label' => __( 'Title', 'fw' ),
	),
	'subtitle' => array(
		'type'  => 'text',
		'label' => __( 'Subtitle', 'fw' ),
 	),
	'heading' => array(
		'type'    => 'select',
		'label'   => __('Size', 'fw'),
		'choices' => array(
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
		)
	),
    'heading_color' => array(
        'type'         => 'color-picker',
        'label'   => __( 'Color', 'fw' ),
    ),
	'centered' => array(
		'type'    => 'switch',
		'label'   => __('Centered', 'fw'),
	)
);
