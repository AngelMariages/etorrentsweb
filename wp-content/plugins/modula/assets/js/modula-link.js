(function($){

	function ModulaLink( element ){
		var instance = this;

		this.$el = $( element );
		this.images = this.$el.data('images');
		this.config = this.$el.data('config');

		if ( false == this.config || !this.config ) {
			return;
		}

		var links = jQuery.map( this.images, function(o) {
			    return { 'src' : o.src, 'opts': { 'caption': o.opts.caption,'thumb':o.opts.thumb,'image_id' : o.opts.image_id } }
		    });

		// Callbacks
		this.config['lightboxOpts']['beforeLoad'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_before_load', [instance, this]);
		};
		this.config['lightboxOpts']['afterLoad'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_after_load', [instance, this]);
		};
		this.config['lightboxOpts']['beforeShow'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_before_show', [instance, this]);
		};
		this.config['lightboxOpts']['afterShow'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_after_show', [instance, this]);
		};
		this.config['lightboxOpts']['beforeClose'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_before_close', [instance, this]);
		};
		this.config['lightboxOpts']['afterClose'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_after_close', [instance, this]);
		};
		this.config['lightboxOpts']['onInit'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_on_init', [instance, this]);
		};
		this.config['lightboxOpts']['onActivate'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_on_activate', [instance, this]);
		};
		this.config['lightboxOpts']['onDeactivate'] = function () {
			jQuery(document).trigger('modula_link_fancybox_lightbox_on_deactivate', [instance, this]);
		};

		this.$el.click(function(evt){
			evt.preventDefault();

			if ( 'undefined' != typeof $.modulaFancybox ) {
				$.modulaFancybox.open( links, instance.config['lightboxOpts'] );
			}

		});

		// Trigger event before init
        $( document ).trigger('modula_link_api_after_init', [ instance ]  );

	}

	ModulaLink.prototype.open = function ( index ) {
		var instance = this;
		if ( 'undefined' != typeof $.modulaFancybox ) {
			$.modulaFancybox.open( instance.images, instance.config['lightboxOpts'], index );
		}
	}

	jQuery( document ).ready( function($){
	    var modulaGalleries = $('.modula-link');
	    $.each( modulaGalleries, function(){
	        new ModulaLink( this );
	    });
	});

}(jQuery));