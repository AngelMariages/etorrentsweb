<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="preload" as="font" href="<?php echo get_bloginfo('template_url'); ?>/fonts/inspiro.woff"  type="font/woff" crossorigin>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php get_sidebar(); ?>

<?php
    $header_layout = get_theme_mod('header-layout-type', zoom_customizer_get_default_option_value('header-layout-type', inspiro_customizer_data()));
?>

<div class="site">

    <header class="site-header">
        <nav class="navbar <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?> " role="navigation">
            <div class="inner-wrap<?php echo ' '.$header_layout; ?>">

                 <div class="navbar-header">
                     <!-- navbar-brand BEGIN -->
                     <div class="navbar-brand-wpz">

                        <?php inspiro_custom_logo() ?>

                     </div>
                     <!-- navbar-brand END -->
                </div>

                <?php if ( has_nav_menu( 'primary' ) || is_active_sidebar( 'sidebar' ) ) : ?>

                    <button type="button" class="navbar-toggle">
                        <span class="sr-only"><?php _e( 'Toggle sidebar &amp; navigation', 'wpzoom' ); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <div id="sb-search" class="sb-search">
                        <?php get_template_part( 'header_search' ); ?>
                    </div>

                    <div class="header_social">
                        <?php dynamic_sidebar('header_social'); ?>
                    </div>

                    <div class="navbar-collapse collapse">

                        <?php if (has_nav_menu( 'primary' )) {
                            wp_nav_menu( array(
                                'menu_class'     => 'nav navbar-nav dropdown sf-menu',
                                'theme_location' => 'primary',
                                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s' . inspiro_wc_menu_cartitem() . '</ul>',
                                'container'      => false
                            ) );
                        } else {
                            wp_nav_menu( array(
                               'menu_class'     => 'nav navbar-nav dropdown sf-menu',
                               'menu' => 'main'
                            ) );
                        } ?>

                    </div><!-- .navbar-collapse -->

                <?php endif; ?>

            </div>
        </nav><!-- .navbar -->
    </header><!-- .site-header -->
