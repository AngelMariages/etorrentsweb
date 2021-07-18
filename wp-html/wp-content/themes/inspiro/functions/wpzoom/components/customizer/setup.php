<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Setup_Customizer
 *
 * Methods for managing and enqueueing script and style assets.
 *
 * @since 1.7.0.
 */
class WPZOOM_Setup_Customizer {
	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Instance of this class.
	 * @since 1.8.5
	 * @var   object
	 */
	protected static $_instance = null;

	/**
	 * List of the tag names seen for before_widget strings.
	 *
	 * This is used in the {@see 'filter_wp_kses_allowed_html'} filter to ensure that the
	 * data-* attributes can be whitelisted.
	 *
	 * @since 1.8.5
	 * @var array
	 */
	protected $before_widget_tags_seen = array();

	/**
	 * Main WPZOOM_Setup_Customizer Instance
	 *
	 * Ensures only one instance of WPZOOM_Setup_Customizer is loaded or can be loaded.
	 *
	 * @since 1.8.5
	 * @static
	 *
	 * @return object Main WPZOOM_Setup_Customizer instance
	 */
	public static function instance() {
		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Main Class Construct
	 */
	protected function __construct() {
		$this->init();
	}

	/**
	 * Initialize class
	 * 
	 * @since 1.8.5
	 */
	private function init() {
		// Register all the needed hooks
		$this->register_hooks();
	}
	/**
	 * Register our actions and filters
	 * 
	 * @since 1.8.5
	 *
	 * @return void
	 */
	function register_hooks() {
		if ( $this->is_hooked() ) {
			return;
		}

		// Styles for the Customizer controls
		add_action( 'customize_controls_init', array( $this, 'register_styles' ), 10 );

		// Scripts for the Customizer controls
		add_action( 'customize_controls_init', array( $this, 'register_scripts' ), 10 );

		// Styles enqueued in the Customizer only in the theme preview
		add_action( 'customize_preview_init', array( $this, 'register_live_preview_styles' ), 10 );

		// Scripts enqueued in the Customizer only in the theme preview
		add_action( 'customize_preview_init', array( $this, 'register_live_preview_scripts' ), 10 );

		// Handle stuff related to selective refresh (partial refresh)
		add_action( 'customize_preview_init', array( $this, 'selective_refresh_init' ) );

		// Hooking has occurred.
		self::$hooked = true;
	}

	/**
	 * Check if the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_hooked() {
		return self::$hooked;
	}

	/**
	 * Wrapper for getting the path to the customizer assets directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_assets_uri( $endpoint = '' )
	{
	    return WPZOOM::$wpzoomPath . '/components/customizer/assets/' . $endpoint;
	}

	/**
	 * Wrapper for getting the URL for the customizer assets CSS directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_css_uri( $endpoint = '' )
	{
	    return $this->get_assets_uri('css/' . $endpoint);
	}

	/**
	 * Wrapper for getting the URL for the customizer assets JS directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_js_uri( $endpoint = '' )
	{
	    return $this->get_assets_uri('js/' . $endpoint);
	}

	/**
	 * Register style libraries.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	function register_styles() {
		// Chosen
		wp_register_style(
			'chosen',
			$this->get_css_uri('libs/chosen/chosen.min.css'),
			array(),
			'1.5.1'
		);

		// jQuery UI
		wp_register_style(
			'zoom-jquery-ui-custom',
			$this->get_css_uri('libs/jquery-ui/jquery-ui-1.10.4.custom.css'),
			array(),
			'1.10.4'
		);

		// Customizer controls
		wp_register_style(
		    'zoom-customizer-controls',
		    $this->get_css_uri('controls.css'),
		    array( 'zoom-jquery-ui-custom', 'chosen' ),
		    WPZOOM::$wpzoomVersion
		);
	}

	/**
	 * Register JavaScript libraries.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	function register_scripts() {
		// Chosen
		wp_register_script(
			'chosen',
			$this->get_js_uri('libs/chosen/chosen.jquery.min.js'),
			array( 'jquery' ),
			'1.5.1',
			true
		);

		// Customizer controls
		wp_register_script(
		    'zoom-customizer-controls',
		    $this->get_js_uri('controls.js'),
		    array( 'chosen', 'underscore', 'jquery-ui-button', 'jquery-ui-slider', 'customize-controls' ),
		    WPZOOM::$wpzoomVersion,
		    true
		);

		wp_register_script(
		    'zoom-customizer-helper',
		    $this->get_js_uri('customizer.js'),
		    array( 'jquery', 'underscore', 'customize-controls' ),
		    WPZOOM::$wpzoomVersion,
		    true
		);
	}

	/**
	 * Register Customizer styles loaded only in the preview frame
	 *
	 * @since 1.8.5
	 *
	 * @return void
	 */
	function register_live_preview_styles() {
		// Customizer preview
		wp_register_style(
		    'zoom-customizer-preview',
		    $this->get_css_uri('customizer-preview.css'),
		    array(),
		    WPZOOM::$wpzoomVersion
		);
	}

	/**
	 * Register Customizer scripts loaded only in the preview frame
	 *
	 * @since 1.8.5
	 *
	 * @return void
	 */
	function register_live_preview_scripts() {
		wp_register_script(
		    'zoom-customizer-vein-js',
		    $this->get_js_uri('vein.min.js'),
		    array(),
		    false,
		    true
		);

		wp_register_script(
			'zoom-customizer-typekit-font-loader',
		    'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js'
		);

		wp_register_script(
		    'zoom-customizer-preview',
		    $this->get_js_uri('preview.js'),
		    array( 'jquery', 'customize-preview', 'underscore' ),
		    WPZOOM::$wpzoomVersion,
		    true
		);
	}

	/**
	 * Adds hooks for selective refresh.
	 * 
	 * @since 1.8.5
	 */
	public function selective_refresh_init() {
		if ( ! current_theme_supports( 'customize-selective-refresh-widgets' ) ) {
			return;
		}
		add_filter( 'dynamic_sidebar_params', array( $this, 'filter_dynamic_sidebar_params' ) );
		add_filter( 'wp_kses_allowed_html', array( $this, 'filter_wp_kses_allowed_data_attributes' ) );
	}

	/**
	 * Inject selective refresh data attributes into widget container elements.
	 *
	 * @since 1.8.5
	 *
	 * @param array $params {
	 *     Dynamic sidebar params.
	 *
	 *     @type array $args        Sidebar args.
	 *     @type array $widget_args Widget args.
	 * }
	 * @see WP_Customize_Nav_Menus_Partial_Refresh::filter_wp_nav_menu_args()
	 *
	 * @return array Params.
	 */
	public function filter_dynamic_sidebar_params( $params ) {
		$sidebar_args = array_merge(
			array(
				'before_widget' => '',
				'after_widget' => '',
			),
			$params[0]
		);

		// Skip widgets not in a registered sidebar or ones which lack a proper wrapper element to attach the data-* attributes to.
		$matches = array();
		$is_valid = (
			isset( $sidebar_args['id'] )
			&&
			is_registered_sidebar( $sidebar_args['id'] )
			&&
			preg_match( '#^<(?P<tag_name>\w+)#', $sidebar_args['before_widget'], $matches )
		);

		if ( ! $is_valid ) {
			return $params;
		}

		$this->before_widget_tags_seen[ $matches['tag_name'] ] = true;

		$attributes = sprintf( ' data-customize-widget-name="%s"', esc_attr( $sidebar_args['widget_name'] ) );
		$sidebar_args['before_widget'] = preg_replace( '#^(<\w+)#', '$1 ' . $attributes, $sidebar_args['before_widget'] );
		$params[0] = $sidebar_args;

		return $params;
	}

	/**
	 * Ensures the HTML data-* attributes for selective refresh are allowed by kses.
	 *
	 * This is needed in case the `$before_widget` is run through wp_kses() when printed.
	 *
	 * @since 1.8.5
	 *
	 * @param array $allowed_html Allowed HTML.
	 * @return array (Maybe) modified allowed HTML.
	 */
	public function filter_wp_kses_allowed_data_attributes( $allowed_html ) {
		foreach ( array_keys( $this->before_widget_tags_seen ) as $tag_name ) {
			if ( ! isset( $allowed_html[ $tag_name ] ) ) {
				$allowed_html[ $tag_name ] = array();
			}
			$allowed_html[ $tag_name ] = array_merge(
				$allowed_html[ $tag_name ],
				array_fill_keys( array(
					'data-customize-partial-widget-name',
				), true )
			);
		}
		return $allowed_html;
	}
}

$wpzoom_setup_customizer = WPZOOM_Setup_Customizer::instance();