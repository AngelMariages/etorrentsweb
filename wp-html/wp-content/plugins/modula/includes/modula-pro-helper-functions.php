<?php

/* Modula PRO Filter Functions */

// Function to output all filters
function modula_pro_output_filters( $settings ) {

	if ( ! isset( $settings['filters'] ) ) {
		return '';
	}

	$filters = Modula_Pro_Helper::remove_empty_items( $settings['filters'] );

	if ( ! is_array( $filters ) ) {
		return;
	}

	if ( empty( $filters ) ) {
		return;
	}

	$before_items = array('top','top_bottom','left','left_right');
	$after_items = array('bottom','top_bottom','right','left_right');
	$horizontal = array('top','bottom','top_bottom');


	$current_filter = isset( $settings['defaultActiveFilter'] ) ? $settings['defaultActiveFilter'] : 'all';
	$current_filter = isset( $_GET['jtg-filter'] ) ? $_GET['jtg-filter'] : $current_filter;
	$current_filter = sanitize_title( $current_filter );
	$filter_url     = $settings['filterClick'] ? '?jtg-filter=all' : '#';

    $hide_all_filter = $settings['hideAllFilter'];

	$filter_position = isset( $settings['filterPositioning'] ) ? $settings['filterPositioning'] : '';

	$extra_classes  = isset( $settings['filterStyle'] ) ? 'styled-menu menu--'.$settings['filterStyle'] : '';
	$extra_classes .= in_array( $filter_position, $horizontal ) ? ' horizontal-filters' : ' vertical-filters' ;

    if ( 'left' == $filter_position ) {
        $extra_classes .= ' left-vertical';
    }

    if ( 'right' == $filter_position ) {
        $extra_classes .= ' right-vertical';
    }

    if ( 'left_right' == $filter_position ) {
        $extra_classes .= ' both-vertical';
    }

	$filter_by_text = '';
	if(isset($settings['enableCollapsibleFilters']) && '1' == $settings['enableCollapsibleFilters'] && isset($settings['collapsibleActionText'])) {
		$filter_by_text = $settings['collapsibleActionText'];
	}

	$filter_by_wrapper_style = '';
	if( isset($settings['enableCollapsibleFilters']) && '1' == $settings['enableCollapsibleFilters'] ){
		$filter_by_wrapper_style = 'display:none;';
	}

	if( doing_filter( 'modula_shortcode_before_items' ) && ! in_array( $filter_position,$before_items ) ){
		return false;
	}

	if( doing_filter( 'modula_shortcode_after_items' ) && ! in_array( $filter_position, $after_items ) ){
		return false;
	}

	if(isset($settings['enableCollapsibleFilters']) && '1' == $settings['enableCollapsibleFilters']){
		echo '<div class="filter-by-wrapper"><span>'.esc_html( $filter_by_text ).'</span></div>';
	}

	if ( '1' != $settings['dropdownFilters'] ) {

		echo "<div class='filters " . esc_attr( $extra_classes ) . "' style='" . esc_attr( $filter_by_wrapper_style ) . "'><ul class='modula_menu__list'>";

		if ( !isset( $hide_all_filter ) || '1' != $hide_all_filter ) {
			echo '<li class="modula_menu__item ' . ('all' == $current_filter ? 'modula_menu__item--current' : '') . '"><a data-filter="all" href="' . esc_url( $filter_url ) . '" class="' . ('all' == $current_filter ? 'selected' : '') . ' modula_menu__link ">' . esc_html( $settings['allFilterLabel'] ) . '</a>';
		}

		foreach ( $filters as $filter ) {
			$filter_slug = sanitize_title( $filter );
			$filter_url  = $settings['filterClick'] ? '?jtg-filter=' . $filter_slug : "#jtg-filter-" . $filter_slug;
			echo '<li class="modula_menu__item ' . ($filter_slug == $current_filter ? 'modula_menu__item--current' : '') . '"><a data-filter="' . esc_attr( urldecode( $filter_slug ) ) . '" href="' . esc_url( $filter_url ) . '" class=" modula_menu__link  ' . ($current_filter == $filter_slug ? 'selected' : '') . '">' . esc_html( $filter ) . '</a></li>';
		}
		echo "</div>";

	} else {

		echo "<select class='filters " . esc_attr( $extra_classes ) . "' style='" . esc_attr( $filter_by_wrapper_style ) . "'>";

		if ( !isset( $hide_all_filter ) || '1' != $hide_all_filter ) {
			echo '<option class="modula_menu__item ' . ('All' == $current_filter ? 'modula_menu__item--current' : '') . '" value="all"><a  href="' . esc_url( $filter_url ) . '" class="' . ('all' == $current_filter ? 'selected' : '') . ' modula_modula_menu__link ">' . esc_html( $settings['allFilterLabel'] ) . '</a></option>';
		}

		foreach ( $filters as $filter ) {
			$filter_slug = sanitize_title( $filter );
			$filter_url  = $settings['filterClick'] ? '?jtg-filter=' . $filter_slug : "#jtg-filter-" . $filter_slug;
			if ( $filter_slug == $current_filter ) {
				echo '<option selected class="modula_menu__item ' . ($filter_slug == $current_filter ? 'modula_menu__item--current' : '') . '" value="' . esc_attr( urldecode( $filter_slug ) ) . '"><a href="' . esc_url( $filter_url ) . '" class=" modula_menu__link  ' . ($current_filter == $filter_slug ? 'selected' : '') . '">' . esc_html( $filter ) . '</option>';
			} else {
				echo '<option class="modula_menu__item ' . ($filter_slug == $current_filter ? 'modula_menu__item--current' : '') . '" value="' . esc_attr( urldecode( $filter_slug ) ) . '"><a href="' . esc_url( $filter_url ) . '" class=" modula_menu__link  ' . ($current_filter == $filter_slug ? 'selected' : '') . '">' . esc_html( $filter ) . '</option>';
			}
		}


		echo '</select>';
	}

}

