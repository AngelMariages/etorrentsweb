<?php

class ZOOM_Demo_Importer {

    public $demos;
    public $theme_name;
    public $selected_demo;
    public $imported_demo;
    public $imported_demo_timestamp;


    public function __construct()
    {
        add_action('load-toplevel_page_wpzoom_options', array($this, 'wpzoom_page_options_callback'));

        add_filter('zoom_options', array($this, 'demo_importer_options'));
        add_action('wp_ajax_zoom_demo_importer_flush_transient', array($this, 'flush_transient'));
        add_action('wp_ajax_zoom_demo_importer_set_selected_demo', array($this, 'set_selected_demo'));
        add_action('wp_ajax_zoom_demo_importer_check_existing_demo', array($this, 'check_existing_demo'));


        $demos = get_demos_details();

        $this->demos         = $demos['demos'];
        $this->theme_name    = $demos['theme'];
        $this->selected_demo = $demos['selected'];
        $this->imported_demo = $demos['imported'];
        $this->imported_demo_timestamp = $demos['imported_date'];
    }

    public function demo_importer_options($data)
    {

        $desc = '';

        $desc .= '<div class="clear"></div><p class="description nofloat" style="width:100%; margin-top:20px;">' . __('Click on the <u>Load Demo Content</u> button to load the demo content for this theme. This is useful for seeing how the theme will look when filled with content.<br/><br/>', 'wpzoom') . '</p>';

        $desc .= '<p class="description nofloat" style="width:100%; margin-bottom:20px;">' . sprintf(__('If the importer doesn\'t work for you, try importing the demo content %smanually%s.', 'wpzoom'), '<a href="https://www.wpzoom.com/theme-demo-content/" target="_blank">', '</a>') . '</p>';

        if ( ! empty($this->imported_demo) ) {
            $desc .= '<h3>'. __('Demo Importer History', 'wpzoom') .'</h3>';
            $desc .= '<table class="tg widefat" style="undefined;table-layout: fixed; width: 100%">
                        <tr>
                            <th class="tg-yw4l" width="40%"><strong>' . __('Demo title', 'wpzoom') . '</strong></th>
                            <th class="tg-baqh" width="20%"><strong>' . __('Imported date', 'wpzoom') . '</strong></th>
                            <th class="tg-baqh" width="20%"><strong>' . __('Status', 'wpzoom') . '</strong></th>
                            <th class="tg-baqh" width="20%"><strong>' . __('Actions', 'wpzoom') . '</strong></th>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . zoom_get_beauty_demo_title($this->imported_demo) . '</td>
                            <td class="tg-baqh">' . sprintf( __('%s ago', 'wpzoom'), human_time_diff(gmdate("U", $this->imported_demo_timestamp), current_time('timestamp')) ) . '</td>
                            <td class="tg-baqh"><strong class="good">' . __('Imported', 'wpzoom') . '</strong></td>
                            <td class="tg-baqh"><strong class="bad"><a style="color: inherit" href="#" id="erase-imported-demo">'. __('Erase content', 'wpzoom') .'</a></strong></td>
                        </tr>
                    </table>';
        }

        $desc .= '<div class="clear"></div><div id="wpzoom-demo-content-info"><p>' .
                __("Importing the Demo Content will not delete your current posts, pages or anything else. You can delete the demo content (posts, pages, menus) from your site by clicking <u>Erase content</u>.<br/><br/>
                    The importing process can take up to <strong>5 minutes</strong> or longer depending on your server configuration and the number of images included in the demo. Please do not leave this screen until you see &ldquo;<strong><em>All done!</em></strong>&rdquo; in the box that will appear below.", 'wpzoom') .
                '</p></div>';

        $desc .= '<table class="tg widefat" style="undefined;table-layout: fixed; width: 100%">
                        <colgroup>
                            <col style="width: 245px">
                            <col style="width: 108px">
                            <col style="width: 86px">
                        </colgroup>
                        <tr>
                            <th class="tg-yw4l"><strong>' . __('Server Environment', 'wpzoom') . '</strong></th>
                            <th class="tg-baqh">' . __('Recommended', 'wpzoom') . '</th>
                            <th class="tg-baqh">' . __('Current', 'wpzoom') . '</th>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . __('PHP Maximum Execution Time (seconds)', 'wpzoom') . '</td>
                            <td class="tg-baqh">' . __('300 or greater', 'wpzoom') . '</td>
                            <td class="tg-baqh"><strong class="' . (absint(preg_replace('[^0-9]', '', ini_get("max_execution_time"))) < 300 ? 'bad' : 'good') . '">' . ini_get("max_execution_time") . '</strong></td>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . __('PHP Maximum Upload Filesize', 'wpzoom') . '</td>
                            <td class="tg-baqh">' . __('64<abbr title="Megabytes">M</abbr> or greater', 'wpzoom') . '</td>
                            <td class="tg-baqh"><strong class="' . (absint(preg_replace('[^0-9]', '', ini_get("upload_max_filesize"))) < 64 ? 'bad' : 'good') . '">' . ini_get("upload_max_filesize") . '</strong></td>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . __('PHP Maximum Post Size', 'wpzoom') . '</td>
                            <td class="tg-baqh">' . __('5<abbr title="Megabytes">M</abbr> or greater', 'wpzoom') . '</td>
                            <td class="tg-baqh"><strong class="' . (absint(preg_replace('[^0-9]', '', ini_get("post_max_size"))) < 5 ? 'bad' : 'good') . '">' . ini_get("post_max_size") . '</strong></td>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . __('PHP Maximum Input Time (seconds)', 'wpzoom') . '</td>
                            <td class="tg-baqh">' . __('100 or greater', 'wpzoom') . '</td>
                            <td class="tg-baqh"><strong class="' . (absint(preg_replace('[^0-9]', '', ini_get("max_input_time"))) < 100 ? 'bad' : 'good') . '">' . ini_get("max_input_time") . '</strong></td>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . __('PHP Memory Limit', 'wpzoom') . '</td>
                            <td class="tg-baqh">' . __('256<abbr title="Megabytes">M</abbr> or greater', 'wpzoom') . '</td>
                            <td class="tg-baqh"><strong class="' . (absint(preg_replace('[^0-9]', '', ini_get("memory_limit"))) < 256 ? 'bad' : 'good') . '">' . ini_get("memory_limit") . '</strong></td>
                        </tr>
                        <tr>
                            <td class="tg-yw4l">' . __( 'PHP Version', 'wpzoom' ) . '</td>
                            <td class="tg-baqh">' . __( '7.2 or greater', 'wpzoom' ) . '</td>
                            <td class="tg-baqh"><strong class="' . ( version_compare( phpversion(), '7.2', '<' ) ? 'bad' : 'good' ) . '">' . phpversion() . '</strong></td>
                        </tr>
                    </table>';

        $desc .= '<p class="description" style="width:100%; margin-top:20px;">' . __('If any of these indicators are lower than recommended values, ask your hosting to increase them.', 'wpzoom') . '</p>';


        $data['import-export'][] = array("type" => "preheader", "name" => __("Demo Content", 'wpzoom'));

        $data['import-export'][] = array("name" => __("Load Demo Content", 'wpzoom'),
            "desc" => $desc,
            "id" => "misc_load_demo_content",
            "class" => "button-primary",
            "type" => "button"
        );

        return $data;
    }

    public function wpzoom_page_options_callback()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('zoom-demo-importer', $this->get_js_uri('general.js'), array('jquery', 'underscore'), '1.0.0', true);

        $localize_data = array(
            'theme_raw_name' => WPZOOM::$theme_raw_name,
            'demo_imported' => $this->imported_demo,
            'strings' => array(
                'single_import_txt' => __('Import Demo Content', 'wpzoom'),
                'multiple_import_txt' => __('Select Demo Content', 'wpzoom'),
                'load_demo_content_txt' => __('Load Demo Content', 'wpzoom'),
                'erase_content' => '<i class="fa fa-trash-o"></i> ' . __('Yes, erase content', 'wpzoom'),
                'erase_current_content' => __('Erase current content', 'wpzoom'),
                'continue_import' => __('Continue import', 'wpzoom'),
                'import_anyway' => __('Import anyway', 'wpzoom'),
                'open_theme_setup' => __('Open Theme Setup', 'wpzoom'),
                'cancel_txt' => __('Cancel', 'wpzoom'),
                'warning' => __('Warning!', 'wpzoom'),
                'confirm_alert' => '<div class="popup-modal-message warning-message">
                            <div class="icon-wrap">
                                <span><i class="fa fa-exclamation"></i></span>
                            </div>
                            <p class="description">'. sprintf(__('You have already imported this demo (%s). Are you sure you want to import it again?', 'wpzoom'), zoom_get_beauty_demo_title($this->imported_demo)) .'</p>
                        </div>',
                'erase_alert' => '<div class="popup-modal-message warning-message">
                            <div class="icon-wrap">
                                <span><i class="fa fa-exclamation"></i></span>
                            </div>
                            <p class="description">'. sprintf(__('Are you sure want to delete «%s» demo content?', 'wpzoom'), zoom_get_beauty_demo_title($this->imported_demo)) .'</p>
                            <p class="description"><strong>'. __('Note: This action will not delete your own content, but only imported content!', 'wpzoom') .'</strong></p>
                        </div>',
                'starting_message' => __('Please wait, the process will start in a couple of seconds...', 'wpzoom'),
            )
        );

        if( current_theme_supports('wpz-multiple-demo-importer') ){
            $localize_data['nonce_set_selected_demo'] = wp_create_nonce('set_selected_demo');
            $localize_data['nonce_check_existing_demo'] = wp_create_nonce('check_existing_demo');
            $localize_data['has_multiple_demo'] = true;
        }

        wp_localize_script('zoom-demo-importer', 'zoom_demo_importer', $localize_data);

        add_action('admin_print_footer_scripts', array($this, 'admin_js_templates'));

    }

