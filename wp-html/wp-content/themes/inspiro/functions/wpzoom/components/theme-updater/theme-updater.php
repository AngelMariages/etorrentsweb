<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 */

// Includes the files needed for the theme updater
if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

$wpz_theme_name = wp_get_theme( get_template() );

// Config settings
$config = array(
	'remote_api_url' => 'https://www.wpzoom.com/', // Site where EDD is hosted
	'item_name'      => $wpz_theme_name->get( 'Name' ), // Name of theme
	'theme_slug'     => WPZOOM::$theme_raw_name, // Theme slug
	'version'        => $wpz_theme_name->get( 'Version' ), // The current version of this theme
	'author'         => 'WPZOOM', // The author of this theme
	'download_id'    => '', // Optional, used for generating a license renewal link
	'renew_url'      => 'https://www.wpzoom.com/account/licenses/' // Optional, allows for a custom license renewal link
);

if ( current_theme_supports( 'wpz-theme-info' ) ) {

	$theme_info = get_theme_support( 'wpz-theme-info' );
	$theme_info = array_pop( $theme_info );

	if ( ! empty( $theme_info['name'] ) ) {
		$config['item_name'] = ucfirst( $theme_info['name'] );
	}

	if ( ! empty( $theme_info['slug'] ) ) {
		$config['theme_slug'] = strtolower( $theme_info['slug'] );
	}

}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(


$config,

// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'wpzoom' ),
		'enter-key'                 => __( 'Enter your license key for this theme to enable <strong>theme updates</strong>.', 'wpzoom' ),
		'license-key'               => __( 'License Key', 'wpzoom' ),
		'license-action'            => __( 'License Action', 'wpzoom' ),
		'deactivate-license'        => __( 'Deactivate License', 'wpzoom' ),
		'activate-license'          => __( 'Activate License', 'wpzoom' ),
		'status-unknown'            => __( 'Incorrect license key.', 'wpzoom' ),
		'renew'                     => __( 'Renew?', 'wpzoom' ),
		'unlimited'                 => __( 'unlimited', 'wpzoom' ),
		'license-key-is-active'     => __( 'License key is active.', 'wpzoom' ),
		'expires%s'                 => __( 'Expires %s.', 'wpzoom' ),
        'expires-never'             => __( 'Lifetime License. ', 'wpzoom' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'wpzoom' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'wpzoom' ),
		'license-key-expired'       => __( 'License key has expired.', 'wpzoom' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'wpzoom' ),
		'license-is-inactive'       => __( 'License is <strong>inactive</strong>. Click on the <strong>Activate Button</strong> to activate it.', 'wpzoom' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'wpzoom' ),
		'site-is-inactive'          => __( 'This license is inactive on this website. Click on the <strong>Activate Button</strong> to activate it.', 'wpzoom' ),
		'license-status-unknown'    => __( 'Incorrect license key.', 'wpzoom' ),
		'update-notice'             => __( "Updating this theme will lose any modifications you have made to this theme and translations stored in theme folder. Changes made in Customizer and Custom CSS code added in Additional CSS will not be affected. 'Cancel' to stop, 'OK' to update.", 'wpzoom' ),
		'update-available'          => __( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wpzoom' )
	)

);