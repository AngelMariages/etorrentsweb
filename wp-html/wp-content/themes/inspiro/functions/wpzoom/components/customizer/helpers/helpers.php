<?php

/**
 * Returns a custom logo, linked to home.
 *
 * @since 4.5.0
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 * @return string Custom logo markup.
 */
function get_zoom_custom_logo($blog_id = 0)
{
    $html = '';
    $switched_blog = false;

    if ( is_multisite() && ! empty( $blog_id ) && (int) $blog_id !== get_current_blog_id() ) {
        switch_to_blog( $blog_id );
        $switched_blog = true;
    }

    $custom_logo_id = get_theme_mod( 'custom_logo' );

    // We have a logo. Logo is go.
    if ( $custom_logo_id ) {
        $custom_logo_attr = array(
            'class'    => 'custom-logo',
            'itemprop' => 'logo',
        );

        $info = zoom_customizer_logo_information();

        $width = absint($info['width']);
        $height = absint($info['height']);

        if ( get_theme_mod('custom_logo_retina_ready') ) {
            $width /= 2;
            $height /= 2;
        }

        /*
         * If the logo alt attribute is empty, get the site title and explicitly
         * pass it to the attributes used by wp_get_attachment_image().
         */
        $image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );

        if ( empty( $image_alt ) ) {
            $custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
        }

        /*
         * If the alt attribute is not empty, there's no need to explicitly pass
         * it because wp_get_attachment_image() already adds the alt attribute.
         */
        $html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( $custom_logo_id, array($width, $height), false, $custom_logo_attr )
        );
    }

    // If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
    elseif ( is_customize_preview() ) {
        $html = sprintf( '<a href="%1$s" class="custom-logo-link" style="display:none;"><img class="custom-logo"/></a>',
            esc_url( home_url( '/' ) )
        );
    }

    if ( $switched_blog ) {
        restore_current_blog();
    }

    /**
     * Filters the custom logo output.
     *
     * @since 4.5.0
     * @since 4.6.0 Added the `$blog_id` parameter.
     *
     * @param string $html    Custom logo HTML output.
     * @param int    $blog_id ID of the blog to get the custom logo for.
     */
    return apply_filters( 'get_custom_logo', $html, $blog_id );
}

/**
 * Displays a custom logo, linked to home.
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 */
function the_zoom_custom_logo($blog_id = 0)
{
    echo get_zoom_custom_logo($blog_id);
}

/**
 * Utility function for getting information about the theme logos.
 *
 * @param  bool $force Update the dimension cache.
 *
 * @return array Array containing image file, width, and height for each logo.
 */
function zoom_customizer_logo_information($force = false)
{
    $logo_information = array();

    $logo_information['image'] = get_theme_mod('custom_logo');

    if (!empty($logo_information['image'])) {
        $dimensions = zoom_customizer_get_logo_dimensions($logo_information['image'], $force);

        // Set the dimensions to the array if all information is present
        if (!empty($dimensions) && isset($dimensions['width']) && isset($dimensions['height'])) {
            $logo_information['width'] = $dimensions['width'];
            $logo_information['height'] = $dimensions['height'];
        }
    }

    return $logo_information;
}

/**
 * Get the dimensions of a logo image from cache or regenerate the values.
 *
 * @param  int $attachment_id The URL of the image in question.
 * @param  bool $force Cause a cache refresh.
 *
 * @return array The dimensions array on success, and a blank array on failure.
 */

function zoom_customizer_get_logo_dimensions($attachment_id, $force = false)
{
    // Build the cache key
    $key = WPZOOM::$theme_raw_name . '-' . md5('logo-dimensions-' . $attachment_id . WPZOOM::$themeVersion);

    // Pull from cache
    $dimensions = get_transient($key);

    // If the value is not found in cache, regenerate
    if (false === $dimensions || is_preview() || true === $force) {
        $dimensions = array();

        // Get the dimensions
        $info = wp_get_attachment_image_src($attachment_id, 'full');

        if (false !== $info && isset($info[0]) && isset($info[1]) && isset($info[2])) {
            // Detect JetPack altered src
            if (false === $info[1] && false === $info[2]) {
                // Parse the URL for the dimensions
                $pieces = parse_url(urldecode($info[0]));

                // Pull apart the query string
                if (isset($pieces['query'])) {
                    parse_str($pieces['query'], $query_pieces);

                    // Get the values from "resize"
                    if (isset($query_pieces['resize']) || isset($query_pieces['fit'])) {
                        if (isset($query_pieces['resize'])) {
                            $jp_dimensions = explode(',', $query_pieces['resize']);
                        } elseif ($query_pieces['fit']) {
                            $jp_dimensions = explode(',', $query_pieces['fit']);
                        }

                        if (isset($jp_dimensions[0]) && isset($jp_dimensions[1])) {
                            // Package the data
                            $dimensions = array(
                                'width' => $jp_dimensions[0],
                                'height' => $jp_dimensions[1],
                            );
                        }
                    }
                }
            } else {
                // Package the data
                $dimensions = array(
                    'width' => $info[1],
                    'height' => $info[2],
                );
            }
        } else {
            // Get the image path from the URL
            $wp_upload_dir = wp_upload_dir();
            $path = trailingslashit($wp_upload_dir['basedir']) . get_post_meta($attachment_id, '_wp_attached_file', true);

            // Sometimes, WordPress just doesn't have the metadata available. If not, get the image size
            if (file_exists($path)) {
                $getimagesize = getimagesize($path);

                if (false !== $getimagesize && isset($getimagesize[0]) && isset($getimagesize[1])) {
                    $dimensions = array(
                        'width' => $getimagesize[0],
                        'height' => $getimagesize[1],
                    );
                }
            }
        }

        // Store the transient
        if (!is_preview()) {
            set_transient($key, $dimensions, 86400);
        }
    }

    return $dimensions;
}


function zoom_customizer_sanitize_choice($value, $setting)
{
    return $value;
}

function zoom_customizer_sanitize_show_hide_checkbox($value)
{
    return (int)$value ? 'block' : 'none';
}

if (!function_exists('maybe_hash_hex_color')) :
    /**
     * Ensures that any hex color is properly hashed.
     *
     * This is a copy of the core function for use when the customizer is not being shown.
     *
     * @param  string $color The proposed color.
     *
     * @return string|null The sanitized color.
     */
    function maybe_hash_hex_color($color)
    {
        if ($unhashed = sanitize_hex_color_no_hash($color)) {
            return '#' . $unhashed;
        }

        return $color;
    }
endif;


