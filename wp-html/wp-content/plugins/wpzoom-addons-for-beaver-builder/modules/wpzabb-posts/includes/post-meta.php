<?php do_action( 'wpzabb_builder_post_'. $layout .'_before_meta', $settings, $module ); ?>

<?php if ( $settings->show_author || $settings->show_date || $settings->show_comments ) : ?>
<div class="wpzabb-post-<?php echo $layout ?>-meta">
	<?php if ( $settings->show_author ) : ?>
		<span class="wpzabb-post-<?php echo $layout ?>-author">
			<?php

			printf(
				_x( 'By %s', '%s stands for author name.', 'wpzabb' ),
				'<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '"><span>' . get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) . '</span></a>'
			);

			?>
		</span>
	<?php endif; ?>
	<?php if ( $settings->show_date ) : ?>
		<?php if ( $settings->show_author ) : ?>
			<span class="fl-sep"><?php echo $settings->info_separator; ?></span>
		<?php endif; ?>
		<span class="wpzabb-post-<?php echo $layout ?>-date">
			<?php FLBuilderLoop::post_date( $settings->date_format ); ?>
		</span>
	<?php endif; ?>
	<?php if ( $settings->show_comments ) : ?>
		<?php if ( $settings->show_author || $settings->show_date ) : ?>
			<span class="fl-sep"><?php echo $settings->info_separator; ?></span>
		<?php endif; ?>
		<span class="wpzabb-post-<?php echo $layout ?>-comments">
			<?php comments_popup_link( __( '0 Comments', 'wpzabb' ), __( '1 Comment', 'wpzabb' ), __( '% Comments', 'wpzabb' ) ); ?>
		</span>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php if ( $settings->show_terms && $module->get_post_terms() ) : ?>
<div class="wpzabb-post-<?php echo $layout ?>-meta">
	<div class="wpzabb-post-<?php echo $layout ?>-terms">
		<span class="fl-terms-label"><?php echo $settings->terms_list_label; ?></span>
		<?php echo $module->get_post_terms(); ?>
	</div>
</div>
<?php endif; ?>

<?php do_action( 'wpzabb_builder_post_'. $layout .'_after_meta', $settings, $module ); ?>
