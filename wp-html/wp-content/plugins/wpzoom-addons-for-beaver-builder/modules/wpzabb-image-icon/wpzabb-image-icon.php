<?php

/**
 * @class WPZABBImageIconModule
 */
class WPZABBImageIconModule extends FLBuilderModule {

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
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __('Image / Icon', 'wpzabb'),
			'description'   => __('Image / Icon with effect', 'wpzabb'),
			'category'      => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'image-icon/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'image-icon/',
            'icon'				=> 'format-image.svg',
		));

		$this->add_css( 'font-awesome' );
	}


	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update($settings)
	{
		// Make sure we have a photo_src property.
		if(!isset($settings->photo_src)) {
			$settings->photo_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data($settings->photo);

		if($data) {
			$settings->data = $data;
		}

		// Save a crop if necessary.
		$this->crop();

		return $settings;
	}

	/**
	 * @method delete
	 */
	public function delete()
	{
		$cropped_path = $this->_get_cropped_path();

		if(file_exists($cropped_path['path'])) {
			unlink($cropped_path['path']);
		}
	}

	/**
	 * @method crop
	 */
	public function crop()
	{
		// Delete an existing crop if it exists.
		$this->delete();

		// Do a crop.
		if(!empty($this->settings->image_style) && $this->settings->image_style != "simple" && $this->settings->image_style != "custom" ) {

			$editor = $this->_get_editor();

			if(!$editor || is_wp_error($editor)) {
				return false;
			}

			$cropped_path = $this->_get_cropped_path();
			$size         = $editor->get_size();
			$new_width    = $size['width'];
			$new_height   = $size['height'];

			// Get the crop ratios.
			
			
			if($this->settings->image_style == 'circle') {
				$ratio_1 = 1;
				$ratio_2 = 1;
			}
			elseif($this->settings->image_style == 'square') {
				$ratio_1 = 1;
				$ratio_2 = 1;
			}

			// Get the new width or height.
			if($size['width'] / $size['height'] < $ratio_1) {
				$new_height = $size['width'] * $ratio_2;
			}
			else {
				$new_width = $size['height'] * $ratio_1;
			}

			// Make sure we have enough memory to crop.
			@ini_set('memory_limit', '300M');

			// Crop the photo.
			$editor->resize($new_width, $new_height, true);

			// Save the photo.
			$editor->save($cropped_path['path']);

			// Return the new url.
			return $cropped_path['url'];
		}

		return false;
	}

	/**
	 * @method get_data
	 */
	public function get_data()
	{
		if(!$this->data) {

			// Photo source is set to "url".
			if($this->settings->photo_source == 'url') {
				$this->data = new stdClass();
				//$this->data->alt = $this->settings->caption;
				//$this->data->caption = $this->settings->caption;
				//$this->data->link = $this->settings->photo_url;
				$this->data->url = $this->settings->photo_url;
				$this->settings->photo_src = $this->settings->photo_url;
			}

			// Photo source is set to "library".
			else if(is_object($this->settings->photo)) {
				$this->data = $this->settings->photo;
			}
			else {
				$this->data = FLBuilderPhoto::get_attachment_data($this->settings->photo);
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
		
		if ( $this->settings->photo_source == 'library' ) {
			
			if ( ! empty( $this->settings->photo ) ) {
				
				$data = self::get_data();
				
				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {

						foreach ( $data->sizes as $key => $size ) {
							
							if ( $size->url == $this->settings->photo_src ) {
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

		// Return a cropped photo.
		if($this->_has_source() && !empty($this->settings->image_style)) {

			$cropped_path = $this->_get_cropped_path();

			// See if the cropped photo already exists.
			if(file_exists($cropped_path['path'])) {
				$src = $cropped_path['url'];
			}
			// It doesn't, check if this is a demo image.
			elseif(stristr($src, FL_BUILDER_DEMO_URL) && !stristr(FL_BUILDER_DEMO_URL, $_SERVER['HTTP_HOST'])) {
				$src = $this->_get_cropped_demo_url();
			}
			// It doesn't, check if this is a OLD demo image.
			elseif(stristr($src, FL_BUILDER_OLD_DEMO_URL)) {
				$src = $this->_get_cropped_demo_url();
			}
			// A cropped photo doesn't exist, try to create one.
			else {

				$url = $this->crop();

				if($url) {
					$src = $url;
				}
			}
		}

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
	 * @method get_attributes
	 */
	/*public function get_attributes()
	{
		$attrs = '';
		
		if ( isset( $this->settings->attributes ) ) {
			foreach ( $this->settings->attributes as $key => $val ) {
				$attrs .= $key . '="' . $val . '" ';
			}
		}
		
		return $attrs;
	}*/

	/**
	 * @method _has_source
	 * @protected
	 */
	protected function _has_source()
	{
		if($this->settings->photo_source == 'url' && !empty($this->settings->photo_url)) {
			return true;
		}
		else if($this->settings->photo_source == 'library' && !empty($this->settings->photo_src)) {
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
	 * @method _get_cropped_path
	 * @protected
	 */
	protected function _get_cropped_path()
	{
		$crop        = empty($this->settings->image_style) ? 'simple' : $this->settings->image_style;
		$url         = $this->_get_uncropped_url();
		$cache_dir   = FLBuilderModel::get_cache_dir();

		if(empty($url)) {
			$filename    = uniqid(); // Return a file that doesn't exist.
		}
		else {
			
			if ( stristr( $url, '?' ) ) {
				$parts = explode( '?', $url );
				$url   = $parts[0];
			}
			
			$pathinfo    = pathinfo($url);
			$dir         = $pathinfo['dirname'];
			$ext         = $pathinfo['extension'];
			$name        = wp_basename($url, ".$ext");
			$new_ext     = strtolower($ext);
			$filename    = "{$name}-{$crop}.{$new_ext}";
		}

		return array(
			'filename' => $filename,
			'path'     => $cache_dir['path'] . $filename,
			'url'      => $cache_dir['url'] . $filename
		);
	}

	/**
	 * @method _get_uncropped_url
	 * @protected
	 */
	protected function _get_uncropped_url()
	{
		if($this->settings->photo_source == 'url') {
			$url = $this->settings->photo_url;
		}
		else if(!empty($this->settings->photo_src)) {
			$url = $this->settings->photo_src;
		}
		else {
			$url = '';
		}

		return $url;
	}

	/**
	 * @method _get_cropped_demo_url
	 * @protected
	 */
	protected function _get_cropped_demo_url()
	{
		$info = $this->_get_cropped_path();

		return FL_BUILDER_DEMO_CACHE_URL . $info['filename'];
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBImageIconModule', array(
	'general'       => array( // Tab
		'title'         => __('General', 'wpzabb'), // Tab title
		'sections'      => array( // Tab Sections
			'type_general'		=> array( // Section
				'title'         => __('Image / Icon','wpzabb'), // Section Title
				'fields'        => array( // Section Fields
					'image_type'    => array(
						'type'          => 'select',
						'label'         => __('Image Type', 'wpzabb'),
						'default'       => 'icon',
						'options'       => array(
							'icon'          => __('Icon', 'wpzabb'),
							'photo'         => __('Photo', 'wpzabb'),
						),
						'toggle'        => array(
							'icon'          => array(
								'sections'	 => array( 'icon_basic',  'icon_style', 'icon_colors' ),
							),
							'photo'         => array(
								'sections'	 => array( 'img_basic', 'img_style' ),
							)
						),
					),
				)
			),

			/* Icon Basic Setting */
			'icon_basic'		=> array( // Section
				'title'         => __('Icon Basics','wpzabb'), // Section Title
				'fields'        => array( // Section Fields
					'icon'          => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'wpzabb'),
						'default'		=> 'ua-icon ua-icon-mail2',
						'show_remove'   => true
					),
					'icon_size'     => array(
						'type'          => 'text',
						'label'         => __('Size', 'wpzabb'),
						'placeholder'   => '30',
						'maxlength'     => '5',
						'size'          => '6',
						'description'   => 'px',
					),
					'icon_align'         => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'left'          => __('Left', 'wpzabb'),
							'center'        => __('Center', 'wpzabb'),
							'right'         => __('Right', 'wpzabb')
						),
					)
				)
			),
			/* Image Basic Setting */
			'img_basic'		=> array( // Section
				'title'         => __('Image Basics','wpzabb'), // Section Title
				'fields'        => array( // Section Fields
					'photo_source'  => array(
						'type'          => 'select',
						'label'         => __('Photo Source', 'wpzabb'),
						'default'       => 'library',
						'options'       => array(
							'library'       => __('Media Library', 'wpzabb'),
							'url'           => __('URL', 'wpzabb')
						),
						'toggle'        => array(
							'library'       => array(
								'fields'        => array('photo')
							),
							'url'           => array(
								'fields'        => array('photo_url' )
							)
						)
					),
					'photo'         => array(
						'type'          => 'photo',
						'label'         => __('Photo', 'wpzabb'),
						'show_remove'	=> true,
						'connections'   => array( 'photo' )
					),
					'photo_url'     => array(
						'type'          => 'text',
						'label'         => __('Photo URL', 'wpzabb'),
						'placeholder'   => 'http://www.example.com/my-photo.jpg',
						'connections'	=> array( 'url' )
					),
					'img_size'     => array(
						'type'          => 'text',
						'label'         => __('Size', 'wpzabb'),
						'placeholder'   => 'auto',
						'maxlength'     => '5',
						'size'          => '6',
						'description'   => 'px',
						'default'		=> '400'
					),
					'img_align'         => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'left'          => __('Left', 'wpzabb'),
							'center'        => __('Center', 'wpzabb'),
							'right'         => __('Right', 'wpzabb')
						),
					)
				)
			),

			/* Icon Style Section */
			'icon_style'			=> array(
				'title'			=> 'Style',
				'fields'		=> array(
					/* Icon Style */
					'icon_style'         => array(
                    	'type'          => 'select',
                    	'label'         => __('Icon Background Style', 'wpzabb'),
                    	'default'       => 'simple',
                    	'options'       => array(
                        	'simple'        => __('Simple', 'wpzabb'),
                        	'circle'          => __('Circle Background', 'wpzabb'),
                        	'square'         => __('Square Background', 'wpzabb'),
                        	'custom'         => __('Design your own', 'wpzabb'),
                    	),
                        'toggle' => array(
                            'simple' => array(
                                'fields' => array(),
                                /*'sections' => array( 'colors' )*/
                            ),
                            'circle' => array(
                                /*'sections' => array( 'colors' ),*/
                                'fields' => array( 'icon_color_preset', 'icon_bg_color', 'icon_bg_color_opc', 'icon_bg_hover_color', 'icon_bg_hover_color_opc', 'icon_three_d' ),
                            ),
                            'square' => array(
                                /*'sections' => array( 'colors' ),*/
                                'fields' => array( 'icon_color_preset', 'icon_bg_color', 'icon_bg_color_opc', 'icon_bg_hover_color', 'icon_bg_hover_color_opc', 'icon_three_d' ),
                            ),
                            'custom' => array(
                                /*'sections' => array( 'colors' ),*/
                                'fields' => array( 'icon_color_preset', 'icon_border_style', 'icon_bg_color', 'icon_bg_color_opc', 'icon_bg_hover_color', 'icon_bg_hover_color_opc', 'icon_three_d', 'icon_bg_size', 'icon_bg_border_radius' ),
                            )
                        ),
						'trigger' => array(
							'custom' => array(
								'fields' => array( 'icon_border_style' ),
							)
						),
                	),
					
					/* Icon Background SIze */
					'icon_bg_size'          => array(
                        'type'          => 'text',
                        'label'         => __('Background Size', 'wpzabb'),
                        'help'          => __('Spacing between Icon & Background edge','wpzabb'),
                        'maxlength'     => '3',
                        'size'          => '6',
                        'description'   => 'px',
                        'placeholder'	=> '30',
                    ),

                    /* Border Style and Radius for Icon */
					'icon_border_style'   => array(
		                'type'          => 'select',
		                'label'         => __('Border Style', 'wpzabb'),
		                'default'       => 'solid',
		                'help'          => __('The type of border to use. Double borders must have a width of at least 3px to render properly.', 'wpzabb'),
		                'options'       => array(
		                    'none'   => __( 'None', 'Border type.', 'wpzabb' ),
		                    'solid'  => __( 'Solid', 'Border type.', 'wpzabb' ),
		                    'dashed' => __( 'Dashed', 'Border type.', 'wpzabb' ),
		                    'dotted' => __( 'Dotted', 'Border type.', 'wpzabb' ),
		                    'double' => __( 'Double', 'Border type.', 'wpzabb' )
		                ),
		                'toggle'        => array(
		                    'solid'         => array(
		                        'fields'        => array('icon_border_width', 'icon_border_color','icon_border_hover_color' )
		                    ),
		                    'dashed'        => array(
		                        'fields'        => array('icon_border_width', 'icon_border_color','icon_border_hover_color' )
		                    ),
		                    'dotted'        => array(
		                        'fields'        => array('icon_border_width', 'icon_border_color','icon_border_hover_color' )
		                    ),
		                    'double'        => array(
		                        'fields'        => array('icon_border_width', 'icon_border_color','icon_border_hover_color' )
		                    )
		                ),
		                /*'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-icon i',
                        		'property'	=> 'border-style',
                        )*/
		            ),
		            'icon_border_width'    => array(
		                'type'          => 'text',
		                'label'         => __('Border Width', 'wpzabb'),
		                'default'       => '',
		                'description'   => 'px',
		                'maxlength'     => '3',
		                'size'          => '6',
		                'placeholder'   => '1',
		                'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-icon i',
                        		'property'	=> 'border-width',
                        		'unit'		=> 'px'
                        )
		            ),
		            'icon_bg_border_radius'    => array(
		                'type'          => 'text',
		                'label'         => __('Border Radius', 'wpzabb'),
		                'default'		=> '',
		                'description'   => 'px',
		                'maxlength'     => '3',
		                'size'          => '6',
		                'placeholder'   => '20',
		                'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-icon i',
                        		'property'	=> 'border-radius',
                        		'unit'		=> 'px'
                        )
		            ),
				)
			), 

			/* Image Style Section */
			'img_style'			=> array(
				'title'			=> __('Style','wpzabb'),
				'fields'		=> array(
					/* Image Style */
					'image_style'         => array(
                    	'type'          => 'select',
                    	'label'         => __('Image Style', 'wpzabb'),
                    	'default'       => 'simple',
                    	'help'			=> __('Circle and Square style will crop your image in 1:1 ratio','wpzabb'),
                    	'options'       => array(
                        	'simple'        => __('Simple', 'wpzabb'),
                        	'circle'        => __('Circle', 'wpzabb'),
                        	'square'        => __('Square', 'wpzabb'),
                        	'custom'        => __('Design your own', 'wpzabb'),
                    	),
                        'toggle' => array(
                            'simple' => array(
                                'fields' => array()
                            ),
                            'circle' => array(
                                'fields' => array( ),
                            ),
                            'square' => array(
                                'fields' => array( ),
                            ),
                            'custom' => array(
                                'sections'  => array( 'img_colors' ),
                                'fields'	=> array( 'img_bg_size', 'img_border_style', 'img_border_width', 'img_bg_border_radius' ) 
                            )
                        ),
                        'trigger'       => array(
							'custom'           => array(
								'fields'        => array('img_border_style')
							),
							
						)
                	),

                    /* Image Background Size */
                    'img_bg_size'          => array(
                        'type'          => 'text',
                        'label'         => __('Background Size', 'wpzabb'),
                        'default'       => '',
                        'help'          => __('Spacing between Image edge & Background edge','wpzabb'),
                        'maxlength'     => '3',
                        'size'          => '6',
                        'description'   => 'px',
                        'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-image .wpzabb-photo-img',
                        		'property'	=> 'padding',
                        		'unit'		=> 'px'
                        )
                    ),

                    /* Border Style and Radius for Image */
					'img_border_style'   => array(
		                'type'          => 'select',
		                'label'         => __('Border Style', 'wpzabb'),
		                'default'       => 'none',
		                'help'          => __('The type of border to use. Double borders must have a width of at least 3px to render properly.', 'wpzabb'),
		                'options'       => array(
		                    'none'   => __( 'None', 'Border type.', 'wpzabb' ),
		                    'solid'  => __( 'Solid', 'Border type.', 'wpzabb' ),
		                    'dashed' => __( 'Dashed', 'Border type.', 'wpzabb' ),
		                    'dotted' => __( 'Dotted', 'Border type.', 'wpzabb' ),
		                    'double' => __( 'Double', 'Border type.', 'wpzabb' )
		                ),
		                'toggle'        => array(
		                    'solid'         => array(
		                        'fields'        => array('img_border_width', 'img_border_radius','img_border_color','img_border_hover_color' )
		                    ),
		                    'dashed'        => array(
		                        'fields'        => array('img_border_width', 'img_border_radius','img_border_color','img_border_hover_color' )
		                    ),
		                    'dotted'        => array(
		                        'fields'        => array('img_border_width', 'img_border_radius','img_border_color','img_border_hover_color' )
		                    ),
		                    'double'        => array(
		                        'fields'        => array('img_border_width', 'img_border_radius','img_border_color','img_border_hover_color' )
		                    )
		                ),
		                /*'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-image .wpzabb-photo-img',
                        		'property'	=> 'border-style',
                        )*/
		            ),
		            'img_border_width'    => array(
		                'type'          => 'text',
		                'label'         => __('Border Width', 'wpzabb'),
		                'description'   => 'px',
		                'maxlength'     => '3',
		                'size'          => '6',
		                'placeholder'   => '1',
		                'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-image .wpzabb-photo-img',
                        		'property'	=> 'border-width',
                        		'unit'		=> 'px'
                        )
		            ),
		            'img_bg_border_radius'    => array(
		                'type'          => 'text',
		                'label'         => __('Border Radius', 'wpzabb'),
		                'description'   => 'px',
		                'maxlength'     => '3',
		                'size'          => '6',
		                'placeholder'   => '0',
		                /*'preview'		=> array(
                        		'type' 		=> 'css',
                        		'selector'	=> '.wpzabb-image .wpzabb-image-content',
                        		'property'	=> 'border-radius',
                        		'unit'		=> 'px'
                        )*/
		            ),
				)
			),
			/* Icon Colors */
			'icon_colors'        => array( // Section
                'title'         => __('Colors', 'wpzabb'), // Section Title
                'fields'        => array( // Section Fields
                    
                    /* Style Options */
                    'icon_color_preset'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Icon Color Presets', 'wpzabb' ),
                        'default'       => 'preset1',
                        'options'       => array(
                         	'preset1'		=> __('Preset 1','wpzabb'),
                          	'preset2'		=> __('Preset 2','wpzabb'),
                          	/*'preset3'		=> 'Preset 3',*/
                        ),
                        'help'			=> __('Preset 1 => Icon : White, Background : Theme </br>Preset 2 => Icon : Theme, Background : #f3f3f3', 'wpzabb')
                    ),
                    /* Icon Color */
                    'icon_color' => array( 
						'type'       => 'color',
		                'label'     => __('Icon Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
		            'icon_hover_color' => array( 
						'type'       => 'color',
	                    'label'         => __('Icon Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
	                    'preview'       => array(
	                            'type'      => 'none',
	                    )
					),

                    /* Background Color Dependent on Icon Style **/
                    'icon_bg_color' => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
		            'icon_bg_color_opc' => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
		            'icon_bg_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Background Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
	                    'preview'       => array(
	                            'type'      => 'none',
	                    )
					),
		            'icon_bg_hover_color_opc' => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),

                     /* Border Color Dependent on Border Style for ICon */
                    'icon_border_color' => array( 
						'type'       => 'color',
						'label'         => __('Border Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
		            'icon_border_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Border Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),

                    /* Gradient Color Option */
                    'icon_three_d'       => array(
                        'type'          => 'select',
                        'label'         => __('Gradient', 'wpzabb'),
                        'default'       => '0',
                        'options'       => array(
                            '0'             => __('No', 'wpzabb'),
                            '1'             => __('Yes', 'wpzabb')
                        )
                    ),
                )
            ),

			/* Image Colors */
			'img_colors'        => array( // Section
                'title'         => __('Colors', 'wpzabb'), // Section Title
                'fields'        => array( // Section Fields
                    /* Background Color Dependent on Icon Style **/
                    'img_bg_color' => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
					'img_bg_color_opc' => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
		            'img_bg_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Background Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
	                    'preview'       => array(
	                            'type'      => 'none',
	                    )
					),
					'img_bg_hover_color_opc' => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),

                     /* Border Color Dependent on Border Style for Image */
                    'img_border_color' => array( 
						'type'       => 'color',
						'label'         => __('Border Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
		            'img_border_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Border Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
                )
            ),

		)
	),
));