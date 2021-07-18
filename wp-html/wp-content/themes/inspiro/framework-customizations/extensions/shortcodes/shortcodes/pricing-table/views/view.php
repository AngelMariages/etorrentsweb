<?php if (!defined('FW')) die( 'Forbidden' ); ?>
<?php $tabs_id = uniqid('fw-tabs-');


$bg_color = $extra_classes = '';

if ( $atts['background_options']['background'] == 'color' ) {
    if ( isset( $atts['background_options']['color']['background_color']['id'] ) && $atts['background_options']['color']['background_color']['id'] == 'fw-custom' ) {
        if ( ! empty( $atts['background_options']['color']['background_color']['color'] ) ) {
            $bg_color = 'background-color:' . $atts['background_options']['color']['background_color']['color'] . ';';
        }
    } elseif ( isset( $atts['background_options']['color']['background_color']['id'] ) ) {
        $extra_classes .= ' fw_theme_bg_' . $atts['background_options']['color']['background_color']['id'];
    }
}

?>


<div class="fw-pricing-container <?php echo esc_attr( $extra_classes ); ?>">

    <div class="fw-pricing-header" style="<?php echo ($bg_color); ?>">
        <?php $heading = "<h3 class='fw-pricing-title'>{$atts['title']}</h3>"; ?>
        <?php echo $heading; ?>

        <?php $price = "<span class='fw-pricing-price'>{$atts['price']}</span>"; ?>
        <?php echo $price; ?>

        <?php $duration = "<span class='fw-pricing-duration'>{$atts['duration']}</span>"; ?>
        <?php echo $duration; ?>
    </div>


    <ul class="fw-pricing-content">

    	<?php foreach ($atts['tabs'] as $key => $tab) : ?>

            <li><?php echo do_shortcode( $tab['tab_title'] ) ?></li>

        <?php endforeach; ?>

    </ul>

    <a href="<?php echo esc_attr($atts['link']) ?>" target="<?php echo esc_attr($atts['target']) ?>" class="wpz-btn">
        <span><?php echo $atts['label']; ?></span>
    </a>

</div>