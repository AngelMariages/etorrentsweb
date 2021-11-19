(function( $ ){
	"use strict";

	
    $( document ).ready(function(){
        
    	var modulaCursorFrame = new wp.media({
            title: 'Select a image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: false // Set to true to allow multiple files to be selected
        });

        modulaCursorFrame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            var attachment = modulaCursorFrame.state().get('selection').first().toJSON();

            var att_id = attachment.id,
                att_src = attachment.url;

            if ( 'undefined' != typeof attachment.sizes.thumbnail ) {
                att_src = attachment.sizes.thumbnail.url;
            }
            
            $('#uploadCursor').val( att_id );
            
            $('.modula_cursor_preview').html( '<img src="' + att_src + '" id="modula_cursor_preview">' );
            wp.Modula.Settings.set( 'uploadCursor',att_id );
            $( '#upload_cursor_file' ).hide();
            $( '#replace_cursor_file' ).show();
            $( '#delete_cursor_file' ).show();

        });

        $( '#upload_cursor_file' ).click(function( event ){
            event.preventDefault();
            modulaCursorFrame.open();
        });

        $( '#replace_cursor_file' ).click(function( event ){
            event.preventDefault();
            modulaCursorFrame.open();
            
        });

        $( '#delete_cursor_file' ).click(function( event ){
            event.preventDefault();
            
            wp.Modula.Settings.set( 'uploadCursor',0 );
            $('#uploadCursor').val( 0 );
            $('.modula_cursor_preview').html( '' );
            $( '#upload_cursor_file' ).show();
            $( '#replace_cursor_file' ).hide();
            $( '#delete_cursor_file' ).hide();

        });
    
    });
	

})( jQuery );