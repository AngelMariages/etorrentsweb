<div class="wpzabb-module-content wpzabb-heading-wrap wpzabb-heading-align-<?php echo $settings->alignment; ?> <?php echo ( $settings->separator_style == 'line_text' ) ? $settings->responsive_compatibility : ''; ?>">

	<?php if( $settings->separator_position == 'top' ) { ?>
		 <div class="wpzabb-module-content wpzabb-separator-parent">
		 	<?php if( $settings->separator_style == 'line_icon' || $settings->separator_style == 'line_image' || $settings->separator_style == 'line_text' ) { ?>
		 		<div class="wpzabb-separator-wrap <?php echo 'wpzabb-separator-'.$settings->alignment; ?> <?php echo ( $settings->separator_style == 'line_text' ) ? $settings->responsive_compatibility : ''; ?>" >
		 			<div class="wpzabb-separator-line wpzabb-side-left">
		 				<span></span>
		 			</div>			 		    
		 	        <div class="wpzabb-divider-content wpzabbi-divider">
		 				<?php $module->render_image(); ?>
		 				<?php if( $settings->separator_style == 'line_text' ) {
	 						echo '<'.$settings->separator_text_tag_selection.' class="wpzabb-divider-text">'.$settings->text_inline.'</'.$settings->separator_text_tag_selection.'>'; 
	 					}
		 				?>
		 	        </div>			 		    
		 		    <div class="wpzabb-separator-line wpzabb-side-right">
		 		    	<span></span>
		 		    </div> 
		 	    </div>
		 	<?php } ?>
		 	<?php if( $settings->separator_style == 'line' ) { ?>
	 			<div class="wpzabb-separator"></div>
		 	<?php } ?>
		 </div> 
	<?php } ?>

	<<?php echo $settings->tag; ?> class="wpzabb-heading">
		<?php if( !empty( $settings->link ) ) : ?>
			<a href="<?php echo $settings->link; ?>" title="<?php echo $settings->heading; ?>" target="<?php echo $settings->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $settings->link_target, 0, 1 ); ?>>
			<?php endif; ?>
			<span class="wpzabb-heading-text"><?php echo $settings->heading; ?></span>
			<?php if( !empty( $settings->link ) ) : ?>
			</a>
		<?php endif; ?>
	</<?php echo $settings->tag; ?>>

	<?php if($settings->separator_position == 'center') { ?>
		<div class="wpzabb-module-content wpzabb-separator-parent">			
			<?php if( $settings->separator_style == 'line_icon' || $settings->separator_style == 'line_image' || $settings->separator_style == 'line_text' ) { ?>
				<div class="wpzabb-separator-wrap <?php echo 'wpzabb-separator-'.$settings->alignment; ?> <?php echo ( $settings->separator_style == 'line_text' ) ? $settings->responsive_compatibility : ''; ?>">
					<div class="wpzabb-separator-line wpzabb-side-left">
						<span></span>
					</div>					    
			        <div class="wpzabb-divider-content wpzabbi-divider">
						<?php $module->render_image(); ?>
						<?php if( $settings->separator_style == 'line_text' ) {
							echo '<'.$settings->separator_text_tag_selection.' class="wpzabb-divider-text">'.$settings->text_inline.'</'.$settings->separator_text_tag_selection.'>'; 
						} ?>
			        </div>					    
				    <div class="wpzabb-separator-line wpzabb-side-right">
				    	<span></span>
				    </div> 
			    </div>
			<?php } ?>
			<?php if( $settings->separator_style == 'line' ) { ?>
					<div class="wpzabb-separator"></div>
			<?php } ?>
		</div>
    <?php } ?>

	<?php if( $settings->description != '' ) : ?>
		<div class="wpzabb-subheading wpzabb-text-editor">
			<?php echo $settings->description; ?>
		</div>
	<?php endif; ?>

	<?php if($settings->separator_position == 'bottom') { ?>
		<div class="wpzabb-module-content wpzabb-separator-parent">			
			<?php if( $settings->separator_style == 'line_icon' || $settings->separator_style == 'line_image' || $settings->separator_style == 'line_text' ) { ?>
				<div class="wpzabb-separator-wrap <?php echo 'wpzabb-separator-'.$settings->alignment; ?> <?php echo ( $settings->separator_style == 'line_text' ) ? $settings->responsive_compatibility : ''; ?>">
					<div class="wpzabb-separator-line wpzabb-side-left">
						<span></span>
					</div>
				    
			        <div class="wpzabb-divider-content wpzabbi-divider">
						<?php $module->render_image(); ?>
						<?php if( $settings->separator_style == 'line_text' ){
								echo '<'.$settings->separator_text_tag_selection.' class="wpzabb-divider-text">'.$settings->text_inline.'</'.$settings->separator_text_tag_selection.'>'; 
							}
						?>
			        </div>
				    
				    <div class="wpzabb-separator-line wpzabb-side-right">
				    	<span></span>
				    </div> 
			    </div>
			<?php } ?>

			<?php if( $settings->separator_style == 'line' ) { ?>
				<div class="wpzabb-separator"></div>
			<?php } ?>
		</div>
	<?php } ?> 
</div>