// Add filters to items
function modula_pro_add_filters( $item_data, $item, $settings ) {

	if ( isset( $item['filters'] ) ) {

		$filters = explode( ',', $item['filters'] );
		$item_data['item_classes'][] = 'jtg-filter-all';

		foreach ( $filters as $filter ) {
			$item_data['item_classes'][] = 'jtg-filter-' . esc_attr(urldecode(sanitize_title( $filter )));
		}
		
	}

	return $item_data;

}


/**
 * @param $template_data
 *
 * @return string
 *
 * Adds extra class to modula-gallery that is used to position the filters
 */
function modula_pro_extra_modula_section_classes($template_data){

	if ( empty( Modula_Pro_Helper::remove_empty_items( $template_data['settings']['filters'] ) ) ) {
		return $template_data;
	}

	$filter_position = isset( $template_data['settings']['filterPositioning'] ) ? $template_data['settings']['filterPositioning'] : '';
	$horizontal = array( 'top', 'bottom', 'top_bottom' );
	$extra_classes = in_array( $filter_position, $horizontal ) ? ' horizontal-filters' : ' vertical-filters' ;
	$template_data['gallery_container']['class'][] = $extra_classes;
	return $template_data;
}

/**
 * @return array
 *
 * Return default active filter
 */
function modula_pro_current_active_filter() {
	$id = get_the_ID();
	$settings = get_post_meta($id,'modula-settings',true);
	$filters = isset($settings['filters']) ? $settings['filters'] : false;

	$return = array(
		'All' => esc_html__( 'All','modula-pro' )
	);

	if( $filters ) {
		foreach ( $filters as $filter ) {
			$return[ $filter ] = esc_html( $filter );
		}
	}

	return $return;
}

function modula_pro_sanitize_color( $color ){

	if ( method_exists( 'Modula_Helper', 'sanitize_rgba_colour' ) ) {
		return Modula_Helper::sanitize_rgba_colour( $color );
	}

	if ( empty( $color ) || is_array( $color ) ){
		return 'rgba(0,0,0,0)';
	}
			

	if ( false === strpos( $color, 'rgba' ) ) {
		return sanitize_hex_color( $color );
	}
	
	$color = str_replace( ' ', '', $color );
	sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
	
	return 'rgba(' . absint( $red ) . ',' . absint( $green ) . ',' . absint( $blue ) . ',' . floatval( $alpha ) . ')';

}