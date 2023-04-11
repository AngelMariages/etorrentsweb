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
 * @option array  "tabs"
 */
?>
<h3><?php esc_html_e( 'Exclude or include by data-attribute', 'wp-external-links' ) ?></h3>
<p>
    <?php WPEL_Plugin::wp_kses_wf(__('The <code>data-wpel-link</code> attribute can be set on links and forces the plugin to treat those links that way.', 'wp-external-links' )); ?>
</p>
<ul>
    <li><?php WPEL_Plugin::wp_kses_wf(__('Links with <code>data-wpel-link="internal"</code> will be treated as internal links.', 'wp-external-links' )); ?></li>
    <li><?php WPEL_Plugin::wp_kses_wf(__('Links with <code>data-wpel-link="external"</code> will be treated as external links.', 'wp-external-links' )); ?></li>
    <li><?php WPEL_Plugin::wp_kses_wf(__('Links with <code>data-wpel-link="exclude"</code> will be treated as excluded links (which have their own settings or will be treated as internal links).', 'wp-external-links' )); ?></li>
    <li><?php WPEL_Plugin::wp_kses_wf(__('Links with <code>data-wpel-link="ignore"</code> will be completely ignored by this plugin.', 'wp-external-links' )); ?></li>
</ul>

<h3><?php esc_html_e( 'FAQ', 'wp-external-links' ); ?></h3>
<p><?php WPEL_Plugin::wp_kses_wf(__('On the <a href="https://wordpress.org/plugins/wp-external-links/faq/" target="_blank">FAQ page</a> you can find some additional tips & tricks.', 'wp-external-links' )); ?></p>

<h3><?php esc_html_e( 'Reported issues', 'wp-external-links' ); ?></h3>
<p><?php WPEL_Plugin::wp_kses_wf(__('When you experience problems using this plugin please look if your problem was <a href="https://wordpress.org/support/plugin/wp-external-links" target="_blank">already reported</a>.', 'wp-external-links' )); ?></p>

<h3><?php esc_html_e(__('Send your issue', 'wp-external-links' )); ?></h3>
<p><?php WPEL_Plugin::wp_kses_wf(__('If the issue wasn\'t reported yet then you should <a href="https://wordpress.org/support/plugin/wp-external-links#postform" target="_blank">post your problem</a>. <b>Our average response time is a few hours, and we reply to every message!</b>', 'wp-external-links' )); ?>
    <?php WPEL_Plugin::wp_kses_wf(__( '<br>Make sure you copy/paste the technical information displayed below. Without it we can\'t help you.', 'wp-external-links' )); ?>
</p>
<p>
    <button class="button js-wpel-copy"><?php esc_html_e( 'Copy Technical Info', 'wp-external-links' ); ?></button>
</p>
<p>
<textarea id="plugin-settings" class="large-text js-wpel-copy-target" rows="8" readonly="readonly">
<?php esc_html_e( 'WP URL: ', 'wp-external-links' ); echo esc_html(get_bloginfo( 'wpurl' )); ?>

<?php esc_html_e( 'WP version: ', 'wp-external-links' ); echo esc_html(get_bloginfo( 'version' )); ?>

<?php esc_html_e( 'PHP version: ', 'wp-external-links' ); echo esc_html(phpversion()); ?>

<?php $theme = wp_get_theme(); esc_html_e( 'Active Theme: ', 'wp-external-links' ); echo esc_html($theme->get('Name') . ', version: ' . $theme->get('Version')); ?>

<?php esc_html_e( 'Active Plugins:', 'wp-external-links' ); ?>

<?php
$plugins = get_plugins() ;

foreach ( $plugins as $plugin => $plugin_values ) {
    if ( ! is_plugin_active( $plugin ) ) {
        continue;
    }

    esc_html_e(' - '. $plugin_values[ 'Name' ] .', version: '. $plugin_values[ 'Version' ] . "\n", 'wp-external-links');
}
?>

<?php esc_html_e( 'WPEL Settings:', 'wp-external-links' ); ?>

array(
<?php
foreach ( $vars[ 'tabs' ] as $tab_key => $values ) {
    if ( ! isset( $values[ 'fields' ] ) ) {
        continue;
    }

    $option_values = $values[ 'fields' ]->get_option_values();
    $option_name = $values[ 'fields' ]->get_setting( 'option_name' );

    echo esc_html("'$option_name' => ");
    var_export( $option_values );
    echo esc_html( ",\n");
}
?>
);
</textarea>
</p>