    public function flush_transient(){
        check_ajax_referer('set_selected_demo', 'nonce_set_selected_demo');

        // Delete theme mod
        remove_theme_mod('wpz_multiple_demo_importer');

        // Delete transient
        $transient_id = 'get_demo_xml_transient_' . $this->theme_name . '_' . $this->selected_demo;
        delete_transient($transient_id);

        // Send json
        wp_send_json_success();
    }

    public function set_selected_demo(){
        check_ajax_referer('set_selected_demo', 'nonce_set_selected_demo');
        set_theme_mod('wpz_multiple_demo_importer', sanitize_text_field($_POST['selected_demo']));
        wp_send_json_success();
    }

    public function check_existing_demo(){
        check_ajax_referer('check_existing_demo', 'nonce_check_existing_demo');

        $response = array(
            'imported' => $this->imported_demo,
            'selected' => $this->selected_demo,
            'message'  => '<div class="popup-modal-message warning-message">
                            <div class="icon-wrap">
                                <span><i class="fa fa-exclamation"></i></span>
                            </div>
                            <p class="description">'. sprintf(__('Do you want to erase previous demo content (%s) and then import selected (%s) one? <strong>After the erase the importing process will start automatically!</strong>', 'wpzoom'), zoom_get_beauty_demo_title($this->imported_demo), zoom_get_beauty_demo_title($this->selected_demo)) .'</p>
                            <p class="description"><strong>'. __('Note: It\'s recommended to Erase current demo content in order to prevent duplication of menus, posts, pages, terms, etc.', 'wpzoom') .'</strong></p>
                        </div>',
        );

