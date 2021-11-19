/**
 * @package WPZOOM Framework
 *
 * Add highlight widget outline line in Customizer Preview
 *
 * @since 1.8.5
 */

/* global jQuery, wp */
(function (wp, $) {
    'use strict';

    if ( ! wp || ! wp.customize ) { return; }

    var api = wp.customize,
        WPZOOM;


    api.bind('ready', function() {
        var iframeContents = null;

        api.previewer.bind('synced', function() {
            let iframe = $('#customize-preview iframe');
            iframeContents = iframe.contents();

            iframeContents.find('[data-customize-partial-type="widget"]').each( function(index, widget) {
                let $widget = $(widget);
                if ( !$widget.find('.widget__outline').length ) {
                    $widget.prepend('<div class="widget__outline"></div>');
                }
            });
        });

        api.previewer.bind('highlight-widget-control', function(widgetId) {
            showWidgetName(widgetId);
        });

        api.control.bind('change', function(widget) {
            let widgetIdArray = widget.selector.split('_');
            widgetIdArray.splice(0, 1);
            let widgetId = widgetIdArray.join('_');

            onControlChange(widget.selector, widgetId);
        });

        function showWidgetName(widgetId) {
            if (!iframeContents) { return }
            let $widget = iframeContents.find('[data-customize-partial-id="widget['+widgetId+']"]');
            let name = $widget.data('customize-widget-name');
            let $button = $widget.find('.customize-partial-edit-shortcut-button');

            $button.find('span').remove();
            $button.append('<span><strong>Edit</strong>: ' + name + '</span>');
            if ( !$widget.find('.widget__outline').length ) {
                $widget.prepend('<div class="widget__outline"></div>');
            }
        }

        function onControlChange(selector, widgetId) {
            $(selector).on('click', function() {
                let $widget = iframeContents.find('[data-customize-widget-id='+widgetId+']');
                let position = $widget.offset().top;
                position = position - 150;
                iframeContents.find('html, body').animate({
                    'scrollTop': position
                }, 500);
            });
        }
    });

})(wp, jQuery);