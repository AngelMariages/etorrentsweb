<?php

/**
 * @class WPZABBImageBoxModule
 */
class WPZABBImageBoxModule extends FLBuilderModule {

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
		parent::__construct(array(
			'name'          	=> __( 'Image Box', 'wpzabb' ),
			'description'   	=> __( 'Add an image with some text over.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'image-box/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'image-box/',
            'partial_refresh'	=> true,
            'icon'				=> 'format-gallery.svg',
		));
	}

    /**
	 * @method render_button
	 */
	public function render_button()
	{
		$btn_settings = array(
			'text'             			=> $this->settings->btn_text,
			'link'             			=> $this->settings->link,
			'link_target'             	=> $this->settings->link_target,
			'align'             		=> $this->settings->btn_align,
			'mob_align'             	=> $this->settings->btn_mob_align,
			'border_radius'             => $this->settings->btn_border_radius,
			'width' 					=> $this->settings->btn_width,
			'custom_width' 				=> $this->settings->btn_custom_width,
			'custom_height' 			=> $this->settings->btn_custom_height,
			'padding_top_bottom' 		=> $this->settings->btn_padding_top_bottom,
			'padding_left_right' 		=> $this->settings->btn_padding_left_right,
			'font_family'       		=> $this->settings->btn_font_family,
			'font_size_unit'   			=> $this->settings->btn_font_size_unit,
			'font_size_unit_medium' 	=> $this->settings->btn_font_size_unit_medium,
			'font_size_unit_responsive' => $this->settings->btn_font_size_unit_responsive,
			'line_height_unit' 			=> $this->settings->btn_line_height_unit,
			'line_height_unit_medium' 	=> $this->settings->btn_line_height_unit_medium,
			'line_height_unit_responsive' => $this->settings->btn_line_height_unit_responsive,
			'text_transform' 			=> $this->settings->btn_text_transform,
			'letter_spacing' 			=> $this->settings->btn_custom_letter_spacing,
			'letter_spacing_medium' 	=> $this->settings->btn_custom_letter_spacing_medium,
			'letter_spacing_responsive' => $this->settings->btn_custom_letter_spacing_responsive,
			'style' 					=> $this->settings->btn_style,
			'border_size' 				=> $this->settings->btn_border_size,
			'flat_options' 				=> $this->settings->btn_flat_options,
			'icon' 						=> $this->settings->btn_icon,
			'icon_position' 			=> $this->settings->btn_icon_position,
			'text_color' 				=> $this->settings->btn_text_color,
			'text_hover_color' 			=> $this->settings->btn_text_hover_color,
			'bg_color' 					=> $this->settings->btn_bg_color,
			'bg_color_opc' 				=> $this->settings->btn_bg_color_opc,
			'bg_hover_color' 			=> $this->settings->btn_bg_hover_color,
			'bg_hover_color_opc' 		=> $this->settings->btn_bg_hover_color_opc,
			'hover_attribute' 			=> $this->settings->btn_hover_attribute,
		);

		/* Render HTML Function */
		echo '<div class="wpzabb-image-button">';
		FLBuilder::render_module_html( 'wpzabb-button', $btn_settings );
		echo '</div>';
	}


	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings )
	{
		// Make sure we have a image_src property.
		if(!isset($settings->image_src)) {
			$settings->image_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data($settings->image);

		if($data) {
			$settings->data = $data;
		}

		return $settings;
	}

	/**
	 * @method get_data
	 */
	public function get_data()
	{
		if(!$this->data) {

			// Photo source is set to "url".
			if($this->settings->image_source == 'url') {
				$this->data = new stdClass();

				$this->data->url = $this->settings->image_url;
				$this->settings->image_src = $this->settings->image_url;
			}

			// Photo source is set to "library".
			else if(is_object($this->settings->image)) {
				$this->data = $this->settings->image;
			}
			else {
				$this->data = FLBuilderPhoto::get_attachment_data($this->settings->image);
			}

			// Data object is empty, use the settings cache.
			if(!$this->data && isset($this->settings->data)) {
				$this->data = $this->settings->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 */
	public function get_classes()
	{
		$classes = array( 'wpzabb-photo-img' );
		
		if ( $this->settings->image_source == 'library' ) {
			
			if ( ! empty( $this->settings->image ) ) {
				
				$data = self::get_data();
				
				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {

						foreach ( $data->sizes as $key => $size ) {
							
							if ( $size->url == $this->settings->image_src ) {
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
	 */
	public function get_src()
	{
		$src = $this->_get_uncropped_url();

		return $src;
	}


	/**
	 * @method get_alt
	 */
	public function get_alt()
	{
		$photo = $this->get_data();

		if(!empty($photo->alt)) {
			return htmlspecialchars($photo->alt);
		}
		else if(!empty($photo->description)) {
			return htmlspecialchars($photo->description);
		}
		else if(!empty($photo->caption)) {
			return htmlspecialchars($photo->caption);
		}
		else if(!empty($photo->title)) {
			return htmlspecialchars($photo->title);
		}
	}


	/**
	 * @method _has_source
	 * @protected
	 */
	protected function _has_source()
	{
		if($this->settings->image_source == 'url' && !empty($this->settings->image_url)) {
			return true;
		}
		else if($this->settings->image_source == 'library' && !empty($this->settings->image_src)) {
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
		if($this->_has_source() && $this->_editor === null) {

			$url_path  = $this->_get_uncropped_url();
			$file_path = str_ireplace(home_url(), ABSPATH, $url_path);

			if(file_exists($file_path)) {
				$this->_editor = wp_get_image_editor($file_path);
			}
			else {
				$this->_editor = wp_get_image_editor($url_path);
			}
		}

		return $this->_editor;
	}

	/**
	 * @method _get_uncropped_url
	 * @protected
	 */
	protected function _get_uncropped_url()
	{
		if($this->settings->image_source == 'url') {
			$url = $this->settings->image_url;
		}
		else if(!empty($this->settings->image_src)) {
			$url = $this->settings->image_src;
		}
		else {
			$url = '';
		}

		return $url;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBImageBoxModule', array(
	'general'      => array( // Tab
		'title'         => __( 'General', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'image_source'  => array(
						'type'          => 'select',
						'label'         => __('Image Source', 'wpzabb'),
						'default'       => 'library',
						'options'       => array(
							'library'       => __('Media Library', 'wpzabb'),
							'url'           => __('URL', 'wpzabb')
						),
						'toggle'        => array(
							'library'       => array(
								'fields'        => array('image')
							),
							'url'           => array(
								'fields'        => array('image_url' )
							)
						)
					),
					'image'         => array(
						'type'          => 'photo',
						'label'         => __('Image', 'wpzabb'),
						'show_remove'	=> true,
						'connections'   => array( 'photo' )
					),
					'image_url'     => array(
						'type'          => 'text',
						'label'         => __('Image URL', 'wpzabb'),
						'placeholder'   => 'http://www.example.com/my-image.jpg',
						'connections'	=> array( 'url' )
					),
					'heading'        => array(
						'type'            => 'text',
						'label'           => __('Heading', 'wpzabb'),
						'default'         => '',
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.wpzabb-image-box-wrap .wpzabb-image-heading'
						),
						'connections'		=> array( 'string', 'html' )
					),
					'subheading'        => array(
						'type'            => 'text',
						'label'           => __('Subheading', 'wpzabb'),
						'default'         => '',
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.wpzabb-image-box-wrap .wpzabb-image-subheading'
						),
						'connections'		=> array( 'string', 'html' )
					),
					'description'          => array(
						'type'          => 'editor',
						'label'         => __('Description', 'wpzabb'),
					),
					'btn_text'          => array(
						'type'          => 'text',
						'label'         => __('Button Label', 'wpzabb'),
						'default'       => __('Click Here', 'wpzabb'),
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.wpzabb-button-text'
						),
						'connections'	=> array( 'string', 'html' )
					),
					'link'          => array(
						'type'          => 'link',
						'label'         => __('Link', 'wpzabb'),
						'preview'         => array(
							'type'            => 'none'
						),
						'connections'		=> array( 'url' )
					),
					'link_target'   => array(
						'type'          => 'select',
						'label'         => __('Link Target', 'wpzabb'),
						'default'       => '_self',
						'options'       => array(
							'_self'         => __('Same Window', 'wpzabb'),
							'_blank'        => __('New Window', 'wpzabb')
						),
						'preview'         => array(
							'type'            => 'none'
						)
					),
				),
			),
		),
	),
	'typography'         => array(
		'title'         => __('Typography', 'wpzabb'),
		'sections'      => array(
			'heading_typo'     => array(
				'title'         => __('Heading', 'wpzabb'),
				'fields'        => array(
					'tag'           => array(
						'type'          => 'select',
						'label'         => __( 'HTML Tag', 'wpzabb' ),
						'default'       => 'h3',
						'options'       => array(
							'h1'            =>  'h1',
							'h2'            =>  'h2',
							'h3'            =>  'h3',
							'h4'            =>  'h4',
							'h5'            =>  'h5',
							'h6'            =>  'h6'
						)
					),
					'font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'wpzabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-image-box-wrap .wpzabb-image-heading'
						)
					),
					'new_font_size_unit'     => array(
						'type'          => 'unit',
						'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-image-box-wrap .wpzabb-image-heading',
                            'property'      => 'font-size',
                            'unit'			=> 'px'
                        ),
                        'responsive' => array(
							'placeholder' => array(
								'default' => '26',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'line_height_unit'    => array(
						'type'          => 'unit',
						'label'         => __( 'Line Height', 'wpzabb' ),
						'description'   => 'em',
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-image-box-wrap .wpzabb-image-heading',
                            'property'      => 'line-height',
                            'unit'			=> 'em'
                        ),
                        'responsive' => array(
							'placeholder' => array(
								'default' => '1.1',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'none',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
						'preview'      => array(
							'type'         => 'css',
							'selector'     => '.wpzabb-image-box-wrap .wpzabb-image-heading',
							'property'     => 'text-transform',
						),
					),
					'letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'custom_letter_spacing' ),
							),
						),
					),
		            'custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-image-box-wrap .wpzabb-image-heading',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
					'color'    => array( 
						'type'       => 'color',
						'label'         => __('Text Color', 'wpzabb'),
						'default'    => '#ffffff',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-heading, .fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-heading *'
						)
					),
					'heading_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '20',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-heading',
							'unit'		=> 'px',
						)
					),
					'heading_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '10',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-heading',
							'unit'		=> 'px',
						)
					),
				)
			),
			'subheading_typo'    =>  array(
		        'title'		=> __('Subheading', 'wpzabb'),
		        'fields'    => array(
		            'subheading_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-image-box-wrap .wpzabb-image-subheading'
						)
		            ),
		            'subheading_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.wpzabb-image-box-wrap .wpzabb-image-subheading',
							'unit'		=> 'px',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '14',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
		            'subheading_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.wpzabb-image-box-wrap .wpzabb-image-subheading',
							'unit'		=> 'em',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '1.8',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
					'subheading_text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'uppercase',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
						'preview'      => array(
							'type'         => 'css',
							'selector'     => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-subheading',
							'property'     => 'text-transform',
						),
					),
					'subheading_letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'subheading_custom_letter_spacing' ),
							),
						),
					),
		            'subheading_custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-subheading',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		            'subheading_color'        => array( 
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '#ededed',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-subheading'
						)
					),
					'subheading_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-image-box-wrap .wpzabb-image-subheading',
							'unit'	=> 'px',
						)
					),
					'subheading_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '10',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-bottom',
							'selector' => '.wpzabb-image-box-wrap .wpzabb-image-subheading',
							'unit'		=> 'px',
						)
					),
		        )
		    ),
			'desc_typo'    =>  array(
		        'title'		=> __('Description', 'wpzabb'),
		        'fields'    => array(
		            'desc_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-image-box-wrap .wpzabb-image-description'
						)
		            ),
		            'desc_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.wpzabb-image-box-wrap .wpzabb-image-description',
							'unit'		=> 'px',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '16',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
		            'desc_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.wpzabb-image-box-wrap .wpzabb-image-description',
							'unit'		=> 'em',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '1.8',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
					'desc_text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'none',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
						'preview'      => array(
							'type'         => 'css',
							'selector'     => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-description',
							'property'     => 'text-transform',
						),
					),
					'desc_letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'desc_custom_letter_spacing' ),
							),
						),
					),
		            'desc_custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-description',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		            'desc_color'        => array( 
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '#ffffff',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-image-box-wrap .wpzabb-image-description'
						)
					),
					'desc_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-image-box-wrap .wpzabb-image-description, .wpzabb-image-box-wrap .wpzabb-image-description p',
							'unit'	=> 'px',
						)
					),
					'desc_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '20',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-bottom',
							'selector' => '.wpzabb-image-box-wrap .wpzabb-image-description, .wpzabb-image-box-wrap .wpzabb-image-description p',
							'unit'		=> 'px',
						)
					),
		        )
		    ),
			'btn_typo'    =>  array(
                'title'     => __('Button', 'wpzabb' ) ,
		        'fields'    => array(
		            'btn_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
                        'preview'         => array(
                            'type'            => 'font',
                            'selector'        => '.wpzabb-button'
                        )
		            ),
		            'btn_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
		                'description'   => 'px',
		                'responsive' => array(
                            'placeholder' => array(
                                'default' => '14',
                                'medium' => '',
                                'responsive' => '',
                            ),
                        ),
		                'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.wpzabb-button',
                            'property'		=>	'font-size',
                            'unit'			=> 'px'
                        )
		            ),
		            'btn_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'responsive' => array(
                            'placeholder' => array(
                                'default' => '1.4',
                                'medium' => '',
                                'responsive' => '',
                            ),
                        ),
		                'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.wpzabb-button',
                            'property'		=>	'line-height',
                            'unit'			=> 'em'
                        )
		            ),
		            'btn_text_transform' => array(
		            	'type'          => 'select',
		            	'label'         => __( 'Text Transform', 'wpzabb' ),
		            	'default'       => 'uppercase',
		            	'options'       => array(
		            		'none'			=> __( 'None', 'wpzabb' ),
		            		'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
		            		'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
		            		'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
		            	),
		            	'preview'      => array(
		            		'type'         => 'css',
		            		'selector'     => '.wpzabb-button',
		            		'property'     => 'text-transform',
		            	),
		            ),
		            'btn_letter_spacing'     => array(
		            	'type'          => 'select',
		            	'label'         => __( 'Letter Spacing', 'wpzabb' ),
		            	'default'       => 'default',
		            	'options'       => array(
		            		'default'       => __( 'Default', 'wpzabb' ),
		            		'custom'        => __( 'Custom', 'wpzabb' ),
		            	),
		            	'toggle'        => array(
		            		'custom'        => array(
		            			'fields'        => array( 'btn_custom_letter_spacing' ),
		            		),
		            	),
		            ),
		            'btn_custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-button',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		        )
		    ),
		)
	),
	'style'         => array(
		'title'         => __('Style', 'wpzabb'),
		'sections'      => array(
			'general'         => array(
				'title'         => __('General', 'wpzabb'),
				'fields'        => array(
					'bg_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Background Overlay Color', 'wpzabb'),
						'default'    => '#000000',
						'show_reset' => true,
					),
                    'bg_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '30',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'bg_hover_color'        => array( 
						'type'       => 'color',
                        'label'      => __('Background Overlay Hover Color', 'wpzabb'),
						'default'    => '#000000',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
                    'bg_hover_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '40',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
				)
			),
			'style'         => array(
				'title'         => __('Button Style', 'wpzabb'),
				'fields'        => array(
					'btn_style'         => array(
						'type'          => 'select',
						'label'         => __('Style', 'wpzabb'),
						'default'       => 'flat',
						'class'			=> 'creative_button_styles',
						'options'       => array(
							'flat'          => __('Flat', 'wpzabb'),
							'transparent'   => __('Transparent', 'wpzabb'),
						),
					),
					'btn_border_size'   => array(
						'type'          => 'text',
						'label'         => __('Border Size', 'wpzabb'),
						'description'   => 'px',
						'maxlength'     => '3',
						'size'          => '5',
						'placeholder'   => '2'
					),
					'btn_flat_options'         => array(
						'type'          => 'select',
						'label'         => __('Hover Styles', 'wpzabb'),
						'default'       => 'none',
						'options'       => array(
							'none'          => __('None', 'wpzabb'),
							'animate_to_left'      => __('Appear Icon From Right', 'wpzabb'),
							'animate_to_right'          => __('Appear Icon From Left', 'wpzabb'),
							'animate_from_top'      => __('Appear Icon From Top', 'wpzabb'),
							'animate_from_bottom'     => __('Appear Icon From Bottom', 'wpzabb'),
						),
					),
				)
			),
			'icon'    => array(
				'title'         => __('Icons', 'wpzabb'),
				'fields'        => array(
					'btn_icon'          => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'wpzabb'),
						'show_remove'   => true
					),
					'btn_icon_position' => array(
						'type'          => 'select',
						'label'         => __('Icon Position', 'wpzabb'),
						'default'       => 'after',
						'options'       => array(
							'before'        => __('Before Text', 'wpzabb'),
							'after'         => __('After Text', 'wpzabb')
						)
					)
				)
			),
			'colors'        => array(
				'title'         => __('Colors', 'wpzabb'),
				'fields'        => array(
					'btn_text_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Text Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
					'btn_text_hover_color'   => array( 
						'type'       => 'color',
                        'label'         => __('Text Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
					'btn_bg_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Background Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
                    'btn_bg_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'btn_bg_hover_color'        => array( 
						'type'       => 'color',
                        'label'      => __('Background Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
                    'btn_bg_hover_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
                    'btn_hover_attribute' => array(
                    	'type'          => 'select',
                        'label'         => __( 'Apply Hover Color To', 'wpzabb' ),
                        'default'       => 'bg',
                        'options'       => array(
                            'border'    => __( 'Border', 'wpzabb' ),
                            'bg'        => __( 'Background', 'wpzabb' ),
                        ),
                        'width'	=> '75px'
                    ),

				)
			),
			'formatting'    => array(
				'title'         => __('Structure', 'wpzabb'),
				'fields'        => array(
					'btn_width'         => array(
						'type'          => 'select',
						'label'         => __('Width', 'wpzabb'),
						'default'       => 'auto',
						'options'       => array(
							'auto'          => _x( 'Auto', 'Width.', 'wpzabb' ),
							'full'          => __('Full Width', 'wpzabb'),
							'custom'        => __('Custom', 'wpzabb')
						),
						'toggle'        => array(
							'auto'          => array(
								'fields'        => array('btn_align', 'btn_mob_align' )
							),
							'full'          => array(
								'fields'		=> array()
							),
							'custom'        => array(
								'fields'        => array('btn_align', 'btn_mob_align', 'btn_custom_width', 'btn_custom_height', 'btn_padding_top_bottom', 'btn_padding_left_right' )
							)
						)
					),
					'btn_custom_width'  => array(
						'type'          => 'text',
						'label'         => __('Custom Width', 'wpzabb'),
						'default'   	=> '200',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'btn_custom_height'  => array(
						'type'          => 'text',
						'label'         => __('Custom Height', 'wpzabb'),
						'default'   	=> '45',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'btn_padding_top_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Padding Top/Bottom', 'wpzabb'),
						'placeholder'   => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'btn_padding_left_right'       => array(
						'type'          => 'text',
						'label'         => __('Padding Left/Right', 'wpzabb'),
						'placeholder'   => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'btn_border_radius' => array(
						'type'          => 'text',
						'label'         => __('Round Corners', 'wpzabb'),
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'btn_align'         => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'center'        => __('Center', 'wpzabb'),
							'left'          => __('Left', 'wpzabb'),
							'right'         => __('Right', 'wpzabb')
						)
					),
					'btn_mob_align'         => array(
						'type'          => 'select',
						'label'         => __('Mobile Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'center'        => __('Center', 'wpzabb'),
							'left'          => __('Left', 'wpzabb'),
							'right'         => __('Right', 'wpzabb')
						)
					),
				)
			)
		)
	),
));
