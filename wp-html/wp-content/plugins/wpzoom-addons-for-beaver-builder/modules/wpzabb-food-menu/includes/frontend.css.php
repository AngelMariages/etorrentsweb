.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap {
	background: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->background_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap::before {
	border: 2px solid <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->outline_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array( 'settings' => $settings, 'setting_name' => 'title_font', 'selector' => ".fl-node-$id .wpzabb-food-menu-wrap .wpzabb-food-menu-title" ) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-title {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->title_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item {
	border-bottom: <?php echo $settings->item_separator_size . $settings->item_separator_size_unit; ?> <?php echo $settings->item_separator_style; ?> <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_separator_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child {
	border-top: <?php echo $settings->item_separator_size . $settings->item_separator_size_unit; ?> <?php echo $settings->item_separator_style; ?> <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_separator_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child {
	border-bottom: <?php echo $settings->item_separator_size . $settings->item_separator_size_unit; ?> <?php echo $settings->item_separator_style; ?> <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_separator_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-image {
	flex-basis: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_image_size ); ?>%;
}

<?php echo FLBuilderCSS::typography_field_rule( array( 'settings' => $settings, 'setting_name' => 'item_name_font', 'selector' => ".fl-node-$id .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name" ) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_name_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_name_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a:hover,
.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a:active {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_name_hover_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array( 'settings' => $settings, 'setting_name' => 'item_price_font', 'selector' => ".fl-node-$id .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-price" ) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-price {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_price_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array( 'settings' => $settings, 'setting_name' => 'item_description_font', 'selector' => ".fl-node-$id .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-description" ) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-description {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->item_description_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array( 'settings' => $settings, 'setting_name' => 'button_font', 'selector' => ".fl-node-$id .wpzabb-food-menu-wrap .wpzabb-food-menu-button a" ) ); ?>

<?php echo FLBuilderCSS::border_field_rule( array( 'settings' => $settings, 'setting_name' => 'button_border', 'selector' => ".fl-node-$id .wpzabb-food-menu-wrap .wpzabb-food-menu-button a" ) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-button a {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->button_color); ?>;
	background: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->button_background ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-button a:hover,
.fl-node-<?php echo $id; ?> .wpzabb-food-menu-wrap .wpzabb-food-menu-button a:active {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->button_hover_color); ?>;
}