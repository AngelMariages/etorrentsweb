Array.prototype.moveElement = function (from, to) {
    this.splice(to, 0, this.splice(from, 1)[0]);
};
document.addEventListener("DOMContentLoaded", function (event) {
    var hashData = wpzoom_featured_posts_data;
    var instance = new Vue({
        el: '#wpzoom-featured-posts-wrapper',
        template: '#tmpl-zoom-featured-posts',
        data: {
            posts: hashData.posts,
            indexedPosts: hashData['indexedPosts'],
            buttonLabel: hashData.buttonLabel,
            itemsButtonLabel : hashData.buttonReorderListLabel,
            reorderItemsDesc : hashData.reorderItemsDesc,
            headingTitle: hashData.headingTitle,
            postsLimit: hashData.postsLimit,
            showRemoveControl: hashData.showRemoveControl,
            isAjax: false,
            changedPosts: [],
            viewMode:hashData.viewMode
        },

        computed: {
            hasChangedPosts: function () {
                return {
                    disabled: !(this.changedPosts.length > 0)
                };

            }
        },
        watch: {
            posts: function () {
                var that = this;
                that.changedPosts = [];
                that.posts.forEach(function (el) {
                    if (that.indexedPosts[el['ID']].menu_order !== el.menu_order) {
                        that.changedPosts.push(el);
                    }
                });
            }
        },
        methods: {
            switchViewMode:function(mode){
                this.viewMode =  mode;

                wp.ajax.post(
                    hashData.callbacks.set_view_mode,
                    {
                        'nonce_set_view_mode': hashData.nonce_set_view_mode,
                        'view_mode': mode
                    }
                ).done(function(){

                });

            },
            styleObject: function (key) {
                var value = this.isAjax ? "hidden" : "visible";
                var styles = {visibility: value};
                if (key >= this.postsLimit) {
                    styles['opacity'] = '0.7';
                }
                return styles;
            },
            up: function (key) {
                var oldMenuOrder = this.posts[key].menu_order;
                this.posts[key].menu_order = this.posts[key + 1].menu_order;
                this.posts[key + 1].menu_order = oldMenuOrder;
                this.posts.moveElement(key, key + 1);
            },
            down: function (key) {
                var oldMenuOrder = this.posts[key].menu_order;
                this.posts[key].menu_order = this.posts[key - 1].menu_order;
                this.posts[key - 1].menu_order = oldMenuOrder;
                this.posts.moveElement(key, key - 1);
            },
            onEnd: function (event) {

                var draggedIndex = event.oldIndex;
                var movedIndex = event.newIndex;
                var lower = draggedIndex < movedIndex ? draggedIndex : movedIndex;
                var higher = draggedIndex > movedIndex ? draggedIndex : movedIndex;
                var sliced = this.posts.slice(lower, higher+1);

                var max = sliced.reduce(function(accumulator, currentValue){
                    return  accumulator > currentValue.menu_order ? accumulator : currentValue.menu_order;
                });

                sliced.map(function(current){
                    current.menu_order = max;
                    max--;
                });

                var args = [lower, sliced.length].concat(sliced);
                this.posts.splice.apply(this.posts, args );
            },
            remove: function (key) {

                var that = this;
                wp.ajax.post(
                    hashData.callbacks.set_featured,
                    {
                        'nonce_set_featured': hashData.nonce_set_featured,
                        'post_id': that.posts[key].ID,
                        'value': 0,
                        beforeSend: function () {
                            that.isAjax = true;
                        }
                    }
                ).done(function () {
                    that.posts.splice(key, 1);
                }).always(function () {
                    that.isAjax = false;
                });


            },
            save: function () {

                var that = this;
                if (this.changedPosts.length > 0) {
                    wp.ajax.post(
                        hashData.callbacks.save_order,
                        {
                            'nonce_save_order': hashData.nonce_save_order,
                            'posts': that.changedPosts,
                            beforeSend: function () {
                                that.isAjax = true;
                            }
                        }
                    ).done(function () {
                    }).always(function () {
                        window.location.reload();
                    });
                }
            },
            reorderItems: function () {
                var that = this;

                wp.ajax.post(
                    hashData.callbacks.reorder_items,
                    {
                        'nonce_reorder_items': hashData.nonce_reorder_items,
                        beforeSend: function () {
                            that.isAjax = true;
                        }
                    }
                ).done(function (response) {
                    window.location.reload();
                }).always(function () {
                });
            }
        }
    });
});
