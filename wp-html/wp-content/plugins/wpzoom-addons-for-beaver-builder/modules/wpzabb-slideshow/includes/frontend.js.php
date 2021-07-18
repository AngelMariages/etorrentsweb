( function( $ ) {
	var slider = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides' );

	slider.on( 'ready.flickity', onSlideshowStart );

	slider.flickity( {
		accessibility: true,
		adaptiveHeight: false,
		autoPlay: <?php echo 'yes' == $settings->slideshow_auto ? '' . intval( $settings->slideshow_speed ) : 'false'; ?>,
		cellAlign: 'center',
		cellSelector: '.wpzabb-slideshow-slide',
		contain: true,
		draggable: '>1',
		dragThreshold: 3,
		fade: <?php echo 'slide' == $settings->slideshow_transition ? 'false' : 'true'; ?>,
		freeScroll: false,
		friction: <?php echo floatval( $settings->slideshow_transition_speed ); ?>,
		groupCells: false,
		imagesLoaded: true,
		initialIndex: 0,
		lazyLoad: true,
		pauseAutoPlayOnHover: <?php echo 'yes' == $settings->slideshow_hoverpause ? 'true' : 'false'; ?>,
		percentPosition: true,
		prevNextButtons: <?php echo 'yes' == $settings->slideshow_arrows ? 'true' : 'false'; ?>,
		pageDots: <?php echo 'dots' == $settings->slideshow_navigation ? 'true' : 'false'; ?>,
		resize: true,
		setGallerySize: true,
		watchCSS: false,
        arrowShape: {
          x0: 10,
          x1: 60, y1: 50,
          x2: 65, y2: 45,
          x3: 20
        },
		wrapAround: <?php echo 'yes' == $settings->slideshow_loop ? 'true' : 'false'; ?>
	} );

	<?php if ( 'thumbs' == $settings->slideshow_navigation ) : ?>
		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-thumbs-nav' ).flickity( {
			accessibility: true,
			adaptiveHeight: false,
			asNavFor: '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides',
			autoPlay: false,
			cellAlign: 'center',
			cellSelector: '.wpzabb-slideshow-thumbs-nav-item',
			contain: true,
			draggable: '>1',
			dragThreshold: 3,
			fade: false,
			freeScroll: false,
			groupCells: 4,
			imagesLoaded: true,
			initialIndex: 0,
			lazyLoad: true,
			percentPosition: true,
			prevNextButtons: true,
			pageDots: false,
			resize: false,
			rightToLeft: false,
			setGallerySize: true,
			watchCSS: false,
			wrapAround: <?php echo 'yes' == $settings->slideshow_loop ? 'true' : 'false'; ?>
		} );
	<?php endif; ?>

	$( function() {
<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>]%c Document ready!',
		               'color:grey', 'color:inherit' );
<?php endif; ?>
	} );

	function onSlideshowStart() {
		var slides = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide' ),
		    maxSlidesHeight = -1;

		$( slides ).each( function() {
			var detailsHeight = $( this ).find( '.wpzabb-slideshow-slide-details' ).height();

<?php if ( 'no' == $settings->slideshow_autoheight ) : ?>
			if ( detailsHeight > maxSlidesHeight ) {
				maxSlidesHeight = detailsHeight;
			}
<?php endif; ?>
		} );

<?php if ( 'no' == $settings->slideshow_autoheight ) : ?>
		var tree = $( slides ).add( $( slides ).closest( '.wpzabb-slideshow-slides' ) )
		                      .add( $( slides ).closest( '.flickity-viewport' ) )
		                      .add( $( slides ).closest( '.flickity-slider' ) )
		                      .add( $( slides ).find( '.wpzabb-slideshow-slide-outer-wrap' ) );

		tree.height( maxSlidesHeight );
<?php endif; ?>
	}
} )( jQuery );