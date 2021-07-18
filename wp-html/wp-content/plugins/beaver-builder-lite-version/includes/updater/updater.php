<?php

/* Only run if not already setup and not using a repo version. */
if ( ! class_exists( 'FLUpdater' ) && FL_BUILDER_LITE !== true ) {

	/* Defines */
	define( 'FL_UPDATER_DIR', trailingslashit( dirname( __FILE__ ) ) );

	/* Classes */
	require_once FL_UPDATER_DIR . 'classes/class-fl-updater.php';

	/* Actions */
	add_action( 'fl_themes_license_form', 'FLUpdater::render_form' );

	/* Initialize the updater. */
	FLUpdater::init();
}

/**
 * Show dummy license tab with links to docs/upgrade etc.
 */
if ( FL_BUILDER_LITE === true ) {
	add_action( 'fl_themes_license_form', 'FLBuilderAdmin::render_form_lite' );
}

/**
 * Godaddy Booster fix.
 */
add_action( 'init', function() {
	if ( is_admin() && FL_BUILDER_LITE === true && defined( 'FL_BUILDER_BOOSTER_DIR' ) && ! defined( 'FL_UPDATER_DIR' ) ) {
		$data = get_plugin_data( FL_BUILDER_BOOSTER_DIR . '/bb-booster.php', false, false );
		if ( version_compare( $data['Version'], '1.0.9.2', '<=' ) ) {
			define( 'FL_UPDATER_DIR', FL_BUILDER_DIR . 'includes/updater/' );
			require_once FL_UPDATER_DIR . 'classes/class-fl-updater.php';
			FLUpdater::add_product(
				array(
					'name'    => __( 'Beaver Builder Booster', 'bb-booster' ),
					'version' => '1.0.9.1',
					'slug'    => 'bb-booster',
					'type'    => 'plugin',
				)
			);
		}
	}
} );
