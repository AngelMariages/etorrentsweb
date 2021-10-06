<?php
/*-----------------------------------------------------------------------------------*/
/* Initializing Widgetized Areas (Sidebars)                                          */
/*-----------------------------------------------------------------------------------*/

/*----------------------------------*/
/* Sidebar                          */
/*----------------------------------*/

register_sidebar(array(
    'name'          => 'Sidebar',
    'id'            => 'sidebar',
    'description'   => 'Main sidebar that is displayed on the right and can be toggled by clicking on the menu icon.',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

register_sidebar(array(
    'name'          => 'Sidebar on the Right',
    'id'            => 'blog-sidebar',
    'description'   => 'Sidebar displayed on pages and blog posts when the sidebar enabled',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

register_sidebar(array(
    'name'          => 'Portfolio Sidebar',
    'id'            => 'portfolio-sidebar',
    'description'   => 'Sidebar displayed on single portfolio posts when the sidebar enabled',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

/*----------------------------------*/
/* Shop Sidebar                     */
/*----------------------------------*/

register_sidebar(array(
    'name'          => 'Shop Sidebar',
    'id'            => 'sidebar-shop',
    'description'   => 'Main sidebar for Shop and other WooCommerce related pages.',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget'  => '<div class="clear">&nbsp;</div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

/*----------------------------------*/
/* Homepage widgetized areas        */
/*----------------------------------*/

register_sidebar(array(
    'name'          => 'Homepage',
    'id'            => 'home-full',
    'description'   => 'Widget area for page template "Homepage (Widgetized)". &#13; &#10; &#09; Add here: "WPZOOM: Portfolio Scroller", "WPZOOM: Portfolio Showcase" widgets.',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h2 class="section-title">',
    'after_title'   => '</h2>',
));

/*----------------------------------*/
/* Footer widgetized areas          */
/*----------------------------------*/

register_sidebar(array('name' => 'Footer: Column 1',
    'id'            => 'footer_1',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

register_sidebar(array('name' => 'Footer: Column 2',
    'id'            => 'footer_2',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

register_sidebar(array('name' => 'Footer: Column 3',
    'id'            => 'footer_3',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
));

register_sidebar( array(
    'name'          => 'Footer: Column 4',
    'id'            => 'footer_4',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget'  => '<div class="clear"></div></div>',
    'before_title'  => '<h3 class="title">',
    'after_title'   => '</h3>',
) );


/* Header - for social icons
===============================*/

register_sidebar(array(
    'name'=>'Header Social Icons',
    'id' => 'header_social',
    'description' => 'Widget area in the header. Install the "Social Icons Widget by WPZOOM" plugin and add the widget here.',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="title"><span>',
    'after_title' => '</span></h3>',
));


register_sidebar(array('name'=>'Footer Instagram Bar',
    'description' => 'Widget area for "Instagram widget by WPZOOM".',
    'id' => 'footer_instagram_section',
    'before_widget' => '<div class="widget %2$s" id="%1$s">',
    'after_widget' => '<div class="clear"></div></div>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>',
));