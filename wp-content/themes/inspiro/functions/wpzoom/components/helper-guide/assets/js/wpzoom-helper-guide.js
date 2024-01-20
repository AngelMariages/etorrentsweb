let wpzoomHelperGuideLauncher = '',
		wpzoomHelperGuideMobileClose = '',
		wpzoomHelperGuideContainer = '',
		wpzoomHelperGuideContent = '',
		wpzoomHelperGuideHeader = '',
		wpzoomHelperGuideBackButton = '',
		wpzoomHelperGuideAccentColor = '',
		wpzoomHelperGuideTocTriggers = '';

// init
function wpzoomHelperGuideInit() {
	wpzoomHelperGuideLauncher = document.querySelector( '#wpzoom-helper-guide-launcher--button' );
	wpzoomHelperGuideMobileClose = document.querySelector( '#wpzoom-helper-guide-mobile-close' );
	wpzoomHelperGuideContainer = document.querySelector( '#wpzoom-helper-guide-container' );
	wpzoomHelperGuideContent = document.querySelector( '#wpzoom-helper-guide-content' );
	wpzoomHelperGuideHeader = document.querySelector( '#wpzoom-helper-guide-header');
	wpzoomHelperGuideBackButton = document.querySelector( '#wpzoom-helper-guide-back-to-toc' );
	wpzoomHelperGuideAccentColor = wpzoomHelperGuideLauncher.getAttribute( 'data-accent-color' );

	// fetch the markdown file (set in wpzoom-helper-guide.php)
	// then load the content into the container
	fetch( wpzoomHelperGuideDocUrl )
		.then( blob => blob.text() )
		.then( data => loadContent( data ) );
}

function loadContent( data ) {
	// first we format the content
	const dataFormat = marked( data );

	// then we create our custom content structure
	const content = createContent( dataFormat );

	// then we insert content into the content box
	wpzoomHelperGuideContent.innerHTML = content;

	// apply accent color
	applyAccentColor( wpzoomHelperGuideAccentColor );

	// bind interaction events after all content is loaded
	wpzoomHelperGuideBindEvents();
}

function createContent( data ) {
	let sections = data.split( '<h1' ); // split at h1
	sections = sections.filter( ( n ) => { return n != '' } ); // remove empty elements

	const content = sections.map( section => {
		const splitIndex = section.indexOf( '</h1>' ); // split into two blocks after <h1>
		const headingSplit = section.slice( 0, splitIndex );
		const heading = headingSplit.slice( headingSplit.indexOf( '>' ) + 1 ); // content after `id="*">``
		const body = section.slice( splitIndex + 5 ); // content after closing `</h1>`

		return {
			heading: heading,
			body: body
		};
	} );

	const contentHtml = formatContent( content );

	return contentHtml;
}

function formatContent( content ) {
	const html = content.map( item => {
		return `
			<a style="color:${wpzoomHelperGuideAccentColor}" class="wpzoom-helper-guide-toc--trigger">${ item.heading }<span>&rarr;</span></a>
			<div class="wpzoom-helper-guide-toc--content">
				${ item.body }
			</div>
		`;
	} ).join( '' );

	return html;
}

function showHideContainer( e ) {
	wpzoomHelperGuideLauncher.classList.toggle( 'active' );
	wpzoomHelperGuideContainer.classList.toggle( 'open' );
}

function showContent( e ) {
	// hide all triggers
	for ( i = 0; i < wpzoomHelperGuideTocTriggers.length; i++ ) {
		wpzoomHelperGuideTocTriggers[i].classList.add( 'hidden' );
		wpzoomHelperGuideTocTriggers[i].classList.remove( 'show' );
	}

	// add a class to indicate current selection
	e.target.classList.add( 'current' );

	// add a class to content block of the current selection
	// so we can show just that one
	const content = e.target.nextElementSibling;
	content.classList.add( 'open' );

	// show back button
	wpzoomHelperGuideHeader.classList.add( 'with-content' );
}

function backToToc() {
	// show all triggers
	for ( i = 0; i < wpzoomHelperGuideTocTriggers.length; i++ ) {
		wpzoomHelperGuideTocTriggers[i].classList.remove( 'hidden', 'current' );
		wpzoomHelperGuideTocTriggers[i].classList.add( 'show' );
	}

	// hide all content blocks
	const contentBlocks = document.querySelectorAll( '.wpzoom-helper-guide-toc--content' );
	for ( i = 0; i < contentBlocks.length; i++ ) {
		contentBlocks[i].classList.remove( 'open' );
	}

	// show main header
	wpzoomHelperGuideHeader.classList.remove( 'with-content' );
}

function applyAccentColor( color ) {
	wpzoomHelperGuideLauncher.setAttribute( 'style', 'background:' + color );
	wpzoomHelperGuideHeader.setAttribute( 'style', 'background:' + color );
}

function wpzoomHelperGuideBindEvents() {
	wpzoomHelperGuideLauncher.addEventListener( 'click', showHideContainer );
	wpzoomHelperGuideMobileClose.addEventListener( 'click', showHideContainer );
	wpzoomHelperGuideBackButton.addEventListener( 'click', backToToc );

	wpzoomHelperGuideTocTriggers = document.querySelectorAll( '.wpzoom-helper-guide-toc--trigger' );
	for ( i = 0; i < wpzoomHelperGuideTocTriggers.length; i++ ) {
		wpzoomHelperGuideTocTriggers[i].addEventListener( 'click', showContent );
	}
}

// init after page has loaded to make sure
// we can find the DOM nodes to modify
window.addEventListener( 'load', wpzoomHelperGuideInit );
