(function ($, _) {

    var D = $(document),
        B = $('body'),
        W = $(window);

    D.ready(function() {

        $("#child_theme_install").click(function (e) {
            e.preventDefault();

            var loading = $("#zoomLoading");
            var success = $("#zoomSuccess");
            var fail    = $("#zoomFail");

            var ct_auto_activate = $("#child_theme_auto_activate").is(':checked'),
            	ct_keep_parent_settings = $("#child_theme_keep_parent_settings").is(':checked');

            loading.fadeIn();

            $.ajax({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: 'zoom_install_child_theme',
                    location: 'zoomForm-tab-content',
                    child_theme_auto_activate: ct_auto_activate,
                    child_theme_keep_parent_settings: ct_keep_parent_settings
                }
            }).done( function( response ) {
                var data = response.data || {};

            	loading.fadeOut();

            	if ( data.done === 1 ) {
                    success.find('p').text( data.message ).append(` <strong>Debug: ${ data.debug }</strong>`);
	            	success.fadeIn();
            	}
            	else if ( data.done === 0 ) {
                    fail.find('p').text( data.message ).append(` <strong>Debug: ${ data.debug }</strong>`);
            		fail.fadeIn();
            	}

            	setTimeout(function () {
            	    success.fadeOut();
            	    fail.fadeOut();
                    
                    location.reload();
            	}, 3500);

            });
        });

    });

})(jQuery, _);