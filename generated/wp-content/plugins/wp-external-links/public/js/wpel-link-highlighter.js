/**
 * WP External Links Plugin
 * Admin
 */
/*global jQuery, window*/
jQuery(function ($) {
    const urlParams = new URLSearchParams(window.location.search);
    let link_to_find = decodeURI(urlParams.get('wpel-link-highlight'));
    
    if($('a[href="' + link_to_find + '"]').length == 0){
        if(link_to_find.substr(-1) == '/'){
            link_to_find = link_to_find.slice(0, - 1);
        } else {
            link_to_find = link_to_find + '/';
        }
    }

    if($('a[href="' + link_to_find + '"]').length == 0){
        link_to_find = link_to_find.replace(window.location.origin,'');
    }
	
	if($('a[href="' + link_to_find + '"]').length == 0){
        if(link_to_find.substr(-1) == '/'){
            link_to_find = link_to_find.slice(0, - 1);
        } else {
            link_to_find = link_to_find + '/';
        }
    }

    if($('a[href="' + link_to_find + '"]').length == 0){
        alert('Link not found ' + link_to_find);
    } else {
        $('a[href="' + link_to_find + '"]').css('border', '3px dashed #00a1ff').css('box-shadow','1px 1px 18px 4px #00a1ff').css('padding', '10px');
        $([document.documentElement, document.body]).animate({
            scrollTop: $('a[href="' + link_to_find + '"]').offset().top - 100
        }, 100);
    }
});
