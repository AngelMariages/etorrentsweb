( function ( $, document ) {
	'use strict';

	/**
	 * Set the Image ID of the Featured Image
	 *
	 * @param {int} id The post_id of the image to use as Featured Image.
	 *
	 * @global
	 */
	parent.WPSetThumbnailID = function(id){
		wp.data.dispatch('core/editor').editPost({featured_media : id})
	};

	/**
	 * Overwrite the content of the Featured Image postbox
	 *
	 * @param {string} html New HTML to be displayed in the content area of the postbox.
	 *
	 * @global
	 */
	parent.WPSetThumbnailHTML = function(html){
		return html;
	};

} )( jQuery, document );