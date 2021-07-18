<?php

add_action( 'wp_ajax_modula_pro_install_addon', 'modula_pro_ajax_install_addon' );

/**
 * Installs an Modula addon.
 *
 * @since 2.0.0
 */
function modula_pro_ajax_install_addon() {

	// Run a security check first.
	check_admin_referer( 'modula-pro-install', 'nonce' );

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = esc_url($_POST['plugin']);
		global $hook_suffix;

		// Let only plugins from our site.
		if ( 0 !== strpos( $download_url, 'https://wp-modula.com' ) ) {
			// Send back a response.
			echo json_encode( false );
			die;
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Prepare variables.
		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'modula-pro-settings'
			),
			admin_url( 'admin.php' )
		);
		$url = esc_url( $url );

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
			echo json_encode( array( 'plugin' => $plugin_basename ) );
			die;
		}
	}

	// Send back a response.
	echo json_encode( true );
	die;

}

add_action( 'wp_ajax_modula_pro_activate_addon', 'modula_pro_ajax_activate_addon' );

/**
 * Activates an Modula addon.
 *
 * @since 2.0.0
 */
function modula_pro_ajax_activate_addon() {

	// Run a security check first.
	check_admin_referer( 'modula-pro-activate', 'nonce' );

	// Activate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		if ( 0 !== strpos( $_POST['plugin'], 'modula-' ) ) {
			echo json_encode( false );
			die;
		}
		$activate = activate_plugin( $_POST['plugin'] );

		if ( is_wp_error( $activate ) ) {
			echo json_encode( array( 'error' => $activate->get_error_message() ) );
			die;
		}
	}

	echo json_encode( true );
	die;

}

add_action( 'wp_ajax_modula_pro_deactivate_addon', 'modula_pro_ajax_deactivate_addon' );
/**
 * Deactivates an Modula addon.
 *
 * @since 2.0.0
 */
function modula_pro_ajax_deactivate_addon() {

	// Run a security check first.
	check_admin_referer( 'modula-pro-deactivate', 'nonce' );

	// Deactivate the addon.
	if ( isset( $_POST['plugin'] ) ) {

		if ( 0 !== strpos( $_POST['plugin'], 'modula-' ) ) {
			echo json_encode( false );
			die;
		}

		$deactivate = deactivate_plugins( $_POST['plugin'] );
	}

	echo json_encode( true );
	die;

}