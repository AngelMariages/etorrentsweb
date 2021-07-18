<?php

$settings->bg_color = wpzabb_theme_button_bg_color( $settings->bg_color );
$settings->bg_hover_color = wpzabb_theme_button_bg_hover_color( $settings->bg_hover_color );
$settings->text_color = wpzabb_theme_button_text_color( $settings->text_color );
$settings->text_hover_color = wpzabb_theme_button_text_hover_color( $settings->text_hover_color );

$settings->bg_color 		= WPZABB_Helper::wpzabb_colorpicker( $settings, 'bg_color', true );
$settings->bg_hover_color 	= WPZABB_Helper::wpzabb_colorpicker( $settings, 'bg_hover_color', true );
$settings->text_color 		= WPZABB_Helper::wpzabb_colorpicker( $settings, 'text_color');
$settings->text_hover_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'text_hover_color');

// Border Color
if ( ! empty( $settings->bg_color ) ) {
	$border_color = $settings->bg_color;
}
if ( ! empty( $settings->bg_hover_color ) ) {
	$border_hover_color = $settings->bg_hover_color;
}

// Old Background Gradient Setting
if ( isset( $settings->three_d ) && $settings->three_d ) {
	$settings->style = 'gradient';
}

// Background Gradient
if ( ! empty( $settings->bg_color ) ) {
	$bg_grad_start = "#" .FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_color ), 30, 'lighten' );
}
if ( ! empty( $settings->bg_hover_color ) ) {
	$bg_hover_grad_start = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_hover_color ), 30, 'lighten' );
}

?>

.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {
	color: #333;
}

<?php if ( $settings->threed_button_options == 'animate_top' || $settings->threed_button_options == 'animate_bottom' ) { ?>
/* 3D Fix */

	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap.wpzabb-button-width-auto .perspective, 
	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap.wpzabb-button-width-custom .perspective {
	   display: inline-block;
	   max-width: 100%;
	}
<?php } ?>

<?php 
	
    $settings->font_family = (array)$settings->font_family; 

?>

