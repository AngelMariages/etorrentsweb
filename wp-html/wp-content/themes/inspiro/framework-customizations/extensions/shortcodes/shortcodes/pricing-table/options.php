<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
$colors             = array(
    'pricing_1' => '#161719',
    'pricing_2' => '#0700ce',
    'pricing_3' => '#52cc5a',
    'pricing_4' => '#f17e12',
    'pricing_5' => '#C22828'
);
$admin_url           = admin_url();

$options = array(
    'title'    => array(
        'type'  => 'text',
        'label' => __( 'Pricing Option Title', 'fw' )
    ),
    'price'  => array(
        'label' => __( 'Price', 'fw' ),
        'type'  => 'text',
        'value' => '$99'
    ),
    'duration'  => array(
        'label' => __( 'Duration', 'fw' ),
        'type'  => 'text',
        'value' => 'per month'
    ),
    'background_options' => array(
        'type'         => 'multi-picker',
        'label'        => false,
        'desc'         => false,
        'picker'       => array(
            'background' => array(
                'label'   => esc_html__( 'Background', 'fw' ),
                'desc'    => esc_html__( 'Select the background for your section', 'fw' ),
                'attr'    => array( 'class' => 'fw-checkbox-float-left' ),
                'type'    => 'radio',
                'choices' => array(
                    'none'    => esc_html__( 'None', 'fw' ),
                    'color'   => esc_html__( 'Color', 'fw' ),
                ),
                'value'   => 'none'
            ),
        ),
        'choices'      => array(
            'none'  => array(),
            'color' => array(
                'background_color' => array(
                    'label'   => esc_html__( '', 'fw' ),
                    'desc'    => esc_html__( 'Select the background color', 'fw' ),
                    'value'   => '',
                    'choices' => $colors,
                    'type'    => 'color-palette'
                ),
            ),
        ),
        'show_borders' => false,
    ),
	'tabs' => array(
		'type'          => 'addable-popup',
		'label'         => __( 'Features', 'fw' ),
		'popup-title'   => __( 'Add/Edit a Feature', 'fw' ),
        'desc'          => __( 'Click Add for each new feature', 'fw' ),
		'template'      => '{{=tab_title}}',
		'popup-options' => array(
			'tab_title' => array(
				'type'  => 'text',
				'label' => __('Enter feature name', 'fw')
			)
		),
	),
    'label'  => array(
        'label' => __( 'Button Label', 'fw' ),
        'desc'  => __( 'This is the text that appears on your button', 'fw' ),
        'type'  => 'text',
        'value' => 'Join Today'
    ),
    'link'   => array(
        'label' => __( 'Button Link', 'fw' ),
        'desc'  => __( 'Where should your button link to', 'fw' ),
        'type'  => 'text',
        'value' => '#'
    ),
    'target' => array(
        'type'  => 'switch',
        'label'   => __( 'Open Link in New Window', 'fw' ),
        'desc'    => __( 'Select here if you want to open the linked page in a new window', 'fw' ),
        'right-choice' => array(
            'value' => '_blank',
            'label' => __('Yes', 'fw'),
        ),
        'left-choice' => array(
            'value' => '_self',
            'label' => __('No', 'fw'),
        ),
    ),

);