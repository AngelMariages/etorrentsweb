<?php 

$settings->dot_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'dot_color' );
$settings->arrow_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'arrow_color' );
$settings->img_size = ( trim($settings->img_size) !== '' ) ? $settings->img_size : '100';

?>


.fl-node-<?php echo $id; ?> .wpzabb-testimonials-wrap .bx-pager.bx-default-pager a,
.fl-node-<?php echo $id; ?> .wpzabb-testimonials-wrap .bx-pager.bx-default-pager a.active {
	background: <?php echo $settings->dot_color; ?>;
	opacity: 1;
}
.fl-node-<?php echo $id; ?> .wpzabb-testimonials-wrap .bx-pager.bx-default-pager a {
	opacity: 0.2;
}
.fl-node-<?php echo $id; ?> .wpzabb-testimonials-wrap .fa:hover,
.fl-node-<?php echo $id; ?> .wpzabb-testimonials-wrap .fa {
	color: <?php echo $settings->arrow_color; ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-testimonial-author-avatar img {
	width: <?php echo (int)$settings->img_size; ?>px;
}