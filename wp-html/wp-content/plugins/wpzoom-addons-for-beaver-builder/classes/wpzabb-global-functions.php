<?php 

/**
 * = Global functions
 *
 * array_replace_recursive()
 * recurse()
 * wpzabb_theme_base_color()
 * wpzabb_theme_text_color()
 * wpzabb_theme_link_color()
 * wpzabb_theme_link_hover_color()
 * wpzabb_theme_button_font_family()
 * wpzabb_theme_button_font_size()
 * wpzabb_theme_button_line_height()
 * wpzabb_theme_button_letter_spacing()
 * wpzabb_theme_button_text_transform()
 * wpzabb_theme_button_bg_color()
 * wpzabb_theme_button_bg_hover_color()
 * wpzabb_theme_button_text_color()
 * wpzabb_theme_button_text_hover_color()
 * wpzabb_theme_button_padding()
 * wpzabb_theme_button_vertical_padding()
 * wpzabb_theme_button_horizontal_padding()
 * wpzabb_theme_button_border_radius()
 * wpzabb_parse_color_to_hex()
 *
 */

/**
 * array_replace_recursive() function for PHP older version
 */

if ( !function_exists('array_replace_recursive') ) {
	function array_replace_recursive($base, $replacements) {

		$base = recurse($base, $replacements);
    	// handle the arguments, merge one by one
    	$args = func_get_args();
    	$base = $args[0];
    	if ( !is_array($base) ) {
      		return $base;
    	}
    
    	for ($i = 1; $i < count($args); $i++) {
      		if ( is_array($args[$i]) ) {
        		$base = recurse($base, $args[$i]);
      		}
    	}
    
    	return $base;
  	}

  	function recurse($base, $replacements) {
    	foreach ($replacements as $key => $value) {
        	// create new key in $base, if it is empty or not an array
        	if (!isset($base[$key]) || (isset($base[$key]) && !is_array($base[$key]))) {
          		$base[$key] = array();
        	}

        	// overwrite the value in the base array
        	if (is_array($value)) {
          		$value = recurse($base[$key], $value);
        	}
        	
        	$base[$key] = $value;
      	}
      	
      	return $base;
    }
}
/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_base_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/theme_color', $default );
		
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_theme_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_text_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/text_color', $default );
		
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_text_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_link_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/link_color', $default );
				
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_link_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_link_hover_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/link_hover_color', $default );
				
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_link_hover_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the font family, if yes, returns users value else checks for filtered value.
 *
 * @return string - font-family
 */
function wpzabb_theme_button_font_family( $default ) {
	$btn_font_family = array();

	if ( $default['family'] == '' ||  $default['family'] == 'Default' ) {

		$btn_font_family = apply_filters( 'wpzabb_theme_button_font_family', $default );

	} else {
		$btn_font_family = $default;
	}

	return $btn_font_family;
}

/**
 * Button Font Size
 */
function wpzabb_theme_button_font_size( $default ) {
	$font_size = '';

	if ( $default == '' ) {

		$font_size = apply_filters( 'wpzabb/global/button_font_size', $default );
				
		if ( $font_size == '' ) {
			$font_size = apply_filters( 'wpzabb_theme_button_font_size', $default );
		}else{
			$font_size = $font_size.'px';
		}
	} else {
		$font_size = $default;
	}

	return $font_size;
}

/**
 * Button Line Height
 */
function wpzabb_theme_button_line_height( $default ) {
	$line_height = '';

	if ( $default == '' ) {

		$line_height = apply_filters( 'wpzabb/global/button_line_height', $default );
				
		if ( $line_height == '' ) {
			$line_height = apply_filters( 'wpzabb_theme_button_line_height', $default );
		}else{
			$line_height = $line_height.'px';
		}
	} else {
		$line_height = $default;
	}

	return $line_height;
}

/**
 * Button Letter Spacing
 */
function wpzabb_theme_button_letter_spacing( $default ) {
	$letter_spacing = '';

	if ( $default == '' ) {

		$letter_spacing = apply_filters( 'wpzabb/global/button_letter_spacing', $default );
				
		if ( $letter_spacing == '' ) {
			$letter_spacing = apply_filters( 'wpzabb_theme_button_letter_spacing', $default );
		}else{
			$letter_spacing = $letter_spacing.'px';
		}
	} else {
		$letter_spacing = $default;
	}

	return $letter_spacing;
}

/**
 * Button Text Transform
 */
