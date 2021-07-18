<?php

/**
 * @class WPZABBClientsModule
 */
class WPZABBClientsModule extends FLBuilderModule {

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
			'name'          	=> __( 'Clients', 'wpzabb' ),
			'description'   	=> __( 'Displays a list of clients.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'clients/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'clients/',
            'partial_refresh'	=> true,
            'icon'              => 'star-filled.svg'
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
		$client = $settings->clients[0];

		// Make sure we have a image_src property.
		if(!isset($settings->image_src)) {
			$settings->image_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data($client->image);

		if($data) {
			$settings->data = $data;
		}

		return $settings;
	}


	/**
	 * @method get_data
	 * @param $client {object}
	 */
	public function get_data( $client )
	{
		if(!$this->data) {

			// Photo source is set to "url".
			if($client->image_source == 'url') {
				$this->data = new stdClass();

				$this->data->url = $client->image_url;
				$client->image_src = $client->image_url;
			}

			// Photo source is set to "library".
			else if(is_object($client->image)) {
				$this->data = $client->image;
			}
			else {
				$this->data = FLBuilderPhoto::get_attachment_data($client->image);
			}

			// Data object is empty, use the settings cache.
			if(!$this->data && isset($client->data)) {
				$this->data = $client->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $client {object}
	 */
	public function get_classes( $client )
	{
		$classes = array( 'wpzabb-photo-img' );

		if ( $client->image_source == 'library' ) {

			if ( ! empty( $client->image ) ) {

				$data = self::get_data( $client );

				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {

						foreach ( $data->sizes as $key => $size ) {

							if ( $size->url == $client->image_src ) {
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
	 * @param $client {object}
	 */
	public function get_src( $client )
	{
		$src = $this->_get_uncropped_url( $client );

		return $src;
	}


	/**
	 * @method get_alt
	 * @param $client {object}
	 */
	public function get_alt( $client )
	{
		$photo = $this->get_data( $client );

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
	 * @param $client {object}
	 * @protected
	 */
	protected function _has_source( $client )
	{
		if($client->image_source == 'url' && !empty($client->image_url)) {
			return true;
		}
		else if($client->image_source == 'library' && !empty($client->image_src)) {
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
		foreach ( $settings->clients as $i => $client ) {
			if($this->_has_source( $client ) && $this->_editor === null) {

				$url_path  = $this->_get_uncropped_url( $client );
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
	 * @param $client {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $client )
	{
		if($client->image_source == 'url') {
			$url = $client->image_url;
		}
		else if(!empty($client->image_src)) {
			$url = $client->image_src;
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
FLBuilder::register_module('WPZABBClientsModule', array(
	'general'      => array( // Tab
		'title'         => __( 'General', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => 'Layout', // Section Title
				'fields'        => array( // Section Fields
					'layout'       => array(
						'type'          => 'select',
						'label'         => __( 'Layout Columns', 'wpzabb' ),
						'default'       => 'layout-4-cols',
						'options'       => array(
							'layout-1-col'		=> __( '1 column', 'wpzabb' ),
							'layout-2-cols'     => __( '2 columns', 'wpzabb' ),
							'layout-3-cols'     => __( '3 columns', 'wpzabb' ),
							'layout-4-cols'     => __( '4 columns', 'wpzabb' ),
                            'layout-5-cols'     => __( '5 columns', 'wpzabb' ),
						),
					),
					'content_align'     => array(
						'type'          => 'select',
						'label'         => __( 'Content Align', 'wpzabb' ),
						'default'       => 'center',
						'options'       => array(
							'center'	=> __( 'Center', 'wpzabb' ),
							'left'     	=> __( 'Left', 'wpzabb' ),
							'right'     => __( 'Right', 'wpzabb' ),
						),
					),
				),
			),
			'slider'       => array( // Section
				'title'         => __( 'Slider Settings', 'wpzabb' ), // Section Title
				'fields'        => array( // Section Fields
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
						'default'       => '0',
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
							'selector'      => '.wpzabb-clients-wrap .fa',
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
			)
		),
	),
	'clients' 		=> array(
		'title'		=> __( 'Clients', 'wpzabb' ),
		'sections'	=> array(
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'clients'     => array(
						'type'          => 'form',
						'label'         => __( 'Client', 'wpzabb' ),
						'form'          => 'client_form', // ID from registered form below
						'preview_text'  => 'name', // Name of a field to use for the preview text
						'multiple'      => true,
					),
				),
			),
		)
	)
));


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form('client_form', array(
	'title' => __( 'Add Client', 'wpzabb' ),
	'tabs'  => array(
		'general'      => array( // Tab
			'title'         => __( 'General', 'wpzabb' ), // Tab title
			'sections'      => array( // Tab Sections
				'general'       => array( // Section
					'title'         => '', // Section Title
					'fields'        => array( // Section Fields
						'name'        => array(
							'type'            => 'text',
							'label'           => __('Client Name', 'wpzabb'),
							'default'         => __('Some Company', 'wpzabb'),
							'preview'         => array(
								'type'            => 'text',
								'selector'        => '.wpzabb-clients-wrap .wpzabb-client-name'
							),
							'connections'		=> array( 'string', 'html' )
						),
						'image_source'  => array(
							'type'          => 'select',
							'label'         => __('Client Image Source', 'wpzabb'),
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
							'label'         => __('Client Image', 'wpzabb'),
							'show_remove'	=> true,
							'connections'   => array( 'photo' )
						),
						'image_url'     => array(
							'type'          => 'text',
							'label'         => __('Client Image URL', 'wpzabb'),
							'placeholder'   => 'http://www.example.com/my-logo.jpg',
							'connections'	=> array( 'url' )
						),
						'link'          => array(
							'type'          => 'link',
							'label'         => __('Link', 'wpzabb'),
							'default'       => 'http://www.example.com/',
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
						)
					),
				),
			),
		),
	),
));
