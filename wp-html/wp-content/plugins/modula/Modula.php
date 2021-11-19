<?php
/**
 * Plugin Name: Modula PRO
 * Plugin URI: https://wp-modula.com/
 * Description: Modula is one of the best & most creative WordPress gallery plugins. Use it to create a great grid or masonry image gallery.
 * Author: WPChill
 * Author URI: https://www.wpchill.com/
 * Version: 2.4.2
 */

/**
 * Define Constants
 *
 * @since    2.0.0
 */

define( 'MODULA_PRO_VERSION', '2.4.2' );
define( 'MODULA_PRO_PATH', plugin_dir_path( __FILE__ ) );
define( 'MODULA_PRO_URL', plugin_dir_url( __FILE__ ) );
define( 'MODULA_PRO_FILE', __FILE__ );
defined( 'MODULA_PRO_STORE_URL' ) || define( 'MODULA_PRO_STORE_URL', 'https://wp-modula.com' );
defined( 'MODULA_PRO_ALTERNATIVE_STORE_URL' ) || define( 'MODULA_PRO_ALTERNATIVE_STORE_URL', 'https://license.wpchill.com/modula/' );
defined( 'MODULA_PRO_STORE_UPGRADE_URL' ) || define( 'MODULA_PRO_STORE_UPGRADE_URL', 'https://wp-modula.com/pricing' );
defined( 'MODULA_PRO_STORE_ITEM_ID' ) || define( 'MODULA_PRO_STORE_ITEM_ID', 212 );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-modula-pro.php';

/**
 * The plugin updater
 */
require plugin_dir_path( __FILE__ ) . 'includes/updater/class-modula-pro-updater.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function modula_pro_run() {

	$plugin = new Modula_PRO();

}

modula_pro_run();