        wp_send_json_success($response);
    }

    /**
     * JavaScript templates for back-end widget form.
     */
    public function admin_js_templates()
    {
        ?>
        <script type="text/html" id="tmpl-zoom-demo-importer-modal-list"><?php $this->get_modal_demo_importer_template(); ?></script>
    <?php
    }
        /**
         * Generate modal search template.
         */
    public function get_modal_demo_importer_template(){

        $demos = get_demos_details();
        $classes = array();

        if ( $demos['multiple-demo'] ) {
            printf('<p style="text-align: center;">%s</p>', __('Select the content you want to import:', 'wpzoom'));

            echo '<div class="zoomForms zoomDemoImporter" style="text-align:center;">';
            echo '<div class="wpz_option_container clearfix">';

            if ( !empty($demos['demos']) ) {
                foreach($demos['demos'] as $demo ){

                    $classes['label']['selected'] = ($this->imported_demo == $demo['id'] ? 'RadioSelected' : '');
                    $classes['label']['thumbnail'] = (empty($demo['thumbnail']) ? 'RadioLabelNoimg' : '');

                    echo '<input id="'. esc_attr($demo['name']) .'" type="radio" class="RadioClass" name="demo_importer_select" value="'. $demo['name'] .'" '. checked($this->imported_demo, $demo['id'], false) .'>';

                    echo '<label for="'. esc_attr($demo['name']) .'" class="RadioLabelClass '. implode(' ', $classes['label']) .'" '. ($this->imported_demo == $demo['id'] ? ' data-imported="true"' : 'data-imported="false"') .'>';

                    if ( ! empty($demo['thumbnail']) ) {
                        echo '<img src="'. esc_url($demo['thumbnail']) .'" alt="'. zoom_get_beauty_demo_title($demo['name']) .'" title="'. zoom_get_beauty_demo_title($demo['name']) .'" class="demo-importer-select">';
                    }

                    echo '<h3>'. zoom_get_beauty_demo_title($demo['name']) .'</h3>';

                    $str = $this->imported_demo == $demo['id'] ?  '<i class="fa fa-check" title="'. __('Imported', 'wpzoom') .'"></i>' : '';
                    echo force_balance_tags($str);

                    echo '</label>';

                }
            }

            echo '</div></div>';

        } else {

            printf('<p>%s</p>', __('Are you sure you want to load the demo content?', 'wpzoom'));

        }

        // Check for inactive required plugins
        $tgmpa = $GLOBALS['tgmpa'];
        $tgmpa_plugins = $tgmpa->plugins;
        $inactive_plugins = array();

        foreach ($tgmpa_plugins as $id => $plugin) {
            if ( ! is_plugin_active($plugin['file_path']) ) {
                $inactive_plugins[$id] = $plugin;
            }
        }

        if ( ! empty($inactive_plugins) ) {

            echo '<div style="margin:15px 0 0;padding:15px;color: #858500; background: #ffe; border: 1px solid #D2CBB9;box-shadow: 0 1px 1px rgba(0,0,0,.04);border-radius:3px;">';

            echo '<p style="margin-top: 0"><strong>IMPORTANT</strong>: In order to import the demo content correctly, please activate the following plugins:</p><ul>';

            foreach ($inactive_plugins as $id => $plugin) {
                $required = $plugin['required'] ? __('Required', 'wpzoom') : __('Recommended', 'wpzoom');

                if ( $plugin['required'] ) {
                    echo '<li style="color: #c00"><strong>'. $plugin['name'] .'</strong> <em>'. $required .'</em></li>';
                } else {
                    echo '<li><strong>'. $plugin['name'] .'</strong> <em>'. $required .'</em></li>';
                }
            }

            $text_plural = sprintf( _n( 'Begin activating plugin', 'Begin activating plugins', count($inactive_plugins), 'wpzoom' ), count($inactive_plugins) );

            echo '</ul><a href="'. admin_url('admin.php?page='. $tgmpa->menu) .'" target="_blank">'. $text_plural .'</a></div>';

        }

    }

    public function get_assets_uri($endpoint = '')
    {
        return WPZOOM::$wpzoomPath . '/components/demo-importer/assets/' . $endpoint;
    }


    public function get_js_uri($endpoint = '')
    {
        return $this->get_assets_uri('js/' . $endpoint);
    }

    public static function do_import() {

        require dirname(__FILE__) . '/wordpress-importer/plugin.php';
        require dirname(__FILE__) . '/importer.php';
        require dirname(__FILE__) . '/importer-logger.php';

        $logger = new WPZOOM_Importer_Logger();
        $logger->min_level = 'info';

        $xml_data = get_demo_xml_data();

        if ( $xml_data['remote']['response'] ) {
            $xml_url = $xml_data['remote']['url'];
        } elseif ( $xml_data['local']['response'] ) {
            $xml_url = $xml_data['local']['url'];
        }

        $importer = new WPZOOM_Importer(array('fetch_attachments' => true));
        $importer->set_logger($logger);

        ?><!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <link rel="stylesheet" href="<?php echo WPZOOM::$wpzoomPath; ?>/components/demo-importer/output-iframe.css"
                  type="text/css" media="all"/>
        </head>
        <body>
        <pre><strong><?php _e("Loading demo content&hellip;\n", 'wpzoom'); ?></strong><?php $importer->import( $xml_url ); ?>
            <strong><?php _e('All done!', 'wpzoom'); ?></strong></pre>
        <script type="text/javascript">window.setTimeout(parent.wpzoom_load_demo_content_done, 500);</script>
        </body>
        </html><?php

        return true;

    }

    public static function do_erase() {

        require dirname(__FILE__) . '/wordpress-importer/plugin.php';
        require dirname(__FILE__) . '/importer.php';
        require dirname(__FILE__) . '/importer-logger.php';

        $logger = new WPZOOM_Importer_Logger();
        $logger->min_level = 'info';

        $xml_data = get_demo_xml_data( 'imported' );
        $demos = get_demos_details();

        if ( $xml_data['remote']['response'] ) {
            $xml_url = $xml_data['remote']['url'];
        } elseif ( $xml_data['local']['response'] ) {
            $xml_url = $xml_data['local']['url'];
        }

        // Delete transient
        $transient_id = 'get_demo_xml_transient_' . $demos['theme'] . '_' . $demos['imported'];
        $transient = get_site_transient( $transient_id );

        if ( $transient ) {
            delete_transient( $transient_id );
        }

        $importer = new WPZOOM_Importer(array('fetch_attachments' => true));
        $importer->set_logger($logger);

        ?><!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <link rel="stylesheet" href="<?php echo WPZOOM::$wpzoomPath; ?>/components/demo-importer/output-iframe.css"
                  type="text/css" media="all"/>
        </head>
        <body>
        <pre><strong><?php _e("Deleting demo content&hellip;\n", 'wpzoom'); ?></strong><?php $importer->erase( $xml_url ); ?>
            <strong><?php _e('All done!', 'wpzoom'); ?></strong></pre>
        <script type="text/javascript">window.setTimeout(parent.wpzoom_delete_demo_content_done, 500);</script>
        </body>
        </html><?php

        return true;
    }

}