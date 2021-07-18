wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;
wp.Modula.models = 'undefined' === typeof( wp.Modula.models ) ? {} : wp.Modula.models;
wp.Modula.views  = 'undefined' === typeof( wp.Modula.views ) ? {} : wp.Modula.views;
wp.Modula.modalChildViews = 'undefined' === typeof( wp.Modula.modalChildViews ) ? [] : wp.Modula.modalChildViews;

var ModulaFilterModalView = Backbone.View.extend({

	/**
    * The Tag Name and Tag's Class(es)
    */
    tagName:    'div',
    className:  'setting modula-filters',

    /**
    * Template
    * - The template to load inside the above tagName element
    */
    template:   wp.template( 'modula-filters' ),

    /**
    * Initialize
    */
    initialize: function( args ) {
    	var view = this;

        this.model = args.model;
        this.isSelectize = false;

    },

    render: function() {

    	var choices = [],
    		item = this.model.get( 'item' ),
    		values = item.get( 'filters' );

    	if ( values ) {
    		values = values.split( ',' );
    	}

    	_.each( wp.Modula.Settings.get( 'filters' ), function( value ){
        	choices.push( { 'value' : value, 'text' : value } );
        });
    	

		this.$el.html( this.template( this.model.attributes ) );

		if ( ! this.isSelectize ) {
			this.$( '#modula-pro-filters' ).selectize({
			    plugins: ['remove_button'],
			    options: choices,
			    items: values,
			    delimiter: ',',
			    persist: true,
			    create: function( input ) {

			    	var filters = wp.Modula.Settings.get( 'filters' ),
			    		newFilters = _.clone( filters );
			    	newFilters.push( input );
			    	wp.Modula.Settings.set( 'filters', newFilters );

			        return {
			            value: input,
			            text: input
			        }
			    }
			});
		}

	    return this;

	},

});

wp.Modula.modalChildViews.push( ModulaFilterModalView );

wp.Modula.models.filters = Backbone.Model.extend({

	defaults: {
        'filters': [],
    },
    updateTimeout: false,

	initialize: function( args ){

  		var view = new wp.Modula.views.filters({
  			model: this,
  			el: jQuery( '#modula-filters' )
  		});

        var defaultSelectView = new wp.Modula.views.filterSelectView({
            model: this,
            el: jQuery( 'select[data-setting="defaultActiveFilter"]' )
        });

  		this.set( 'view', view );

    },

    updateSettings: function() {
    	var model = this;

    	if ( this.updateTimeout ) {
            clearTimeout( this.updateTimeout );
        }

        this.updateTimeout = setTimeout(function () {

            var currentFilters = model.get( 'filters' ),
            	newFilters = _.clone( currentFilters );

    		// wp.Modula.Settings.set( { 'filters': newFilters }, { silent: true } );
    		wp.Modula.Settings.set( { 'filters': newFilters } );

        }, 200);
    	
    }
});

