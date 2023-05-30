/**
 * @package WPZOOM Framework
 */

/* global jQuery, wp */
(function (wp, $, WPZOOM_Controls) {
    'use strict';

    if ( ! wp || ! wp.customize || ! WPZOOM_Controls ) { return; }

    var api = wp.customize,
        WPZOOM;

    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    // Setup
    WPZOOM = $.extend(WPZOOM_Controls, {
        cache: {
            $document: $(document),
            list_controls: api.control._value,
            control_elements: {},
            cloud: {},
            deps_is_ready: false,
            frag: false
        },
        __construct: function() {
            var self = this;

            self.setDependencies();
            self.checkDependency();
            self.initFont();
            self.fontResetOption();
            self.initRPM();
            self.initRSH();
        },
        convertToType: function(value, type) {
            switch (type) {
                case 'number':
                    value = Number(value);
                    break;

                case 'string':
                    value = String(value);
                    break;

                case 'boolean':
                    value = Boolean(value);
                    break;
            }

            return value;
        },
        prepareItems: function(data, callback) {
            var self = this;

            var frag = document.createDocumentFragment();

            _.each(data, function (item, key) {

                _.each(item, function (text, val) {

                    var $option = new Option( text, val );

                    if ( val === 'label' ) {
                        $option = document.createElement('option');
                        $option.setAttribute( 'value', val + '-' + key );
                        $option.setAttribute( 'disabled', 'disabled' );
                        $option.textContent = text;
                    }

                    if ( val !== 'label' ) {
                        var font_families_with_preview = WPZOOM.font_families_with_preview;
                        $option.title = text;

                        if ( typeof font_families_with_preview[ text ] !== 'undefined' ) {
                            $option.classList.add('font-has-preview');
                        }
                    }

                    frag.appendChild($option);
                });

                self.cache.frag = true;
            });

            callback(frag);
        },
        checkDependency: function( event ) {
            event = event || 'ready';

            var self = this,
                list = self.cache.list_controls,
                list_dep = self.cache.list_dependencies,
                elements = self.cache.control_elements,
                controlID;

            var doCheck = function( event ) {
                var check = [];

                // Each all registered controls
                _.each(list, function(control){

                    var dependencies = {}, node = $(control.selector), element;

                    // Set control id
                    controlID = control.id;

                    // on document ready
                    if ( event == 'ready' && typeof list_dep[ controlID ] !== 'undefined' ) {
                        dependencies = list_dep[ controlID ]; // list of dependencies of this item
                    }

                    // on document change
                    if ( event == 'change' && typeof control.params.dependency != 'undefined' ) {
                        dependencies = control.params.dependency;
                    }

                    // Get the size of an object
                    var size = Object.size(dependencies);

                    // We have dependencies
                    if ( size ) {

                        check[ controlID ] = [];

                        // Link Elements
                        if ( typeof elements[ control.id ] == 'undefined' ) {
                            elements[ control.id ] = control;
                        }

                        // Each all dependencies to check if control satisfy all conditions or not
                        _.each(dependencies, function(val, id){

                            var control      = list[ id ], // dependency control
                                value        = control.setting.get(), // value of dependency control
                                value_type   = typeof value,
                                control_type = control.params.type,
                                node         = $(control.selector),
                                helper       = [];

                            if ( value == '' ) value = '0';

                            helper[ controlID ] = [];

                            // Link Elements
                            if ( typeof elements[ control.id ] == 'undefined' ) {
                                elements[ control.id ] = control;
                            }

                            // Check for multiple values
                            if ( _.isArray( val ) ) {
                                _.each(val, function(_val) {
                                    _val = WPZOOM.convertToType( _val, value_type );

                                    helper[ controlID ].push(value == _val);
                                });
                            } else {
                                val = WPZOOM.convertToType( val, value_type );

                                helper[ controlID ].push(value == val);
                            }

                            if ( _.contains( helper[ controlID ], true ) ) {
                                check[ controlID ].push(true);
                            } else {
                                check[ controlID ].push(false);
                            }

                        });

                    }

                    // Skip controls that has no dependency
                    if ( typeof check[ controlID ] == 'undefined' ) return;

                    // If control satisfy all conditions it remains visible
                    // else hide control until all conditions will be true
                    if ( _.contains(check[ controlID ], true) && ! _.contains(check[ controlID ], false) ) {
                        $('#customize-control-' + controlID).slideDown();
                    } else {
                        $('#customize-control-' + controlID).slideUp();
                    }

                });
            }

            // Check dependencies
            doCheck( event );

            // on Change event

            _.each(elements, function(control){
                var element = $(control.selector);

                if ( control.params.type == 'zoom_checkbox' && control.params.value == '0' ) {
                    element.find('input[type="checkbox"]').prop('checked', false);
                }

                // Update customizer controls visibility
                element.bind('change', function(e) {
                    doCheck(e.type);
                });
            });
        },
        setDependencies: function() {
            var self = this,
                list = self.cache.list_controls,
                dependencies = {};

            if ( self.cache.deps_is_ready ) return;

            _.each(list, function(control) {
                if ( typeof control.params.dependency != 'undefined' ) {
                    var id = control.id,
                        dependency = control.params.dependency;

                    if ( dependency ) {
                        dependencies[ id ] = dependency;
                    };
                }
            });

            self.cache.deps_is_ready = true;
            self.cache.list_dependencies = dependencies;
        }
    });

    // Font choice loader
    WPZOOM = $.extend(WPZOOM_Controls, {
        fontElements: $(),

        initFont: function() {
            var self = this;

            self.cache.toSync = $(document).find('[data-customize-setting-link="body-font-family-sync-all"]').is(':checked');

            self.cache.$document.ready(function() {
                self.getFontElements();

                self.fontElements.each(function() {

                    $(this).chosen({
                        no_results_text: self.l10n.chosen_no_results_fonts,
                        search_contains: true,
                        width          : '100%'
                    })
                    .on('change', function (event, params){

                        if ( typeof params === 'undefined' || typeof params.toSync === 'undefined' ) {
                            self.updateFontParams(params);
                        }

                        if ( self.cache.toSync && typeof params.toSync === 'undefined' ) {
                            self.syncFontFamily( true );
                        }

                        self.cache.$document.find('.zoom-font-family-preview-modal').remove()
                    });

                    $(this).on('chosen:showing_dropdown', self.updateFontElements);
                });

                self.cache.$document.on('mouseover', '.chosen-container .active-result.font-has-preview', function(){
                    var $element = $(this),
                        offset = $element.offset(),
                        top_position = offset.top,
                        left_position = offset.left,
                        font_name = $element.attr('title');

                    self.cache.$document.find('#customize-preview').append('<div class="zoom-font-family-preview-modal" data-font-family-name="'+ font_name +'" style="top: '+ top_position +'px; left: '+ left_position +'px;">'+ WPZOOM.l10n.modal_font_preview_text +'</div>');
                });

                self.cache.$document.on('mouseout', '.chosen-container .active-result.font-has-preview', function(){
                    var $element = $(this),
                        font_name = $element.attr('title');
                    self.cache.$document.find('.zoom-font-family-preview-modal').remove();
                });
            });
        },

        getFontElements: function() {
            var self = this;
            var font;

            self.fontSettings = self.fontSettings || {};

            $.each(self.fontSettings, function(i, setting) {
                api.control(setting.setting_id, function(control) {
                    var $element = control.container.find('select');
                    var value = setting.default;

                    $element.data('settingId', setting.setting_id);

                    // Check selected value
                    if ( typeof setting.value != 'undefined' ) {
                        value = setting.value;
                    }

                    if ( setting.rule == 'font-family' ) {
                        $element.html('<option value="'+ value +'" selected="selected">' + value + '</option>');
                        font = value;

                        self.cache.cloud[ control.params.section ] = {};
                        self.fontElements = self.fontElements.add($element);
                    }

                    if ( setting.rule == 'font-weight' || setting.rule == 'font-subset' || setting.rule == 'font-style' ) {
                        self.updateFontParams({'container': control.container, 'selected': font, 'value': setting.value}, true);
                    }
                });
            });
        },

        updateFontElements: function() {
            var self = WPZOOM;

            self.fontElements.each(function() {
                $(this)
                    .html('<option>' + self.l10n.chosen_loading + '</option>')
                    .trigger('chosen:updated');
            });

            self.prepareItems(self.fontChoises, function(response) {
                if (response) {
                    self.insertFontChoices(response);
                }
            });
        },

        updateFontParams: function(params, init) {
            init = init || false;

            var options = $.extend({
                init: init,
                labels: ['font-weight', 'font-subset', 'font-style']
            }, params);

            var self = WPZOOM;
            var active_panel = self.cache.$document.find('.control-section.open');

            if ( options.init ) {

                _.each(options.labels, function(label){

                    options.label = label;

                    self.toggleFontLabels(options.container, options);
                });

            } else if ( ! init && active_panel.length ) {

                _.each(options.labels, function(label) {

                    var customize_control = active_panel.find('[id$="'+ label +'"][class*="customize-control"]');

                    if ( ! customize_control.length ) return;

                    var matches = customize_control.attr('id').match(/customize-control-(.*)/),
                        setting_id = matches[1],
                        $container = api.control(setting_id).container;

                    options.trigger = true;
                    options.label = label;

                    self.toggleFontLabels($container, options);
                });
            }
        },

        toggleFontLabels: function($container, options) {
            var self = WPZOOM;

            var label = options.label,
                triggerSet = options.triggerSet || 'normal';

            var standard_fonts = self.fontParams['standard-fonts'],
                google_fonts = self.fontParams['google-fonts'];

            // Hide all labels
            $container.find('label[for*="' + label + '"]').hide();

            // Standard Fonts
            if ( typeof standard_fonts.fonts[options.selected] != 'undefined' ) {

                // Show needed labels
                $container.find('label[for$="font-weightnormal"]').show();
                $container.find('label[for$="font-weightbold"]').show();
                $container.find('label[for$="font-stylenormal"]').show();
                $container.find('label[for$="font-styleitalic"]').show();

                // Hide 'Font Languages' control
                if ( $container.attr('id').indexOf('font-subset') != -1 ) {
                    $container.hide();
                }

                // Set 'normal' as default
                // or value from options.triggerSet if exists
                if ( options.trigger ) {

                    if ( 'font-weight' == label ) {
                        if ( triggerSet.match(/(100|200|300|400|normal)/) ) {
                            $container.find('label[for$="font-weightnormal"]').trigger('click');
                        }
                        if ( triggerSet.match(/(500|600|700|800|900|bold)/) ) {
                            $container.find('label[for$="font-weightbold"]').trigger('click');
                        }
                    }

                    if ( 'font-style' == label ) {
                        if ( triggerSet.match(/(italic|normal)/) ) {
                            $container.find('label[for$="font-style'+ triggerSet +'"]').trigger('click');
                        }
                    }

                }

            }

            // Google Fonts
            if ( typeof google_fonts.fonts[options.selected] != 'undefined' ) {

                // Loop only font variants: 100, 200, 300, 400, ...
                // and skip variants: 100italic, 200italic, 300italic, 400italic, ...
                _.each(google_fonts.fonts[options.selected].variants, function (variant) {

                    var hasOnlyItalic = google_fonts.fonts[options.selected].variants.length === 1 && variant.indexOf('italic') === 0;
                    var hasOnlyRegular = google_fonts.fonts[options.selected].variants.length === 1 && variant.indexOf('regular') === 0;

                    // font family has only `italic` as font style variant
                    if ( hasOnlyItalic ) {
                        if ( label == 'font-style' ) {
                            // Display font style `italic`
                            $container.find('label[for$="font-style'+ variant +'"]').show();
                            // Set font style 'italic' as default
                            if ( options.trigger ) {
                                // Trigger 'click' if default value is not active
                                if ( ! $container.find('input[id$="font-style'+ variant +'"]').is(':checked') ) {
                                    $container.find('label[for$="font-style'+ variant +'"]').trigger('click');
                                }
                            }
                            return;
                        }
                    }
                    // font family has multiple font style variants
                    // and we need to skip variant that include `italic` style
                    else if ( variant.indexOf('italic') != -1 ) {
                        return;
                    }

                    // Regular is 400
                    if ( variant == 'regular' ) variant = 'normal';

                    // Show needed labels
                    $container.find('label[for$="font-weight'+ variant +'"]').show();

                    if ( hasOnlyItalic ) {
                        $container.find('label[for$="font-stylenormal"]').hide();
                        $container.find('label[for$="font-styleitalic"]').show();
                    }
                    if ( hasOnlyRegular ) {
                        $container.find('label[for$="font-stylenormal"]').show();
                        $container.find('label[for$="font-styleitalic"]').hide();
                    }
                    if ( ! hasOnlyRegular && ! hasOnlyItalic ) {
                        $container.find('label[for$="font-stylenormal"]').show();
                        $container.find('label[for$="font-styleitalic"]').show();
                    }

                    // Set 'normal' as default
                    // or value from options.triggerSet if exists
                    if ( options.trigger && variant == triggerSet ) {

                        // Trigger 'click' if default value is not active
                        if ( ! $container.find('input[id$="font-weight'+ variant +'"]').is(':checked') ) {
                            $container.find('label[for$="font-weight'+ variant +'"]').trigger('click');
                        }
                    }

                    // Show 'bold'
                    if ( variant == '700' || variant == '800' || variant == '900' ) {
                        $container.find('label[for$="font-weightbold"]').show();
                    }

                });


                // Show 'Font Languages' control
                if ( $container.attr('id').indexOf('font-subset') != -1 ) {
                    $container.show();
                }

                // Font subsets (latin, cyrillic, greek, ...)
                _.each(google_fonts.fonts[options.selected].subsets, function (subset) {

                    // Show needed labels
                    $container.find('label[for$="font-subset'+ subset +'"]').show();

                    // Show 'all' label if font has more than 3 subsets
                    if ( google_fonts.fonts[options.selected].subsets.length > 3 ) {
                        $container.find('label[for$="font-subsetall"]').show();
                    }

                    // Set 'latin' as default
                    // or value from options.triggerSet if exists
                    if ( options.trigger ) {

                        if ( ( triggerSet != 'normal' && subset == triggerSet) || (triggerSet == 'normal' && subset == 'latin' ) ) {
                            // Trigger 'click' if default value is not active
                            if ( ! $container.find('input[id$="font-subset'+ subset +'"]').is(':checked') ) {
                                $container.find('label[for$="font-subset'+ subset +'"]').trigger('click');
                            }
                        }

                    }

                });

            }

        },

        insertFontChoices: function(content) {
            var self = this;

            self.fontElements.each(function() {
                var $element = $(this),
                    settingId = $element.data('settingId');

                var foo = content.cloneNode(true);

                $element.html(foo);

                api(settingId, function(setting) {
                    var v = setting();
                    $element
                        .val(v)
                        .trigger('chosen:updated')
                        .off('chosen:showing_dropdown', self.updateFontElements);
                });
            });
        },

        fontResetOption: function() {
            var self = this;

            $('<span class="wpz-customizer-font-reset button" title="' + WPZOOM.l10n.reset_tooltip + '" role="button"><span class="dashicons dashicons-undo"></span> ' + WPZOOM.l10n.reset_button + '</span>')
                .on( 'click', function(e){
                    if ( confirm( WPZOOM.l10n.dialog_confirm ) ) {
                        var $this = $(this);

                        self.updateFontElements();

                        wp.ajax.post( 'wpz_customizer_get_options_defaults', { nonce: wp.customize.settings.nonce['wpzoom-customizer-options-defaults'], wp_customize: 'on' } )
                            .done( function(response){
                                if ( response && response.data ) {
                                    var ajaxData = response.data;

                                    $.each( $this.closest('ul.control-section').find('select[name^="_customize-"], input[name^="_customize-"], input.zoom-range-input, input.zoom-responsive-input'), function(key,val){
                                        var $input = $(val);
                                        var devices = [ 'desktop', 'tablet', 'mobile' ];
                                        var id = $input.attr('id'),
                                            device = $input.attr('data-id'),
                                            isResponsive = $.inArray( device, devices ) !== -1;

                                        if ( isResponsive ) {
                                            id = id.replace('-'+device, '');
                                        }

                                        if ( $input.is('input') ) id = id.replace( /^input_/i, '' ).replace( new RegExp( $.trim( $input.val() ) + '$' ), '' );

                                        if ( id != '' && ( id in ajaxData ) ) {
                                            var dflt = ajaxData[ id ];

                                            if ( id.indexOf('font-subset') !== -1 && !(dflt instanceof Array) ) dflt = [dflt];

                                            if ( isResponsive ) {
                                                var $select_unit = $input.parent().find('.zoom-responsive-select.' + device);

                                                if ( (typeof dflt === 'object') && (device + '-unit' in dflt) ) {
                                                    $select_unit.val( dflt[ device + '-unit' ] );
                                                }

                                                $select_unit.trigger('change');

                                                dflt = (typeof dflt === 'object') && (device in dflt) ? dflt[ device ] : dflt;
                                            }

                                            var newval, original;

                                            if ( $input.is('input[type="checkbox"], input[type="radio"]') ) {
                                                original = $input.prop('checked');
                                                newval = dflt instanceof Array && dflt.indexOf( $input.val() ) !== -1 ? true : ( $input.val() == dflt );
                                                $input.prop('checked', newval);
                                            } else {
                                                original = $input.val();
                                                newval = dflt;
                                                $input.val( newval );
                                            }

                                            if ( newval != original ) {
                                                $input.trigger('chosen:updated').trigger('change');

                                                if ( $input.is('select') ) {
                                                    self.updateFontParams({ 'container': $input.closest('.customize-control'), 'selected': newval, 'value': newval });
                                                }
                                            }
                                        }
                                    } );
                                }
                            } );
                    }

                    e.preventDefault();
                } )
                .appendTo('#customize-theme-controls [id^="sub-accordion-section-font-"] .customize-section-title');
        },

        syncFontFamily: function( toSync ) {
            var self = this;

            var typography_panels = _.filter( self.cache.list_controls, function( control ) {
                return 'body-font-family-sync-all' !== control.id && control.id.match(/(font-family|font-weight|font-subset|font-style)/);
            } );

            var font_family_to_sync = '',
                isChecked = typeof toSync !== 'undefined';

            self.cache.toSync = isChecked;
            self.updateFontElements();

            _.each( typography_panels, function(control){
                var matches = control.id.match(/(font-family|font-weight|font-subset|font-style)/);
                var section = control.params.section,
                    rule = matches[0];

                var options = {
                    trigger: true,
                    triggerSet: 'normal',
                    selected: '',
                    label: rule
                };

                // store values in case we need to revert back to
                if ( typeof self.cache.cloud[ section ][ rule ] === 'undefined' ) {
                    self.cache.cloud[ section ][ rule ] = control.setting.get();
                }

                // match only font-family rule
                if ( 'font-family' === rule ) {
                    var $font_family_chosen = control.container.find('select[name^="_customize-"]');

                    // get selected body font family
                    if ( 'body-font-family' === control.id ) {
                        font_family_to_sync = control.setting.get();
                    }
                    
                    // "Sync all fonts" is checked
                    if ( isChecked ) {
                        if ( '' !== font_family_to_sync ) {
                            control.setting.set( font_family_to_sync );
                        }
                    }
                    // revert back values
                    else {

                        if ( 'body-font-family' !== control.id ) {
                            font_family_to_sync = self.cache.cloud[ section ][ rule ];
                            control.setting.set( font_family_to_sync );
                        }

                    }

                    options['selected'] = font_family_to_sync;
                    options['toSync'] = false;

                    $font_family_chosen.trigger('chosen:updated').trigger('change', options);

                }
                // match rules font-weight, font-subset, font-style
                else {

                    options['triggerSet']   = _.isString( self.cache.cloud[ section ][ rule ] ) ? self.cache.cloud[ section ][ rule ] : JSON.stringify( self.cache.cloud[ section ][ rule ] );
                    options['selected']     = font_family_to_sync;

                    self.toggleFontLabels(control.container, options);
                }

            });
        }
    });

    // Reorderable post meta feature
    WPZOOM = $.extend(WPZOOM_Controls, {
        initRPM: function() {
            var self = this;

            if ( typeof self.rpm !== 'undefined' && typeof self.rpm.homepage_post_meta !== 'undefined' ) {
                var $homepage_meta = $("<div class='post-meta-editor homepage-post-meta'>" + self.buildMetaList( self.rpm.homepage_post_meta ) + "</div>").insertAfter( "#_customize-input-homepage-post-meta" ),
                    $archive_meta = $("<div class='post-meta-editor archive-post-meta'>" + self.buildMetaList( self.rpm.archive_post_meta ) + "</div>").insertAfter( "#_customize-input-archive-post-meta" ),
                    $single_meta = $("<div class='post-meta-editor single-post-meta'>" + self.buildMetaList( self.rpm.single_post_meta ) + "</div>").insertAfter( "#_customize-input-single-post-meta" );

                var $sortables = $homepage_meta.add( $archive_meta ).add( $single_meta ).find( ".post-meta-section-fields" );

                $sortables.each( function() {
                    $( this ).sortable( {
                        axis: "y",
                        connectWith: $( this ).closest( ".post-meta-editor" ).find( ".post-meta-section-fields" ),
                        containment: $( this ).closest( ".post-meta-editor" ),
                        create: function() { $( this ).css( "min-height", $( this ).find( "> li" ).height() + "px" ); },
                        cursor: "move",
                        forcePlaceholderSize: true,
                        handle: "> .dashicons",
                        items: "> li",
                        placeholder: "ui-state-highlight",
                        start: function() { $( this ).height( "auto" ); },
                        tolerance: "pointer",
                        update: function() {
                            $( this ).toggleClass( "empty", $( this ).find( "li:not(.ui-sortable-helper)" ).length < 1 );
                            self.updateValue( $( this ).closest( ".post-meta-editor" ) );
                        }
                    } );
                } );

                $sortables.disableSelection();

                $sortables.find( "> li" )
                    .mousedown( function() {
                        var $cont = $( this ).closest( ".post-meta-section-fields" );
                        $cont.height( $cont.height() );
                    } )
                    .mouseup( function() {
                        $( this ).closest( ".post-meta-section-fields" ).height( "auto" );
                    } );

                $sortables.find("input:checkbox")
                    .on( "change", function(){
                        $(this).closest("li").toggleClass( "unchecked", !$(this).is(":checked") );
                        self.updateValue( $(this).closest( ".post-meta-editor" ) );
                    } );
            }
        },

        buildMetaList: function( array_in ) {
            var output = "";

            if ( array_in && array_in.length > 0 && typeof array_in[0] !== 'undefined' ) {
                if ( typeof array_in[0].name !== 'undefined' && typeof array_in[0].fields !== 'undefined' ) {
                    array_in.forEach( function(v){
                        var section_name = v.name.replace( /_/g, " " ),
                            fields = v.fields;

                        output += "<div class='post-meta-section post-meta-section-" + v.name + "' data-section='" + v.name + "'><h3 class='post-meta-section-name'>" + section_name + "</h3><ul class='post-meta-section-fields'>";

                        fields.forEach( function(z){
                            var checked = z.enabled ? " checked='checked'" : "",
                                clazz = z.enabled ? "" : " unchecked",
                                name = z.field.replace( /_/g, " " ).replace( "previous next", "previous/Next" );

                            output += "<li class='post-meta-" + z.field + clazz + "' data-field='" + z.field + "'><span class='dashicons dashicons-move'></span> <label><input type='checkbox'" + checked + " /> " + name + "</label></li>";
                        } );

                        output += "</ul></div>";
                    } );
                } else {
                    output += "<div class='post-meta-section'><ul class='post-meta-section-fields'>";

                    array_in.forEach( function(v){
                        var checked = v.enabled ? " checked='checked'" : "",
                            clazz = v.enabled ? "" : " unchecked",
                            name = v.field.replace( /_/g, " " ).replace( "previous next", "previous/Next" );

                        output += "<li class='post-meta-" + v.field + clazz + "' data-field='" + v.field + "'><span class='dashicons dashicons-move'></span> <label><input type='checkbox'" + checked + " /> " + name + "</label></li>";
                    } );

                    output += "</ul></div>";
                }
            }

            return output;
        },

        updateValue: function( $wrapper ) {
            var newVal = "";

            if ( $wrapper.find( ".post-meta-section-name" ).length > 0 ) {
                newVal += "[";

                $wrapper.find( ".post-meta-section" ).each( function() {
                    var name = typeof $( this ).data( "section" ) !== "undefined" ? $( this ).data( "section" ) : "",
                        fields = $( this ).find( ".post-meta-section-fields" ),
                        arr = fields.sortable( "toArray", { attribute: "data-field" } ),
                        mapped = arr.map( function( x ) { return '{"field":"' + x + '","enabled":' + fields.find( "li.post-meta-" + x + " input:checkbox" ).is( ":checked" ) + '}'; } );

                    newVal += '{"name":"' + name + '","fields":[' + mapped + ']},';
                } );

                newVal = newVal.replace( /,+$/, "" ) + "]";
            } else {
                var fields = $wrapper.find( ".post-meta-section-fields" ),
                    arr = fields.sortable( "toArray", { attribute: "data-field" } ),
                    mapped = arr.map( function( x ) { return '{"field":"' + x + '","enabled":' + fields.find( "li.post-meta-" + x + " input:checkbox" ).is( ":checked" ) + '}'; } );

                newVal = '[' + mapped + ']';
            }

            if ( $wrapper.hasClass( "homepage-post-meta" ) ) {
                if ( this.rpm.homepage_post_meta != newVal ) {
                    this.rpm.homepage_post_meta = newVal;

                    $wrapper.parent().find( "#_customize-input-homepage-post-meta" )
                        .val( this.rpm.homepage_post_meta )
                        .trigger( "change" );
                }
            } else if ( $wrapper.hasClass( "archive-post-meta" ) ) {
                if ( this.rpm.archive_post_meta != newVal ) {
                    this.rpm.archive_post_meta = newVal;

                    $wrapper.parent().find( "#_customize-input-archive-post-meta" )
                        .val( this.rpm.archive_post_meta )
                        .trigger( "change" );
                }
            } else {
                if ( this.rpm.single_post_meta != newVal ) {
                    this.rpm.single_post_meta = newVal;

                    $wrapper.parent().find( "#_customize-input-single-post-meta" )
                        .val( this.rpm.single_post_meta )
                        .trigger( "change" );
                }
            }
        }
    });

    // Responsive slider height feature
    WPZOOM = $.extend( WPZOOM_Controls, {
        initRSH: function() {
            var self = this;

            if ( typeof self.rsh !== 'undefined' && true == self.rsh ) {
                var $desktop = $( '#customize-control-slideshow_height_desktop' ),
                    $tablet  = $( '#customize-control-slideshow_height_tablet' ),
                    $phone   = $( '#customize-control-slideshow_height_phone' ),
                    $all     = $desktop.add( $tablet ).add( $phone );

                $( '<span class="device-select dashicons dashicons-desktop"></span>' )
                    .appendTo( $desktop.find( '.customize-control-title' ) )
                    .on( 'click', function( e ) { e.preventDefault(); api.previewedDevice.set( 'tablet' ); } );

                $( '<span class="device-select dashicons dashicons-tablet"></span>' )
                    .appendTo( $tablet.find( '.customize-control-title' ) )
                    .on( 'click', function( e ) { e.preventDefault(); api.previewedDevice.set( 'mobile' ); } );

                $( '<span class="device-select dashicons dashicons-smartphone"></span>' )
                    .appendTo( $phone.find( '.customize-control-title' ) )
                    .on( 'click', function( e ) { e.preventDefault(); api.previewedDevice.set( 'desktop' ); } );

                $tablet.add( $phone ).hide();

                api.previewedDevice.bind( function( newDevice ) {
                    var height = '100';

                    $all.hide();

                    if ( 'desktop' == newDevice ) {
                        height = api( 'slideshow_height_desktop' ).get();

                        $desktop.show();
                    } else if ( 'tablet' == newDevice ) {
                        height = api( 'slideshow_height_tablet' ).get();

                        $tablet.show();
                    } else if ( 'mobile' == newDevice ) {
                        height = api( 'slideshow_height_phone' ).get();

                        $phone.show();
                    }

                    api.previewer.send( 'update-slider', parseInt( height ) );
                } );

                api( 'slideshow_height_desktop', function( value ) { value.bind( self.updateSlider ) } );
                api( 'slideshow_height_tablet', function( value ) { value.bind( self.updateSlider ) } );
                api( 'slideshow_height_phone', function( value ) { value.bind( self.updateSlider ) } );
            }
        },

        updateSlider: function() {
            var height = '100',
                currentDevice = api.previewedDevice.get();

            if ( 'desktop' == currentDevice ) {
                height = api( 'slideshow_height_desktop' ).get();
            } else if ( 'tablet' == currentDevice ) {
                height = api( 'slideshow_height_tablet' ).get();
            } else if ( 'mobile' == currentDevice ) {
                height = api( 'slideshow_height_phone' ).get();
            }

            api.previewer.send( 'update-slider', parseInt( height ) );
        }
    } );

    $(document).ready(function() {
        WPZOOM.__construct();
    });

    $('.wp-full-overlay-footer .devices button').on('click', function() {

        var device = $(this).attr('data-device');

        $('.customize-control-zoom_responsive .zoom-range-container, .customize-control .zoom-responsive-btns > li').removeClass( 'active' );
        $('.customize-control-zoom_responsive .zoom-range-container.' + device + ', .customize-control .zoom-responsive-btns > li.' + device).addClass( 'active' );
    });


    api.controlConstructor.zoom_responsive = api.Control.extend({

        // When we're finished loading continue processing.
        ready: function() {

            'use strict';

            var control = this,
                value;

            control.responsiveContainer = control.container.find('.zoom-responsive-container');
            control.responsiveBtns = control.responsiveContainer.find('.zoom-responsive-btns');
            control.responsiveRange = control.responsiveContainer.find('.zoom-range-container');
            control.valueUpdated = false;

            control.zoomResponsiveInit();
            
            /**
             * Save on change / keyup / paste
             */
            control.responsiveRange.on( 'change keyup paste', 'input.zoom-range-input', function() {

                if ( ! control.valueUpdated ) {
                    // Update value on change.
                    control.updateValue();
                }

            });

            /**
             * Refresh preview frame on blur
             */
            control.responsiveRange.on( 'blur', 'input', function() {

                value = $(this).val() || '';

                if ( value == '' ) {
                    api.previewer.refresh();
                }

            });

        },

        /**
         * Updates the sorting list
         */
        updateValue: function() {

            'use strict';

            var control = this,
            newValue = {};

            control.responsiveRange.each( function() {
                var $input      = $(this).find('.zoom-range-input'),
                    $select     = $(this).find( 'select.zoom-responsive-select' ),
                    item        = $input.data('id'),
                    _item       = $select.data('id'),
                    item_value  = $input.val(),
                    _item_value = $select.val();

                newValue[item] = item_value;
                newValue[_item] = _item_value;
            });

            control.setting.set( newValue );
            control.valueUpdated = true;
        },

        zoomResponsiveInit : function() {
            
            'use strict';

            var control = this;

            control.responsiveRange.each(function() {
                var $input  = $(this).find('.zoom-range-input'),
                    $slider = $(this).find('.zoom-range-slider'),
                    $select = $(this).find('select.zoom-responsive-select'),
                    id      = $input.data('id'),
                    value   = parseFloat( $input.val() ),
                    _value  = parseFloat( $input.attr('data-value') ),
                    min     = parseFloat( $input.attr('min') ),
                    max     = parseFloat( $input.attr('max') ),
                    step    = parseFloat( $input.attr('step') );

                value = !isNaN( value ) ? value : _value;

                // Configure the slider
                $slider.slider({
                    value : value,
                    min   : min,
                    max   : max,
                    step  : step,
                    slide : function(e, ui) { $input.val(ui.value) }
                });

                // Debounce the slide event so the preview pane doesn't update too often
                $slider.on('slide', _.debounce(function(e, ui) {
                    control.valueUpdated = false;
                    $input.keyup().trigger('change');
                }, 300));

                $select.on('change', _.debounce(function(){
                    control.valueUpdated = false;
                    $input.trigger('change');
                }, 300));

                // Sync values of number input and slider
                $input.val( $slider.slider('value')).on('change', function() {
                    $slider.slider('value', $(this).val());
                });

                // Listen for changes to the range input.
                $input.on('change', function() {
                    $slider.slider('value', $(this).val());
                    control.valueUpdated = false;
                    control.responsiveRange.trigger('change');
                });

                // // Update the range if the setting changes.
                control.setting.bind(function(value) {
                    if ( typeof value === 'object' ) {
                        $input.val( value[ id ] );
                    }
                });
            });

            control.responsiveBtns.find('button').on( 'click', function( event ) {
                var device = $(this).attr('data-device');
                if( 'desktop' == device ) {
                    device = 'tablet';
                } else if( 'tablet' == device ) {
                    device = 'mobile';
                } else {
                    device = 'desktop';
                }

                $( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
            });
        },
    });


    /**
     * Initialize instances of WPZOOM_Customizer_Control_Select
     *
     * @since 1.7.0
     */
    api.controlConstructor.zoom_color_picker = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-color-picker-container'),
                $input = $('input.zoom-alpha-color-picker', $container);

            $input.alphaColorPicker();
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Select
     *
     * @since 1.7.0
     */
    api.controlConstructor.zoom_select = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-select-container'),
                $input = $('select', $container);

            // Listen for changes to the select.
            $input.on('change', function() {
                var value = $(this).val();
                var value_type = typeof control.setting.get();

                control.setting.set( WPZOOM.convertToType( value, value_type ) );
            });

            // Update the select if the setting changes.
            control.setting.bind(function(value) {
                $input.val(value);
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Sortable
     *
     * @since 1.7.0
     */
    api.controlConstructor.zoom_sortable = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-sortable-container'),
                $label = control.container.find('.customize-control-title'),
                $sortable = $('ol', $container),
                $input = $('input[type="hidden"]', $container);

            var newval;

            $label.on('click', function(e){ e.preventDefault(); $sortable.focus(); });

            $sortable.sortable({
                axis: "y",
                containment: "parent",
                update: function(e, ui){
                    var value_type = typeof control.setting.get();
                        newval = '';

                    // Listen for changes to the sortable.
                    $.each($sortable.sortable("toArray"), function(index, id){
                        var value = $container.find('#' + id).attr('data-item-value');

                        newval += value;
                    });

                    control.setting.set( WPZOOM.convertToType( newval, value_type ) );
                }
            });

            // Update the select if the setting changes.
            control.setting.bind(function(value) {
                $input.val(value);
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Radio
     *
     * @since 1.7.0.
     */
    api.controlConstructor.zoom_radio = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-radio-container');

            $container.each(function() {
                if ($(this).hasClass('zoom-radio-buttonset-container') || $(this).hasClass('zoom-radio-image-container')) {
                    $(this).buttonset();
                }
            });

            // Listen for changes to the radio group.
            $container.on('change', 'input:radio', function(event) {
                var value = $(this).parent().find('input:radio:checked').val();
                var value_type = typeof control.setting.get();

                control.setting.set( WPZOOM.convertToType( value, value_type ) );
            });

            if ( $container.attr('id') == 'input_style-kits-selector' ) {
                $container.find('.ui-button').on('click', function(){
                    var request = wp.ajax.post( 'wpz_customizer_get_stylekit_data', {
                        nonce: wp.customize.settings.nonce['wpzoom-customizer-stylekits'],
                        wp_customize: 'on',
                        stylekit: $(this).attr('for').replace('style-kits-selector', '')
                    });
                    request.done(function(response){
                        _.each(response.data, function(value,id){
                            var setting = wp.customize(id);

                            if ( setting && typeof setting.findControls() !== 'undefined' && setting.findControls().length > 0 && typeof setting.findControls()[0].container !== 'undefined' )
                            {
                                var control = setting.findControls()[0];
                                var $container = $( control.container );

                                setting.set( typeof value === 'object' ? JSON.stringify([value]) : value );
                                $container.find('input, select, textarea').addBack().trigger('change');
                                $container.find('select').trigger('chosen:showing_dropdown').trigger('chosen:updated');

                                if ( typeof value === 'object' )
                                {
                                    if ( 'obj' in control ) control.obj[0] = value;

                                    _.each(value, function(value2,id2){
                                        var $elem = $container.find('[class*="-' + id2 + '"]');

                                        if ( $elem.length > 0 )
                                        {
                                            $elem.val(value2).trigger('change');

                                            if ( $elem.hasClass('wp-color-picker') ) {
                                                $elem.wpColorPicker('color', value2);
                                            } else if ( $elem.hasClass('zoom-range-input') ) {
                                                $elem.siblings('.zoom-range-slider').slider('value', value2);
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    });
                });
            }

            // Update the radio group if the setting changes.
            control.setting.bind(function(value) {
                $container.find('input:radio').filter('[value=' + value + ']').prop('checked', true);
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Background_Gradient
     *
     * @since 1.7.1.
     */
    api.controlConstructor.zoom_background_gradient = api.Control.extend({
        obj: {},
        ready: function() {
            var control = this,
                settings = control.setting.get(),
                $container = control.container.find('.zoom-background-gradient-container'),
                picker = control.container.find( '.color-picker-hex' ),
                directions = control.container.find( 'select#directions' ),
                range = control.container.find( '.range-opacity-container' );

            try {
                control.obj = JSON.parse( settings );
            } catch (e) {
                control.obj = [settings];
            }

            picker.each(function(){
                var id = $(this).attr('id'),
                    value = control.obj[0][ id ];

                control.initColorPicker( $(this), value );
            });

            // Listen for changes to the select.
            directions.on('change', function() {
                var value = $(this).val();

                control.obj[0]['direction'] = value;

                control.setting.set( JSON.stringify(control.obj) );
            });

            // Update the select if the setting changes.
            control.setting.bind(function(value) {
                value = JSON.parse(value);

                directions.val(value[0]['direction']);
            });

            range.each(function() {
                control.initRange( $(this) );
            });
        },
        initColorPicker: function( picker, value ) {
            var control = this,
                updating = false,
                id = picker.attr('id');

            picker.val( value ).wpColorPicker({
                change: function() {
                    updating = true;

                    control.obj['picker'] = picker;
                    control.obj['id'] = id;
                    control.obj[0][ id ] = picker.wpColorPicker( 'color' );

                    control.setting.set( JSON.stringify(control.obj) );
                    updating = false;
                },
                clear: function() {
                    updating = true;

                    control.obj['picker'] = picker;
                    control.obj['id'] = id;
                    control.obj[0][ id ] = '';

                    control.setting.set( JSON.stringify(control.obj) );
                    updating = false;
                }
            });

            control.setting.bind( function ( value ) {
                // Bail if the update came from the control itself.
                if ( updating ) {
                    return;
                }

                value = JSON.parse(value);

                var picker = value[ 'picker' ], id = value[ 'id' ];

                _.each(value[0], function(val, key) {
                    if ( id === key ) {
                        picker.val( val );
                        picker.wpColorPicker( 'color', val );
                    }
                });

            } );


            // Collapse color picker when hitting Esc instead of collapsing the current section.
            control.container.on( 'keydown', function( event ) {
                var pickerContainer;
                if ( 27 !== event.which ) { // Esc.
                    return;
                }
                pickerContainer = control.container.find( '.wp-picker-container' );

                $.each(pickerContainer, function(){
                    if ( $(this).hasClass( 'wp-picker-active' ) ) {
                        if ( typeof control.obj[ 'picker' ] !== 'undefined' ) {
                            control.obj[ 'picker' ].wpColorPicker( 'close' );
                        }

                        $(this).find( '.wp-color-result' ).focus();
                        event.stopPropagation(); // Prevent section from being collapsed.
                    }
                });
            } );
        },
        initRange: function( container ) {
            var control = this;

            var $input = container.find('.zoom-range-input'),
                $slider = container.find('.zoom-range-slider'),
                value = parseFloat( $input.val() ),
                min = parseFloat( $input.attr('min') ),
                max = parseFloat( $input.attr('max') ),
                step = parseFloat( $input.attr('step') );

            // Configure the slider
            $slider.slider({
                value : value,
                min   : min,
                max   : max,
                step  : step,
                slide : function(e, ui) { $input.val(ui.value) }
            });

            // Debounce the slide event so the preview pane doesn't update too often
            $slider.on('slide', _.debounce(function(e, ui) {
                $input.keyup().trigger('change');
            }, 300));

            // Sync values of number input and slider
            $input.val( $slider.slider('value')).on('change', function() {
                $slider.slider('value', $(this).val());
            });

            // Listen for changes to the range.
            $input.on('change', function() {
                var value = $(this).val(),
                    id = $(this).attr('id');

                control.obj['slide'] = $(this);
                control.obj['slide_id'] = id;
                control.obj[0][ id ] = value;

                control.setting.set( JSON.stringify(control.obj) );
            });

            // Update the range if the setting changes.
            control.setting.bind(function(value) {
                var $input = control.obj['slide'],
                    id = control.obj['slide_id'];

                value = JSON.parse(value);

                if ( typeof id !== 'undefined' ) {
                    $input.val(value[0][ id ]);
                }
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Checkbox_Multiple
     *
     * @since 1.7.0.
     */
    api.controlConstructor.zoom_checkbox_multiple = api.Control.extend({
        ready: function() {
            var control = this,
                multiple_values = [],
                $container = control.container.find('.zoom-checkbox-container');

            $container.each(function() {
                if ($(this).hasClass('zoom-checkbox-buttonset-container')) {
                    $(this).buttonset();
                }
            });

            // Map checked values
            $container.find('input:checkbox').each(function() {
                if ( $(this).is(':checked') ) {
                    multiple_values.push( $(this).val() );
                }
            });

            // Listen for changes to the checkbox group.
            $container.on('change', 'input:checkbox', function() {
                var value = $(this).val();
                var isButtonset = $(this).hasClass('zoom-checkbox-buttonset-container');

                // Add new value in array if doesn't contains
                // else remove it
                if ( ! _.contains(multiple_values, value) ) {
                    multiple_values.push( value );
                } else {
                    multiple_values = $.grep(multiple_values, function(val) {
                        return val != value;
                    });
                }

                // Check all checkboxes
                if ( value == 'all' ) {

                    if ( isButtonset ) {
                        $container.find('input:checkbox').prop('checked', this.checked).button('refresh');
                    } else {
                        $container.find('input:checkbox').prop('checked', this.checked);
                    }

                    // Push all values in array if 'all' is checked
                    // else empty array
                    if ( this.checked ) {
                        $container.find('input:checkbox').each(function() {
                            if ( $(this).val() != 'all' ) {
                                multiple_values.push( $(this).val() );
                            }
                        });
                    } else {
                        multiple_values = [];
                    }
                }

                control.setting.set(multiple_values);
            });

            // Update the checkbox group if the setting changes.
            control.setting.bind(function(value) {
                var isButtonset = $container.hasClass('zoom-checkbox-buttonset-container');

                _.each(multiple_values, function(val){

                    if ( isButtonset ) {
                        $container.find('input:checkbox').filter('[value=' + val + ']').prop('checked', true).button('refresh');
                    } else {
                        $container.find('input:checkbox').filter('[value=' + val + ']').prop('checked', true);
                    }

                });
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Checkbox
     *
     * @since 1.7.1.
     */
    api.controlConstructor.zoom_checkbox = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-checkbox-container');

            // Listen for changes to the checkbox.
            $container.on('change', 'input:checkbox', function() {
                var value = $(this).parent().find('input:checkbox:checked').val();
                var value_type = typeof control.setting.get();

                if ( 'body-font-family-sync-all' === control.id ) {
                    WPZOOM.syncFontFamily( value );
                }

                control.setting.set( WPZOOM.convertToType( value, value_type ) );
            });

            // Update the checkbox if the setting changes.
            control.setting.bind(function(value) {
                $container.find('input:checkbox').filter('[value=' + value + ']').prop('checked', true);
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Text
     *
     * @since 1.7.1.
     */
    api.controlConstructor.zoom_text = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-text-container');

            // Listen for changes to the text input.
            $container.on('change', 'input:text', function() {
                var value = $(this).val();
                var value_type = typeof control.setting.get();

                control.setting.set( WPZOOM.convertToType( value, value_type ) );
            });

            // Update the text input if the setting changes.
            control.setting.bind(function(value) {
                $container.find('input:text').val(value);
            });
        }
    });

    /**
     * Initialize instances of WPZOOM_Customizer_Control_Range
     *
     * @since 1.7.0.
     */
    api.controlConstructor.zoom_range = api.Control.extend({
        ready: function() {
            var control = this,
                $container = control.container.find('.zoom-range-container');

            $container.each(function() {
                var $input = $(this).find('.zoom-range-input'),
                    $slider = $(this).find('.zoom-range-slider'),
                    value = parseFloat( $input.val() ),
                    _value = parseFloat( $input.attr('data-value') ),
                    min = parseFloat( $input.attr('min') ),
                    max = parseFloat( $input.attr('max') ),
                    step = parseFloat( $input.attr('step') );

                value = !isNaN( value ) ? value : _value;

                // Configure the slider
                $slider.slider({
                    value : value,
                    min   : min,
                    max   : max,
                    step  : step,
                    slide : function(e, ui) { $input.val(ui.value) }
                });

                // Debounce the slide event so the preview pane doesn't update too often
                $slider.on('slide', _.debounce(function(e, ui) {
                    $input.keyup().trigger('change');
                }, 300));

                // Sync values of number input and slider
                $input.val( $slider.slider('value')).on('change', function() {
                    $slider.slider('value', $(this).val());
                });

                // Listen for changes to the range.
                $input.on('change', function() {
                    var value = $(this).val();
                    control.setting.set(value);
                });

                // Update the range if the setting changes.
                control.setting.bind(function(value) {
                    $input.val(value);
                });
            });
        }
    });

})(wp, jQuery, WPZOOM_Controls);