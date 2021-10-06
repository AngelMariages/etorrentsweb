(function ($) {
    'use strict';

    /**
     * Document ready (jQuery)
     */
    $(function () {
        var $control;


        var mediaControl = {
            // Initializes a new media manager or returns an existing frame.
            // @see wp.media.featuredImage.frame()
            frame: function () {
                if (this._frame)
                    return this._frame;

                this._frame = wp.media({
                    title: $control.data('title'),
                    library: {
                        type: 'video'
                    },
                    button: {
                        text: $control.data('button')
                    },
                    multiple: false
                });

                this._frame.on('open', this.updateFrame).state('library').on('select', this.select);

                return this._frame;
            },

            select: function () {
                var selection = this.get('selection'),
                    returnProperty = 'url';

                var source = selection.pluck(returnProperty)[0];
                var ext = ( source.lastIndexOf('?') > 0 ) ? source.substring(source.lastIndexOf('.') + 1, source.lastIndexOf('?')) : source.substring(source.lastIndexOf('.') + 1);

                if (ext == 'mp4' || ext == 'webm') {
                    $($control.data('target') + "_" + ext).val(source).change();
                    $('#wpzoom-home-slider-video-bg-insert-media-button').blur();
                } else {
                    alert('Unsupported file extension: ' + ext);
                }
            },

            updateFrame: function () {
                // Do something when the media frame is opened.
            },

            init: function () {
                $('.wpz-upload-video-control').on('click', function (e) {
                    e.preventDefault();

                    $control = $(this).closest('.wp-media-buttons');

                    mediaControl.frame().open();
                });
            }
        };

        mediaControl.init();
    });
})(jQuery);
