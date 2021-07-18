<?php
/**
 * @class WPZABBImageGridModule
 */
class WPZABBImageGridModule extends FLBuilderModule {
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
			'name'          	=> __( 'Image Grid', 'wpzabb' ),
			'description'   	=> __( 'Displays a selection of linked images in a grid.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/' . WPZABB_PREFIX . 'image-grid/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'image-grid/',
            'partial_refresh'	=> true
		) );

		$this->add_css( 'dashicons' );
		//$this->add_js( 'vimeo-api', 'https://player.vimeo.com/api/player.js' );
		//$this->add_js( 'touch-events',  $this->url . 'js/touchevents.polyfill.js', array( 'jquery' ), '1.0' );
		//$this->add_css( 'jquery-flexslider', $this->url . 'css/jquery.flexslider.css', array( 'dashicons' ), '1.0' );
		//$this->add_js( 'jquery-flexslider', $this->url . 'js/jquery.flexslider.js', array( 'jquery', 'touch-events', 'vimeo-api' ), '1.0' );

		FLBuilderAJAX::add_action( 'wpzabb_slideshow_get_thumb', array( $this, 'ajax_get_thumbnail' ), array( 'post_id', 'source', 'dat', 'element_num' ) );
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		$item = $settings->items[0];

		// Make sure we have a image_src property.
		if ( !isset( $settings->image_src ) ) {
			$settings->image_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data( $item->image );

		if ( $data ) {
			$settings->data = $data;
		}

		return $settings;
	}

	/**
	 * @method get_data
	 * @param $item {object}
	 */
	public function get_data( $item ) {
		if ( !$this->data ) {
			// Photo source is set to "url".
			if ( $item->image_source == 'url' ) {
				$this->data = new stdClass();
				$this->data->url = $item->image_url;
				$item->image_src = $item->image_url;
			} else if ( is_object( $item->image ) ) { // Photo source is set to "library".
				$this->data = $item->image;
			} else {
				$this->data = FLBuilderPhoto::get_attachment_data( $item->image );
			}

			// Data object is empty, use the settings cache.
			if ( !$this->data && isset( $item->data ) ) {
				$this->data = $item->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $item {object}
	 */
	public function get_classes( $item ) {
		$classes = array( 'wpzabb-photo-img' );
		
		if ( $item->image_source == 'library' ) {
			if ( ! empty( $item->image ) ) {
				$data = self::get_data( $item );
				
				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {
						foreach ( $data->sizes as $key => $size ) {
							if ( $size->url == $item->image_src ) {
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
	 * @param $item {object}
	 */
	public function get_src( $item ) {
		$src = $this->_get_uncropped_url( $item );

		return $src;
	}

	/**
	 * @method _has_source
	 * @param $item {object}
	 * @protected
	 */
	protected function _has_source( $item ) {
		if ( $item->image_source == 'url' && !empty( $item->image_url ) ) {
			return true;
		} else if ( $item->image_source == 'library' && !empty( $item->image_src ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor() {
		foreach ( $settings->items as $i => $item ) {
			if ( $this->_has_source( $item ) && $this->_editor === null ) {
				$url_path  = $this->_get_uncropped_url( $item );
				$file_path = str_ireplace( home_url(), ABSPATH, $url_path );

				if ( file_exists( $file_path ) ) {
					$this->_editor = wp_get_image_editor( $file_path );
				} else {
					$this->_editor = wp_get_image_editor( $url_path );
				}
			}
		}

		return $this->_editor;
	}

	/**
	 * @method _get_uncropped_url
	 * @param $item {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $item ) {
		if ( $item->image_source == 'url' ) {
			$url = $item->image_url;
		} else if( !empty( $item->image_src ) ) {
			$url = $item->image_src;
		} else {
			$url = '';
		}

		return $url;
	}

	/**
	 * @method get_video_url
	 * @param $item {object}
	 */
	public function get_video_url( $item ) {
		if ( 'library' == $item->video_source ) {
			$url = wp_get_attachment_url( $item->video );

			return false !== $url && !empty( $url ) ? trim( $url ) : '';
		} elseif ( 'url' == $item->video_source ) {
			$url = trim( $item->video_url );

			return !empty( $url ) ? $url : '';
		} else {
			return '';
		}
	}

	/**
	 * AJAX callback to return the thumbnail of a given video or image URL.
	 *
	 * @param  string       $post_id     The ID of the post this AJAX request came from.
	 * @param  string       $source      The source of the video or image (either library, url, or image).
	 * @param  string       $dat         The URL (for videos/images hosted elsewhere) or ID (for videos/images from the local media library) of the video/image.
	 * @param  string       $element_num The element number for use on the JS side.
	 * @return string|false              The URL of the thumbnail, or false othewrwise.
	 */
	public function ajax_get_thumbnail( $post_id, $source, $dat, $element_num ) {
		if ( 'library' == $source || 'library-image' == $source ) {
			$url = wp_get_attachment_url( $dat );

			return false !== $url ? array( 'type' => $source, 'url' => $url, 'element_num' => intval( $element_num ) ) : false;
		} elseif ( 'url-image' == $source ) {
			return array( 'type' => $source, 'url' => $dat, 'element_num' => intval( $element_num ) );
		} else {
			return false;
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBImageGridModule', array(
	'items' => array( // Tab
		'title'    => __( 'Items', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'items'                  => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'items'             => array(
						'type'          => 'form',
						'label'         => __( 'Item', 'wpzabb' ),
						'form'          => 'image_grid_form', // ID from registered form below
						'multiple'      => true
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
							'max'  	 => 6,
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
	),
	'style'	=> array( // Tab
		'title'    => __( 'Style', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'style_image_grid_overlay' => array( // Section
				'title'     => __( 'Background Overlay', 'wpzabb' ), // Section Title
				'collapsed' => false, // opened by default
				'fields'    => array( // Section Fields
					'image_grid_overlay_gradient' => array(
						'type'          => 'gradient',
						'label'         => '',
						'default'       => array(
							'type'            => 'linear',
							'colors'          => array( 'rgba(0, 0, 0, 0.2)', 'rgba(0, 0, 0, 0.3)' ),
							'stops'           => array( 30, 100 ),
							'angle'           => 180 //to bottom
						),
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-image-grid .wpzabb-image-grid-items .wpzabb-image-grid-item a::after',
							'property'        => 'background-image'
						)
					)
				)
			),
			'style_image_grid_overlay_hover' => array( // Section
				'title'     => __( 'Background Overlay on Hover', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'image_grid_overlay_gradient_hover' => array(
						'type'          => 'gradient',
						'label'         => '',
						'default'       => array(
							'type'            => 'linear',
							'colors'          => array( 'rgba(0, 0, 0, 0.4)', 'rgba(0, 0, 0, 0.5)' ),
							'stops'           => array( 30, 100 ),
							'angle'           => 180 //to bottom
						),
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-image-grid .wpzabb-image-grid-items .wpzabb-image-grid-item a:hover::after',
							'property'        => 'background-image'
						)
					)
				)
			),
		),
	),
) );

/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'image_grid_form', array(
	'title' => __( 'Add Item', 'wpzabb' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'wpzabb' ), // Tab title
			'sections' => array( // Tab Sections
				'general' => array( // Section
					'title'  => '', // Section Title
					'fields' => array( // Section Fields
						'title'       => array(
							'type'          => 'text',
							'label'         => __( 'Title', 'wpzabb' ),
							'placeholder'   => __( 'Item title...', 'wpzabb' ),
							'help'          => __( 'The title of the item. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => '',
							'connections'   => array( 'string', 'html' )
						),
						'link'        => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'wpzabb' ),
							'placeholder'   => __( 'e.g. http://www.wpzoom.com', 'wpzabb' ),
							'help'          => __( 'The URL that the item links to. <em>(Optional)</em>', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'image_source' => array(
							'type'          => 'select',
							'label'         => __( 'Image', 'wpzabb' ),
							'help'          => __( 'The image shown for the item.', 'wpzabb' ),
							'default'       => 'library',
							'options'       => array(
								'none'        => __( 'None', 'wpzabb' ),
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
							),
							'responsive'    => array(
								'default'         => array(
									'default'    => 'library',
									'medium'     => 'library',
									'responsive' => 'library'
								)
							)
						),
						'image'       => array(
							'type'          => 'photo',
							'label'         => ' ',
							'show_remove'	=> true,
							'connections'   => array( 'photo' ),
							'responsive'    => true
						),
						'image_url'   => array(
							'type'          => 'link',
							'label'         => ' ',
							'placeholder'   => 'e.g. http://www.example.com/my-image.jpg',
							'preview'       => array( 'type' => 'none' ),
							'connections'	=> array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true,
							'responsive'    => true
						),
						'color'   => array(
							'type'          => 'color',
							'label'         => __( 'Color', 'wpzabb' ),
							'help'          => __( 'The accent color of the item.', 'wpzabb' ),
							'default'       => '48b1ff',
							'show_reset'    => false,
							'show_alpha'    => false
						)
					)
				)
			)
		)
	)
) );