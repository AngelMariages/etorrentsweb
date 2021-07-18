<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main WPZOOM_Helper_Guide class.
 */
class WPZOOM_Helper_Guide {
	public $markdown_doc_url = null;
	protected $component_uri = '';

	/**
	 * Constructor.
	 */
	public function __construct( $component_uri ) {
        $this->component_uri = $component_uri;

        add_filter('zoom_options', [$this, 'add_options'] );

    }

    public function add_options($options){
        $new_option = array(array(
            "name" => __("Disable WPZOOM Helper Guide", "wpzoom"),
            "desc" => __("Disable WPZOOM Helper Guide", "wpzoom"),
            "id" => "framework_helper_guide",
            "std" => "off",
            "type" => "checkbox"
        ));

        array_splice($options['framework'], 8, 0, $new_option);

        return $options;
    }

	/**
	 * Initialize.
	 *
	 * @param string $markdown_doc_url URL of the raw markdown file.
	 */
	public function init( $markdown_doc_url ) {
		$this->markdown_doc_url = $markdown_doc_url;

		if ( is_admin() ) {
			if ( ! is_customize_preview() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'wpzoom_helper_guide_load_scripts' ) );
				add_action( 'admin_footer', array( $this, 'display_wpzoom_helper_guide' ) );
			}
		} elseif ( is_customize_preview() ) {
			add_action( 'customize_preview_init', function () {
				add_action( 'wp_enqueue_scripts', array( $this, 'wpzoom_helper_guide_load_scripts' ), 99 );
				add_action( 'wp_footer', array( $this, 'display_wpzoom_helper_guide' ), 1 );
			} );
		}
	}

	/**
	 * Enqueue CSS and JS.
	 */
	public function wpzoom_helper_guide_load_scripts() {

		wp_enqueue_style(
			'wpzoom_helper_guide_admin_css',
			$this->get_css_uri( 'wpzoom-helper-guide.css' ),
			array(),
			WPZOOM::$wpzoomVersion
		);

		wp_enqueue_script(
			'wpzoom_helper_guide_markdown_js',
			$this->get_js_uri( 'marked.js' ),
			array(),
			WPZOOM::$wpzoomVersion,
			true
		);

		wp_enqueue_script(
			'wpzoom_helper_guide_admin_js',
			$this->get_js_uri( 'wpzoom-helper-guide.js' ),
			array(),
			WPZOOM::$wpzoomVersion,
			true
		);

	}

	public function get_css_uri( $name = '' ) {
		return $this->get_assets_uri( 'css' ) . $name;
	}

	public function get_assets_uri( $name = '' ) {
		return trailingslashit( trailingslashit( $this->component_uri ) . 'assets/' . $name );
	}

	public function get_js_uri( $name = '' ) {
		return $this->get_assets_uri( 'js' ) . $name;
	}

	/**
	 * Get admin color scheme.
	 */
	public function wpzoom_helper_guide_get_admin_colors() {

		if(is_customize_preview()){
			register_admin_color_schemes();
		}

		global $_wp_admin_css_colors;


		$current_color_scheme = get_user_meta( get_current_user_id(), 'admin_color', true );
		$colors               = $_wp_admin_css_colors[ $current_color_scheme ]->colors;

		return $colors;
	}

	/**
	 * Display the HTML.
	 *
	 * @param $markdown_doc_url URL of the raw markdown file.
	 */
	public function display_wpzoom_helper_guide() {
		$colors                           = $this->wpzoom_helper_guide_get_admin_colors();
		$wpzoom_helper_guide_accent_color = $colors[2];
		?>
		<script
			type="text/javascript">var wpzoomHelperGuideDocUrl = <?php echo json_encode( $this->markdown_doc_url ); ?>;</script>
		<div class="wpzoom-helper-guide-launcher">
			<button class="wpzoom-helper-guide-launcher--button" id="wpzoom-helper-guide-launcher--button"
			        data-accent-color="<?php echo esc_attr( $wpzoom_helper_guide_accent_color ); ?>">
				<svg class="wpzoom-helper-guide-launcher--icon-enable" xmlns="https://www.w3.org/2000/svg"
				     xmlns:xlink="https://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100"
				     style="enable-background:new 0 0 100 100;" xml:space="preserve"><g>
						<circle cx="50" cy="63.5" r="3"></circle>
						<g>
							<path
								d="M88.6,50c0-21.3-17.3-38.6-38.6-38.6S11.4,28.7,11.4,50S28.7,88.6,50,88.6S88.6,71.3,88.6,50z M15.6,50    c0-18.9,15.4-34.4,34.4-34.4S84.4,31.1,84.4,50S68.9,84.4,50,84.4S15.6,68.9,15.6,50z"></path>
							<path
								d="M55.8,42.1c0.1,2.5-1.4,4.8-3.7,5.7c-2.6,1-4.3,3.6-4.3,6.5v1.4h4.2v-1.4c0-1.1,0.7-2.2,1.6-2.6c4-1.6,6.5-5.5,6.3-9.8    c-0.2-5.1-4.5-9.4-9.6-9.6C47.7,32.1,45,33.1,43,35c-2,1.9-3.1,4.5-3.1,7.3h4.2c0-1.6,0.6-3.1,1.8-4.2c1.2-1.1,2.7-1.7,4.3-1.6    C53.3,36.6,55.7,39.1,55.8,42.1z"></path>
						</g>
					</g></svg>
				<svg class="wpzoom-helper-guide-launcher--icon-close" xmlns="https://www.w3.org/2000/svg"
				     viewBox="0 0 24 24">
					<g id="plus">
						<path
							d="M18.36,19.78L12,13.41,5.64,19.78,4.22,18.36,10.59,12,4.22,5.64,5.64,4.22,12,10.59l6.36-6.36,1.41,1.41L13.41,12l6.36,6.36Z"/>
					</g>
				</svg>
				<span class="wpzoom-helper-guide-launcher--label"><strong>Need help
						with <?php echo WPZOOM::$themeName; ?> theme?</strong></span>
			</button>
		</div>

		<div class="wpzoom-helper-guide-container" id="wpzoom-helper-guide-container">
			<div class="wpzoom-helper-guide-container--head" id="wpzoom-helper-guide-header">
				<h4 class="wpzoom-helper-guide-container--heading"><strong>Need help
						with <?php echo WPZOOM::$themeName; ?> theme?</strong></h4>
				<a href="javascript:;" class="wpzoom-helper-guide-container--back" style="color:<?php echo esc_attr( $wpzoom_helper_guide_accent_color );?>" id="wpzoom-helper-guide-back-to-toc">
					<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<rect x="0" fill="none" width="24" height="24"/>
						<g>
							<path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
						</g>
					</svg>
					Back
				</a>
				<svg class="wpzoom-helper-guide-container--close-mobile" id="wpzoom-helper-guide-mobile-close"
				     xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<g id="plus">
						<path
							d="M18.36,19.78L12,13.41,5.64,19.78,4.22,18.36,10.59,12,4.22,5.64,5.64,4.22,12,10.59l6.36-6.36,1.41,1.41L13.41,12l6.36,6.36Z"/>
					</g>
				</svg>
			</div>
			<div class="wpzoom-helper-guide-container--content" id="wpzoom-helper-guide-content"></div>
            <div class="wpzoom-helper-howtoclose">You can disable this tooltip from <a href="admin.php?page=wpzoom_options#tabframework">Theme Options &rarr; Framework &rarr; Disable WPZOOM Helper Guide</a></div>
		</div>
		<?php
	}

}