function wpzabb_theme_button_text_transform( $default ) {
	$text_transform = '';

	if ( $default == '' ) {

		$text_transform = apply_filters( 'wpzabb/global/button_text_transform', $default );
				
		if ( $text_transform == '' ) {
			$text_transform = apply_filters( 'wpzabb_theme_button_text_transform', $default );
		}
	} else {
		$text_transform = $default;
	}

	return $text_transform;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_button_bg_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/button_bg_color', $default );
				
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_button_bg_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_button_bg_hover_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/button_bg_hover_color', $default );
				
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_button_bg_hover_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_button_text_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/button_text_color', $default );
				
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_button_text_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - hex value for the color
 */
function wpzabb_theme_button_text_hover_color( $default ) {
	$color = '';

	if ( $default == '' ) {

		$color = apply_filters( 'wpzabb/global/button_text_hover_color', $default );
				
		if ( $color == '' ) {
			$color = apply_filters( 'wpzabb_theme_button_text_hover_color', $default );
		}
	} else {
		$color = $default;
	}

	return $color;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the color, if yes, returns users value else checks for filtered value.
 *
 * @return string - padding value
 */
function wpzabb_theme_button_padding( $default ) {
	$padding = '';

	if ( $default == '' ) {

		$padding = apply_filters( 'wpzabb/global/button_padding', $default );
							
		if ( $padding == '' ) {
			$padding = apply_filters( 'wpzabb_theme_button_padding', $default );
			if ( $padding == '' ) {
				$padding = '12px 24px';
			}
		}
	} else {
		$padding = $default;
	}

	return $padding;
}

function wpzabb_theme_button_vertical_padding( $default ) {
	$padding = '';

	if ( $default == '' ) {

		$padding = apply_filters( 'wpzabb/global/button_vertical_padding', $default );
							
		if ( $padding == '' ) {
			$padding = apply_filters( 'wpzabb_theme_button_vertical_padding', $default );
			if ( $padding == '' ) {
				$padding = '12';
			}
		}
	} else {
		$padding = $default;
	}

	return $padding;
}

function wpzabb_theme_button_horizontal_padding( $default ) {
	$padding = '';

	if ( $default == '' ) {

		$padding = apply_filters( 'wpzabb/global/button_horizontal_padding', $default );
							
		if ( $padding == '' ) {
			$padding = apply_filters( 'wpzabb_theme_button_horizontal_padding', $default );
			if ( $padding == '' ) {
				$padding = '24';
			}
		}
	} else {
		$padding = $default;
	}

	return $padding;
}

/**
 * Provide option to override the element defaults from theme options.
 *
 * checks if user has set the radius, if yes, returns users value else checks for filtered value.
 *
 * @return string - radius value
 */
function wpzabb_theme_button_border_radius( $default ) {
	$radius = '';

	if ( $default == '' ) {
	
		$radius = apply_filters( 'wpzabb/global/button_border_radius', $default );

		if ( $radius == '' ) {
			$radius = apply_filters( 'wpzabb_theme_button_border_radius', $default );
			if ( $radius == '' ) {
				$radius = '4';
			}
		}
	} else {
		$radius = $default;
	}

	return $radius;
}



/**
 * Provide option to parse a color code.
 *
 * returns a hex value for color from rgba or #hex color.
 *
 * @return string - hex value for the color
 */
function wpzabb_parse_color_to_hex( $code = '' ) {
	$color = '';
	$hex = '';
	if( $code != '' ) {
		if ( strpos( $code, 'rgba' ) !== false ) {
			$code = ltrim( $code, 'rgba(' );
			$code = rtrim( $code, ')' );
			$rgb = explode( ',', $code );
			$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
			$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
			$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
			$color = $hex;
		} else {
			$color = ltrim( $code, '#' );
		}
	}
	//var_dump($hex); die;
	return $color;
}

/**
 * Function that return an array of categories by post_type.
 *
 * @return array - array of available categories
 */
function wpzabb_get_category_term_list( $post_type = 'post' ) {

    $args   = array(
        'hide_empty' => true,
        'taxonomy'   => 'category'
    );

    if ( $post_type === 'portfolio' ) {
        $args   = array(
            'hide_empty' => true,
            'taxonomy'   => 'portfolio'
        );
    } elseif ( $post_type === 'product' ) {
        $args   = array(
            'hide_empty' => true,
            'taxonomy'   => 'product_cat'
        );
    }

    $terms  = get_terms( $args );
    $result = wp_list_pluck( $terms, 'name', 'slug' );

    return array( 0 => esc_html__( 'All Categories', 'fw' ) ) + $result;
}
