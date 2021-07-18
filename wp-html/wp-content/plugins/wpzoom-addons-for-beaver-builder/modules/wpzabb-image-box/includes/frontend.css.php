<?php
	// General background color
	$settings->bg_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'bg_color', true ); // opacity true
	$settings->bg_hover_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'bg_hover_color', true ); // opacity true

	// Heading
	$settings->heading_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'color' );
	$settings->heading_margin_top = ( trim($settings->heading_margin_top) !== '' ) ? $settings->heading_margin_top : '20';
	$settings->heading_margin_bottom = ( trim($settings->heading_margin_bottom) !== '' ) ? $settings->heading_margin_bottom : '10';

	// Subheadign
	$settings->subheading_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'subheading_color' );
	$settings->subheading_margin_top = ( trim($settings->subheading_margin_top) !== '' ) ? $settings->subheading_margin_top : '0';
	$settings->subheading_margin_bottom = ( trim($settings->subheading_margin_bottom) !== '' ) ? $settings->subheading_margin_bottom : '10';

	// Description
	$settings->desc_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'desc_color' );
	$settings->desc_margin_top = ( trim($settings->desc_margin_top) !== '' ) ? $settings->desc_margin_top : '0';
	$settings->desc_margin_bottom = ( trim($settings->desc_margin_bottom) !== '' ) ? $settings->desc_margin_bottom : '20';
?>

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image::before {
	background-color: <?php echo $settings->bg_color; ?>
}
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image:hover::before {
	background-color: <?php echo $settings->bg_hover_color; ?>
}

<?php /* Grid Layout */ ?>

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-2-cols .wpzabb-image {
	width: 48.68%;
	margin-right: 2.3%;
}
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-3-cols .wpzabb-image {
	width: 31.7%;
    margin-right: 2%;
}
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-4-cols .wpzabb-image {
	width: 23%;
    margin-right: 2.3%;
}

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-2-cols .wpzabb-image:nth-of-type(2n+2) {
    margin-right: 0;
}
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-3-cols .wpzabb-image:nth-of-type(3n+3) {
    margin-right: 0;
}
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-4-cols .wpzabb-image:nth-of-type(4n+4) {
    margin-right: 0;
}

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-2-cols .wpzabb-image:last-child,
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-3-cols .wpzabb-image:last-child,
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-4-cols .wpzabb-image:last-child {
	margin-right: 0;
}

<?php /* Typography formating */ ?>

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading {
	margin-top: <?php echo $settings->heading_margin_top; ?>px;
	margin-bottom: <?php echo $settings->heading_margin_bottom; ?>px;
}

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading,
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading * {
	<?php if(!empty($settings->heading_color)) : ?>
		color: <?php echo $settings->heading_color; ?>;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-subheading {
	display: block;
	margin-top: <?php echo $settings->subheading_margin_top; ?>px;
	margin-bottom: <?php echo $settings->subheading_margin_bottom; ?>px;
	<?php if(!empty($settings->subheading_color)) : ?>
		color: <?php echo $settings->subheading_color; ?>;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-description * {
	margin-top: <?php echo $settings->desc_margin_top; ?>px;
	margin-bottom: <?php echo $settings->desc_margin_bottom; ?>px;
	color: <?php echo wpzabb_theme_text_color( $settings->desc_color ); ?>;
}

<?php /* Name font settings */ ?>
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading,
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading * {

	<?php if( !empty($settings->font) && $settings->font['family'] != 'Default' ) : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->font ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->new_font_size_unit ) && $settings->new_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->new_font_size_unit; ?>px;
	<?php endif; ?>

	<?php if( isset( $settings->line_height_unit ) && $settings->line_height_unit != '' ) : ?>
		line-height: <?php echo $settings->line_height_unit; ?>em;
	<?php endif; ?>

	<?php if ( ! empty( $settings->text_transform ) ) : ?>
		text-transform: <?php echo $settings->text_transform; ?>;
	<?php endif; ?>

	<?php if ( 'custom' == $settings->letter_spacing ) : ?>
		letter-spacing: <?php echo $settings->custom_letter_spacing; ?>px;
	<?php endif; ?>
}

<?php /* Position font settings */ ?>
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-subheading {

	<?php if( !empty($settings->subheading_font_family) && $settings->subheading_font_family['family'] != 'Default' ) : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->subheading_font_family ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->subheading_font_size_unit ) && $settings->subheading_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->subheading_font_size_unit; ?>px;
	<?php endif; ?>

	<?php if( isset( $settings->subheading_line_height_unit ) && $settings->subheading_line_height_unit != '' ) : ?>
		line-height: <?php echo $settings->subheading_line_height_unit; ?>em;
	<?php endif; ?>

	<?php if ( ! empty( $settings->subheading_text_transform ) ) : ?>
		text-transform: <?php echo $settings->subheading_text_transform; ?>;
	<?php endif; ?>

	<?php if ( 'custom' == $settings->subheading_letter_spacing ) : ?>
		letter-spacing: <?php echo $settings->subheading_custom_letter_spacing; ?>px;
	<?php endif; ?>
}

