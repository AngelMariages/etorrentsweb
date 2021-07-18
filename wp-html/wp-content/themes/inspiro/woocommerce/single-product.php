<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         http://docs.woothemes.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>


<div id="main">

    <div class="inner-wrap wrap--layout-<?php echo esc_attr( option::get( 'layout_product' ) ); ?>">

        <main class="site-main container-fluid">

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="entry-content">

                    <?php do_action('woocommerce_output_content_wrapper'); ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php wc_get_template_part( 'content', 'single-product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                </div>

                <div class="clearfix"></div>
            </article><!-- #post-## -->

        </main><!-- #main -->


        <?php if ( option::get( 'layout_product' ) !== 'full' && is_active_sidebar( 'sidebar-shop' ) ) : ?>

            <div class="sidebar sidebar--shop sidebar--product">

                <?php dynamic_sidebar( 'sidebar-shop' ); ?>

            </div>

        <?php endif; ?>

    </div><!-- .main-wrap -->
</div>

<?php get_footer('shop'); ?>