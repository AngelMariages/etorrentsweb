( function( $ ) {
	FLBuilder.registerModuleHelper( 'wpzabb-image-grid', {
		init: function() {
			$( 'body' ).delegate( '.fl-builder-settings-tabs a', 'click', this.onTabClick );

			setTimeout( this.onTabClick, 500 );
		},

		onTabClick: function() {
			var tab  = $( '.fl-builder-settings-tabs a.fl-active' ),
			    id   = tab.attr( 'href' ).split( '#' ).pop(),
			    form = $( '#' + id );

			if ( 'fl-builder-settings-tab-items' == id && form.length > 0 && !form.hasClass( 'initd' ) ) {
				var trs = form.find( '#fl-field-items > tr' ),
				    items = form.find( '#fl-field-items > tr > .fl-field-control > .fl-field-control-wrapper > .fl-form-field' );

				if ( items.length > 0 ) {
					items.each( function() {
						var input = $( this ).find( 'input[name="items[]"]' );

						if ( input.length > 0 ) {
							var json;

							try {
								json = JSON.parse( input.val() );
							} catch( e ) {}

							if ( typeof json !== 'undefined' ) {
								var img = json.image_src;

								if ( typeof img !== 'undefined' && '' !== img ) {
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