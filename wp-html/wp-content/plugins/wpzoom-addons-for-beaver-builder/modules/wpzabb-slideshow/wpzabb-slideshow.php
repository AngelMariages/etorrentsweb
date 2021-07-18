<?php
if ( ! defined( 'WPZABB_SLIDESHOW_DEBUG' ) ) {
	define( 'WPZABB_SLIDESHOW_DEBUG', 0 );
}

/**
 * @class WPZABBSlideshowModule
 */
class WPZABBSlideshowModule extends FLBuilderModule {
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
			'name'          	=> __( 'Slideshow', 'wpzabb' ),
			'description'   	=> __( 'Displays a selection of items in a slideshow format.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/' . WPZABB_PREFIX . 'slideshow/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'slideshow/',
            'partial_refresh'	=> true,
            'icon'              => 'slides.svg'
		) );

		$this->add_css( 'dashicons' );
		$this->add_css( 'flickity-style', $this->url . 'css/flickity.css', array(), '1.3.1' );
		$this->add_css( 'flickity-fade-style', $this->url . 'css/flickity-fade.css', array( 'flickity-style' ), '1.0.0' );
		$this->add_js( 'flickity-script', $this->url . 'js/flickity.js', array( 'jquery' ), '2.2.1' );
		$this->add_js( 'flickity-fade-script', $this->url . 'js/flickity-fade.js', array( 'flickity-script' ), '1.0.0' );

		wp_localize_script( 'flickity-script', 'wpzabb_slideshow_ajax', array(
			'url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'wpzabb-slideshow-ajax-nonce' )
		) );

		if ( 'wpzoom' == wp_get_theme()->get( 'TextDomain' ) ) {
			add_action( 'fl_builder_loop_settings_after_form', array( $this, 'loop_settings_after_form' ) );
			add_filter( 'fl_builder_loop_query_args', array( $this, 'loop_query_args' ) );
		}

		add_action( 'wp_ajax_wpzabb_slideshow_get_thumb', array( $this, 'ajax_get_thumbnail' ) );
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		$slide = $settings->slides[0];

		// Make sure we have a image_src property.
		if ( !isset( $settings->image_src ) ) {
			$settings->image_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data( $slide->image );

		if ( $data ) {
			$settings->data = $data;
		}

		return $settings;
	}

	/**
	 * @method get_data
	 * @param $slide {object}
	 */
	public function get_data( $slide ) {
		if ( !$this->data ) {
			// Photo source is set to "url".
			if ( $slide->image_source == 'url' ) {
				$this->data = new stdClass();
				$this->data->url = $slide->image_url;
				$slide->image_src = $slide->image_url;
			} else if ( is_object( $slide->image ) ) { // Photo source is set to "library".
				$this->data = $slide->image;
			} else {
				$this->data = FLBuilderPhoto::get_attachment_data( $slide->image );
			}

			// Data object is empty, use the settings cache.
			if ( !$this->data && isset( $slide->data ) ) {
				$this->data = $slide->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $slide {object}
	 */
	public function get_classes( $slide ) {
		$classes = array( 'wpzabb-photo-img' );

		if ( $slide->image_source == 'library' ) {
			if ( ! empty( $slide->image ) ) {
				$data = self::get_data( $slide );

				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {
						foreach ( $data->sizes as $key => $size ) {
							if ( $size->url == $slide->image_src ) {
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
	 * @param $slide {object}
	 */
	public function get_src( $slide ) {
		$src = $this->_get_uncropped_url( $slide );

		return $src;
	}

	/**
	 * @method _has_source
	 * @param $slide {object}
	 * @protected
	 */
	protected function _has_source( $slide ) {
		if ( $slide->image_source == 'url' && !empty( $slide->image_url ) ) {
			return true;
		} else if ( $slide->image_source == 'library' && !empty( $slide->image_src ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor() {
		foreach ( $settings->slides as $i => $slide ) {
			if ( $this->_has_source( $slide ) && $this->_editor === null ) {
				$url_path  = $this->_get_uncropped_url( $slide );
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
	 * @param $slide {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $slide ) {
		if ( $slide->image_source == 'url' ) {
			$url = $slide->image_url;
		} else if( !empty( $slide->image_src ) ) {
			$url = $slide->image_src;
		} else {
			$url = '';
		}

		return $url;
	}

	/**
	 * AJAX callback to return the thumbnail of a given image URL.
	 */
	public function ajax_get_thumbnail() {
		check_ajax_referer( 'wpzabb-slideshow-ajax-nonce', 'nonce' );

		$result = false;
		$post_id = isset( $_POST[ 'post_id' ] ) ? intval( $_POST[ 'post_id' ] ) : -1;
		$source = isset( $_POST[ 'source' ] ) ? $_POST[ 'source' ] : 'none';
		$dat = isset( $_POST[ 'dat' ] ) ? $_POST[ 'dat' ] : '';
		$element_num = isset( $_POST[ 'element_num' ] ) ? intval( $_POST[ 'element_num' ] ) : -1;

		if ( 'library' == $source || 'library-image' == $source ) {
			$url = wp_get_attachment_url( $dat );
			$result = false !== $url ? array( 'type' => $source, 'url' => $url, 'element_num' => $element_num ) : false;
		} elseif ( 'url-image' == $source ) {
			$result = array( 'type' => $source, 'url' => $dat, 'element_num' => $element_num );
		}

		wp_send_json_success( $result );
	}

	/**
	 * Adds an extra field to the loop settings in the builder.
	 */
	public function loop_settings_after_form( $settings ) {
		if ( is_object( $settings ) && property_exists( $settings, 'slides_source' ) ) {
			?><div id="fl-builder-settings-section-wpzabb_posts_amount" class="fl-loop-data-wpzabb-posts-amount fl-builder-settings-section">
				<table class="fl-form-table">
					<?php
					FLBuilder::render_settings_field( 'wpzabb_posts_amount', array(
						'type'    => 'unit',
						'label'   => __( 'Posts Limit', 'wpzabb' ),
						'help'    => __( 'The maximum amount of posts to display', 'wpzabb' ),
						'default' => 10,
						'slider' => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1
						)
					), $settings );
					?>
				</table>
			</div>

			<div id="fl-builder-settings-section-wpzabb_featured_posts_only" class="fl-loop-data-wpzabb-featured-posts-only fl-builder-settings-section">
				<table class="fl-form-table">
					<?php
					FLBuilder::render_settings_field( 'wpzabb_featured_posts_only', array(
						'type'    => 'select',
						'label'   => __( '<strong>[WPZOOM]</strong> Only Show Featured Posts', 'wpzabb' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'wpzabb' ),
							'no'  => __( 'No', 'wpzabb' )
						)
					), $settings );
					?>
				</table>
			</div>

			<div id="fl-builder-settings-section-wpzabb_show_excerpt" class="fl-loop-data-wpzabb-show-excerpt fl-builder-settings-section">
				<table class="fl-form-table">
					<?php
					FLBuilder::render_settings_field( 'wpzabb_show_excerpt', array(
						'type'    => 'select',
						'label'   => __( 'Show Excerpt', 'wpzabb' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'wpzabb' ),
							'no'  => __( 'No', 'wpzabb' )
						)
					), $settings );
					?>
				</table>
			</div>

			<div id="fl-builder-settings-section-wpzabb_read_more" class="fl-loop-data-wpzabb-read-more fl-builder-settings-section">
				<table class="fl-form-table">
					<?php
					FLBuilder::render_settings_field( 'wpzabb_read_more', array(
						'type'    => 'select',
						'label'   => __( 'Show Read More Button', 'wpzabb' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'wpzabb' ),
							'no'  => __( 'No', 'wpzabb' )
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array( 'wpzabb_read_more_label' )
							),
							'no'  => array(
								'fields' => array( '' )
							)
						)
					), $settings );

					FLBuilder::render_settings_field( 'wpzabb_read_more_label', array(
						'type'    => 'text',
						'label'   => __( 'Read More Button Label', 'wpzabb' ),
						'default' => __( 'Read More', 'wpzabb' )
					), $settings );
					?>
				</table>
			</div><?php
		}
	}

	/**
	 * Filters the query args for the loop in the builder.
	 */
	public function loop_query_args( $args ) {
		if ( isset( $args[ 'settings' ] ) && is_object( $args[ 'settings' ] ) && property_exists( $args[ 'settings' ], 'slides_source' ) &&
		     property_exists( $args[ 'settings' ], 'wpzabb_featured_posts_only' ) && 'yes' == $args[ 'settings' ]->wpzabb_featured_posts_only ) {
			$args[ 'meta_key' ] = 'wpzoom_is_featured';
		}

		return $args;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBSlideshowModule', array(
	'slides' => array( // Tab
		'title'    => __( 'Slides', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'items'                  => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'slides_source'      => array(
						'type'          => 'select',
						'label'         => __( 'Slides Source', 'wpzabb' ),
						'description'   => __( 'You can customize which posts are included in the slideshow on the <strong>Posts</strong> tab.', 'wpzabb' ),
						'help'          => __( 'The source for slides in the slideshow. (i.e. Custom slides, WordPress posts)', 'wpzabb' ),
						'default'       => 'custom',
						'options'       => array(
							'custom'           => __( 'Custom Slides', 'wpzabb' ),
							'posts'            => __( 'WordPress Posts', 'wpzabb' )
						),
						'toggle'        => array(
							'custom'             => array(
								'tabs'   => array(),
								'fields' => array( 'slides' )
							),
							'posts'              => array(
								'tabs'   => array( 'loop' ),
								'fields' => array()
							)
						)
					),
					'slides'      => array(
						'type'          => 'form',
						'label'         => __( 'Slide', 'wpzabb' ),
						'form'          => 'slides_form', // ID from registered form below
						'multiple'      => true
					)
				)
			)
		)
	),
	'loop' => array(
		'title'    => __( 'Posts', 'wpzabb' ),
		'file'     => FL_BUILDER_DIR . 'includes/loop-settings.php'
	),
	'general'    => array( // Tab
		'title'    => __( 'General', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'general'                => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'slideshow_auto'         => array(
						'type'          => 'button-group',
						'label'         => __( 'Autoplay Slideshow', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should automatically rotate through each slide on an interval.', 'wpzabb' ),
						'default'       => 'no',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'no',
								'medium'     => 'no',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						),
						'toggle'        => array(
							'yes'             => array(
								'fields'   => array( 'slideshow_speed' )
							),
							'no'              => array()
						)
					),
					'slideshow_speed'        => array(
						'type'          => 'unit',
						'label'         => __( 'Autoplay Interval', 'wpzabb' ),
						'help'          => __( 'The interval (in miliseconds) at which the slideshow should automatically rotate.', 'wpzabb' ),
						'default'       => 10000,
						'responsive'    => array(
							'default'         => array(
								'default'    => 10000,
								'medium'     => 10000,
								'responsive' => 10000
							)
						),
						'units'         => array( 'ms' ),
						'default_unit'  => 'ms',
						'slider'        => array(
							'min'             => 0,
							'max'             => 600000,
							'step'            => 1
						)
					),
					'slideshow_transition'   => array(
						'type'          => 'select',
						'label'         => __( 'Slide Transition', 'wpzabb' ),
						'help'          => __( 'The effect used to transition between each slide.', 'wpzabb' ),
						'default'       => 'slide',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'slide',
								'medium'     => 'slide',
								'responsive' => 'slide'
							)
						),
						'options'       => array(
							'fade'             => __( 'Fade', 'wpzabb' ),
							'slide'            => __( 'Slide', 'wpzabb' )
						)
					),
					'slideshow_transition_speed' => array(
						'type'          => 'unit',
						'label'         => __( 'Transition Speed', 'wpzabb' ),
						'help'          => __( 'The speed of the slideshow transitions.', 'wpzabb' ),
						'default'       => 0.28,
						'responsive'    => array(
							'default'         => array(
								'default'    => 0.28,
								'medium'     => 0.28,
								'responsive' => 0.28
							)
						),
						'slider'        => array(
							'min'             => 0,
							'max'             => 1,
							'step'            => 0.001
						)
					),
					'slideshow_shuffle'      => array(
						'type'          => 'button-group',
						'label'         => __( 'Shuffle', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should show all the slides in a random order.', 'wpzabb' ),
						'default'       => 'no',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'no',
								'medium'     => 'no',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_loop'         => array(
						'type'          => 'button-group',
						'label'         => __( 'Infinite Loop', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should loop the slides infinitely.<br/><strong>Yes:</strong> Will rotate past the last slide back to the first one again.<br/><strong>No:</strong> Will stop on the last slide and then run back to the first one.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'yes'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_hoverpause'   => array(
						'type'          => 'button-group',
						'label'         => __( 'Pause On Hover', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should pause when a pointing device hovers over it. <em>(Only if the <strong>Autoplay Slideshow</strong> option is enabled)</em>', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_autoheight' => array(
						'type'          => 'button-group',
						'label'         => __( 'Automatic Height', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should have an automatic height based on the browser/viewport height.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'yes'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						),
						'toggle'        => array(
							'yes'             => array(
								'fields'   => array( 'slideshow_autoheight_size', 'slideshow_autoheight_max' )
							),
							'no'              => array()
						)
					),
					'slideshow_autoheight_size' => array(
						'type'          => 'unit',
						'label'         => __( 'Automatic Height Size', 'wpzabb' ),
						'help'          => __( 'The height (in percents) relative to the browser/viewport the slideshow should maintain.', 'wpzabb' ),
						'default'       => 100,
						'responsive'    => array(
							'default'         => array(
								'default'    => 100,
								'medium'     => 100,
								'responsive' => 100
							)
						),
						'units'         => array( '%' ),
						'default_unit'  => '%',
						'slider'        => array(
							'min'             => 0,
							'max'             => 100,
							'step'            => 1
						)
					),
					'slideshow_autoheight_max' => array(
						'type'          => 'unit',
						'label'         => __( 'Automatic Height Max', 'wpzabb' ),
						'help'          => __( 'The maximum height (in pixels) the slideshow should ever be allowed to grow to.', 'wpzabb' ),
						'default'       => 550,
						'responsive'    => array(
							'default'         => array(
								'default'    => 550,
								'medium'     => 550,
								'responsive' => 550
							)
						),
						'units'         => array( 'px' ),
						'default_unit'  => 'px',
						'slider'        => array(
							'min'             => 0,
							'max'             => 5000,
							'step'            => 1
						)
					),
					'slideshow_image_size'   => array(
						'type'    => 'photo-sizes',
						'label'   => __( 'Image Size', 'wpzabb' ),
						'default' => 'large'
					),
					'slideshow_arrows'       => array(
						'type'          => 'button-group',
						'label'         => __( 'Display Navigation Arrows', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should show previous/next arrow buttons for manually rotating the slides.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_navigation'   => array(
						'type'          => 'select',
						'label'         => __( 'Slide Navigation', 'wpzabb' ),
						'help'          => __( 'The type of interface used for precise slide navigation.<br/><strong>None:</strong> No navigation will be shown.<br/><strong>Dots:</strong> Each slide will be represented by a dot which is clickable to navigate to that slide.<br/><strong>Thumbnails:</strong> Each slide will be represented by a thumbnail which is clickable to navigate to that slide.', 'wpzabb' ),
						'default'       => 'none',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'none',
								'medium'     => 'none',
								'responsive' => 'none'
							)
						),
						'options'       => array(
							'none'             => __( 'None', 'wpzabb' ),
							'dots'             => __( 'Dots', 'wpzabb' ),
							'thumbs'           => __( 'Thumbnails', 'wpzabb' )
						)
					)
				)
			)
		)
	),
	'style'      => array( // Tab
		'title'    => __( 'Style', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'style_slide_background' => array( // Section
				'title'     => __( 'Slide Background', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_background_color' => array(
						'type'          => 'color',
						'label'         => '',
						'default'       => '000000',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide',
							'property'        => 'background-color'
						)
					)
				)
			),
			'style_slide_overlay' => array( // Section
				'title'     => __( 'Slide Overlay', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_overlay_gradient' => array(
						'type'          => 'gradient',
						'label'         => '',
						'default'       => array(
							'type'            => 'linear',
							'colors'          => array( 'rgba(0, 0, 0, 0.5)', 'rgba(0, 0, 0, 0.3)' ),
							'stops'           => array( 0, 100 ),
							'angle'           => 0
						),
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-image::after, .wpzabb-slideshow .wpzabb-slideshow-slide-video::after',
							'property'        => 'background-image'
						)
					)
				)
			),
			'style_slide_title'   => array( // Section
				'title'     => __( 'Slide Title', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_title_font'       => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => '',
							'font_weight'     => 700,
							'font_size'       => array(
								'length'     => '30',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.5',
								'unit'       => ''
							),
							'text_align'      => 'center',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title'
						)
					),
					'slide_title_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title, .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a',
							'property'        => 'color'
						)
					),
					'slide_title_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'cccccc',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a:hover',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_content'   => array( // Section
				'title'     => __( 'Slide Content', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_content_font'       => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => '',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '20',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.5',
								'unit'       => ''
							),
							'text_align'      => 'center',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content'
						)
					),
					'slide_content_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_button' => array( // Section
				'title'     => __( 'Slide Button', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_button_background_color' => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 0.0)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a',
							'property'        => 'background-color'
						)
					),
					'slide_button_align' => array(
						'type'          => 'align',
						'label'         => __( 'Alignment', 'wpzabb' ),
						'default'       => 'center',
						'show_alpha'    => true,
                        'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button',
							'property'        => 'text-align'
						)
					),
					'slide_button_border'    => array(
						'type'          => 'border',
						'label'         => __( 'Border', 'wpzabb' ),
						'default'       => array(
							'style'           => 'solid',
							'color'           => 'ffffff',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a'
						)
					),
					'slide_button_font'      => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => '',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '20',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button'
						)
					),
					'slide_button_color'     => array(
						'type'          => 'color',
						'label'         => __( 'Font Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a',
							'property'        => 'color'
						)
					),
					'slide_button_hover_background_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Background Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 1)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover',
							'property'        => 'background-color'
						)
					),
					'slide_button_hover_border' => array(
						'type'          => 'border',
						'label'         => __( 'Hover Border', 'wpzabb' ),
						'default'       => array(
							'style'           => 'solid',
							'color'           => 'ffffff',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover'
						)
					),
					'slide_button_hover_font' => array(
						'type'          => 'typography',
						'label'         => __( 'Hover Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => '',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '20',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover'
						)
					),
					'slide_button_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Font Color', 'wpzabb' ),
						'default'       => '000000',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_navigation'   => array( // Section
				'title'     => __( 'Slide Navigation', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_navigation_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 0.5)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .flex-direction-nav a, .wpzabb-slideshow .flex-direction-nav a::before',
							'property'        => 'color'
						)
					),
					'slide_navigation_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .flex-direction-nav a:hover, .wpzabb-slideshow .flex-direction-nav a:active, .wpzabb-slideshow .flex-direction-nav a:hover::before, .wpzabb-slideshow .flex-direction-nav a:active::before',
							'property'        => 'color'
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
FLBuilder::register_settings_form( 'slides_form', array(
	'title' => __( 'Add Slide', 'wpzabb' ),
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
							'placeholder'   => __( 'Slide title...', 'wpzabb' ),
							'help'          => __( 'The title of the slide. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => '',
							'connections'   => array( 'string', 'html' )
						),
						'link'        => array(
							'type'          => 'link',
							'label'         => __( 'Title Link', 'wpzabb' ),
							'placeholder'   => __( 'e.g. http://www.wpzoom.com', 'wpzabb' ),
							'help'          => __( 'The URL that the slide title links to. <em>(Optional)</em>', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'content'     => array(
							'type'          => 'editor',
							'label'         => __( 'Content', 'wpzabb' ),
							'placeholder'   => __( 'Slide content...', 'wpzabb' ),
							'help'          => __( 'The text content displayed below the slide title. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => '',
							'rows'          => 4,
							'media_buttons' => false,
							'connections'   => array( 'string', 'html' ),
							'responsive'    => array(
								'default'         => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => ''
								)
							),
							'preview'       => array(
								'type'           => 'none'
							)
						),
						'button'            => array(
							'type'          => 'button-group',
							'label'         => __( 'Button', 'wpzabb' ),
							'help'          => __( 'Whether to show a clickable button on this slide.', 'wpzabb' ),
							'default'       => 'no',
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							),
							'toggle'        => array(
								'yes'             => array(
									//'sections' => array( 'style_button' ),
									'fields'   => array( 'button_label', 'button_url' )
								),
								'no'              => array()
							),
							'responsive'    => array(
								'default'         => array(
									'default'    => 'no',
									'medium'     => 'no',
									'responsive' => 'no'
								)
							)
						),
						'button_label'      => array(
							'type'          => 'text',
							'label'         => __( 'Button Label', 'wpzabb' ),
							'help'          => __( 'The label of the clickable button on this slide.', 'wpzabb' ),
							'default'       => __( 'Read More', 'wpzabb' ),
							'responsive'    => array(
								'default'         => array(
									'default'    => __( 'Read More', 'wpzabb' ),
									'medium'     => __( 'Read More', 'wpzabb' ),
									'responsive' => __( 'Read More', 'wpzabb' )
								)
							)
						),
						'button_url'        => array(
							'type'          => 'link',
							'label'         => __( 'Button URL', 'wpzabb' ),
							'help'          => __( 'The URL the clickable button on this slide points to.', 'wpzabb' ),
							'placeholder'   => __( 'e.g. http://www.wpzoom.com', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'image_source' => array(
							'type'          => 'select',
							'label'         => __( 'Image', 'wpzabb' ),
							'help'          => __( 'The image shown in the background of the slide. <em>(Optional)</em>', 'wpzabb' ),
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
						)
					)
				)
			)
		)
	)
) );