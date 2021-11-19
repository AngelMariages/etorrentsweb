<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}
$manifest = array();

$manifest['id'] = get_option( 'stylesheet' );

$manifest['supported_extensions'] = array(
	'page-builder' => array()
);