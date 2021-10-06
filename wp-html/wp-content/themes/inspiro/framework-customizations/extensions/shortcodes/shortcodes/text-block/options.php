<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
$options = array(
	'text_group'      => array(
		'type'    => 'group',
		'options' => array(
			'text'                  => array(
				'attr'    => array( 'class' => 'text_advanced_styling' ),
				'type'    => 'wp-editor',
				'tinymce' => true,
				'reinit'  => true,
				'wpautop' => true,
				'label'   => esc_html__( 'Content', 'fw' ),
				'desc'    => esc_html__( 'Enter some content for this texblock', 'fw' )
			),
		)
	)
);
