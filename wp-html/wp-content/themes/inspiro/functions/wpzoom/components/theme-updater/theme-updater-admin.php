<?php

/**
 * Theme updater admin page and functions.
 *
 */
class EDD_Theme_Updater_Admin
{

    /**
     * Variables required for the theme updater
     *
     * @since 1.0.0
     * @type string
     */
    protected $remote_api_url = null;
    protected $theme_slug = null;
    protected $version = null;
    protected $author = null;
    protected $download_id = null;
    protected $renew_url = null;
    protected $strings = null;
    public $guide_data = array();

    /**
     * Initialize the class.
     *
     * @since 1.0.0
     */
    function __construct($config = array(), $strings = array())
    {
        $this->guide_data = is_file(get_template_directory() . '/functions/theme-guide-data.php') ? require_once get_template_directory() . '/functions/theme-guide-data.php' : array();
        $guide_defaults = $this->get_default_guide_data();

        foreach ($this->guide_data as $key => &$guide_line) {
            if (array_key_exists($key, $guide_defaults)) {
                $guide_line = wp_parse_args($guide_line, $guide_defaults[$key]);
            }
        }

        $diff = array_diff_key($guide_defaults, $this->guide_data);
        $this->guide_data = array_merge($diff, $this->guide_data);

        $config = wp_parse_args($config, array(
            'remote_api_url' => 'http://easydigitaldownloads.com',
            'theme_slug' => get_template(),
            'item_name' => '',
            'license' => '',
            'version' => '',
            'author' => '',
            'download_id' => '',
            'renew_url' => ''
        ));

        /**
         * Fires after the theme $config is setup.
         *
         * @param array $config Array of EDD SL theme data.
         */
        do_action( 'post_edd_sl_theme_updater_setup', $config );


        // Set config arguments
        $this->remote_api_url = $config['remote_api_url'];
        $this->item_name = $config['item_name'];
        $this->theme_slug = $config['theme_slug'];
        $this->version = $config['version'];
        $this->author = $config['author'];
        $this->download_id = $config['download_id'];
        $this->renew_url = $config['renew_url'];

        // Populate version fallback
        if ('' == $config['version']) {
            $theme = wp_get_theme($this->theme_slug);
            $this->version = $theme->get('Version');
        }

        // Strings passed in from the updater config
        $this->strings = $strings;

        add_action( 'admin_init', array( $this, 'admin_styles' ) );
        add_action( 'admin_init', array( $this, 'updater' ) );
        add_action( 'admin_init', array( $this, 'register_option' ) );
        add_action( 'admin_init', array( $this, 'license_action' ) );
        add_action( 'admin_menu', array( $this, 'license_menu' ) );
        add_action( 'admin_print_scripts-appearance_page_license_page', array( $this, 'admin_styles' ) );
        add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );
        add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );

        if ( current_user_can( 'update_themes' ) ) {
            add_action( 'admin_notices', array( &$this, 'license_notice' ) );
        }


    }

    function get_default_guide_data()
    {
        $wpz_theme_name = wp_get_theme(get_template());

        return array(
            'documentation' => array(
                'header' => __('Read Theme Documentation', 'wpzoom'),
                'content' => __('<strong>Theme Documentation</strong> is the place where you\'ll find the information needed to setup the theme quickly, and other details about theme-specific features.', 'wpzoom'),
                'actions' => array(
                    '<a class="button button-primary" href="https://www.wpzoom.com/documentation/'.str_replace('_', '-', WPZOOM::$theme_raw_name).'/" target="_blank">'.$wpz_theme_name.' Documentation &raquo;</a>
'
                )
            ),
            'demo-content' => array(
                'header' => __('Import the Demo Content', 'wpzoom'),
                'content' => __('If you’re installing the theme on a new site, installing the demo content is the best way to get familiarized. This feature can be found on the <a href="admin.php?page=wpzoom_options" target="_blank">Theme Options</a> page, in the <strong>Import/Export</strong> section.', 'wpzoom'),
                'actions' => array(
                    '<a class="button button-primary" href="https://www.wpzoom.com/docs/demo-content-importer/" target="_blank">View Instructions</a> &nbsp;&nbsp;',
                    '<a class="button button-secondary" href="admin.php?page=wpzoom_options" target="_blank">Open Theme Options</a>'
                )
            ),
            'customizer' => array(
                'header' => __('Add your Logo & Customize the Theme', 'wpzoom'),
                'content' => __('Using the <strong>Live Customizer</strong> you can easily upload your <strong>logo image</strong>, change <strong>fonts, colors, widgets, menus</strong> and much more!', 'wpzoom'),
                'actions' => array(
                    '<a class="button button-primary" href="customize.php" target="_blank">Open Theme Customizer »</a>',
                )
            ),
            'plugins' => array(
                'header' => __('Install Required Plugins', 'wpzoom'),
                'content' => __('In order to enable all the features from your theme, you’ll need to install and activate the required plugins such as <strong>Jetpack</strong> or <strong>WooCommerce</strong>, which are available for <strong>free</strong>.', 'wpzoom'),
                'actions' => array(
                    '<a class="button button-primary" href="admin.php?page=tgmpa-install-plugins" target="_blank">Install Required Plugins</a>&nbsp;&nbsp;',
                    '<a class="button button-secondary" href="https://www.wpzoom.com/recommended-plugins/" target="_blank">Recommended Plugins by WPZOOM</a>'
                )
            ),
            'support' => array(
                'header' => __('Need one-to-one Assistance?', 'wpzoom'),
                'content' => __('Need help setting up your theme or have a question? Get in touch with our Support Team. We’d love the opportunity to help you.', 'wpzoom'),
                'actions' => array(
                    '<a class="button button-primary" href="https://www.wpzoom.com/support/tickets/" target="_blank">Open Support Desk »</a>'
                ),
            )
        );
    }

    function license_notice()
    {
        $license_status = get_option( $this->theme_slug . '_license_key_status', 'inactive');

        $wpz_theme_name = wp_get_theme(get_template());

        if ( $license_status == 'expired' ) {
            echo '<div id="update-nag" class="notice notice-warning settings-error">';
            echo sprintf( __('Your <strong>%s theme</strong> license is <strong>%s</strong>, please %srenew your license key%s to enable theme updates', 'wpzoom'), $wpz_theme_name, $license_status, '<strong><a href="' . $this->get_renewal_link() . '">', '</a></strong>' );
            echo '</div>';
        } elseif ( $license_status == 'invalid' ) {
            echo '<div id="update-nag" class="notice notice-warning settings-error">';
            echo sprintf( __('If you want to receive updates for <strong>%s theme</strong>, please %senter your license key%s or %spurchase a new license%s if you don\'t have one yet.', 'wpzoom'), $wpz_theme_name, '<strong><a href="admin.php?page=wpzoom-license">', '</a></strong>', '<strong><a href="https://www.wpzoom.com/themes/' . WPZOOM::$theme_raw_name . '/" target="_blank">', '</a></strong>' );
            echo '</div>';
        } elseif ( $license_status == 'inactive'  || $license_status == 'site_inactive' ) {
            echo '<div id="update-nag" class="notice notice-warning settings-error">';
            echo sprintf( __('Your <strong>%s theme</strong> license key is <strong>inactive</strong>. Please %sgo to this page%s and click the <strong>Activate License</strong> button to enable theme updates', 'wpzoom'), $wpz_theme_name, '<strong><a href="admin.php?page=wpzoom-license">', '</a></strong>' );
            echo '</div>';
        } elseif ( $license_status == 'item_name_mismatch' ) {
            echo '<div id="update-nag" class="notice notice-warning settings-error">';
            echo sprintf( __('You have entered an incorrect license key for <strong>%s theme</strong>. Please %senter a correct key%s or %spurchase a new license%s for theme you\'re currently using.', 'wpzoom'), $wpz_theme_name, '<strong><a href="admin.php?page=wpzoom-license">', '</a></strong>', '<strong><a href="https://www.wpzoom.com/themes/' . WPZOOM::$theme_raw_name . '/" target="_blank">', '</a></strong>' );
            echo '</div>';
        } elseif ( $license_status == 'disabled' ) {
            echo '<div id="update-nag" class="notice notice-warning settings-error">';
            echo sprintf( __('Your <strong>%s theme</strong> license key is <strong>%s</strong>, please %scheck the status%s of your license or get in touch with the WPZOOM team for more details.', 'wpzoom'), $wpz_theme_name, $license_status, '<strong><a href="' . $this->get_renewal_link() . '">', '</a></strong>' );
            echo '</div>';
        } elseif ( $license_status != 'valid' ) {
            echo '<div id="update-nag" class="notice notice-warning settings-error">';
            echo sprintf( __('Your <strong>%s theme</strong> license is <strong>%s</strong>, please %sactivate your license key%s to enable theme updates', 'wpzoom'), $wpz_theme_name, $license_status, '<strong><a href="' . $this->get_renewal_link() . '">', '</a></strong>' );
            echo '</div>';
        }
    }

    /**
     * Enqueue the admin styles
     */
    function admin_styles()
    {
        wp_enqueue_style('wpzoom-theme-admin-style', WPZOOM::get_root_uri() . '/components/theme-updater/admin.css');
    }

    /**
     * Creates the updater class.
     *
     * since 1.0.0
     */
    function updater()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        /* If there is no valid license key status, don't allow updates. */
        if (get_option($this->theme_slug . '_license_key_status', false) != 'valid') {
            return;
        }

        if (!class_exists('EDD_Theme_Updater')) {
            // Load our custom theme updater
            include(dirname(__FILE__) . '/theme-updater-class.php');
        }

        new EDD_Theme_Updater(
            array(
                'remote_api_url' => $this->remote_api_url,
                'version' => $this->version,
                'license' => trim(get_option($this->theme_slug . '_license_key')),
                'item_name' => $this->item_name,
                'author' => $this->author
            ),
            $this->strings
        );
    }

    /**
     * Adds a menu item for the theme license under the appearance menu.
     *
     * since 1.0.0
     */
    function license_menu()
    {

        $strings = $this->strings;

        add_submenu_page(
            'wpzoom_options',
            __('Theme License + Quick Start', 'wpzoom'),//$strings['theme-license'],
            __('Theme License + Quick Start', 'wpzoom'),//$strings['theme-license'],
            'manage_options',
            'wpzoom-license',
            array($this, 'license_page')
        );
    }

    /**
     * Outputs the markup used on the theme license page.
     *
     * since 1.0.0
     */
    function license_page()
    {

        $strings = $this->strings;

        $license = trim(get_option($this->theme_slug . '_license_key'));
        $status = get_option($this->theme_slug . '_license_key_status', false);

        $wpz_theme_name = wp_get_theme(get_template());

        // Checks license status to display under license key
        if (!$license) {
            $message = $strings['enter-key'];
        } else {
            // delete_transient( $this->theme_slug . '_license_message' );
            if (!get_transient($this->theme_slug . '_license_message', false)) {
                set_transient($this->theme_slug . '_license_message', $this->check_license(), (60 * 60 * 24));
            }
            $message = get_transient($this->theme_slug . '_license_message');
        }
        ?>

        <div class="cols-wrap">

            <div class="wpz_right-col">

                  <div class="license-wrap wpz_license_column">

                    <h2 class="headline"><?php _e( 'Activate Your License Key', 'wpzoom' ); ?></h2>

                    <p>
                        <?php echo sprintf( __( 'Enter your license key to enable <strong>1-click theme updates</strong>. You can find your license in <a href="https://www.wpzoom.com/account/licenses/" target="_blank">WPZOOM Members Area &rarr; Licenses</a>.', 'wpzoom' ) );
                         ?>
                    </p>

                    <form method="post" action="options.php">
                        <?php settings_fields( $this->theme_slug . '-license' ); ?>
                        <h3 class="license-key-label"><?php echo esc_html($strings['license-key']); ?></h3>
                        <div>
                            <input id="<?php echo esc_attr($this->theme_slug.'_license_key'); ?>" name="<?php echo esc_attr($this->theme_slug.'_license_key'); ?>" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
                        </div>


                        <?php

                        if ($status == 'invalid') { ?>
                            <div style="background: #fdf0f0;margin:8px 15px 15px 0; border:2px solid #e49e9e; border-radius:3px;" class="error settings-error notice"><p><?php echo $message; ?></p>
                           </div>
                       <?php } elseif ($status == 'expired') { ?>

                             <div style="background: #fdf0f0;margin:8px 15px 15px 0; border:2px solid #e49e9e; border-radius:3px;" class="error settings-error notice"><p><strong>  <?php echo $message; ?></strong></p>
                            </div>

                        <?php } else { ?>

                        <p class="description"><?php echo $message; ?></p>

                        <?php } ?>



                        <?php submit_button( 'Save License Key' );

                            if ( $license ) {
                                wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );

                                if ( 'valid' == $status ) { ?>
                                <input type="submit" class="button-secondary" name="<?php esc_attr_e($this->theme_slug.'_license_deactivate'); ?>" value="<?php esc_attr_e( $strings['deactivate-license'] );  ?>"/>
                                <?php } else { ?>
                                <input type="submit" class="button-secondary cta-button" name="<?php esc_attr_e($this->theme_slug.'_license_activate'); ?>" value="<?php esc_attr_e( $strings['activate-license'] ); ?>"/>
                                <?php
                                       }
                                    }
                                  ?>
                    </form>

                </div>


                <div class="license-wrap">

                    <h2 class="headline"><?php _e('Follow @WPZOOM', 'wpzoom'); ?></h2>

                    <iframe
                        src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Fwpzoom&width=84&layout=button_count&action=like&show_faces=false&share=false&height=21&appId=610643215638351"
                        width="84" height="21" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                        allowTransparency="true"></iframe>

                    <br/>
                    <br/>

                    <a href="https://twitter.com/wpzoom" class="twitter-follow-button">Follow @wpzoom</a>
                    <script>!function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = p + '://platform.twitter.com/widgets.js';
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, 'script', 'twitter-wjs');</script>

                </div>

                <div class="video_channel">

                    <h4><?php _e('New to WordPress?', 'wpzoom'); ?></h4>

                    <div class="video_channel_promo">

                        <a href="https://www.wpzoom.com/video/" target="_blank"><img
                                src="https://www.wpzoom.com/wp-content/themes/wpzoom/images/video_thumb.png"
                                alt="WordPress Video Tutorials"></a>

                    </div>

                    <h2 class="headline"><?php _e('WordPress 101 Video Tutorials', 'wpzoom'); ?></h2>

                    <p><?php _e('<strong>20+ video tutorials</strong> that cover everything you need to know to get started using WordPress.', 'wpzoom'); ?></p>

                    <a class="cta-button" href="https://www.wpzoom.com/video/" target="_blank"><?php _e('Learn WordPress Today &raquo;', 'wpzoom'); ?></a>

                    <div class="clear"></div>
                </div>


            </div>


            <div class="theme-guide">

                <h2 class="headline"><?php echo sprintf(__('Getting Started with %s', 'wpzoom'), $wpz_theme_name); ?></h2>

                <p class="description"><?php _e('Just installed a new theme and don\'t know where to start? We\'re here to help!', 'wpzoom'); ?></p>

                    <ol>

                        <?php foreach ($this->guide_data as $guide_line): ?>
                        <li>
                            <?php if (!empty($guide_line['header'])): ?>
                                <h3><?php echo force_balance_tags( $guide_line['header'] ) ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($guide_line['content'])): ?>
                                <p><?php echo force_balance_tags( $guide_line['content'] ) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($guide_line['actions'])): ?>
                                <?php foreach ((array)$guide_line['actions'] as $action) {
                                    echo force_balance_tags($action);
                                } ?>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ol>

            </div>

        </div>


        <?php
    }

    /**
     * Registers the option used to store the license key in the options table.
     *
     * since 1.0.0
     */
    function register_option()
    {
        register_setting(
            $this->theme_slug . '-license',
            $this->theme_slug . '_license_key',
            array($this, 'sanitize_license')
        );
    }

    /**
     * Sanitizes the license key.
     *
     * since 1.0.0
     *
     * @param string $new License key that was submitted.
     * @return string $new Sanitized license key.
     */
    function sanitize_license($new)
    {

        $old = get_option($this->theme_slug . '_license_key');

        if ($old && $old != $new) {
            // New license has been entered, so must reactivate
            delete_option($this->theme_slug . '_license_key_status');
            delete_transient($this->theme_slug . '_license_message');
        }

        return $new;
    }

    /**
     * Makes a call to the API.
     *
     * @since 1.0.0
     *
     * @param array $api_params to be used for wp_remote_get.
     * @return array $response decoded JSON response.
     */
     function get_api_response( $api_params ) {

        // Call the custom API.
        $verify_ssl = (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
        $response   = wp_remote_post( $this->remote_api_url, array( 'timeout' => 30, 'sslverify' => $verify_ssl, 'body' => $api_params ) );

        // Make sure the response came back okay.
        if ( is_wp_error( $response ) ) {
            wp_die( $response->get_error_message(), sprintf(__( 'Error %s', 'wpzoom' ), $response->get_error_code()) );
        }

        return $response;
     }

    /**
     * Activates the license key.
     *
     * @since 1.0.0
     */
    function activate_license() {

        $license = trim( get_option( $this->theme_slug . '_license_key' ) );

        // Data to send in our API request.
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license,
            'item_name'  => urlencode( $this->item_name ),
            'url'        => home_url()
        );

        $response = $this->get_api_response( $api_params );

        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.', 'wpzoom' );
            }

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( false === $license_data->success ) {

                switch( $license_data->error ) {

                    case 'expired' :

                        $message = sprintf(
                            __( 'Your license key expired on %s.', 'wpzoom' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                        );
                        break;

                    case 'disabled':
                    case 'revoked' :

                        $message = __( 'Your license key has been disabled.', 'wpzoom' );
                        break;

                    case 'missing' :

                        $message = __( 'Invalid license.', 'wpzoom' );
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __( 'Your license is not active for this URL.', 'wpzoom' );
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf( __( 'This appears to be an invalid license key for %s.', 'wpzoom' ), $this->item_name );
                        break;

                    case 'no_activations_left':

                        $message = sprintf(__( '<strong>Your theme license key has reached its activation limit. Please %supgrade%s your license or contact WPZOOM if you have an unlimited license.</strong>', 'wpzoom' ), '<a href="' . esc_url( 'https://www.wpzoom.com/account/licenses/') .'" target="_blank">', '</a>' );
                        break;

                    default :

                        $message = __( 'An error occurred, please try again.', 'wpzoom' );
                        break;
                }

                if ( ! empty( $message ) ) {
                    $base_url = admin_url( 'admin.php?page=wpzoom-license' );
                    $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

                    wp_redirect( $redirect );
                    exit();
                }

            }

        }

        // $response->license will be either "active" or "inactive"
        if ( $license_data && isset( $license_data->license ) ) {
            update_option( $this->theme_slug . '_license_key_status', $license_data->license );
            delete_transient( $this->theme_slug . '_license_message' );
        }

        wp_redirect( admin_url( 'admin.php?page=wpzoom-license' ) );
        exit();

    }

   /**
     * Deactivates the license key.
     *
     * @since 1.0.0
     */
    function deactivate_license() {

        // Retrieve the license from the database.
        $license = trim( get_option( $this->theme_slug . '_license_key' ) );

        // Data to send in our API request.
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => $license,
            'item_name'  => urlencode( $this->item_name ),
            'url'        => home_url()
        );

        $response = $this->get_api_response( $api_params );

        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.', 'wpzoom' );
            }

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "deactivated" or "failed"
            if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
                delete_option( $this->theme_slug . '_license_key_status' );
                delete_transient( $this->theme_slug . '_license_message' );
            }

        }

        if ( ! empty( $message ) ) {
            $base_url = admin_url( 'admin.php?page=wpzoom-license' );
            $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

            wp_redirect( $redirect );
            exit();
        }

        wp_redirect( admin_url( 'admin.php?page=wpzoom-license' ) );
        exit();

    }

    /**
     * Constructs a renewal link
     *
     * @since 1.0.0
     */
    function get_renewal_link()
    {

        // If a renewal link was passed in the config, use that
        if ('' != $this->renew_url) {
            return $this->renew_url;
        }

        // If download_id was passed in the config, a renewal link can be constructed
        $license_key = trim(get_option($this->theme_slug . '_license_key', false));
        if ('' != $this->download_id && $license_key) {
            $url = esc_url($this->remote_api_url);
            $url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
            return $url;
        }

        // Otherwise return the remote_api_url
        return $this->remote_api_url;

    }


    /**
     * Checks if a license action was submitted.
     *
     * @since 1.0.0
     */
    function license_action()
    {

        if (isset($_POST[$this->theme_slug . '_license_activate'])) {
            if (check_admin_referer($this->theme_slug . '_nonce', $this->theme_slug . '_nonce')) {
                $this->activate_license();
            }
        }

        if (isset($_POST[$this->theme_slug . '_license_deactivate'])) {
            if (check_admin_referer($this->theme_slug . '_nonce', $this->theme_slug . '_nonce')) {
                $this->deactivate_license();
            }
        }

    }

    /**
         * Checks if license is valid and gets expire date.
         *
         * @since 1.0.0
         *
         * @return string $message License status message.
         */
        function check_license() {

            $license = trim( get_option( $this->theme_slug . '_license_key' ) );
            $strings = $this->strings;

            $api_params = array(
                'edd_action' => 'check_license',
                'license'    => $license,
                'item_name'  => urlencode( $this->item_name ),
                'url'        => home_url()
            );

            $response = $this->get_api_response( $api_params );

            // make sure the response came back okay
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

                if ( is_wp_error( $response ) ) {
                    $message = $response->get_error_message();
                } else {
                    $message = $strings['license-status-unknown'];
                }

            } else {

                $license_data = json_decode( wp_remote_retrieve_body( $response ) );

                // If response doesn't include license data, return
                if ( !isset( $license_data->license ) ) {
                    $message = $strings['license-status-unknown'];
                    return $message;
                }

                // We need to update the license status at the same time the message is updated
                if ( $license_data && isset( $license_data->license ) ) {
                    update_option( $this->theme_slug . '_license_key_status', $license_data->license );
                }

                // Get expire date
                $expires = false;
                if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
                    $expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
                    $renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
                } elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
                    $expires = 'lifetime';
                }

                // Get site counts
                $site_count = isset($license_data->site_count)? $license_data->site_count : 0;
                $license_limit = isset($license_data->license_limit) ? $license_data->license_limit : 0;


                // If unlimited
                if ( 0 == $license_limit ) {
                    $license_limit = $strings['unlimited'];
                }

                if ( $license_data->license == 'valid' ) {
                    $message = $strings['license-key-is-active'] . ' ';
                    if ( isset( $expires ) && 'lifetime' != $expires ) {
                        $message .= sprintf( $strings['expires%s'], $expires ) . ' ';
                    }
                    if ( isset( $expires ) && 'lifetime' == $expires ) {
                        $message .= $strings['expires-never'];
                    }
                    if ( $site_count && $license_limit ) {
                        $message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
                    }
                } else if ( $license_data->license == 'expired' ) {
                    if ( $expires ) {
                        $message = sprintf( $strings['license-key-expired-%s'], $expires );
                    } else {
                        $message = $strings['license-key-expired'];
                    }
                    if ( $renew_link ) {
                        $message .= ' ' . $renew_link;
                    }
                } else if ( $license_data->license == 'invalid' ) {
                    $message = $strings['license-keys-do-not-match'];
                } else if ( $license_data->license == 'inactive' ) {
                    $message = $strings['license-is-inactive'];
                } else if ( $license_data->license == 'disabled' ) {
                    $message = $strings['license-key-is-disabled'];
                } else if ( $license_data->license == 'site_inactive' ) {
                    // Site is inactive
                    $message = $strings['site-is-inactive'];
                } else {
                    $message = $strings['license-status-unknown'];
                }

            }

            return $message;
        }

    /**
     * Disable requests to wp.org repository for this theme.
     *
     * @since 1.0.0
     */
    function disable_wporg_request($r, $url)
    {

        // If it's not a theme update request, bail.
        if (0 !== strpos($url, 'https://api.wordpress.org/themes/update-check/1.1/')) {
            return $r;
        }

        // Decode the JSON response
        $themes = json_decode($r['body']['themes']);

        // Remove the active parent and child themes from the check
        $parent = get_option('template');
        $child = get_option('stylesheet');
        unset($themes->themes->$parent);
        unset($themes->themes->$child);

        // Encode the updated JSON response
        $r['body']['themes'] = json_encode($themes);

        return $r;
    }

}


/**
 * This is a means of catching errors from the activation method above and displyaing it to the customer
 */
function edd_sample_theme_admin_notices() {
    if ( isset( $_GET['sl_theme_activation'] ) && ! empty( $_GET['message'] ) ) {

        switch( $_GET['sl_theme_activation'] ) {

            case 'false':
                $message = stripslashes(urldecode( $_GET['message'] ));
                ?>
                <div class="error">
                    <p><?php echo $message; ?></p>
                </div>
                <?php
                break;

            case 'true':
            default:

                break;

        }
    }
}
add_action( 'admin_notices', 'edd_sample_theme_admin_notices' );
