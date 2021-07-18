/**
 * Theme demo content import functionality
 */
var wpzoomIframeScrollIntervalID;

(function ($, _, zoomData) {

    var D = $(document),
        B = $('body'),
        W = $(window);

    var _options = {};


    var demoImporter = {
        popup: {
            instance: $.magnificPopup.instance,
            getPopupHtml: function (data) {
                var opts = $.extend({
                    header: false,
                    content: false,
                    controls: false
                }, data);

                var header = opts.header ? '<div class="white-popup-header">' + opts.header + '</div>' : '<div class="white-popup-header no-border"></div>';
                var content = '<div class="white-popup-content">' + (opts.content ? opts.content : '') + '</div>';
                var controls = '<div class="white-popup-controls' + (opts.controls ? '' : 'no-border') + '">' + (opts.controls ? opts.controls : '') + '</div>';

                var html = '<div class="white-popup">' +
                    header +
                    content +
                    controls +
                    '</div>';
                return html;
            },
            getDemoContentHtml : function(){

            },
            getControls: function(data) {
                var out = '';

                $.each(data, function(index, val) {
                    var float = "float" in val ? 'style="float: '+ val.float +'"' : '';

                    if ( val.type == 'success' ) {
                        out += '<button type="button" '+ float +' class="button-primary button-success" id="'+ val.id +'">'+ val.text +'</button>';
                    } else if ( val.type == 'danger' ) {
                        out += '<button type="button" '+ float +' class="button-primary button-danger" id="'+ val.id +'">'+ val.text +'</button>';
                    } else if ( val.type == 'default' ) {
                        out += '<button type="button" '+ float +' class="button-secondary" id="'+ val.id +'">'+ val.text +'</button>';
                    }
                });

                return out;
            },
        },
        updateOptions: function(index, val) {
            _options[index] = val;
        },
        confirmAlert: function(header, content, buttons) {
            buttons = buttons || [{
                                    text: 'OK',
                                    type: 'success',
                                    float: 'right',
                                    id: 'wpz-success-confirm-alert',
                                    callback: function(){
                                        return true;
                                    }
                                }];

            demoImporter.popup.instance.open({
                items: {
                    'src': demoImporter.popup.getPopupHtml({
                        header: header,
                        content: content,
                        controls: demoImporter.popup.getControls(buttons)
                    }),
                    'type': 'inline'
                },
                modal: true
            });

            $.each(buttons, function(index, val) {
                $('#'+val.id).on('click', function(e){

                    demoImporter.popup.instance.close();

                    // Call button function
                    val.callback();
                });
            });

            _.delay(function(){
                demoImporter.popup.instance.content.find('.popup-modal-message').addClass('done');
            }, 50);

            return;
        },
        checkExistingDemo: function() {

            themeSetup.popup.openOnce({data: {message: zoomData.strings.starting_message}});

            $.ajax({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: 'zoom_demo_importer_check_existing_demo',
                    'nonce_check_existing_demo': zoomData.nonce_check_existing_demo
                }
            }).done(function (response) {
                themeSetup.popup.instance.close();

                demoImporter.updateOptions('imported_demo', response.data.imported);
                demoImporter.updateOptions('selected_demo', response.data.selected);

                if ( _options['imported_demo'] != false && _options['imported_demo'] !== _options['selected_demo'] ) {

                    var buttons = [
                        {
                            text: zoomData.strings.erase_current_content,
                            type: 'danger',
                            float: 'right',
                            id: 'wpz-erase-current-demo-content',
                            callback: function(){
                                demoImporter.addIframe('wpzoom_erase_demo_content', 'delete_demo_content');
                                demoImporter.updateOptions('off_trigger', true);
                                return;
                            }
                        },
                        {
                            text: zoomData.strings.continue_import,
                            type: 'success',
                            float: 'right',
                            id: 'wpz-continue-import-demo',
                            callback: function(){
                                B.trigger('demo:importer:select:demo');
                                return;
                            }
                        },
                        {
                            text: zoomData.strings.cancel_txt,
                            type: 'default',
                            float: 'left',
                            id: 'wpz-cancel-import-demo',
                            callback: function(){
                                return;
                            }
                        }
                    ];

                    demoImporter.confirmAlert(zoomData.strings.warning, response.data.message, buttons);

                } else if ( _options['imported_demo'] == _options['selected_demo'] ) {

                    var buttons = [
                        {
                            text: zoomData.strings.import_anyway,
                            type: 'success',
                            float: 'right',
                            id: 'wpz-import-anyway',
                            callback: function(){
                                B.trigger('demo:importer:select:demo');
                                return;
                            }
                        },
                        {
                            text: zoomData.strings.cancel_txt,
                            type: 'default',
                            float: 'left',
                            id: 'wpz-cancel-import',
                            callback: function(){
                                return;
                            }
                        },
                        {
                            text: zoomData.strings.open_theme_setup,
                            type: 'default',
                            float: 'left',
                            id: 'wpz-theme-setup',
                            callback: function(){
                                B.trigger('wpzoom:load_demo_content:done', 'open_theme_setup');
                                return;
                            }
                        }
                    ];

                    demoImporter.confirmAlert(zoomData.strings.warning, zoomData.strings.confirm_alert, buttons);

                } else {

                    demoImporter.popup.instance.close();
                    B.trigger('demo:importer:select:demo');

                }
            });
        },
        openSelectDemoModal: function() {

            if ("has_multiple_demo" in zoomData) {

                demoImporter.popup.instance.open({
                    items: {
                        'src': demoImporter.popup.getPopupHtml({
                            header: zoomData.strings.multiple_import_txt,
                            content: wp.template('zoom-demo-importer-modal-list')(),
                            controls: '<button class="button-primary next-step" id="zoom-load-demo-content">'+ zoomData.strings.load_demo_content_txt +'</button><button id="zoom-cancel-load-demo-content" style="float:left" class="button-secondary">'+ zoomData.strings.cancel_txt +'</button>'
                        }),
                        'type': 'inline'
                    },
                    modal: true,
                    'callbacks' : {
                        afterClose: function(){
                            B.on('demo:importer:select:demo', function(){
                                demoImporter.addIframe('wpzoom_demo_content', 'demo_content');
                            });
                        },
                        close: function() {
                            D.find('.wp-pointer').show();
                        }
                    }
                });

                $('#zoom-cancel-load-demo-content').on('click', function (e) {
                    e.preventDefault();

                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: {
                            action: 'zoom_demo_importer_flush_transient',
                            'nonce_set_selected_demo': zoomData.nonce_set_selected_demo,
                        }
                    });

                    demoImporter.popup.instance.close();
                });

                $('#zoom-load-demo-content').on('click', function (e) {
                    e.preventDefault();

                    var selected = D.find('input[name="demo_importer_select"]:checked').val();
                    demoImporter.zoomData.demo_imported = zoomData.theme_raw_name + '-' + selected;
                    themeSetup.zoomData.demo_imported = zoomData.theme_raw_name + '-' + selected;

                    themeSetup.popup.openOnce({data: {message: zoomData.strings.starting_message}});

                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: {
                            action: 'zoom_demo_importer_set_selected_demo',
                            'nonce_set_selected_demo': zoomData.nonce_set_selected_demo,
                            'selected_demo': selected
                        }
                    }).done(function () {
                        demoImporter.checkExistingDemo();
                    });
                });

            } else {
                
                demoImporter.popup.instance.open({
                    items: {
                        'src': demoImporter.popup.getPopupHtml({
                            header: zoomData.strings.single_import_txt,
                            content: wp.template('zoom-demo-importer-modal-list')(),
                            controls: '<button class="button-primary next-step" id="zoom-load-demo-content">'+ zoomData.strings.load_demo_content_txt +'</button><button id="zoom-cancel-load-demo-content" style="float:left" class="button-secondary">'+ zoomData.strings.cancel_txt +'</button>'
                        }),
                        'type': 'inline'
                    },
                    modal: true,
                    'callbacks' : {
                        afterClose: function(){
                            B.on('import:demo:content', function(){
                                demoImporter.addIframe('wpzoom_demo_content', 'demo_content');
                            });
                        },
                        close: function() {
                            D.find('.wp-pointer').show();
                        }
                    }
                });

                $('#zoom-cancel-load-demo-content').on('click', function (e) {
                    e.preventDefault();
                    demoImporter.popup.instance.close();
                });

                $('#zoom-load-demo-content').on('click', function (e) {
                    e.preventDefault();
                    demoImporter.popup.instance.close();
                    B.trigger('import:demo:content');
                });
            }
        },
        addIframe: function (action, type) {
            $("#wpzoom-demo-content-iframe-wrapper").prev('.clear').addBack().remove();
            $("#misc_load_demo_content").parent().find(".cleaner").before('<div class="clear"></div><div id="wpzoom-demo-content-iframe-wrapper"><span class="spinner"></span><iframe src="' + wpzoom_ajax_url + '?action='+ action +'&type='+ type +'&_ajax_nonce=' + $("#nonce").val() + '" id="wpzoom-demo-content-iframe" /></div>');
            $("html, body").animate({scrollTop: $("#wpzoom-demo-content-iframe-wrapper").offset().top - $("#wpadminbar").outerHeight() - 10}, 1000);

            wpzoomIframeScrollIntervalID = window.setInterval(function () {
                var iframe = $("#wpzoom-demo-content-iframe").contents();
                iframe.scrollTop(iframe.height());
            }, 200);

            $(this).prop("disabled", true);
        }

    }

    D.ready(function() {

        $("#misc_load_demo_content").click(function (e) {
            e.preventDefault();

            D.find('.wp-pointer').hide();

            demoImporter.openSelectDemoModal();
        });

        $('#erase-imported-demo').on('click', function(e) {
            e.preventDefault();

            var buttons = [
                {
                    text: zoomData.strings.erase_content,
                    type: 'danger',
                    float: 'right',
                    id: 'wpz-erase-demo-import',
                    callback: function(){
                        demoImporter.addIframe('wpzoom_erase_demo_content', 'delete_demo_content');
                        return;
                    }
                },
                {
                    text: zoomData.strings.cancel_txt,
                    type: 'default',
                    float: 'left',
                    id: 'wpz-cancel-erase-import',
                    callback: function(){
                        return;
                    }
                }
            ];

            demoImporter.confirmAlert(zoomData.strings.warning, zoomData.strings.erase_alert, buttons);
        });

        D.on('change', 'input[name="demo_importer_select"]', function(){
            demoImporter.updateOptions('selected_demo', $(this).val());
        });

        B.on('wpzoom:delete_demo_content:done', function(){
            demoImporter.updateOptions('imported_demo', '');
            
            demoImporter.popup.instance.close();
            B.trigger('demo:importer:select:demo');
        });

    });

    window.demoImporter = demoImporter;
    window.demoImporter.options = _options;
    window.demoImporter.zoomData = zoomData;

})(jQuery, _, zoom_demo_importer);



