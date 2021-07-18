<?php
	// Name Heading
	$settings->name_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'color' );
	$settings->name_margin_top = ( trim($settings->name_margin_top) !== '' ) ? $settings->name_margin_top : '20';
	$settings->name_margin_bottom = ( trim($settings->name_margin_bottom) !== '' ) ? $settings->name_margin_bottom : '10';

	// Position
	$settings->position_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'position_color' );
	$settings->position_margin_top = ( trim($settings->position_margin_top) !== '' ) ? $settings->position_margin_top : '0';
	$settings->position_margin_bottom = ( trim($settings->position_margin_bottom) !== '' ) ? $settings->position_margin_bottom : '10';

	// Info
	$settings->info_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'info_color' );
	$settings->info_margin_top = ( trim($settings->info_margin_top) !== '' ) ? $settings->info_margin_top : '0';
	$settings->info_margin_bottom = ( trim($settings->info_margin_bottom) !== '' ) ? $settings->info_margin_bottom : '0';
?>


<?php /* Content Align */ ?>

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.content-align-<?php echo $settings->content_align; ?> .wpzabb-member {
	text-align: <?php echo $settings->content_align; ?>
}

<?php /* Grid Layout */ ?>

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member {
	margin-bottom: 30px;
	width: 100%;
	display: inline-block;
	vertical-align: top;
}
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-2-cols .wpzabb-member {
	width: 48.68%;
	margin-right: 2.3%;
}
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-3-cols .wpzabb-member {
	width: 31.7%;
    margin-right: 2%;
}
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-4-cols .wpzabb-member {
	width: 23%;
    margin-right: 2.3%;
}

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-2-cols .wpzabb-member:nth-of-type(2n+2) {
    margin-right: 0;
}
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-3-cols .wpzabb-member:nth-of-type(3n+3) {
    margin-right: 0;
}
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-4-cols .wpzabb-member:nth-of-type(4n+4) {
    margin-right: 0;
}

<?php /* Typography formating */ ?>

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name {
	margin-top: <?php echo $settings->name_margin_top; ?>px;
	margin-bottom: <?php echo $settings->name_margin_bottom; ?>px;
}

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name,
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name .wpzabb-member-name-text,
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name * {
	<?php if(!empty($settings->name_color)) : ?>
		color: <?php echo $settings->name_color; ?>;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-position {
	display: block;
	margin-top: <?php echo $settings->position_margin_top; ?>px;
	margin-bottom: <?php echo $settings->position_margin_bottom; ?>px;
	<?php if(!empty($settings->position_color)) : ?>
		color: <?php echo $settings->position_color; ?>;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-info * {
	margin-top: <?php echo $settings->info_margin_top; ?>px;
	margin-bottom: <?php echo $settings->info_margin_bottom; ?>px;
	color: <?php echo wpzabb_theme_text_color( $settings->info_color ); ?>;
}

<?php /* Name font settings */ ?>
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name,
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name * {

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
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-position {

	<?php if( !empty($settings->position_font_family) && $settings->position_font_family['family'] != 'Default' ) : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->position_font_family ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->position_font_size_unit ) && $settings->position_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->position_font_size_unit; ?>px;
	<?php endif; ?>

	<?php if( isset( $settings->position_line_height_unit ) && $settings->position_line_height_unit != '' ) : ?>
		line-height: <?php echo $settings->position_line_height_unit; ?>em;
	<?php endif; ?>

	<?php if ( ! empty( $settings->position_text_transform ) ) : ?>
		text-transform: <?php echo $settings->position_text_transform; ?>;
	<?php endif; ?>

	<?php if ( 'custom' == $settings->position_letter_spacing ) : ?>
		letter-spacing: <?php echo $settings->position_custom_letter_spacing; ?>px;
	<?php endif; ?>
}

<?php /* Info font settings */ ?>
.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-info {

	<?php if( !empty($settings->info_font_family) && $settings->info_font_family != 'Default' ) : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->info_font_family ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->info_font_size_unit ) && $settings->info_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->info_font_size_unit; ?>px;
	<?php endif; ?>

	<?php if( isset( $settings->info_line_height_unit ) && $settings->info_line_height_unit != '' ) : ?>
		line-height: <?php echo $settings->info_line_height_unit; ?>em;
	<?php endif; ?>

	<?php if ( ! empty( $settings->info_text_transform ) ) : ?>
		text-transform: <?php echo $settings->info_text_transform; ?>;
	<?php endif; ?>

	<?php if ( 'custom' == $settings->info_letter_spacing ) : ?>
		letter-spacing: <?php echo $settings->info_custom_letter_spacing; ?>px;
	<?php endif; ?>
}


<?php /* Global Setting If started */ ?>
<?php if($global_settings->responsive_enabled) { ?>

        <?php /* Medium Breakpoint media query */  ?>
        @media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {

        	<?php /* For Medium Device */ ?>
            .fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name,
            .fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name * {

				<?php if( isset( $settings->new_font_size_unit_medium ) && $settings->new_font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->new_font_size_unit_medium; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->line_height_unit_medium ) && $settings->line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_medium; ?>em;
				<?php endif; ?>

			}
			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-position {

				<?php if( isset( $settings->position_font_size_unit_medium ) && $settings->position_font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->position_font_size_unit_medium; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->position_line_height_unit_medium ) && $settings->position_line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->position_line_height_unit_medium; ?>em;
				<?php endif; ?>
			}
			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-info {

				<?php if( isset( $settings->info_font_size_unit_medium ) && $settings->info_font_size_unit_medium != '' ) : ?>
					font-size: <?php echo $settings->info_font_size_unit_medium; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->info_line_height_unit_medium ) && $settings->info_line_height_unit_medium != '' ) : ?>
					line-height: <?php echo $settings->info_line_height_unit_medium; ?>em;
				<?php endif; ?>
			}
        }

        <?php /* Small Breakpoint media query */ ?>
        @media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {

        	<?php /* For Small Device */ ?>
            .fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name,
            .fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-name * {

				<?php if( isset( $settings->new_font_size_unit_responsive ) && $settings->new_font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->new_font_size_unit_responsive; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->line_height_unit_responsive ) && $settings->line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-position {

				<?php if( isset( $settings->position_font_size_unit_responsive ) && $settings->position_font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->position_font_size_unit_responsive; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->position_line_height_unit_responsive ) && $settings->position_line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->position_line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap .wpzabb-member-info {

				<?php if( isset( $settings->info_font_size_unit_responsive ) && $settings->info_font_size_unit_responsive != '' ) : ?>
					font-size: <?php echo $settings->info_font_size_unit_responsive; ?>px;
				<?php endif; ?>

				<?php if( isset( $settings->info_line_height_unit_responsive ) && $settings->info_line_height_unit_responsive != '' ) : ?>
					line-height: <?php echo $settings->info_line_height_unit_responsive; ?>em;
				<?php endif; ?>
			}

			<?php /* Grid Layout */ ?>
			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-2-cols .wpzabb-member,
			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-3-cols .wpzabb-member,
			.fl-node-<?php echo $id; ?> .wpzabb-team-members-wrap.layout-4-cols .wpzabb-member {
				width: 100%;
				margin-right: 0;
			}
        }
    <?php
} ?>


