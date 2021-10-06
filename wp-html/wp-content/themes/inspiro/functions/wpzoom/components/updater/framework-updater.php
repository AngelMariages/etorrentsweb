<?php
/**
 * WPZOOM_Framework_Updater Class
 *
 * @package WPZOOM
 * @subpackage Framework_Updater
 */

class WPZOOM_Framework_Updater {

    public $enable_update;

    public $enable_notifs;

    public $user_can_update;

    public $status;

    public function __construct()
    {
        $this->enable_update   = option::is_on('framework_update_enable');
        $this->enable_notifs   = option::is_on('framework_update_notification_enable');
        $this->status          = option::get('framework_status');
        $this->user_can_update = current_user_can('update_themes');

        add_action('admin_menu', array( $this, 'admin_menu_bubble' ) );

        if ( $this->enable_update && $this->enable_notifs ) {
            add_action( 'admin_head', array( $this, 'update_init' ) );
            add_action( 'admin_head', array( $this, 'check_update' ) );
        }

        if ( $this->status === 'needs_update' && $this->user_can_update ) {
            add_action( 'admin_notices', array( $this, 'admin_notification') );
        }
    }


    public function do_update()
    {
        if (isset($_POST['wpzoom-update-do'])) {
            $action = strtolower(trim(strip_tags($_POST['wpzoom-update-do'])));

            $data = array(
                'message' => '
                        <div class="popup-modal-message success-message">
                            <div class="icon-wrap">
                                <span><i class="fa fa-check"></i></span>
                            </div>
                            <p class="description">'. __('You have successfully updated ZOOM Framework!', 'wpzoom') .'</p>
                        </div>'
            );

            if ($action == 'update') {
                $fwUrl = 'http://framework.wpzoom.com/wpzoom-framework.zip';
                $fwFile = download_url($fwUrl);

                if (is_wp_error($fwUrl)) {
                    $error = $fwFile->get_error_code();

                    if ($error == 'http_no_url') {
                        $msg = __("<p class=\"description\">Failed: Invalid URL Provided</p>", 'wpzoom');
                    } else {
                        $msg = sprintf(__("<p class=\"description\">Failed: Upload - %s</p>", 'wpzoom'), $error);
                    }

                    function framework_update_warning() {
                        global $msg;
                        echo force_balance_tags($msg);
                    }
                    add_action( 'admin_notices', 'framework_update_warning' );

                    $data['message'] = '<div class="popup-modal-message warning-message">
                                    <div class="icon-wrap">
                                        <span><i class="fa fa-exclamation"></i></span>
                                    </div>
                                    '. $msg .'
                                </div>';

                    wp_send_json_error($data);
                    return;
                }
            }

            global $wp_filesystem;
            $to = WPZOOM::get_wpzoom_root();
            $dounzip = unzip_file($fwFile, $to);

            unlink($fwFile);

            if (is_wp_error($dounzip)) {

                $error = $dounzip->get_error_code();
                $data = $dounzip->get_error_data($error);

                if($error == 'incompatible_archive') {
                    //The source file was not found or is invalid
                    $msg = __('Failed: Incompatible archive', 'wpzoom');
                    function framework_update_no_archive_warning() {
                        global $msg;
                        echo "<p>$msg</p>";
                    }
                    add_action( 'admin_notices', 'framework_update_no_archive_warning' );
                }

                if($error == 'empty_archive') {
                    $msg = __('Failed: Empty Archive', 'wpzoom');
                    function framework_update_empty_archive_warning() {
                        global $msg;
                        echo "<p>$msg</p>";
                    }
                    add_action( 'admin_notices', 'framework_update_empty_archive_warning' );
                }

                if($error == 'mkdir_failed') {
                    $msg = __("Failed: mkdir Failure", 'wpzoom');
                    function framework_update_mkdir_warning() {
                        global $msg;
                        echo "<p>$msg</p>";
                    }
                    add_action( 'admin_notices', 'framework_update_mkdir_warning' );
                }

                if($error == 'copy_failed') {
                    $msg = __("Failed: Copy Failed", 'wpzoom');
                    function framework_update_copy_fail_warning() {
                        global $msg;
                        echo "<p>$msg</p>";
                    }
                    add_action( 'admin_notices', 'framework_update_copy_fail_warning' );
                }

                if($error == 'fs_unavailable') {
                    $msg = __("Could not access filesystem.", 'wpzoom');
                    function framework_update_fs_unavailable_warning() {
                        global $msg;
                        echo "<p>$msg</p>";
                    }
                    add_action( 'admin_notices', 'framework_update_fs_unavailable_warning' );
                }

                $data['message'] = '<div class="popup-modal-message warning-message">
                                <div class="icon-wrap">
                                    <span><i class="fa fa-exclamation"></i></span>
                                </div>
                                <p class="description">'. $msg .'</p>
                            </div>';

                wp_send_json_error($data);
                return;

            }

            function framework_updated_success() {
                echo __('<div class="updated fade"><p>New framework successfully downloaded, extracted and updated.</p></div>', 'wpzoom');
            }
            add_action('admin_notices', 'framework_updated_success');

            remove_action('admin_notices', array('WPZOOM', 'notification'));

            option::delete('framework_status');
            option::set('framework_last_checked', time());
        }
    }


