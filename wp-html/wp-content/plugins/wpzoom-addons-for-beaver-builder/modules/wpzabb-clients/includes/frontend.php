<?php
	$clients_class = 'wpzabb-clients-wrap ' . $settings->layout . ' content-align-'. $settings->content_align;
?>
<div class="<?php echo $clients_class; ?>">

	<ul class="wpzabb-clients">
		<?php
		for ( $i = 0; $i < count( $settings->clients ); $i++ ) :

			if ( ! is_object( $settings->clients[ $i ] ) ) {
				continue;
			} else {
				$client = $settings->clients[ $i ];
			}

			$classes  = $module->get_classes( $client );
			$src      = $module->get_src( $client );
			$alt      = $module->get_alt( $client );
		?>
		<li id="wpzabb-client-<?php echo $i ?>" class="wpzabb-client">
			<?php if( !empty( $client->link ) ) : ?>
				<a href="<?php echo $client->link; ?>" title="<?php echo $client->name; ?>" target="<?php echo $client->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $client->link_target, 0, 1 ); ?>>
			<?php endif; ?>
			<div class="wpzabb-client-image" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo $classes; ?>" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" itemprop="image"/>
			</div>
			<?php if( !empty( $client->link ) ) : ?>
				</a>
			<?php endif; ?>
		</li>
		<?php endfor; ?>
	</ul>

	<div class="fl-slider-next"></div>
	<div class="fl-slider-prev"></div>

</div>
