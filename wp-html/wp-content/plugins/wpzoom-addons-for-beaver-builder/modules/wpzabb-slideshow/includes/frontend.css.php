<?php
$auto_height = 'yes' == $settings->slideshow_autoheight;
$auto_height_size = intval( $settings->slideshow_autoheight_size );
$auto_height_max = intval( $settings->slideshow_autoheight_max );
$button_align_dsk = property_exists( $settings, 'slide_button_align' ) ? ( 'right' == $settings->slide_button_align ? 'right' : ( 'center' == $settings->slide_button_align ? 'center' : 'left' ) ) : 'left';
$button_align_tab = property_exists( $settings, 'slide_button_align_medium' ) ? ( 'right' == $settings->slide_button_align_medium ? 'right' : ( 'center' == $settings->slide_button_align_medium ? 'center' : 'left' ) ) : 'left';
$button_align_mob = property_exists( $settings, 'slide_button_align_responsive' ) ? ( 'right' == $settings->slide_button_align_responsive ? 'right' : ( 'center' == $settings->slide_button_align_responsive ? 'center' : 'left' ) ) : 'left';
?>

<?php if ( $auto_height ) : ?>
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides,
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .flickity-viewport,
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .flickity-slider,
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide,
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide-outer-wrap {
		height: <?php echo $auto_height_size; ?>vh;
		max-height: <?php echo $auto_height_max; ?>px;
	}
<?php endif; ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide {
	background-color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_background_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-image::after {
	background-image: <?php echo FLBuilderColor::gradient( $settings->slide_overlay_gradient ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_title_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_title_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a:hover {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_title_hover_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_content_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_content_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button {
	text-align: <?php echo $button_align_dsk; ?>;
}

@media screen and (max-width: 768px) {
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button {
		text-align: <?php echo $button_align_tab; ?>;
	}
}

@media screen and (max-width: 460px) {
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button {
		text-align: <?php echo $button_align_mob; ?>;
	}
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_color ); ?>;
	background-color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_background_color ); ?>;
}

<?php echo FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_border',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a"
) ); ?>

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_hover_color ); ?>;
	background-color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_hover_background_color ); ?>;
}

<?php echo FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_hover_border',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover"
) ); ?>

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_hover_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a::before {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_navigation_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:hover,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:active,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:hover::before,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:active::before {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_navigation_hover_color ); ?>;
}