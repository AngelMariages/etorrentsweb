<?php

$settings->dot_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'dot_color' );
$settings->arrow_color = WPZABB_Helper::wpzabb_colorpicker( $settings, 'arrow_color' );

?>

.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .bx-pager.bx-default-pager a,
.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .bx-pager.bx-default-pager a.active {
	background: <?php echo $settings->dot_color; ?>;
	opacity: 1;
}
.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .bx-pager.bx-default-pager a {
	opacity: 0.2;
}
.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .fa:hover,
.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .fa {
	color: <?php echo $settings->arrow_color; ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap.content-align-<?php echo $settings->content_align; ?> .wpzabb-client {
	text-align: <?php echo $settings->content_align; ?>
}

.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .wpzabb-clients {
	margin: 0;
}

.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .wpzabb-client {
	float: none !important;
	max-width: 258px;
}

.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .wpzabb-client a {
	display: block;
	height: 100%;
	width: 100%;
}

.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap .wpzabb-client a::after {
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
}

.wpzabb-client-image {
	display: flex;
	align-items: center;
	justify-content: center;
	height: 100%;
	width: 100%;
}

<?php /* Global Setting If started */ ?>
<?php if($global_settings->responsive_enabled) { ?>
        <?php /* Small Breakpoint media query */ ?>
        @media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
        	<?php /* For Small Device */ ?>
			<?php /* Grid Layout */ ?>
			.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap.layout-2-cols .wpzabb-client,
			.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap.layout-3-cols .wpzabb-client,
			.fl-node-<?php echo $id; ?> .wpzabb-clients-wrap.layout-4-cols .wpzabb-client,
            .fl-node-<?php echo $id; ?> .wpzabb-clients-wrap.layout-5-cols .wpzabb-client {
				width: 100%;
				margin-right: 0;
			}
        }
    <?php
} ?>


