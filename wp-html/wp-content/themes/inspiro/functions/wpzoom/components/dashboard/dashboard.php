<?php
if (wpzoom::$tf) return;

function WPZOOM_Dashboard() {
?>

<div class="table table_theme">
    <p class="sub"><?php _e('Latest Theme', 'wpzoom'); ?></p>

      <div class="thumbnail-chrome">
        <?php
               $current = get_transient( 'wpzoom_dashboard_widget_theme' );
               if ( ! is_object( $current ) || ! isset( $current->data ) ) {
                   $current = new stdClass();
                   $current->lastChecked = 0;
               }

               $time_changed = 24 * HOUR_IN_SECONDS < ( time() - $current->lastChecked );

               if ( $time_changed ) {
                   $response = wp_remote_get( 'https://www.wpzoom.com/frame/latest_theme.html' );

                   if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {
                       $current->data = wp_remote_retrieve_body( $response );
                   }

                   $current->lastChecked = time();

                   set_transient( 'wpzoom_dashboard_widget_theme', $current );
              }

              if ($current) echo force_balance_tags($current->data);
          ?>  
        </div>

    <a href="https://www.wpzoom.com/themes/" target="_blank" alt="<?php _e('Browse our wide selection of WordPress themes to find the right one for you', 'wpzoom'); ?>" class="button button-primary"><?php _e('Browse more &rarr;', 'wpzoom'); ?></a>

</div>


<div class="table table_news">
     <div class="rss-widget">
        <?php
        /**
         * Get RSS Feed(s)
         */

        $items = get_transient('wpzoom_dashboard_widget_news');

        if (!(is_array($items) && count($items))) {
            include_once(ABSPATH . WPINC . '/class-simplepie.php');
            $rss = new SimplePie();
            $rss->set_timeout(5);
            $rss->set_feed_url('https://www.wpzoom.com/feed/');
            $rss->strip_htmltags(array_merge($rss->strip_htmltags, array('h1', 'a', 'img')));
            $rss->enable_cache(false);
            $rss->init();

            $items = $rss->get_items(0, 3);

            $cached = array();
            foreach ($items as $item) {
                $cached[] = array(
                    'url' => $item->get_permalink(),
                    'title' => $item->get_title(),
                    'date' => $item->get_date("d M Y"),
                    'content' => substr(strip_tags($item->get_content()), 0, 150) . "[...]"
                );
            }

            $items = $cached;
            set_transient('wpzoom_dashboard_widget_news', $cached, 60 * 60 * 24);
        }
        ?>

        <ul class="news">
            <?php if (empty($items)) {
                echo '<li>' . __('No items', 'wpzoom') . '</li>';
            } else {
                foreach ($items as $item) {
            ?>

                <li class="post">
                    <span class="rss-date"><?php echo esc_html($item['date']); ?></span>
                    <a href="<?php echo esc_url( $item['url'] ); ?>" class="rsswidget"><?php echo esc_html($item['title']); ?></a>
                  </li>

            <?php
                }
            }
            ?>
        </ul><!-- end of .news -->
    </div>
</div>



<div class="clear">&nbsp;</div>
<?php
}

function wpzoom_dashboard_widgets() {
    wp_add_dashboard_widget('dashboard_wpzoom', __('WPZOOM News', 'wpzoom'), 'WPZOOM_Dashboard');

    wp_enqueue_style('dashboard_wpzoom_stylesheet', WPZOOM::$assetsPath . '/css/dashboard.css');

    // Globalize the metaboxes array, this holds all the widgets for wp-admin
    global $wp_meta_boxes;

    // Get the regular dashboard widgets array
    // (which has our new widget already but at the end)

    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    // Backup and delete our new dashbaord widget from the end of the array
    $wpzoom_widget_backup = array('dashboard_wpzoom' => $normal_dashboard['dashboard_wpzoom']);
    unset($normal_dashboard['dashboard_wpzoom']);

    // Merge the two arrays together so our widget is at the beginning
    $sorted_dashboard = array_merge($wpzoom_widget_backup, $normal_dashboard);

    // Save the sorted array back into the original metaboxes
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

add_action('wp_dashboard_setup', 'wpzoom_dashboard_widgets');

