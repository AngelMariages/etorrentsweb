<?php if (!defined('FW')) die( 'Forbidden' ); ?>

<?php

if (post_type_exists( 'product' ) )  {


    $term_id   = (int) $atts['category'];

    $posts_per_page = (int) $atts['posts_number'];
    $orderby = $atts['products_orderby'];
    $order = $atts['products_order'];
    if ( $posts_per_page == 0 ) {
        $posts_per_page = - 1;
    }


    if ( $term_id == 0 ) {
        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'product',
            'order'        => $order
        );
    } else {
        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'product',
            'order'          => $order,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $term_id
                )
            )
        );
    }

    switch ( $orderby ) {
        case 'price' :
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            break;
        case 'rand' :
            $args['orderby']  = 'rand';
            break;
        case 'sales' :
            $args['meta_key'] = 'total_sales';
            $args['orderby']  = 'meta_value_num';
            break;
        default :
            $args['orderby']  = 'date';
    }



    $wp_query = new WP_Query( $args );

    ?>


    <div class="woocommerce">


        <?php woocommerce_product_loop_start(); ?>


        <?php if ( $wp_query->have_posts() ) : ?>


                <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); $_product;

                    if ( function_exists( 'wc_get_product' ) ) {
                        $_product = wc_get_product( $wp_query->post->ID );
                    } else {
                        $_product = new WC_Product( $wp_query->post->ID );
                    }

                    ?>

                   <li <?php post_class(); ?>>
                    <?php
                    /**
                     * woocommerce_before_shop_loop_item hook.
                     *
                     * @hooked woocommerce_template_loop_product_link_open - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item' );

                    /**
                     * woocommerce_before_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_template_loop_product_thumbnail - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item_title' );

                    /**
                     * woocommerce_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_template_loop_product_title - 10
                     */
                    do_action( 'woocommerce_shop_loop_item_title' );



                    if ( $atts['product_price'] == 'price_show') {

                        /**
                         * woocommerce_after_shop_loop_item_title hook.
                         *
                         * @hooked woocommerce_template_loop_rating - 5
                         * @hooked woocommerce_template_loop_price - 10
                         */
                        do_action( 'woocommerce_after_shop_loop_item_title' );

                    }


                    if ( $atts['product_btn'] == 'cart_show') {

                        /**
                         * woocommerce_after_shop_loop_item hook.
                         *
                         * @hooked woocommerce_template_loop_product_link_close - 5
                         * @hooked woocommerce_template_loop_add_to_cart - 10
                         */
                        do_action( 'woocommerce_after_shop_loop_item' );

                    }
                    ?>
                   </li>


                <?php endwhile; ?>



        <?php endif; ?>

        <?php woocommerce_product_loop_end(); ?>

    </div>

<?php } ?>
