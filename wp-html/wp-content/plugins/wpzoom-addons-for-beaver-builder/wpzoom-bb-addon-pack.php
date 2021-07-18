<?php

/**
 * Plugin Name: Beaver Builder Addons by WPZOOM
 * Plugin URI: https://www.wpzoom.com/
 * Description: WPZOOM Addons will extend Beaver Builder with several useful extensions that are missing in the Lite version.
 * Version: 1.3.4
 * Author: WPZOOM
 * Author URI: https://www.wpzoom.com
 * Copyright: (c) 2018 WPZOOM
 * License: GPLv2 or later
 * Text Domain: wpzabb
 * Domain Path: /languages
 */


if( !class_exists( "WPZOOM_BB_Addon_Pack" ) ) {

	define( 'BB_WPZOOM_ADDON_DIR', plugin_dir_path( __FILE__ ) );
	define( 'BB_WPZOOM_ADDON_URL', plugins_url( '/', __FILE__ ) );
	define( 'BB_WPZOOM_ADDON_LITE_VERSION', '1.3.4' );
	define( 'WPZOOM_REMOVE_wpzabb_FROM_REGISTRATION_LISTING', true );
	define( 'BB_WPZOOM_ADDON_FILE', trailingslashit( dirname( __FILE__ ) ) . 'wpzoom-bb-addon-pack.php' );
	define( 'BB_WPZOOM_ADDON_LITE', true );


	class WPZOOM_BB_Addon_Pack {

		/*
		* Constructor function that initializes required actions and hooks
		* @Since 1.0
		*/
		function __construct() {

			register_activation_hook( __FILE__, array( $this, 'activation_reset' ) );

			//	WPZABB Initialize
			require_once 'classes/class-wpzabb-init.php';
		}

		function activation_reset() {

			$no_memory = $this->check_memory_limit();

			// Stop process when allocated memory is exhausted
			if( $no_memory == true && ! defined( 'WP_CLI' ) ) {

				$msg  = sprintf( __('Unfortunately, the plugin could not be activated as the memory allocated by your host has almost exhausted. WPZOOM Addons for Beaver Builder plugin recommends that your site should have 15M PHP memory remaining. <br/><br/><a class="button button-primary" href="%s">Return to Plugins Page</a>', 'wpzabb'), network_admin_url( 'plugins.php' ) );

				deactivate_plugins( plugin_basename( __FILE__ ) );
				wp_die( $msg );
			}

			delete_option( 'wpzabb_hide_branding' );

			// Force check graupi bundled products
			update_site_option( 'wpzoom_force_check_extensions', true );
			update_option( 'wpzabb_lite_redirect', true );
		}

		/*
		* Checks website memory limit
		*
		* @Since 1.0
		*/
		function check_memory_limit() {
			$memory_limit  = ini_get( 'memory_limit' );       	// Total Memory.
            $peak_memory   = memory_get_peak_usage( true );   	// Available Memory.
            $wpzabb_required = 14999999;                      	// Required Memory for WPZABB.

            if ( '-1' !== $memory_limit ) {
                if ( preg_match( '/^(\d+)(.)$/', $memory_limit, $matches ) ) {

                    switch ( $matches[2] ) {
                        case 'K':
                        case 'k':
                            $memory_limit = $matches[1] * 1024;
                            break;
                        case 'M':
                        case 'm':
                            $memory_limit = $matches[1] * 1024 * 1024;
                            break;
                        case 'G':
                        case 'g':
                            $memory_limit = $matches[1] * 1024 * 1024 * 1024;
                            break;
                    }
                }

                if ( $memory_limit - $peak_memory <= $wpzabb_required ) {
                    return true;
                } else {
                    return false;
                }
            }
		}
	}

	new WPZOOM_BB_Addon_Pack();

} else {

	// Display admin notice for activating beaver builder
	add_action( 'admin_notices', 'admin_notices' );
	add_action( 'network_admin_notices', 'admin_notices' );

	function admin_notices() {
		$deactivate_url = admin_url( 'plugins.php' );
		if ( is_plugin_active_for_network( 'wpzoom-addons-for-beaver-builder/wpzoom-bb-addon-pack.php' ) ) {
			$deactivate_url = network_admin_url( 'plugins.php' );
		}
		$slug = 'wpzoom-bb-addon-pack';
		$deactivate_url = add_query_arg(
			array(
				'action'        => 'deactivate',
				'plugin'        => rawurlencode( 'wpzoom-addons-for-beaver-builder/' . $slug . '.php' ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_wpzoom-addons-for-beaver-builder/' . $slug . '.php' ),
			), $deactivate_url
		);
		echo '<div class="notice notice-error"><p>';
		echo sprintf( __( "You currently have two versions of <strong>Beaver Builder Addons by WPZOOM</strong> active on this site. Please <a href='%s'>deactivate one</a> before continuing.", 'wpzabb' ), $deactivate_url );
		echo '</p></div>';

	}
}