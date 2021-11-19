wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;

(function( $, modula ){

	var selection = Backbone.Collection.extend({});

	var modal = Backbone.Model.extend({
		initialize: function( args ){

            var selection = new modula.bulkedit['selection']();
            this.set( 'selection', selection );

            var modalView = new modula.bulkedit['view']({
                'model': this,
            });
            this.set( 'modalView', modalView );
            
            var wpMediaView = new wp.media.view.Modal( {
                controller: {
                    trigger: function() {}
                },
                title: 'Bulk Edit'
            } );
            this.set( 'modal', wpMediaView );

        },
        open: function() {
            var modal = this.get( 'modal' ),
                modalView = this.get( 'modalView' );

            // Render Modula View
            modalView.render();
            // Append modulaModalView to wpMediaView
            modal.content( modalView );
            // Open wpMediaView
            modal.open();

        },
        close: function(){
        	var modal = this.get( 'modal' ),
        		selection = this.get( 'selection' );

        	modal.close();
        	selection.reset();
        }
	});

	var modalView = Backbone.View.extend({
		/**
        * The Tag Name and Tag's Class(es)
        */
        tagName:    'div',
        className:  'modula-bulkedit-popup',
        childViews: [],
        items:      [],

        /**
        * Template
        * - The template to load inside the above tagName element
        */
        template:   wp.template( 'modula-bulkedit' ),

        events: {
            'click #close-modula-bulkedit': 'close',
            'click #save-modula-bulkedit': 'save',
            'click #modula-select-all': 'select',
            'click #modula-deselect-all': 'deselect',
            'click #modula-toggle': 'toggle',
            'click #delete-modula-bulkedit': 'delete'
        },

        initialize: function() {
            var self = this;

            $.each( modula.bulkedit['childviews'], function( index, childview ){
                var view = new childview({ 'modal' : self.model });
                self.childViews.push( view );
            });

        },

        /**
        * Render
        * - Binds the model to the view, so we populate the view's fields and data
        */
        render: function() {
        	var self = this,
                selection = this.model.get( 'selection' );

            // Reset selection
            selection.reset();

        	// Get HTML
            this.$el.html( this.template() );

            // Left area
            modula.Items.each( function( item ) {
            	var view = new modula.bulkedit['item']({ 'model' : item, 'modal' : self.model });
            	view.render();
            	self.$el.find('ul.attachments').append( view.el );
            });

            // Right Area
            self.$el.find('.media-sidebar').html('');
            $.each( self.childViews, function( index, childview ){
                self.$el.find('.media-sidebar').append( childview.render().el )
            });

        },

        close: function(){
            var self = this;

            self.$el.find('#save-modula-bulkedit').addClass( 'updating-message' ).attr( 'disabled', 'disabled' );

            $.each( self.childViews, function( index, childview ){
                childview.saveView();
            });

            modula.Save.saveImages( function(){
                self.model.close();
            });

        },

        select: function(){
            var selection = this.model.get( 'selection' );
            selection.add( modula.Items.models );
        },

        deselect: function(){
            var selection = this.model.get( 'selection' );
            selection.reset();
        },

        toggle: function(){
            var selection = this.model.get( 'selection' ),
                oldSelection = selection.clone();

            if ( selection.length == 0 ) {
                selection.add( modula.Items.models );
            }else if ( selection.length == modula.Items.length ) {
                selection.reset();
            }else{
                selection.reset();
                modula.Items.each( function( item ) {
                    if ( ! oldSelection.findWhere( { 'id': item.get('id') } ) ) {
                        selection.add( item );
                    }
                });
            }

            oldSelection.reset();
        },

        save: function(){
            var self = this;

            self.$el.find('#save-modula-bulkedit').addClass( 'updating-message' ).attr( 'disabled', 'disabled' );

            $.each( self.childViews, function( index, childview ){
                childview.saveView();
            });

            clearInterval( wp.Modula.Save.updateInterval );
            modula.Save.saveImages( function(){
                setTimeout(function(){
                    self.$el.find('#save-modula-bulkedit').removeClass( 'updating-message' ).removeAttr( 'disabled' );
                }, 1000);
            });

        },

        delete: function(){
            var self = this,
                selection = self.model.get( 'selection' ),
                items = selection.toJSON();

            _.each( items, function( item ){
                var model = selection.findWhere( { 'id': item.id } );
                model.delete();
            });

        }

	});

	var filtersView = Backbone.View.extend({
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
        selectize: false,

        /**
        * Initialize
        */
        initialize: function( args ) {
            var view = this,
            selection;

            this.modal = args.modal;
            this.isSelectize = false;

            // Listen to section
            selection = this.modal.get( 'selection' );
            this.listenTo( selection, 'add', view.updateFilters );
            this.listenTo( selection, 'remove', view.updateFilters );
            this.listenTo( selection, 'reset', view.updateFilters );

        },

        render: function() {

            var choices = [],
                values = [];

            _.each( modula.Settings.get( 'filters' ), function( value ){
                choices.push( { 'value' : value, 'text' : value } );
            });
            

            this.$el.html( this.template({ 'filters': '' }) );

            if ( ! this.isSelectize ) {
                this.selectize = this.$( '#modula-pro-filters' ).selectize({
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

        updateFilters: function(){
            if ( this.selectize ) {
                var selectize = this.selectize[0].selectize;
                selectize.clear();
            }
        },

        saveView: function(){
            var self = this,
                selectize = this.selectize[0].selectize,
                filters = selectize.getValue(),
                selection = this.modal.get( 'selection' );

            if ( selection.length > 0 ) {
                selection.each( function( item ) {
                    item.set( 'filters', filters );
                });
            }
        }

    });

	var bulkEditItem = Backbone.View.extend({
		/**
        * The Tag Name and Tag's Class(es)
        */
        tagName:    'li',
        className:  'attachment',

        /**
        * Template
        * - The template to load inside the above tagName element
        */
        template:   wp.template( 'modula-bulkedit-item' ),

        events: {
            'click': 'checkStatus',
        },

        initialize: function( args ) {
		    this.modal = args.modal;

		    var selection = this.modal.get( 'selection' );

		    this.listenTo( selection, 'add', this.itemAdded );
            this.listenTo( selection, 'remove', this.itemRemoved );
		    this.listenTo( selection, 'reset', this.removeItem );

            this.listenTo( this.model, 'destroy', this.remove );

		},

        /**
        * Render
        * - Binds the model to the view, so we populate the view's fields and data
        */
        render: function() {

        	// Get HTML
            this.$el.html( this.template( this.model.toJSON() ) );

        },

        checkStatus: function() {
        	var selection = this.modal.get( 'selection' );

        	if ( this.$el.hasClass( 'selected' ) ) {
        		selection.remove( this.model );
        		// this.$el.removeClass( 'selected details' );
        	}else{
        		selection.add( this.model );
        		// this.$el.addClass( 'selected details' );
        	}
        },

        itemAdded: function() {
        	var selection = this.modal.get( 'selection' );

        	if ( selection.findWhere( { 'id': this.model.get('id') } ) ) {
        		this.$el.addClass( 'selected details' );
        	}

        },

        itemRemoved: function() {
        	var selection = this.modal.get( 'selection' );
        	if ( ! selection.findWhere( { 'id': this.model.get('id') } ) ) {
        		this.$el.removeClass( 'selected details' );
        	}

        },

        removeItem: function() {
            this.$el.removeClass( 'selected details' );
        }

	});

    modula.bulkedit = {
        'model' : modal,
        'view' : modalView,
        'selection' : selection,
        'childviews' : [ filtersView ],
        'item' : bulkEditItem
    };

}( jQuery, wp.Modula ))