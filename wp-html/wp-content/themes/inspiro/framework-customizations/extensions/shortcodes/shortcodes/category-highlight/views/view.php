<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

if ( empty( $atts['image'] ) ) {
    $image = fw_get_framework_directory_uri('/static/img/no-image.png');
} else {
    $image = $atts['image']['url'];
}
?>

<div class="fw-category-image" style="background-image: url(<?php echo esc_attr($atts['image']['url']); ?>);">


    <?php if ( $atts['link'] ) { ?><a href="<?php echo esc_attr($atts['link']) ?>" target="<?php echo esc_attr($atts['target']) ?>"><?php } ?>

    	<div class="fw-category-inner">
    		<div class="fw-category-name">
    			<h3><?php echo $atts['name']; ?></h3>
    			<span><?php echo $atts['subtitle']; ?></span>
    		</div>
    		<div class="fw-category-text">
    			<p><?php echo $atts['desc']; ?></p>
    		</div>


            <?php if ( $atts['button'] == 'button_all_show') { ?>

                <div class="wpz-btn-center">
                    <span class="wpz-btn wpz-btn-white">
                    <?php echo $atts['label']; ?>
                    </span>
                </div>

            <?php } ?>


    	</div>

    <?php if ( $atts['link'] ) { ?></a><?php } ?>
</div>
