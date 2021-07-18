.fl-node-<?php echo $id; ?> .wpzabb-post-grid-post {
	margin-bottom: <?php echo $settings->post_spacing; ?>px;
}
.fl-node-<?php echo $id; ?> .wpzabb-post-grid {
	margin-left: -<?php echo $settings->post_spacing / 2; ?>px;
	margin-right: -<?php echo $settings->post_spacing / 2; ?>px;
}
.fl-node-<?php echo $id; ?> .wpzabb-post-column {
	padding-left: <?php echo $settings->post_spacing / 2; ?>px;
	padding-right: <?php echo $settings->post_spacing / 2; ?>px;
	width: <?php echo 100 / $settings->post_columns; ?>%;
}
.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(<?php echo $settings->post_columns; ?>n + 1) {
	clear: both;
}
@media screen and (max-width: <?php echo $global_settings->medium_breakpoint; ?>px) {
	.fl-node-<?php echo $id; ?> .wpzabb-post-column {
		width: <?php echo 100 / $settings->post_columns_medium; ?>%;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(<?php echo $settings->post_columns; ?>n + 1) {
		clear: none;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(<?php echo $settings->post_columns_medium; ?>n + 1) {
		clear: both;
	}
}
@media screen and (max-width: <?php echo $global_settings->responsive_breakpoint; ?>px) {
	.fl-node-<?php echo $id; ?> .wpzabb-post-column {
		width: <?php echo 100 / $settings->post_columns_responsive; ?>%;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(<?php echo $settings->post_columns_medium; ?>n + 1) {
		clear: none;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(<?php echo $settings->post_columns_responsive; ?>n + 1) {
		clear: both;
	}
}
@media screen and (max-width: <?php echo $global_settings->xsmall_breakpoint; ?>px) {
	.fl-node-<?php echo $id; ?> .wpzabb-post-column {
		width: 100%;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(<?php echo $settings->post_columns_responsive; ?>n + 1) {
		clear: none;
	}
	.fl-node-<?php echo $id; ?> .wpzabb-post-column:nth-child(n + 1) {
		clear: both;
	}
}
