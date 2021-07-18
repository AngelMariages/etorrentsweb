(function($) {

	WPZABBPostsModule = function(settings)
	{
		this.settings    = settings;
		this.nodeClass   = '.fl-node-' + settings.id;

		this.wrapperClass = this.nodeClass + ' .wpzabb-post-' + this.settings.layout;
		this.postClass    = this.nodeClass + ' .wpzabb-post-column, ' + this.nodeClass + ' .wpzabb-post-list-post';

		if(this._hasPosts()) {
			this._initLayout();
			this._initInfiniteScroll();
		}
	};

	WPZABBPostsModule.prototype = {

		settings        : {},
		nodeClass       : '',
		wrapperClass    : '',
		postClass       : '',
		gallery         : null,
		currPage		: 1,
		totalPages		: 1,

		_hasPosts: function()
		{
			return $(this.postClass).length > 0;
		},

		_initLayout: function()
		{
			$(this.postClass).css('visibility', 'visible');
		},

		_initInfiniteScroll: function()
		{
			var isScroll = 'scroll' == this.settings.pagination || 'load_more' == this.settings.pagination,
				pages	 = $( this.nodeClass + ' .fl-builder-pagination' ).find( 'li .page-numbers:not(.next)' );

			if( pages.length > 1) {
				total = pages.last().text().replace( /\D/g, '' )
				this.totalPages = parseInt( total );
			}

			if( isScroll && this.totalPages > 1 && 'undefined' === typeof FLBuilder ) {
				this._infiniteScroll();

				if( 'load_more' == this.settings.pagination ) {
					this._infiniteScrollLoadMore();
				}
			}
		},

		_infiniteScroll: function(settings)
		{
			var path 		= $(this.nodeClass + ' .fl-builder-pagination a.next').attr('href'),
				pagePattern = /(.*?(\/|\&|\?)paged-[0-9]{1,}(\/|=))([0-9]{1,})+(.*)/,
				pageMatched = null,
				scrollData	= {
					navSelector     : this.nodeClass + ' .fl-builder-pagination',
					nextSelector    : this.nodeClass + ' .fl-builder-pagination a.next',
					itemSelector    : this.postClass,
					prefill         : true,
					bufferPx        : 200,
					loading         : {
						msgText         : 'Loading',
						finishedMsg     : '',
						img             : FLBuilderLayoutConfig.paths.pluginUrl + 'img/ajax-loader-grey.gif',
						speed           : 1
					}
				};

			// Define path since Infinitescroll incremented our custom pagination '/paged-2/2/' to '/paged-3/2/'.
			if ( pagePattern.test( path ) ) {
				scrollData.path = function( currPage ){
					pageMatched = path.match( pagePattern );
					path = pageMatched[1] + currPage + pageMatched[5];
					return path;
				}
			}

			$(this.wrapperClass).infinitescroll( scrollData, $.proxy(this._infiniteScrollComplete, this) );

			setTimeout(function(){
				$(window).trigger('resize');
			}, 100);
		},

		_infiniteScrollComplete: function(elements)
		{
			var wrap = $(this.wrapperClass);

			elements = $(elements);

			if(this.settings.layout == 'grid') {
				wrap.imagesLoaded( $.proxy( function() {
					wrap.masonry('appended', elements);
					elements.css('visibility', 'visible');
				}, this ) );
			}

			if( 'load_more' == this.settings.pagination ) {
				$( '#infscr-loading' ).appendTo( this.wrapperClass );
			}

			this.currPage++;

			this._removeLoadMoreButton();
		},

		_infiniteScrollLoadMore: function()
		{
			var wrap = $( this.wrapperClass );

			$( window ).unbind( '.infscr' );

			$(this.nodeClass + ' .fl-builder-pagination-load-more .wpzabb-button').on( 'click', function(){
				wrap.infinitescroll( 'retrieve' );
				return false;
			});
		},

		_removeLoadMoreButton: function()
		{
			if ( 'load_more' == this.settings.pagination && this.totalPages == this.currPage ) {
				$( this.nodeClass + ' .fl-builder-pagination-load-more' ).remove();
			}
		}
	};

})(jQuery);
