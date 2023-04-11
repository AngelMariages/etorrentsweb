<?php

/**
 * Class WPEL_Register_Scripts
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_Register_Scripts extends WPRun_Base_1x0x0
{

    /**
     * Action for "wp_enqueue_scripts"
     */
    protected function action_wp_enqueue_scripts()
    {
        $this->register_scripts();
    }

    /**
     * Action for "admin_enqueue_scripts"
     */
    protected function action_admin_enqueue_scripts()
    {
        $this->register_scripts();
    }

    /**
     * Register styles and scripts
     */
    protected function register_scripts()
    {
        $plugin_version = get_option('wpel-version');
        $pointers = get_option('wpel-pointers', array());

        if(function_exists('get_current_screen')){
            $current_screen = get_current_screen();
        }

      if (isset($current_screen) && $current_screen->id != 'toplevel_page_wpel-settings-page' && $current_screen->id != 'settings_page_wpel-settings-page') {
        if (empty($pointers['hide_welcome_pointer']) && current_user_can('administrator')) {
          $pointers['_nonce_dismiss_pointer'] = wp_create_nonce('wpel_dismiss_notice');
          $pointers['welcome'] = array('target' => '#toplevel_page_wpel-settings-page', 'edge' => 'left', 'align' => 'right', 'content' => 'Thank you for installing the <b style="font-weight: 800;">WP External Links</b> plugin!<br>Open <a href="' . admin_url('admin.php?page=wpel-settings-page') . '">WP External Links</a> to manage your links, and configure settings.');

          wp_enqueue_style('wp-pointer');

          wp_enqueue_script('wpel-pointers', plugins_url('/public/js/wpel-pointers.js', WPEL_Plugin::get_plugin_file()), array('jquery'), get_option('wpel-version'), true);
          wp_enqueue_script('wp-pointer');
          wp_localize_script('wp-pointer', 'wpel_pointers', $pointers);
        }
      }

        // set style font awesome icons
        wp_register_style(
            'wpel-font-awesome',
            plugins_url('/public/css/font-awesome.min.css', WPEL_Plugin::get_plugin_file()),
            array(),
            $plugin_version
        );

        // front style
        wp_register_style(
            'wpel-style',
            plugins_url('/public/css/wpel.css', WPEL_Plugin::get_plugin_file()),
            array(),
            $plugin_version
        );

        // set admin style
        wp_register_style(
            'wpel-admin-style',
            plugins_url('/public/css/wpel-admin.css', WPEL_Plugin::get_plugin_file()),
            array(),
            $plugin_version
        );

        $wpel_js = array(
            'nonce_ajax' => wp_create_nonce('wpel_run_tool'),
            'loader' => admin_url('/images/spinner.gif')
        );

        // set wpel admin script
        wp_register_script(
            'wpel-admin-script',
            plugins_url('/public/js/wpel-admin.js', WPEL_Plugin::get_plugin_file()),
            array('jquery'),
            $plugin_version,
            true
        );

        wp_localize_script('wpel-admin-script', 'wpel', $wpel_js);
    }
}
