<?php
	$columns = [];
	$class_names = ['wpzabb-image-grid'];

	if ( count( $settings->items ) > 0 ) {
		$columns['desktop'] = min( max( absint( $settings->columns ), 1 ), 6 );
		$columns['tablet'] = min( max( absint( $settings->columns_medium ), 1 ), 6 );
		$columns['mobile'] = min( max( absint( $settings->columns_responsive ), 1 ), 6 );
	}

	if ( ! empty( $columns ) ) {
		$class_names[] = 'columns-desktop-' . absint( $columns['desktop'] );
		$class_names[] = 'columns-tablet-' . absint( $columns['tablet'] );
		$class_names[] = 'columns-phone-' . absint( $columns['mobile'] );
	}
?>
<div class="<?php echo implode( ' ', $class_names ); ?>">

	<ul class="wpzabb-image-grid-items">
		<?php

		for ( $i = 0; $i < count( $settings->items ); $i++ ) :

			if ( ! is_object( $settings->items[ $i ] ) ) {
				continue;
			}

			$item    = $settings->items[ $i ];
			$classes = $module->get_classes( $item );
			$img     = trim( $module->get_src( $item ) );
			$color   = !empty( $item->color ) ? WPZABB_Helper::maybe_prepend_hash( $item->color ) : '#48b1ff';

		?>
		<li class="wpzabb-image-grid-item wpzabb-image-grid-item-<?php echo $i + 1; ?>">
			<?php if ( !empty( $item->link ) ) : ?>
				<a href="<?php echo esc_url( $item->link ); ?>" title="<?php echo esc_attr( $item->title ); ?>" target="<?php echo $item->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $item->link_target, $item->link_nofollow, 1 ); ?>>
			<?php endif; ?>

			<?php if ( !empty( $img ) ) : ?>
				<img class="wpzabb-image-grid-item-image <?php echo esc_attr( $classes ); ?>" src="<?php echo esc_url( $img ); ?>" />
			<?php endif; ?>

			<?php if ( !empty( $item->title ) ) : ?>
				<h4 class="wpzabb-image-grid-item-title">
					<span><?php echo esc_html( $item->title ); ?></span>
				</h4>
			<?php endif; ?>

			<span class="wpzabb-image-grid-item-color" style="background-color:<?php echo $color; ?>"></span>

			<?php if ( !empty( $item->link ) ) : ?>
				</a>
			<?php endif; ?>
		</li>
		<?php endfor; ?>
	</ul>
</div>