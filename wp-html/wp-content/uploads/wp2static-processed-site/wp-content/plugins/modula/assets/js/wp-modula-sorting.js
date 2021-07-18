wp.Modula = 'undefined' === typeof (wp.Modula) ? {} : wp.Modula;

(function ($, modula) {
    modula.sorting = {
        items: [],
        sorted: [],
        /**
         * This is the base item, we'll create the grid's structure with it
         * @param {object} params 
         */
        baseItem: function (params) {
            return {
                height: params.attributes.height,
                width: params.attributes.width,
            }
        },
        /**
         * This object contains the extended properties of the first item 
         * and we'll use it to popuplate the grid structure
         */
        extendedProperties: function ( model ) {
            var attributes = model.toJSON();
            delete attributes['view'];
            delete attributes['height'];
            delete attributes['width'];
            delete attributes['resize'];

            return attributes;
        },
        /**
         * Initiates the script
         */
        init: function () {
            this.sortItems = this.sortItems.bind(this);
            this.attachEvent();

            // Get all the information from server
            var ids = modula.Items.pluck('id');

            data = {
                'action': 'query-attachments',
                'query' : {
                    'post__in' : ids
                }
            };

            jQuery.post( modulaHelper.ajax_url, data, function( data ) {

                if ( ! data.success ) { return; }

                jQuery.each( data.data, function( i, item ){
                    
                    var image = modula.Items.get( item['id'] );
                    if ( image ) {
                        image.set( 'modified', item['modified'] );
                        image.set( 'date', item['date'] );
                        image.set( 'original-title', item['title'] );
                    }
                });

            }, 'json' );

        },
        /**
         * Attaches the event to the radio inputs and whenever the value changes,
         * we try to re-order the images depending on that particular condition
         */
        attachEvent() {
            var self = this;
            $('.modula-sorting-container').find('input').on('change', function () {
                self.sortItems($(this).val());
            });
        },
        /**
         * Before each sorting, we need to gather the items in 2 distinct arrays
         * 
         * 1. First array will hold the grid structure ( as placeholders ). 
         * It does not have any information regarding the image (title,description,alt, etc)
         * 
         * 2. Second array holds the images, we don't care about their size or position.
         * At this current point, we know that we have X placeholders and X images and in order
         * to keep the grid structure, we only need to fill "placeholder items" with the ones that
         * are "changeable", which is this array.
         * 
         * For example, 
         * arrayOne[0] <---> arrayOne[1] = sortedArray = [ { propsFrom->arrayOne[0] merged with propsFrom->arrayOne[3] } ]
         */
        _gatherItems() {
            var self = this;
            _.each(modula.Items.models, function (e) {
                self.items.push(self.baseItem(e));
                self.sorted.push(self.extendedProperties(e))
            });
        },
        /**
         * For example (more information can be found at the _gatherItems() function), 
         * arrayOne[0] <---> arrayOne[1] = sortedArray = [ { propsFrom->arrayOne[0] merged with propsFrom->arrayOne[3] } ]
         */
        _sort() {
            var self = this;

            _.each(self.sorted, function (el, index) {
                self.items[index] = _.extend( el, self.items[index] );
                self.items[index].index = index;
            });
        },
        /**
         * Given a string, performs a sorting on the array.
         * 
         * Should gather all items,
         * Apply sort
         * Trigger event
         * 
         * @param {string} key 
         */
        sortItems(key) {
            var self = this;
            this._gatherItems();
            switch (key) {
                case 'dateCreatedOld':
                    this.sorted = _.sortBy(self.sorted, function (o) { return o.date; }).reverse()
                    break;
                case 'titleAZ':
                    this.sorted = _.sortBy(self.sorted, function (o) {

                        if("" != o['title']){
                            return o['title'];
                        } else {
                            return o['original-title'];
                        }
                    });
                    break;
                case 'titleZA':
                    this.sorted = _.sortBy(self.sorted, function (o) {

                        if("" != o['title']){
                            return o['title'];
                        } else {
                            return o['original-title'];
                        }
                    }).reverse();
                    break;
                case 'random':
                    this.sorted = _.sortBy(self.sorted, function (o) { return Math.random() - 0.5; });
                    break;
                case 'dateModifiedFirst':
                    this.sorted = _.sortBy(self.sorted, function (o) { return o.modified; });
                    break;
                case 'dateModifiedLast':
                    this.sorted = _.sortBy(self.sorted, function (o) { return o.modified; }).reverse();
                    break;
                case 'dateCreatedNew':
                default:
                    this.sorted = _.sortBy(self.sorted, function (o) { return o.date; });
                    break;
            }

            this._sort();
            $(document).trigger('modula:sortChanged', { items: this.items });
            this.items = [];
            this.sorted = [];
        }
    }
}(jQuery, wp.Modula))