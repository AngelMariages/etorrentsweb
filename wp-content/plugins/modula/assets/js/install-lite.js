(function( wp, $ ) {
  	'use strict';
  	if ( ! wp ) {
    	return;
  	}
  
  	function activatePlugin( url ) {
	    $.ajax( {
	      	async: true,
	      	type: 'GET',
	      	dataType: 'html',
	      	url: url,
	      	success: function() {
	        	location.reload();
	      	}
	    });
	}

	$('#install-modula-lite').click( function( event ) {
		var action = $( this ).data( 'action' ),
		  	url = $( this ).attr( 'href' ),
		  	slug = 'modula-best-grid-gallery';

		$(this).addClass( 'updating-message' );
		$(this).attr( 'disabled', 'disabled' );
		event.preventDefault();

		if ( 'install' === action ) {
			wp.updates.installPlugin( {
				slug: slug
			} );
		} else if ( 'activate' === action ) {
			activatePlugin( url );
		}
	});

	$( document ).on( 'wp-plugin-install-success', function( response, data ) {
		if ( 'modula-best-grid-gallery' == data.slug ) {
			event.preventDefault();
			activatePlugin( data.activateUrl );
		}
	} );
})( window.wp, jQuery );