<?php
/**
 * Requirements Notice
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
?>
<div class="notice notice-error is-dismissible">
    <p>
        <?php WPEL_Plugin::wp_kses_wf(__('The plugin <strong>WP External Links</strong> requires'
                    .' PHP version 5.3 or up and WordPress version 3.6 or up.'
                    .'<br>Please upgrade your PHP and/or WordPress.'
                    .' Deactivate the plugin to remove this message.', 'wp-external-links' ));
        ?>
    </p>
</div>
