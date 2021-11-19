<?php if (!defined('FW')) die( 'Forbidden' );

$heading_color = '';
if ( ! empty( $atts['heading_color'] ) ) {
    $heading_color = 'color:' . $atts['heading_color'] . ';';
}

$section_style   = ( $heading_color ) ? 'style="' . esc_attr($heading_color) . '"' : '';

?>
<div class="fw-heading<?php if ( isset( $atts['centered'] ) && $atts['centered'] ) echo ' fw-heading-center'; ?>" <?php echo $section_style; ?>>
	<?php $heading = "<{$atts['heading']} class='fw-special-title'".$section_style.">{$atts['title']}</{$atts['heading']}>"; ?>
	<?php echo $heading; ?>
	<?php if (!empty($atts['subtitle'])): ?>
		<div class="fw-special-subtitle"><?php echo $atts['subtitle']; ?></div>
	<?php endif; ?>
</div>