function wpzoom_load_demo_content_done() {
    clearInterval(wpzoomIframeScrollIntervalID);

    // Set cookie
    // expires in 365 days
    if ( demoImporter.zoomData.demo_imported != '' ) {
        Cookies.set( demoImporter.zoomData.theme_raw_name + '_' + demoImporter.zoomData.demo_imported + "_demo_content_done", 1, { expires : 365 } );
    } else {
        Cookies.set( demoImporter.zoomData.theme_raw_name + '_' + demoImporter.zoomData.theme_raw_name + "_demo_content_done", 1, { expires : 365 } );
    }

    jQuery("#misc_load_demo_content").prop("disabled", false);
    jQuery("#wpzoom-demo-content-iframe-wrapper .spinner").css("visibility", "hidden");
    jQuery('body').trigger('wpzoom:load_demo_content:done', 'demo_content_done');
}

function wpzoom_delete_demo_content_done() {
    clearInterval(wpzoomIframeScrollIntervalID);

    // Delete cookies
    Cookies.remove( demoImporter.zoomData.theme_raw_name + '_' + demoImporter.zoomData.demo_imported + "_demo_content_done" );
    Cookies.remove( demoImporter.zoomData.theme_raw_name + '_' + demoImporter.zoomData.theme_raw_name + "_demo_content_done" );
    Cookies.remove( demoImporter.zoomData.theme_raw_name + '_' + demoImporter.zoomData.demo_imported + "_steps_status" );
    Cookies.remove( demoImporter.zoomData.theme_raw_name + '_' + demoImporter.zoomData.demo_imported + "_theme_setup_is_complete" );

    jQuery('body').trigger('wpzoom:delete_demo_content:done');
}
