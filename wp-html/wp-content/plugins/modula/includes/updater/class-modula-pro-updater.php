<?php

/**
 *
 */
class Modula_Pro_Updater {

	function __construct() {
		add_action( 'admin_init', array( $this, 'plugin_updater' ), 0 );
	}

	public function plugin_updater() {

		if ( ! class_exists( 'Modula_Pro_Base_Updater' ) ) {
			// load our custom updater
			require_once dirname( __FILE__ ) . '/class-modula-pro-base-updater.php';
		}

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'modula_pro_license_key' ) );

		// setup the updater
		$modula_pro_updater = new Modula_Pro_Base_Updater(
			MODULA_PRO_STORE_URL,
			MODULA_PRO_FILE,
			array(
				'version' => MODULA_PRO_VERSION,          // current version number
				'license' => $license_key,               // license key (used get_option above to retrieve from DB)
				'item_id' => MODULA_PRO_STORE_ITEM_ID,       // ID of the product
				'author'  => 'MachoThemes',            // author of this plugin
				'beta'    => false,
			)
		);
    
		do_action( 'modula_pro_updater', $license_key, MODULA_PRO_STORE_URL );

	}

}

new Modula_Pro_Updater();
