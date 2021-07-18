<?php

class Modula_PRO_Addon {

	private $site_host;
	private $addons = array();

	function __construct() {

		add_filter( 'modula_addon_button_action', array( $this, 'output_download_link' ), 10, 2 );
		add_filter( 'modula_addon_server_url', array( $this, 'add_license_to_url' ), 10, 2 );

		// Add script for installing addons
		add_action( 'admin_enqueue_scripts', array( $this, 'addons_scripts' ) );

		// Add ajax action in order to install our addons
		add_action( 'wp_ajax_modula-install-addons', array( $this, 'install_addons' ), 20 );

		// Get website domain
		if ( function_exists( 'domain_mapping_siteurl' ) ) {
			$this->site_host = domain_mapping_siteurl( get_current_blog_id() );
		} else {
			$this->site_host = site_url();
		}

		// Get License key
		$this->license_key = trim( get_option( 'modula_pro_license_key' ) );

	}

	private function check_for_addons() {

		if ( false !== ( $data = get_transient( 'modula_pro_licensed_extensions' ) ) ) {
			$this->addons = is_array( $data ) ? $data : array();
			return;
		}

		// Make sure this matches the exact URL from your site.
		$url = apply_filters( 'modula_addon_server_url', MODULA_PRO_STORE_URL . '/wp-json/mt/v1/get-licensed-extensions' );
		$url = add_query_arg(
			array(
				'license' => $this->license_key,
				'url'     => $this->site_host,
			),
			$url
		);

		// Get data from the remote URL.
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {

			// Decode the data that we got.
			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! empty( $data ) && is_array( $data ) ) {

				$this->addons = $data;

				// Store the data for a week.
				set_transient( 'modula_pro_licensed_extensions', $data, 7 * DAY_IN_SECONDS );
			}
		}

	}

	public function addons_scripts( $hook ) {

		if ( 'modula-gallery_page_modula-addons' == $hook ) {
			wp_enqueue_script( 'modula-pro-addon', MODULA_PRO_URL . 'assets/js/wp-modula-addons.js', array( 'jquery' ), '2.0.0', true );
			$args = array(
				'install_nonce' => wp_create_nonce( 'modula-pro-install' ),
				'connect_error' => esc_html__( 'ERROR: There was an error connecting to the server, Please try again.', 'modula-pro' ),
			);
			wp_localize_script( 'modula-pro-addon', 'modulaPRO', $args );
		}

	}

	public function add_license_to_url( $url ) {
		return add_query_arg(
			array(
				'license' => get_option( 'modula_pro_license_key' ),
				'url'     => site_url(),
			),
			$url
		);
	}

	public function output_download_link( $link, $addon ) {

		if ( empty( $this->addons ) && '' != $this->license_key ) {
			$this->check_for_addons();
		}

		$labels = array(
			'install'   => esc_html__( 'Install & Activate', 'modula-pro' ),
			'activate'  => esc_html__( 'Activate', 'modula-pro' ),
			'installed' => esc_html__( 'Installed', 'modula-pro' ),
		);

		$classes = array(
			'install'   => 'button button-primary modula-addon-action',
			'activate'  => 'button button-primary modula-addon-action',
			'installed' => 'button',
		);

		$action = 'install';

		if ( ! isset( $addon['slug'] ) ) {
			$url  = admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' );
			$link = '<a href="' . $url . '" class="button button-primary">' . esc_html__( 'Add license', 'modula-pro' ) . '</a>';
			return $link;
		}

        $licenses_status = get_option('modula_pro_license_status', false);

		if ( array_key_exists( $addon['slug'], $this->addons ) ) {

			$slug        = $addon['slug'];
			$plugin_path = $slug . '/' . $slug . '.php';

			if ( $this->check_plugin_is_installed( $addon['slug'] ) && ! $this->check_plugin_is_active( $plugin_path ) ) {
				$action = 'activate';
			} elseif ( $this->check_plugin_is_active( $plugin_path ) ) {
				$action = 'installed';
			}

			if ( 'install' != $action ) {
				$url = $this->create_plugin_link( $action, $plugin_path );
			} else {
				$url = $this->addons[ $addon['slug'] ]['download_link'];
			}

			$attr = '';

			if ( 'installed' != $action ) {
				$attr = 'data-action="' . esc_attr($action) . '"';
			} else {
				$attr = 'disabled="disabled"';
			}

			$link = '<a href="' . esc_url($url) . '" ' . $attr . ' class="' . esc_attr($classes[ $action ]) . '">' . esc_html($labels[ $action ]) . '</a>';

		} elseif ( '' != $this->license_key && !(!$licenses_status || 'valid' != $licenses_status->license) ) { //the user has entered a license key, but this extension requires an upgrade
			$url  = MODULA_PRO_STORE_UPGRADE_URL . '?utm_source=modula-pro&utm_campaign=upsell&utm_medium=' . $addon['slug'] . '&license=' . $this->license_key;
			$link = '<a target="_blank" href="' . esc_url($url) . '" class="button button-primary">' . esc_html__( 'Upgrade', 'modula-pro' ) . '</a>';
		} else {
			$url  = admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' );
			$link = '<a href="' . esc_url($url) . '" class="button button-primary">' . esc_html__( 'Add license', 'modula-pro' ) . '</a>';
		}

		return $link;
	}

	// Function to check if a plugin is active
	private function create_plugin_link( $state, $slug ) {
		$string = '';
		switch ( $state ) {
			case 'deactivate':
				$string = add_query_arg(
					array(
						'action'        => 'deactivate',
						'plugin'        => rawurlencode( $slug ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug ),
					),
					admin_url( 'plugins.php' )
				);
				break;
			case 'activate':
				$string = add_query_arg(
					array(
						'action'        => 'activate',
						'plugin'        => rawurlencode( $slug ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $slug ),
					),
					admin_url( 'plugins.php' )
				);
				break;
			default:
				$string = '';
				break;
		}// End switch().
		return $string;
	}

	private function _get_plugins( $plugin_folder = '' ) {

		if ( ! empty( $this->plugins ) ) {
			return $this->plugins;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$this->plugins = get_plugins( $plugin_folder );
		return $this->plugins;
	}

	private function check_plugin_is_installed( $slug ) {
		if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
			return true;
		}
		return false;
	}
	/**
	 * @return bool
	 */
	private function check_plugin_is_active( $plugin_path ) {
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_path ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			return is_plugin_active( $plugin_path );
		}
	}

	// Install Addons
	public function install_addons() {

		// Run a security check first.
		check_admin_referer( 'modula-pro-install', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			echo json_encode( array( 'error' => esc_html__( 'There was an error installing the addon. Please try again.', 'modula-pro' ) ) );
			die;
		}

		if ( ! isset( $_POST['plugin'] ) ) {
			echo json_encode( array( 'error' => esc_html__( 'There was an error installing the addon. Please try again.', 'modula-pro' ) ) );
			die;
		}

		$download_url = esc_url( $_POST['plugin'] );
		if ( false === strpos( $download_url, MODULA_PRO_STORE_URL ) ) {
			echo json_encode( array( 'error' => esc_html__( 'There was an error installing the addon. Please try again.', 'modula-pro' ) ) );
			die;
		}

		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Prepare variables.
		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'modula-pro-settings',
			),
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, null ) ) ) {
			$form = ob_get_clean();
			echo json_encode( array( 'form' => $form ) );
			die;
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo json_encode( array( 'form' => $form ) );
			die;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-skin.php';

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( $skin = new Modula_PRO_Skin() );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			$activate_url    = $this->create_plugin_link( 'activate', $plugin_basename );
			echo json_encode(
				array(
					'plugin'       => $plugin_basename,
					'activate_url' => $activate_url,
				)
			);
			die;
		}


		// Send back a response.
		echo json_encode( true );
		die;

	}

}

new Modula_PRO_Addon();