wp.Modula.views.filters = Backbone.View.extend({

	events: {
		// Delete filter
		'click .modula-delete-filter':   'deleteFilter',

		// Add filter
		'click #modula-add-filter':   'addFilter',

		// Settings specific events
        'keyup input':         'updateModel',
        'change input':        'updateModel',
    },

    childHTML: '<div class="modula-filter-input"><span class="dashicons dashicons-move"></span><input type="text" name="modula-settings[filters][]" value="" class="regular-text"><a href="#" class="modula-delete-filter"><span class="dashicons dashicons-trash"></span></a></div>',


    initialize: function( args ) {

    	this.filters = this.$( '.modula-filter-input' );

    	this.listenTo( wp.Modula.Settings, 'change:filters', this.changedFilters );

    	// initialize 3rd party scripts
    	this.initSortable();

    },

    changedFilters: function( settings, value ) {

    	var settingsFilters = wp.Modula.Settings.get( 'filters' ),
    		currentFilters  = this.model.get( 'filters' ),
    		newFilters      = _.difference( settingsFilters, currentFilters ),
    		view            = this;

    	_.each( newFilters, function( filter ){

    		var filterHTML = jQuery( view.childHTML );

    		filterHTML.find( 'input' ).val( filter );
    		view.$( '.modula-filters' ).append( filterHTML );

    		// Actualize current filters
    		currentFilters.push( filter );
    		view.model.set( 'filters', currentFilters );

    	});

    },

    updateModel: function( event ) {
    	var currentFilter = this.$( event.target ).parents( '.modula-filter-input' ),
    		filters = this.model.get( 'filters' ),
    		index = this.filters.index( currentFilter );

        filters[ index ] = event.target.value;

        // Update the model
        this.model.set( 'filters', filters );

        // Update settings
    	this.model.updateSettings();

    },

    deleteFilter: function ( event ) {

    	var currentFilter = this.$( event.target ).parents( '.modula-filter-input' ),
    		filters = this.model.get( 'filters' ),
    		index = this.filters.index( currentFilter );

    	event.preventDefault();

    	filters.splice( index, 1 );
    	currentFilter.remove();

    	// Update settings
    	this.model.updateSettings();


    },

    addFilter: function ( event ) {

    	var filterHTML = jQuery( this.childHTML ),
    		filters = this.model.get( 'filters' );

    	event.preventDefault();

    	this.$( '.modula-filters' ).append( filterHTML );
    	this.filters = this.$( '.modula-filter-input' );
    	filters.push( '' );
    	this.model.set( 'filters', filters );

    	// Update settings
    	this.model.updateSettings();

    },


    initSortable: function() {
    	var view = this;
    	this.$( '.modula-filters' ).sortable({
			items: ".modula-filter-input",
			helper: "clone",
			stop: function( event, ui ) {
				view.orderFilters();
			},
		});
    },

    orderFilters: function() {

    	var newFilters = [],
    		view = this;

    	this.filters = this.$( '.modula-filter-input' );
    	this.filters.each( function( index, el ){
    		var value = view.$( el ).find('input').val();
    		newFilters.push( value );
    	});

    	view.model.set( 'filters', newFilters );

    	// Update settings
    	this.model.updateSettings();

    }
   
});

/**
 * View to populate default filter selector with filters user entered above
 *
 */
wp.Modula.views.filterSelectView = Backbone.View.extend({

    initialize: function( args ) {

        this.listenTo( wp.Modula.Settings, 'change:filters', this.changedFilters );

        // initialize 3rd party scripts
        this.initSelectize();

    },

    changedFilters: function( settings, value ) {

        var settingsFilters = wp.Modula.Settings.get( 'filters' ),
            filters         = [],
            view            = this;

        filters.push( this.default );

        _.each( settingsFilters, function( filter ){
            filters.push( { value: filter, text: filter } );
        });

        this.selectize.clearOptions();
        this.selectize.addOption( filters );

    },

    initSelectize: function() {
        var select = this.$el.selectize();

        this.selectize = select[0].selectize;

        this.default = '';
        if ( 'undefined' != typeof this.selectize.options['All'] ) {
         this.default = { value: 'All', text: this.selectize.options['All']['text'] }
        }

    }

});

jQuery(document).ready(function(){

    /**
	 * Styling preview for filters
	 */
	jQuery('.filter_style_preview').click(function(event){
		event.preventDefault();
		var list_parent = jQuery(this).parent();
		var list = jQuery(this).parents('ul.modula_menu__list');
		if(!list_parent.hasClass('modula_menu__item--current')){
			list_parent.addClass('modula_menu__item--current');
			list.find('li').not(list_parent).removeClass('modula_menu__item--current');
		}
	});

	jQuery('select[name="modula-settings[filterStyle]"]').on('change',function(){
		var selected_option = jQuery(this).find('option:selected').val();
		jQuery('.filter-style-preview').removeClass().addClass('menu filters filter-style-preview menu--'+ selected_option);

	});

});