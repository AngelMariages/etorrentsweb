<?php

/**
 * Plugin Name:    WP External Links
 * Version:        2.58
 * Plugin URI:     https://getwplinks.com/
 * Description:    Open external links in a new tab or window, control "nofollow" and "noopener", set font icon; SEO friendly.
 * Author:         WebFactory Ltd
 * Author URI:     https://www.webfactoryltd.com/
 * License:        Dual licensed under the MIT and GPLv2+ licenses
 * Text Domain:    wp-external-links

 * Copyright 2019 - 2023  WebFactory Ltd  (email: support@webfactoryltd.com)
 * Copyright 2011 - 2019  @freelancephp

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once 'wf-flyout/wf-flyout.php';
new wf_flyout(__FILE__);

if (!function_exists('wpel_init')) :

  function wpel_init()
  {
    // only load in WP environment
    if (!defined('ABSPATH')) {
      die();
    }

    define('TEST_WPEL_PLUGIN_FILE', __FILE__);
    define('WPEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
    define('WPEL_PLUGIN_URL', plugin_dir_url(__FILE__));

    $plugin_file = defined('TEST_WPEL_PLUGIN_FILE') ? TEST_WPEL_PLUGIN_FILE : __FILE__;
    $plugin_dir = dirname(__FILE__);

    // check requirements
    $wp_version = get_bloginfo('version');
    $php_version = phpversion();

    if (version_compare($wp_version, '4.2', '<') || version_compare($php_version, '5.3', '<')) {
      if (!function_exists('wpel_requirements_notice')) {
        function wpel_requirements_notice()
        {
          include dirname(__FILE__) . '/templates/requirements-notice.php';
        }

        add_action('admin_notices', 'wpel_requirements_notice');
      }

      return;
    }

    /**
     * Autoloader
     */
    if (!class_exists('WPRun_Autoloader_1x0x0')) {
      require_once $plugin_dir . '/libs/wprun/class-wprun-autoloader.php';
    }

    $autoloader = new WPRun_Autoloader_1x0x0();
    $autoloader->add_path($plugin_dir . '/libs/', true);
    $autoloader->add_path($plugin_dir . '/includes/', true);


    /**
     * Load debugger
     */
    if (true === constant('WP_DEBUG')) {
      FWP_Debug_1x0x0::create(array(
        'log_hooks'  => false,
      ));
    }

    /**
     * Register Hooks
     */
    global $wpdb;
    WPEL_Activation::create($plugin_file, $wpdb);
    WPEL_Deactivate::create($plugin_file, $wpdb);
    WPEL_Uninstall::create($plugin_file, $wpdb);

    /**
     * Set plugin vars
     */
    WPEL_Plugin::create($plugin_file, $plugin_dir);
  }

  wpel_init();

endif;
