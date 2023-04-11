<?php

/**
 * Admin Settings
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 *
 * @var array $vars
 *      @option array  "tabs"
 *      @option string "current_tab"
 *      @option string "page_url"
 *      @option string "menu_url"
 *      @option string "own_admin_menu"
 */
?>
<div class="wrap wpel-settings-page wpel-settings-page-<?php echo esc_html($vars['current_tab']); ?>">
    <h1 class="wpel-logo-wrapper">
        <span class="wpel-logo"><img src="<?php echo esc_url(plugins_url('/public/images/logo.png', WPEL_Plugin::get_plugin_file())); ?>" /></span>
    </h1>

    <div class="wpel-body-wrap">
        <?php
        if ($vars['own_admin_menu']) :
            settings_errors();
        endif;

        // nav tabs
        $nav_tabs_template = WPEL_Plugin::get_plugin_dir('/templates/partials/nav-tabs.php');
        WPEL_Plugin::show_template($nav_tabs_template, $vars);
        ?>

        <form method="post" action="options.php" class="wpel-hidden">
            <?php
            $content_tab_template = __DIR__ . '/tab-contents/' . $vars['current_tab'] . '.php';
            $default_tab_template = WPEL_Plugin::get_plugin_dir('/templates/partials/tab-contents/' . $vars['current_tab'] . '.php');

            if (is_readable($content_tab_template)) :
                WPEL_Plugin::show_template($content_tab_template, $vars);
            elseif (is_readable($default_tab_template)) :
                WPEL_Plugin::show_template($default_tab_template, $vars);
            else :
                $content_tab_template = WPEL_Plugin::get_plugin_dir('/templates/partials/tab-contents/fields-default.php');

                if (is_readable($content_tab_template)) :
                    WPEL_Plugin::show_template($content_tab_template, $vars);
                endif;
            endif;
            ?>
        </form>
    </div>
    <div class="wpel-container-right">
        <div class="sidebar-box pro-ad-box">
            <p class="text-center"><a href="#" data-pro-feature="sidebar-box-logo" class="open-pro-dialog">
            <img src="<?php echo esc_url(plugins_url('/public/images/logo.png', WPEL_Plugin::get_plugin_file())); ?>" alt="WP Links PRO" title="WP Links PRO"></a><br>PRO version is here! Grab the launch discount.<br><b>All prices are LIFETIME!</b></p>
            <ul class="plain-list">
                <li>Complete control over all links</li>
                <li>Exit Confirmation for traffic &amp; links protection</li>
                <li>Scan &amp; test all links with one click using our SaaS</li>
                <li>Unlimited custom link rules for any group of links</li>
                <li>Licenses &amp; Sites Manager (remote SaaS dashboard)</li>
                <li>White-label Mode</li>
                <li>Complete Codeless Plugin Rebranding</li>
                <li>Email support from plugin developers</li>
            </ul>

            <p class="text-center"><a href="#" class="open-pro-dialog button button-buy" data-pro-feature="sidebar-box">Get PRO Now</a></p>
        </div>

        <div class="sidebar-box" style="margin-top: 35px;">
          <p>Please <a href="https://wordpress.org/support/plugin/wp-external-links/reviews/#new-post" target="_blank">rate the plugin ★★★★★</a> to <b>keep it up-to-date &amp; maintained</b>. It only takes a second to rate. Thank you! 👋</p>
    </div>
    </div>
