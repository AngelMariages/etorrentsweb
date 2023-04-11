<?php

/**
 * Tab Exit Confirmation Links
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
 */

echo '<div class="notice-box-info">
<b>Exit Confirmation (a PRO feature) protects your traffic &amp; visitors</b> by alerting them when they\'re about to leave your site. Since you don\'t control content on 3rd party sites it\'s a good practice to warn visitors they\'re leaving the safety of your site. Doing that will also bring you more traffic as visitors will spend more time on your site. <a href="#" class="open-pro-dialog" data-pro-feature="exit-confirmation-banner">Get PRO now</a> to use the Exit Confirmation feature.
</div>';

$default_fields_file = WPEL_Plugin::get_plugin_dir('/templates/partials/tab-contents/fields-default.php');
WPEL_Plugin::show_template($default_fields_file, $vars);

echo '<br><a href="#" class="open-pro-dialog button button-primary" data-pro-feature="exit-confirmation">Save Changes</a>';
