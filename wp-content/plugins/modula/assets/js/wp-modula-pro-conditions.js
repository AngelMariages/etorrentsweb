wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;

var modulaProGalleryConditions = Backbone.Model.extend({

    initialize: function( args ){

		var rows = jQuery('.modula-settings-container tr[data-container]');
		var tabs = jQuery('.modula-tabs .modula-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );

		this.initEvents();
		this.initValues();

    },

    initEvents: function(){

        this.listenTo(wp.Modula.Settings, 'change:cursor', this.changeCustomCursor );
        this.listenTo(wp.Modula.Settings, 'change:uploadCursor', this.changeUploadCursor );
        this.listenTo(wp.Modula.Settings, 'change:lightbox', this.changedLightbox );
        this.listenTo(wp.Modula.Settings, 'change:lightbox_toolbar', this.changedToolbar );
		this.listenTo(wp.Modula.Settings, 'change:dropdownFilters', this.changedDropdownFilters);
	    this.listenTo(wp.Modula.Settings, 'change:hide_title', this.changedTitle );
	    this.listenTo(wp.Modula.Settings, 'change:hide_description', this.changedCaption );
	    this.listenTo(wp.Modula.Settings, 'change:lightbox_animationEffect', this.changedAnimation );
	    this.listenTo(wp.Modula.Settings, 'change:lightbox_transitionEffect', this.changedTransition );
	    this.listenTo(wp.Modula.Settings, 'change:showCaptionLightbox', this.changedLightboxCaption );
	    this.listenTo(wp.Modula.Settings, 'change:showTitleLightbox', this.changedLightboxTitle );
	    this.listenTo(wp.Modula.Settings, 'change:lightbox_share', this.changedLightboxShare );
		this.listenTo(wp.Modula.Settings, 'change:lightbox_email', this.changedLightboxEmail );
	    this.listenTo(wp.Modula.Settings, 'change:show_gallery_title', this.showGalleryTitle );
     },

     initValues: function(){
         this.changeCustomCursor( false, wp.Modula.Settings.get( 'cursor' ) );
         this.changeUploadCursor( false, wp.Modula.Settings.get( 'uploadCursor') );
         this.changedLightbox(false,wp.Modula.Settings.get( 'lightbox' ));
         this.changedToolbar(false,wp.Modula.Settings.get( 'lightbox_toolbar' ));
		 this.changedDropdownFilters(false,wp.Modula.Settings.get( 'dropdownFilters' ));
	     this.changedTitle(false,wp.Modula.Settings.get( 'hide_title' ));
	     this.changedCaption(false,wp.Modula.Settings.get( 'hide_description' ));
	     this.changedAnimation(false,wp.Modula.Settings.get( 'lightbox_animationEffect' ));
	     this.changedTransition(false,wp.Modula.Settings.get( 'lightbox_transitionEffect' ));
	     this.changedLightboxTitle(false,wp.Modula.Settings.get( 'showTitleLightbox' ));
	     this.changedLightboxCaption(false,wp.Modula.Settings.get( 'showCaptionLightbox' ));
	     this.changedLightboxShare(false,wp.Modula.Settings.get( 'lightbox_share' ));
		 this.changedLightboxEmail(false, wp.Modula.Settings.get( 'lightbox_email' ));
	     this.showGalleryTitle(false,wp.Modula.Settings.get( 'show_gallery_title' ));
     },

     changeCustomCursor: function( settings, value) {
        var cursorBox = jQuery( '.modula-effects-preview > div' );
        var rows = this.get( 'rows' );
        if ( 'custom' != value ) {
            rows.filter( '[data-container="uploadCursor"]' ).hide();
            cursorBox.css('cursor', wp.Modula.Settings.get( 'cursor' ) );
        }else {
            rows.filter( '[data-container="uploadCursor"]' ).show();
            var imageSource;
            if(jQuery("#modula_cursor_preview")[0]) {
                imageSource = jQuery("#modula_cursor_preview")[0].src;

            cursorBox.css('cursor', 'url(' + imageSource + '), auto' );
            }
        }

     },

     changeUploadCursor: function( settings, value ) {
         cursorBox = jQuery( '.modula-effects-preview > div' );
         customCursorValue =  wp.Modula.Settings.get( 'cursor' );
         if ( 0 != value  && 'custom' == customCursorValue ) {
            var imageSource = jQuery("#modula_cursor_preview")[0].src;
            cursorBox.css('cursor', 'url(' + imageSource + '), auto' );
         }else {
             cursorBox.css( 'cursor', wp.Modula.Settings.get( 'cursor' ) );
         }
     },

    changedLightbox: function( settings, value ){
        var rows = this.get( 'rows' ),
            tabs = this.get( 'tabs' ),
            link_options = ['no-link', 'direct', 'attachment-page'];

        if ( 'fancybox' == value ) {

            rows.filter( '[data-container="loop_lightbox"],[data-container="lightbox_keyboard"], [data-container="lightbox_wheel"],[data-container="lightbox_clickSlide"],[data-container="lightbox_animationEffect"],[data-container="lightbox_animationDuration"],[data-container="lightbox_transitionEffect"],[data-container="lightbox_transitionDuration"],[data-container="lightbox_toolbar"],[data-container="lightbox_infobar"],[data-container="lightbox_dblclickSlide"],[data-container="lightbox_touch"],[data-container="lightbox_thumbsAutoStart"],[data-container="lightbox_thumbsAxis"],[data-container="lightbox_bottomThumbs"],[data-container="showAllOnLightbox"],[data-container="lightbox_background_color"],[data-container="showTitleLightbox"],[data-container="showCaptionLightbox"],[data-container="captionPosition"]' ).show();

            if('1' == wp.Modula.Settings.get('lightbox_toolbar') && '1' == wp.Modula.Settings.get('lightbox_share')){
            	rows.filter('[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]').show();
            } else {
	            rows.filter('[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]').hide();
            }
            jQuery('.lightbox-afterrow').show();

	        if (1 == wp.Modula.Settings.get('lightbox_toolbar')) {
		        rows.filter('[data-container="lightbox_close"],[data-container="lightbox_thumbs"],[data-container="lightbox_download"],[data-container="lightbox_zoom"],[data-container="lightbox_share"]').show();
	        }

        }else{

            rows.filter( ' [data-container="loop_lightbox"],[data-container="lightbox_keyboard"], [data-container="lightbox_wheel"],[data-container="lightbox_clickSlide"],[data-container="lightbox_animationEffect"],[data-container="lightbox_animationDuration"],[data-container="lightbox_transitionEffect"],[data-container="lightbox_transitionDuration"],[data-container="lightbox_toolbar"],[data-container="lightbox_close"],[data-container="lightbox_thumbs"],[data-container="lightbox_download"],[data-container="lightbox_zoom"],[data-container="lightbox_share"],[data-container="lightbox_infobar"],[data-container="lightbox_dblclickSlide"],[data-container="lightbox_touch"],[data-container="lightbox_thumbsAutoStart"],[data-container="lightbox_thumbsAxis"],[data-container="lightbox_bottomThumbs"],[data-container="showAllOnLightbox"],[data-container="lightbox_background_color"],[data-container="showTitleLightbox"],[data-container="showCaptionLightbox"],[data-container="captionPosition"],[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]' ).hide();
	        jQuery('.lightbox-afterrow').hide();

        }

    },
    changedToolbar: function( settings, value ){
        var rows = this.get( 'rows' ),
            tabs = this.get( 'tabs' );

        if ( 1 == value && 'fancybox' == wp.Modula.Settings.get('lightbox')) {

            rows.filter( '[data-container="lightbox_close"],[data-container="lightbox_download"],[data-container="lightbox_zoom"],[data-container="lightbox_thumbs"],[data-container="lightbox_share"]' ).show();

            if('1' == wp.Modula.Settings.get('lightbox_share')){
            	rows.filter('[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]').show();
				if( '1' == wp.Modula.Settings.get( 'lightbox_email') ) {
					rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').show();
				} else {
					rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').hide();
			}
            } else {
	            rows.filter('[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]').hide();
				rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').hide();

            }

        }else{

            rows.filter( '[data-container="lightbox_close"],[data-container="lightbox_download"],[data-container="lightbox_zoom"],[data-container="lightbox_thumbs"],[data-container="lightbox_share"],[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]' ).hide();
			rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').hide();

        }

    },
	changedDropdownFilters: function( settings, value ) {
		let rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );

		if( 1 == value ) {
			rows.filter( '[data-container="filterClick"],[data-container="filterStyle"],[data-container="filterLinkColor"],[data-container="filterLinkHoverColor"],[data-container="filterTextAlignment"],[data-container="enableCollapsibleFilters"]' ).hide();
		}else {
			rows.filter( '[data-container="filterClick"],[data-container="filterStyle"],[data-container="filterLinkColor"],[data-container="filterLinkHoverColor"],[data-container="filterTextAlignment"],[data-container="enableCollapsibleFilters"]' ).show();
		}
	},
	changedTitle: function (settings, value) {
		var rows = this.get('rows'),
			tabs = this.get('tabs');

		if (1 == wp.Modula.Settings.get('hide_title')) {

			rows.filter('[data-container="titleFontFamily"],[data-container="titleFontWeight"]').hide();

		} else {

			rows.filter('[data-container="titleFontFamily"],[data-container="titleFontWeight"]').show();

		}

	},
	changedCaption: function (settings, value) {
		var rows = this.get('rows'),
			tabs = this.get('tabs');

		if (1 == wp.Modula.Settings.get('hide_description')) {

			rows.filter('[data-container="captionsFontFamily"],[data-container="captionFontWeight"]').hide();

		} else {

			rows.filter('[data-container="captionsFontFamily"],[data-container="captionFontWeight"]').show();

		}
	},
	changedAnimation: function (settings, value) {
		var rows = this.get('rows'),
			tabs = this.get('tabs');

		if (false == value || 'false' == value) {

			rows.filter('[data-container="lightbox_animationDuration"]').hide();

		} else {

			rows.filter('[data-container="lightbox_animationDuration"]').show();

		}
	},
	changedTransition: function (settings, value) {
		var rows = this.get('rows'),
			tabs = this.get('tabs');

		if (false == value || 'false' == value) {

			rows.filter('[data-container="lightbox_transitionDuration"]').hide();

		} else {

			rows.filter('[data-container="lightbox_transitionDuration"]').show();

		}
	},
	changedLightboxTitle: function (settings, value) {

		var rows = this.get('rows');

		if ( '1' != value ) {
			rows.filter('[data-container="showTitleLightbox_position"]').hide();
		} else {
			rows.filter('[data-container="showTitleLightbox_position"]').show();
		}

	},
	changedLightboxCaption: function (settings, value) {

		var rows = this.get('rows');

		if ( '1' == value && 'fancybox' == wp.Modula.Settings.get('lightbox') || '1' == wp.Modula.Settings.get('showTitleLightbox') && 'fancybox' == wp.Modula.Settings.get('lightbox') ) {
			rows.filter('[data-container="captionPosition"]').show();
		} else {
			rows.filter('[data-container="captionPosition"]').hide();
		}

	},

	changedLightboxShare: function (settings, value) {

		var rows = this.get('rows');

		if ( '1' == value && 'fancybox' == wp.Modula.Settings.get('lightbox') && '1' == wp.Modula.Settings.get('lightbox_toolbar')  ) {
			rows.filter('[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]').show();
			
			if( '1' == wp.Modula.Settings.get( 'lightbox_email') ) {
				rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').show();
			} else {
				rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').hide();
			}
		} else {
			rows.filter('[data-container="lightbox_linkedin"],[data-container="lightbox_facebook"],[data-container="lightbox_whatsapp"],[data-container="lightbox_pinterest"],[data-container="lightbox_twitter"],[data-container="lightbox_email"]').hide();
			rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').hide();
		}

	},

	changedLightboxEmail: function( settings, value ) {
		let rows = this.get( 'rows' );

		if( '1' == value && '1' == wp.Modula.Settings.get( 'lightbox_share') && 'fancybox' == wp.Modula.Settings.get( 'lightbox') && '1' == wp.Modula.Settings.get( 'lightbox_toolbar') ) {
			rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').show();
		}else {
			rows.filter( '[data-container="lightboxEmailSubject"], [data-container="lightboxEmailMessage"]').hide();
		}
	},

	showGalleryTitle: function (settings, value) {

		var rows = this.get('rows');

		if ( '1' == value  ) {
			rows.filter('[data-container="gallery_title_type"]').show();
		} else {
			rows.filter('[data-container="gallery_title_type"]').hide();
		}

	},

});