function sanitize_responsive_typo( $val, $default = array() ) {
    $responsive = array(
        'desktop'      => isset( $default['desktop'] ) ? $default['desktop'] : '',
        'tablet'       => isset( $default['tablet'] ) ? $default['tablet'] : '',
        'mobile'       => isset( $default['mobile'] ) ? $default['mobile'] : '',
        'desktop-unit' => isset( $default['desktop-unit'] ) ? $default['desktop-unit'] : '',
        'tablet-unit'  => isset( $default['tablet-unit'] ) ? $default['tablet-unit'] : '',
        'mobile-unit'  => isset( $default['mobile-unit'] ) ? $default['mobile-unit'] : '',
    );

    if ( is_array( $val ) ) {
        $responsive['desktop']      = is_numeric( $val['desktop'] ) ? $val['desktop'] : '';
        $responsive['tablet']       = is_numeric( $val['tablet'] ) ? $val['tablet'] : '';
        $responsive['mobile']       = is_numeric( $val['mobile'] ) ? $val['mobile'] : '';
        $responsive['desktop-unit'] = in_array( $val['desktop-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['desktop-unit'] : 'px';
        $responsive['tablet-unit']  = in_array( $val['tablet-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['tablet-unit'] : 'px';
        $responsive['mobile-unit']  = in_array( $val['mobile-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['mobile-unit'] : 'px';
    }
    else {
        $responsive['desktop'] = is_numeric( $val ) ? $val : '';
    }

    return $responsive;
}


if ( ! function_exists('hex2rgba') ) {
    /* Convert hexdec color string to rgb(a) string */

    function hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
              return $default;

            //Sanitize $color if "#" is provided
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }

            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                    $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                    $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                    return $default;
            }

            //Convert hexadec to rgb
            $rgb =  array_map('hexdec', $hex);

            //Check if opacity is set(rgba or rgb)
            if( $opacity !== false ){
                if(abs($opacity) > 1)
                    $opacity = 1.0;
                $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
            } else {
                $output = 'rgb('.implode(",",$rgb).')';
            }

            //Return rgb(a) color string
            return $output;
    }
}


if (!function_exists('sanitize_hex_color')) :
    /**
     * Sanitizes a hex color.
     *
     * This is a copy of the core function for use when the customizer is not being shown.
     *
     * @param  string $color The proposed color.
     * @return string|null              The sanitized color.
     */
    function sanitize_hex_color($color)
    {
        if ('' === $color) {
            return '';
        }

        // 3 or 6 hex digits, or the empty string.
        if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
            return $color;
        }

        return null;
    }
endif;

if (!function_exists('sanitize_hex_color_no_hash')) :
    /**
     * Sanitizes a hex color without a hash. Use sanitize_hex_color() when possible.
     *
     * This is a copy of the core function for use when the customizer is not being shown.
     *
     * @param  string $color The proposed color.
     * @return string|null              The sanitized color.
     */
    function sanitize_hex_color_no_hash($color)
    {
        $color = ltrim($color, '#');

        if ('' === $color) {
            return '';
        }

        return sanitize_hex_color('#' . $color) ? $color : null;
    }
endif;

/**
 * Allow only certain tags and attributes in a string.
 *
 * @param  string $string The unsanitized string.
 * @return string               The sanitized string.
 */
function zoom_customizer_sanitize_text($string)
{
    global $allowedtags;
    $expandedtags = $allowedtags;

    // span
    $expandedtags['span'] = array();

    // Enable id, class, and style attributes for each tag
    foreach ($expandedtags as $tag => $attributes) {
        $expandedtags[$tag]['id'] = true;
        $expandedtags[$tag]['class'] = true;
        $expandedtags[$tag]['style'] = true;
    }

    // br (doesn't need attributes)
    $expandedtags['br'] = array();

    /**
     * Customize the tags and attributes that are allows during text sanitization.
     *
     * @param array $expandedtags The list of allowed tags and attributes.
     * @param string $string The text string being sanitized.
     */
    apply_filters('zoom_customizer_sanitize_text_allowed_tags', $expandedtags, $string);

    return wp_kses($string, $expandedtags);
}


if (!function_exists('zoom_customizer_all_font_choices')) :
    /**
     * Packages the font choices into value/label pairs for use with the customizer.
     *
     * @return array    The fonts in value/label pairs.
     */
    function zoom_customizer_all_font_choices()
    {
        $fonts = zoom_customizer_get_all_fonts();
        $choices = array();

        // Repackage the fonts into value/label pairs
        foreach ($fonts as $key => $fonts_group) {

            $choices[$key]['label'] = $fonts_group['label'];

            if ( isset($fonts_group['fonts']) ) {
                foreach ($fonts_group['fonts'] as $_key => $font) {
                    $choices[$key][$_key] = $font['label'];
                }
            }

        }

        /**
         * Allow for developers to modify the full list of fonts.
         *
         * @param array $choices The list of all fonts.
         */
        return apply_filters('zoom_customizer_all_font_choices', $choices);
    }
endif;


function zoom_customizer_alias_rules($rule)
{

    $aliases = array(
        'background-gradient' => 'background',
        'font-size-responsive' => 'font-size'
    );

    if ( 'font-family-sync-all' === $rule ) {
        return;
    }

    if ( array_key_exists( $rule, $aliases ) ) {
        $rule = $aliases[ $rule ];
    }

    return $rule;
}

function zoom_customizer_get_filtered_value( $rule, $value, $default = array(), $values = null )
{
    if ( null !== $values && is_array( $values ) && count( $values ) > 0 ) {
        foreach ( $values as $k => $v ) {
            if ( $k == $value ) {
                $value = $v;
                break;
            }
        }
    } else {
        $callbacks = array(
            'color' => 'maybe_hash_hex_color',
            'font-family' => 'zoom_customizer_get_font_stack',
            'font-size' => 'zoom_customizer_get_font_size',
            'letter-spacing' => 'zoom_customizer_get_letter_spacing',
            'display' => 'zoom_customizer_display_element',
            'background-gradient' => 'zoom_customizer_display_gradient',
            'opacity' => 'zoom_customizer_opacity',
            'font-size-responsive' => 'sanitize_responsive_typo'
        );

        $callbacks = apply_filters( 'zoom_customizer_get_filtered_value', $callbacks, $rule, $value, $default );

        $keys = array_keys( $callbacks );

        if ( in_array( $rule, $keys ) ) {
            if ( ! empty( $default ) ) {
                $value = call_user_func( $callbacks[ $rule ], $value, $default );
            }
            else {
                $value = call_user_func( $callbacks[ $rule ], $value );
            }
        }
    }

    return $value;
}

function zoom_customizer_display_gradient( $options )
{

    if ( ! is_string($options) ) return false;

    $json_decode = json_decode($options, true);

    $options = $json_decode[0];

    $gradient = $gradient2 = '';

    $directions = array(
        'user-agent' => array(
            'horizontal'    => 'left',
            'vertical'      => 'top',
            'diagonal-lt'   => '45deg',
            'diagonal-lb'   => '-45deg'
        ),
        'w3c' => array(
            'horizontal'    => 'to right',
            'vertical'      => 'to bottom',
            'diagonal-lt'   => '135deg',
            'diagonal-lb'   => '45deg'
        ),
    );

    $direction = $directions['user-agent'][ $options['direction'] ];
    $direction2 = $directions['w3c'][ $options['direction'] ];
    $start_color = hex2rgba( $options['start_color'], $options['start_opacity'] );
    $end_color = hex2rgba( $options['end_color'], $options['end_opacity'] );
    $start_location = $options['start_location'];
    $end_location = $options['end_location'];

    $gradient = $direction . ', ' . $start_color . ' ' . $start_location . '%, ' . $end_color . ' ' . $end_location . '%';
    $gradient2 = $direction2 . ', ' . $start_color . ' ' . $start_location . '%, ' . $end_color . ' ' . $end_location . '%';

    return '-moz-linear-gradient('. $gradient .'); /* FF3.6+ */
           background: -webkit-linear-gradient('. $gradient .'); /* Chrome10+,Safari5.1+ */
           background: -o-linear-gradient('. $gradient .'); /* Opera 11.10+ */
           background: -ms-linear-gradient('. $gradient .'); /* IE10+ */
           background: linear-gradient('. $gradient2 .'); /* W3C */;';
}

function zoom_customizer_get_font_size($size)
{
    if ( is_array( $size ) && isset( $size['desktop'] ) ) {
        $size = $size['desktop'];
    }
    return ((float)$size) . 'px';
}

function zoom_customizer_get_letter_spacing($size)
{
    return ((float)$size) . 'px';
}

function zoom_customizer_opacity($opacity)
{
    $opacity = $opacity != '' ? $opacity : 0;
    return min( 100, max( 0, intval( $opacity ) ) ) / 100;
}

function zoom_customizer_display_element($value)
{
    return ($value == 1 || $value === 'on' || $value === '1' || $value === 'block') ? 'block' : 'none';
}

if ( ! function_exists('is_JSON') ) {
    function is_JSON( $string ){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('zoom_customizer_get_font_stack')) :
    /**
     * Validate the font choice and get a font stack for it.
     *
     * @since  1.0.0.
     *
     * @param  string $font The 1st font in the stack.
     * @return string             The full font stack.
     */
    function zoom_customizer_get_font_stack($font)
    {
        $fonts = zoom_customizer_get_all_fonts();
        $standard_fonts = $fonts['standard-fonts']['fonts'];
        $stack = $font;

        // Standard fonts
        foreach ($standard_fonts as $key => $val) {

            if ( $key == $font ) {

                // Sanitize font choice
                $font = zoom_customizer_sanitize_font_choice($font);
                $choices = zoom_customizer_all_font_choices();

                if ( isset( $standard_fonts[$font]['stack'] ) && !empty( $standard_fonts[$font]['stack'] ) ) {
                    $stack = $standard_fonts[$font]['stack'];
                }
                elseif ( in_array( $font, $choices ) ) {
                    $stack = '"' . $font . '","Helvetica Neue",Helvetica,Arial,sans-serif';
                }
                else {
                    $stack = '"Helvetica Neue",Helvetica,Arial,sans-serif';
                }

            }

        }

        /**
         * Allow developers to filter the full font stack.
         *
         * @param string $stack The font stack.
         * @param string $font The font.
         */
        return apply_filters('zoom_customizer_get_font_stack', $stack, $font);
    }
endif;

if (!function_exists('zoom_customizer_sanitize_font_choice')) :
    /**
     * Sanitize a font choice.
     *
     * @param  string $value The font choice.
     * @return string              The sanitized font choice.
     */
    function zoom_customizer_sanitize_font_choice($value)
    {

        $key_exists = false;
        $font_choices = zoom_customizer_all_font_choices();

        foreach ($font_choices as $key => $choice) {
            if ( array_key_exists($value, $choice) ) {
                $key_exists = true;
            }
        }

        if (!is_string($value)) {
            // The array key is not a string, so the chosen option is not a real choice
            return '';
        } else if ( $key_exists ) {
            return $value;
        } else {
            return '';
        }
    }
endif;

if (!function_exists('zoom_customizer_get_all_fonts')) :
    /**
     * Compile font options from different sources.
     *
     * @return array    All available fonts.
     */
    function zoom_customizer_get_all_fonts()
    {
        $heading1 = array(
            'standard-fonts' => array(
                'label'     => sprintf( '--- %s ---', __('Standard Fonts', 'wpzoom') ),
                'fonts'     => zoom_customizer_get_standard_fonts(),
                'preview'   => true // allow font preview
            )
        );

        $heading2 = array(
            'popular-google-fonts' => array(
                'label'     => sprintf( '--- %s ---', __('Popular Google Fonts', 'wpzoom') ),
                'fonts'     => zoom_customizer_get_popular_google_fonts(),
                'preview'   => true // allow font preview
            )
        );

        $heading3 = array(
            'google-fonts' => array(
                'label'     => sprintf( '--- %s ---', __('All Google Fonts', 'wpzoom') ),
                'fonts'     => zoom_customizer_get_google_fonts()
            )
        );

        /**
         * Allow for developers to modify the full list of fonts.
         *
         * @param array $fonts The list of all fonts.
         */
        return apply_filters( 'zoom_customizer_get_all_fonts', array_merge( $heading1, $heading2, $heading3 ) );
    }
endif;

if (!function_exists('zoom_customizer_get_standard_fonts')) :
    /**
     * Return an array of standard websafe fonts.
     *
     * @return array    Standard websafe fonts.
     */
    function zoom_customizer_get_standard_fonts()
    {
        $standard_font_families = array(
            'Arial' => array(
                'label' => _x('Arial', 'font style', 'wpzoom'),
                'stack' => '"Arial", "Helvetica Neue", Helvetica, sans-serif',
                'styles' => array( 'regular', 'italic', 'oblique' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Arial Black' => array(
                'label' => _x('Arial Black', 'font style', 'wpzoom'),
                'stack' => '"Arial Black", "Arial Bold", Gadget, sans-serif',
                'styles' => array( 'regular', 'italic', 'oblique' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Helvetica' => array(
                'label' => _x('Helvetica', 'font style', 'wpzoom'),
                'stack' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
                'styles' => array( 'regular', 'italic', 'oblique' ),
                'weights' => array( '100', 'regular', 'bold' ),
            ),
            'Georgia' => array(
                'label' => _x('Georgia', 'font style', 'wpzoom'),
                'stack' => 'Georgia, serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Times New Roman' => array(
                'label' => _x('Times New Roman', 'font style', 'wpzoom'),
                'stack' => 'TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Impact' => array(
                'label' => _x('Impact', 'font style', 'wpzoom'),
                'stack' => 'Impact, Haettenschweiler, "Franklin Gothic Bold", Charcoal, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", sans-serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'bold' ),
            ),
            'Tahoma' => array(
                'label' => _x('Tahoma', 'font style', 'wpzoom'),
                'stack' => 'Tahoma, Verdana, Segoe, sans-serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Verdana' => array(
                'label' => _x('Verdana', 'font style', 'wpzoom'),
                'stack' => 'Verdana, Geneva, sans-serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Trebuchet MS' => array(
                'label' => _x('Trebuchet MS', 'font style', 'wpzoom'),
                'stack' => '"Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Lucida Console' => array(
                'label' => _x('Lucida Console', 'font style', 'wpzoom'),
                'stack' => '"Lucida Console", "Lucida Sans Typewriter", monaco, "Bitstream Vera Sans Mono", monospace',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Courier New' => array(
                'label' => _x('Courier New', 'font style', 'wpzoom'),
                'stack' => '"Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Palatino' => array(
                'label' => _x('Palatino', 'font style', 'wpzoom'),
                'stack' => 'Palatino, "Palatino Linotype", "Palatino LT STD", "Book Antiqua", Georgia, serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Serif' => array(
                'label' => _x('Serif', 'font style', 'wpzoom'),
                'stack' => 'Georgia, Times, "Times New Roman", serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Sans Serif' => array(
                'label' => _x('Sans Serif', 'font style', 'wpzoom'),
                'stack' => '"Helvetica Neue", Helvetica,Arial, sans-serif',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            ),
            'Monospaced' => array(
                'label' => _x('Monospaced', 'font style', 'wpzoom'),
                'stack' => 'Monaco, "Lucida Sans Typewriter", "Lucida Typewriter", "Courier New", Courier, monospace',
                'styles' => array( 'regular', 'italic' ),
                'weights' => array( 'regular', 'bold' ),
            )
        );

        // sort array by key
        ksort( $standard_font_families );

        /**
         * Allow for developers to modify the standard fonts.
         *
         * @param array $fonts The list of standard fonts.
         */
        return apply_filters('zoom_customizer_get_standard_fonts', $standard_font_families );
    }
endif;


if (!function_exists('zoom_customizer_get_popular_google_fonts')) :
    /**
     * Return an array of popular google fonts fonts.
     *
     * @since 1.8.8
     * @return array    Standard websafe fonts.
     */
    function zoom_customizer_get_popular_google_fonts()
    {
        $google_fonts = zoom_customizer_get_google_fonts();

        /**
         * Allow for developers to modify the list of popular fonts.
         *
         * @param array The list of popular fonts.
         */
        $whitelist_popular_google_fonts = apply_filters('zoom_customizer_get_popular_google_fonts', array(
            'Open Sans',
            'Montserrat',
            'Roboto',
            'Lato',
            'Slabo 27px',
            'Oswald',
            'Source Sans Pro',
            'Raleway',
            'PT Sans',
            'Lora',
            'Noto Sans',
            'Nunito Sans',
            'Concert One',
            'Prompt',
            'Work Sans',
            'Lobster',
            'Merriweather',
            'Playfair Display'
        ) );

        $popular_google_fonts = array();
        foreach ( $google_fonts as $font_family => $font_options ) {
            if ( ! in_array( $font_family, $whitelist_popular_google_fonts ) )
                continue;
            $popular_google_fonts[ $font_family ] = $font_options;
        }

        return $popular_google_fonts;
    }
endif;


if ( ! function_exists( 'zoom_get_google_font_uri' ) ) :
    /**
     * Build the HTTP request URL for Google Fonts.
     *
     * @return string    The URL for including Google Fonts.
     */
    function zoom_get_google_font_uri( $data = array() ) {
        // Grab the font choices
        $data        = empty( $data ) ? apply_filters( 'wpzoom_customizer_data', array() ) : $data;
        $font_keys   = zoom_customizer_get_font_familiy_ids( $data );
        $subset_keys = zoom_customizer_get_font_familiy_ids( $data, 'font-subset' );
        $request     = '//fonts.googleapis.com/css';

        $fonts = array();
        foreach ( $font_keys as $key => $default ) {
            $fonts[] = get_theme_mod( $key, $default );
        }

        $subsets = array();
        foreach ( $subset_keys as $key => $default ) {
            $subsets[$key] = get_theme_mod( $key, $default );
        }

        // De-dupe the fonts
        $fonts              = array_unique( $fonts );
        $subsets            = array_unique( $subsets, SORT_REGULAR );
        $allowed_fonts      = zoom_customizer_get_google_fonts();
        $variants           = zoom_customizer_get_google_font_used_variants( $data, zoom_customizer_get_google_font_variants( $allowed_fonts ) );
        $subsets_available  = zoom_customizer_get_google_font_subsets();
        $families           = array();
        $families_subset    = array();

        foreach ($subsets as $key => $subset) {

            if ( is_array($subset) && ! empty($subset) ) {

                // Remove 'all'
                if ( in_array( 'all', $subset ) ) {
                    unset( $subset[0] );
                }

                $families_subset = array_unique( array_merge($families_subset, $subset) );
            }

            if ( is_string($subset) && isset( $subsets_available[ $subset ] ) ) {
                $families_subset = array_unique( array_merge( $families_subset, array($subset) ) );
            }
        }

        // Validate each font and convert to URL format
        foreach ( $fonts as $font ) {
            $font = trim( $font );

            // Verify that the font exists
            if ( array_key_exists( $font, $allowed_fonts ) ) {
                // Build the family name and variant string (e.g., "Open+Sans:regular,italic,700")
                $font_variants = zoom_customizer_choose_google_font_variants( $font, $variants[ $font ] );
                if ( ! empty( $font_variants ) ) {
                    $families[] = urlencode( $font . ':' . join( ',', $font_variants ) );
                } else {
                    $families[] = urlencode( $font );
                }
            }
        }

        // Convert from array to string
        if ( empty( $families ) ) {
            return '';
        } else {
            $request = add_query_arg( 'family', implode( '|', $families ), $request );
        }

        // Append the subset string
        if ( ! empty( $families_subset ) ) {
            $request = add_query_arg( 'subset', join( ',', $families_subset ), $request );
        }

        $request = add_query_arg('display', 'swap', $request);

        /**
         * Filter the Google Fonts URL.
         *
         * @since 1.2.3.
         *
         * @param string    $url    The URL to retrieve the Google Fonts.
         */
        return apply_filters( 'zoom_get_google_font_uri', $request );
    }
endif;


if (!function_exists('zoom_customizer_get_google_fonts')) :
    /**
     * Return an array of all available Google Fonts.
     *
     * @return array    All Google Fonts.
     */
    function zoom_customizer_get_google_fonts()
    {
        static $google_fonts = array();

        if (empty($google_fonts)) {
            $google_fonts = zoom_customizer_get_google_fonts_from_api();
        }

        return $google_fonts;
    }
endif;

if (!function_exists('zoom_customizer_get_google_font_variants')) :
    function zoom_customizer_get_google_font_variants( $fonts )
    {
        static $font_variants = array();

        if (empty($font_variants)) {
            foreach( $fonts as $key => $value ) {
                $font_variants[ $key ] = $value['variants'];
            }
        }

        return $font_variants;
    }
endif;

if ( !function_exists('zoom_customizer_get_selected_font_weight') ) {
    /**
     * Get selected font weight
     *
     * @since 1.8.5
     * @return array  Selected font weight merged into $output array
     */
    function zoom_customizer_get_selected_font_weight( $font_weight, $font_family_variants, $output = array() ) {
        // multiple font weight is selected
        if ( is_array( $font_weight ) ) {
            foreach ( $font_weight as $weight ) {
                $output = zoom_customizer_parse_font_weight_value( $weight, $font_family_variants, $output );
            }
        } else {
            $output = zoom_customizer_parse_font_weight_value( $font_weight, $font_family_variants, $output );
        }

        return $output;
    }
}

if ( !function_exists('zoom_customizer_get_selected_font_style') ) {
    /**
     * Get selected font style
     *
     * @since 1.8.5
     * @return array  Selected font style merged into $output array
     */
    function zoom_customizer_get_selected_font_style( $font_style, $font_weight, $font_family_variants, $output = array() ) {
        // If user selected `italic` style, we need to include font family variant for specified font weight like: 400i, 500i, 600i, 700i ...
        if ( 'italic' === $font_style )
        {
            // multiple font weight is selected
            if ( is_array( $font_weight ) ) {
                foreach ( $font_weight as $weight ) {
                    $output = zoom_customizer_parse_font_style_value( $font_style, $weight, $font_family_variants, $output );
                }
            } else {
                $output = zoom_customizer_parse_font_style_value( $font_style, $font_weight, $font_family_variants, $output );
            }
        } else {
            $font_style = 'regular';
        }

        // include font style if it's found in $font_family_variants but not in our $output array
        if ( in_array( $font_style, $font_family_variants ) && ! in_array( $font_style, $output ) )
        {
            $output = array_merge( $output, array( $font_style ) );
        }

        return $output;
    }
}

if ( !function_exists('zoom_customizer_parse_font_weight_value') ) {
    /**
     * Parse value for font weight
     *
     * @since 1.8.5
     * @return array  Font weight merged into $output array
     */
    function zoom_customizer_parse_font_weight_value( $font_weight, $font_family_variants, $output ) {
        if ( ! is_array( $output ) ) {
            $output = array();
        }
        
        if ( ! is_array( $font_family_variants ) ) {
            return $output;
        }

        if ( 'bold' === $font_weight ) {
            // for `bold` font weight include 700 font variant if it's available
            $font_weight = in_array( '700', $font_family_variants ) ? '700' : '';
        } elseif ( 'normal' === $font_weight ) {
            // for `normal` font weight include regular font variant
            $font_weight = 'regular';
        }

        if ( in_array( $font_weight, $font_family_variants ) && ! in_array( $font_weight, $output ) ) {
            $output = array_merge( $output, array( $font_weight ) );
        }

        return $output;
    }
}

if ( !function_exists('zoom_customizer_parse_font_style_value') ) {
    /**
     * Parse value for font style
     *
     * @since 1.8.5
     * @return array  Font style merged into $output array
     */
    function zoom_customizer_parse_font_style_value( $font_style, $font_weight, $font_family_variants, $output ) {
        if ( 'normal' === $font_weight )
        {
            $font_style = in_array( 'italic', $font_family_variants ) ? 'italic' : '';

            if ( ! empty( $font_style ) && ! in_array( $font_style, $output ) )
            {
                // fonst_style is `italic`
                $output = array_merge( $output, array( $font_style ) );
            }

            // we need to inlcude only `italic` variant if exist in $font_family_variants and remove regular from array
            if ( ! empty( $font_style ) && in_array( 'regular', $output ) )
            {
                $key_to_remove = array_search( 'regular', $output );
                unset( $output[ $key_to_remove ] );
            }
        }
        else
        {
            // check if we have `italic` style in $font_family_variants for selected font weight
            // ( e.g. if 600italic is found in $font_family_variants then include 600i variant )
            $font_style = in_array( $font_weight . 'italic', $font_family_variants ) ? $font_weight . 'i' : '';

            if ( ! empty( $font_style ) && ! in_array( $font_style, $output ) )
            {
                // fonst_style is `italic`
                $output = array_merge( $output, array( $font_style ) );
            }

            // include only `italic` style for the same font weight
            if ( ! empty( $font_style ) && in_array( $font_weight, $output) )
            {
                $key_to_remove = array_search( $font_weight, $output );
                unset($output[ $key_to_remove ]);
            }
        }

        return $output;
    }
}

if (!function_exists('zoom_customizer_get_google_font_used_variants')) :
    function zoom_customizer_get_google_font_used_variants( $data, $available_variants )
    {
        $output = array();
        $standard_fonts = zoom_customizer_get_standard_fonts();
        $is_standard_font = false;
        $is_customize_preview = is_customize_preview();

        foreach ( $data as $section_element )
        {
            $font_family = '';
            foreach ( $section_element['options'] as $mod_name => $option )
            {
                $rules = array('font-family', 'font-style', 'font-weight');

                if ( isset( $option['style']['rule'] ) && in_array( $option['style']['rule'], $rules ) )
                {
                    $rule = $option['style']['rule'];
                    $default = $option['setting']['default']; // option default value
                    $font_family_variants = isset( $available_variants[ $font_family ] ) ? $available_variants[ $font_family ] : array( 'regular' );

                    if ( 'font-family' === $rule )
                    {
                        $font_family = get_theme_mod( $mod_name, $default );
                        $output[ $font_family ] = isset( $output[ $font_family ] ) ? $output[ $font_family ] : array();

                        $is_standard_font = isset( $standard_fonts[ $font_family ] );

                        // Include all available variants if we are in Customizer Preview
                        if ( $is_customize_preview ) {

                            if ( $is_standard_font ) continue;

                            $output[ $font_family ] = $available_variants[ $font_family ];
                            continue;
                        }
                    }
                    elseif ( 'font-weight' === $rule )
                    {
                        $font_weight = get_theme_mod( $mod_name, $default );

                        if ( $is_standard_font ) {
                            $output[ $font_family ] = isset( $standard_fonts[ $font_family ]['weights'] ) ? $standard_fonts[ $font_family ]['weights'] : array( 'regular', 'bold' );
                        }

                        $output[ $font_family ] = zoom_customizer_get_selected_font_weight( $font_weight, $font_family_variants, $output[ $font_family ] );
                    }
                    elseif ( 'font-style' === $rule )
                    {
                        $font_style = get_theme_mod( $mod_name, $default );

                        if ( $is_standard_font ) {
                            $output[ $font_family ] = isset( $standard_fonts[ $font_family ]['styles'] ) ? $standard_fonts[ $font_family ]['styles'] : array( 'regular', 'italic' );
                        }

                        $output[ $font_family ] = zoom_customizer_get_selected_font_style( $font_style, $font_weight, $font_family_variants, $output[ $font_family ] );
                    }

                    // Only for Body Typography
                    // Include `italic` or `regular` style if not exists
                    if ( strpos( $mod_name, 'body' ) === 0 )
                    {
                        // check if we have available `italic` in font family variants
                        // then check if we don't have it in our output array
                        if ( in_array( 'italic', $font_family_variants ) && ! in_array( 'italic', $output[ $font_family ] ) )
                        {
                            foreach ( $font_family_variants as $variant )
                            {
                                if ( is_array( $font_weight ) )
                                {
                                    // check only numeric variants (skip 300italic, regular, italic, 500italic ...)
                                    if ( is_numeric($variant) && in_array( $variant, $output[ $font_family ] ) && ! in_array( $variant.'i', $output[ $font_family ] ) )
                                    {
                                        $output[ $font_family ] = array_merge( $output[ $font_family ], array( $variant.'i' ) );
                                    }
                                }
                                else
                                {
                                    if ( '700' == $variant && ! in_array( $variant, $output[ $font_family ] ) )
                                    {
                                        $output[$font_family] = array_merge( $output[ $font_family ], array( $variant, $variant.'i' ) );
                                    }
                                }
                            }

                            $output[ $font_family ] = array_merge( $output[ $font_family ], array( 'italic' ) );
                        }

                        // only check if we don't have `regular` in our output array
                        if ( ! in_array( 'regular', $output[ $font_family ] ) )
                        {
                            $output[ $font_family ] = array_merge( $output[ $font_family ], array( 'regular' ) );
                        }

                        if ( in_array( '700' , $font_family_variants ) && ! in_array( '700' , $output[ $font_family ] ) )
                        {
                            $output[ $font_family ] = array_merge( $output[ $font_family ], array( '700' ) );
                        }
                    }

                    if ( is_array( $output[ $font_family ] ) ) {
                        // Sort output values
                        sort( $output[ $font_family ] );
                    }
                }
            }
        }

        return $output;
    }
endif;

if (!function_exists('zoom_customizer_get_google_fonts_from_api')) :

    function zoom_customizer_get_google_fonts_from_api()
    {
        $api_url = apply_filters('zoom_customizer_google_fonts_api_url', 'https://www.googleapis.com/webfonts/v1/webfonts?key=');
        $api_key = apply_filters('zoom_customizer_google_fonts_api_key', 'AIzaSyALmRY1LOeH4eIRhrQ35yJPHHAye9ujPkA');
        static $transient = false;

        if (empty($transient)) {
            if (($transient = get_site_transient('zoom_customizer_google_fonts_json')) === false) {

                $response = wp_remote_get($api_url . $api_key);
                $transient = wp_remote_retrieve_body($response);

                if (
                    200 === wp_remote_retrieve_response_code( $response )
                    &&
                    ! is_wp_error( $transient ) && ! empty( $transient )
                ) {
                    $decoded_transient = json_decode( $transient, true );
                    if ( is_array( $decoded_transient ) && array_key_exists( 'items', $decoded_transient ) ) {
                        set_site_transient( 'zoom_customizer_google_fonts_json', $transient, MONTH_IN_SECONDS );
                    }
                } else {
                    $default_google_fonts = zoom_customizer_get_default_google_fonts_encoded_json();

                    if ( ! empty( $default_google_fonts['file_exists'] ) ) {
                        $transient = $default_google_fonts['encoded'];
                    }
                }
            }

            $transient = json_decode($transient, true);

            $collector = array();
            if(is_array($transient) && array_key_exists('items', $transient)) {
                foreach ($transient['items'] as $active) {
                    $collector[$active['family']] = array(
                        'label' => $active['family'],
                        'variants' => $active['variants'],
                        'subsets' => $active['subsets']
                    );
                }
            }

            $transient = $collector;
        }

        return apply_filters('zoom_customizer_get_google_fonts_from_api', $transient);
    }

endif;

if ( ! function_exists( 'zoom_customizer_get_default_google_fonts_encoded_json' ) ) :
    function zoom_customizer_get_default_google_fonts_encoded_json() {

        static $result = false;

        if ( false === $result ) {

            $result = array( 'file_exists' => false, 'encoded' => '' );

            $google_fonts_json = WPZOOM_INC . '/assets/fonts/google_fonts.json';

            if ( file_exists( $google_fonts_json ) ) {
                $result['file_exists'] = true;
                $result['encoded']     = file_get_contents( $google_fonts_json );
            }
        }

        return $result;

    }
endif;

function zoom_customizer_add_css_rule( $setting_id, $default, $css_rule )
{
    if ( ! isset( $css_rule['selector'] ) ) {
        return;
    }

    $db_value       = get_theme_mod( $setting_id, $default );
    $declarations   = zoom_customizer_alias_rules( $css_rule['rule'] );
    $values         = isset( $css_rule[ 'values' ] ) ? $css_rule[ 'values' ] : null;
    $value          = zoom_customizer_get_filtered_value( $css_rule['rule'], $db_value, $default, $values );
    $default        = zoom_customizer_get_filtered_value( $css_rule['rule'], $default, array(), $values );

    // if ( is_array( $value ) && is_array( $default ) ) {
    //     $diff = array_diff( $value, $default );

    //     if ( empty( $diff ) ) {
    //         return;
    //     }
    // }

    if ( ! is_array( $value ) && ! is_array( $default ) ) {
        if ( strtolower( $value ) === strtolower( $default ) ) {
            return;
        }
    }

    if ( is_string( $declarations ) ) {
        $declarations = array(
            $declarations => $value
        );
    }

    $css_data = array(
        'selectors' => (array)$css_rule['selector'],
        'declarations' => $declarations
    );

    if ( isset( $css_rule['media'] ) ) {
        if ( is_array( $css_rule['media'] ) ) {
            $css_data['media']['desktop'] = $css_rule['media']['desktop'];
            $css_data['media']['tablet'] = $css_rule['media']['tablet'];
            $css_data['media']['mobile'] = $css_rule['media']['mobile'];
        }
        elseif( is_string( $css_rule['media'] ) && ! empty( $css_rule['media'] ) ) {
            $css_data['media'] = $css_rule['media'];
        }
    }

    zoom_customizer_get_css()->add( $css_data );
}

function zoom_customizer_get_font_familiy_ids($data, $rule = 'font-family')
{
    $font_families = array();
    foreach ($data as $section_element) {
        foreach ($section_element['options'] as $key => $option) {
            if (!empty($option['style']['rule']) && $option['style']['rule'] == $rule) {
                array_push($font_families, $key);
                $font_families[$key] = $option['setting']['default'];
            }
        }
    }

    return $font_families;
}

if (!function_exists('zoom_customizer_choose_google_font_variants')) :
    /**
     * Given a font, chose the variants to load for the theme.
     *
     * Attempts to load regular, italic, and 700. If regular is not found, the first variant in the family is chosen. italic
     * and 700 are only loaded if found. No fallbacks are loaded for those fonts.
     *
     * @param  string $font The font to load variants for.
     * @param  array $variants The variants for the font.
     * @return array                  The chosen variants.
     */
    function zoom_customizer_choose_google_font_variants($font, $variants = array())
    {
        $chosen_variants = array();
        if (empty($variants)) {
            $fonts = zoom_customizer_get_google_fonts();

            if (array_key_exists($font, $fonts)) {
                $variants = $fonts[$font]['variants'];
            }
        }

        // If a "regular" variant is not found, get the first variant
        if (!in_array('regular', $variants)) {
            $chosen_variants[0] = $variants[0];
        } else {
            $chosen_variants[0] = 'regular';
        }

        $chosen_variants = array_unique( array_merge( $chosen_variants, $variants ) );

        // `regular` is default font families variant and if we have only "regular" in array, we can remove it
        $count_variants = count( $chosen_variants );
        if ( 1 === $count_variants && strpos( 'regular', $chosen_variants[0] ) === 0 ) {
            $chosen_variants = array();
        }

        /**
         * Allow developers to alter the font variant choice.
         *
         * @param array $variants The list of variants for a font.
         * @param string $font The font to load variants for.
         * @param array $variants The variants for the font.
         */
        return apply_filters('zoom_customizer_font_variants', $chosen_variants, $font, $variants);
    }
endif;

function zoom_customizer_normalize_options(&$customizer_data)
{
    foreach ($customizer_data as $section_id => &$section_data) {

        if (isset($section_data['options']) && !empty($section_data['options'])) {
            zoom_customizer_filter_options($section_data['options']);
        }

    }
}

function zoom_customizer_filter_options(&$options)
{
    foreach ($options as $key => $option) {
        if (array_key_exists('type', $option) && $option['type'] === 'typography') {
            unset($options[$key]);

            $typography_options = zoom_customizer_typography_callback($key, $option);
            $options = array_merge($options, $typography_options);
        }
    }
}

function zoom_customizer_typography_callback( $key, $option )
{
    $collector = array();

    static $cached_font_choices = array();
    static $defaults = array();

    if ( empty( $cached_font_choices ) ) {
        $cached_font_choices = zoom_customizer_all_font_choices();
    }

    if ( empty( $defaults ) ) {
        $defaults = array(
            'font-family' => array(
                'setting' => array(
                    'sanitize_callback' => 'zoom_customizer_sanitize_font_choice',
                    'transport' => 'postMessage',
                    'default' => ''
                ),
                'control' => array(
                    'label' => __('Font Family', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Select',
                )
            ),
            'font-family-sync-all' => array(
                'setting' => array(
                    'sanitize_callback' => 'absint',
                    'transport' => 'postMessage',
                    'default' => false
                ),
                'control' => array(
                    'label' => __('Sync all fonts', 'wpzoom'),
                    'description' => __('Force selected font family to all panels.', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Checkbox',
                ),
            ),
            'font-size' => array(
                'setting' => array(
                    'sanitize_callback' => 'absint',
                    'transport' => 'postMessage',
                    'default' => 18
                ),
                'control' => array(
                    'label' => __('Font Size', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Range',
                    'input_type' => 'number',
                    'input_attrs' => array(
                        'min'  => 10,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
            ),
            'font-style' => array(
                'setting' => array(
                    'transport' => 'postMessage',
                    'default' => 'normal'
                ),
                'control' => array(
                    'label' => __('Font Style', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Radio',
                    'mode' => 'buttonset',
                    'choices' => array(
                        'normal' => __('Normal', 'wpzoom'),
                        'italic' => __('Italic', 'wpzoom'),
                    )
                )
            ),
            'font-weight' => array(
                'setting' => array(
                    'transport' => 'postMessage',
                    'default' => 'normal'
                ),
                'control' => array(
                    'label' => __('Font Weight', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Radio',
                    'mode' => 'buttonset',
                    'choices' => array(
                        'normal' => __('Normal', 'wpzoom'),
                        'bold' => __('Bold', 'wpzoom'),
                        '100' => '100',
                        '200' => '200',
                        '300' => '300',
                        '400' => '400',
                        '500' => '500',
                        '600' => '600',
                        '700' => '700',
                        '800' => '800',
                        '900' => '900'
                    ),
                )
            ),
            'font-subset' => array(
                'ignore_selector' => true, // Igore from style selector
                'setting' => array(
                    'transport' => 'postMessage',
                    'default'   => 'latin',
                ),
                'control' => array(
                    'label' => __('Font Languages', 'wpzoom'),
                    'control_type'  => 'WPZOOM_Customizer_Control_Checkbox_Multiple',
                    'mode' => 'buttonset',
                    'choices' => zoom_customizer_get_google_font_subsets()
                )
            ),
            'text-transform' => array(
                'setting' => array(
                    'transport' => 'postMessage',
                    'default' => 'none'
                ),
                'control' => array(
                    'label' => __('Text Transform', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Radio',
                    'mode' => 'buttonset',
                    'choices' => array(
                        'none' => __('None', 'wpzoom'),
                        'capitalize' => __('Capitalize', 'wpzoom'),
                        'lowercase' => __('Lowercase', 'wpzoom'),
                        'uppercase' => __('Uppercase', 'wpzoom'),
                    )
                )
            ),
            'line-height' => array(
                'setting' => array(
                    'transport' => 'postMessage',
                    'default' => 1
                ),
                'control' => array(
                    'control_type' => 'WPZOOM_Customizer_Control_Range',
                    'label'   => __( 'Line Height (em)', 'wpzoom' ),
                    'input_attrs' => array(
                        'min'  => 0,
                        'max'  => 5,
                        'step' => 0.1,
                    ),
                ),
            ),
            'letter-spacing' => array(
                'setting' => array(
                    'sanitize_callback' => 'absint',
                    'transport' => 'postMessage',
                    'default' => 0
                ),
                'control' => array(
                    'label' => __('Letter Spacing (in px)', 'wpzoom'),
                    'control_type' => 'WPZOOM_Customizer_Control_Range',
                    'input_type' => 'number',
                    'input_attrs' => array(
                        'min'  => 0,
                        'max'  => 20,
                        'step' => 0.1,
                    ),
                ),
            ),
        );
    }

    foreach ($option['rules'] as $rule => $default) {
        if ( ! isset( $defaults[ $rule ] ) ) {
            continue;
        }

        $setting_id = $key . '-' . $rule;
        $is_responsive = false;
        $rpv_media = '';

        /**
         * Replace setting with Responsive Control
         * Add media queries
         * 
         * @since 1.8.6
         */
        if ( isset( $option[ $rule . '-responsive' ] ) ) {
            $is_responsive = true;

            $rpv_rule   = $rule . '-responsive';
            $responsive = $option[ $rpv_rule ];
            $label      = $defaults[ $rule ]['control']['label'];

            $value_desktop  = isset( $responsive['desktop'] ) ? $responsive['desktop'] : $default;
            $value_tablet   = isset( $responsive['tablet'] ) ? $responsive['tablet'] : $value_desktop;
            $value_mobile   = isset( $responsive['mobile'] ) ? $responsive['mobile'] : $value_tablet;
            $unit_desktop   = isset( $responsive['desktop-unit'] ) ? $responsive['desktop-unit'] : 'px';
            $unit_tablet    = isset( $responsive['tablet-unit'] ) ? $responsive['tablet-unit'] : 'px';
            $unit_mobile    = isset( $responsive['mobile-unit'] ) ? $responsive['mobile-unit'] : 'px';

            $rpv_media = apply_filters( 'wpzoom_customizer_custom_media_queries', array() );

            $defaults[ $rpv_rule ] = array(
                'setting' => array(
                    'transport' => 'postMessage',
                    'default' => array(
                        'desktop' => $value_desktop,
                        'tablet' => $value_tablet,
                        'mobile' => $value_mobile,
                        'desktop-unit' => $unit_desktop,
                        'tablet-unit'  => $unit_tablet,
                        'mobile-unit'  => $unit_mobile,
                    )
                ),
                'control' => array(
                    'label' => $label,
                    'control_type' => 'WPZOOM_Customizer_Control_Responsive',
                    'input_attrs' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'units' => array(
                        'px' => 'px',
                        'em' => 'em'
                    ),
                ),
                'style' => array( 'rule' => $rpv_rule )
            );

            $rule = $rpv_rule;
        }
        
        $collector[ $setting_id ] = array(
            'setting' => $defaults[ $rule ]['setting'],
            'control' => $defaults[ $rule ]['control'],
            'style'   => isset( $defaults[ $rule ]['style'] ) ? $defaults[ $rule ]['style'] : array( 'rule' => $rule ),
        );

        // Ignore rule selector
        if ( ! isset( $defaults[ $rule ]['ignore_selector'] ) || $defaults[ $rule ]['ignore_selector'] != true ) {
            $collector[ $setting_id ]['style']['selector'] = $option['selector'];
        }

        if ( ! empty( $option['media'] ) ) {
            $collector[ $setting_id ]['style']['media'] = $option['media'];
        }

        /**
         * Add responsive media screens
         * 
         * @since 1.8.6
         */
        if ( ! empty( $rpv_media ) ) {
            $collector[ $setting_id ]['style']['media'] = $rpv_media;
        }

        if ( ! $is_responsive ) {
            $collector[ $setting_id ]['setting']['default'] = $default;
        }
    }

    return $collector;
}

function zoom_customizer_add_css_rules( $rules )
{
    foreach ( $rules as $setting_id => $rule ) {

        if ( isset( $rule['style'] ) && is_array( current( $rule['style'] ) ) ) {
            foreach ( $rule['style'] as $subrule ) {
                zoom_customizer_add_css_rule( $setting_id, $rule['default'], $subrule );
            }
            continue;
        }

        if ( isset( $rule['style'] ) ) {
            zoom_customizer_add_css_rule( $setting_id, $rule['default'], $rule['style'] );
        }
    }
}

add_action('zoom_customizer_display_customization_css', 'zoom_customizer_add_css_rules');



function zoom_customizer_get_default_option_value( $option_id, $data = array() )
{
    $data  = empty( $data ) ? apply_filters( 'wpzoom_customizer_data', array() ) : $data;
    $value = false;
    foreach ($data as $section) {
        if (!empty($section['options'])) {
            foreach ($section['options'] as $key => $option) {
                if ($key == $option_id) {

                    // Check for default value
                    if ( isset( $option['setting']['default'] ) ) {
                        $value = $option['setting']['default'];
                    }
                }
            }
        }
    }
    return $value;
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function zoom_customizer_partial_blogname()
{
    //In future must remove it is for backward compatibility.
    if(get_theme_mod('logo')){
        set_theme_mod('custom_logo',  zoom_get_attachment_id_from_url(get_theme_mod('logo')));
        remove_theme_mod('logo');
    }

    has_custom_logo() ? the_zoom_custom_logo() : printf('<h1><a href="%s" title="%s">%s</a></h1>', home_url(), get_bloginfo('description'), get_bloginfo('name'));
}

/**
 * Render the blog copyright for the selective refresh partial.
 */
function zoom_customizer_partial_blogcopyright()
{
    echo get_option('blogcopyright', sprintf(__('Copyright &copy; %1$s %2$s', 'wpzoom'), date('Y'), get_bloginfo('name')));
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function zoom_customizer_partial_blogdescription()
{
    bloginfo('description');
}

if (!function_exists('zoom_customizer_get_google_font_subsets')) :
    /**
     * Retrieve the list of available Google font subsets.
     *
     * @since  1.0.0.
     *
     * @return array    The available subsets.
     */
    function zoom_customizer_get_google_font_subsets()
    {
        /**
         * Filter the list of supported Google Font subsets.
         *
         * @since 1.2.3.
         *
         * @param array $subsets The list of subsets.
         */
        return apply_filters('zoom_customizer_get_google_font_subsets', array(
            'all' => __('All', 'wpzoom'),
            'cyrillic' => __('Cyrillic', 'wpzoom'),
            'cyrillic-ext' => __('Cyrillic Extended', 'wpzoom'),
            'devanagari' => __('Devanagari', 'wpzoom'),
            'greek' => __('Greek', 'wpzoom'),
            'greek-ext' => __('Greek Extended', 'wpzoom'),
            'khmer' => __('Khmer', 'wpzoom'),
            'latin' => __('Latin', 'wpzoom'),
            'latin-ext' => __('Latin Extended', 'wpzoom'),
            'vietnamese' => __('Vietnamese', 'wpzoom'),
        ));
    }
endif;


/**
 * Style kits.
 */
function zoom_customizer_data_add_stylekits( $data )
{
    $stylekits = apply_filters('wpzoom_customizer_stylekits', array());
    if ( !empty( $stylekits ) ) {

        $kits = array();
        $styles = array();
        foreach ( $stylekits as $kit_name => $kit_data ) {
            $kits[$kit_name] = ucwords( str_replace( '_', ' ', $kit_name ) );
            $styles[$kit_name] = 'background-image:url("' . get_template_directory_uri() . '/functions/assets/image/stylekit-' . $kit_name . '.png");';
        }

        $options = array(
            'setting' => array(
                'default' => 'default',
                'sanitize_callback' => 'sanitize_text_field'
            ),
            'control' => array(
                'control_type' => 'WPZOOM_Customizer_Control_Radio',
                'label' => __('Choose a Style', 'wpzoom'),
                'description' => __('<strong>CAUTION:</strong><span>Choosing a style kit will overwrite previous customizer settings (fonts, colors, etc.)<br/><br/>Some styles may look glitchy in the Customizer, so make sure to refresh the page after selecting a style, or view the website outside the Customizer.</span>', 'wpzoom'),
                'mode' => 'buttonset',
                'choices' => $kits,
                'styles' => $styles
            )
        );

        $data['style-kits'] = array('title' => __('Style Kits', 'wpzoom'), 'priority' => 21, 'options' => array('style-kits-selector' => $options));
    }

    return $data;
}
add_filter('wpzoom_customizer_data_add_stylekits', 'zoom_customizer_data_add_stylekits');

function zoom_customizer_stylekits_refresh_nonces( $nonces )
{
    $nonces['wpzoom-customizer-stylekits'] = wp_create_nonce('wpzoom-customizer-stylekits');
    return $nonces;
}
add_filter('customize_refresh_nonces', 'zoom_customizer_stylekits_refresh_nonces');

function zoom_customizer_get_stylekit_data()
{
    global $wpdb;

    check_ajax_referer('wpzoom-customizer-stylekits', 'nonce');

    $stylekits = apply_filters('wpzoom_customizer_stylekits', array());
    $post = $_POST['stylekit'];

    if ( !current_user_can('edit_theme_options') || empty($stylekits) || !isset($post) || !isset($stylekits[ sanitize_text_field($post) ]) ) {
        wp_send_json_error(array('message' => __('Problem fetching style kit.', 'wpzoom')));
    }

    wp_send_json_success(array('success' => true, 'data' => $stylekits[ sanitize_text_field($post) ]));

    wp_die();
}
add_action('wp_ajax_wpz_customizer_get_stylekit_data', 'zoom_customizer_get_stylekit_data');



function zoom_customizer_custom_media_query( $media )
{
    $media_queries = array(
        'desktop' => 'screen and (min-width: 769px)',
        'tablet' => 'screen and (max-width: 768px)',
        'mobile' => 'screen and (max-width: 480px)'
    );

    if ( ! empty( $media ) ) {
        $media_queries = array_merge( $media_queries, $media );
    }

    return $media_queries;
}

add_filter( 'wpzoom_customizer_custom_media_queries', 'zoom_customizer_custom_media_query' );



/**
 * Customizer options.
 */
function zoom_customizer_options_defaults_refresh_nonces( $nonces )
{
    $nonces['wpzoom-customizer-options-defaults'] = wp_create_nonce('wpzoom-customizer-options-defaults');
    return $nonces;
}
add_filter('customize_refresh_nonces', 'zoom_customizer_options_defaults_refresh_nonces');

function zoom_customizer_get_options_defaults()
{
    check_ajax_referer('wpzoom-customizer-options-defaults', 'nonce');

    if ( !current_user_can('edit_theme_options') ) {
        wp_send_json_error(array('message' => __('Permission error.', 'wpzoom')));
    }

    wp_send_json_success(array('success' => true, 'data' => option::getCustomizerJsOptionsDefaults()));

    wp_die();
}
add_action('wp_ajax_wpz_customizer_get_options_defaults', 'zoom_customizer_get_options_defaults');



/**
 * Same as the core built-in function comments_popup_link() but returns the value rather than echos it.
 */
function get_comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
    ob_start();
    comments_popup_link( $zero, $one, $more, $css_class, $none );
    return ob_get_clean();
}

/**
 * Same as the core built-in function comments_template() but returns the value rather than echos it.
 */
function get_comments_template( $file = '/comments.php', $separate_comments = false ) {
    ob_start();
    comments_template( $file, $separate_comments );
    return ob_get_clean();
}

/**
 * Searches an array of arrays for a given key with a given value and returns the result, or false otherwise.
 */
function get_array_item_by_sub_key_value( $array, $key_name, $search_value ) {
    if ( is_array( $array ) && $key_name != '' && $search_value != '' ) {
        foreach ( $array as $item ) {
            if ( is_array( $item ) && isset( $item[ $key_name ] ) && $item[ $key_name ] == $search_value ) {
                return $item;
            }
        }
    }

    return false;
}