function modula_pro_get_url_parameters(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || false
}

(function( $ ){
    "use strict";

    function ModulaPRO( modula ) {

        this.instance = modula;
        this.filter   = '*';
        this.hiddenItems = modula.$element.find( '.hidden-items .modula-hidden-item' )

        if ( this.instance.options.haveFilters ) {
            this.initFilters();
        }

        if ( 'fancybox' == this.instance.options['lightbox'] ) {
            this.initLightbox();
        }

    }

    ModulaPRO.prototype.initLightbox = function () {
        var self = this,
            inst = self.instance;

        inst.$element.on( 'click', '.modula-item-link:not( .modula-simple-link )', function(evt){

            evt.preventDefault();

            var items = inst.$items.filter( self.filter ).filter(':not( .modula-simple-link )'),
                links = jQuery.map( items, function(o) { 
                    var link  = jQuery(o).find('.modula-item-link'),
                    image = jQuery(o).find('.pic');
                    return {
                        'src':  link.attr( 'href' ),
                        'opts': {
                            '$thumb':   image.parents( '.modula-item' ),
                            'caption':  link.data( 'caption' ),
                            'alt':      image.attr( 'alt' ),
                            'thumb':    link.attr( 'data-thumb' ),
                            'image_id': link.attr( 'data-image-id' )
                        }
                    }
                });

            if ( self.hiddenItems.length > 0 ) {
                var hiddenLinks = jQuery.map( self.hiddenItems.filter( self.filter ), function(o) {
                    var link  = jQuery(o);
                    return { 'src' : link.attr( 'href' ), 'opts': { 'caption': link.data( 'caption' ), 'alt': '','thumb':link.attr('data-thumb'), 'image_id' : link.attr('data-image-id') } }
                });

                links = links.concat( hiddenLinks );
            }

            var index = items.index( jQuery(this).parents( '.modula-item' ) );

            inst.options.lightboxOpts['beforeLoad'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_before_load', [inst, this]);
            };
            inst.options.lightboxOpts['afterLoad'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_after_load', [inst, this]);
            };
            inst.options.lightboxOpts['beforeShow'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_before_show', [inst, this]);
            };
            inst.options.lightboxOpts['afterShow'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_after_show', [inst, this]);
            };
            inst.options.lightboxOpts['beforeClose'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_before_close', [inst, this]);
            };
            inst.options.lightboxOpts['afterClose'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_after_close', [inst, this]);
            };
            inst.options.lightboxOpts['onInit'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_on_init', [inst, this]);
            };
            inst.options.lightboxOpts['onActivate'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_on_activate', [inst, this]);
            };
            inst.options.lightboxOpts['onDeactivate'] = function () {
                jQuery(document).trigger('modula_fancybox_lightbox_on_deactivate', [inst, this]);
            };

            jQuery.modulaFancybox.open( links, inst.options.lightboxOpts, index );

        });

    }

    ModulaPRO.prototype.initFilters = function () {
        var self = this,
            inst = self.instance;

        if ( 'undefined' != typeof inst.options.defaultActiveFilter ) {
            if ( 'All' == inst.options.defaultActiveFilter ) {
                self.filter = '*';
            }else{
                self.filter = '.jtg-filter-' + inst.options.defaultActiveFilter;
            }
        }

        var urlFilter = modula_pro_get_url_parameters( 'jtg-filter' );
        if ( urlFilter ) {
            self.filter = '.jtg-filter-' + urlFilter;
        }

        // Dropdown Filter value 
        var dropdownFilters = inst.options.dropdownFilters;

        if( '1' == dropdownFilters ) {

            inst.$element.on( 'change', '.filters', function (e) {
            
                if ( jQuery(this).hasClass( "modula_menu__item--current" ) ) {
                    return;
                }

                self.filter = '.jtg-filter-' + this.value;

                inst.$element.find( '.filters .modula_menu__item--current' ).removeClass( 'modula_menu__item--current' );
                inst.$element.find(".filters option[value='" + this.value + "']").addClass( 'modula_menu__item--current' ).prop("selected", "selected");

                if ( inst.isIsotope ) {

                    inst.$items.removeClass( 'jtg-hidden' );
                    inst.$items.not( self.filter ).addClass( 'jtg-hidden' );
                    inst.reset();

                    inst.$itemsCnt.modulaisotope({ filter: self.filter });
                }else{
                    inst.$itemsCnt.justifiedGallery({ filter: self.filter });
                }
                });
        }else {

            inst.$element.on( 'click', '.filters a', function (e) {
        
            if ('0' == inst.options.filterClick) {
                e.preventDefault();
            }else{
                return true;
            }

            if ( jQuery(this).parent().hasClass( "modula_menu__item--current" ) ) {
                return;
            }
            
            self.filter = '.jtg-filter-' + jQuery(this).data('filter');

            inst.$element.find( '.filters .modula_menu__item--current' ).removeClass( 'modula_menu__item--current' );
            inst.$element.find(".filters a[data-filter='" + jQuery(this).attr('data-filter') + "']").parent().addClass( 'modula_menu__item--current' );

            if ( inst.isIsotope ) {

                inst.$items.removeClass( 'jtg-hidden' );
                inst.$items.not( self.filter ).addClass( 'jtg-hidden' );
                inst.reset();

                inst.$itemsCnt.modulaisotope({ filter: self.filter });
            }else{
                inst.$itemsCnt.justifiedGallery({ filter: self.filter });
            }

        });
        }

        inst.$element.find('.filter-by-wrapper').click(function () {
            var wrapper = jQuery(this);
            if (inst.$element.find('.filters').hasClass('active')) {
                inst.$element.find('.filters').hide(600).removeClass('active');
                wrapper.removeClass('opened');
            } else {
                inst.$element.find('.filters').show(600).addClass('active');
                wrapper.addClass('opened');
            }
        });

        if ( '*' != self.filter && '' != self.filter ) {
            if ( inst.isIsotope ) {
                inst.$items.removeClass( 'jtg-hidden' );
                inst.$items.not( self.filter ).addClass( 'jtg-hidden' );
                inst.reset();
                
                inst.$itemsCnt.modulaisotope({ filter: self.filter });
            }else{
                inst.$itemsCnt.justifiedGallery({ filter: self.filter });
            }
        }

    }

    $(document).on( 'modula_api_after_init', function ( event, inst ) {
        new ModulaPRO( inst );
    });

})(jQuery);


