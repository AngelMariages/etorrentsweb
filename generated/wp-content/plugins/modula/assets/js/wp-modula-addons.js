(function( $ ){
    "use strict";

    function modula_install_addon( $url ) {
        
        // Process the Ajax to perform the activation.
        var opts = {
            url:      ajaxurl,
            type:     'post',
            async:    true,
            cache:    false,
            dataType: 'json',
            data: {
                action: 'modula-install-addons',
                nonce:  modulaPRO.install_nonce,
                plugin: $url
            },
            success: function( response ) {
                // If there is a WP Error instance, output it here and quit the script.
                if ( response.error ) {
                    console.log( response.error );
                    return;
                }

                // If we need more credentials, output the form sent back to us.
                if ( response.form ) {
                    // Display the form to gather the users credentials.
                    $( '.modula-addons-error' ).html( response.form );

                    $('.modula-addons-error').on('click', '#upgrade', function(e) {
                        // Prevent the default action, let the user know we are attempting to install again and go with it.
                        e.preventDefault();

                        // Now let's make another Ajax request once the user has submitted their credentials.
                        var hostname  = $(this).parent().parent().find('#hostname').val();
                        var username  = $(this).parent().parent().find('#username').val();
                        var password  = $(this).parent().parent().find('#password').val();
                        $( '.modula-addons-error' ).html('');
                        var cred_opts = {
                            url:      ajaxurl,
                            type:     'post',
                            async:    true,
                            cache:    false,
                            dataType: 'json',
                            data: {
                                action:   'modula-install-addons',
                                nonce:    modulaPRO.install_nonce,
                                plugin:   $url,
                                hostname: hostname,
                                username: username,
                                password: password
                            },
                            success: function(response) {
                                // If there is a WP Error instance, output it here and quit the script.
                                if ( response.error ) {
                                    console.log( response.error );
                                    return;
                                }

                                if ( response.form ) {
                                    $( '.modula-addons-error' ).html( '<div class="notice notice-error"><p>' + modulaPRO.connect_error + '</p></div>' );
                                    return;
                                }

                                // The Ajax request was successful, so let's activate the addon.
                                if ( response.activate_url ) {
                                    modula_activate_addon( response.activate_url );
                                }
                            },
                            error: function(xhr, textStatus ,e) {
                                console.log( xhr );
                                console.log( textStatus );
                                console.log( e );
                                return;
                            }
                        };
                        $.ajax(cred_opts);
                    });

                    // No need to move further if we need to enter our creds.
                    return;
                }

                // The Ajax request was successful, so let's update the output.
                if ( response.activate_url ) {
                    modula_activate_addon( response.activate_url );
                }
            },
            error: function( xhr, textStatus ,e ) {
                console.log( xhr );
                console.log( textStatus );
                console.log( e );
                return;
            }
        };

        $.ajax(opts);

    }

    function modula_activate_addon( url ) {

        jQuery.ajax( {
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function( response ) {
                location.reload();
            },
        } );

    }

    function modula_deactivate_addon( url ) {
        
        jQuery.ajax( {
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function( response ) {
                location.reload();
            },
        } );

    }

    $( document ).ready(function(){

        // Re-enable install button if user clicks on it, needs creds but tries to install another addon instead.
        $( '.modula-addons-container' ).on('click', '.modula-addon-action', function(e) {
            var url    = $(this).attr('href'),
                action = $(this).data('action');

            e.preventDefault();
            $(this).addClass( 'updating-message' );

            if ( 'install' == action ) {
                modula_install_addon( url );
            }else if ( 'activate' == action ) {
                modula_activate_addon( url );
            }else if ( 'deactivate' == action ) {
                modula_deactivate_addon( url );
            }

        });

    });

})(jQuery);