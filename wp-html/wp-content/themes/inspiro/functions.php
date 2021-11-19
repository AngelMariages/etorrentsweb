<?php
/**
 * WPZOOM Theme Functions
 *
 * Don't edit this file until you know what you're doing. If you want to add
 * custom functions in your theme please create a Child Theme and add the code
 * in the functions.php file of it. In this way your changes will never
 * be overwritten in case of a theme update.
 */

/**
 * Paths to WPZOOM Theme Functions
 */
define("FUNC_INC", get_template_directory() . "/functions");

define("WPZOOM_INC", FUNC_INC . "/wpzoom");

/** WPZOOM Framework Core */
require_once WPZOOM_INC . "/init.php";

/** WPZOOM Theme */
require_once FUNC_INC . "/functions.php";
require_once FUNC_INC . "/post-options.php";
require_once FUNC_INC . "/custom-post-types.php";
require_once FUNC_INC . "/sidebar.php";


/* Theme widgets */
require_once FUNC_INC . "/widgets/recentposts.php";
require_once FUNC_INC . "/widgets/single-page.php";
require_once FUNC_INC . "/widgets/portfolio-showcase.php";
require_once FUNC_INC . "/widgets/portfolio-scroller.php";
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require_once FUNC_INC . "/widgets/featured-products.php";
}

include_once get_template_directory() . '/theme-includes/init.php';