( function( $ ) {
	FLBuilder.registerModuleHelper( 'wpzabb-slideshow', {
		init: function() {
			$( 'body' ).delegate( '.fl-builder-settings-tabs a', 'click', this.onTabClick );

			setTimeout( this.onTabClick, 500 );

			var slidesrc = $( '#fl-field-slides_source select[name="slides_source"]' );
			slidesrc.parent().find( '.fl-field-description' ).toggle( 'posts' == slidesrc.val() );
			slidesrc.on( 'change', function() {
				$( this ).parent().find( '.fl-field-description' ).toggle( 'posts' == $( this ).val() );
			} );
		},

		onTabClick: function() {
			var tab  = $( '.fl-builder-settings-tabs a.fl-active' ),
			    id   = tab.attr( 'href' ).split( '#' ).pop(),
			    form = $( '#' + id );

			if ( 'fl-builder-settings-tab-slides' == id && form.length > 0 && !form.hasClass( 'initd' ) &&
			     'custom' == form.find( '#fl-field-slides_source select[name="slides_source"]' ).val() ) {
				var trs = form.find( '#fl-field-slides > tr' ),
				    slides = form.find( '#fl-field-slides > tr > .fl-field-control > .fl-field-control-wrapper > .fl-form-field' );

				if ( slides.length > 0 ) {
					slides.each( function() {
						var input = $( this ).find( 'input[name="slides[]"]' );

						if ( input.length > 0 ) {
							var json;

							try {
								json = JSON.parse( input.val() );
							} catch( e ) {}

							if ( typeof json !== 'undefined' ) {
								var img = json.image_src,
								    is_img = false !== img && '' !== img;

								if ( is_img ) {
									$( this ).find( '> .fl-form-field-edit' ).html( '<img src="' + img + '" />' );
								}
							}
						}
					} );
				}

				form.addClass( 'initd' );
			}
		}
	} );
} )( jQuery );