<?php /* Info font settings */ ?>
.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-description {

	<?php if( !empty($settings->desc_font) && $settings->desc_font['family'] != 'Default' ) : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->fdesc_ont ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->desc_font_size_unit ) && $settings->desc_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->desc_font_size_unit; ?>px;
	<?php endif; ?>

	<?php if( isset( $settings->desc_line_height_unit ) && $settings->desc_line_height_unit != '' ) : ?>
		line-height: <?php echo $settings->desc_line_height_unit; ?>em;
	<?php endif; ?>

	<?php if ( ! empty( $settings->desc_text_transform ) ) : ?>
		text-transform: <?php echo $settings->desc_text_transform; ?>;
	<?php endif; ?>

	<?php if ( 'custom' == $settings->desc_letter_spacing ) : ?>
		letter-spacing: <?php echo $settings->desc_custom_letter_spacing; ?>px;
	<?php endif; ?>
}


<?php /* Button settings styles */ ?>
<?php
	$btn_settings = array(
		'align'             		=> $settings->btn_align,
		'mob_align'             	=> $settings->btn_mob_align,
		'border_radius'             => $settings->btn_border_radius,
		'width' 					=> $settings->btn_width,
		'custom_width' 				=> $settings->btn_custom_width,
		'custom_height' 			=> $settings->btn_custom_height,
		'padding_top_bottom' 		=> $settings->btn_padding_top_bottom,
		'padding_left_right' 		=> $settings->btn_padding_left_right,
		'font_family'       		=> $settings->btn_font_family,
		'font_size_unit'   			=> $settings->btn_font_size_unit,
		'font_size_unit_medium' 	=> $settings->btn_font_size_unit_medium,
		'font_size_unit_responsive' => $settings->btn_font_size_unit_responsive,
		'line_height_unit' 			=> $settings->btn_line_height_unit,
		'line_height_unit_medium' 	=> $settings->btn_line_height_unit_medium,
		'line_height_unit_responsive' => $settings->btn_line_height_unit_responsive,
		'text_transform' 			=> $settings->btn_text_transform,
		'letter_spacing' 			=> $settings->btn_letter_spacing,
		'letter_spacing_medium' 	=> $settings->btn_custom_letter_spacing_medium,
		'letter_spacing_responsive' => $settings->btn_custom_letter_spacing_responsive,
		'style' 					=> $settings->btn_style,
		'border_size' 				=> $settings->btn_border_size,
		'flat_options' 				=> $settings->btn_flat_options,
		'icon' 						=> $settings->btn_icon,
		'icon_position' 			=> $settings->btn_icon_position,
		'text_color' 				=> $settings->btn_text_color,
		'text_hover_color' 			=> $settings->btn_text_hover_color,
		'bg_color' 					=> $settings->btn_bg_color,
		'bg_color_opc' 				=> $settings->btn_bg_color_opc,
		'bg_hover_color' 			=> $settings->btn_bg_hover_color,
		'bg_hover_color_opc' 		=> $settings->btn_bg_hover_color_opc,
		'hover_attribute' 			=> $settings->btn_hover_attribute,
	);

	FLBuilder::render_module_css( 'wpzabb-button', $id, $btn_settings );
?>


<?php /* Global Setting If started */ ?>
<?php if($global_settings->responsive_enabled) { ?>

        <?php /* Medium Breakpoint media query */  ?>
        @media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {

        	<?php /* For Medium Device */ ?>
            .fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading,
            .fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading * {

				<?php if( isset( $settings->new_font_size_unit_medium ) && $settings->new_font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->new_font_size_unit_medium; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->line_height_unit_medium ) && $settings->line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_medium; ?>em;
				<?php endif; ?>

			}
			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-subheading {

				<?php if( isset( $settings->subheading_font_size_unit_medium ) && $settings->subheading_font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->subheading_font_size_unit_medium; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->subheading_line_height_unit_medium ) && $settings->subheading_line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->subheading_line_height_unit_medium; ?>em;
				<?php endif; ?>
			}
			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-description {

				<?php if( isset( $settings->desc_font_size_unit_medium ) && $settings->desc_font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->desc_font_size_unit_medium; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->desc_line_height_unit_medium ) && $settings->desc_line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->desc_line_height_unit_medium; ?>em;
				<?php endif; ?>
			}
        }

        <?php /* Small Breakpoint media query */ ?>
        @media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {

        	<?php /* For Small Device */ ?>
            .fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading,
            .fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-heading * {

				<?php if( isset( $settings->new_font_size_unit_responsive ) && $settings->new_font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->new_font_size_unit_responsive; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->line_height_unit_responsive ) && $settings->line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-subheading {

				<?php if( isset( $settings->subheading_font_size_unit_responsive ) && $settings->subheading_font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->subheading_font_size_unit_responsive; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->subheading_line_height_unit_responsive ) && $settings->subheading_line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->subheading_line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap .wpzabb-image-description {

				<?php if( isset( $settings->desc_font_size_unit_responsive ) && $settings->desc_font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->desc_font_size_unit_responsive; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->desc_line_height_unit_responsive ) && $settings->desc_line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->desc_line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			<?php /* Grid Layout */ ?>
			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-2-cols .wpzabb-image,
			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-3-cols .wpzabb-image,
			.fl-node-<?php echo $id; ?> .wpzabb-image-box-wrap.layout-4-cols .wpzabb-image {
				width: 100%;
				margin-right: 0;
			}
        }
    <?php
} ?>


