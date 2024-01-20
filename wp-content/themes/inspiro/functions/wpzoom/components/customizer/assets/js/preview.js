/**
 * @package WPZOOM Framework
 *
 * CSS Rules
 *
/* global jQuery, wp */

(function (wp, $, WPZOOM_Preview, _, vein) {
    'use strict';

    if ( ! wp || ! wp.customize || ! WPZOOM_Preview ) { return; }

    var api = wp.customize,
        themeName = WPZOOM_Preview.themeName,
        cssRules = WPZOOM_Preview.cssRules,
        fontParams = WPZOOM_Preview.fontParams;

    $(document).ready(function () {

        var $customCssSelector = $(`#${ themeName }-custom-css`);
        var $wpCustomCssSelector = $('#wp-custom-css');

        var styleSheet = $customCssSelector.length ? $customCssSelector[0].sheet : $wpCustomCssSelector[0].sheet;

        var utils = {
            fontSize: function (current, value) {
                return parseFloat(value) + 'px';
            },
            fontSizeResponsive: function (current, value) {
                var selectors = current.style.selector.split(',');

                _.each( current.style.media, function( mediaScreen, device ) {
                    var mediaValue = value[ device ] + value[ `${ device }-unit` ];
                    var mediaQueries = {};

                    _.each( selectors, function( selector, key ) {
                        mediaQueries[ `@media ${ mediaScreen }` ] = [ $.trim( selector ) ];

                        vein.inject(
                            [ mediaQueries ],
                            { 'font-size': mediaValue },
                            { 'stylesheet': styleSheet }
                        );
                    } );
                } );
            },
            letterSpacing: function (current, value) {
                return parseFloat(value) + 'px';
            },
            display: function (current, value, display_type) {
                display_type = display_type || 'block';
                
                if ( value == 'yes' || value == 'on' )
                    value = true;

                if ( value == 'no' || value == 'off' )
                    value = false;

                return value ? display_type : 'none';
            },
            backgroundGradient : function (current, value) {
                value = JSON.parse(value)[0];

                var gradient = '', gradient2 = '';

                var directions = {
                    'user-agent': {
                        'horizontal': 'left',
                        'vertical': 'top',
                        'diagonal-lt': '45deg',
                        'diagonal-lb': '-45deg'
                    },
                    'w3c': {
                        'horizontal': 'to right',
                        'vertical': 'to bottom',
                        'diagonal-lt': '135deg',
                        'diagonal-lb': '45deg'
                    },
                };

                var direction = directions['user-agent'][ value['direction'] ],
                    direction2 = directions['w3c'][ value['direction'] ],
                    start_color = hexToRgbA( value['start_color'], value['start_opacity'] ),
                    end_color = hexToRgbA( value['end_color'], value['end_opacity'] ),
                    start_location = value['start_location'],
                    end_location = value['end_location'];

                gradient = `${ direction }, ${ start_color } ${ start_location }%, ${ end_color } ${ end_location }%`;
                gradient2 = `${ direction2 }, ${ start_color } ${ start_location }%, ${ end_color } ${ end_location }%`;

                var gradients = [
                    {'background': `-moz-linear-gradient(${ gradient })`}, /* FF3.6+ */
                    {'background': `-webkit-linear-gradient(${ gradient })`}, /* Chrome10+,Safari5.1+ */
                    {'background': `-o-linear-gradient(${ gradient })`}, /* Opera 11.10+ */
                    {'background': `-ms-linear-gradient(${ gradient })`}, /* IE10+ */
                    {'background': `linear-gradient(${ gradient2 })`} /* W3C */
                ];

                _.each(gradients, function(gradient){
                    vein.inject(
                        current.style.selector.split(','),
                        gradient,
                        {'stylesheet': styleSheet}
                    );
                });
            },
            fontFamily: function (current, value) {

                var font_families = value;

                var fontInject = function (fontFamily, fontGroup) {
                    vein.inject(
                        current.style.selector.split(','),
                        {'font-family': 'standard-fonts' === fontGroup ? fontFamily : '"'+ fontFamily +'"'},
                        {'stylesheet': styleSheet}
                    );
                };

                // Standard Fonts
                _.each(fontParams['standard-fonts'].fonts, function (font, key) {

                    if ( font.label == value ) {
                        fontInject(font.stack, 'standard-fonts');
                    }

                });

                // Google Fonts
                _.each(fontParams['google-fonts'].fonts, function (font, key) {

                    if ( font.label == value ) {
                        if ( typeof font.variants != 'undefined' ) {
                            font_families += ':' + font.variants.join(',');
                        }

                        if ( typeof font.subsets != 'undefined' ) {
                            font_families += ':' + font.subsets.join(',');
                        }

                        WebFont.load({
                            google: {
                                families: [font_families]
                            },
                            fontactive: fontInject
                        });

                    }

                });

                return value;
            }
        };

        _.each(cssRules, function (current, key) {

            wp.customize(key, function (value) {

                value.bind(function (newval) {
                    var myObj = {};
                    var findKeyUtils;

                    // Skip all if is not style rule
                    if ( typeof current.style === 'undefined' ) return;

                    if ( _.isArray(current.style) ) {
                        _.each( current.style, function (subcurrent) {

                            myObj = {};

                            myObj[subcurrent.rule] = newval;

                            findKeyUtils = _.findKey(utils, function (value, key) { return key === $.camelCase(subcurrent.rule) });

                            if ( findKeyUtils ) {
                                // Display
                                if ( subcurrent.rule === 'display' ) {
                                    myObj[subcurrent.rule] = utils[$.camelCase(subcurrent.rule)](current, newval, subcurrent.display_type);

                                    if ( typeof WPZOOM_THEME !== 'undefined' ) {
                                        WPZOOM_THEME.postsMasonry.init();
                                    }
                                } else {
                                    myObj[subcurrent.rule] = utils[$.camelCase(subcurrent.rule)](current, newval);
                                }
                                // Font Family
                                if (subcurrent.rule === 'font-family') {
                                    return;
                                }
                                // Font Size Responsive
                                if (subcurrent.rule === 'font-size-responsive') {
                                    return;
                                }
                            }

                            // Inject styles
                            if ( typeof subcurrent.selector != 'undefined' ) {
                                vein.inject(
                                    subcurrent.selector.split(','),
                                    myObj,
                                    {'stylesheet': styleSheet}
                                );
                            }
                            
                        });
                        return;
                    }

                    myObj[current.style.rule] = newval;

                    findKeyUtils = _.findKey(utils, function (value, key) { return key === $.camelCase( current.style.rule ) });

                    if ( findKeyUtils ) {
                        // Display
                        if ( current.style.rule === 'display' ) {
                            myObj[current.style.rule] = utils[$.camelCase(current.style.rule)](current, newval, current.style.display_type);

                            if ( typeof WPZOOM_THEME !== 'undefined' ) {
                                WPZOOM_THEME.postsMasonry.init();
                            }
                        } else {
                            myObj[current.style.rule] = utils[$.camelCase(current.style.rule)](current, newval);
                        }
                        // Font Family
                        if (current.style.rule === 'font-family') {
                            return;
                        }
                        // Font Size Responsive
                        if (current.style.rule === 'font-size-responsive') {
                            return;
                        }
                    }

                    // Opacity
                    if ( current.style.rule === 'opacity' ) {
                        var opacity = newval.replace( /[^0-9]/, '' );

                        if ( opacity == '' ) opacity = 0;
                        myObj[current.style.rule] = Math.min( 100, Math.max( 0, parseInt( opacity, 10 ) ) ) / 100;
                    }

                    // Inject styles
                    if ( typeof current.style.selector != 'undefined' ) {
                        vein.inject(
                            current.style.selector.split(','),
                            myObj,
                            {'stylesheet': styleSheet}
                        );
                    }

                    // Sortable control reorder elements
                    if ( current.style.rule === 'reorder' ) {
                        var order = array_flip( newval.split('') );

                        _.each( current.style.choices, function( item ){

                            $(document).find( item.selector ).removeClass( remove_order_class ).addClass('order-' + (parseInt(order[ item.id ], 10) + 1));

                        });
                    }

                    // Toggle Class
                    if ( current.style.rule === 'toggleClass' ) {
                        _.each( current.style.choices, function( item ) {

                            var $element = $(document).find( item.selector );

                            if ( item.class === 'hidden' ) {

                                if ( item.id === newval ) {
                                    if ( $element.hasClass( item.class ) ) {
                                        $element.removeClass( item.class );
                                    } else {
                                        $element.addClass( item.class );
                                    }
                                } else {
                                    $element.addClass( item.class );
                                }

                                return;
                            }

                            if ( item.id === newval ) {
                                $element.addClass( item.class );
                            } else {
                                $element.removeClass( item.class );
                            }

                        });
                    }

                });
            });
        });
    });

})(wp, jQuery, WPZOOM_Preview, _, vein);


