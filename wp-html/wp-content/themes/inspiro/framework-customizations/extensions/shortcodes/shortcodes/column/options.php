<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}


$colors             = array(
    'color_1' => '#1A1A1A',
    'color_2' => '#0bb4aa',
    'color_3' => '#f4f4f4'
);

$admin_url = admin_url();
$color_settings = fw_get_db_settings_option('color_settings', $colors);

$options = array(

	'padding_group' => array(
		'type' => 'group',
		'options' => array(
			'html_label'  => array(
				'type'  => 'html',
				'value' => '',
				'label' => __('Additional Spacing', 'fw'),
				'html'  => '',
			),
			'padding_top'  => array(
				'label'   => false,
				'desc'    => __( 'top', 'fw' ),
				'type'  => 'short-text',
				'value' => '0',
			),
			'padding_right'  => array(
				'label'   => false,
				'desc'    => __( 'right', 'fw' ),
				'type'  => 'short-text',
				'value' => '0',
			),
			'padding_bottom'  => array(
				'label'   => false,
				'desc'    => __( 'bottom', 'fw' ),
				'type'  => 'short-text',
				'value' => '0',
			),
			'padding_left'  => array(
				'label'   => false,
				'desc'    => __( 'left', 'fw' ),
				'type'  => 'short-text',
				'value' => '0',
			),
		)
	),
    'background_options' => array(
        'type'  => 'multi-picker',
        'label' => false,
        'desc'  => false,
        'picker' => array(
            'background' => array(
                'label' => __('Background', 'fw'),
                'desc'  => __('Select the background for your column', 'fw'),
                'attr'  => array( 'class' => 'fw-checkbox-float-left' ),
                'type'  => 'radio',
                'choices' => array(
                    'none'  => __('None', 'fw'),
                    'image' => __('Image', 'fw'),
                    'color' => __('Color', 'fw'),
                ),
                'value' => 'none'
            ),
        ),
        'choices' => array(
            'none' => array(),
            'color' => array(
                'background_color' => array(
                    'label' => __('', 'fw'),
                    'help'    => __('The default color palette can be changed from the', 'fw').' <a target="_blank" href="'.$admin_url.'themes.php?page=fw-settings&_focus_tab=fw-options-tab-styling">'.__('Styling section', 'fw').'</a> '.__('found in the Theme Settings page','fw'),
                    'desc'    => __( 'Select the background color', 'fw' ),
                    'value'   => '',
                    'choices' => $color_settings,
                    'type'    => 'color-palette'
                ),
            ),
            'image' => array(
                'background_image' => array(
                    'label'   => __( '', 'fw' ),
                    'type'    => 'background-image',
                    'choices' => array(//	in future may will set predefined images
                    )
                ),
                'background_color' => array(
                    'label' => __('', 'fw'),
                    'help'    => __('The default color palette can be changed from the', 'fw').' <a target="_blank" href="'.$admin_url.'themes.php?page=fw-settings&_focus_tab=fw-options-tab-styling">'.__('Styling section', 'fw').'</a> '.__('found in the Theme Settings page','fw'),
                    'desc'    => __( 'Select the background color', 'fw' ),
                    'value'   => '',
                    'choices' => $color_settings,
                    'type'    => 'color-palette'
                ),
                'repeat' => array(
                    'label' => __( '', 'fw' ),
                    'desc'  => __( 'Select how will the background repeat', 'fw' ),
                    'type'  => 'short-select',
                    'attr'  => array( 'class' => 'fw-checkbox-float-left' ),
                    'value' => 'no-repeat',
                    'choices' => array(
                        'no-repeat' => __( 'No-Repeat', 'fw' ),
                        'repeat' => __( 'Repeat', 'fw' ),
                        'repeat-x' => __( 'Repeat-X', 'fw' ),
                        'repeat-y' => __( 'Repeat-Y', 'fw' ),
                    )
                ),
                'bg_position_x' => array(
                    'label' => __( 'Position', 'fw' ),
                    'desc'  => __( 'Select the horizontal background position', 'fw' ),
                    'type'  => 'short-select',
                    'attr'  => array( 'class' => 'fw-checkbox-float-left' ),
                    'value' => '',
                    'choices' => array(
                        'left' => __( 'Left', 'fw' ),
                        'center' => __( 'Center', 'fw' ),
                        'right' => __( 'Right', 'fw' ),
                    )
                ),
                'bg_position_y' => array(
                    'label' => __( '', 'fw' ),
                    'desc'  => __( 'Select the vertical background position', 'fw' ),
                    'type'  => 'short-select',
                    'attr'  => array( 'class' => 'fw-checkbox-float-left' ),
                    'value' => '',
                    'choices' => array(
                        'top' => __( 'Top', 'fw' ),
                        'center' => __( 'Center', 'fw' ),
                        'bottom' => __( 'Bottom', 'fw' ),
                    )
                ),
                'bg_size' => array(
                    'label' => __( 'Size', 'fw' ),
                    'desc'  => __( 'Select the background size', 'fw' ),
                    'help'  => __( '<strong>Auto</strong> - Default value, the background image has the original width and height.<br><br><strong>Cover</strong> - Scale the background image so that the background area is completely covered by the image.<br><br><strong>Contain</strong> - Scale the image to the largest size such that both its width and height can fit inside the content area.', 'fw' ),
                    'type'  => 'short-select',
                    'attr'  => array( 'class' => 'fw-checkbox-float-left' ),
                    'value' => '',
                    'choices' => array(
                        'auto' => __( 'Auto', 'fw' ),
                        'cover' => __( 'Cover', 'fw' ),
                        'contain' => __( 'Contain', 'fw' ),
                    )
                ),
                'parallax' => array(
                    'type'  => 'switch',
                    'label' => __( 'Parallax Effect', 'fw' ),
                    'desc'  => __( 'Create a parallax effect on scroll?', 'fw' ),
                    'value' => '',
                    'right-choice' => array(
                        'value' => 'yes',
                        'label' => __('Yes', 'fw'),
                    ),
                    'left-choice' => array(
                        'value' => 'no',
                        'label' => __('No', 'fw'),
                    ),
                ),
                'overlay_options' => array(
                    'type'  => 'multi-picker',
                    'label' => false,
                    'desc'  => false,
                    'picker' => array(
                        'overlay' => array(
                            'type'  => 'switch',
                            'label' => __( 'Overlay Color', 'fw' ),
                            'desc'  => __( 'Enable image overlay color?', 'fw' ),
                            'value' => 'no',
                            'right-choice' => array(
                                'value' => 'yes',
                                'label' => __('Yes', 'fw'),
                            ),
                            'left-choice' => array(
                                'value' => 'no',
                                'label' => __('No', 'fw'),
                            ),
                        ),
                    ),
                    'choices' => array(
                        'no'  => array(),
                        'yes' => array(
                            'background' => array(
                                'label'   => __('', 'fw'),
                                'desc'    => __('Select the overlay color', 'fw'),
                                'value'   => $color_settings['color_1'],
                                'choices' => $color_settings,
                                'type'    => 'color-palette'
                            ),
                            'overlay_opacity_image' => array(
                                'type'  => 'slider',
                                'value' => 100,
                                'properties' => array(
                                    'min' => 0,
                                    'max' => 100,
                                    'sep' => 1,
                                ),
                                'label' => __('', 'fw'),
                                'desc'  => __('Select the overlay color opacity in %', 'fw'),
                            )
                        ),
                    ),
                ),
            ),
        ),
        'show_borders' => false,
    ),

	'class'  => array(
		'label' => __( 'Custom Class', 'fw' ),
		'desc'  => __( 'Enter custom CSS class', 'fw' ),
		'help'  => __( 'You can use this class to further style this shortcode by adding your custom CSS.', 'fw' ),
		'type'  => 'text',
		'value' => '',
	),
);