<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 */

$widgets_areas = (int) get_theme_mod( 'footer-widget-areas', zoom_customizer_get_default_option_value( 'footer-widget-areas', inspiro_customizer_data() ) );

$has_active_sidebar = false;
if ( $widgets_areas > 0 ) {
    $i = 1;

    while ( $i <= $widgets_areas ) {
        if ( is_active_sidebar( 'footer_' . $i ) ) {
            $has_active_sidebar = true;
            break;
        }

        $i++;
    }
}

?>

    <?php if ( is_active_sidebar( 'footer_instagram_section' ) ) : ?>
        <section class="site-widgetized-section section-footer">
            <div class="widgets clearfix">
                <?php dynamic_sidebar( 'footer_instagram_section' ); ?>
            </div>
        </section><!-- .site-widgetized-section -->
    <?php endif; ?>

    <footer id="colophon" class="site-footer" role="contentinfo">

        <div class="inner-wrap">

            <?php if ( $has_active_sidebar ) : ?>

                <div class="footer-widgets widgets widget-columns-<?php echo esc_attr( $widgets_areas ); ?>">
                    <?php for ( $i = 1; $i <= $widgets_areas; $i ++ ) : ?>

                        <div class="column">
                            <?php dynamic_sidebar( 'footer_' . $i ); ?>
                        </div><!-- .column -->

                    <?php endfor; ?>

                    <div class="clear"></div>

                    <div class="site-footer-separator"></div>

                </div><!-- .footer-widgets -->


            <?php endif; ?>


            <div class="site-info">
                <p class="copyright"><?php zoom_customizer_partial_blogcopyright(); ?></p>

                <p class="designed-by">
                    <?php printf( __( 'Designed by %s', 'wpzoom' ), '<a href="https://www.wpzoom.com/" target="_blank" rel="nofollow">WPZOOM</a>' ); ?>
                </p>
            </div><!-- .site-info -->

        </div>

    </footer><!-- #colophon -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>