<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$bg_color = $bg_image = $bg_video_data_attr = $extra_classes = '';
$container_class = ( isset( $atts['is_fullwidth'] ) && $atts['is_fullwidth'] ) ? 'fw-container-fluid' : 'fw-container';


$overlay_style = $bg_color = $bg_image = $style = '';
$atts['padding_top'] = (int)$atts['padding_top'];
$atts['padding_right'] = (int)$atts['padding_right'];
$atts['padding_bottom'] = (int)$atts['padding_bottom'];
$atts['padding_left'] = (int)$atts['padding_left'];
if($atts['padding_top'] != 0 || $atts['padding_right'] != 0 || $atts['padding_bottom'] != 0 || $atts['padding_left'] != 0){
    $style = 'style="padding: ' . $atts['padding_top'] . 'px ' . $atts['padding_right'] . 'px ' . $atts['padding_bottom'] . 'px ' . $atts['padding_left'] . 'px;"';
}

if ( isset( $atts['background_options']['background'] ) && $atts['background_options']['background'] == 'default' ) {
	$extra_classes .= ' fw-main-row';
} elseif ( isset( $atts['is_fullwidth'] ) && isset( $atts['auto_generated'] ) && $atts['auto_generated'] == '' ) {
	$extra_classes .= ' fw-main-row-custom';
} else {
	$extra_classes .= ' fw-main-row';
}

if ( isset( $atts['first_in_builder'] ) && $atts['first_in_builder'] ) {
	$extra_classes .= ' fw-main-row-top';
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
} elseif ( $atts['background_options']['background'] == 'video' ) {
	if($atts['background_options']['video']['video_type']['selected'] == 'uploaded' ){
		$video_url = $atts['background_options']['video']['video_type']['uploaded']['video']['url'];
	}
	else{
		$video_url = $atts['background_options']['video']['video_type']['youtube']['video'];
	}
	$filetype  = wp_check_filetype( $video_url );
	$filetypes = array( 'mp4' => 'mp4', 'ogv' => 'ogg', 'webm' => 'webm', 'jpg' => 'poster' );
	$filetype  = array_key_exists( (string) $filetype['ext'], $filetypes ) ? $filetypes[ $filetype['ext'] ] : 'video';
	$data_name_attr = version_compare( fw_ext('shortcodes')->manifest->get_version(), '1.3.9', '>=' ) ? 'data-background-options' : 'data-wallpaper-options';
    $bg_video_data_attr = $data_name_attr.'="' . htmlspecialchars( json_encode( array( 'source' => array( 'poster'=>'', $filetype => $video_url ) ) ) ) . '"';
	$extra_classes .= ' background-video';
} elseif(isset($atts['background_options']['background']) && $atts['background_options']['background'] == 'color'){
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

<?php
global $post;
$section_id = 'section_id-'. fw_unique_increment();
?>

<?php if(isset($atts['background_options']['background']) && $atts['background_options']['background'] == 'image' && $atts['background_options']['image']['parallax'] == 'yes') :
    $atts['class'] .= ' parallax-section'; ?>
    <script>
        jQuery(document).ready(function($) {
            $('#<?php echo $section_id; echo $post->ID; ?>').parallax("50%", 0.1);
        });
    </script>
<?php endif; ?>

<section id="<?php echo $section_id; echo $post->ID;?>" class="<?php echo esc_attr( $extra_classes . ' fw-section-no-padding' ); ?> <?php echo esc_attr($atts['class']); ?>" style="<?php echo ($bg_color); ?> <?php echo ($bg_image); ?>"<?php echo ($bg_video_data_attr); ?> >
<?php echo $overlay_style; ?>
	<div class="fw-col-inner <?php echo esc_attr( $container_class ); ?>" <?php echo $style; ?>>
		<?php echo do_shortcode( $content ); ?>
	</div>
</section>