/**
 * @package WPZOOM Framework
 *
 * DOM Rules
 *
/* global jQuery, wp */

(function (wp, $, WPZOOM_Preview, _) {

    if ( ! wp || ! wp.customize || ! WPZOOM_Preview ) { return; }

    var api = wp.customize,
        domRules = WPZOOM_Preview.domRules;

    $(document).ready(function () {

        var utils = {
            toggleClass: function (object, newval, oldval) {
                $(object.dom.selector).removeClass(oldval);
                $(object.dom.selector).addClass(newval);
            },
            changeStylesheet: function (object, newval, oldval) {
                $('#' + object.dom.selector.replace('*', oldval)).attr('href', function (index, href) {
                    return href.replace(oldval, newval)
                }).attr('id', function (index, id) {
                    return id.replace(oldval, newval)
                });
            }
        };

        _.each(domRules, function (current, key) {
            wp.customize(key, function (value) {
                value.bind(function (newval, oldval) {

                    if (_.findKey(utils, function (value, key) {
                            return key === $.camelCase(current.dom.rule)
                        })) {
                        utils[$.camelCase(current.dom.rule)](current, newval, oldval);
                    }
                });
            });
        });
    });

})(wp, jQuery, WPZOOM_Preview, _);


/**
 * @package WPZOOM Framework
 *
 * Preview functionality for Responsive Slider Height feature.
 *
/* global jQuery, wp.customize */

( function( $, api ) {
    api.bind( 'preview-ready', function() {
        api.preview.bind( 'update-slider', function( msg ) {
            $( '#slider .flex-viewport, #slider .slides, #slider .slides > li' ).each( function() {
                $( this )[ 0 ].style.setProperty( 'height', msg + 'vh', 'important' );
            } );
        } );
    } );
} )( jQuery, wp.customize );


function array_flip( trans ) {
    var key, tmp_ar = {};

    for ( key in trans ) {
        if ( trans.hasOwnProperty( key ) ) {
            tmp_ar[trans[key]] = key;
        }
    }

    return tmp_ar;
}

function hexToRgbA(hex, opacity) {
    var c;

    opacity = opacity || '1';

    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+', '+ opacity +')';
    }
    throw new Error('Bad Hex');
}

function remove_order_class( index, css ) {
    return ( css.match(/(^|\s)order-[1-3]/ig) || [] ).join(' ');
}
