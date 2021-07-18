
<nav id="side-nav" class="side-nav" tabindex="-1">
    <div class="side-nav__scrollable-container">
        <div class="side-nav__wrap">

            <div class="side-nav__close-button">
                <button type="button" class="navbar-toggle">
                    <span class="sr-only"><?php _e( 'Toggle navigation', 'wpzoom' ); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <?php
            if ( has_nav_menu( 'primary' ) ) :
                wp_nav_menu( array(
                    'menu_class'     => 'nav navbar-nav',
                    'theme_location' => 'primary',
                    'items_wrap'     => '<ul class="%2$s">%3$s' . inspiro_wc_menu_cartitem() . '</ul>',
                    'container'      => false
                ) );
            endif;
            ?>

            <?php dynamic_sidebar( 'sidebar' ); ?>
        </div>
    </div>
</nav>
<div class="side-nav-overlay"></div>
