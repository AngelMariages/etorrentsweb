<div <?php $module->render_post_class(); ?> itemscope itemtype="<?php WPZABBPostsModule::schema_itemtype(); ?>">

	<?php WPZABBPostsModule::schema_meta(); ?>
	<?php $module->render_featured_image( array( 'above-title', 'beside', 'beside-right' ) ); ?>

	<?php if ( in_array( $settings->image_position, array( 'above-title', 'beside', 'beside-right' ) ) || ! $module->has_featured_image( array( 'beside-content', 'beside-content-right' ) ) ) : ?>
	<div class="wpzabb-post-list-text">
	<?php endif; ?>

		<div class="wpzabb-post-list-header">

			<?php $module->render_meta( 'above-title' ); ?>

			<<?php echo $settings->title_tag ?> class="wpzabb-post-list-title" itemprop="headline">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			</<?php echo $settings->title_tag ?>>

			<?php $module->render_meta( 'above' ); ?>

		</div>

	<?php if ( $module->has_featured_image( 'above' ) ) : ?>
	</div>
	<?php endif; ?>

	<?php $module->render_featured_image( array( 'above', 'beside-content', 'beside-content-right' ) ); ?>

	<?php if ( $module->has_featured_image( array( 'above', 'beside-content', 'beside-content-right' ) ) ) : ?>
	<div class="wpzabb-post-list-text">
	<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_feed_before_content', $settings, $module ); ?>

		<?php if ( $settings->show_content || $settings->show_more_link ) : ?>
		<div class="wpzabb-post-list-content" itemprop="text">
			<?php

			if ( $settings->show_content ) {

				if ( 'full' == $settings->content_type ) {
					$module->render_content();
				} else {
					$module->render_excerpt();
				}
			}

			?>
			<?php if ( $settings->show_more_link ) : ?>
			<a class="wpzabb-post-list-more" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $settings->more_link_text; ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_feed_after_content', $settings, $module ); ?>

	</div>

	<div class="fl-clear"></div>
</div>
