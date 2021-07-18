<?php
	$team_members_class = 'wpzabb-team-members-wrap ' . $settings->layout . ' content-align-'. $settings->content_align;
?>
<div class="<?php echo $team_members_class; ?>">

	<div class="wpzabb-members">
		<?php
		for ( $i = 0; $i < count( $settings->members ); $i++ ) :

			if ( ! is_object( $settings->members[ $i ] ) ) {
				continue;
			} else {
				$member = $settings->members[ $i ];
			}

			$classes  = $module->get_classes( $member );
			$src      = $module->get_src( $member );
			$alt      = $module->get_alt( $member );
		?>
		<figure class="wpzabb-member">
			<?php if( !empty( $member->link ) ) : ?>
				<a href="<?php echo $member->link; ?>" title="<?php echo $member->name; ?>" target="<?php echo $member->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $member->link_target, 0, 1 ); ?>>
			<?php endif; ?>
			<div class="wpzabb-member-avatar" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo $classes; ?>" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" itemprop="image"/>
			</div>
			<?php if( !empty( $member->link ) ) : ?>
				</a>
			<?php endif; ?>
			<figcaption class="wpzabb-member-caption">
				<<?php echo $settings->tag; ?> class="wpzabb-member-name">
				<?php if( !empty( $member->link ) ) : ?>
					<a href="<?php echo $member->link; ?>" title="<?php echo $member->name; ?>" target="<?php echo $member->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $member->link_target, 0, 1 ); ?>>
				<?php endif; ?>
				<span class="wpzabb-member-name-text"><?php echo $member->name ?></span>
				<?php if( !empty( $member->link ) ) : ?>
					</a>
				<?php endif; ?>
				</<?php echo $settings->tag; ?>>
				<span class="wpzabb-member-position"><?php echo $member->position ?></span>
				<?php if ( !empty( $member->member_info ) ): ?>
					<div class="wpzabb-member-info"><?php echo $member->member_info ?></div>
				<?php endif ?>
			</figcaption>
		</figure>
		<?php endfor; ?>
	</div>

</div>
