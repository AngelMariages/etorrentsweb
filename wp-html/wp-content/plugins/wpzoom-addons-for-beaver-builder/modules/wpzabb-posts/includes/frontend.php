<?php

// Get the query data.
$query = FLBuilderLoop::query( $settings );

// Render the posts.
if ( $query->have_posts() ) :

	do_action( 'wpzabb_builder_posts_module_before_posts', $settings, $query );
?>
<div class="wpzabb-post-<?php echo $module->get_layout_slug(); ?>" itemscope="itemscope" itemtype="https://schema.org/Blog">
	<?php

	while ( $query->have_posts() ) {

		$query->the_post();

		ob_start();

		include apply_filters( 'wpzabb_builder_posts_module_layout_path', $module->dir . 'includes/post-' . $module->get_layout_slug() . '.php', $settings->layout, $settings );

		// Do shortcodes here so they are parsed in context of the current post.
		echo do_shortcode( ob_get_clean() );
	}

	?>
</div>
<div class="fl-clear"></div>
<?php endif; ?>
<?php

do_action( 'wpzabb_builder_posts_module_after_posts', $settings, $query );

// Render the pagination.
if ( 'none' != $settings->pagination && $query->have_posts() && $query->max_num_pages > 1 ) : ?>
	<div class="fl-builder-pagination"<?php if ( in_array( $settings->pagination, array( 'scroll', 'load_more' ) ) ) { echo ' style="display:none;"';} ?>>
		<?php FLBuilderLoop::pagination( $query ); ?>
	</div>
	<?php if ( 'load_more' == $settings->pagination && $query->max_num_pages > 1 ) : ?>
		<?php $module->render_more_button(); ?>
	<?php endif; ?>
<?php endif; ?>
<?php

do_action( 'wpzabb_builder_posts_module_after_pagination', $settings, $query );

// Render the empty message.
if ( ! $query->have_posts() ) : ?>
	<div class="wpzabb-post-grid-empty">
		<p><?php echo $settings->no_results_message; ?></p>
		<?php if ( $settings->show_search ) : ?>
		<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
<?php

endif;

wp_reset_postdata();

?>
