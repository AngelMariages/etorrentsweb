<?php
if ( post_type_exists( 'product' ) ) {
	$args = array(
		'limit' => ( (int)$settings->count > 0 ? (int)$settings->count : -1 ),
		'order' => $settings->orderdir,
		'orderby' => ( $settings->orderby == 'price' || $settings->orderby == 'sales' ? 'meta_value_num' : ( $settings->orderby == 'rand' ? 'rand' : 'date' ) ),
		'return' => 'objects'
	);
	if ( $settings->category != '0' ) $args['category'] = array( $settings->category );
	if ( $settings->featured == 'yes' ) $args['include'] = wc_get_featured_product_ids();
	if ( $settings->orderby == 'price' || $settings->orderby == 'sales' ) $args['meta_key'] = $settings->orderby == 'price' ? '_price' : 'total_sales';

	$products = wc_get_products( $args );

	if ( count( $products ) > 0 ) :
		$colsdsk = min( max( (int)$settings->columns, 1 ), 10 );
		$colstab = min( max( (int)$settings->columns_medium, 1 ), 10 );
		$colspho = min( max( (int)$settings->columns_responsive, 1 ), 10 );

?><div class="wpzabb-woocommerce-products woocommerce columns-desktop-<?php echo $colsdsk; ?> columns-tablet-<?php echo $colstab; ?> columns-phone-<?php echo $colspho; ?>">

	<?php
	wc_setup_loop();

	woocommerce_product_loop_start();

	foreach ( $products as $product ) {
		$post_object = get_post( $product->get_id() );
		setup_postdata( $GLOBALS['post'] =& $post_object );
		$GLOBALS['product'] = $product;

		?><li <?php wc_product_class( '', $product ); ?>>
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

			if ( $settings->showprice == 'true' ) {
				/**
				 * woocommerce_after_shop_loop_item_title hook.
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
			}

			if ( $settings->showcartbtn == 'true' ) {
				/**
				 * woocommerce_after_shop_loop_item hook.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
			}
			?>
		</li><?php
	}

	wp_reset_postdata();

	woocommerce_product_loop_end();
	?>

</div><?php

	endif;

	wp_reset_postdata();
}