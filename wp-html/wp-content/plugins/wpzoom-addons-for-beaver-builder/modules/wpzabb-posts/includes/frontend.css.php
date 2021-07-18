<?php

$layout = $module->get_layout_slug();
$file   = $module->dir . 'includes/post-' . $layout;
$custom = isset( $settings->post_layout ) && 'custom' == $settings->post_layout;

if ( fl_builder_filesystem()->file_exists( $file . '-common.css.php' ) ) {
	include $file . '-common.css.php';
}
if ( ! $custom && fl_builder_filesystem()->file_exists( $file . '.css.php' ) ) {
	include $file . '.css.php';
}

if ( 'load_more' == $settings->pagination ) {

	$btn_settings = array(
		'text'             			=> $settings->more_btn_text,
		'link'             			=> '#',
		'link_target'             	=> '_self',
		'align'             		=> 'center',
		'mob_align'             	=> 'center',
		'border_radius'             => $settings->more_btn_border_radius,
		'width' 					=> $settings->more_btn_width,
		'padding_top_bottom' 		=> $settings->more_btn_padding_top_bottom,
		'padding_left_right' 		=> $settings->more_btn_padding_left_right,
		'font_family'       		=> $settings->more_btn_font_family,
		'font_size_unit'   			=> $settings->more_btn_font_size,
		'line_height_unit' 			=> $settings->more_btn_line_height_unit,
		'letter_spacing' 			=> $settings->more_btn_custom_letter_spacing,
		'text_transform' 			=> $settings->more_btn_text_transform,
		'letter_spacing' 			=> $settings->more_btn_letter_spacing,
		'style' 					=> $settings->more_btn_style,
		'border_size' 				=> $settings->more_btn_border_size,
		'flat_options' 				=> $settings->more_btn_flat_options,
		'icon' 						=> $settings->more_btn_icon,
		'icon_position' 			=> $settings->more_btn_icon_position,
		'text_color' 				=> $settings->more_btn_text_color,
		'text_hover_color' 			=> $settings->more_btn_text_hover_color,
		'bg_color' 					=> $settings->more_btn_bg_color,
		'bg_color_opc' 				=> $settings->more_btn_bg_color_opc,
		'bg_hover_color' 			=> $settings->more_btn_bg_hover_color,
		'bg_hover_color_opc' 		=> $settings->more_btn_bg_hover_color_opc,
		'transparent_button_options' => 'none',
		'hover_attribute' 			=> $settings->more_btn_hover_attribute,
	);

	FLBuilder::render_module_css('wpzabb-button', $id, $btn_settings);
}
