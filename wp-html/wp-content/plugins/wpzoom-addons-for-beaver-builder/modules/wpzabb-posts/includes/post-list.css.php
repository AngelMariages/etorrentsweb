<?php

$settings->title_margin_top = ( trim($settings->title_margin_top) !== '' ) ? $settings->title_margin_top : '0';
$settings->title_margin_bottom = ( trim($settings->title_margin_bottom) !== '' ) ? $settings->title_margin_bottom : '15';
$settings->image_margin_top = ( trim($settings->image_margin_top) !== '' ) ? $settings->image_margin_top : '0';
$settings->image_margin_bottom = ( trim($settings->image_margin_bottom) !== '' ) ? $settings->image_margin_bottom : '0';
$settings->info_margin_top = ( trim($settings->info_margin_top) !== '' ) ? $settings->info_margin_top : '0';
$settings->info_margin_bottom = ( trim($settings->info_margin_bottom) !== '' ) ? $settings->info_margin_bottom : '15';

$settings->bg_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'bg_color', true );
$settings->border_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'border_color' );
$settings->title_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'title_color' );
$settings->title_hover_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'title_hover_color' );
$settings->info_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'info_color' );
$settings->info_link_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'info_link_color' );
$settings->info_link_hover_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'info_link_hover_color' );
$settings->content_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'content_color' );
$settings->link_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'link_color' );
$settings->link_hover_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'link_hover_color' );

?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-post {

	<?php if ( ! empty( $settings->bg_color ) ) : ?>
	background-color: <?php echo $settings->bg_color; ?>;
	<?php endif; ?>

	<?php if ( 'default' != $settings->border_type && 'none' != $settings->border_type && ! empty( $settings->border_color ) ) : ?>
	border: <?php echo $settings->border_size; ?>px <?php echo $settings->border_type; ?> <?php echo $settings->border_color; ?>;
	<?php endif; ?>

	<?php if ( 'none' == $settings->border_type ) : ?>
	border: none;
	<?php endif; ?>

	<?php if ( 'default' != $settings->post_align ) : ?>
	text-align: <?php echo $settings->post_align; ?>;
	<?php endif; ?>
}