</div>

  <div id="wpel-pro-dialog" style="display: none;" title="WP Links PRO is here!"><span class="ui-helper-hidden-accessible"><input type="text"/></span>

  <div class="center logo"><a href="https://getwplinks.com/?ref=wpel-free-pricing-table" target="_blank"><img src="<?php echo esc_url(plugins_url('/public/images/logo.png', WPEL_Plugin::get_plugin_file())); ?>" alt="WP Links PRO" title="WP Links PRO"></a><br>

  <span>Limited PRO Launch Discount - <b>all prices are LIFETIME</b>! Pay once &amp; use forever!</span>
  </div>

  <table id="wpel-pro-table">
  <tr>
  <td class="center">Lifetime Personal License</td>
  <td class="center">Lifetime Team License</td>
  <td class="center">Lifetime Agency License</td>
  </tr>

  <tr class="prices">
  <td class="center"><del>$79 /year</del><br><span>$59</span> <b>/lifetime</b></td>
  <td class="center"><del>$159 /year</del><br><span>$69</span> <b>/lifetime</b></td>
  <td class="center"><del>$299 /year</del><br><span>$119</span> <b>/lifetime</b></td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-yes"></span><b>1 Site License</b></td>
  <td><span class="dashicons dashicons-yes"></span><b>5 Sites License</b></td>
  <td><span class="dashicons dashicons-yes"></span><b>100 Sites License</b></td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-yes"></span>All Plugin Features</td>
  <td><span class="dashicons dashicons-yes"></span>All Plugin Features</td>
  <td><span class="dashicons dashicons-yes"></span>All Plugin Features</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>
  <td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>
  <td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-yes"></span>Link Scanner (300 scan credits)</td>
  <td><span class="dashicons dashicons-yes"></span>Link Scanner (2,000 scan credits)</td>
  <td><span class="dashicons dashicons-yes"></span>Link Scanner (6,000 scan credits)</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-yes"></span>Custom Link Rules</td>
  <td><span class="dashicons dashicons-yes"></span>Custom Link Rules</td>
  <td><span class="dashicons dashicons-yes"></span>Custom Link Rules</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-yes"></span>Exit Confirmation Links Protection</td>
  <td><span class="dashicons dashicons-yes"></span>Exit Confirmation Links Protection</td>
  <td><span class="dashicons dashicons-yes"></span>Exit Confirmation Links Protection</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-no"></span>Licenses &amp; Sites Manager</td>
  <td><span class="dashicons dashicons-yes"></span>Licenses &amp; Sites Manager</td>
  <td><span class="dashicons dashicons-yes"></span>Licenses &amp; Sites Manager</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-no"></span>White-label Mode</td>
  <td><span class="dashicons dashicons-yes"></span>White-label Mode</td>
  <td><span class="dashicons dashicons-yes"></span>White-label Mode</td>
  </tr>

  <tr>
  <td><span class="dashicons dashicons-no"></span>Full Plugin Rebranding</td>
  <td><span class="dashicons dashicons-no"></span>Full Plugin Rebranding</td>
  <td><span class="dashicons dashicons-yes"></span>Full Plugin Rebranding</td>
  </tr>

  <tr>
  <td><a class="button button-buy" data-href-org="https://getwplinks.com/buy/?product=personal-launch&ref=pricing-table" href="https://getwplinks.com/buy/?product=personal-launch&ref=pricing-table" target="_blank">Lifetime License<br>$59 -&gt; BUY NOW</a>
  <br>or <a class="button-buy" data-href-org="https://getwplinks.com/buy/?product=personal-monthly&ref=pricing-table" href="https://getwplinks.com/buy/?product=personal-monthly&ref=pricing-table" target="_blank">only $8.99 <small>/month</small></a></td>
  <td><a class="button button-buy" data-href-org="https://getwplinks.com/buy/?product=team-launch&ref=pricing-table" href="https://getwplinks.com/buy/?product=team-launch&ref=pricing-table" target="_blank">Lifetime License<br>$69 -&gt; BUY NOW</a></td>
  <td><a class="button button-buy" data-href-org="https://getwplinks.com/buy/?product=agency-launch&ref=pricing-table" href="https://getwplinks.com/buy/?product=agency-launch&ref=pricing-table" target="_blank">Lifetime License<br>$119 -&gt; BUY NOW</a></td>
  </tr>

  </table>

  <div class="center footer"><b>100% No-Risk Money Back Guarantee!</b> If you don\'t like the plugin over the next 7 days, we will happily refund 100% of your money. No questions asked! Payments are processed by our merchant of records - <a href="https://paddle.com/" target="_blank">Paddle</a>.</div>
</div>
