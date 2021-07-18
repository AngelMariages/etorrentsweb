<?php

/**
 * @class WPZABBMapModule
 */
class WPZABBMapModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Map', 'wpzabb' ),
			'description'   	=> __( 'Display a Google map.', 'wpzabb' ),
			'category'          => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'map/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'map/',
            'partial_refresh'	=> true,
			'icon'				=> 'location.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBMapModule', array(
	'general'       => array(
		'title'         => __( 'General', 'wpzabb' ),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'address'       => array(
						'type'          => 'textarea',
						'rows'			=> '3',
						'label'         => __( 'Address', 'wpzabb' ),
						'placeholder'   => __( '1865 Winchester Blvd #202 Campbell, CA 95008', 'wpzabb' ),
						'preview'       => array(
							'type'            => 'refresh',
						),
						'connections'	=> array( 'custom_field' ),
					),
					'height'        => array(
						'type'          => 'text',
						'label'         => __( 'Height', 'wpzabb' ),
						'default'       => '400',
						'size'          => '5',
						'description'   => 'px',
						'sanitize'		=> 'absint',
					),
				),
			),
		),
	),
));