.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {
	
	<?php if( $settings->font_family['family'] != "Default") : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->font_family ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->font_size_unit ) && $settings->font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->font_size_unit; ?>px;
		<?php if( $settings->line_height_unit == '' ) ?>
			line-height: <?php echo $settings->font_size_unit + 2; ?>px; 
	<?php endif; ?>

	<?php if( isset( $settings->font_size ) && is_array( $settings->font_size ) ) { ?>
		<?php if( isset( $settings->font_size['desktop'] ) && $settings->font_size['desktop'] == '' && isset( $settings->line_height['desktop'] ) && $settings->line_height['desktop'] != '' && $settings->line_height_unit == '' ) { ?>
		    line-height: <?php echo $settings->line_height['desktop']; ?>px;
		<?php } ?>
	<?php } else if( isset( $settings->font_size ) && is_object( $settings->font_size ) ) { ?>
		<?php if( isset( $settings->font_size->desktop ) && $settings->font_size->desktop == '' && isset( $settings->line_height->desktop ) && $settings->line_height->desktop != '' && $settings->line_height_unit == '' ) { ?>
		    line-height: <?php echo $settings->line_height->desktop; ?>px;
		<?php } ?>
	<?php } ?>

	<?php if ( 'custom' == $settings->letter_spacing ) : ?>
		letter-spacing: <?php echo $settings->custom_letter_spacing; ?>px;
	<?php endif; ?>

	<?php if ( ! empty( $settings->text_transform ) ) : ?>
		text-transform: <?php echo $settings->text_transform; ?>;
	<?php endif; ?>
	 
	<?php if( isset( $settings->line_height_unit ) && $settings->line_height_unit != '' ) : ?>
		line-height: <?php echo $settings->line_height_unit; ?>em;
	<?php endif; ?>
	
	<?php if( $settings->width == 'custom' ) { 
			$padding_top_bottom = ( $settings->padding_top_bottom !== '' ) ? $settings->padding_top_bottom : '0';
			$padding_left_right = ( $settings->padding_left_right !== '' ) ? $settings->padding_left_right : '0';
		?>

		padding-top: <?php echo $padding_top_bottom; ?>px;
		padding-bottom: <?php echo $padding_top_bottom; ?>px;
		padding-left: <?php echo $padding_left_right; ?>px;
		padding-right: <?php echo $padding_left_right; ?>px;
	<?php } else {
		echo "padding:". wpzabb_theme_button_padding( '' ).";";
	}
	
	$settings->border_radius = wpzabb_theme_button_border_radius( $settings->border_radius );
	if( $settings->border_radius != '' ) : ?>
	border-radius: <?php echo $settings->border_radius; ?>px;
	-moz-border-radius: <?php echo $settings->border_radius; ?>px;
	-webkit-border-radius: <?php echo $settings->border_radius; ?>px;
	<?php endif; ?>
	
	<?php if ( 'custom' == $settings->width ) : ?>
	width: <?php echo $settings->custom_width; ?>px;
	min-height: <?php echo $settings->custom_height; ?>px;
	display: -webkit-inline-box;
	display: -ms-inline-flexbox;
	display: inline-flex;
	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	justify-content: center;	
	<?php endif; ?>

	<?php if ( ! empty( $settings->bg_color ) ) : ?>
	background: <?php echo $settings->bg_color; ?>;
	border: <?php echo $settings->border_size; ?>px solid <?php echo $border_color; ?>;

		<?php if( 'gradient' == $settings->style ) : // Gradient ?>
		background: -moz-linear-gradient(top,  <?php echo $bg_grad_start; ?> 0%, <?php echo $settings->bg_color; ?> 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $bg_grad_start; ?>), color-stop(100%,<?php echo $settings->bg_color; ?>)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->bg_color; ?> 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->bg_color; ?> 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->bg_color; ?> 100%); /* IE10+ */
		background: linear-gradient(to bottom,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->bg_color; ?> 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $bg_grad_start; ?>', endColorstr='<?php echo $settings->bg_color; ?>',GradientType=0 ); /* IE6-9 */
		<?php endif; ?>
	
	<?php endif; ?>

	<?php if ( 'transparent' == $settings->style ) : // Transparent ?>
		background: none;
	<?php endif; ?>
}

<?php if ( $settings->icon != '' ): ?>
	.fl-node-<?php echo $id; ?> .wpzabb-button-has-icon .wpzabb-button-icon {
		<?php if( isset( $settings->icon_size_unit ) && $settings->icon_size_unit != '' ) : ?>
			font-size: <?php echo $settings->icon_size_unit; ?>px;
		<?php endif; ?>
	}
<?php endif ?>

<?php if ( 'custom' == $settings->width && $settings->custom_height != '' && ( $settings->line_height->desktop == '' || ( intval($settings->custom_height) > intval($settings->line_height->desktop) ) ) ) : ?>
html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {
	line-height: <?php echo $settings->custom_height; ?>px;
}
<?php endif; ?>

<?php if ( 'custom' == $settings->width && $settings->custom_height != '' ) : 
	$translateText = intval($settings->custom_height) + ($padding_top_bottom * 2) + 50;
?>
.fl-node-<?php echo $id; ?> .wpzabb-creative-flat-btn.wpzabb-animate_from_top-btn:hover .wpzabb-button-text {
	-webkit-transform: translateY(<?php echo $translateText; ?>px);
	-moz-transform: translateY(<?php echo $translateText; ?>px);
	-ms-transform: translateY(<?php echo $translateText; ?>px);
	-o-transform: translateY(<?php echo $translateText; ?>px);
	transform: translateY(<?php echo $translateText; ?>px);
}

