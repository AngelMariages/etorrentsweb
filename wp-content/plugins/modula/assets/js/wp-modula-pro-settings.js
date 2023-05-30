wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;
wp.Modula.settings = 'undefined' === typeof( wp.Modula.settings ) ? {} : wp.Modula.settings;

(function( $, modula ){

	modulaSettingsPROView = modula.settings['view'].extend({

		events: {
    		// Tabs specific events
    		'click .modula-tab':     'changeTab',
            'click .modula-tab > *': 'changeTabFromChild',

    		// Settings specific events
            'keyup input':         'updateModel',
            'keyup textarea':      'updateModel',
            'change input':        'updateModel',
            'change textarea':     'updateModel',
            'blur textarea':       'updateModel',
            'change select':       'updateModel',
        },

		initialize: function( args ) {

            this.initializeLite();
            this.initializePro();

        },

        initializePro: function(){

            this.fontSelectors  = this.$el.find( '.modula-font-selector' );
            this.initFontSelector();

        },

        initFontSelector: function(){
        	if ( this.fontSelectors.length > 0 ) {
                this.fontSelectors.each( function( $index, fontSelector ) {
                	var value = jQuery( fontSelector ).data( 'value' );
                	//@todo: we need to find a solution to trigger a change event on input.
                    jQuery( fontSelector ).selectize({
                    	options : modulaFonts,
                    	valueField : 'family',
                    	labelField : 'family',
                    	searchField : 'family',
                    	items: [ value ]
                    });
                });
            }
        },
	});

    modula.settings['view'] = modulaSettingsPROView;

}( jQuery, wp.Modula ));

jQuery( document ).ready(function() {

	jQuery('#copyGalleryId').click(function (e) {
		document.execCommand("copy");
		jQuery("#copyGalleryId").after("<span id='copyNotification'> Id Copied To Clipboard </span>");
		setTimeout(function(){
			if (jQuery('#copyNotification').length > 0) {
				jQuery('#copyNotification').remove();
			}
		}, 180)
	});

	jQuery('#copyGalleryId').on('copy', function(){
		event.preventDefault();
		if (event.clipboardData) {
			event.clipboardData.setData("text/plain", jQuery('#copyGalleryId')[0].innerText);
		}
	});

	jQuery( 'tr[data-container="lightboxEmailMessage"] td .modula-placeholders' ).on('click', 'span', function(){
		let input = jQuery( 'textarea[data-setting="lightboxEmailMessage"]');
		let placeholder = jQuery(this).attr('data-placeholder') ;
		input.val( function( index, value ){
			value += placeholder;
			return value;
		})
	}) 
});