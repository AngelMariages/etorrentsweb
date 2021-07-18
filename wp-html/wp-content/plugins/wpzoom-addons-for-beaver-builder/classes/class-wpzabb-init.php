<?php

/**
 * WPZABB initial setup
 *
 * @since 1.0
 */
class WPZABB_Init {

	public static $wpzabb_options;

	/**
	*  Constructor
	*/

	public function __construct() {

		if ( class_exists( 'FLBuilder' ) ) {

			/**
			 *	For Performance
			 *	Set WPZABB static object to store data from database.
			 */
			self::set_wpzabb_options();

			add_filter( 'fl_builder_settings_form_defaults', array( $this, 'wpzabb_global_settings_form_defaults' ), 10, 2 );	
			// Load all the required files of wpzoom-bb-addon-pack
			self::includes();
			add_action( 'init', array( $this, 'init' ) );			

			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 100 );

			$basename = plugin_basename( BB_WPZOOM_ADDON_FILE );
			// Filters
			add_filter( 'plugin_action_links_' . $basename, array( $this, 'wpzabb_render_plugin_action_links' ) );

		} else {

			// disable WPZABB activation ntices in admin panel
			define( 'WPZOOM_WPZABB_NOTICES', false );

			// Display admin notice for activating beaver builder
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'network_admin_notices', array( $this, 'admin_notices' ) );
		}

	}

	function wpzabb_render_plugin_action_links( $actions ) {

		return $actions;
	}

	function includes() {

		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-helper.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/wpzabb-global-functions.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/helper.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-ui-panel.php';

		// Load the appropriate text-domain
		$this->load_plugin_textdomain();

	}

	/**
	*	For Performance
	*	Set WPZABB static object to store data from database.
	*/
	static function set_wpzabb_options() {
		self::$wpzabb_options = array(
			'fl_builder_wpzabb'          => FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb', true ),
			'fl_builder_wpzabb_branding' => FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb_branding', false ),
			'wpzabb_global_settings'     => get_option('_wpzabb_global_settings'),

			'fl_builder_wpzabb_modules' => FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb_modules', false ),
		);
	}

	function wpzabb_global_settings_form_defaults( $defaults, $form_type ) {

		if ( class_exists( 'FLCustomizer' ) && 'wpzabb-global' == $form_type ) {

        	$defaults->enable_global = 'no';
	    }

	    return $defaults; // Must be returned!
	}

	function init() {

		// WPZOOM Addons Pack Modules
		$this->load_modules();
	}

	function load_plugin_textdomain() {
		//Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wpzabb' );

		//Setup paths to current locale file
		$mofile_global = trailingslashit( WP_LANG_DIR ) . 'plugins/wpzoom-bb-addon-pack/' . $locale . '.mo';
		$mofile_local  = trailingslashit( BB_WPZOOM_ADDON_DIR ) . 'languages/' . $locale . '.mo';

		if ( file_exists( $mofile_global ) ) {
			//Look in global /wp-content/languages/plugins/wpzoom-bb-addon-pack/ folder
			return load_textdomain( 'wpzabb', $mofile_global );
		}
		else if ( file_exists( $mofile_local ) ) {
			//Look in local /wp-content/plugins/wpzoom-bb-addon-pack/languages/ folder
			return load_textdomain( 'wpzabb', $mofile_local );
		} 

		//Nothing found
		return false;
	}

	function load_scripts() {

		wp_dequeue_style( 'bootstrap-tour' );
		wp_dequeue_script( 'bootstrap-tour' );

		wp_enqueue_style(
			'magnificPopup',
			BB_WPZOOM_ADDON_URL . 'assets/css/magnific-popup.css',
			array(),
			BB_WPZOOM_ADDON_LITE_VERSION
		);

		wp_enqueue_script(
			'magnificPopup',
			BB_WPZOOM_ADDON_URL . 'assets/js/jquery.magnific-popup.min.js',
			array(),
			BB_WPZOOM_ADDON_LITE_VERSION,
			true
		);
		
	}

	function admin_notices() {

		if ( file_exists( plugin_dir_path( 'bb-plugin-agency/fl-builder.php' ) ) 
			|| file_exists( plugin_dir_path( 'beaver-builder-lite-version/fl-builder.php' ) ) ) {

			$url = network_admin_url() . 'plugins.php?s=Beaver+Builder+Plugin';
		} else {
			$url = network_admin_url() . 'plugin-install.php?s=billyyoung&tab=search&type=author';
		}

		echo '<div class="notice notice-error">';
		echo '<p>' . sprintf( __('The %s plugin requires %s plugin installed and activated.', 'wpzabb'), '<strong>WPZOOM Addons Pack for Beaver Builder</strong>', '<strong><a href="' . esc_url($url) . '">Beaver Builder</a></strong>' ) . '</p>';
	    echo '</div>';
  	}

  	function load_modules() {

  		$enable_modules = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_modules();

		foreach ( $enable_modules as $file => $name ) {

			if ( $name == 'false' ) {
				continue;
			}

			$module_path	= $file . '/' .$file . '.php';
			$child_path		= get_stylesheet_directory() . '/wpzoom-bb-addon-pack/modules/'.$module_path;
			$theme_path		= get_template_directory() . '/wpzoom-bb-addon-pack/modules/'.$module_path;
			$addon_path		= BB_WPZOOM_ADDON_DIR . 'modules/' . $module_path;

			// Check for the module class in a child theme.
			if( is_child_theme() && file_exists($child_path) ) {
				require_once $child_path;
			}

			// Check for the module class in a parent theme.
			else if( file_exists($theme_path) ) {
				require_once $theme_path;
			}

			// Check for the module class in the builder directory.
			else if( file_exists($addon_path) ) {
				require_once $addon_path;
			}
		}
  	}
}

/**
 * Initialize the class only after all the plugins are loaded.
 */

function init_wpzabb() {
	$WPZABB_Init = new WPZABB_Init();
}

add_action( 'plugins_loaded', 'init_wpzabb' );
