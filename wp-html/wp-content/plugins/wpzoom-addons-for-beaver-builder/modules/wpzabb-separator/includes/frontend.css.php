<?php 
	$settings->color = wpzabb_theme_base_color( WPZABB_Helper::wpzabb_colorpicker( $settings, 'color' ) );
	$settings->height = ( trim( $settings->height ) !== '' ) ? $settings->height : '1';
	$settings->width = ( trim( $settings->width ) !== '' ) ? $settings->width : '100';
?>
.fl-node-<?php echo $id; ?> .wpzabb-separator {
	border-top:<?php echo $settings->height; ?>px <?php echo $settings->style; ?> <?php echo $settings->color ; ?>;
	width: <?php echo $settings->width; ?>%;
	display: inline-block;
}
.fl-node-<?php echo $id; ?> .wpzabb-separator-parent {
	text-align: <?php echo $settings->alignment; ?>;
}