.fl-node-<?php echo $id; ?> .wpzabb-creative-flat-btn.wpzabb-animate_from_bottom-btn:hover .wpzabb-button-text {
	-webkit-transform: translateY(-<?php echo $translateText; ?>px);
	-moz-transform: translateY(-<?php echo $translateText; ?>px);
	-ms-transform: translateY(-<?php echo $translateText; ?>px);
	-o-transform: translateY(-<?php echo $translateText; ?>px);
	transform: translateY(-<?php echo $translateText; ?>px);
}
<?php endif; ?>

<?php if ( ! empty( $settings->text_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a *,
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited,
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited * {
	color: <?php echo $settings->text_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->bg_hover_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:hover {
	<?php if( $settings->style != "transparent" && $settings->style != "gradient"  ){ ?>
		background: <?php echo $settings->bg_hover_color; ?>;
	<?php } ?>
	border: <?php echo $settings->border_size; ?>px solid <?php echo $border_hover_color; ?>;
	
	<?php /*if ( 'transparent' == $settings->style ) : // Transparent ?>
	background-color: rgba(<?php echo implode( ',', FLBuilderColor::hex_to_rgb( $settings->bg_hover_color ) ) ?>, 1 );
	<?php endif; */?>

	<?php if ( 'gradient' == $settings->style ) : // Gradient ?>
	background: -moz-linear-gradient(top,  <?php echo $bg_hover_grad_start; ?> 0%, <?php echo $settings->bg_hover_color; ?> 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $bg_hover_grad_start; ?>), color-stop(100%,<?php echo $settings->bg_hover_color; ?>)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->bg_hover_color; ?> 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->bg_hover_color; ?> 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->bg_hover_color; ?> 100%); /* IE10+ */
	background: linear-gradient(to bottom,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->bg_hover_color; ?> 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $bg_hover_grad_start; ?>', endColorstr='<?php echo $settings->bg_hover_color; ?>',GradientType=0 ); /* IE6-9 */
	<?php endif; ?>
}
<?php endif; ?>

<?php if ( ! empty( $settings->text_hover_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:hover,
.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:hover * {
	color: <?php echo $settings->text_hover_color; ?>;
}
<?php endif; ?>

<?php if ( 'yes' == $settings->text_decoration_on_hover ): ?>
	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:hover .wpzabb-button-text {
		<?php if( 'transparent' == $settings->style && 'none' != $settings->text_decoration ){ ?>
			text-decoration: <?php echo $settings->text_decoration ?>;
		<?php } ?>
	}
<?php else: ?>
	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a .wpzabb-button-text {
		<?php if( 'transparent' == $settings->style && 'none' != $settings->text_decoration ){ ?>
			text-decoration: <?php echo $settings->text_decoration ?>;
		<?php } ?>
	}
<?php endif ?>

<?php 
// Responsive button Alignment
if( $global_settings->responsive_enabled ) : ?>	
@media ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap.wpzabb-button-reponsive-<?php echo $settings->mob_align; ?> {
		text-align: <?php echo $settings->mob_align; ?>;
	}
}
<?php endif; ?>

<?php /* Typography responsive layout starts here*/ ?>

<?php if( $global_settings->responsive_enabled ) { // Global Setting If started 
	if( isset( $settings->font_size_unit_medium ) || isset( $settings->line_height_unit_medium ) || isset( $settings->line_height ) || isset( $settings->font_size ) || ( isset( $settings->font_size['medium'] ) && $settings->font_size['medium'] != "" ) || ( isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != "" || isset( $settings->width ) ) )
	{
		/* Medium Breakpoint media query */	
	?>
		@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
			.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {

				<?php if( isset( $settings->font_size_unit_medium ) && $settings->font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->font_size_unit_medium; ?>px;
					<?php if( $settings->width != 'custom' && $settings->line_height_unit_medium == '' ) : ?>
						line-height: <?php echo $settings->font_size_unit_medium + 2; ?>px;
					<?php endif; ?>
				<?php endif; ?>

				<?php if( isset( $settings->font_size ) && is_array( $settings->font_size ) ) { ?>
					<?php if( isset( $settings->font_size['medium'] ) && $settings->font_size['medium'] == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '' && $settings->line_height_unit == '' && $settings->line_height_unit_medium == '' ) { ?>
					    line-height: <?php echo $settings->line_height['medium']; ?>px;
					<?php } ?>
				<?php } else if( isset( $settings->font_size ) && is_object( $settings->font_size ) ) { ?>
					<?php if( isset( $settings->font_size->medium ) && $settings->font_size->medium == '' && isset( $settings->line_height->medium ) && $settings->line_height->medium != '' && $settings->line_height_unit == '' && $settings->line_height_unit_medium == '' ) { ?>
				    	line-height: <?php echo $settings->line_height->medium; ?>px;
					<?php } ?>
				<?php } ?>

				<?php if( isset( $settings->line_height_unit_medium ) && $settings->line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_medium; ?>em;
				<?php endif; ?>
				
			}

			<?php if ( 'custom' == $settings->width && $settings->custom_height != '' && ( $settings->line_height->medium == '' || ( intval($settings->custom_height) > intval($settings->line_height->medium) ) ) ) : ?>
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {
				line-height: <?php echo $settings->custom_height; ?>px;
			}
			<?php else: ?>
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {

				<?php if( isset( $settings->font_size->medium ) && $settings->font_size->medium == '' && isset( $settings->line_height->medium ) && $settings->line_height->medium != '' && $settings->line_height_unit == '' && $settings->line_height_unit_medium == '' ) { ?>
				    line-height: <?php echo $settings->line_height->medium; ?>px;
				<?php } ?>

				<?php if( isset( $settings->line_height_unit_medium ) && $settings->line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_medium; ?>em;
				<?php endif; ?>
			}
			<?php endif; ?>
		}		
	<?php
	}
	if( isset( $settings->font_size_unit_responsive ) || isset( $settings->line_height_unit_responsive ) || isset( $settings->font_size_unit_medium ) || isset( $settings->line_height_unit_medium ) || isset( $settings->line_height ) || isset( $settings->font_size ) || ( isset( $settings->font_size['small'] ) && $settings->font_size['small'] != "" ) || ( isset( $settings->line_height['small'] ) && $settings->line_height['small'] != "" || isset( $settings->width ) ) )
	{
		/* Small Breakpoint media query */	
	?>
		@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
			.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {

				<?php if( isset( $settings->font_size_unit_responsive ) && $settings->font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->font_size_unit_responsive; ?>px;
					<?php if( $settings->width != 'custom' && $settings->line_height_unit_responsive == '' ) : ?>
						line-height: <?php echo $settings->font_size_unit_responsive + 2; ?>px;
					<?php endif; ?>
				<?php endif; ?>

				<?php if( isset( $settings->font_size ) && is_array( $settings->font_size ) ) { ?>
					<?php if( isset( $settings->font_size['small'] ) && $settings->font_size['small'] == '' && isset( $settings->line_height['small'] ) && $settings->line_height['small'] != '' && $settings->line_height_unit == '' && $settings->line_height_unit_medium == '' && $settings->line_height_unit_responsive == '' ) { ?>
					    line-height: <?php echo $settings->line_height['small']; ?>px;
					<?php } ?>
				<?php } else if( isset( $settings->font_size ) && is_object( $settings->font_size ) ) { ?>
					<?php if( isset( $settings->font_size->small ) && $settings->font_size->small == '' && isset( $settings->line_height->small ) && $settings->line_height->small != '' && $settings->line_height_unit == '' && $settings->line_height_unit_medium == '' && $settings->line_height_unit_responsive == '' ) { ?>
					    line-height: <?php echo $settings->line_height->small; ?>px;
					<?php } ?>
				<?php } ?>

				<?php if( isset( $settings->line_height_unit_responsive ) && $settings->line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			<?php if ( 'custom' == $settings->width && $settings->custom_height != '' && ( $settings->line_height->small == '' || ( intval($settings->custom_height) > intval($settings->line_height->small) ) ) ) : ?>
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {
				line-height: <?php echo $settings->custom_height; ?>px;
			}
			<?php else: ?>
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a,
			html.internet-explorer .fl-node-<?php echo $id; ?> .wpzabb-button-wrap a:visited {

				<?php if( isset( $settings->font_size->small ) && $settings->font_size->small == '' && isset( $settings->line_height->small ) && $settings->line_height->small != '' && $settings->line_height_unit == '' && $settings->line_height_unit_medium == '' && $settings->line_height_unit_responsive == '' ) { ?>
				    line-height: <?php echo $settings->line_height->small; ?>px;
				<?php } ?>
				
				<?php if( isset( $settings->line_height_unit_responsive ) && $settings->line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}
			<?php endif; ?>
		}		
	<?php
	}
}

/* Typography responsive layout Ends here*/ ?>

<?php /* Transparent New Style CSS*/ ?>
<?php
if( !empty( $settings->style ) && $settings->style == "transparent" ) {
?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-none-btn:hover{
		<?php
		if( $settings->transparent_button_options == 'none' ) {
			if( $settings->hover_attribute == 'border' ) {
		?>
			border-color:<?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
		<?php
			} else {
		?>
			background:<?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
		<?php
			}
		} else {
		?>
		background:<?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
		<?php
		}
		?>
	}
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-none-btn:hover .wpzabb-button-icon {
		<?php if ( $settings->text_hover_color != "" && $settings->text_hover_color != "FFFFFF" && $settings->transparent_button_options == "none") { ?>
			color: <?php echo $settings->text_hover_color; ?>
		<?php } else { ?>
			color: <?php echo $settings->text_color; ?>;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap a.wpzabb-creative-transparent-btn.wpzabb-none-btn:hover .wpzabb-button-text {
		<?php if ( $settings->text_hover_color != "" && $settings->text_hover_color != "FFFFFF" && $settings->transparent_button_options == "none") { ?>
			color: <?php echo $settings->text_hover_color; ?>
		<?php } else { ?>
			color: <?php echo $settings->text_color; ?>;
		<?php } ?>
	}
	
	

	
	
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fade-btn:hover{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	}

	/*transparent-fill-top*/
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-top-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    height: 100%;
	}

	/*transparent-fill-bottom*/
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-bottom-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    height: 100%;
	}

	/*transparent-fill-left*/
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-left-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    width: 100%;
	}
	/*transparent-fill-right*/
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-right-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    width: 100%;
	}

	/*transparent-fill-center*/
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-center-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    height: calc( 100% + <?php echo $settings->border_size."px";?> );
	    width: calc( 100% + <?php echo $settings->border_size."px";?> );
	}

	/* transparent-fill-diagonal */
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-diagonal-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    height: 260%;
	}

	/*transparent-fill-horizontal*/
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-transparent-fill-horizontal-btn:hover:after{
		background: <?php echo wpzabb_theme_base_color( $settings->bg_hover_color ); ?>;
	    height: calc( 100% + <?php echo $settings->border_size."px";?> );
	    width: calc( 100% + <?php echo $settings->border_size."px";?> );
	}



	.fl-node-<?php echo $id; ?> a.wpzabb-transparent-fill-diagonal-btn:hover {
		background: none;
	}

	.fl-node-<?php echo $id; ?> a.wpzabb-creative-transparent-btn.wpzabb-<?php echo $settings->transparent_button_options;?>-btn:hover .wpzabb-button-text{
		color: <?php echo wpzabb_theme_button_text_color( $settings->text_hover_color ); ?>;

		position: relative;
    	z-index: 9;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-<?php echo $settings->transparent_button_options;?>-btn:hover .wpzabb-button-icon {
		color: <?php echo wpzabb_theme_button_text_color( $settings->text_hover_color ); ?>;
		position: relative;
    	z-index: 9;
	}
<?php
}
?>

<?php /* 3D New Style CSS*/ ?>
<?php
if( !empty( $settings->style ) && $settings->style == "threed" ) {
?>
	<?php /* 3D Move Down*/ ?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_down-btn{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_color ), 10, 'darken' ); ?>
		box-shadow: 0 6px <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_down-btn:hover{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_hover_color ), 10, 'darken' ); ?>
		top: 2px;
		box-shadow: 0 4px <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}

	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_down-btn:active{
		box-shadow:none!important;
		-webkit-transition:all 50ms linear;
		   -moz-transition:all 50ms linear;
				transition:all 50ms linear;
		top: 6px;
	}


	<?php /* 3D Move Up*/ ?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_up-btn{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_color ), 10, 'darken' ); ?>
		box-shadow: 0 -6px <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}
	
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_up-btn:hover{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_hover_color ), 10, 'darken' ); ?>
		top: -2px;
		box-shadow: 0 -4px <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}

	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_up-btn:active{
		box-shadow:none!important;
		-webkit-transition:all 50ms linear;
		   -moz-transition:all 50ms linear;
				transition:all 50ms linear;
		top: -6px;
	}

	<?php /* 3D Move Left*/ ?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_left-btn{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_color ), 10, 'darken' ); ?>
		box-shadow: -6px 0 <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}
	
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_left-btn:hover{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_hover_color ), 10, 'darken' ); ?>
		left: -2px;
		box-shadow: -4px 0 <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}

	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_left-btn:active {
		box-shadow:none!important;
		-webkit-transition:all 50ms linear;
		   -moz-transition:all 50ms linear;
				transition:all 50ms linear;
		left: -6px;
	}


	<?php /* 3D Move Right*/ ?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_right-btn{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_color ), 10, 'darken' ); ?>
		box-shadow: 6px 0 <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}

	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_right-btn:hover{
		<?php $shadow_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_hover_color ), 10, 'darken' ); ?>
		left: 2px;
		box-shadow: 4px 0 <?php echo wpzabb_theme_base_color( $shadow_color ); ?>;
	}

	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-threed_right-btn:active {
		box-shadow:none!important;
		-webkit-transition:all 50ms linear;
		   -moz-transition:all 50ms linear;
				transition:all 50ms linear;
		left: 6px;
	}

	<?php /* Animate Background Color */ ?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-<?php echo $settings->threed_button_options;?>-btn:hover:after{
		<?php $background_color = "#" . FLBuilderColor::adjust_brightness( wpzabb_parse_color_to_hex( $settings->bg_hover_color ), 10, 'darken' ); ?>
		background: <?php echo $background_color;?>;
	}


	<?php /* Text Color*/?>
	.fl-node-<?php echo $id; ?> a.wpzabb-creative-threed-btn.wpzabb-<?php echo $settings->threed_button_options;?>-btn:hover .wpzabb-button-text{
		color: <?php echo wpzabb_theme_base_color( $settings->text_hover_color ); ?>;
	}

	<?php /* 3D Padding for Shadow */ ?>
	.fl-node-<?php echo $id; ?> .wpzabb-button-wrap {
		<?php if( $settings->threed_button_options == 'threed_down' ) : ?>
			padding-bottom: 6px;
		<?php elseif( $settings->threed_button_options == 'threed_up' ) : ?>
			padding-top: 6px;
		<?php elseif( $settings->threed_button_options == 'threed_left' ) : ?>
			padding-left: 6px;
		<?php elseif( $settings->threed_button_options == 'threed_right' ) : ?>
			padding-right: 6px;
		<?php endif; ?>

	}
<?php
}
?>