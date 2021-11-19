<?php if (!defined('FW')) die( 'Forbidden' ); ?>
<?php $color_class = !empty($atts['color']) ? "wpz-btn-{$atts['color']}" : ''; ?>

<?php if ( isset( $atts['centered'] ) && $atts['centered'] ) { ?>
<div class="wpz-btn-center">
<?php } ?>
    <a href="<?php echo esc_attr($atts['link']) ?>" target="<?php echo esc_attr($atts['target']) ?>" class="wpz-btn wpz-btn-1 <?php echo esc_attr($color_class); ?>">
    	<span><?php echo $atts['label']; ?></span>
    </a>
<?php if ( isset( $atts['centered'] ) && $atts['centered'] ) { ?>
</div>
<?php } ?>