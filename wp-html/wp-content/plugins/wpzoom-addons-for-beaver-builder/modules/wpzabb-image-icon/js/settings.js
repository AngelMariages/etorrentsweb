(function($){
	FLBuilder.registerModuleHelper('wpzabb-image-icon', {
		
		init: function()
		{	
			var form    	= $('.fl-builder-settings'),
				image_type 	= form.find('select[name=image_type]'),
				icon_style	= form.find('select[name=icon_style]'),
				image_style	= form.find('select[name=image_style]'),
				photoSource     = form.find('select[name=photo_source]'),
				librarySource   = form.find('select[name=photo_src]'),
				urlSource       = form.find('input[name=photo_url]');
			
			//console.log( this );
			// Init validation events.
			//this._photoSourceChanged();
			this._toggleImageIcon();

				// Validation events
			photoSource.on('change', $.proxy( this._photoSourceChanged, this ) );
			image_type.on('change', $.proxy( this._toggleImageIcon, this ) );
			icon_style.on('change', $.proxy( this._toggleBorderOptions, this ) ) ;
			image_style.on('change', $.proxy( this._toggleBorderOptions, this ) ) ;
			//image_type.trigger('change');
		},
		
		_toggleBorderOptions: function() {
			var form		= $('.fl-builder-settings'),
				show_border = false
				image_type 	= form.find('select[name=image_type]').val(),
				icon_style 	= form.find('select[name=icon_style]').val(),
				border_style 	= form.find('select[name=icon_border_style]').val(),
				image_style 	= form.find('select[name=image_style]').val(),
				img_border_style 	= form.find('select[name=img_border_style]').val();

			
			if( image_type == 'icon' ){
				if( icon_style == 'custom'  ){
					show_border = true;
				}

				if( show_border == false ){
					form.find('#fl-field-icon_border_width').hide();
					form.find('#fl-field-icon_border_color').hide();
					form.find('#fl-field-icon_border_hover_color').hide();
				}else if( show_border ){
					if ( border_style != 'none' ) {
						form.find('#fl-field-icon_border_width').show();
						form.find('#fl-field-icon_border_color').show();
						form.find('#fl-field-icon_border_hover_color').show();
					}
				}
			}else if( image_type == 'photo' ){
				if( image_style == 'custom' ){
					show_border = true;
				}

				if( show_border == false ){
					form.find('#fl-field-img_border_width').hide();
					form.find('#fl-field-img_border_color').hide();
					form.find('#fl-field-img_border_hover_color').hide();
				}else if( show_border ){
					if ( img_border_style != 'none' ) {
						form.find('#fl-field-img_border_width').show();
						form.find('#fl-field-img_border_color').show();
						form.find('#fl-field-img_border_hover_color').show();
					}
				}
			}
		},
		_toggleImageIcon: function() {
			var form        = $('.fl-builder-settings'),
				show_color 	= false,
				image_type 	= form.find('select[name=image_type]').val(),
				image_style = form.find('select[name=image_style]').val();
			
			//console.log( this );
			//console.log( image_style );
			if( image_type == 'photo' && image_style == 'custom' ){
				show_color = true;
			}

			if( show_color == false ){
				form.find('#fl-builder-settings-section-img_colors').hide();
			}else if( show_color ){
				form.find('#fl-builder-settings-section-img_colors').show();
			}
			this._toggleBorderOptions();
			this._photoSourceChanged();
		},
		_photoSourceChanged: function()
		{
			var form            = $('.fl-builder-settings'),
				image_type	 	= form.find('select[name=image_type]').val(),
				photoSource     = form.find('select[name=photo_source]').val(),
				photo           = form.find('input[name=photo]'),
				photoUrl        = form.find('input[name=photo_url]'),
				iconSize        = form.find('input[name=icon_size]'),
				imgSize        	= form.find('input[name=img_size]');

			photo.rules('remove');
			photoUrl.rules('remove');
			iconSize.rules('remove');
			imgSize.rules('remove');

			if ( image_type == 'photo' ) {
				if(photoSource == 'library') {
					photo.rules('add', { required: true });
				}
				else {
					photoUrl.rules('add', { required: true });
				}
				imgSize.rules('add', { number:true });
			}else if ( image_type == 'icon' ) {
				iconSize.rules('add', { number:true });
			}
		}
	});
})(jQuery);