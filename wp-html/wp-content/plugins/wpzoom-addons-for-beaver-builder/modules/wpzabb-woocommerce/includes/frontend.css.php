<?php if ( $global_settings->responsive_enabled ) : ?>
	@media ( min-width: <?php echo $global_settings->medium_breakpoint . 'px'; ?> ) {
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-1 .products li { width: calc(100% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-2 .products li { width: calc(50% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-3 .products li { width: calc(33.3% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-4 .products li { width: calc(25% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-5 .products li { width: calc(20% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-6 .products li { width: calc(16.6% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-7 .products li { width: calc(14.2% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-8 .products li { width: calc(12.5% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-9 .products li { width: calc(11.1% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-desktop-10 .products li { width: calc(10% - 30px); }
	}

	@media ( max-width: <?php echo $global_settings->medium_breakpoint . 'px'; ?> ) {
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-1 .products li { width: calc(100% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-2 .products li { width: calc(50% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-3 .products li { width: calc(33.3% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-4 .products li { width: calc(25% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-5 .products li { width: calc(20% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-6 .products li { width: calc(16.6% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-7 .products li { width: calc(14.2% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-8 .products li { width: calc(12.5% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-9 .products li { width: calc(11.1% - 30px); }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-tablet-10 .products li { width: calc(10% - 30px); }
	}

	@media ( max-width: <?php echo $global_settings->responsive_breakpoint . 'px'; ?> ) {

		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-1 .products li { width: auto !important; margin-right: auto !important; margin-left: auto !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-2 .products li { width: calc(50% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-3 .products li { width: calc(33.3% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-4 .products li { width: calc(25% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-5 .products li { width: calc(20% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-6 .products li { width: calc(16.6% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-7 .products li { width: calc(14.2% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-8 .products li { width: calc(12.5% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-9 .products li { width: calc(11.1% - 30px) !important; }
		.fl-node-<?php echo $id; ?> .wpzabb-woocommerce-products.columns-phone-10 .products li { width: calc(10% - 30px) !important; }
	}
<?php endif; ?>