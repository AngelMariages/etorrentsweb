<div class="wpzabb-post-column">
<div <?php $module->render_post_class(); ?> itemscope itemtype="<?php WPZABBPostsModule::schema_itemtype(); ?>">

	<?php WPZABBPostsModule::schema_meta(); ?>
	<?php $module->render_featured_image( 'above-title' ); ?>

	<div class="wpzabb-post-grid-text">

		<?php $module->render_meta( 'above-title' ); ?>

		<<?php echo $settings->title_tag ?> class="wpzabb-post-grid-title" itemprop="headline">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		</<?php echo $settings->title_tag ?>>

		<?php $module->render_meta( 'above' ); ?>

	<?php if ( $module->has_featured_image( 'above' ) ) : ?>
	</div>
	<?php endif; ?>

	<?php $module->render_featured_image( 'above' ); ?>

	<?php if ( $module->has_featured_image( 'above' ) ) : ?>
	<div class="wpzabb-post-grid-text">
	<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_grid_before_content', $settings, $module ); ?>

		<?php if ( $settings->show_content || $settings->show_more_link ) : ?>
		<div class="wpzabb-post-grid-content">
			<?php if ( $settings->show_content ) : ?>
			<?php $module->render_excerpt(); ?>
			<?php endif; ?>
			<?php if ( $settings->show_more_link ) : ?>
			<a class="wpzabb-post-grid-more" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $settings->more_link_text; ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_grid_after_content', $settings, $module ); ?>

	</div>
</div>
</div>
