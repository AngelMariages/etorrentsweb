jQuery(document).ready(function ($) {

    $.fn.wpzoomTabs = function(){

        var savedOrder = this.find('input').val();

        this.tabs({
            active: savedOrder,
            activate: function (event, ui) {
                var $order = ui.newTab.data('tab-order');
                ui.newTab.siblings('input').val($order);
                ui.newTab.siblings('li').find('a').removeClass('active');
                ui.newTab.find('a').addClass('active');
            }
        });
    };

    $('.portfolio-tabs').wpzoomTabs();
    $('.slider-tabs').wpzoomTabs();

    $out = inspiro_embed_option_type;

    $('.radio-switcher input:radio').on('change', function (e) {
        e.preventDefault();

        var $closest = ($(this).closest('.zoom-tab').length > 0) ? $(this).closest('.zoom-tab') : $(this).closest('.inside');
        $closest.find('.switch-wrapper').hide();
        $closest.find('.wpzoom_' + $(this).val()).show();
        $closest.find('.dnt')[$(this).val() === 'vimeo_pro' ? 'show': 'hide']();
    });

    $('.radio-switcher input:radio:checked').trigger('change');

    $('.preview-video-input').on('input', function (e) {
            var $that = $(this);
            var $button = $('<div"><small style="float: left; clear: both; margin-top: 10px;" class="button">This is the Featured Image</small></div>');
            var $preloader = $that.closest('.preview-video-input-span').find('.wpzoom-preloader');

            // if empty value exit from function and remove html from preview
            if (!$.trim($that.val())) {
                $preview_wrapper = $that.closest('.switch-wrapper').find('.wpzoom_video_external_preview').html('');
                return;
            }
            _.debounce(function () {
                wp.ajax.post(
                    'get_oembed_response',
                    {
                        beforeSend: function (xhr) {
                            if ($preloader.length) {
                                $preloader.show();
                            }
                        },
                        '_nonce': $out.nonce,
                        'url': $that.val(),
                        post_id: $('#post_ID').val()
                    }).done(function (data) {
                    var $response_type = $that.data('response-type');
                    var $disable_button = $that.data('disable-button');
                    if (data.featured_response.is_already_featured == true) {
                        $button.children().text($out['text-when-disabled']).addClass('button-disabled');
                    } else {
                        $button.children().text($out['text-when-enabled']).removeClass('button-disabled');
                    }

                    var $fail = '';
                    if (data.response === false) {
                        $button.html('');
                    }

                    if ($disable_button != undefined) {
                        $button.html('');
                    }
                    if (data.response === false || (data.response === true && data.thumbnail === false)) {
                        $fail = $('<div><p class="autothumb_error updated">' + $out.wpzoom_post_embed_info + '</p></div>').html();
                    }

                    var $html = ($response_type != undefined) ? ( $response_type == 'thumb' ? (data.thumbnail ? '<img width="400" style="float: left;" src="' + data.thumbnail + '" />' : $fail) : (data.response ? data.response : $fail) ) : (data.response ? data.response : $fail);
                    var $preview_wrapper = $that.closest('.switch-wrapper').find('.wpzoom_video_external_preview');
                    $preview_wrapper.html('<div style="float: left;">' + $html + $button.html() + '</div>');

                    $preview_wrapper.find('.button').on('click', function () {
                        e.preventDefault();

                        wp.ajax.post(
                            'attach_remote_video_thumb',
                            {
                                '_nonce': $out['nonce-button'],
                                'postid': $('#post_ID').val(),
                                'url': $that.val()
                            }
                        ).done(function (response) {
                            var thumb_id = response.id;

                            wp.ajax.post(
                                'set-post-thumbnail',
                                {
                                    post_id: $('#post_ID').val(),
                                    thumbnail_id: thumb_id,
                                    _ajax_nonce: $out['nonce-featured'],
                                    cookie: encodeURIComponent(document.cookie)
                                }
                            ).always(function (str) {
                                var win = window.dialogArguments || opener || parent || top;

                                if (str != '0') {
                                    win.WPSetThumbnailID(thumb_id);
                                    win.WPSetThumbnailHTML(str);

                                    $preview_wrapper.find('.button').text($out['text-when-disabled']).addClass('button-disabled');
                                }

                            });
                        });
                    });
                }).always(function () {
                    if ($preloader.length) {
                        $preloader.hide();
                    }
                });
            }, 500)();
        }
    ).trigger('input');
});