    /**
     * Adds notifications if there is any new version available.
     * Runs one time a day.
     */
    public function check_update()
    {
        /* force recheck if we spoted manualy modified version */
        if (get_transient('framework_version') !== WPZOOM::$wpzoomVersion) {
            $lastChecked = 0;
        } else {
            $lastChecked = (int) option::get('framework_last_checked');
        }

        if ($lastChecked == 0 || ($lastChecked + 60 * 60 * 24) < time()) {
            if ( $this->has_update() ) {
                option::set('framework_status', 'needs_update');
            } else {
                option::delete('framework_status');
            }
            option::set('framework_last_checked', time());
            set_transient('framework_version', WPZOOM::$wpzoomVersion);
        }

    }

    /**
     * Checks if a new ZOOM Framework version is available.
     */
    public function has_update()
    {
        $remoteVersion = $this->get_remote_version();
        $localVersion  = $this->get_local_version();

        if (preg_match('/[0-9]*\.?[0-9]+/', $remoteVersion)) {
            if (version_compare($localVersion, $remoteVersion, '<')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns local framework version.
     *
     * @return string
     */
    public static function get_local_version() {
        return WPZOOM::$wpzoomVersion;
    }

    /**
     * Returns latest available ZOOM Framework version.
     *
     * @return string
     */
    public static function get_remote_version() {
        global $wp_version;

        $php_version = phpversion();
        $home_url = home_url('/');
        $fw_version = WPZOOM::$wpzoomVersion;

        $url  = 'http://framework.wpzoom.com/get-version/';

        $options = array(
            'timeout' => 3,
            'user-agent' => sprintf('WordPress/%1$s PHP/%2$s FW/%3$s; %4$s', $wp_version, $php_version, $fw_version, $home_url)
        );

        $response = wp_remote_get($url, $options);

        if (is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)) {
            return __('Can\'t contact WPZOOM server. Please try again later.', 'wpzoom');
        }

        $version = trim(wp_remote_retrieve_body($response));

        $version = maybe_unserialize($version);

        return $version;
    }

    /**
     * Returns Framework changelog
     *
     * @return string changelog
     */
    public static function get_changelog() {
        global $wp_version;

        $url = 'http://framework.wpzoom.com/changelog/';

        $options = array(
            'timeout'    => 3,
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' )
        );

        $response = wp_remote_get($url, $options);

        if (is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)) {
            return __('Can\'t contact WPZOOM server. Please try again later.', 'wpzoom');
        }

        $response = trim(wp_remote_retrieve_body($response));
        $response = maybe_unserialize($response);

        return $response;
    }

    public function admin_notification()
    {
        $msg = sprintf(__('You are using an outdated version of ZOOM Framework, please <a href="%s">update now</a>. <input type="button" class="close button" value="Hide" />', 'wpzoom'), admin_url('admin.php?page=wpzoom_update'));

        echo '<div class="update-nag zoomfw-core notice notice-warning">' . $msg . '</div>';
    }

    public function admin_menu_bubble()
    {
        global $menu, $submenu;

        $warning_count = 0;

        if ( $this->status === 'needs_update' && $this->user_can_update ) {
            $warning_count = 1;
        }

        $bubble = sprintf( __( '%s', 'wpzoom' ), "<span class='awaiting-mod count-$warning_count'><span class='pending-count'>" . number_format_i18n($warning_count) . "</span></span>" );

        foreach ($submenu as $key => $items) {

            foreach ($items as $_key => $menu_item) {

                if ( $menu_item[2] == 'wpzoom_update' ) {
                    $submenu[$key][$_key][0] = $submenu[$key][$_key][0] . $bubble;
                }

            }

        }

    }

    /**
     * Checks if we are going to make an update and updates current framework to latest version
     */
    public function update_init() {
        global $r;

        if (!isset($_GET['page'])) return;

        $requestedPage = strtolower(strip_tags(trim($_REQUEST['page'])));

        if ($requestedPage != 'wpzoom_update') return;

        $fsmethod = get_filesystem_method();
        $fs = WP_Filesystem();

        if ($fs == false) {
            function framework_update_filesystem_warning() {
                $method = get_filesystem_method();
                printf(__("<p>Failed: Filesystem preventing downloads. (%s)</p>", 'wpzoom'), $method);
            }
            add_action( 'admin_notices', 'framework_update_filesystem_warning' );

            return;
        }

        $this->do_update();
    }
}

new WPZOOM_Framework_Updater();
