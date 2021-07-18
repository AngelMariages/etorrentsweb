<?php

/**
 * @class WPZABBSeparatorModule
 */
class WPZABBSeparatorModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Simple Separator', 'wpzabb'),
			'description'   	=> __('A divider line to separate content.', 'wpzabb'),
			'category'      => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'separator/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'separator/',
            'editor_export' 	=> false,
			'partial_refresh'	=> true,
			'icon'				=> 'minus.svg',
			
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBSeparatorModule', array(
	'general'       => array( // Tab
		'title'         => __('General', 'wpzabb'), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'color' => array( 
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
					'height'        => array(
						'type'          => 'text',
						'label'         => __('Thickness', 'wpzabb'),
						'placeholder'   => '1',
						'maxlength'     => '2',
						'size'          => '3',
						'description'   => 'px',
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-separator',
							'property'      => 'border-top-width',
							'unit'          => 'px'
						),
						'help'			=> __( 'Thickness of Border', 'wpzabb' )
					),
					'width'        => array(
						'type'          => 'text',
						'label'         => __('Width', 'wpzabb'),
						'placeholder'   => '100',
						'maxlength'     => '3',
						'size'          => '5',
						'description'   => '%'
					),
					'alignment'         => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'center'      => __( 'Center', 'wpzabb' ),
							'left'        => __( 'Left', 'wpzabb' ),
							'right'       => __( 'Right', 'wpzabb' )
						),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-separator-parent',
							'property'      => 'text-align'
						),
						'help'          => __('Align the border.', 'wpzabb'),
					),
					'style'         => array(
						'type'          => 'select',
						'label'         => __('Style', 'wpzabb'),
						'default'       => 'solid',
						'options'       => array(
							'solid'         => __( 'Solid', 'wpzabb' ),
							'dashed'        => __( 'Dashed', 'wpzabb' ),
							'dotted'        => __( 'Dotted', 'wpzabb' ),
							'double'        => __( 'Double', 'wpzabb' )
						),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-separator',
							'property'      => 'border-top-style'
						),
						'help'          => __('The type of border to use. Double borders must have a height of at least 3px to render properly.', 'wpzabb'),
					)
				)
			)
		)
	)
));
