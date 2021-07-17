/**
 * WordPress wp-admin wide functionality.
 */

/**
 * Framework .update-nag notification close button.
 */
jQuery(document).ready(function($) {
    $('.zoomfw-core.update-nag .close').click(function() {
        var ask = confirm(
            'This notification will be hidden for the next 72 hours. ' +
            'You can disable it forever from Theme Options > Framework Options ' +
            'by unchecking "Framework Updater Notification" item.'
        );

        if (!ask) return;

        $(this).parent().remove();

        var data = {
            type: 'framework-notification-hide',
            value: 'framework'
        };

        wp.ajax.post('wpzoom_updater', data);
    });

    $('.zoomfw-theme.update-nag .close').click(function() {
        var ask = confirm(
            'This notification will be hidden for the next 72 hours. ' +
            'You can disable it forever from Theme Options > Framework Options ' +
            'by unchecking "Theme Updater Notification" item.'
        );

        if (!ask) return;

        $(this).parent().remove();

        var data = {
            type: 'theme-notification-hide',
            value: 'framework'
        };

        wp.ajax.post('wpzoom_updater', data);
    });

    $('.zoomfw-seo.update-nag .close').click(function() {
        var ask = confirm('This notification will be hidden forever.');

        if (!ask) return;

        $(this).parent().remove();

        var data = {
            type: 'seo-notification-hide',
            value: 'seo'
        };

        wp.ajax.post('wpzoom_updater', data);
    });
});