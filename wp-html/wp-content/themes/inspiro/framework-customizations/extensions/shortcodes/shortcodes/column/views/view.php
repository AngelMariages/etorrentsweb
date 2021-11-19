<?php if (!defined('FW')) die('Forbidden'); ?>

<?php

    $id_to_class = fw_ext_builder_get_item_width('page-builder', $atts['width'] . '/frontend_class');



    $id = uniqid( 'column-' );
    $overlay_style = $bg_color = $bg_image = $style = '';
	$atts['padding_top'] = (int)$atts['padding_top'];
	$atts['padding_right'] = (int)$atts['padding_right'];
	$atts['padding_bottom'] = (int)$atts['padding_bottom'];
	$atts['padding_left'] = (int)$atts['padding_left'];
	if($atts['padding_top'] != 0 || $atts['padding_right'] != 0 || $atts['padding_bottom'] != 0 || $atts['padding_left'] != 0){
		$style = 'style="padding: ' . $atts['padding_top'] . 'px ' . $atts['padding_right'] . 'px ' . $atts['padding_bottom'] . 'px ' . $atts['padding_left'] . 'px;"';
	}

    if(isset($atts['background_options']['background']) && $atts['background_options']['background'] == 'image' && !empty($atts['background_options']['image']['background_image']['data']) ){
        $bg_image = 'background-image:url(' . $atts['background_options']['image']['background_image']['data']['icon'] . ');';
        $bg_image .= ' background-repeat: '.$atts['background_options']['image']['repeat'].';';
        $bg_image .= ' background-position: '.$atts['background_options']['image']['bg_position_x'].' '.$atts['background_options']['image']['bg_position_y'].';';
        $bg_image .= ' background-size: '.$atts['background_options']['image']['bg_size'].';';

        if(isset($atts['background_options']['image']['background_color']['id']) && $atts['background_options']['image']['background_color']['id'] == 'fw-custom' && $atts['background_options']['image']['background_color']['color'] != '') {
            $bg_color = 'background-color:' . $atts['background_options']['image']['background_color']['color'] . ';';
        }
        elseif(isset($atts['background_options']['image']['background_color']['id'])){
            $atts['class'] .= ' fw_theme_bg_'.$atts['background_options']['image']['background_color']['id'];
        }

        $type = $atts['background_options']['background'];
        $overlay = $atts['background_options'][$type]['overlay_options']['overlay'];
        if($overlay == 'yes'){
            $overlay_bg = $atts['background_options'][$type]['overlay_options']['yes']['background']['id'];
            $opacity_param = 'overlay_opacity_'.$atts['background_options']['background'];
            $opacity = $atts['background_options'][$type]['overlay_options']['yes'][$opacity_param]/100;
            if($overlay_bg == 'fw-custom' && !empty($atts['background_options'][$type]['overlay_options']['yes']['background']['color'])){
                $overlay_style = '<div class="fw-main-row-overlay" style="background-color: '.$atts['background_options'][$type]['overlay_options']['yes']['background']['color'].'; opacity: '.$opacity.';"></div>';
            }
            else{
                $overlay_style = '<div class="fw-main-row-overlay overlay_'.$overlay_bg.'" style="opacity: '.$opacity.';"></div>';
            }
        }
    }
    elseif(isset($atts['background_options']['background']) && $atts['background_options']['background'] == 'color'){
        if(isset($atts['background_options']['color']['background_color']['id']) && $atts['background_options']['color']['background_color']['id'] == 'fw-custom') {
            if( !empty($atts['background_options']['color']['background_color']['color']) ){
                $bg_color = 'background-color:' . $atts['background_options']['color']['background_color']['color'] . ';';
            }
        }
        elseif(isset($atts['background_options']['color']['background_color']['id'])){
            $atts['class'] .= ' fw_theme_bg_'.$atts['background_options']['color']['background_color']['id'];
        }
    }
?>
<?php if(isset($atts['background_options']['background']) && $atts['background_options']['background'] == 'image' && $atts['background_options']['image']['parallax'] == 'yes') :
    $atts['class'] .= ' parallax-section'; ?>
    <script>
        jQuery(document).ready(function($) {
            $('#<?php echo $id; ?>').parallax("50%", 0.1);
        });
    </script>
<?php endif; ?>
<div id="<?php echo $id; ?>" class="<?php echo esc_attr($id_to_class); ?> <?php echo esc_attr($atts['class']); ?>" style="<?php echo $bg_image.' '.$bg_color; ?>">
    <?php echo $overlay_style; ?>
    <div class="fw-col-inner" <?php echo $style; ?>>
		<?php echo do_shortcode($content); ?>
	</div>
</div>