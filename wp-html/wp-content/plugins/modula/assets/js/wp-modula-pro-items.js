wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;
wp.Modula.items = 'undefined' === typeof( wp.Modula.items ) ? {} : wp.Modula.items;

(function( $, modula ){

	var modulaProItemsCollection = modula.items['collection'].extend({

		initialize: function () {
            // Listen to remove items from collections
            this.listenTo(this, 'remove', $.proxy(wp.Modula.Save.checkSave, wp.Modula.Save));
            this.listenTo(this, 'add', $.proxy(wp.Modula.Save.checkSave, wp.Modula.Save));

            this.changeOrder = this.changeOrder.bind(this);
        },

		addItem: function( model ) {
            this.add( model );
        },

        changeOrder: function (event, params) {
            _.each(modula.Items.models, function (el) {
                el.get('view').remove();
            });
            modula.Items.reset();

            _.each(params.items, function (el) {
                var item = new modula.items['model'](el);
                item.addToCollection();
            })
        }

    });

    var modulaPROItem = modula.items['model'].extend({

    	addToCollection() {
            // Check if wp.Modula.Items exist
            modula.Items = 'undefined' === typeof (modula.Items) ? new modula.items['collection']() : modula.Items;

            // Add this model to items
            modula.Items.addItem(this);

            // Set collection index to this model
            this.set('index', modula.Items.indexOf(this));
        },

    	deleteView: function () {
            this.get('view').remove();
        }

    });

    var modulaPROItemView = modula.items['view'].extend({

        /**
        * Events
        * - Functions to call when specific events occur
        */
        events: {
            'click .modula-edit-image'   :   'editImage',
            'click .modula-delete-image' :   'deleteImage',
            'click .modula-replace-image':   'replaceImage',
            'resize'                     :   'resizeImage',
            'resizestop'                 :   'resizeStop',
            'modula:updateIndex'         :   'updateIndex',
        },

        replaceImage: function( event ) {
            var item = this.model;

            event.preventDefault();

            modula.replace.open( item.cid );

        }

    });

	modula.items['collection'] = modulaProItemsCollection;
	modula.items['model'] = modulaPROItem;
    modula.items['view'] = modulaPROItemView;

}( jQuery, wp.Modula ))