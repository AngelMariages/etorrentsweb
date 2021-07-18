<?php

$testimonials_class = 'wpzabb-testimonials-wrap wpzabb-testimonials-align-' . $settings->content_align;
?>
<div class="<?php echo $testimonials_class; ?>">

	<ul class="wpzabb-testimonials">
		<?php

		if ( 'random' === $settings->order ) {
			shuffle( $settings->testimonials );
		}

		for ( $i = 0; $i < count( $settings->testimonials ); $i++ ) :

			if ( ! is_object( $settings->testimonials[ $i ] ) ) {
				continue;
			}

			$testimonial = $settings->testimonials[ $i ];

			$classes  = $module->get_classes( $testimonial );
			$src      = $module->get_src( $testimonial );
			$alt      = $module->get_alt( $testimonial );

		?>
		<li id="wpzabb-testimonial-<?php echo $i ?>">
			<blockquote class="wpzabb-testimonial">
				<?php echo $testimonial->testimonial; ?>
			</blockquote>
			<div class="wpzabb-testimonial-footer">
				<div class="wpzabb-testimonial-author">
					<?php if( !empty( $testimonial->author_link ) ) : ?>
						<a href="<?php echo $testimonial->author_link; ?>" title="<?php echo $testimonial->author_name; ?>" target="<?php echo $testimonial->author_link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $testimonial->author_link_target, 0, 1 ); ?>>
					<?php endif; ?>
					<?php if ( !empty( $src ) ): ?>
						<div class="wpzabb-testimonial-author-avatar" itemscope itemtype="http://schema.org/ImageObject">
							<img class="<?php echo $classes; ?>" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" itemprop="image"/>
						</div>
					<?php endif ?>
					<?php if( !empty( $testimonial->author_link ) ) : ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="wpzabb-testimonial-author-info">
					<?php if( !empty( $testimonial->author_link ) ) : ?>
						<a href="<?php echo $testimonial->author_link; ?>" title="<?php echo $testimonial->author_name; ?>" target="<?php echo $testimonial->author_link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $testimonial->author_link_target, 0, 1 ); ?>>
					<?php endif; ?>
					<h4 class="author-name"><?php echo $testimonial->author_name; ?></h4>
					<?php if ( !empty( $testimonial->author_company ) ): ?>
						<?php if ( !empty( $testimonial->author_company_link ) ): ?>
							<a href="<?php echo $testimonial->author_company_link ?>" title="<?php echo $testimonial->author_company ?>" target="_blank">
						<?php endif ?>
						<span class="author-company"><?php echo $testimonial->author_company; ?></span>
						<?php if ( !empty( $testimonial->author_company_link ) ): ?>
							</a>
						<?php endif ?>
					<?php endif ?>
					<span class="author-position"><?php echo $testimonial->author_position; ?></span>
					<?php if( !empty( $testimonial->author_link ) ) : ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</li>
		<?php endfor; ?>
	</ul>

	<div class="fl-slider-next"></div>
	<div class="fl-slider-prev"></div>

</div>
