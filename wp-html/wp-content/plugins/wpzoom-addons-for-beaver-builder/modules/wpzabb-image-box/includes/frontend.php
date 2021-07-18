<?php
	$image_box_class = 'wpzabb-image-box-wrap';
?>
<div class="<?php echo $image_box_class; ?>">

	<div class="wpzabb-images">
		<?php
			$classes  = $module->get_classes();
			$src      = $module->get_src();
			$alt      = $module->get_alt();
			$bg_image = sprintf( 'background-image: url(%s);', esc_url($src) );
		?>
		<figure class="wpzabb-image" style="<?php echo $bg_image; ?>">
			<?php if( !empty( $settings->link ) ) : ?>
				<a href="<?php echo $settings->link; ?>" class="wpzabb-image-overlay-link" title="<?php echo $settings->heading; ?>" target="<?php echo $settings->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $settings->link_target, 0, 1 ); ?>></a>
			<?php endif; ?>
			<div class="wpzabb-image-image hidden" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo $classes; ?>" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" itemprop="image"/>
			</div>
			<figcaption class="wpzabb-image-caption">
				<<?php echo $settings->tag; ?> class="wpzabb-image-heading"><?php echo $settings->heading ?></<?php echo $settings->tag; ?>>
				<?php if ( !empty( $settings->subheading ) ): ?>
					<span class="wpzabb-image-subheading"><?php echo $settings->subheading ?></span>
				<?php endif ?>
				<?php if ( !empty( $settings->description ) ): ?>
					<div class="wpzabb-image-description"><?php echo $settings->description ?></div>
				<?php endif ?>
				<?php $module->render_button(); ?>
			</figcaption>
		</figure>
	</div>

</div>
