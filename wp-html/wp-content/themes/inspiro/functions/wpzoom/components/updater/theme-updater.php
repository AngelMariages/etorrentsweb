<?php
/**
 * WPZOOM_Theme_Updater Class
 *
 * @package WPZOOM
 * @subpackage Theme_Updater
 */

class WPZOOM_Theme_Updater {
    /**
     * Returns local theme version
     *
     * @return string
     */
    public static function get_local_version() {
        return WPZOOM::$themeVersion;
    }

    /**
     * Returns current theme version pulled from WPZOOM server.
     *
     * @return string
     */
    public static function get_remote_version() {
        global $wp_version;

        $url  = 'https://wploy.wpzoom.com/changelog/' . WPZOOM::$theme_raw_name;

        $options = array(
            'timeout'    => 3,
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' )
        );

        $response = wp_remote_get($url, $options);

        if (is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)) {
            return __('Can\'t contact WPZOOM server. Please try again later.', 'wpzoom');
        }

        $changelog = trim(wp_remote_retrieve_body($response));
        $changelog = maybe_unserialize($changelog);

        $changelog = preg_split("/(\r\n|\n|\r)/", $changelog);

        foreach ($changelog as $line) {
            if (preg_match("/((?:\d+(?!\.\*)\.)+)(\d+)?(\.\*)?/i", $line, $matches)) {
                $version = $matches[0];
                break;
            }
        }

        return $version;
    }

    /**
     * Checks if new theme version is available
     *
     * @return bool true if new version if remote version is higher than local
     */
    public static function has_update() {
        $remoteVersion = self::get_remote_version();
        $localVersion  = self::get_local_version();

        if (preg_match('/[0-9]*\.?[0-9]+/', $remoteVersion)) {
            if (version_compare($localVersion, $remoteVersion, '<')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds notifications if there are new theme version available.
     * Runs on time a day
     *
     * @return void
     */
    public static function check_update() {
        $lastChecked = (int) option::get('theme_last_checked');
        $temp_version = get_transient('wpzoom_temp_theme_version');

        // force a check if we think theme was updated
        if (!$temp_version) {
            set_transient('wpzoom_temp_theme_version', WPZOOM::$themeVersion);
        } else {
            if (version_compare($temp_version, WPZOOM::$themeVersion, '!=')) {
                $lastChecked = 0;
                set_transient('wpzoom_temp_theme_version', WPZOOM::$themeVersion);
            }
        }

        if ($lastChecked == 0 || ($lastChecked + 60 * 60 * 24) < time()) {
            if (self::has_update()) {
                option::set('theme_status', 'needs_update');
            } else {
                option::delete('theme_status');
            }
            option::set('theme_last_checked', time());
        }

        if (option::get('theme_status') == 'needs_update' && current_user_can('update_themes') && get_option(WPZOOM::$theme_raw_name.'_license_key_status') !== 'valid') {
            add_thickbox();
            add_action('admin_notices', array(__CLASS__, 'notification'));
        }
    }

    /**
     * wp-admin global notification about new theme version release
     *
     * @return void
     */
    public static function notification() {
        $update_url = isset(WPZOOM::$config['tf_url']) ? WPZOOM::$config['tf_url'] : 'https://www.wpzoom.com/themes/' . WPZOOM::$theme_raw_name;
        $ignored_themes = get_deprecated_themes();
        // in_array(WPZOOM::$theme_raw_name, $ignored_themes) in future will replace the true condition.
        $tutorial_link = true ? __(' or click <u><a target="_blank" href="https://www.wpzoom.com/tutorial/how-to-update-a-wpzoom-theme/">here</a></u> to view how to update your theme.', 'wpzoom') : '';
        $wpz_theme_name = wp_get_theme(get_template());

        echo '<div class="zoomfw-theme update-nag notice notice-warning">';
        echo 'A <strong>new update</strong> for <a  target="_blank" href="' . $update_url . '">' . $wpz_theme_name . '</a> theme is available. ';
        echo '<u><a href="https://wploy.wpzoom.com/changelog/' . WPZOOM::$theme_raw_name . '?width=750&amp;TB_iframe=true" class="thickbox thickbox-preview">View Changelog</a></u>'.$tutorial_link;
        echo ' <input type="button" class="close button" value="Hide" /></div>';
    }

    public static function disable_wporg_request($args, $url) {
        if (0 !== strpos($url, 'https://api.wordpress.org/themes/update-check/1.1/') &&
            0 !== strpos($url, 'http://api.wordpress.org/themes/update-check/1.1/')) {
            return $args;
        }

        $themes = json_decode($args['body']['themes']);

        $parent = get_option('template');
        $child = get_option('stylesheet');

        unset($themes->themes->$parent);
        unset($themes->themes->$child);

        $args['body']['themes'] = json_encode($themes);

        return $args;
    }
}
