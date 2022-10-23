<?php

/**
 * Class WPEL_Plugin
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_Plugin extends FWP_Plugin_Base_1x0x0
{

    /**
     * Initialize plugin
     * @param string $plugin_file
     * @param string $plugin_dir
     */
    protected function init($plugin_file, $plugin_dir)
    {
        parent::init($plugin_file, $plugin_dir);
        $this->create_components();
        add_action('wp_ajax_wpel_dismiss_notice', array($this, 'ajax_dismiss_notice'));
    }

    /**
     * Dismiss notice via AJAX call
     *
     * @return null
     */
    function ajax_dismiss_notice()
    {
      check_ajax_referer('wpel_dismiss_notice');

      if (!current_user_can('administrator')) {
        wp_send_json_error('You are not allowed to run this action.');
      }

      $notice_name = trim(sanitize_text_field(@$_GET['notice_name']));
      $pointers = get_option('wpel-pointers', array());

      if ($notice_name != 'welcome') {
        wp_send_json_error('Unknown notice');
      } else {
        $pointers['hide_welcome_pointer'] = true;
        update_option('wpel-pointers', $pointers);
        wp_send_json_success();
      }
    } // ajax_dismiss_notice


    /**
     * Create components
     */
    protected function create_components()
    {
        WPEL_Register_Scripts::create();

        // network admin page
        $network_page = WPEL_Network_Page::create(array(
            'network-settings'          => WPEL_Network_Fields::create(),
            'network-admin-settings'    => WPEL_Network_Admin_Fields::create(),
        ));

        // admin settings page
        $settings_page = WPEL_Settings_Page::create($network_page, array(
            'external-links'    => WPEL_External_Link_Fields::create(),
            'internal-links'    => WPEL_Internal_Link_Fields::create(),
            'excluded-links'    => WPEL_Excluded_Link_Fields::create(),
            'admin'             => WPEL_Admin_Fields::create(),
            'exceptions'        => WPEL_Exceptions_Fields::create(),
            'exit-confirmation' => WPEL_Exit_Confirmation_Fields::create(),
        ));

        // front site
        if (!is_admin()) {
            WPEL_Front::create($settings_page);
        }

        // update procedures
        WPEL_Update::create();
    }
}
