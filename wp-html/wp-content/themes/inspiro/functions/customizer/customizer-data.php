<?php


function inspiro_customizer_data()
{
    static $data = array();

    if(empty($data)){

        $media_viewport = 'screen and (min-width: 950px)';

        $data = array(
            'color-palettes-container' => array(
                'title' => __('Color Scheme', 'wpzoom'),
                'priority' => 22,
                'options' => array(
                    'color-palettes' => array(
                        'setting' => array(
                            'default' => 'default',
                            'sanitize_callback' => 'sanitize_text_field'
                        ),
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_Radio',
                            'label' => __('Color Scheme', 'wpzoom'),
                            'mode' => 'buttonset',
                            'choices' => array(
                                'default' => __('Default', 'wpzoom'),
                                'blue' => __('Blue', 'wpzoom'),
                                'red' => __('Red', 'wpzoom'),
                                'brown' => __('Brown', 'wpzoom')
                            )
                        ),
                        'dom' => array(
                            // * - mean that it is dynamic and would be from select choices.
                            'selector' => 'inspiro-style-color-*-css',
                            'rule' => 'change-stylesheet'
                        )
                    ),
                )
            ),
            'title_tagline' => array(
                'title' => __('Site Identity', 'wpzoom'),
                'priority' => 20,
                'options' => array(
                    'custom_logo_retina_ready' => array(
                        'setting' => array(
                            'sanitize_callback' => 'absint',
                            'default' => false,
                        ),
                        'control' => array(
                            'label' => __('Retina Ready?', 'wpzoom'),
                            'type' => 'checkbox',
                            'priority' => 9
                        ),
                        'partial' => array(
                            'selector' => '.navbar-brand-wpz a',
                            'container_inclusive' => false,
                            'render_callback' => 'inspiro_custom_logo'
                        )
                    ),
                    'blogname' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'default' => get_option('blogname'),
                            'transport' => 'postMessage',
                            'type' => 'option'
                        ),
                        'control' => array(
                            'label' => __('Site Title', 'wpzoom'),
                            'type' => 'text',
                            'priority' => 9
                        ),
                        'partial' => array(
                            'selector' => '.navbar-brand-wpz a',
                            'container_inclusive' => false,
                            'render_callback' => 'zoom_customizer_partial_blogname'
                        )
                    ),
                    'blogdescription' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'default' => get_option('blogdescription'),
                            'transport' => 'postMessage',
                            'type' => 'option'
                        ),
                        'control' => array(
                            'label' => __('Tagline', 'wpzoom'),
                            'type' => 'text',
                            'priority' => 10
                        ),
                        'partial' => array(
                            'selector' => '.navbar-brand-wpz .tagline',
                            'container_inclusive' => false,
                            'render_callback' => 'zoom_customizer_partial_blogdescription'
                        )
                    ),
                    'custom_logo' => array(
                        'partial' => array(
                            'selector' => '.navbar-brand-wpz a',
                            'container_inclusive' => false,
                            'render_callback' => 'inspiro_custom_logo'
                        )
                    )
                )
            ),


            /**
             *  Header
             */
            'header' => array(
                'title' => __('Header Options', 'wpzoom'),
                'priority' => 50,
                'options' => array(
                    'header-layout-type' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'default' => 'wpz_layout_narrow'
                        ),
                        'control' => array(
                            'label' => __('Header Menu Width', 'wpzoom'),
                            'type' => 'radio',
                            'choices' => array(
                                'wpz_layout_narrow' => __('Narrow', 'wpzoom'),
                                'wpz_layout_full' => __('Full-width', 'wpzoom')
                             )
                        ),
                        'dom' => array(
                            'selector' => '.navbar .inner-wrap',
                            'rule' => 'toggle-class'
                        )
                    ),
                    'navbar-hide-search' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'default' => 'block'
                        ),
                        'control' => array(
                            'label' => __('Show Search Form', 'wpzoom'),
                            'type' => 'checkbox',
                        ),
                        'style' => array(
                            'selector' => '.sb-search',
                            'rule' => 'display'
                        )
                    ),
                    'navbar_sticky_menu' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'refresh',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Stick Menu at the Top?', 'wpzoom'),
                            'description' => __('Do you want the top menu to stay at the top when scrolling?', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox'
                        )
                    ),

                )
            ),
            'slider-general' => array(
                'title' => __('General Settings', 'wpzoom'),
                'panel' => 'slider-container',
                'options' => array(
                    'featured_posts_show' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'refresh',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Display Slideshow on homepage?', 'wpzoom'),
                            'description' => sprintf( __('Do you want to show a featured slider on the homepage? To add posts in slider, go to %sSlideshow section%s', 'wpzoom'), '<a href=\'edit.php?post_type=slider\' target=\'_blank\'>', '</a>' ),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                        ),
                    ),

                    'featured_posts_posts' => array(
                        'setting' => array(
                            'sanitize_callback' => 'absint',
                            'transport' => 'refresh',
                            'default' => 5
                        ),
                        'control' => array(
                            'label' => __('Number of Posts in Slider', 'wpzoom'),
                            'description' => __('How many posts should appear in Slider on the homepage? Default: 5.', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Text',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        )
                    ),

                    'slideshow_auto' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'refresh',
                            'default' => false
                        ),
                        'control' => array(
                            'label' => __('Autoplay Slideshow?', 'wpzoom'),
                            'description' => __('Do you want to auto-scroll the slides?', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        )
                    ),
                    'slideshow_speed' => array(
                        'setting' => array(
                            'sanitize_callback' => 'absint',
                            'transport' => 'refresh',
                            'default' => 3000
                        ),
                        'control' => array(
                            'label' => __('Slider Autoplay Interval', 'wpzoom'),
                            'description' => __('Select the interval (in miliseconds) at which the Slider should change posts. Default: 3000 (3 seconds).', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Text',
                            'dependency' => array('featured_posts_show' => array(1, 'on'), 'slideshow_auto' => array(1, 'on') ),
                        )
                    ),
                    'slideshow_title' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'postMessage',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Display Slide Title?', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        ),
                        'style' => array(
                            'selector' => '#slider .slides > li h3',
                            'rule' => 'display'
                        )
                    ),
                    'slideshow_excerpt' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'postMessage',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Display Slide Content?', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        ),
                        'style' => array(
                            'selector' => '#slider .slides > li .excerpt',
                            'rule' => 'display'
                        )
                    ),
                    'slideshow_arrows' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'postMessage',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Display Navigation Arrows?', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        ),
                        'style' => array(
                            'selector' => '#slider .flex-direction-nav',
                            'rule' => 'display'
                        )
                    ),
                    'slideshow_scroll' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'postMessage',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Display Scroll to Content Pointer?', 'wpzoom'),
                            'description' => __('This pointer is located at the bottom center of the slideshow and when you click it the page scrolls to the next section located below the slideshow.', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        ),
                        'style' => array(
                            'selector' => '#scroll-to-content',
                            'rule' => 'display'
                        )
                    )
                )
			),
            'slider-style' => array(
                'title' => __('Look & Feel', 'wpzoom'),
                'panel' => 'slider-container',
                'options' => array(
                    'slideshow_height_desktop' => array(
                        'setting' => array(
                            'sanitize_callback' => 'absint',
                            'default' => 100,
                            'transport' => 'postMessage'
                        ),
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_Range',
                            'label'        => __( 'Slider Height (In Percents)', 'wpzoom' ),
                            'description'  => __( 'Slider height in regard to browser height.', 'wpzoom' ),
                            'input_type' => 'number',
                            'input_attrs'  => array(
                                'min'  => 10,
                                'max'  => 100,
                                'step' => 5
                            )
                        )
                    ),
                    'slideshow_height_tablet' => array(
                        'setting' => array(
                            'sanitize_callback' => 'absint',
                            'default' => 100,
                            'transport' => 'postMessage'
                        ),
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_Range',
                            'label'        => __( 'Slider Height (In Percents)', 'wpzoom' ),
                            'description'  => __( 'Slider height in regard to browser height.', 'wpzoom' ),
                            'input_attrs'  => array(
                                'min'  => 10,
                                'max'  => 100,
                                'step' => 5
                            )
                        )
                    ),
                    'slideshow_height_phone' => array(
                        'setting' => array(
                            'sanitize_callback' => 'absint',
                            'default' => 100,
                            'transport' => 'postMessage'
                        ),
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_Range',
                            'label'        => __( 'Slider Height (In Percents)', 'wpzoom' ),
                            'description'  => __( 'Slider height in regard to browser height.', 'wpzoom' ),
                            'input_attrs'  => array(
                                'min'  => 10,
                                'max'  => 100,
                                'step' => 5
                            )
                        )
                    ),
                    'slideshow_overlay' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'postMessage',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Enable overlay background?', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                        ),
                        'style' => array(
                            'selector' => '#slider .slide-background-overlay',
                            'rule' => 'display'
                        )
                    ),
                    'slideshow_overlay-color' => array(
                        'setting' => array(
                            'transport' => 'postMessage',
                            'default' => array(
                                'start_color' => '#000',
                                'end_color' => '#000',
                                'direction' => 'vertical',
                                'start_opacity' => '0.3',
                                'end_opacity' => '0.5',
                                'start_location' => '0',
                                'end_location' => '100',
                            )
                        ),
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_Background_Gradient',
                            'dependency' => array('featured_posts_show' => array(1, 'on'), 'slideshow_overlay' => array(1, 'on') ),
                            'label' => __('Background Gradient Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '#slider .slide-background-overlay',
                            'rule' => 'background-gradient'
                        )
                    ),
                    'slideshow_effect' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'refresh',
                            'default' => 'slide'
                        ),
                        'control' => array(
                            'label' => __('Slider Effect', 'wpzoom'),
                            'description' => __('Select the effect for slides transition.', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Select',
                            'dependency' => array('featured_posts_show' => array(1, 'on') ),
                            'choices' => array(
                                'slide' => __('Slide', 'wpzoom'),
                                'fade' => __('Fade', 'wpzoom')
                            )
                        )
                    )
                )
            ),
            'slider-mobile' => array(
                'title' => __('Mobile Options', 'wpzoom'),
                'panel' => 'slider-container',
                'options' => array(
                    'featured_video_mobile' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'refresh',
                            'default' => true
                        ),
                        'control' => array(
                            'label' => __('Play Background Video on Mobile?', 'wpzoom'),
                            'description' => __('Unchecking this option you can disable self-hosted and Vimeo videos from playing automatically on mobile devices. Featured Image of the Slideshow posts will be displayed instead.', 'wpzoom'),
                            'control_type' => 'WPZOOM_Customizer_Control_Checkbox'
                        )
                    )
                )
            ),
            'color' => array(
                'title' => __('General', 'wpzoom'),
                'panel' => 'color-scheme',
                'priority' => 110,
                'capability' => 'edit_theme_options',
                'options' => array(

                    'color-accent' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => ''
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Accent Color', 'wpzoom'),
                            'description' => 'If this option affects the menu, just refresh this page, as this happens only in Customizer.'
                        ),

                        'style' => array(
                            array(
                                'selector' => 'a,
                                    .comment-author .fn a:hover,
                                    .zoom-twitter-widget a,
                                    .woocommerce-pagination .page-numbers,
                                    .paging-navigation .page-numbers,
                                    .page .post_author a,
                                    .single .post_author a,
                                     .button:hover, .button:focus, .btn:hover, .more-link:hover, .more_link:hover, .side-nav .search-form .search-submit:hover, .site-footer .search-form .search-submit:hover, .btn:focus, .more-link:focus, .more_link:focus, .side-nav .search-form .search-submit:focus, .site-footer .search-form .search-submit:focus, .infinite-scroll #infinite-handle span:hover,
                                    .btn-primary, .side-nav .search-form .search-submit, .site-footer .search-form .search-submit,
                                    .woocommerce-pagination .page-numbers.current, .woocommerce-pagination .page-numbers:hover, .paging-navigation .page-numbers.current, .paging-navigation .page-numbers:hover, .featured_page_wrap--with-background .btn:hover, .fw-page-builder-content .feature-posts-list h3 a:hover,
.widgetized-section .feature-posts-list h3 a:hover, .widgetized-section .featured-products .price:hover, .portfolio-view_all-link .btn:hover, .portfolio-archive-taxonomies a:hover, .entry-thumbnail-popover-content h3:hover, .entry-thumbnail-popover-content span:hover, .entry-thumbnail-popover-content .btn:hover, .entry-title a:hover, .entry-meta a:hover, .page .has-post-cover .entry-header .entry-meta a:hover,
.single .has-post-cover .entry-header .entry-meta a:hover, .page .post_author a:hover,
.single .post_author a:hover, .single #jp-relatedposts .jp-relatedposts-items-visual h4.jp-relatedposts-post-title a:hover, .comment-author .fn a:hover, .site-info a:hover, .woocommerce-page #content input.button:focus, .woocommerce-page ul.products li.product .price, .woocommerce-page div.product span.price, .woocommerce-page #content input.button.alt, .woocommerce-pagination .page-numbers:hover, .woocommerce-message::before, .fw_theme_bg_color_1 input[type=submit]:hover, .fw-page-builder-content .fw_theme_bg_color_1 .feature-posts-list h3 a:hover, .fw_theme_bg_color_2 input[type=submit]:hover, .fw-section-image .btn:hover, .wpz-btn:hover,.wpz-btn:focus, .fw-section-image .wpz-btn:hover, .fw-pricing-container .wpz-btn:hover#main .woocommerce-page #content input.button.alt, .woocommerce-page #main  a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover,
.woocommerce #respond input#submit:hover,
.woocommerce #content input.button:hover,
.woocommerce-page a.button:hover,
.woocommerce-page button.button:hover,
.woocommerce-page input.button:hover,
.woocommerce-page #respond input#submit:hover,
.woocommerce-page #main input.button:hover, .woocommerce #content div.product p.price, .woocommerce #content div.product span.price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page div.product span.price,
.fw-section-image .wpz-btn:focus,button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, .clean_skin_wrap_post a:hover, .clean_skin_wrap_post .portfolio_sub_category:hover, .widgetized-section .inner-wrap .portfolio-view_all-link .btn:hover, .section-footer .zoom-instagram-widget a.ig-b-v-24:hover',
                                'rule' => 'color'
                            ),
                            array(
                                'selector' => '.button:hover, .button:focus, .btn:hover, .more-link:hover, .more_link:hover, .side-nav .search-form .search-submit:hover, .site-footer .search-form .search-submit:hover, .btn:focus, .more-link:focus, .more_link:focus, .side-nav .search-form .search-submit:focus, .site-footer .search-form .search-submit:focus, .infinite-scroll #infinite-handle span:hover,
                                    .btn-primary, .side-nav .search-form .search-submit, .site-footer .search-form .search-submit,
                                    input:focus, textarea:focus,
                                    .slides > li h3 a:hover:after, .slides > li .slide_button a:hover, .featured_page_wrap--with-background .btn:hover,.widgetized-section .featured-products .price:hover, .portfolio-view_all-link .btn:hover, .portfolio-archive-taxonomies a:hover, .search-form input:focus, .woocommerce-page #content input.button:focus, .woocommerce-page #content input.button.alt, .fw_theme_bg_color_1 input[type=submit]:hover, .wpz-btn:hover,
.wpz-btn:focus, .fw-section-image .wpz-btn:hover,
.fw-section-image .wpz-btn:focus, .fw-pricing-container .wpz-btn:hover, .entry-thumbnail-popover-content .btn:hover,button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, .woocommerce-page #main  a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover,
.woocommerce #respond input#submit:hover,
.woocommerce #content input.button:hover,
.woocommerce-page a.button:hover,
.woocommerce-page button.button:hover,
.woocommerce-page input.button:hover,
.woocommerce-page #respond input#submit:hover,
.woocommerce-page #main input.button:hover, .woocommerce-cart table.cart td.actions .coupon .input-text:focus, .widgetized-section .inner-wrap .portfolio-view_all-link .btn:hover',
                                'rule' => 'border-color'
                            ),

                            array(
                                'selector' => '.slides > li .slide_button a:hover, .woocommerce .quantity .minus:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .fw_theme_bg_color_2, .overlay_color_2, .background-video, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .navbar .wpz-button a',
                                'rule' => 'background-color'
                            ),

                            array(
                                'selector' => '.navbar-nav > li > ul:before',
                                'rule' => 'border-bottom-color'
                            ),

                            array(
                                'selector' => '.navbar-nav a:hover, .navbar-nav ul',
                                'rule' => 'border-top-color'
                            )


                        )

                    ),


                    'color-background' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Background Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => 'body',
                            'rule' => 'background'
                        )
                    ),
                    'color-body-text' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#444444'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Body Text', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => 'body, h1, h2, h3, h4, h5, h6',
                            'rule' => 'color'
                        )
                    ),

                    'color-logo-html' => array(
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_HTML',
                            'html' => __('Logo', 'wpzoom'),
                        ),
                    ),

                    'color-logo' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Logo Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.navbar-brand-wpz a',
                            'rule' => 'color'
                        ),
                    ),
                    'color-logo-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Logo Color on Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.navbar-brand-wpz a:hover',
                            'rule' => 'color'
                        )
                    ),

                    'color-link-html' => array(
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_HTML',
                            'html' => __('Links', 'wpzoom'),
                        ),
                    ),
                    'color-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Link Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => 'a,.comment-author .fn, .comment-author .fn a, .zoom-twitter-widget a, .woocommerce-pagination .page-numbers, .paging-navigation .page-numbers, .page .post_author a, .single .post_author a, .comment-author a.comment-reply-link, .comment-author a.comment-edit-link',
                            'rule' => 'color'
                        )
                    ),
                    'color-link-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#076c65'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Link Color on Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => 'a:hover, .zoom-twitter-widget a:hover, .woocommerce-pagination .page-numbers.current, .woocommerce-pagination .page-numbers:hover, .paging-navigation .page-numbers.current, .paging-navigation .page-numbers:hover, .entry-thumbnail-popover-content h3:hover, .comment-author .fn a:hover, .page .post_author a:hover, .single .post_author a:hover',
                            'rule' => 'color'
                        ),
                    ),

                    'color-buttons-html' => array(
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_HTML',
                            'html' => __('Buttons', 'wpzoom'),
                        ),
                    ),

                    'button-background' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => ''
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Buttons Background Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.button, .btn, .more-link, .more_link, .side-nav .search-form .search-submit, .portfolio-view_all-link .btn, .entry-thumbnail-popover-content .btn',
                            'rule' => 'background'
                        )
                    ),
                    'button-background-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => 'rgba(11, 180, 170, 0.05)'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Buttons Background Color on Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.button:hover, .button:focus, .btn:hover, .more-link:hover, .more_link:hover, .side-nav .search-form .search-submit:hover, .site-footer .search-form .search-submit:hover, .btn:focus, .more-link:focus, .more_link:focus, .side-nav .search-form .search-submit:focus, .site-footer .search-form .search-submit:focus, .infinite-scroll #infinite-handle span:hover, .portfolio-view_all-link .btn:hover, .entry-thumbnail-popover-content .btn:hover',
                            'rule' => 'background'
                        )
                    ),
                    'button-border' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#444'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Buttons Border Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.button, .btn, .more-link, .more_link, .side-nav .search-form .search-submit, .portfolio-view_all-link .btn, .entry-thumbnail-popover-content .btn',
                            'rule' => 'border-color'
                        )
                    ),
                    'button-border-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Buttons Border Color on Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.button:hover, .button:focus, .btn:hover, .more-link:hover, .more_link:hover, .side-nav .search-form .search-submit:hover, .site-footer .search-form .search-submit:hover, .btn:focus, .more-link:focus, .more_link:focus, .side-nav .search-form .search-submit:focus, .site-footer .search-form .search-submit:focus, .infinite-scroll #infinite-handle span:hover, .portfolio-view_all-link .btn:hover, .entry-thumbnail-popover-content .btn:hover',
                            'rule' => 'border-color'
                        )
                    ),
                    'button-color' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#444'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Buttons Text Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.button, .btn, .more-link, .more_link, .side-nav .search-form .search-submit, .portfolio-view_all-link .btn, .entry-thumbnail-popover-content .btn',
                            'rule' => 'color'
                        )
                    ),
                    'button-color-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Buttons Text Color on Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.button:hover, .button:focus, .btn:hover, .more-link:hover, .more_link:hover, .side-nav .search-form .search-submit:hover, .site-footer .search-form .search-submit:hover, .btn:focus, .more-link:focus, .more_link:focus, .side-nav .search-form .search-submit:focus, .site-footer .search-form .search-submit:focus, .infinite-scroll #infinite-handle span:hover, .portfolio-view_all-link .btn:hover, .entry-thumbnail-popover-content .btn:hover',
                            'rule' => 'color'
                        )
                    ),

                ),

            ),
            'color-main-menu' => array(
                'panel' => 'color-scheme',
                'title' => __('Main Menu', 'wpzoom'),
                'options' => array(
                    'color-menu-background' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#111111'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Menu Background', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '.navbar',
                                'rule' => 'background'
                            ),
                            array(
                                'selector' => '.navbar',
                                'rule' => 'background-color'
                            )

                        )
                    ),

                    'color-menu-background-scroll' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => 'rgba(0,0,0,0.9)'
                        ),
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_Color',
                            'label' => __('Menu Background on Scroll', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-menu-link-scroll',
                            'selector' => '.headroom--not-top .navbar',
                            'rule' => 'background-color'
                        )
                    ),

                    // 'color-menu-background-scroll-opacity' => array(
                    //     'setting' => array(
                    //         'transport' => 'postMessage',
                    //         'sanitize_callback' => 'absint',
                    //         'default' => '90'
                    //     ),
                    //     'control' => array(
                    //         'label' => __( 'Overlay Opacity', 'wpzoom' ),
                    //         'description' => __('The opacity of the menu background color on scroll. (Default: 90)', 'wpzoom'),
                    //         'control_type' => 'WPZOOM_Customizer_Control_Range',
                    //         'value' => '90',
                    //         'input_attrs' => array(
                    //             'min' => '0',
                    //             'max' => '100',
                    //             'step' => '1'
                    //         )
                    //     ),
                    //     'style' => array(
                    //         'selector' => '.headroom--not-top .navbar',
                    //         'rule' => 'opacity'
                    //     )
                    // ),

                    'color-menu-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Menu Item', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-menu-link',
                            'selector' => '.navbar-collapse .navbar-nav > li > a, .sb-search .sb-icon-search',
                            'rule' => 'color'
                        )
                    ),
                    'color-menu-link-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Menu Item Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '.navbar-collapse .navbar-nav > li > a:hover',
                                'rule' => 'color'
                            ),
                            array(
                                'selector' => '.navbar-collapse .navbar-nav > li > a:hover',
                                'rule' => 'border-bottom-color'
                            )
                        )
                    ),
                    'color-menu-link-current' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Menu Current Item', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '.navbar-collapse .navbar-nav > li.current-menu-item > a, .navbar-collapse .navbar-nav > li.current_page_item > a, .navbar-collapse .navbar-nav > li.current-menu-parent > a',
                                'rule' => 'color'
                            ),
                            array(
                                'selector' => '.navbar-collapse .navbar-nav > .current-menu-item a, .navbar-collapse .navbar-nav > .current_page_item a, .navbar-collapse .navbar-nav > .current-menu-parent a',
                                'rule' => 'border-bottom-color'
                            )
                        )
                    ),
                    'color-menu-dropdown' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#111111'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Dropdown Menu Background', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.navbar-nav ul',
                            'rule' => 'background'
                        )
                    ),
                    'color-menu-dropdown-arrow' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Dropdown Menu Arrow', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '.navbar-nav ul',
                                'rule' => 'border-top-color'
                            ),
                            array(
                                'selector' => '.navbar-nav > li > ul:before',
                                'rule' => 'border-bottom-color'
                            )
                        )
                    ),
                    'color-menu-hamburger' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Hamburger Icon Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-menu-hamburger',
                            'selector' => '.navbar-toggle .icon-bar',
                            'rule' => 'background-color'
                        )
                    )
                )
            ),
            'color-sidebar' => array(
                'panel' => 'color-scheme',
                'title' => __('Sidebar Panel', 'wpzoom'),
                'options' => array(
                    'color-sidebar-background' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#101010'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Sidebar Panel Background', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-sidebar-background',
                            'selector' => '.side-nav__scrollable-container',
                            'rule' => 'background-color'
                        )
                    ),

                    'color-sidebar-menu-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#fff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Menu Link Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-sidebar-text',
                            'selector' => '.side-nav .navbar-nav li a, .side-nav .navbar-nav li a:active, .side-nav .navbar-nav li li a',
                            'rule' => 'color'
                        )
                    ),

                    'color-sidebar-menu-link-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#fff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Menu Link Color Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-sidebar-text',
                            'selector' => '.side-nav .navbar-nav li a:hover, .side-nav .navbar-nav li li a:hover',
                            'rule' => 'color'
                        )
                    ),

                    'color-sidebar-text' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#78787f'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Text Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-sidebar-text',
                            'selector' => '.side-nav__scrollable-container',
                            'rule' => 'color'
                        )
                    ),

                    'color-sidebar-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __(' Link Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-sidebar-link',
                            'selector' => '.side-nav__scrollable-container a',
                            'rule' => 'color'
                        )
                    ),

                    'color-sidebar-link-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#fff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __(' Link Color Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-sidebar-link-hover',
                            'selector' => '.side-nav__scrollable-container a:hover',
                            'rule' => 'color'
                        )
                    ),
                )
            ),

            'color-slider' => array(
                'panel' => 'color-scheme',
                'title' => __('Homepage Slider', 'wpzoom'),
                'options' => array(
                    'color-slider-title' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Slide Title', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li h3 a, .slides li h3',
                            'rule' => 'color',
                            'media' => $media_viewport
                        )
                    ),
                    'color-slider-description' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Slide Description', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .excerpt',
                            'rule' => 'color'
                        )
                    ),
                    'color-slider-arrows' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Slider Arrows', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '#slider .flex-direction-nav .flex-nav-prev .flex-prev:after, #slider .flex-direction-nav .flex-nav-next .flex-next:after',
                                'rule' => 'color'
                            ),
                            array(
                                'selector' => '#slider #scroll-to-content:before',
                                'rule' => 'border-color'
                            )
                        )
                    ),

                    'slider-buttons-html' => array(
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_HTML',
                            'html' => __('Buttons', 'wpzoom'),
                        ),
                    ),

                    'color-slider-button-text' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Button Text', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .slide_button a',
                            'rule' => 'color'
                        )
                    ),
                    'color-slider-button-text-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Button Text Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .slide_button a:hover',
                            'rule' => 'color'
                        )
                    ),
                    'color-slider-button-background' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => ''
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Button Background', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .slide_button a',
                            'rule' => 'background-color'
                        )
                    ),
                    'color-slider-button-background-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Button Background Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .slide_button a:hover',
                            'rule' => 'background-color'
                        )
                    ),
                    'color-slider-button-border' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Button Border', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .slide_button a',
                            'rule' => 'border-color'
                        )
                    ),
                    'color-slider-button-border-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Button Border Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.slides li .slide_button a:hover',
                            'rule' => 'border-color'
                        )
                    ),


                )
            ),
            'color-posts' => array(
                'panel' => 'color-scheme',
                'title' => __('Blog Posts', 'wpzoom'),
                'options' => array(
                    'color-post-title' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#222222'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Title', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.entry-title a, .fw-page-builder-content .feature-posts-list h3 a, .widgetized-section .feature-posts-list h3 a',
                            'rule' => 'color'
                        )
                    ),
                    'color-post-title-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Title Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.entry-title a:hover, .fw-page-builder-content .feature-posts-list h3 a:hover, .widgetized-section .feature-posts-list h3 a:hover ',
                            'rule' => 'color'
                        )
                    ),

                    'posts-meta-html' => array(
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_HTML',
                            'html' => __('Post Details', 'wpzoom'),
                        ),
                    ),

                    'color-post-meta' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#999999'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.entry-meta',
                            'rule' => 'color'
                        )
                    ),
                    'color-post-meta-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#222222'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta Link', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '.entry-meta a',
                                'rule' => 'color'
                            ),
                            array(
                                'selector' => '.recent-posts .entry-meta a',
                                'rule' => 'border-color'
                            )
                        )
                    ),
                    'color-post-meta-link-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta Link Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            array(
                                'selector' => '.entry-meta a:hover',
                                'rule' => 'color'
                            ),
                            array(
                                'selector' => '.recent-posts .entry-meta a:hover',
                                'rule' => 'border-color'
                            )
                        )
                    ),

                    'posts-readmore-html' => array(
                        'control' => array(
                            'control_type' => 'WPZOOM_Customizer_Control_HTML',
                            'html' => __('Read More Button', 'wpzoom'),
                        ),
                    ),

                    'color-post-button-color' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#444444'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Read More Text Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.more_link',
                            'rule' => 'color'
                        )
                    ),
                    'color-post-button-color-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Read More Text Color Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.more_link:hover, .more_link:active',
                            'rule' => 'color'
                        )
                    ),
                    'color-post-button-background' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => ''
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Read More Button Background Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.more_link',
                            'rule' => 'background-color'
                        )
                    ),
                    'color-post-button-background-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => 'rgba(11, 180, 170, 0.05)'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Read More Button Background Color Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.more_link:hover, .more_link:active',
                            'rule' => 'background-color'
                        )
                    ),
                    'color-post-button-border' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#444444'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Read More Button Border', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.more_link',
                            'rule' => 'border-color'
                        )
                    ),
                    'color-post-button-border-color' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Read More Button Border Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.more_link:hover, .more_link:active',
                            'rule' => 'border-color'
                        )
                    ),
                )
            ),
            'color-single' => array(
                'panel' => 'color-scheme',
                'title' => __('Individual Posts and Pages', 'wpzoom'),
                'options' => array(
                    'color-single-title' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#222222'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post/Page Title', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.page h1.entry-title, .single h1.entry-title',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-title-image' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post/Page Title (with Featured Image)', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.page .has-post-cover .entry-header h1.entry-title, .single .has-post-cover .entry-header h1.entry-title',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-meta' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#494949'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta', 'wpzoom'),
                        ),
                        'style' => array(
                            'id' => 'color-single-meta',
                            'selector' => '.single .entry-meta',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-meta-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#222222'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta Link', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.single .entry-meta a',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-meta-link-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta Link Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.single .entry-meta a:hover',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-meta-image' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta (with Featured Image)', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.single .has-post-cover .entry-header .entry-meta',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-meta-link-image' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta Link (with Featured Image)', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.single .has-post-cover .entry-header .entry-meta a',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-meta-link-hover-image' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post Meta Link Hover (with Featured Image)', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.single .has-post-cover .entry-header .entry-meta a:hover',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-content' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#444444'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Post/Page Text Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.entry-content',
                            'rule' => 'color'
                        )
                    ),
                    'color-single-link' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Links Color in Posts', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.entry-content a',
                            'rule' => 'color'
                        )
                    ),

                )
            ),
            'color-portfolio' => array(
                'panel' => 'color-scheme',
                'title' => __('Portfolio Page', 'wpzoom'),
                'options' => array(
                    'color-portfolio-top-categories' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#1a1a1a'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Portfolio Categories Area Background', 'wpzoom'),
                            'description' => 'This option works only when the Portfolio page has a Featured Image that\'s shown in the header.'
                        ),
                        'style' => array(
                            'selector' => '.portfolio-with-post-cover .portfolio-archive-taxonomies, .portfolio-view_all-link',
                            'rule' => 'background'
                        )
                    ),
                    'color-portfolio-posts-area' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Portfolio Posts Area Background', 'wpzoom'),
                            'description' => 'This option works only when the Portfolio page has a Featured Image that\'s shown in the header.'
                        ),
                        'style' => array(
                            'selector' => '.portfolio-with-post-cover .portfolio-archive',
                            'rule' => 'background'
                        )
                    ),
                )
            ),
            'color-widgets' => array(
                'panel' => 'color-scheme',
                'title' => __('Widgets', 'wpzoom'),
                'options' => array(
                    'color-widget-title-homepage' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#222222'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Widget Title on Home Page', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.widget .section-title',
                            'rule' => 'color'
                        )
                    ),
                    'color-widget-title-others' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#ffffff'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Widget Title in Sidebar and Footer', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.widget h3.title',
                            'rule' => 'color'
                        )
                    ),
                )
            ),
            'color-footer' => array(
                'panel' => 'color-scheme',
                'title' => __('Footer', 'wpzoom'),
                'options' => array(
                    'footer-background-color' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#1a1a1a'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Footer Background Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.site-footer',
                            'rule' => 'background-color'
                        )
                    ),
                    'footer-background-color-separator' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#232323'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Footer Separator Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.site-footer .site-footer-separator',
                            'rule' => 'background'
                        )
                    ),

                    'footer-text-color' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#a0a0a0'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Footer Text Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.site-footer',
                            'rule' => 'color'
                        )
                    ),

                    'footer-link-color' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#0bb4aa'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Link Color', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.site-footer a',
                            'rule' => 'color'
                        )
                    ),

                    'footer-link-color-hover' => array(
                        'setting' => array(
                            'sanitize_callback' => 'maybe_hash_hex_color',
                            'transport' => 'postMessage',
                            'default' => '#076c65'
                        ),
                        'control' => array(
                            'control_type' => 'WP_Customize_Color_Control',
                            'label' => __('Link Color on Hover', 'wpzoom'),
                        ),
                        'style' => array(
                            'selector' => '.site-footer a:hover',
                            'rule' => 'color'
                        )
                    ),

                )
            ),
            /**
             *  Typography
             */
            'font-site-body' => array(
                'panel' => 'typography',
                'title' => __('Body', 'wpzoom'),
                'description' => sprintf( __('Please visit %sfonts.google.com%s for a detailed preview of each font.', 'wpzoom'), '<a href=\'https://fonts.google.com/\' target=\'_blank\'>', '</a>' ),
                'options' => array(
                    'body' => array(
                        'type' => 'typography',
                        'selector' => 'body, .footer-widgets .column, .site-info',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-family-sync-all' => false,
                            'font-size' => 16,
                            'font-weight' => 'normal',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'font-style' => 'normal',
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 16,
                            'tablet' => 16,
                            'mobile' => 16
                        )
                    )
                )
            ),
            'font-site-title' => array(
                'panel' => 'typography',
                'title' => __('Site Title', 'wpzoom'),
                'description' => sprintf( __('Please visit %sfonts.google.com%s for a detailed preview of each font.', 'wpzoom'), '<a href=\'https://fonts.google.com/\' target=\'_blank\'>', '</a>' ),
                'options' => array(
                    'title' => array(
                        'type' => 'typography',
                        'selector' => '.navbar-brand-wpz h1 a',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 26,
                            'font-weight' => 'bold',
                            'letter-spacing' => 1,
                            'font-subset' => 'latin',
                            'text-transform' => 'uppercase',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 26,
                            'tablet' => 26,
                            'mobile' => 20
                        )
                    )
                )
            ),
            'font-nav' => array(
                'panel' => 'typography',
                'title' => __('Menu Links', 'wpzoom'),
                'options' => array(
                    'mainmenu' => array(
                        'type' => 'typography',
                        'selector' => '.navbar-collapse a',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 16,
                            'font-weight' => '500',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        )
                    )
                )
            ),
            'font-nav-mobile' => array(
                'panel' => 'typography',
                'title' => __('Mobile Menu Links', 'wpzoom'),
                'options' => array(
                    'mobilemenu' => array(
                        'type' => 'typography',
                        'selector' => '.side-nav .navbar-nav li a',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 16,
                            'font-weight' => '600',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'uppercase',
                            'font-style' => 'normal'
                        )
                    )
                )
            ),
            'font-slider' => array(
                'panel' => 'typography',
                'description' => sprintf( __('Please visit %sfonts.google.com%s for a detailed preview of each font.', 'wpzoom'), '<a href=\'https://fonts.google.com/\' target=\'_blank\'>', '</a>' ),
                'title' => __('Homepage Slider Title', 'wpzoom'),
                'options' => array(
                    'slider-title' => array(
                        'type' => 'typography',
                        'selector' => '.slides > li h3',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 72,
                            'font-weight' => 200,
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 72,
                            'tablet' => 38,
                            'mobile' => 24
                        )
                    )
                )
            ),
            'font-slider-description' => array(
                'panel' => 'typography',
                'title' => __('Homepage Slider Description', 'wpzoom'),
                'options' => array(
                    'slider-text' => array(
                        'type' => 'typography',
                        'selector' => '.slides > li .excerpt',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 20,
                            'font-weight' => 'normal',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 20,
                            'tablet' => 18,
                            'mobile' => 16
                        )
                    )
                )
            ),
            'font-slider-button' => array(
                'panel' => 'typography',
                'title' => __('Homepage Slider Button', 'wpzoom'),
                'options' => array(
                    'slider-button' => array(
                        'type' => 'typography',
                        'selector' => '.slides > li .slide_button a',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 18,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'uppercase',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 18,
                            'tablet' => 16,
                            'mobile' => 14
                        )
                    )
                )
            ),
            'font-widgets-homepage' => array(
                'panel' => 'typography',
                'title' => __('Widget Title on Homepage', 'wpzoom'),
                'options' => array(
                    'home-widget-full' => array(
                        'type' => 'typography',
                        'selector' => '.widget .section-title',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 26,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'uppercase',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 26,
                            'tablet' => 20,
                            'mobile' => 20
                        )
                    )
                )
            ),
            'font-widgets-others' => array(
                'panel' => 'typography',
                'title' => __('Widget Title in Sidebar and Footer', 'wpzoom'),
                'options' => array(
                    'widget-title' => array(
                        'type' => 'typography',
                        'selector' => '.widget h3.title, .side-nav .widget .title',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 20,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'uppercase',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 20,
                            'tablet' => 20,
                            'mobile' => 20
                        )
                    )
                )
            ),
            'font-post-title' => array(
                'panel' => 'typography',
                'title' => __('Blog Posts Title', 'wpzoom'),
                'options' => array(
                    'blog-title' => array(
                        'type' => 'typography',
                        'selector' => '.entry-title',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 42,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 42,
                            'tablet' => 32,
                            'mobile' => 24
                        )
                    )
                )
            ),
            'font-single-post-title' => array(
                'panel' => 'typography',
                'title' => __('Single Post Title', 'wpzoom'),
                'options' => array(
                    'post-title' => array(
                        'type' => 'typography',
                        'selector' => '.single h1.entry-title',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 42,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 42,
                            'tablet' => 32,
                            'mobile' => 24
                        )
                    )
                )
            ),
            'font-single-post-title-image' => array(
                'panel' => 'typography',
                'title' => __('Single Post Title (with Featured Image)', 'wpzoom'),
                'options' => array(
                    'post-title-image' => array(
                        'type' => 'typography',
                        'selector' => '.single .has-post-cover .entry-header .entry-title',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 45,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 45,
                            'tablet' => 32,
                            'mobile' => 24
                        )
                    )
                )
            ),

            'font-page-title' => array(
                'panel' => 'typography',
                'title' => __('Single Page Title', 'wpzoom'),
                'options' => array(
                    'page-title' => array(
                        'type' => 'typography',
                        'selector' => '.page h1.entry-title',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 26,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'uppercase'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 26,
                            'tablet' => 26,
                            'mobile' => 24
                        )
                    )
                )
            ),
            'font-page-title-image' => array(
                'panel' => 'typography',
                'title' => __('Single Page Title  (with Featured Image)', 'wpzoom'),
                'options' => array(
                    'page-title-image' => array(
                        'type' => 'typography',
                        'selector' => '.page .has-post-cover .entry-header h1.entry-title',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 45,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 45,
                            'tablet' => 32,
                            'mobile' => 24
                        )
                    )
                )
            ),

            'font-portfolio-title' => array(
                'panel' => 'typography',
                'title' => __('Portfolio Post Title (in Galleries)', 'wpzoom'),
                'options' => array(
                    'portfolio-title' => array(
                        'type' => 'typography',
                        'selector' => '.entry-thumbnail-popover-content h3',
                        'rules' => array(
                            'font-family' => 'Libre Franklin',
                            'font-size' => 26,
                            'font-weight' => 'bold',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 26,
                            'tablet' => 18,
                            'mobile' => 18
                        )
                    )
                )
            ),

            'font-portfolio-title-lightbox' => array(
                'panel' => 'typography',
                'title' => __('Portfolio Post Title (in Galleries with Lightbox)', 'wpzoom'),
                'options' => array(
                    'portfolio-title-lightbox' => array(
                        'type' => 'typography',
                        'selector' => '.entry-thumbnail-popover-content.lightbox_popup_insp h3',
                        'rules' => array(
                            'font-family' => 'Montserrat',
                            'font-size' => 18,
                            'font-weight' => '500',
                            'letter-spacing' => 0,
                            'font-subset' => 'latin',
                            'text-transform' => 'none',
                            'font-style' => 'normal'
                        ),
                        'font-size-responsive' => array(
                            'desktop' => 18,
                            'tablet' => 14,
                            'mobile' => 14
                        )
                    )
                )
            ),
            'footer-area' => array(
                'title' => __('Footer', 'wpzoom'),
                'options' => array(
                    'footer-widget-areas' => array(
                        'setting' => array(
                            'default' => '3',
                            'sanitize_callback' => 'sanitize_text_field',
                            'transport' => 'refresh'
                        ),
                        'control' => array(
                            'type' => 'select',
                            'label' => __('Number of Widget Areas', 'wpzoom'),
                            'choices' => array( '0', '1', '2', '3', '4' ),
                        )
                    ),
                    'blogcopyright' => array(
                        'setting' => array(
                            'sanitize_callback' => 'sanitize_text_field',
                            'default' => get_option('blogcopyright', sprintf( __( 'Copyright &copy; %1$s &mdash; %2$s. All Rights Reserved', 'wpzoom' ), date( 'Y' ), get_bloginfo( 'name' ) )),
                            'transport' => 'postMessage',
                            'type' => 'option'
                        ),
                        'control' => array(
                            'label' => __('Footer Text', 'wpzoom'),
                            'type' => 'text',
                            'priority' => 10
                        ),
                        'partial' => array(
                            'selector' => '.site-info .copyright',
                            'container_inclusive' => false,
                            'render_callback' => 'zoom_customizer_partial_blogcopyright'
                        )

                    )
                )
            )
        );

        zoom_customizer_normalize_options($data);
    }


    return $data;
}

add_filter('wpzoom_customizer_data', 'inspiro_customizer_data');