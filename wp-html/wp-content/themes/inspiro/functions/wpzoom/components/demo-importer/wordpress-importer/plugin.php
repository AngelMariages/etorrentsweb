<?php
/*
Plugin Name: WordPress Importer v2
Plugin URI: http://wordpress.org/extend/plugins/wordpress-importer/
Description: Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
Author: wordpressdotorg, rmccue
Author URI: http://wordpress.org/
Version: 2.0
Text Domain: wpzoom
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

if ( ! class_exists( 'WP_Importer' ) ) {
	defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
	require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
}

require dirname( __FILE__ ) . '/class-logger.php';
require dirname( __FILE__ ) . '/class-logger-cli.php';
require dirname( __FILE__ ) . '/class-wxr-importer.php';

if ( defined( 'WP_CLI' ) ) {
	require __DIR__ . '/class-command.php';

	WP_CLI::add_command( 'wxr-importer', 'WXR_Import_Command' );
}

function wpimportv2_init() {
	/**
	 * WordPress Importer object for registering the import callback
	 * @global WP_Import $wp_import
	 */
	$GLOBALS['wxr_importer'] = new WP_Import();
	register_importer(
		'wordpress',
		'WordPress (v2)',
		__('Import <strong>posts, pages, comments, custom fields, categories, and tags</strong> from a WordPress export (WXR) file.', 'wpzoom'),
		array( $GLOBALS['wxr_importer'], 'dispatch' )
	);
}
add_action( 'admin_init', 'wpimportv2_init' );
