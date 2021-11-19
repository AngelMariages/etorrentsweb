<?php

/**
 * Class WPEL_LinkHero
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_LinkHero extends FWP_Plugin_Base_1x0x0
{
    /**
     * Initialize plugin
     * @param string $plugin_file
     * @param string $plugin_dir
     */
    public $linkhero;
    public $linkhero_api = 'http://api-beta.linkhero.com';
    public static $lh_url = 'https://linkhero.com/wpel/subscribe.php';
    
    protected function init($plugin_file, $plugin_dir)
    {
        parent::init($plugin_file, $plugin_dir);
        add_action('wp_ajax_wpel_run_tool', array($this, 'ajax_tool'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        $this->linkhero = get_option('wpel-linkhero', array('checker' => array(), 'enabled' => false));
    }

    static function is_localhost()
    {
        $whitelist = array('127.0.0.1', '::1');
        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        }
        return false;
    }

    function is_plugin_page()
    {
        $current_screen = get_current_screen();

        if (!empty($current_screen) && $current_screen->id == 'toplevel_page_wpel-settings-page') {
            return true;
        } else {
            return false;
        }
    } // is_plugin_page

    function admin_enqueue_scripts()
    {
        if (!$this->is_plugin_page()) {
            return;
        }

        $plugin_version = get_option('wpel-version');

        wp_enqueue_style(
            'wpel-datatables-style',
            plugins_url('/public/css/jquery.dataTables.min.css', WPEL_Plugin::get_plugin_file()),
            array(),
            $plugin_version
        );

        wp_enqueue_script(
            'wpel-datatables-script',
            plugins_url('/public/js/jquery.dataTables.min.js', WPEL_Plugin::get_plugin_file()),
            array('jquery'),
            $plugin_version,
            true
        );
    }

    function get_sitemap_url()
    {
        return home_url('/sitemap.xml');
    }

    function lh_request_scan()
    {
        $sitemap_url = $this->get_sitemap_url();

        if ($sitemap_url !== false) {
            $request = array(
                'sitemap' => $sitemap_url
            );

            $res = wp_remote_post(
                $this->linkhero_api . '/wpel/add/check_urls',
                array(
                    'sslverify' => false,
                    'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                    'data_format' => 'body',
                    'body' => json_encode($request),
                    'timeout' => 15
                )
            );

            if (!is_wp_error($res)) {
                $res = wp_remote_retrieve_body($res);
                $res = json_decode($res);

                //TODO: Returns as object
                if (!empty($res->success) && $res->success == true && isset($res->result->job_id)) {
                    $this->linkhero['checker']['lastscan'] = time();
                    $this->linkhero['checker']['status'] = 'pending';
                    $this->linkhero['checker']['limit'] = 0;
                    $this->linkhero['checker']['total_pages'] = 0;
                    $this->linkhero['checker']['job_id'] = $res->result->job_id;

                    update_option('wpel-linkhero', $this->linkhero);
                    return true;
                } else {
                    //TODO: Handle error                    
                    return new WP_Error(1, 'An error occured: ' . $res->result);
                }
            } else {
                //TODO: Handle error
                return $res;
            }
        } else {
            return new WP_Error(1, 'No sitemap found for your website');
        }
    }

    function lh_get_results()
    {
        $request['job_id'] = $this->linkhero['checker']['job_id'];

        $res = wp_remote_post(
            $this->linkhero_api . '/wpel/get/check_urls',
            array(
                'sslverify' => false,
                'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                'data_format' => 'body',
                'body' => json_encode($request),
                'timeout' => 15
            )
        );

        if (!is_wp_error($res)) {
            $res = wp_remote_retrieve_body($res);
            $res = json_decode($res, true);

            if (!empty($res['success']) && $res['success'] == true) {
                $this->linkhero['checker']['results'] = $res['result'];
                $this->linkhero['checker']['limit'] = array_key_exists('limit', $res) && (int)$res['limit'] > 0 ? (int)$res['limit'] : 0;
                $this->linkhero['checker']['total_pages'] = array_key_exists('total_pages', $res) && (int)$res['total_pages'] > 0 ? (int)$res['total_pages'] : 0;
                $pending_pages = false;
                foreach ($this->linkhero['checker']['results'] as $page_url => $page) {
                    if (!is_array($page) || $page['status'] != 'finished') {
                        $pending_pages = true;
                        break;
                    }

                    foreach ($page['hrefs'] as $href_url => $href) {
                        if (!array_key_exists('scrape_status', $href) ||  ($href['scrape_status'] != 'finished' && $href['scrape_status'] != 'error')) {
                            $this->linkhero['checker']['results'][$page_url]['status'] = 'pending';
                            $pending_pages = true;
                            break;
                        }
                    }
                }

                if (array_key_exists('results', $this->linkhero['checker']) && count($this->linkhero['checker']['results']) > 0 && !$pending_pages) {
                    $this->linkhero['checker']['status'] = 'finished';
                }
                update_option('wpel-linkhero', $this->linkhero);
            } else {
                //TODO: Handle error
                return new WP_Error(1, 'An error occured: ' . $res->result);
            }
        } else {
            //TODO: Handle error
            return $res;
        }
    }

    function ajax_tool()
    {

        switch ($_REQUEST['tool']) {
            case 'check_links':
                $this->linkhero['enabled'] = true;

                if (isset($_REQUEST['force']) && $_REQUEST['force'] == 'true') {
                    $this->linkhero = array('checker' => array(), 'enabled' => false);
                    delete_option('wpel-linkhero-subscribed');
                    update_option('wpel-linkhero', $this->linkhero);
                    wp_send_json_success();
                }

                if (empty($this->linkhero['checker']) || $this->linkhero['checker'] == false || $this->linkhero['checker']['lastscan'] < (time() - 60 * 60 * 24)) {
                    $res = $this->lh_request_scan();
                    if (is_wp_error($res)) {
                        wp_send_json_error($res->get_error_message());
                    } else {
                        wp_send_json_success(array('status' => 'pending'));
                    }
                } else {
                    //Refresh
                    // TODO: Add some limit to how many times we check status
                    if (!isset($this->linkhero['checker']['job_id']) || empty($this->linkhero['checker']['job_id'])) {
                        wp_send_json_error('No job ID exists');
                    }
                    if ($this->linkhero['checker']['status'] == 'pending') {
                        $this->lh_get_results();
                    }
                    $results = [];

                    foreach ($this->linkhero['checker']['results'] as $page_url => $page) {
                        if(!is_array($page)){
                            $page = json_decode($page);
                        }
                        if(!is_array($page)){
                            $page = [];
                        }

                        $results[$page_url] = $page;

                        $results[$page_url]['links_total'] = 0;
                        $results[$page_url]['links_finished'] = 0;
                        $results[$page_url]['links_error'] = 0;

                        if (array_key_exists('hrefs', $page) && is_array($page['hrefs'])) {
                            $results[$page_url]['links_total'] = count($page['hrefs']);
                            $results[$page_url]['links_finished'] = 0;
                            $results[$page_url]['links_error'] = 0;
                            foreach ($page['hrefs'] as $link_url => $url_result) {
                                if (array_key_exists('scrape_status', $url_result) && $url_result['scrape_status'] == 'finished') {
                                    $results[$page_url]['links_finished']++;
                                } else if (array_key_exists('scrape_status', $url_result) && $url_result['scrape_status'] == 'error') {
                                    $results[$page_url]['links_error']++;
                                }
                            }
                        }
                    }
                    wp_send_json_success(
                        array(
                            'status' => $this->linkhero['checker']['status'],
                            'pages' => $results,
                            'limit' => $this->linkhero['checker']['limit'] > 0 ? $this->linkhero['checker']['limit'] : 0,
                            'total_pages' => $this->linkhero['checker']['total_pages'] > 0 ? $this->linkhero['checker']['total_pages'] : 0
                        )
                    );
                }
                break;
            case 'link_details':
                $page = $_REQUEST['link'];
                $results = [];
                if (array_key_exists($page, $this->linkhero['checker']['results'])) {
                    foreach ($this->linkhero['checker']['results'][$page]['hrefs'] as $href => $details) {
                        $redirects = '';
                        if (array_key_exists('redirects', $details) && count($details['redirects']) > 0) {
                            foreach ($details['redirects'] as $redirect) {
                                $redirects .= '<span class="lh-redirect"><span>' . $redirect['status'] . '</span>' . $redirect['url'] . '</span>';
                            }
                        } else {
                            $redirects = '<i>not redirected</i>';
                        }

                        if (array_key_exists('target', $details) && !empty($details['target'])) {
                            $target = $details['target'];
                        } else {
                            $target = '_self';
                        }
                        
                        $results[] = [
                            ($details['scrape_status'] == 'error' ? '<span title="' . $details['error'] . '" class="wpel_bad dashicons dashicons-editor-unlink"></span><span style="display:none">0</span>' : '<span class="wpel_good dashicons dashicons-admin-links"></span><span style="display:none">1</span>'),
                            '<div class="dt-lh-title">' . 
                            $details['text'] . '<br />' . 
                            '<a href="' . $details['href'] . '" target="_blank">' . $details['href'] . '</a>' .
                            '<a target="_blank" class="wpel-link-locator" href="' . $page . '?wpel-link-highlight=' . urlencode($details['href']) . '" title="Locate link on page"><span class="dashicons dashicons-pressthis"></span></a><br />' .
                            '</div>',
                            isset($details['title'])?$details['title']:'',
                            isset($details['type'])?$details['type']:'',
                            isset($details['rel']) && strlen($details['rel']) > 0 ? $details['rel'] : '<i>none</i>',
                            $target,
                            $redirects,
                        ];
                    }
                }
                echo json_encode(['data' => $results]);
                break;
            case 'subscribed':
                update_option('wpel-linkhero-subscribed', true);
                break;
            default:
                wp_send_json_error('Unknown action');
                break;
        }
        die();
    }
}

/*?>*/