<?php if ( ! empty( $settings->list_post_padding ) ) : ?>
	<?php if ( 'above' == $settings->image_position || 'above-title' == $settings->image_position ) : ?>
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-text {
		padding: <?php echo $settings->list_post_padding; ?>px;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-image,
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-above .wpzabb-post-list-header {
		margin-bottom: 0;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-post {
		padding-bottom: 0;
	}
	<?php else : ?>
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-post {
		padding: <?php echo $settings->list_post_padding; ?>px;
	}
	<?php endif; ?>
<?php endif; ?>

<?php if ( $settings->show_image ) : ?>
	<?php if ( ! empty( $settings->image_spacing ) ) : ?>
		<?php if ( 'above' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image {
			padding: 0 <?php echo $settings->image_spacing; ?>px;
		}
		<?php elseif ( 'above-title' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image {
			padding: <?php echo $settings->image_spacing; ?>px <?php echo $settings->image_spacing; ?>px 0 <?php echo $settings->image_spacing; ?>px;
		}
		<?php elseif ( 'beside' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside .wpzabb-post-list-text {
			padding-left: <?php echo $settings->image_spacing; ?>px;
		}
		<?php elseif ( 'beside-content' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-content .wpzabb-post-list-text {
			padding-left: <?php echo $settings->image_spacing; ?>px;
		}
		<?php elseif ( 'beside-right' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-right .wpzabb-post-list-text {
			padding-right: <?php echo $settings->image_spacing; ?>px;
		}
		<?php elseif ( 'beside-content-right' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-content-right .wpzabb-post-list-text {
			padding-right: <?php echo $settings->image_spacing; ?>px;
		}
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( ! empty( $settings->image_width ) && in_array( $settings->image_position, array( 'beside', 'beside-right', 'beside-content', 'beside-content-right' ) ) ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image {
			width: <?php echo $settings->image_width; ?>%;
		}
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-text {
			overflow: hidden;
		}
		<?php if ( 'beside' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside .wpzabb-post-list-image {
			float: left;
		}
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside .wpzabb-post-list-text {
			margin-left: <?php echo empty( $settings->image_spacing ) ? $settings->image_width + 4 : $settings->image_width; ?>%;
		}
		<?php elseif ( 'beside-content' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-content .wpzabb-post-list-image {
			float: left;
		}
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-content .wpzabb-post-list-text {
			margin-left: <?php echo empty( $settings->image_spacing ) ? $settings->image_width + 4 : $settings->image_width; ?>%;
		}
		<?php elseif ( 'beside-right' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-right .wpzabb-post-list-image {
			float: right;
		}
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-right .wpzabb-post-list-text {
			margin-right: <?php echo empty( $settings->image_spacing ) ? $settings->image_width + 4 : $settings->image_width; ?>%;
		}
		<?php elseif ( 'beside-content-right' == $settings->image_position ) : ?>
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-content-right .wpzabb-post-list-image {
			float: right;
		}
		.fl-node-<?php echo $id; ?> .wpzabb-post-list-image-beside-content-right .wpzabb-post-list-text {
			margin-right: <?php echo empty( $settings->image_spacing ) ? $settings->image_width + 4 : $settings->image_width; ?>%;
		}
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>

<?php if ( $settings->show_image && ! empty( $settings->image_margin_top ) ): ?>
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-image {
		margin-top: <?php echo $settings->image_margin_top; ?>px;
	}
<?php endif ?>

<?php if ( $settings->show_image && ! empty( $settings->image_margin_bottom ) ): ?>
	.fl-node-<?php echo $id; ?> .wpzabb-post-list-image {
		margin-bottom: <?php echo $settings->image_margin_bottom; ?>px;
	}
<?php endif ?>

.fl-node-<?php echo $id; ?> .wpzabb-post-list-title {
	<?php if( !empty($settings->title_font) && $settings->title_font['family'] != 'Default' ) : ?>
		<?php WPZABB_Helper::wpzabb_font_css( $settings->title_font ); ?>
	<?php endif; ?>

	<?php if( isset( $settings->title_font_size ) && $settings->title_font_size != '' ) : ?>
		font-size: <?php echo $settings->title_font_size; ?>px;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .wpzabb-post-list-title {
	margin-top: <?php echo $settings->title_margin_top; ?>px;
	margin-bottom: <?php echo $settings->title_margin_bottom; ?>px;
}

<?php if ( ! empty( $settings->title_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-title a {
	color: <?php echo $settings->title_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->title_hover_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-title a:hover {
	color: <?php echo $settings->title_hover_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->title_font_size ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-title a {
	font-size: <?php echo $settings->title_font_size; ?>px;
}
<?php endif; ?>

<?php if ( ! empty( $settings->info_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta,
.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta a {
	color: <?php echo $settings->info_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->info_link_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta a {
	color: <?php echo $settings->info_link_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->info_link_hover_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta a:hover {
	color: <?php echo $settings->info_link_hover_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->info_font_size ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta,
.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta a {
	font-size: <?php echo $settings->info_font_size; ?>px;
}
<?php endif; ?>

.fl-node-<?php echo $id; ?> .wpzabb-post-list-meta {
	margin-top: <?php echo $settings->info_margin_top; ?>px;
	margin-bottom: <?php echo $settings->info_margin_bottom; ?>px;
}

<?php if ( ! empty( $settings->content_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-content,
.fl-node-<?php echo $id; ?> .wpzabb-post-list-content p {
	color: <?php echo $settings->content_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->content_font_size ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-content,
.fl-node-<?php echo $id; ?> .wpzabb-post-list-content p {
	font-size: <?php echo $settings->content_font_size; ?>px;
}
<?php endif; ?>

<?php if ( ! empty( $settings->link_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-content a {
	color: <?php echo $settings->link_color; ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->link_hover_color ) ) : ?>
.fl-node-<?php echo $id; ?> .wpzabb-post-list-content a:hover {
	color: <?php echo $settings->link_hover_color; ?>;
}
<?php endif; ?>
