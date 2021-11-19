<?php
/**
 * WPZOOM_Theme_Updater Class
 *
 * @package WPZOOM
 * @subpackage Updater
 */

add_action('admin_init', array('WPZOOM_Updater', 'init'));

class WPZOOM_Updater {
    public static function init() {
        if (option::is_on('framework_theme_update_notification_enable') && !wpzoom::$tf) {
            add_action('admin_head', array('WPZOOM_Theme_Updater', 'check_update'));
        }

        add_action('wp_ajax_wpzoom_updater', array(__CLASS__, 'ajax'));

        add_filter('http_request_args',  array('WPZOOM_Theme_Updater', 'disable_wporg_request'), 5, 2);
    }

    public static function ajax() {
        if ($_POST['type'] == 'framework-notification-hide') {
            option::set('framework_last_checked', time() + 60 * 60 * 48);
            option::delete('framework_status');

            die();
        }

        if ($_POST['type'] == 'theme-notification-hide') {
            option::set('theme_last_checked', time() + 60 * 60 * 48);
            option::delete('theme_status');

            die();
        }

        if ($_POST['type'] == 'seo-notification-hide') {
            option::set('framework_seo_aware2', 1);

            die();
        }

        die();
    }
}
