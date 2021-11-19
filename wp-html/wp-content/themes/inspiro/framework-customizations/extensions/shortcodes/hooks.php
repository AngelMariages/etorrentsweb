<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Disable standard Unyson shortcodes
 *
 * @param array $to_disabled array of shortcodes
 * @return array $to_disabled return array of shortcodes names to be disabled
 */
if ( ! function_exists( 'fw_disable_default_shortcodes' ) ) :
	function fw_disable_default_shortcodes( $to_disabled ) {
		$to_disabled[] = 'calendar';
		$to_disabled[] = 'call_to_action';
		$to_disabled[] = 'notification';

		return $to_disabled;
	}

	add_filter( 'fw_ext_shortcodes_disable_shortcodes', 'fw_disable_default_shortcodes', 10, 2 );
endif;