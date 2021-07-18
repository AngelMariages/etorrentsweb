<div class="wpzabb-slideshow">
	<?php
	$slides = array();

	if ( 'posts' == $settings->slides_source ) {
		wp_reset_postdata();

		$settings->posts_per_page = property_exists( $settings, 'wpzabb_posts_amount' ) ? $settings->wpzabb_posts_amount : 10;
		$settings->no_found_rows = true;

		$query = FLBuilderLoop::query( $settings );

		if ( $query->have_posts() ) {
			$h = 0;

			while ( $query->have_posts() ) {
				$query->the_post();

				$slide = new stdClass();
				$slide->title = the_title( '', '', false );
				$slide->link = get_permalink();
				$slide->link_target = '_self';
				$slide->link_nofollow = 'no';
				$slide->content = property_exists( $settings, 'wpzabb_show_excerpt' ) && 'yes' == $settings->wpzabb_show_excerpt ? apply_filters( 'the_excerpt', get_the_excerpt() ) : '';
				$slide->button = property_exists( $settings, 'wpzabb_read_more' ) && 'yes' == $settings->wpzabb_read_more;
				$slide->button_label = property_exists( $settings, 'wpzabb_read_more_label' ) ? $settings->wpzabb_read_more_label : __( 'Read More', 'wpzabb' );
				$slide->button_url = get_permalink();
				$slide->button_url_target = '_self';
				$slide->button_url_nofollow = 'no';
				$slide->image_source = 'library';
				$slide->image = get_post_thumbnail_id();
				$slide->image_src = false !== ( $imgsrc = get_the_post_thumbnail_url( get_the_ID(), $settings->slideshow_image_size ) ) ? $imgsrc : '';
				$slide->image_url = '';

				$slides[ $h ] = $slide;

				$h++;
			}
		}

		wp_reset_postdata();
	} else {
		$slides = $settings->slides;
	}

	$slides_amount = count( $slides );

	if ( 0 < $slides_amount ) :
		if ( 'yes' == $settings->slideshow_shuffle ) {
			shuffle( $slides );
		}

		?><div class="wpzabb-slideshow-slides slides">
			<?php for ( $i = 0; $i < $slides_amount; $i++ ) :
				if ( ! is_object( $slides[ $i ] ) ) {
					continue;
				}

				$slide   = $slides[ $i ];
				$classes = $module->get_classes( $slide );
				$img     = trim( $module->get_src( $slide ) );
				$thumb = !empty( $img ) ? ' data-thumb="' . esc_url( $img ) . '"' : ''; ?>

				<div class="wpzabb-slideshow-slide wpzabb-slideshow-slide-<?php echo $i + 1; ?>"<?php echo $thumb; ?>>
					<div class="wpzabb-slideshow-slide-outer-wrap">
						<?php if ( !empty( $img ) ) : ?>
							<div class="wpzabb-slideshow-slide-image" itemscope itemtype="http://schema.org/ImageObject" style="background-image:url('<?php echo esc_url( $img ); ?>')"></div>
						<?php endif; ?>

						<div class="wpzabb-slideshow-slide-details">
							<div class="wpzabb-slideshow-slide-details-wrap">
								<h4 class="wpzabb-slideshow-slide-title">
									<?php if ( !empty( $slide->link ) ) : ?>
										<a href="<?php echo esc_url( $slide->link ); ?>" title="<?php echo esc_attr( $slide->title ); ?>" target="<?php echo $slide->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $slide->link_target, $slide->link_nofollow, 1 ); ?>>
									<?php endif; ?>

									<?php echo esc_html( $slide->title ); ?>

									<?php if ( !empty( $slide->link ) ) : ?>
										</a>
									<?php endif; ?>
								</h4>

								<?php if ( ! empty( $slide->content ) ) : ?>
									<div class="wpzabb-slideshow-slide-content">
										<?php echo wp_kses_post( $slide->content ); ?>
									</div>
								<?php endif; ?>

								<?php if ( 'yes' == $slide->button ) : ?>
									<div class="wpzabb-slideshow-slide-button">
										<a href="<?php echo esc_url( $slide->button_url ); ?>" title="<?php echo esc_attr( $slide->button_label ); ?>" target="<?php echo $slide->button_url_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $slide->button_url_target, $slide->button_url_nofollow, 1 ); ?>><?php echo $slide->button_label; ?></a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endfor; ?>
		</div>

		<?php if ( 'thumbs' == $settings->slideshow_navigation ) : ?>
			<div class="wpzabb-slideshow-thumbs-nav">
				<?php for ( $j = 0; $j < $slides_amount; $j++ ) :
					if ( ! is_object( $slides[ $j ] ) ) {
						continue;
					}

					$slide = $slides[ $j ];
					$img   = $module->get_src( $slide ); ?>

					<div class="wpzabb-slideshow-thumbs-nav-item wpzabb-slideshow-thumbs-nav-item-<?php echo $j + 1; ?>">
						<?php if ( !empty( $img ) ) : ?>
							<img src="<?php echo esc_url( $img ); ?>" />
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>