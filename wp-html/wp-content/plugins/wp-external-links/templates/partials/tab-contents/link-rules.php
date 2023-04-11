<?php
/**
 * Tab Support
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 *
 * @var array $vars
 *      @option array  "tabs"
 */
?>
    <div id="wpel-link-rules">
    <div class="notice-box-info">
      <b>Link rules (a PRO feature) provide unlimited flexibility</b> in creating rules based on link URL (href) values. Each rule defines how selected links are threated, what their target, rel, icon and other properties will be - <a href="#" class="show-link-rules">see how each rule is defined</a>. For complex sites, with a lot of different rules and exceptions this is a powerful tool that enables full control and modifications of any number of links. <a href="#" class="open-pro-dialog" data-pro-feature="link-rules-banner">Get PRO now</a> to use link rules.
    </div>

    <div id="link-rules-new" style="display: none;">
    <img style="width: 900px;" src="<?php echo esc_url(plugins_url('/public/images/link-rules-new.png', WPEL_Plugin::get_plugin_file())); ?>" alt="New link rule example" title="New link rule example">
    </div>

    <p><a href="#" class="open-pro-dialog" data-pro-feature="link-rules"><img style="max-width: 100%;" src="<?php echo esc_url(plugins_url('/public/images/link-rules.png', WPEL_Plugin::get_plugin_file())); ?>" alt="Link rules example" title="Link rules example"></a></p>

    </div>
