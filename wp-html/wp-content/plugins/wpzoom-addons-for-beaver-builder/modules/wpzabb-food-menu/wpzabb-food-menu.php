<?php

/**
 * @class WPZABBFoodMenuModule
 */
class WPZABBFoodMenuModule extends FLBuilderModule
{
	/**
	 * @property $data
	 */
	public $data = null;

	/**
	 * @property $_editor
	 * @protected
	 */
	protected $_editor = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'          	=> __( 'Food Menu', 'wpzabb' ),
			'description'   	=> __( 'A menu of food items.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/' . WPZABB_PREFIX . 'food-menu/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/',
            'partial_refresh'	=> true
		) );

		add_filter( 'wpzabb_food_menu_price_units', array( $this, 'food_menu_price_units' ) );
	}

	public function food_menu_price_units( $price_units ) {
		$price_units[] = 'лв';
		$price_units[] = 'CHF';
		$price_units[] = 'Kč';
		$price_units[] = 'kr';
		$price_units[] = 'kn';
		$price_units[] = '₾';
		$price_units[] = 'ft';
		$price_units[] = 'zł';
		$price_units[] = '₽';
		$price_units[] = 'lei';
		$price_units[] = '₺';
		$price_units[] = '₴';

		return $price_units;
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings )
	{
		$menu_item = $settings->menu_items[0];

		// Make sure we have a image_src property.
		if ( !isset( $settings->image_src ) )
		{
			$settings->image_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data( $menu_item->image );

		if ( $data )
		{
			$settings->data = $data;
		}

		return $settings;
	}

	/**
	 * @method get_data
	 * @param $menu_item {object}
	 */
	public function get_data( $menu_item )
	{
		if ( !$this->data )
		{
			// Photo source is set to "url".
			if ( $menu_item->image_source == 'url' )
			{
				$this->data = new stdClass();
				$this->data->url = $menu_item->image_url;
				$menu_item->image_src = $menu_item->image_url;
			}
			else if ( is_object( $menu_item->image ) ) // Photo source is set to "library".
			{
				$this->data = $menu_item->image;
			}
			else
			{
				$this->data = FLBuilderPhoto::get_attachment_data( $menu_item->image );
			}

			// Data object is empty, use the settings cache.
			if ( !$this->data && isset( $menu_item->data ) )
			{
				$this->data = $menu_item->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $menu_item {object}
	 */
	public function get_classes( $menu_item )
	{
		$classes = array( 'wpzabb-photo-img' );
		
		if ( $menu_item->image_source == 'library' )
		{
			if ( ! empty( $menu_item->image ) )
			{
				$data = self::get_data( $menu_item );
				
				if ( is_object( $data ) )
				{
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) )
					{
						foreach ( $data->sizes as $key => $size )
						{
							if ( $size->url == $menu_item->image_src )
							{
								$classes[] = 'size-' . $key;
								break;
							}
						}
					}
				}
			}
		}
		
		return implode( ' ', $classes );
	}

	/**
	 * @method get_src
	 * @param $menu_item {object}
	 */
	public function get_src( $menu_item )
	{
		$src = $this->_get_uncropped_url( $menu_item );

		return $src;
	}

	/**
	 * @method get_alt
	 * @param $menu_item {object}
	 */
	public function get_alt( $menu_item )
	{
		$photo = $this->get_data( $menu_item );

		if ( !empty( $photo->alt ) )
		{
			return htmlspecialchars( $photo->alt );
		}
		else if ( !empty( $photo->description ) )
		{
			return htmlspecialchars( $photo->description );
		}
		else if ( !empty( $photo->caption ) )
		{
			return htmlspecialchars( $photo->caption );
		}
		else if( !empty( $photo->title ) )
		{
			return htmlspecialchars( $photo->title );
		}
	}

	/**
	 * @method _has_source
	 * @param $menu_item {object}
	 * @protected
	 */
	protected function _has_source( $menu_item )
	{
		if ( $menu_item->image_source == 'url' && !empty( $menu_item->image_url ) )
		{
			return true;
		}
		else if ( $menu_item->image_source == 'library' && !empty( $menu_item->image_src ) )
		{
			return true;
		}

		return false;
	}

	/**
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor()
	{
		foreach ( $settings->menu_items as $i => $menu_item )
		{
			if ( $this->_has_source( $menu_item ) && $this->_editor === null )
			{
				$url_path  = $this->_get_uncropped_url( $menu_item );
				$file_path = str_ireplace( home_url(), ABSPATH, $url_path );

				if ( file_exists( $file_path ) )
				{
					$this->_editor = wp_get_image_editor( $file_path );
				}
				else
				{
					$this->_editor = wp_get_image_editor( $url_path );
				}
			}
		}

		return $this->_editor;
	}

	/**
	 * @method _get_uncropped_url
	 * @param $menu_item {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $menu_item )
	{
		if ( $menu_item->image_source == 'url' )
		{
			$url = $menu_item->image_url;
		}
		else if( !empty( $menu_item->image_src ) )
		{
			$url = $menu_item->image_src;
		}
		else
		{
			$url = '';
		}

		return $url;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBFoodMenuModule', array(
	'general'    => array( // Tab
		'title'    => __( 'General', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'general'                => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'menu_title'             => array(
						'type'          => 'text',
						'label'         => __( 'Menu Title', 'wpzabb' ),
						'placeholder'   => __( 'The title of the menu...', 'wpzabb' ),
						'default'       => 'Our Menu'
					),
					'currency_position' => array(
						'type'          => 'select',
						'label'         => __( 'Currency Position', 'wpzabb' ),
						'default'       => 'before',
						'options'       => array(
							'before'     => __( 'Before - $10', 'wpzabb' ),
							'after'      => __( 'After - 10$', 'wpzabb' )
						)
					),
					'menu_button'            => array(
						'type'          => 'select',
						'label'         => __( 'Show Menu Button', 'wpzabb' ),
						'default'       => 'no',
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						),
						'toggle'        => array(
							'yes'             => array(
								'sections' => array( 'style_button' ),
								'fields'   => array( 'menu_button_label', 'menu_button_url' )
							),
							'no'              => array()
						)
					),
					'menu_button_label'      => array(
						'type'          => 'text',
						'label'         => __( 'Menu Button Label', 'wpzabb' ),
						'default'       => __( 'View Full Menu', 'wpzabb' )
					),
					'menu_button_url'        => array(
						'type'          => 'link',
						'label'         => __( 'Menu Button URL', 'wpzabb' ),
						'preview'       => array( 'type' => 'none' ),
						'connections'   => array( 'url' ),
						'show_target'   => true,
						'show_nofollow'	=> true
					)
				)
			)
		)
	),
	'menu_items' => array( // Tab
		'title'    => __( 'Menu Items', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'items'                  => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'menu_items'             => array(
						'type'          => 'form',
						'label'         => __( 'Menu Item', 'wpzabb' ),
						'form'          => 'food_menu_form', // ID from registered form below
						'multiple'      => true,
					)
				)
			)
		)
	),
	'style'      => array( // Tab
		'title'    => __( 'Style', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'style_background'       => array( // Section
				'title'     => __( 'Menu Background', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'background_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap',
							'property'        => 'background-color'
						)
					),
					'outline_color'          => array(
						'type'          => 'color',
						'label'         => __( 'Outline Color', 'wpzabb' ),
						'default'       => 'e9e4e2',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap::before',
							'property'        => 'border-color'
						)
					)
				)
			),
			'style_title'            => array( // Section
				'title'     => __( 'Menu Title', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'title_font'             => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Playfair Display',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '28',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.3',
								'unit'       => 'em'
							),
							'text_align'      => 'center',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'none',
							'text_decoration' => 'none',
							'font_style'      => 'italic',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-title'
						)
					),
					'title_color'            => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => '333333',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-title',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_name'        => array( // Section
				'title'     => __( 'Menu Item Name', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_name_font'         => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Playfair Display',
							'font_weight'     => 700,
							'font_size'       => array(
								'length'     => '20',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.1',
								'unit'       => ''
							),
							'text_align'      => 'left',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'uppercase',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name'
						)
					),
					'item_name_color'        => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => '222222',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name, .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a',
							'property'        => 'color'
						)
					),
					'item_name_hover_color'  => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'c16f2d',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a:hover',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_price'       => array( // Section
				'title'     => __( 'Menu Item Price', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_price_font'        => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'PT Serif',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '18',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.1',
								'unit'       => ''
							),
							'text_align'      => 'right',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'none',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-price'
						)
					),
					'item_price_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => '222222',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-price',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_description' => array( // Section
				'title'     => __( 'Menu Item Description', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_description_font'  => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'PT Serif',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '16',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.6',
								'unit'       => ''
							),
							'text_align'      => 'left',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'none',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-description'
						)
					),
					'item_description_color' => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'a5908d',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-description',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_image' => array( // Section
				'title'     => __( 'Menu Item Image', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_image_align'       => array(
						'type'          => 'button-group',
						'label'         => __( 'Alignment', 'wpzabb' ),
						'default'       => 'left',
						'options'       => array(
							'top'             => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-top.svg" height="20" width="20" /> ' . __( 'Top', 'wpzabb' ),
							'left'            => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-left.svg" height="20" width="20" /> ' . __( 'Left', 'wpzabb' ),
							'right'           => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-right.svg" height="20" width="20" /> ' . __( 'Right', 'wpzabb' ),
							'bottom'          => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-bottom.svg" height="20" width="20" /> ' . __( 'Bottom', 'wpzabb' )
						),
						'toggle'        => array(
							'top'             => array(),
							'left'            => array(
								'fields'     => array( 'item_image_size' )
							),
							'right'           => array(
								'fields'     => array( 'item_image_size' )
							),
							'bottom'          => array()
						),
						'preview'       => array(
							'type'            => 'callback',
							'callback'        => 'setAlignmentClass'
						)
					),
					'item_image_size'        => array(
						'type'          => 'unit',
						'label'         => __( 'Size', 'wpzabb' ),
						'description'   => __( 'Percent of menu item width', 'wpzabb' ),
						'default'       => '20',
						'units'         => array( '%' ),
						'default_unit'  => '%',
						'responsive'    => true,
						'slider'        => array(
							'min'             => 0,
							'max'             => 100,
							'step'            => 1
						),
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-image',
							'property'        => 'flex-basis',
							'unit'            => '%'
						)
					)
				)
			),
			'style_item_separator'   => array( // Section
				'title'     => __( 'Menu Item Separator', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_separator_style'   => array(
						'type'          => 'select',
						'label'         => __( 'Style', 'wpzabb' ),
						'default'       => 'dashed',
						'options'       => array(
							'none'            => __( 'None', 'wpzabb' ),
							'solid'           => __( 'Solid', 'wpzabb' ),
							'dashed'          => __( 'Dashed', 'wpzabb' ),
							'dotted'          => __( 'Dotted', 'wpzabb' ),
							'double'          => __( 'Double', 'wpzabb' )
						),
						'preview'       => array(
							'type'            => 'css',
							'rules'           => array(
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item, .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child',
									'property' => 'border-bottom-style'
								),
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child',
									'property' => 'border-top-style'
								)    
							)
						)
					),
					'item_separator_size'    => array(
						'type'          => 'unit',
						'label'         => __( 'Size', 'wpzabb' ),
						'default'       => '1',
						'units'         => array( 'px', 'vw', '%' ),
						'default_unit'  => 'px',
						'slider'        => array(
							'px'              => array(
								'min'        => 0,
								'max'        => 1000,
								'step'       => 1
							),
							'vw'              => array(
								'min'        => 0,
								'max'        => 100,
								'step'       => 1
							),
							'%'               => array(
								'min'        => 0,
								'max'        => 100,
								'step'       => 1
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'rules'           => array(
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item, .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child',
									'property' => 'border-bottom-width'
								),
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child',
									'property' => 'border-top-width'
								)    
							)
						)
					),
					'item_separator_color'   => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'ecd4c0',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'rules'           => array(
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item, .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child',
									'property' => 'border-bottom-color'
								),
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child',
									'property' => 'border-top-color'
								)    
							)
						)
					)
				)
			),
			'style_button'           => array( // Section
				'title'     => __( 'Menu Button', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'button_background'      => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a',
							'property'        => 'background-color'
						)
					),
					'button_font'            => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Playfair Display',
							'font_weight'     => 700,
							'font_size'       => array(
								'length'     => '16',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1',
								'unit'       => ''
							),
							'text_align'      => 'center',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'uppercase',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a'
						)
					),
					'button_color'           => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'c16f2d',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a',
							'property'        => 'color'
						)
					),
					'button_hover_color'     => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => '000000',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a:hover',
							'property'        => 'color'
						)
					),
					'button_border'          => array(
						'type'          => 'border',
						'label'         => __( 'Border', 'wpzabb' ),
						'default'       => array(
							'style'           => 'solid',
							'color'           => 'c16f2d',
							'width'           => array(
								'top'          => 2,
								'left'         => 2,
								'right'        => 2,
								'bottom'       => 2
							),
							'radius'          => array(
								'top_left'     => 0,
								'top_right'    => 0,
								'bottom_left'  => 0,
								'bottom_right' => 0
							),
							'shadow'          => array(
								'color'        => '',
								'horizontal'   => 0,
								'vertical'     => 0,
								'blur'         => 0,
								'spread'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a'
						)
					)
				)
			)
		)
	)
) );


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'food_menu_form', array(
	'title' => __( 'Add Menu Item', 'wpzabb' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'wpzabb' ), // Tab title
			'sections' => array( // Tab Sections
				'general' => array( // Section
					'title'  => '', // Section Title
					'fields' => array( // Section Fields
						'name'        => array(
							'type'          => 'text',
							'label'         => __( 'Name', 'wpzabb' ),
							'placeholder'   => __( 'Menu item name...', 'wpzabb' ),
							'default'       => '',
							'connections'   => array( 'string', 'html' )
						),
						'link'        => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'price'       => array(
							'type'          => 'unit',
							'label'         => __( 'Price', 'wpzabb' ),
							'placeholder'   => __( '0.00', 'wpzabb' ),
							'default'       => '',
							'units'         => apply_filters( 'wpzabb_food_menu_price_units', array( '$', '¢', '£', '€', '¥' ) ),
							'default_unit'  => '$',
							'connections'   => array( 'number' )
						),
						'description' => array(
							'type'          => 'textarea',
							'label'         => __( 'Description', 'wpzabb' ),
							'placeholder'   => __( 'Menu item description...', 'wpzabb' ),
							'default'       => '',
							'rows'          => 4,
							'connections'   => array( 'string', 'html' )
						),
						'image_source' => array(
							'type'          => 'select',
							'label'         => __( 'Image', 'wpzabb' ),
							'default'       => 'library',
							'options'       => array(
								'library'     => __( 'From Media Library', 'wpzabb' ),
								'url'         => __( 'From URL', 'wpzabb' )
							),
							'toggle'        => array(
								'library'     => array(
									'fields' => array( 'image' )
								),
								'url'         => array(
									'fields' => array( 'image_url' )
								)
							)
						),
						'image'       => array(
							'type'          => 'photo',
							'label'         => '',
							'show_remove'	=> true,
							'connections'   => array( 'photo' )
						),
						'image_url'   => array(
							'type'          => 'link',
							'label'         => '',
							'placeholder'   => 'http://www.example.com/my-image.jpg',
							'preview'       => array( 'type' => 'none' ),
							'connections'	=> array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						)
					)
				)
			)
		)
	)
) );