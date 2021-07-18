<?php return array(

/* Framework Admin Menu */
'menu' => array(
    'child-theme' => array(
        'name' => __('Child Theme', 'wpzoom')
    )
),

/* Framework Admin Options */
'child-theme' => array(
    array(
        'type'  => 'preheader',
        'name'  => __( 'Install Child Theme', 'wpzoom' ),
        'desc'  => array(
            sprintf( '%s', __( 'A WordPress child theme allows you to apply custom code changes to your site. Using a child theme ensures that all your custom changes will not be overwritten even when you update the parent theme.', 'wpzoom' ) )
        ),
    ),
    array(
        'type'  => 'notice',
        'name'  => __( 'Important Notice', 'wpzoom' ),
        'desc'  => sprintf( '<div class="notice notice-info"><strong>%s</strong><p>%s</p></div>', __( 'Important Notice', 'wpzoom' ), __( 'Even though using Child Themes is a recommended method to modify theme files, it’s your responsibility to keep it up-to-date and synced with the parent theme. A major update of the parent theme can easily break your website if you didn’t test your Child Theme against the new version on a local or staging environment before update.', 'wpzoom' ) ),
        'id'    => 'child_theme_notice',
    ),
    array(
        'type'  => 'checkbox',
        'name'  => __( 'Automatically activate Child Theme?', 'wpzoom' ),
        'desc'  => __( 'If you don\'t check this option, you will have a chance to preview the Child Theme on the Appearance > Themes page before activating it. ', 'wpzoom' ),
        'id'    => 'child_theme_auto_activate',
        'std'   => 'off',
    ),
    array(
        'type'  => 'checkbox',
        'name'  => __( 'Copy existing Widgets, Menus and Customizer options to Child Theme?', 'wpzoom' ),
        'desc'  => sprintf( '<strong>%s</strong> %s', __( 'Note:', 'wpzoom' ), __( 'This option replaces the Child Theme\'s existing Widgets, Menus and Customizer options with those from the Parent Theme. You should only need to use this option the first time you install a Child Theme.', 'wpzoom' ) ),
        'id'    => 'child_theme_keep_parent_settings',
        'std'   => 'on',
    ),
    array(
        'type'  => 'button',
        'name'  => __( 'Install Child Theme', 'wpzoom' ),
        'id'    => 'child_theme_install',
        'class' => 'button-primary',
    ),
)

);