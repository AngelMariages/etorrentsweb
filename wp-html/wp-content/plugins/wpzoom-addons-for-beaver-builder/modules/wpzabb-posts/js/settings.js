jQuery( function($){
	$( 'body' ).delegate( '.fl-builder-settings-tabs a', 'click', function(e){
		var tab  = $( this ),
		    form = tab.closest( '.fl-builder-settings' ),
		    id   = tab.attr( 'href' ).split( '#' ).pop();

		if ( id == 'fl-builder-settings-tab-filter' )
		{
			form.find( '#as-selections-posts_post' ).before( '<label for="posts_post" class="extra-space">Start typing post names here:</label>' );
			form.find( '#as-selections-tax_post_category' ).before( '<label for="tax_post_category" class="extra-space">Start typing category names here:</label>' );
			form.find( '#as-selections-tax_post_post_tag' ).before( '<label for="tax_post_post_tag" class="extra-space">Start typing tag names here:</label>' );
			form.find( '#as-selections-users' ).before( '<label for="users" class="extra-space">Start typing author names here:</label>' );
		}
	} );
} );