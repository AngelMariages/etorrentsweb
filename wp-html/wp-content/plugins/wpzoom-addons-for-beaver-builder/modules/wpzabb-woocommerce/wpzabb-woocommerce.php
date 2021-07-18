<?php
// Bail early if WooCommerce is not activated...
if ( ! post_type_exists( 'product' ) ) return;

/**
 * @class WPZABBWooCommerceModule
 */
class WPZABBWooCommerceModule extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'              => __( 'WooCommerce', 'wpzabb' ),
			'description'       => __( 'Displays a selection of WooCommerce products.', 'wpzabb' ),
			'category'          => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'               => BB_WPZOOM_ADDON_DIR . 'modules/' . WPZABB_PREFIX . 'woocommerce/',
			'url'               => BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'woocommerce/',
			'partial_refresh'   => true
		) );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBWooCommerceModule', array(
	'general' => array(
		'title' => __( 'General', 'wpzabb' ),
		'sections' => array(
			'info' => array(
				'title'  => '',
				'fields' => array(
					'category'      => array(
						'type'      => 'select',
						'label'     => __( 'Display From Category', 'wpzabb' ),
						'default'   => '0',
						'options'   => wpzabb_get_category_term_list( 'product' )
					),
					'count'         => array(
						'type'      => 'unit',
						'label'     => __( 'Number of Products', 'wpzabb' ),
						'default'   => '8',
						'slider'     => array(
							'min'  	 => 1,
							'max'  	 => 1000,
							'step' 	 => 1
						)
					),
					'featured'      => array(
						'type'      => 'button-group',
						'label'     => __( 'Only Include Featured Products', 'wpzabb' ),
						'default'   => 'no',
						'options'   => array(
							'yes'  => __( 'Yes', 'wpzabb' ),
							'no' => __( 'No', 'wpzabb' )
						)
					),
					'orderby'       => array(
						'type'      => 'button-group',
						'label'     => __( 'Order By', 'wpzabb' ),
						'default'   => 'date',
						'options'   => array(
							'date'  => __( 'Date', 'wpzabb' ),
							'price' => __( 'Price', 'wpzabb' ),
							'rand'  => __( 'Random', 'wpzabb' ),
							'sales' => __( 'Sales', 'wpzabb' )
						)
					),
					'orderdir'      => array(
						'type'      => 'button-group',
						'label'     => __( 'Order Direction', 'wpzabb' ),
						'default'   => 'desc',
						'options'   => array(
							'asc'   => __( 'Ascending', 'wpzabb' ),
							'desc'  => __( 'Descending', 'wpzabb' )
						)
					),
					'showprice'     => array(
						'type'      => 'button-group',
						'label'     => __( 'Product Price', 'wpzabb' ),
						'default'   => 'true',
						'options'   => array(
							'true'  => __( 'Show', 'wpzabb' ),
							'false' => __( 'Hide', 'wpzabb' )
						)
					),
					'showcartbtn'   => array(
						'type'      => 'button-group',
						'label'     => __( 'Add to Cart Button', 'wpzabb' ),
						'default'   => 'true',
						'options'   => array(
							'true'  => __( 'Show', 'wpzabb' ),
							'false' => __( 'Hide', 'wpzabb' )
						)
					)
				)
			)
		)
	),
	'layout' => array(
		'title' => __( 'Layout', 'wpzabb' ),
		'sections' => array(
			'style' => array(
				'title'  => '',
				'fields' => array(
					'columns'        => array(
						'type'       => 'unit',
						'label'      => __( 'Amount of Columns', 'wpzabb' ),
						'default'    => 4,
						'slider'     => array(
							'min'  	 => 1,
							'max'  	 => 10,
							'step' 	 => 1
						),
						'responsive' => array(
							'default' => array(
								'default'    =>  4,
								'medium'     =>  2,
								'responsive' =>  1
							)
						)
					)
				)
			)
		)
	)
) );