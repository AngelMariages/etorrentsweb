wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;

(function( $, modula ){

	var modulaPROReplace = {
		frame: false,
		modelID: 0,

		init: function(){
			var modulaReplace = this;

			this.frame = wp.media({
               	title: 'Replace Image',
               	multiple : false,
               	library : {
                	type : 'image',
                }
           	});

           	this.frame.on('select',function() {
           		var model = modula.Items.get( modulaReplace.modelID );

           		var selection =  modulaReplace.frame.state().get('selection');
              	selection.each( function( attachment ) {
	            	var attachmentAtts = attachment.toJSON();

	            	model.set( 'full', attachmentAtts['sizes']['full']['url'] );
					if ( "undefined" != typeof attachmentAtts['sizes']['large'] ) {
						model.set( 'thumbnail', attachmentAtts['sizes']['large']['url'] );
					}else{
						model.set( 'thumbnail', attachmentAtts['sizes']['full']['url'] );
					}
					model.set( 'id', attachmentAtts['id'] );
					model.set( 'alt', attachmentAtts['alt'] );
					model.set( 'orientation', attachmentAtts['orientation'] );

					model.get( 'view' ).render();
	            	
	            });

              	// Save Images
              	wp.Modula.Save.checkSave();

           });

		},

		open: function( modelID ){
			var modulaReplace = this;
			this.modelID = modelID;

			if ( ! this.frame ) {
				this.init();
			}

			this.frame.open();

		}


	}

	modula.replace = modulaPROReplace;

}( jQuery, wp.Modula ))