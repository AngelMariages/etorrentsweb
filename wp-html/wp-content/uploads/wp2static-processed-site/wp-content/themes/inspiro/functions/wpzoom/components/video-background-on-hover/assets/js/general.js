jQuery(document).ready(function ($) {

    function isVimeoUrl(str) {
        var regexp = /(http|https)?:\/\/(www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|)(\d+)(?:|\/\?)/;

        if (regexp.test(str)) {
            return true;
        }
        else {
            return false;
        }
    };

    function isYoutubeUrl(str) {
        var regexp = /(?:youtube\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\/?\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})/g;

        if (regexp.test(str)) {
            return true;
        }
        else {
            return false;
        }
    };

    function isUrl(str) {
        var regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;

        if (regexp.test(str)) {
            return true;
        }
        else {
            return false;
        }
    };

    var addVideoPlayer = function (src, poster) {
        var player = document.createElement('video');

        if (src) {
            player.src = src;
        }

        player.controls = false;
        player.autoplay = true;
        player.loop = true;
        player.width = 400;
        player.height = 260;
        player.poster = poster;

        return player;
    };

    var addWarningNotice = function (msg) {
        var warningNotice = document.createElement('div');
        warningNotice.classList.add('warning-notice');
        warningNotice.innerHTML = msg;

        return warningNotice;
    };

    var addTrackControls = function (duration, start, btnLabelText, startLabelText, durationLabelText, globalTrackDuration, thumbnailUrl, thumbnailTitle) {
        var durationControl = document.createElement('input');
        var durationLabel = document.createElement('label');
        durationLabel.innerHTML = durationLabelText;
        durationControl.setAttribute('type', 'text');
        durationControl.setAttribute('data-min', 1);
        durationControl.setAttribute('data-from', globalTrackDuration);
        durationControl.setAttribute('data-max', 15);
        durationControl.classList.add('track-control', 'duration-control');
        var startTime = document.createElement('input');
        var startTimeLabel = document.createElement('label');
        startTimeLabel.innerHTML = startLabelText;
        startTime.setAttribute('type', 'text');
        startTime.setAttribute('data-min', 0);
        startTime.setAttribute('data-from', 0);
        startTime.setAttribute('data-max', duration);

        startTime.classList.add('track-control', 'start-time-control');

        var controlsWrapper = document.createElement('div');

        var sendButton = document.createElement('button');
        sendButton.classList.add('button', 'button-primary', 'get-giphy-response');
        sendButton.innerHTML = btnLabelText;

        if (thumbnailUrl) {
            var thumbnail = document.createElement('img');
            thumbnail.classList.add('controls-thumbnail');
            thumbnail.setAttribute('src', thumbnailUrl);
            thumbnail.setAttribute('alt', thumbnailTitle);
            controlsWrapper.appendChild(thumbnail);
        }

        controlsWrapper.appendChild(durationLabel);
        controlsWrapper.appendChild(durationControl);

        if (duration) {
            controlsWrapper.appendChild(startTimeLabel);
            controlsWrapper.appendChild(startTime);
        }

        controlsWrapper.appendChild(sendButton);

        return controlsWrapper;

    }

    var addHtmlLinks = function (videoUrl, imageLink, text, btnLabel) {

        var wrapper = document.createElement('div');
        wrapper.classList.add('thumbnail-wrapper');
        var imgWrapper = document.createElement('div');
        imgWrapper.classList.add('image-div');
        imgWrapper.style.backgroundImage = 'url(' + imageLink + ')';
        imgWrapper.style.backgroundPosition = 'center center';
        var img = document.createElement('img');
        img.src = imageLink;
        img.classList.add('thumbnail');
        var divText = document.createElement('div');
        divText.classList.add('text-div');
        var textNode = document.createElement('div');
        textNode.innerHTML = text;

        var buttonWrapper = document.createElement('div');
        buttonWrapper.classList.add('button-div');

        var button = document.createElement('button');
        button.innerHTML = btnLabel;
        button.classList.add('button', 'button-primary');
        button.setAttribute('data-video-url', videoUrl);

        var thumbnailControlsWrapper = document.createElement('div');
        thumbnailControlsWrapper.classList.add('thumbnail-controls-wrapper');

        //imgWrapper.appendChild(img);
        divText.appendChild(textNode);
        buttonWrapper.appendChild(button);

        wrapper.appendChild(imgWrapper);
        thumbnailControlsWrapper.appendChild(divText);
        thumbnailControlsWrapper.appendChild(buttonWrapper);
        wrapper.appendChild(thumbnailControlsWrapper);

        return wrapper;
    }

    var getSiblingsUrls = function (ignore) {
        var urls = [];
        $('.zoom-tab ').each(function (el, item) {
            var $inputs = $(item).find('input[type=text]');
            $inputs.each(function (el, input) {
                var value = $(input).val();

                if (value !== ignore && (value.indexOf('youtube') !== -1 || value.indexOf('vimeo') !== -1)) {
                    urls.push(value);
                }
            });

        });

        return _.uniq(urls);
    }

    $.fn.giphyInput = function () {
        return this.each(function () {
            var $out = giphy_embed_option_type;

            var $this = $(this);
            var $progressContainer = $this.find('.wpzoom-giphy-progressbar');
            var $giphyFileInput = $this.find('.wpzoom_video_background_giphy_file_id');
            var $giphyErrorTypeInput = $this.find('.wpzoom_video_background_giphy_error_type');
            var $giphyInput = $this.find('.wpzoom_video_background_giphy_url');
            var $done_icon = $giphyInput.closest('.preview-video-input-span').find('.dashicons-yes');
            var $error_icon = $giphyInput.closest('.preview-video-input-span').find('.dashicons-warning');
            var giphyFileId = parseInt($giphyFileInput.val());
            var $setFeaturedImageButton = $this.find('.set-featured-image');
            var $setFeaturedImageWrapper = $this.find('.set-featured-image-wrapper');
            var $thumbnail = $setFeaturedImageWrapper.find('.thumbnail');
            var $reloadBtn = $this.find('.wpzoom-giphy-reload-icon');

            var thumbnailsWrapper = $this.find('.thumbnails-wrapper');

            $setFeaturedImageButton.on('click', function (e) {
                e.preventDefault();
                wp.ajax.post('upload_thumbnail', {
                    'nonce': $out['nonce-upload-thumbnail'],
                    url: $(e.currentTarget).attr('data-thumbnail'),
                    post_id: $('#post_ID').val()
                }).done(function (response) {

                    if (response.data.attachment_id) {

                        $thumbnail.attr('src', response.data.thumbnail_id);
                        var attachment_id = response.data.attachment_id;

                        wp.ajax.post('set-post-thumbnail', {
                            post_id: $('#post_ID').val(),
                            thumbnail_id: attachment_id,
                            _ajax_nonce: $out['nonce-set-featured-image'],
                            cookie: encodeURIComponent(document.cookie)
                        }).always(function (response) {

                            if (response) {
                                wp.data.dispatch('core/editor').editPost({featured_media: attachment_id});
                                $setFeaturedImageButton.text($out['set-featured-button-disabled']);
                                $setFeaturedImageButton.prop('disabled', true);
                            }
                        });
                    }
                });
            });

            var siblingsUrls = getSiblingsUrls($giphyInput.val());

            if (siblingsUrls.length) {

                siblingsUrls.forEach(function (url) {

                    wp.ajax.post('get_thumbnail', {
                        'nonce': $out['nonce-get-thumbnail'],
                        'url': url
                    }).done(function (response) {

                        if (response.data.thumbnail) {
                            var text = '<b>' + response.data.title + '</b> ' + $out['insertTxtNode'];
                            $(thumbnailsWrapper).append(addHtmlLinks(url, response.data.thumbnail, text, $out['insertBtnLabel']));
                            thumbnailsWrapper.find('button').on('click', function (e) {
                                e.preventDefault();
                                var videoUrl = $(e.currentTarget).attr('data-video-url');
                                $giphyInput.val(videoUrl).trigger("input");
                                $(e.currentTarget).closest('.thumbnail-wrapper').fadeOut(1600, "linear", function () {
                                    $(this).remove();
                                });

                            });
                        }
                    });
                });

            }


            if ($progressContainer.length) {
                var bar = new ProgressBar.Line('.wpzoom-giphy-progressbar', {
                    strokeWidth: 1,
                    easing: 'easeInOut',
                    duration: 1400,
                    color: '#FFEA82',
                    trailColor: '#eee',
                    trailWidth: 1,
                    svgStyle: {width: '100%', height: '100%'},
                    from: {color: '#FFEA82'},
                    to: {color: '#46B450'},
                    step: (state, bar) => {
                        bar.path.setAttribute('stroke', state.color);
                    }
                });
            }

            if (giphyFileId) {

                $done_icon.show();
                $progressContainer.show();
                bar.animate(1.0);

            } else if ($giphyInput.val()) {

                $error_icon.show();
                $progressContainer.show();
                bar.animate(1.0, {
                    from: {color: '#FFEA82'},
                    to: {color: '#DC3232'}
                }, function(){
                    if($giphyErrorTypeInput.val()){
                        $giphyInput.closest('.preview-video-input-span').after(addWarningNotice($out['error-messages'][$giphyErrorTypeInput.val()]));
                    }
                });

            }

            $reloadBtn.on('click', function (e) {
                e.preventDefault();
                $giphyInput.trigger('input');
            });

            $giphyInput.on('input', _.debounce(function (e) {
                e.preventDefault();


                var $target = $(e.currentTarget);
                var $preloader = $target.closest('.preview-video-input-span').find('.wpzoom-preloader');
                var $done_icon = $target.closest('.preview-video-input-span').find('.dashicons-yes');
                var $error_icon = $target.closest('.preview-video-input-span').find('.dashicons-warning');
                var $track_controls = $target.closest('.wpzoom_giphy').find('.track-controls');
                var $attachment_wrapper = $target.closest('.wpzoom_giphy').find('.wpzoom-attachment-wrapper');

                $target.closest('.wpzoom_giphy').find('.warning-notice').remove();

                $giphyErrorTypeInput.val('');

                var giphy_url = $target.val();
                var firstCallback = {
                    callback: 'get_track_duration',
                    'args': {
                        'nonce': $out['nonce-get-track-duration'],
                        'url': giphy_url
                    }
                };
                $target.prop('disabled', true);
                $done_icon.hide();
                $error_icon.hide();
                $reloadBtn.hide();
                bar.set(0);
                $progressContainer.show();

                var counter = 0;
                var recursiveAjax = function (counter, data) {

                    var callbackStack = [
                        {
                            'callback': 'get_trimmed_url',
                            'args': {
                                beforeSend: function () {
                                    if ($preloader.length) {
                                        $preloader.show();
                                    }
                                    if ($done_icon.length) {
                                        $done_icon.hide();
                                    }

                                    if ($error_icon.length) {
                                        $error_icon.hide();
                                    }
                                },
                                'nonce': $out['nonce-get-trimmed-url'],
                                'url': giphy_url
                            },

                        },
                        {
                            'callback': 'upload_to_giphy',
                            'args': {
                                'nonce': $out['nonce-upload-to-giphy']

                            }
                        },
                        {
                            'callback': 'get_giphy_data_by_id',
                            args: {
                                'nonce': $out['nonce-get-giphy-data-by-id']

                            }
                        },
                        {
                            'callback': 'upload_to_media_library',
                            args: {
                                'nonce': $out['nonce-upload-to-media-library'],
                                post_id: $('#post_ID').val()
                            }
                        }
                    ];

                    var current = callbackStack[counter];

                    if (!_.isEmpty(data)) {
                        current.args = _.extend(current.args, data);
                    }

                    wp.ajax.post(current.callback, current.args).done(
                        function (response) {
                            counter = counter + 1;
                            bar.animate(counter / (callbackStack.length));
                            if (counter < callbackStack.length) {

                                recursiveAjax(counter, response.data);

                            } else {
                                $preloader.hide();
                                $done_icon.show();
                                $reloadBtn.show();
                                $target.removeAttr('disabled');

                                if (response.data.hasOwnProperty('media_file_id')) {
                                    $giphyFileInput.val(response.data.media_file_id);


                                    $attachment_wrapper.html(addVideoPlayer(response.data.attachment_url, $out['video-poster']));

                                    $track_controls.fadeOut(800, "linear", function () {
                                        $attachment_wrapper.fadeIn(800, "linear");
                                    });

                                }
                            }

                        }
                    ).fail(
                        function () {
                            bar.animate(1.0, {
                                from: {color: '#FFEA82'},
                                to: {color: '#DC3232'}
                            }, function () {
                                $preloader.hide();
                                $error_icon.show();
                                $reloadBtn.show();
                                $target.removeAttr('disabled');
                                $giphyFileInput.val(0);
                                $giphyErrorTypeInput.val('request-fail');
                                $target.closest('.preview-video-input-span').after(addWarningNotice($out['error-messages']['request-fail']));
                            });
                        }
                    );
                };

                if (isUrl(giphy_url) && (isVimeoUrl(giphy_url) || isYoutubeUrl(giphy_url))) {

                    wp.ajax.post(firstCallback.callback, firstCallback.args).done(function (response) {

                        if (!_.isUndefined(response.fail) && response.fail) {
                            $progressContainer.hide();
                            $error_icon.show();
                            $target.removeAttr('disabled');
                            $reloadBtn.show();

                            $giphyFileInput.val(0);
                            $giphyErrorTypeInput.val(response.error_type);
                            $target.closest('.preview-video-input-span').after(addWarningNotice(response.message));
                            return;
                        }

                        var thumbnail = _.isUndefined(response.data.thumbnail.url) ? '' : response.data.thumbnail.url;
                        var title = _.isUndefined(response.data.thumbnail.title) ? '' : response.data.thumbnail.title;
                        $($track_controls).html(addTrackControls(parseInt(response.data.duration), 1, $out['generate-video-label'], $out['generate-start-time-label'], $out['generate-duration-label'], $out['global-track-duration'], thumbnail, title));

                        $attachment_wrapper.fadeOut(800, "linear", function () {
                            $track_controls.fadeIn(800, "linear");
                        });

                        $($track_controls).find('.track-control').ionRangeSlider({
                            skin: "round",
                            prettify: function (seconds) {
                                return moment.duration(seconds, 'seconds').format("mm:ss", {trim: false});
                            }
                        });

                        $($track_controls).find('.get-giphy-response').on('click', function (e) {
                            e.preventDefault();

                            var startAt = $track_controls.find('.start-time-control').val();
                            var duration = $track_controls.find('.duration-control').val();

                            $(e.currentTarget).prop('disabled', true);

                            recursiveAjax(counter, {duration: duration, start: startAt});

                        });

                    });


                } else if (giphy_url) {
                    bar.animate(1.0, {
                        from: {color: '#FFEA82'},
                        to: {color: '#DC3232'}
                    }, function () {
                        $target.closest('.preview-video-input-span').after(addWarningNotice($out['error-messages']['invalid-url']));
                    });
                    $done_icon.hide();
                    $reloadBtn.show();
                    $preloader.hide();
                    $giphyFileInput.val(0);
                    $giphyErrorTypeInput.val('invalid-url');
                    $error_icon.show();
                    $target.removeAttr('disabled');
                } else {
                    $done_icon.hide();
                    $preloader.hide();
                    $error_icon.hide();
                    $progressContainer.hide();
                    $target.removeAttr('disabled');
                }

            }, 700));
        });
    };

    $.fn.mediaControl = function () {

        this.each(function () {
            var $this = $(this);
            var $wasCalled = $this.find('a.wpz-upload-video-control');

            var mediaControl = {
                // Initializes a new media manager or returns an existing frame.
                // @see wp.media.featuredImage.frame()
                frame: function () {
                    if (this._frame)
                        return this._frame;

                    this._frame = wp.media({
                        title: $this.data('title'),
                        library: {
                            type: 'video'
                        },
                        button: {
                            text: $this.data('button')
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
                        $($this.data('target') + "_" + ext).focus().val(source).change();
                    } else {
                        alert('Unsupported file extension: ' + ext);
                    }
                },

                updateFrame: function () {
                    // Do something when the media frame is opened.
                },

                init: function () {
                    $this.find('a.add_media').on('click', function (e) {
                        e.preventDefault();
                        mediaControl.frame().open();
                    });
                }
            };

            if ($wasCalled.length == 0) {
                mediaControl.init();
            }

        });
    };

    $('.wpzoom_giphy').giphyInput();
    $('.wp-media-buttons').mediaControl();

});