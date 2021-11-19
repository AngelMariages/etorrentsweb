<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'image' => array(
		'label' => __( 'Background Image', 'fw' ),
		'desc'  => __( 'Either upload a new, or choose an existing image from your media library', 'fw' ),
		'type'  => 'upload'
	),
	'name'  => array(
		'label' => __( 'Title', 'fw' ),
		'type'  => 'text',
		'value' => ''
	),
	'subtitle'   => array(
		'label' => __( 'Subtitle', 'fw' ),
		'type'  => 'text',
		'value' => ''
	),
	'desc'  => array(
		'label' => __( 'Description', 'fw' ),
		'desc'  => __( 'Enter a few words that describe this widget', 'fw' ),
		'type'  => 'textarea',
		'value' => ''
	),

    'link'   => array(
        'label' => 'URL',
        'desc'  => __( 'Enter the URL of a page or a Portfolio Category. You can view all Portfolio Categories <a href="edit-tags.php?taxonomy=portfolio&post_type=portfolio_item" target="_blank">here</a>.', 'fw' ),
        'type'  => 'text',
        'value' => ''
    ),
    'target' => array(
        'label' => '',
        'type'  => 'switch',
        'value' => '_self',
        'desc'    => __( 'Open Link in New Window', 'fw' ),
        'right-choice' => array(
            'value' => '_blank',
            'label' => __('Yes', 'fw'),
        ),
        'left-choice' => array(
            'value' => '_self',
            'label' => __('No', 'fw'),
        ),
    ),


    'button' => array(
        'type'  => 'switch',
        'label'   => __( 'Button', 'fw' ),
        'value' => 'button_all_show',
        'right-choice' => array(
            'value' => 'button_all_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'button_all_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),

    'label'  => array(
        'label' => 'Button Label',
        'type'  => 'text',
        'value' => 'View Gallery'
    ),


);