<div class="wpzabb-food-menu-wrap<?php echo $settings->menu_button == 'yes' ? ' with-button' : ''; ?>">

	<h3 class="wpzabb-food-menu-title"><?php echo $settings->menu_title; ?></h3>

	<ul class="wpzabb-food-menu-items align-<?php echo $settings->item_image_align; ?>">
		<?php

		for ( $i = 0; $i < count( $settings->menu_items ); $i++ ) :

			if ( ! is_object( $settings->menu_items[ $i ] ) ) {
				continue;
			}

			$menu_item = $settings->menu_items[ $i ];
			$classes   = $module->get_classes( $menu_item );
			$src       = trim( $module->get_src( $menu_item ) );
			$alt       = $module->get_alt( $menu_item );

		?>
		<li id="wpzabb-food-menu-item-<?php echo $i ?>" class="wpzabb-food-menu-item">
			<div class="wpzabb-food-menu-item-outer-wrap">

				<?php if( !empty( $src ) ) : ?>
					<div class="wpzabb-food-menu-item-image" itemscope itemtype="http://schema.org/ImageObject">
						<img class="<?php echo esc_attr( $classes ); ?>" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" itemprop="image" />
					</div>
				<?php endif; ?>

				<div class="wpzabb-food-menu-item-details">

					<div class="wpzabb-food-menu-item-wrap">

						<h4 class="wpzabb-food-menu-item-name">

							<?php if( !empty( $menu_item->link ) ) : ?>
								<a href="<?php echo esc_url( $menu_item->link ); ?>" title="<?php echo esc_attr( $menu_item->name ); ?>" target="<?php echo $menu_item->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $menu_item->link_target, $menu_item->link_nofollow, 1 ); ?>>
							<?php endif; ?>

							<?php echo $menu_item->name; ?>

							<?php if( !empty( $menu_item->link ) ) : ?>
								</a>
							<?php endif; ?>

						</h4>

						<div class="wpzabb-food-menu-item-price">

							<?php
								if ( $settings->currency_position == 'before' ) {
									echo $menu_item->price_unit . filter_var( $menu_item->price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
								} else {
									echo filter_var( $menu_item->price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . $menu_item->price_unit;
								}
							?>

						</div>

					</div>

					<div class="wpzabb-food-menu-item-description">

						<?php echo $menu_item->description; ?>

					</div>

				</div>

			</div>
		</li>
		<?php endfor; ?>
	</ul>

	<?php if ( $settings->menu_button == 'yes' ) : ?>

	<div class="wpzabb-food-menu-button">
		<a href="<?php echo esc_url( $settings->menu_button_url ); ?>" title="<?php echo esc_attr( $settings->menu_button_label ); ?>" target="<?php echo $settings->menu_button_url_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $settings->menu_button_url_target, $settings->menu_button_url_nofollow, 1 ); ?>><?php echo $settings->menu_button_label; ?></a>
	</div>

	<?php endif; ?>

</div>