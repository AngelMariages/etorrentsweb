<?php

/**
 * @class WPZABBTestimonialsModule
 */
class WPZABBTestimonialsModule extends FLBuilderModule {

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
			'name'          	=> __( 'Testimonials', 'wpzabb' ),
			'description'   	=> __( 'An animated tesimonials area.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'testimonials/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'testimonials/',
            'partial_refresh'	=> true,
			'icon' 				=> 'format-quote.svg',
		));
	}


	/**
 	 * @method enqueue_scripts
 	 */
	public function enqueue_scripts() {
		if ( $this->settings && $this->settings->arrows ) {
			$this->add_css( 'font-awesome-5' );
		}
		$this->add_css( 'jquery-bxslider' );
		$this->add_js( 'jquery-bxslider' );
	}


	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings )
	{
		$testimonial = $settings->testimonials[0];

		// Make sure we have a author_avatar_src property.
		if(!isset($settings->author_avatar_src)) {
			$settings->author_avatar_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data($testimonial->author_avatar);

		if($data) {
			$settings->data = $data;
		}

		return $settings;
	}


	/**
	 * @method get_data
	 * @param $testimonial {object}
	 */
	public function get_data( $testimonial )
	{
		if(!$this->data) {

			// Photo source is set to "url".
			if($testimonial->author_avatar_source == 'url') {
				$this->data = new stdClass();

				$this->data->url = $testimonial->author_avatar_url;
				$testimonial->author_avatar_src = $testimonial->author_avatar_url;
			}

			// Photo source is set to "library".
			else if(is_object($testimonial->author_avatar)) {
				$this->data = $testimonial->author_avatar;
			}
			else {
				$this->data = FLBuilderPhoto::get_attachment_data($testimonial->author_avatar);
			}

			// Data object is empty, use the settings cache.
			if(!$this->data && isset($testimonial->data)) {
				$this->data = $testimonial->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $testimonial {object}
	 */
	public function get_classes( $testimonial )
	{
		$classes = array( 'wpzabb-photo-img' );
		
		if ( $testimonial->author_avatar_source == 'library' ) {
			
			if ( ! empty( $testimonial->author_avatar ) ) {
				
				$data = self::get_data( $testimonial );
				
				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {

						foreach ( $data->sizes as $key => $size ) {
							
							if ( $size->url == $testimonial->author_avatar_src ) {
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
	 * @param $testimonial {object}
	 */
	public function get_src( $testimonial )
	{
		$src = $this->_get_uncropped_url( $testimonial );

		return $src;
	}


	/**
	 * @method get_alt
	 * @param $testimonial {object}
	 */
	public function get_alt( $testimonial )
	{
		$photo = $this->get_data( $testimonial );

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
	 * @param $testimonial {object}
	 * @protected
	 */
	protected function _has_source( $testimonial )
	{
		if($testimonial->author_avatar_source == 'url' && !empty($testimonial->author_avatar_url)) {
			return true;
		}
		else if($testimonial->author_avatar_source == 'library' && !empty($testimonial->author_avatar_src)) {
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
		foreach ( $settings->testimonials as $i => $testimonial ) {
			if($this->_has_source( $testimonial ) && $this->_editor === null) {

				$url_path  = $this->_get_uncropped_url( $testimonial );
				$file_path = str_ireplace(home_url(), ABSPATH, $url_path);

				if(file_exists($file_path)) {
					$this->_editor = wp_get_image_editor($file_path);
				}
				else {
					$this->_editor = wp_get_image_editor($url_path);
				}
			}
		}

		return $this->_editor;
	}

	/**
	 * @method _get_uncropped_url
	 * @param $testimonial {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $testimonial )
	{
		if($testimonial->author_avatar_source == 'url') {
			$url = $testimonial->author_avatar_url;
		}
		else if(!empty($testimonial->author_avatar_src)) {
			$url = $testimonial->author_avatar_src;
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
FLBuilder::register_module('WPZABBTestimonialsModule', array(
	'general'      => array( // Tab
		'title'         => __( 'General', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => 'Layout', // Section Title
				'fields'        => array( // Section Fields
					'content_align'       => array(
						'type'          => 'select',
						'label'         => __( 'Content Alignment', 'wpzabb' ),
						'default'       => 'center',
						'options'       => array(
							'left'    	=> __( 'Left', 'wpzabb' ),
							'center'    => __( 'Center', 'wpzabb' ),
							'right'     => __( 'Right', 'wpzabb' ),
						),
					),
					'img_size'     => array(
						'type'          => 'text',
						'label'         => __('Author Image Size', 'wpzabb'),
						'maxlength'     => '5',
						'size'          => '6',
						'description'   => 'px',
						'default'		=> '100'
					),
				),
			),
			'slider'       => array( // Section
				'title'         => __( 'Slider Settings', 'wpzabb' ), // Section Title
				'fields'        => array( // Section Fields
					'order'     => array(
						'type'          => 'select',
						'label'         => __( 'Slides order', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'            => __( 'Default', 'wpzabb' ),
							'random'             => __( 'Random', 'wpzabb' ),
						),
					),
					'auto_play'     => array(
						'type'          => 'select',
						'label'         => __( 'Auto Play', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'0'             => __( 'No', 'wpzabb' ),
							'1'             => __( 'Yes', 'wpzabb' ),
						),
					),
					'pause'         => array(
						'type'          => 'text',
						'label'         => __( 'Delay', 'wpzabb' ),
						'default'       => '4',
						'maxlength'     => '4',
						'size'          => '5',
						'sanitize'		=> 'absint',
						'description'   => _x( 'seconds', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'wpzabb' ),
					),
					'transition'    => array(
						'type'          => 'select',
						'label'         => __( 'Transition', 'wpzabb' ),
						'default'       => 'slide',
						'options'       => array(
							'horizontal'    => _x( 'Slide', 'Transition type.', 'wpzabb' ),
							'fade'          => __( 'Fade', 'wpzabb' ),
						),
					),
					'speed'         => array(
						'type'          => 'text',
						'label'         => __( 'Transition Speed', 'wpzabb' ),
						'default'       => '0.5',
						'maxlength'     => '4',
						'size'          => '5',
						'sanitize'		=> 'floatval',
						'description'   => _x( 'seconds', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'wpzabb' ),
					),
					'direction'   => array(
						'type'          => 'select',
						'label'         => __( 'Transition Direction', 'wpzabb' ),
						'default'       => 'next',
						'options'       => array(
							'next'    		=> __( 'Right To Left', 'wpzabb' ),
							'prev'          => __( 'Left To Right', 'wpzabb' ),
						),
					),
				),
			),
			'arrow_nav'       => array( // Section
				'title'         => __( 'Arrows', 'wpzabb' ),
				'fields'        => array( // Section Fields
					'arrows'       => array(
						'type'          => 'select',
						'label'         => __( 'Show Arrows', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'0'             => __( 'No', 'wpzabb' ),
							'1'             => __( 'Yes', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'         => array(
								'fields'        => array( 'arrow_color' ),
							),
						),
					),
					'arrow_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Arrow Color', 'wpzabb' ),
						'default'       => '999999',
						'show_reset'    => true,
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-testimonials-wrap .fa',
							'property'      => 'color',
						),
					),
				),
			),
			'dot_nav'       => array( // Section
				'title'         => __( 'Dots', 'wpzabb' ), // Section Title
				'fields'        => array( // Section Fields
					'dots'       => array(
						'type'          => 'select',
						'label'         => __( 'Show Dots', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'0'             => __( 'No', 'wpzabb' ),
							'1'             => __( 'Yes', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'         => array(
								'fields'        => array( 'dot_color' ),
							),
						),
					),
					'dot_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Dot Color', 'wpzabb' ),
						'default'       => '999999',
						'show_reset'    => true,
					),
				),
			),
		),
	),
	'testimonials'      => array( // Tab
		'title'         => __( 'Testimonials', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'testimonials'     => array(
						'type'          => 'form',
						'label'         => __( 'Testimonial', 'wpzabb' ),
						'form'          => 'testimonials_form', // ID from registered form below
						'preview_text'  => 'author_name', // Name of a field to use for the preview text
						'multiple'      => true,
					),
				),
			),
		),
	),
));


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form('testimonials_form', array(
	'title' => __( 'Add Testimonial', 'wpzabb' ),
	'tabs'  => array(
		'general'      => array( // Tab
			'title'         => __( 'General', 'wpzabb' ), // Tab title
			'sections'      => array( // Tab Sections
				'general'       => array( // Section
					'title'         => '', // Section Title
					'fields'        => array( // Section Fields
						'testimonial'          => array(
							'type'          => 'editor',
							'label'         => '',
						),
						'author_avatar_source'  => array(
							'type'          => 'select',
							'label'         => __('Avatar Source', 'wpzabb'),
							'default'       => 'library',
							'options'       => array(
								'library'       => __('Media Library', 'wpzabb'),
								'url'           => __('URL', 'wpzabb')
							),
							'toggle'        => array(
								'library'       => array(
									'fields'        => array('author_avatar')
								),
								'url'           => array(
									'fields'        => array('author_avatar_url' )
								)
							)
						),
						'author_avatar'         => array(
							'type'          => 'photo',
							'label'         => __('Avatar', 'wpzabb'),
							'show_remove'	=> true,
							'connections'   => array( 'photo' )
						),
						'author_avatar_url'     => array(
							'type'          => 'text',
							'label'         => __('Avatar URL', 'wpzabb'),
							'placeholder'   => 'http://www.example.com/author-avatar.jpg',
							'connections'	=> array( 'url' )
						),
						'author_name'        => array(
							'type'            => 'text',
							'label'           => __('Name', 'wpzabb'),
							'default'         => __('John Doe', 'wpzabb'),
							'connections'		=> array( 'string', 'html' )
						),
						'author_link'          => array(
							'type'          => 'link',
							'label'         => __('Link', 'wpzabb'),
							'preview'         => array(
								'type'            => 'none'
							),
							'connections'		=> array( 'url' )
						),
						'author_link_target'   => array(
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
						'author_company'        => array(
							'type'            => 'text',
							'label'           => __('Company', 'wpzabb'),
							'default'         => '',
							'connections'		=> array( 'string', 'html' )
						),
						'author_company_link'          => array(
							'type'          => 'link',
							'label'         => __('Company Link', 'wpzabb'),
							'preview'         => array(
								'type'            => 'none'
							),
							'connections'		=> array( 'url' )
						),
						'author_position'        => array(
							'type'            => 'text',
							'label'           => __('Position', 'wpzabb'),
							'default'         => '',
							'connections'		=> array( 'string', 'html' )
						),
					),
				),
			),
		),
	),
));
