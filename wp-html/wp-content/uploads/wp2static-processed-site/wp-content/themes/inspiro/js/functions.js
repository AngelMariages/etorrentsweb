/**
 * Theme functions file
 */
(function ($) {
    'use strict';

    $.fn.magnificPopupCallbackforPortfolios = function(){

        this.magnificPopup({
            disableOn: function() { if( $(window).width() < 0) { return false; } return true; },
            type: 'image',
            gallery: {
                enabled: true,
            },
            image: {
                titleSrc: function (item) {

                    var $el = this.currItem.el;
                    var $popover_content = $el.closest('.entry-thumbnail-popover-content');
                    var $link = $popover_content.find('.portfolio_item-title a');
                    var $title = $link.html();
                    var $href = $link.attr('href');
                    var show_caption = $popover_content.data('show-caption');

                    if (show_caption) {
                        return '<a href="' + $href + '">' + $title + '</a>';
                    }
                }
            },
            iframe: {
                markup: '<div class="mfp-iframe-scaler">'+
                '<div class="mfp-close"></div>'+
                '<iframe class="mfp-iframe" frameborder="0" allow="autoplay" allowfullscreen></iframe>'+
                '<div class="mfp-bottom-bar"><div class="mfp-title"></div></div>'+
                '</div>',
                callbacks: {

                },
                patterns: {
                    vimeo: {
                        index: 'vimeo.com/',
                        id: function(url) {
                            var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
                            if ( !m || !m[5] ) return null;
                            return m[5];
                        },
                        src: '//player.vimeo.com/video/%id%?autoplay=1'
                    },
                    youtu: {
                        index: 'youtu.be',
                        id: function( url ) {
                            // Capture everything after the hostname, excluding possible querystrings.
                            var m = url.match( /^.+youtu.be\/([^?]+)/ );

                            if ( null !== m ) {
                                return m[1];
                            }

                            return null;
                        },
                        // Use the captured video ID in an embed URL.
                        // Add/remove querystrings as desired.
                        src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
                    }
                }
            },
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false,
            callbacks: {
                change: function() {
                    if(this.currItem.type === 'inline'){
                        $(this.content).find('video')[0].play();
                    }
                },
                beforeClose: function () {
                    if (this.currItem.type === 'inline') {
                        var $video = $(this.content).find('video');

                        if ($video.length) {
                            var videoElement = $video[0];

                            var currentSrc = videoElement.currentSrc;
                            videoElement.pause();
                            videoElement.currentTime = 0;
                            videoElement.src = '';
                            videoElement.src = currentSrc;
                        }
                    }
                },
                markupParse: function (template, values, item) {

                    if (item.type === 'iframe') {

                        var $el = item.el;
                        var $popover_content = $el.closest('.entry-thumbnail-popover-content');
                        var $link = $el.closest('.entry-thumbnail-popover-content').find('.portfolio_item-title a');
                        var $title = $link.html();
                        var $href = $link.attr('href');
                        var show_caption = $popover_content.data('show-caption');

                        if (show_caption) {
                            values.title = '<a href="' + $href + '">' + $title + '</a>';
                        }
                    }

                }
            }
        });
    };

    var $document = $(document);
    var $window = $(window);


    /**
     * Document ready (jQuery)
     */
    $(function () {
        /**
         * Header style.
         */
        if ($('.slides > li, .single .has-post-cover, .page .has-post-cover, .single-portfolio_item .slide-background-overlay, .page .is-vimeo-pro-slide, .page .portfolio-header-cover-image, .portfolio-with-post-cover, .blog-with-post-cover').length) {
            $('.navbar').addClass('page-with-cover');
            $('#main').addClass('page-with-cover');
        } else {
            $('.navbar').removeClass('page-with-cover');
        }


        /**
         * Activate superfish menu.
         */
        $('.sf-menu').superfish({
            'speed': 'fast',
            'animation': {
                'height': 'show'
            },
            'animationOut': {
               'height': 'hide'
           }
        });


        var sticky_menu = zoomOptions.navbar_sticky_menu;

        if (sticky_menu) {

            $.fn.TopMenuMargin();

            /**
             * Activate Headroom.
             */
            $('.site-header').headroom({
               tolerance: {
                   up: 0,
                   down: 0
               },
               offset : 70
            });


        }

        $('<span class="child-arrow">&#62279;</span>')
            .click(function(e){
                e.preventDefault();

                var $li = $(this).closest('li'),
                    $sub = $li.find('> ul');

                if ( $sub.is(':visible') ) {
                    $sub.slideUp();
                    $li.removeClass('open');
                } else {
                    $sub.slideDown();
                    $li.addClass('open');
                }
            })
            .appendTo('.side-nav .navbar-nav li.menu-item-has-children > a');



        /**
         * Activate main slider.
         */
        $('#slider').sllider();


        /**
         * Search form in header.
         */
        $(".sb-search").sbSearch();

        /**
         * FitVids - Responsive Videos in posts
         */
        $(".wpzlb-layout, .builder-wrap, .entry-content, .video_cover, .featured_page_content").fitVids();




        /**
         * Masonry on Posts
         */
        var $grid = $('#portfolio-masonry').find( '.portfolio-grid').masonry({
            itemSelector: '.portfolio_item',
            columnWidth: '.portfolio_item'
        });

        $('.entry-cover').find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();

        $('.portfolio-showcase').each(function(){
            $(this).find('.portfolio_item .portfolio-popup-video').magnificPopupCallbackforPortfolios();
        });

        $('.portfolio-archive .portfolio_item').find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();

        // layout Masonry after each image loads
        $grid.imagesLoaded().progress( function() {
            $grid.masonry('layout');
        }).done(function(){
            $grid.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();
        });

        /**
         * Reload masonry after image was loaded by jetpack.
         */

        $(document).on('jetpack-lazy-loaded-image', function () {
            $grid.imagesLoaded().progress().done(function () {
                $grid.masonry('layout');
            });
        });

         /**
         * Background video on hover.
         */
        $('.portfolio-grid:not(.always-play-background-video)').on({
            mouseenter: function (event) {
                var $video = $(this).find('.portfolio-gallery-video-background');

                if ($video.length > 0) {
                    if (event.data.promise == null) {
                        event.data.promise = $video[0].play();

                    } else {
                        event.data.promise.then(function () {
                            event.data.promise = $video[0].play();
                        }).catch(function () {
                            console.log('mouseenter IN Catch Video play failed');

                            event.data.promise = $video[0].play();
                        });
                    }
                }
            },
            mouseleave: function (event) {
                var $video = $(this).find('.portfolio-gallery-video-background');

                if ($video.length > 0) {
                    if (event.data.promise !== null) {
                        event.data.promise.then(function () {

                            $video[0].addEventListener('pause', function(event){
                                $video[0].load();
                        });
                            $video[0].pause();

                        }).catch(function (event) {
                            console.log('mouseleave IN Catch Video play failed');
                        });
                    }
                }
            }
        }, '.is-portfolio-gallery-video-background', {promise: null});



        /**
         *
         */
        $.fn.fullWidthContent();
        $.fn.responsiveSliderImages();
        $.fn.responsiveImagesHeader();
        $.fn.paralised();
        $.fn.sideNav();
        $.fn.singlePageWidgetBackground();
        $.fn.singleportfolio();



        /**
         * Portfolio items popover.
         */
        $('.portfolio-archive .portfolio_item').thumbnailPopover();
        $('.portfolio-showcase .portfolio_item').thumbnailPopover();
        $('.carousel_widget_wrapper .portfolio_item').thumbnailPopover();

        /**
         * Isotope filter for Portfolio Isotope template.
         */
        $('.portfolio-taxonomies-filter-by').portfolioIsotopeFilter();

        /**
         * Clickable divs.
         */
        $('.clickable').on('click', function () {
            window.location.href = $(this).data('href');
        });

        /**
         * Portfolio ajax loading support when isotope is disabled.
         */
        var $portfoliosForLoadMore = $('.portfolio-showcase');

        $portfoliosForLoadMore.each(function () {
            var $portfolioWrapper = $(this);
            var $portfolio = $portfolioWrapper.find('.portfolio-grid');
            if(!$portfolio.attr('data-instance')){
                return;
            }
            var $loadMoreBtnWrapper = $portfolioWrapper.find('.portfolio-view_all-link');
            var $loadMoreBtn = $loadMoreBtnWrapper.find('a');
            var itemsCount = $portfolio.attr('data-items-count');
            var widget_settings = JSON.parse($portfolio.attr('data-instance'));
            var tmpl = $portfolio.attr('data-callback-template');
            var nonce = $portfolio.data('nonce');
            var callback = 'wpz_get_portfolio_items';
            var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);


            if (widget_settings.show_categories == true) {
                return false;
            }

            if (!$loadMoreBtn.attr('data-ajax-loading')) {
                return false;
            }

            if (portfolioLengthAll == itemsCount) {
                $loadMoreBtn.hide();
            }

            $loadMoreBtn.on('click', function (e) {
                e.preventDefault();

                var category_id = 'all';

                if (category_id == 'all' && $portfolio.attr('data-subcategory')) {
                    category_id = $portfolio.attr('data-subcategory');
                }

                $(this).text('Loading...');

                var post_not_in = $portfolio.find('.type-portfolio_item').map(function (index, el) {
                    return $(el).attr('id').split('post-').pop();
                }).toArray().join(',');

                wp.ajax.post(
                    callback,
                    {
                        category_id: category_id,
                        widget_settings: widget_settings,
                        nonce: nonce,
                        tmpl: tmpl,
                        post_not_in: post_not_in,
                        'show_all': true
                    }).done(function (response) {

                    if (_.isEmpty(response.content)) {
                        return;
                    }

                    var $nodes = $.parseHTML(response.content).filter(function ($element) {
                        $($element).addClass('wpz-portfolio-item-added-dynamic');
                        return $($element).is('article') && ($portfolio.find('#' + $($element).attr('id')).length === 0);
                    });

                    $($nodes).imagesLoaded(function () {
                        $portfolio.append($($nodes));
                    }).done(function () {
                        var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);

                        $loadMoreBtn.text($loadMoreBtn.attr('title'));


                        if (portfolioLengthAll == itemsCount) {
                            $loadMoreBtnWrapper.remove();
                        }

                        $portfolio.find('.portfolio_item').thumbnailPopover();
                        $portfolio.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();
                        $portfolio.find('.wpz-portfolio-item-added-dynamic video[autoplay]').each(function(){
                            this.play();
                        });
                    });
                });
            })
        });

        /**
         * Portfolio infinite loading support.
         */
        var $folioitems = $('.portfolio-grid');
        if (typeof wpz_currPage != 'undefined' && wpz_currPage < wpz_maxPages) {
            $('.navigation').empty().append('<a class="btn btn-primary" id="load-more" href="#">Load More&hellip;</a>');

            $('#load-more').on('click', function (e) {
                e.preventDefault();
                if (wpz_currPage < wpz_maxPages) {
                    $(this).text('Loading...');
                    wpz_currPage++;

                    $.get( wpz_pagingURL.replace('%page%', wpz_currPage ) , function (data) {
                        var $newItems = $('.portfolio-grid article', data);


                        if ($folioitems.parent().is('#portfolio-masonry')) {
                            $grid.append($newItems).masonry('appended', $newItems);

                            $newItems.imagesLoaded().progress(function () {
                                $grid.masonry('layout');
                            }).done(function(){

                                $grid.masonry('layout');

                                //trigger jetpack lazy images event
                                $( 'body' ).trigger( 'jetpack-lazy-images-load');
                                $grid.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();
                            });
                        } else {
                            $newItems.addClass('hidden').hide();
                            $folioitems.append($newItems);
                            $folioitems.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();
                            $folioitems.find('article.hidden').fadeIn().removeClass('hidden');

                            //trigger jetpack lazy images event
                            $( 'body' ).trigger( 'jetpack-lazy-images-load');
                        }

                        if ((wpz_currPage + 1) <= wpz_maxPages) {
                            $('#load-more').text('Load More\u2026');
                        } else {
                            $('#load-more').animate({height: 'hide', opacity: 'hide'}, 'slow', function () {
                                $(this).remove();
                            });
                        }
                    });
                }
            });
        }
    });


    $.fn.TopMenuMargin = function () {
        $(window).on('resize orientationchange', update);

        function update() {

            var windowWidth = $(window).width();

            var $header = $('.site-header');
            var $main_content = $('#main, .PP_Wrapper');

             $main_content.css('paddingTop',$header.outerHeight());

             var $adminbar = $('#wpadminbar');

             var isHidden = true;
             var size = [ $(window).width(), $(window).height() ];

        }

        update();
    };


    $.fn.singleportfolio = function () {
        var $singlePort = $('.full-noslider');
        $singlePort.each(function (i) {
            var $this = $(this);

            $window.on('resize focus', dynamicHeightSingle);

            dynamicHeightSingle();

            function dynamicHeightSingle() {
                var height = $(window).height() - $('.full-noslider').offset().top - parseInt($('.full-noslider').css('padding-top'), 10);

                /* use different min-height for different borwser widths */
                if (height < 300) {
                    height = 300;
                } else if (height < 500 && $window.width() > 768) {
                    height = 500;
                }

                $this.find('.entry-cover.cover-fullheight').height(height);
            }

        });
    };

    $.fn.thumbnailPopover = function () {
        return this.on('mousemove', function (event) {
            var $this = $(this);
            var $popoverContent = $this.find('.entry-thumbnail-popover-content');

            var itemHeight = $this.outerHeight();
            var contentHeight = $popoverContent.outerHeight();
            var y = event.pageY - $this.offset().top;

            if (contentHeight <= itemHeight) {
                $popoverContent.addClass('popover-content--animated');
                $popoverContent.css('bottom', '');
                return;
            }

            $popoverContent.removeClass('popover-content--animated');

            $popoverContent.css({
                'bottom': (1 - y / itemHeight) * (itemHeight - contentHeight)
            });
        });
    };

    $.fn.sllider = function () {
        var countedElements = parseInt($(this).data('posts'), 10);
        return this.each(function () {
            var $this = $(this);
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;

            var video_on_mobile = zoomOptions.featured_video_mobile;

            if (video_on_mobile) { /* play on all devices, excluding these ones: */
                 var handHeldDevice = (/webOS|BlackBerry|IEMobile|Opera Mini/i.test(userAgent));
            }
            else { /* when true disable video on mobile */
                var handHeldDevice = (/Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent));
            }

            var slideshow_arrows = (typeof zoomOptions.slideshow_arrows == 'string') ? (zoomOptions.slideshow_arrows != '0' && zoomOptions.slideshow_arrows != '') : zoomOptions.slideshow_arrows != false;
            var slideshow_auto = (typeof zoomOptions.slideshow_auto == 'string') ? (zoomOptions.slideshow_auto != '0' && zoomOptions.slideshow_auto != '') : zoomOptions.slideshow_auto != false;

            $this.flexslider({
                controlNav: false,
                directionNav: slideshow_arrows,
                animationLoop: true,
                useCSS: true,
                smoothHeight: false,
                touch: countedElements > 1 ? true : false,
                keyboard: false,
                pauseOnAction: true,
                slideshow: slideshow_auto,
                animationSpeed: 300,
                animation: zoomOptions.slideshow_effect,
                slideshowSpeed: parseInt(zoomOptions.slideshow_speed, 10),
                start: function(slider){ videoBackground(slider, 'start') },
                before: videoBackground
            });

            $this.find('.wpzoom-button-video-background-play').on('click', function (e) {
                e.preventDefault();
                var $currentSlide = $(e.currentTarget).parents('li');

                if ($currentSlide.attr('data-formstone-options')) {
                   $currentSlide.background('play');
                }

                if ($currentSlide.attr('data-vimeo-options')) {
                   var vimeoPlayer = new Vimeo.Player($currentSlide);
                   vimeoPlayer.play();
                }
                $this.find('.wpzoom-button-video-background-pause').show();
                $(this).hide();
            });
            $this.find('.wpzoom-button-video-background-pause').on('click', function(e){
                e.preventDefault();
                var $currentSlide = $(e.currentTarget).parents('li');

                if ($currentSlide.attr('data-formstone-options')) {
                    $currentSlide.background('pause');
                }

                if ($currentSlide.attr('data-vimeo-options')) {
                    var vimeoPlayer = new Vimeo.Player($currentSlide);
                    vimeoPlayer.pause();
                }
                $this.find('.wpzoom-button-video-background-play').show();
                $(this).hide();
            });
            $this.find('.wpzoom-button-sound-background-mute').on('click', function(e){
                e.preventDefault();
                var $currentSlide = $(e.currentTarget).parents('li');

                if ($currentSlide.attr('data-formstone-options')) {
                    $currentSlide.background('mute');
                }

                if ($currentSlide.attr('data-vimeo-options')) {
                    var vimeoPlayer = new Vimeo.Player($currentSlide);
                    vimeoPlayer.setVolume(0);
                }
                $this.find('.wpzoom-button-sound-background-unmute').show();
                $(this).hide();
            });
            $this.find('.wpzoom-button-sound-background-unmute').on('click', function(e){
                e.preventDefault();
                var $currentSlide = $(e.currentTarget).parents('li');

                if ($currentSlide.attr('data-formstone-options')) {
                   $currentSlide.background('unmute');
                }

                if ($currentSlide.attr('data-vimeo-options')) {
                   var vimeoPlayer = new Vimeo.Player($currentSlide);
                   vimeoPlayer.setVolume(1);
                }
                $this.find('.wpzoom-button-sound-background-mute').show();
                $(this).hide();
            });

            $this.find('.popup-video').each(function(){
                var $popupinstance = $(this);
                var $type = $popupinstance.data('popup-type');
                $(this).magnificPopup({
                    disableOn: function() { if( $(window).width() < 0) { return false; } return true; },
                    type: $type,
                    iframe: {
                        patterns: {
                            vimeo: {
                                index: 'vimeo.com/',
                                id: function(url) {
                                    var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
                                    if ( !m || !m[5] ) return null;
                                    return m[5];
                                },
                                src: '//player.vimeo.com/video/%id%?autoplay=1'
                            },
                            youtu: {
                                index: 'youtu.be',
                                id: function( url ) {

                                    // Capture everything after the hostname, excluding possible querystrings.
                                    var m = url.match( /^.+youtu.be\/([^?]+)/ );

                                    if ( null !== m ) {

                                        return m[1];

                                    }

                                    return null;

                                },
                                // Use the captured video ID in an embed URL.
                                // Add/remove querystrings as desired.
                                src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
                            }
                        }
                    },
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false,
                    callbacks: {
                        beforeOpen : function(first){

                            if( $this.data('flexslider') && $this.data('flexslider').playing){
                                $this.flexslider('pause');
                            }

                            var $activeSlide = $this.find('.flex-active-slide');
                            $activeSlide.background('pause');

                            if ($activeSlide.attr('data-vimeo-options')) {

                                var currentVimeoPlayer = new Vimeo.Player($activeSlide);

                                currentVimeoPlayer.getPaused().then(function(paused) {
                                    if(!paused){
                                        currentVimeoPlayer.pause();
                                    }
                                });
                            }
                        },
                        open : function(){
                            if($type === 'inline'){
                                var container = $.magnificPopup.instance.contentContainer.first();
                                container.find('video')[0].play();
                            }
                        },
                        beforeClose : function(){
                            if($type === 'inline'){

                                var $video = $(this.content).find('video');

                                if ($video.length) {
                                    var videoElement = $video[0];

                                    var currentSrc = videoElement.currentSrc;
                                    videoElement.pause();
                                    videoElement.currentTime = 0;
                                    videoElement.src = '';
                                    videoElement.src = currentSrc;
                                }
                            }
                        },
                        afterClose: function(){

                            if( $this.data('flexslider') && $this.data('flexslider').playing){
                                $this.flexslider('play');
                            }

                            var $activeSlide = $this.find('.flex-active-slide');
                            $activeSlide.background('play');

                            if ($activeSlide.attr('data-vimeo-options')) {
                                var options = JSON.parse($activeSlide.attr('data-vimeo-options'));
                                var currentVimeoPlayer = new Vimeo.Player($activeSlide);
                                var backgroundAutoplay = options.autoplay;

                                currentVimeoPlayer.getPaused().then(function (paused) {
                                    if (backgroundAutoplay && paused) {
                                        currentVimeoPlayer.play();
                                    }
                                });
                            }
                        }
                    }
                });
            });


            $('#slider .slides .li-wrap').css({'margin-top':0,'opacity':0});

            $window.on('resize focus', dynamicHeight);

            dynamicHeight();

            $('#scroll-to-content').on('click', function () {
                $('html, body').animate({
                    scrollTop: $('#slider').offset().top + $('#slider').outerHeight() - $('.navbar').outerHeight()

                }, 600);
            });

            function dynamicHeight() {
                var height = $(window).height() - $('#slider').offset().top - parseInt($('#slider').css('padding-top'), 10);

                /* use different min-height for different borwser widths */
                if (height < 300) {
                    height = 300;
                } else if (height < 500 && $window.width() > 768) {
                    height = 500;
                }

                $this.find('.slides, .slides > li').height(height);
            }



            function videoBackground(slider, called_from) {

                var $slides = slider.find('.slides > li');
                var $currentSlide = $slides.not('.clone').eq(slider.currentSlide);
                var $nextSlide = $slides.not('.clone').eq(slider.animatingTo);

                if ($currentSlide.attr('data-vimeo-options') && 'start' !== called_from && !handHeldDevice) {

                    var options = JSON.parse($currentSlide.attr('data-vimeo-options'));
                    var currentVimeoPlayer = new Vimeo.Player($currentSlide, options);

                    currentVimeoPlayer.on('timeupdate', function () {
                        currentVimeoPlayer.pause();
                        currentVimeoPlayer.off('timeupdate');
                    });
                }

                if ($nextSlide.attr('data-vimeo-options') && !handHeldDevice) {
                    var options = JSON.parse($nextSlide.attr('data-vimeo-options'));
                    var nextVimeoPlayer = new Vimeo.Player($nextSlide, options);
                    var backgroundAutoplay = options.autoplay;
                    var backgroundMute = options.muted;


                    nextVimeoPlayer.setCurrentTime(0.0).then(function () {

                        if (!backgroundAutoplay) {
                            nextVimeoPlayer.pause();
                        } else{
                            nextVimeoPlayer.play();
                        }

                        if (!backgroundMute) {
                            nextVimeoPlayer.setVolume(1);
                        }

                    });


                    nextVimeoPlayer.on('play', function () {

                        if (!handHeldDevice) {
                            $nextSlide.find('.wpzoom-button-video-background-play')[backgroundAutoplay ? 'hide' : 'show']();
                            $nextSlide.find('.wpzoom-button-video-background-pause')[backgroundAutoplay ? 'show' : 'hide']();
                            $nextSlide.find('.wpzoom-button-sound-background-mute')[backgroundMute ? 'hide' : 'show']();
                            $nextSlide.find('.wpzoom-button-sound-background-unmute')[backgroundMute ? 'show' : 'hide']();
                        }

                        $nextSlide.css('background-image', 'none');
                    });
                }

                if ($currentSlide.attr('data-formstone-options')) {

                    if ($currentSlide.data('fsBackground')) {
                        if ($currentSlide.data('fsBackground').playing) {
                            $currentSlide.background('pause');
                        }
                        $currentSlide.background('unload');
                    }
                }

                if ($nextSlide.attr('data-formstone-options')) {

                    if ($nextSlide.data('fsBackground')) {
                        var currentSource = $nextSlide.data('formstoneOptions').source;

                        if ((!_.isEmpty($nextSlide.data('formstoneOptions').mobileSource.mp4) ||
                            !_.isEmpty($nextSlide.data('formstoneOptions').mobileSource.webm)) &&
                            window.matchMedia('(max-width:460px)').matches) {
                            currentSource = {
                                'poster': $nextSlide.data('formstoneOptions').source.poster,
                                'mp4': $nextSlide.data('formstoneOptions').mobileSource.mp4,
                                'webm': $nextSlide.data('formstoneOptions').mobileSource.webm,
                            };
                        }

                        if (!handHeldDevice) {
                            $nextSlide.background('load', currentSource);
                        } else {
                            $nextSlide.background('load', {poster: $nextSlide.data('formstoneOptions').source.poster});
                        }
                    } else {
                        var options = JSON.parse($nextSlide.attr('data-formstone-options'));

                        if ((!_.isEmpty(options.mobileSource.mp4) ||
                            !_.isEmpty(options.mobileSource.webm)) &&
                            window.matchMedia('(max-width:460px)').matches) {
                            options.source = {
                                'poster': options.source.poster,
                                'mp4': options.mobileSource.mp4,
                                'webm': options.mobileSource.webm,
                            };
                        }

                        if (!handHeldDevice) {
                            $nextSlide.background(options);
                        } else {
                            options.source = {poster: options.source.poster};
                            $nextSlide.background(options);
                        }
                    }

                    var backgroundAutoplay = $nextSlide.data('fsBackground').autoPlay;
                    var backgroundMute = $nextSlide.data('fsBackground').mute;


                    if (!handHeldDevice) {
                        $nextSlide.find('.wpzoom-button-video-background-play')[backgroundAutoplay ? 'hide' : 'show']();
                        $nextSlide.find('.wpzoom-button-video-background-pause')[backgroundAutoplay ? 'show' : 'hide']();
                        $nextSlide.find('.wpzoom-button-sound-background-mute')[backgroundMute ? 'hide' : 'show']();
                        $nextSlide.find('.wpzoom-button-sound-background-unmute')[backgroundMute ? 'show' : 'hide']();
                    }
                }


                if (slider.vars.animation == 'swing' && typeof slider.direction !== 'undefined') {
                    if (slider.count == slider.currentSlide + 1 && slider.direction == 'next') {
                        $nextSlide = $nextSlide.add(slider.find('.clone:last'));
                    } else if (slider.currentSlide == 0 && slider.direction == 'prev') {
                        $nextSlide = $nextSlide.add(slider.find('.clone:first'));
                    } else {
                        slider.find('.clone .li-wrap').css({ 'margin-top': 0, 'opacity': 0 });
                    }
                }

                /* Text animation for slide that is dissapearing */
                $currentSlide.find('.li-wrap').stop().animate({'margin-top': 0, 'opacity': 0}, 800);

                /* Text animation for slide that is appearing */
                $nextSlide.find('.li-wrap').stop(true, true).css('opacity', 0).animate({'margin-top': '50px', 'opacity': 1}, 800);

            }

        });
    };




    /**
     * Simple Parallax plugin.
     */
    $.fn.paralised = function () {
        var features = {
            bind: !!(function () {
            }.bind),
            rAF: !!(window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame)
        };

        if (typeof features === 'undefined' || !features.rAF || !features.bind) return;

        /**
         * Handles debouncing of events via requestAnimationFrame
         * @see http://www.html5rocks.com/en/tutorials/speed/animations/
         * @param {Function} callback The callback to handle whichever event
         */
        function Debouncer(e) {
            this.callback = e;
            this.ticking = false
        }

        Debouncer.prototype = {
            constructor: Debouncer, update: function () {
                this.callback && this.callback();
                this.ticking = false
            }, requestTick: function () {
                if (!this.ticking) {
                    requestAnimationFrame(this.rafCallback || (this.rafCallback = this.update.bind(this)));
                    this.ticking = true
                }
            }, handleEvent: function () {
                this.requestTick()
            }
        }

        var debouncer = new Debouncer(update.bind(this));

        $(window).on('scroll', debouncer.handleEvent.bind(debouncer));
        debouncer.handleEvent();

        function update() {
            var scrollPos = $(document).scrollTop();

            var $postCover = $('.has-post-cover .entry-cover');
            var $singlePage = $('.featured_page_wrap--with-background');

            if ($postCover.length) {
                var $postCover = $('.entry-cover');
                var postCoverBottom = $postCover.position().top + $postCover.outerHeight();

                if (scrollPos < postCoverBottom) {
                    var x = easeOutSine(scrollPos, 0, 1, postCoverBottom);

                    $postCover.find('.entry-header').css({
                        'bottom': 30 * (1 - x),
                        'opacity': 1 - easeInQuad(scrollPos, 0, 1, postCoverBottom)
                    });
                }
            }

            $singlePage.each(function (i) {
                var $this = $(this);
                var bottom = $this.position().top + $this.outerHeight();

                var inViewport = (scrollPos + $window.height()) > $this.position().top && scrollPos < bottom;

                if (!inViewport) return;

                var x = easeOutSine(scrollPos + $window.height() - $this.position().top, -1, 2, bottom);

                $this.find('.wpzoom-singlepage').css({
                    '-webkit-transform': 'translateY(' + (-x * 80) + 'px)',
                        'moz-transform': 'translateY(' + (-x * 80) + 'px)',
                            'transform': 'translateY(' + (-x * 80) + 'px)'
                });
            });
        }

        function easeOutSine(t, b, c, d) {
            return c * Math.sin(t / d * (Math.PI / 2)) + b;
        }

        function easeInQuad(t, b, c, d) {
            return c * (t /= d) * t + b;
        }
    };

    $.fn.portfolioIsotopeFilter = function () {
        return this.each(function () {
            var $this = $(this);
            var $taxs = $this.find('li');
            var $portfolioWrapper = $(this).closest('.portfolio-showcase');
            var callback = 'wpz_get_portfolio_items';

            if($portfolioWrapper.length == 0){
                callback = 'wpz_get_portfolio_filtered_items';
                $portfolioWrapper = $(this).closest('.portfolio-archive');
            }

            var $portfolio= $portfolioWrapper.find('.portfolio-grid');
            var $loadMoreBtnWrapper = $portfolioWrapper.find('.portfolio-view_all-link');
            var $loadMoreBtn = $loadMoreBtnWrapper.find('a');
            var widget_settings = $portfolio.data('instance');
            var tmpl = $portfolio.attr('data-callback-template');
            var nonce = $portfolio.data('nonce');
            var count_nonce = $portfolio.data('count-nonce');

            //Check if instance already exists skip create isotope instance.
            if ($portfolio.data('masonry') === undefined) {

                $(window).on('load', function () {

                    // reload items after jetpack render for isotope instance
                    $(document).on('jetpack-lazy-loaded-image', function () {
                        $portfolio.imagesLoaded().progress(function () {
                            $portfolio.isotope('reloadItems').isotope('layout');
                        });
                    });

                    $portfolio.fadeIn().isotope({
                        itemSelector: 'article',
                        layoutMode: 'fitRows',
                    }).isotope('layout');
                });

            }




            $portfolio.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();

            var tax_filter_regex = /cat-item-([0-9]+)/gi;

            if($portfolio.attr('data-ajax-items-loading')){
                var ajaxRequestRunning = false;

                $taxs.on('click', function (e) {
                    e.preventDefault();
                    if (ajaxRequestRunning) {
                        e.stopImmediatePropagation();
                    }
                });

                $taxs.one('click', function (e) {
                    e.preventDefault();

                    if ($(this).hasClass('cat-item-all')) {
                        return;
                    }

                    e.stopImmediatePropagation();
                    ajaxRequestRunning = true;


                    var $this = $(this);
                    var catID = tax_filter_regex.exec($this.attr('class'));
                    tax_filter_regex.lastIndex = 0;

                    var filter = (catID === null) ? '.type-portfolio_item' : '.portfolio_' + catID[1] + '_item';

                    $loadMoreBtn.hide();
                    $this.css('position', 'relative');
                    $this.append("<div class='pulse-circle'></div>");
                    $taxs.removeClass('current-cat');
                    $this.addClass('current-cat animated slow pulse');

                    if (null == catID) {
                        if ($this.attr('data-subcategory')) {
                            catID = $this.attr('data-subcategory');
                        } else {
                            catID = 'all'
                        }

                    } else {
                        catID = catID[1];
                    }

                    wp.ajax.post(
                        callback,
                        {
                            category_id: catID,
                            widget_settings: widget_settings,
                            nonce: nonce,
                            tmpl: tmpl
                        }).done(function (response) {

                        $this.attr('data-counter', response.count);

                        if (_.isEmpty(response.content)) {
                            return;
                        }

                        var $nodes = $.parseHTML(response.content).filter(function ($element) {
                            $($element).addClass('wpz-portfolio-item-added-dynamic');
                            return $($element).is('article') && ($portfolio.find('#' + $($element).attr('id')).length === 0);
                        });

                        $($nodes).imagesLoaded(function () {
                            $portfolio.append($($nodes))
                                .isotope('appended', $($nodes));
                        }).progress(function () {
                            $portfolio.isotope('layout');
                        }).done(function () {
                            //start
                            var items_number = parseInt($this.attr('data-counter'));
                            var portfolioLength = parseInt($portfolio.find(filter).length);
                            var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);

                            if (portfolioLength < items_number) {
                                $loadMoreBtn.show();
                            }

                            if(portfolioLength == items_number){
                                $loadMoreBtn.hide();
                            }

                            if(portfolioLengthAll == $taxs.siblings('.cat-item-all').attr('data-counter')){
                                $loadMoreBtnWrapper.remove();
                            }
                            //end
                            $portfolio.find('.portfolio_item').thumbnailPopover();
                            $portfolio.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();
                            $portfolio.find('.wpz-portfolio-item-added-dynamic video[autoplay]').each(function(){
                                this.play();
                            });
                        });
                    }).always(function () {
                        $this.find('.pulse-circle').remove();
                        $this.removeClass('animated slow pulse');
                        $portfolio.isotope({
                            'filter': filter
                        });

                        ajaxRequestRunning = false;


                    });
                });
            }

            if ($loadMoreBtn.attr('data-ajax-loading')) {

                //hide on first load
                var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);

                if(portfolioLengthAll == $taxs.siblings('.cat-item-all').attr('data-counter')){
                    $loadMoreBtnWrapper.remove();
                }

                $loadMoreBtn.on('click', function (e) {
                    e.preventDefault();

                    var catID = tax_filter_regex.exec($taxs.siblings('.current-cat').attr('class'));
                    tax_filter_regex.lastIndex = 0;
                    var filter = (null == catID) ? '.type-portfolio_item': '.portfolio_' + catID[1] + '_item';
                    var category_id = ( null == catID) ? 'all' :catID[1];

                    if (category_id == 'all' && $portfolio.attr('data-subcategory')) {
                        category_id = $portfolio.attr('data-subcategory');
                    }

                    $(this).text('Loading...');

                    var post_not_in = $portfolio.find('.type-portfolio_item').map(function(index, el){
                        return $(el).attr('id').split('post-').pop();
                    }).toArray().join(',');

                    wp.ajax.post(
                        callback,
                        {
                            category_id: category_id,
                            widget_settings: widget_settings,
                            nonce: nonce,
                            tmpl: tmpl,
                            post_not_in: post_not_in,
                            'show_all': true
                        }).done(function (response) {

                        if (_.isEmpty(response.content)) {
                            return;
                        }

                        var $nodes = $.parseHTML(response.content).filter(function ($element) {
                            $($element).addClass('wpz-portfolio-item-added-dynamic');
                            return $($element).is('article') && ($portfolio.find('#' + $($element).attr('id')).length === 0);
                        });

                        $($nodes).imagesLoaded(function () {
                            $portfolio.append($($nodes))
                                .isotope('appended', $($nodes));
                        }).progress(function () {
                            $portfolio.isotope('layout');
                        }).done(function () {
                            var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);

                            $loadMoreBtn.text($loadMoreBtn.attr('title'));

                            if( parseInt($portfolio.find(filter).length) == $taxs.siblings('.current-cat').attr('data-counter')){
                                $loadMoreBtn.hide();
                            }

                            if(portfolioLengthAll == $taxs.siblings('.cat-item-all').attr('data-counter')){
                                $loadMoreBtnWrapper.remove();
                            }

                            $portfolio.find('.portfolio_item').thumbnailPopover();
                            $portfolio.find('.portfolio-popup-video').magnificPopupCallbackforPortfolios();
                            $portfolio.find('.wpz-portfolio-item-added-dynamic video[autoplay]').each(function(){
                                this.play();
                            });

                        });
                    }).always(function () {
                        $portfolio.isotope({
                            'filter': filter
                        });
                    });
                });
            }

            $taxs.on('click', function (event) {
                event.preventDefault();

                $this = $(this);

                $taxs.removeClass('current-cat');
                $this.addClass('current-cat');


                var catID = tax_filter_regex.exec($this.attr('class'));
                tax_filter_regex.lastIndex = 0;

                var filter;

                if (catID === null) {
                    filter = '.type-portfolio_item';
                } else {
                    filter = '.portfolio_' + catID[1] + '_item';
                }

                var category_id = ( null == catID) ? 'all' :catID[1];

                if (category_id == 'all' && $portfolio.attr('data-subcategory')) {
                    category_id = $portfolio.attr('data-subcategory');
                }

                var items_number = $taxs.siblings('.current-cat').attr('data-counter');

                if (items_number === undefined) {

                    wp.ajax.post('wpz_count_portfolio_items', {
                        nonce: count_nonce,
                        category_id: category_id
                    }).done(function (response) {
                        $this.attr('data-counter', response.count);

                        var items_number = response.count;
                        var portfolioLength = parseInt($portfolio.find(filter).length);
                        var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);

                        if (portfolioLength < items_number) {
                            $loadMoreBtn.show();
                        }

                        if (portfolioLength == items_number) {
                            $loadMoreBtn.hide();
                        }

                        if(portfolioLengthAll == $taxs.siblings('.cat-item-all').attr('data-counter')){
                            $loadMoreBtnWrapper.remove();
                        }
                    });

                } else {
                    var portfolioLength = parseInt($portfolio.find(filter).length);
                    var portfolioLengthAll = parseInt($portfolio.find('.type-portfolio_item').length);

                    if (portfolioLength < items_number ) {
                        $loadMoreBtn.show();
                    }

                    if (portfolioLength == items_number ) {
                        $loadMoreBtn.hide();
                    }

                    if(portfolioLengthAll == $taxs.siblings('.cat-item-all').attr('data-counter')){
                        $loadMoreBtnWrapper.remove();
                    }
                }

                $portfolio.isotope({
                    'filter': filter
                });
            });
        });
    };




    $.fn.fullWidthContent = function () {
        $(window).on('resize', update);

        function update() {
            var windowWidth = $(window).width();
            var containerWidth = $('.entry-content').width();
            var marginLeft = (windowWidth - containerWidth) / 2;

            $('.fullimg').css({
                'width': windowWidth,
                'margin-left': -marginLeft
            });

            $('.fullimg .wp-caption').css({
                'width': windowWidth
            });
        }

        update();
    };

    $.fn.responsiveSliderImages = function () {
        var forceCssRule = true;

        $(window).on('resize orientationchange', update);

        function update() {
            var windowWidth = $(window).width();

            if (windowWidth <= 680) {
                $('#slider .slides li').each(function () {
                    var bgurl = $(this).css('background-image').match(/^url\(['"]?(.+)["']?\)$/);
                    var smallimg = $(this).data('smallimg');

                    if (bgurl) {
                        bgurl = bgurl[1];
                    }

                    if (bgurl == smallimg) return;

                    if (!forceCssRule && $(this).attr('data-vimeo-options')) return;

                    $(this).css('background-image', 'url("' + smallimg + '")');
                });
            }

            if (windowWidth > 680) {
                $('#slider .slides li').each(function () {
                    var bgurl = $(this).css('background-image').match(/^url\(['"]?(.+)["']?\)$/);
                    var bigimg = $(this).data('bigimg');

                    if (bgurl) {
                        bgurl = bgurl[1];
                    }

                    if (bgurl == bigimg) return;

                    if (!forceCssRule && $(this).attr('data-vimeo-options')) return;


                    $(this).css('background-image', 'url("' + bigimg + '")');
                });
            }

            forceCssRule = false;
        }

        update();
    };



    $.fn.responsiveImagesHeader = function () {
        var forceCssRule = true;
        $(window).on('resize orientationchange', update);

        function update() {
            var windowWidth = $(window).width();

            if (windowWidth <= 680) {

                $('.entry-cover-image, .portfolio-header-cover-image').each(function () {
                    var bgurl = $(this).css('background-image').match(/^url\(['"]?(.+)["']?\)$/);
                    var smallimg = $(this).data('smallimg');

                    if (bgurl) {
                        bgurl = bgurl[1];
                    }

                    if (bgurl == smallimg) return;

                    $(this).css('background-image', 'url("' + smallimg + '")');
                });

            }

            if (windowWidth > 680) {

                $('.entry-cover-image, .portfolio-header-cover-image').each(function () {
                    var bgurl = $(this).css('background-image').match(/^url\(['"]?(.+)["']?\)$/);
                    var bigimg = $(this).data('bigimg');

                    if (bgurl) {
                        bgurl = bgurl[1];
                    }

                    if (bgurl == bigimg) return;

                    $(this).css('background-image', 'url("' + bigimg + '")');
                });

            }
        }

        update();
    };




    $.fn.sideNav = function() {
        var wasPlaying = false;

        function toggleNav() {
            $('body').toggleClass('side-nav-open').addClass('side-nav-transitioning');

            var flex = $('#slider').data('flexslider');
            if (flex) {
                if ($('body').hasClass('side-nav-open')) {
                    wasPlaying = flex.playing;
                    if (flex.playing)  {
                        flex.pause();
                    }
                } else {
                    if (wasPlaying) {
                        flex.play();
                    }
                }
            }

            var called = false;
            $('.site').one('transitionend', function () {
                $('body').removeClass('side-nav-transitioning');
                called = true;
            });

            setTimeout(function() {
                if (!called) {
                    $('body').removeClass('side-nav-transitioning');
                }

                $window.trigger('resize');
            }, 230);
        }

        /* touchstart: do not allow scrolling main section then overlay is enabled (this is done via css) */
        $('.navbar-toggle, .side-nav-overlay').on('click touchend', function (event) {
            if ($(document.body).hasClass('side-nav-transitioning')) {
                return;
            }

            toggleNav();
        });

        /* allow closing sidenav with escape key */
        $document.keyup(function (event) {
            if (event.keyCode == 27 && $('body').hasClass('side-nav-open')) {
                toggleNav();
            }
        });

        /**
         * ScrollFix
         *
         * https://github.com/joelambert/ScrollFix
         */
        $('.side-nav__scrollable-container').on('touchstart', function (event) {
            var startTopScroll = this.scrollTop;

            if (startTopScroll <= 0) {
                this.scrollTop = 1;
            }

            if (startTopScroll + this.offsetHeight >= this.scrollHeight) {
                this.scrollTop = this.scrollHeight - this.offsetHeight - 1;
            }
        });
    };

    $.fn.singlePageWidgetBackground = function() {
        $('.featured_page_wrap[data-background]').each(function () {
            var $this = $(this);
            $this.css('background-image', 'url(' + $this.data('background') + ')');
            $this.addClass('featured_page_wrap--with-background');
        });
    };

    $.fn.sbSearch = function() {

    /* allow closing sidenav with escape key */
    $document.keydown(function (event) {

        if (event.keyCode == 27 && $('#sb-search').hasClass('sb-search-open')) {
            $( "#sb-search" ).removeClass( "sb-search-open" )
        }

    });

       return this.each(function() {
           new UISearch( this );
       });
    };

    $.fn.backgroundVideoSingle = function () {
        $(this).each(function (index, el) {
            var entryHeader = $(el);
            if (entryHeader.length) {

                var userAgent = navigator.userAgent || navigator.vendor || window.opera;

                var video_on_mobile = zoomOptions.featured_video_mobile;

                if (video_on_mobile) { /* play on all devices, excluding these ones: */
                    var handHeldDevice = (/webOS|BlackBerry|IEMobile|Opera Mini/i.test(userAgent));
                }
                else { /* when true disable video on mobile */
                    var handHeldDevice = (/Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent));
                }

                if (entryHeader.attr('data-formstone-options')) {

                    var options = JSON.parse(entryHeader.attr('data-formstone-options'));

                    if (!handHeldDevice) {
                        entryHeader.background(options);
                    } else {
                        options.source = {poster: options.source.poster};
                        entryHeader.background(options);
                    }


                    var backgroundAutoplay = entryHeader.data('fsBackground').autoPlay;
                    var backgroundMute = entryHeader.data('fsBackground').mute;

                    if (!handHeldDevice) {
                        entryHeader.find('.wpzoom-button-video-background-play')[backgroundAutoplay ? 'hide' : 'show']();
                        entryHeader.find('.wpzoom-button-video-background-pause')[backgroundAutoplay ? 'show' : 'hide']();
                        entryHeader.find('.wpzoom-button-sound-background-mute')[backgroundMute ? 'hide' : 'show']();
                        entryHeader.find('.wpzoom-button-sound-background-unmute')[backgroundMute ? 'show' : 'hide']();
                    }
                }

                if (entryHeader.attr('data-vimeo-options')  && !handHeldDevice) {
                    var options = JSON.parse(entryHeader.attr('data-vimeo-options'));
                    var vimeoPlayer = new Vimeo.Player(entryHeader, options);
                    var backgroundAutoplay = options.autoplay;
                    var backgroundMute = options.muted;

                    vimeoPlayer.play().then(function () {

                        if (!backgroundAutoplay) {
                            vimeoPlayer.pause();
                        }

                        if (!backgroundMute) {
                            vimeoPlayer.setVolume(1);
                        }

                        if (!handHeldDevice) {
                            entryHeader.find('.wpzoom-button-video-background-play')[backgroundAutoplay ? 'hide' : 'show']();
                            entryHeader.find('.wpzoom-button-video-background-pause')[backgroundAutoplay ? 'show' : 'hide']();
                            entryHeader.find('.wpzoom-button-sound-background-mute')[backgroundMute ? 'hide' : 'show']();
                            entryHeader.find('.wpzoom-button-sound-background-unmute')[backgroundMute ? 'show' : 'hide']();
                        }

                    });
                }

                entryHeader.find('.wpzoom-button-video-background-play').on('click', function (e) {
                    e.preventDefault();
                    var $currentSlide = $(e.currentTarget).closest('div.entry-cover');

                    if ($currentSlide.attr('data-formstone-options')) {
                        $currentSlide.background('play');
                    }

                    if ($currentSlide.attr('data-vimeo-options')) {
                        var vimeoPlayer = new Vimeo.Player($currentSlide);
                        vimeoPlayer.play();
                    }
                    entryHeader.find('.wpzoom-button-video-background-pause').show();
                    $(this).hide();
                });

                entryHeader.find('.wpzoom-button-video-background-pause').on('click', function (e) {
                    e.preventDefault();
                    var $currentSlide = $(e.currentTarget).closest('div.entry-cover');

                    if ($currentSlide.attr('data-formstone-options')) {
                        $currentSlide.background('pause');
                    }

                    if ($currentSlide.attr('data-vimeo-options')) {
                        var vimeoPlayer = new Vimeo.Player($currentSlide);
                        vimeoPlayer.pause();
                    }
                    entryHeader.find('.wpzoom-button-video-background-play').show();
                    $(this).hide();
                });

                entryHeader.find('.wpzoom-button-sound-background-mute').on('click', function (e) {
                    e.preventDefault();
                    var $currentSlide = $(e.currentTarget).closest('div.entry-cover');

                    if ($currentSlide.attr('data-formstone-options')) {
                        $currentSlide.background('mute');
                    }

                    if ($currentSlide.attr('data-vimeo-options')) {
                        var vimeoPlayer = new Vimeo.Player($currentSlide);
                        vimeoPlayer.setVolume(0);
                    }
                    entryHeader.find('.wpzoom-button-sound-background-unmute').show();
                    $(this).hide();
                });

                entryHeader.find('.wpzoom-button-sound-background-unmute').on('click', function (e) {
                    e.preventDefault();
                    var $currentSlide = $(e.currentTarget).closest('div.entry-cover');

                    if ($currentSlide.attr('data-formstone-options')) {
                        $currentSlide.background('unmute');
                    }

                    if ($currentSlide.attr('data-vimeo-options')) {
                        var vimeoPlayer = new Vimeo.Player($currentSlide);
                        vimeoPlayer.setVolume(1);
                    }
                    entryHeader.find('.wpzoom-button-sound-background-mute').show();
                    $(this).hide();
                });
            }

        });
    };


    $(document).ready(function () {

        //init for portfolio
        $('.single-portfolio_item article.portfolio_item .entry-cover').backgroundVideoSingle();
        //init for posts
        $('.single-post article.post .entry-cover').backgroundVideoSingle();
        //init for pages
        $('.page article.page .entry-cover').backgroundVideoSingle();
        $('.page .portfolio-header-cover .entry-cover').backgroundVideoSingle();

    });

    jQuery(function($) {

        $('.widget.wpzoom-portfolio-scroller').each(function(){
            var $this = $(this);
            var $c = $this.find('.flickity-wrapper');
            var autoScroll = $c.attr('data-auto-scroll');
            var scrollInfinitely = $c.attr('data-scroll-infinitely');

            $c.imagesLoaded( function(){

                $this.find('.carousel_widget_wrapper').show();
                $this.find('.loading-wrapper').hide();

                $c.flickity({
                    autoPlay: autoScroll,
                    cellAlign: 'left',
                    contain: true,
                    percentPosition: false,
                    pageDots: false,
                    wrapAround: scrollInfinitely,
                    freeScroll: true,
                    imagesLoaded: true,
                    accessibility: false,
                    arrowShape: {
                        x0: 10,
                        x1: 60, y1: 50,
                        x2: 65, y2: 50,
                        x3: 15
                    }
                });

            });
        });

    });

})(jQuery);