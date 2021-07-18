<?php

class WPZOOM_Theme {
    private static $dynamic_google_webfonts = array();

    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'add_feed_links'));

        if (option::is_on("meta_generator")) {
            add_action('wp_head', array(__CLASS__, 'meta_generator'));
        }

        add_action('wp_title', array(__CLASS__, 'wp_title'), 10, 2);

        add_action('wp_head', array(__CLASS__, 'favicon'));
        add_action('wp_head', array(__CLASS__, 'generate_options_css'));
        add_action('wp_head', array(__CLASS__, 'header_code'));

        add_action('wp_enqueue_scripts', array(__CLASS__, 'theme_styles'), 20);
        add_action('wp_enqueue_scripts', array(__CLASS__, 'theme_scripts'));

        add_action('wp_footer', array(__CLASS__, 'footer_code'));
    }

    /**
     * Shows favicon if it's set in theme options and isn't set in WordPress as Site Icon
     * https://core.trac.wordpress.org/ticket/16434
     */
    public static function favicon() {
        if (get_option( 'site_icon', false) === false) {
            $favicon = option::get('misc_favicon');

            if ($favicon) {
                echo '<link rel="shortcut icon" href="' . $favicon . '" type="image/x-icon" />';
            }
        }
    }

    /**
     * Includes header/footer scripts if they are set in theme options
     */
    public static function header_code() {
        $header_code = trim(stripslashes(option::get('header_code')));

        if ($header_code) {
            echo stripslashes(option::get('header_code'));
        }
    }

    public static function footer_code() {
        $footer_code = trim(stripslashes(option::get('footer_code')));

        if ($footer_code) {
            echo stripslashes(option::get('footer_code'));
        }
    }

    public static function add_feed_links() {
        global $wpz_default_feed;
        $wpz_default_feed = get_feed_link();

        add_theme_support('automatic-feed-links');
        add_filter('feed_link', array(__CLASS__, 'custom_feed_links'), 1);
    }

    public static function custom_feed_links($feed) {
        global $wpz_default_feed;
        $custom_feed = esc_attr(trim(option::get('misc_feedburner')));

        if ($feed == $wpz_default_feed && $custom_feed) {
            return $custom_feed;
        }

        return $feed;
    }

    /**
     * Create a nicely formatted and more specific title element text for output
     * in head of document, based on current view.
     *
     * Won't do anything if title-tag support is declared.
     *
     * @deprecated Use `add_theme_support( 'title-tag )` instead
     */
    public static function wp_title($title, $sep) {
        if (current_theme_supports('title-tag')) {
            return $title;
        }

        if (is_feed()) {
            return $title;
        }

        // Add the site name
        $title = get_bloginfo('name', 'display') . $title;

        // Add the site description for the home/front page
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title = "$title $sep $site_description";
        }

        return $title;
    }

    /**
     * Adds WPZOOM to html meta generator
     *
     * @return void
     */
    public static function meta_generator() {
        $mg = "<!-- WPZOOM Theme / Framework -->\n";
        $mg.= '<meta name="generator" content="' . WPZOOM::$themeName . ' ' . WPZOOM::$themeVersion . '" />' . "\n";
        $mg.= '<meta name="generator" content="WPZOOM Framework ' . WPZOOM::$wpzoomVersion . '" />' . "\n";

        echo $mg;
    }

    /**
     * Include css file for specified style
     */
    public static function theme_styles() {
        /**
         * If current theme supports styles use them
         */
        if (option::get('theme_style')) {
            $style = str_replace(" ", "-", strtolower(option::get('theme_style')));

            if (file_exists(get_template_directory() . '/styles/' . $style . '.css')) {
                wp_register_style('wpzoom-theme', get_template_directory_uri() . '/styles/' . $style . '.css');
                wp_enqueue_style('wpzoom-theme');
            }
        }

        /**
         * Deprecated file, but we still register this stylesheet for
         * backwards comptability.
         */
        if (file_exists(get_template_directory() . '/custom.css')) {
            wp_register_style('wpzoom-custom', get_template_directory_uri() . '/custom.css', array(), WPZOOM::$themeVersion);
            wp_enqueue_style('wpzoom-custom');
        }

        if (file_exists(get_template_directory() . '/css/custom.css')) {
            wp_register_style('theme-custom', get_template_directory_uri() . '/css/custom.css', array(), WPZOOM::$themeVersion);
            wp_enqueue_style('theme-custom');
        }
    }

    public static function theme_scripts() {
        if (is_singular()) {
            wp_enqueue_script('comment-reply');
        }

        /**
         * Enqueue initialization script, HTML5 Shim included.
         *
         * Only if this file exists.
         */
        if (file_exists(get_template_directory() . '/js/init.js')) {
            wp_enqueue_script('wpzoom-init',  get_template_directory_uri() . '/js/init.js', array('jquery'));
        }

        /**
         * Enqueue all theme scripts specified in config file to the footer
         */
        if (isset(WPZOOM::$config['scripts'])) {
            foreach (WPZOOM::$config['scripts'] as $script) {
                wp_enqueue_script('wpzoom-' . $script,  get_template_directory_uri() . '/js/' . $script . '.js', array(), false, true);
            }
        }
    }

    /**
     * Generate custom css from options
     */
    public static function generate_options_css() {
        $css = '';
        $enable = false;
        foreach (option::$evoOptions as $Eoption) {
            foreach ($Eoption as $option) {
                if ((isset($option['type']) && $option['type'] == 'color') || isset($option['css'])) {
                    $value = option::get($option['id']);
                    if (!trim($value) != "") continue;
                    $enable = true;

                    if (in_array($option['attr'], array('height', 'width')) &&
                        strpos($value, 'px') === false) {
                        $value = $value . 'px';
                    }

                    $css .= $option['selector'] . '{' . $option['attr'] . ':' . $value . ";}\n";
                }

                if ((isset($option['type']) && $option['type'] == 'typography')) {
                    $enable = true;
                    $css .= self::dynamic_typography_css($option);
                }
            }
        }

        if ($enable) {
            echo '<style type="text/css">';
            echo self::dynamic_google_webfonts_css();
            echo $css;
            echo "</style>\n";
        }
    }

    /**
     * Registers Google Web Fonts in use so later we know what fonts
     * to include from Web Fonts directory
     *
     * @param  array $font Font data
     * @return void
     */
    public static function dynamic_google_webfonts_register($font) {
        self::$dynamic_google_webfonts[] = $font;
    }

    /**
     * Generates CSS import for used Google Web Fonts
     *
     * @return string The CSS Import String
     */
    public static function dynamic_google_webfonts_css() {
        $fonts = '';

        foreach (self::$dynamic_google_webfonts as $font) {
            $fonts.= $font['name'] . $font['variant'] . '|';
        }

        if (!$fonts) return '';

        $fonts = str_replace( " ","+",$fonts);
        $css = '@import url("http'. (is_ssl() ? 's' : '') .'://fonts.googleapis.com/css?family=' . $fonts . "\");\n";
        $css = str_replace('|"', '"', $css);

        return $css;
    }

    /**
     * Generates CSS for typography options from ZOOM Admin
     *
     * @param  array $option
     * @return string The CSS
     */
    public static function dynamic_typography_css($option) {
        $value = option::get($option['id']);

        if (!is_array($value)) return '';

        $font = array();

        if (isset($value['font-color']) && trim($value['font-color'])) {
            $font[] = "color: " . $value['font-color'] . ";";
        }

        if (isset($value['font-family']) && trim($value['font-family'])) {
            $font_families = ui::recognized_font_families($option['id']);
            $google_font_families = ui::recognized_google_webfonts_families($option['id']);

            if (array_key_exists($value['font-family'], $font_families)) {
                $font[] = "font-family: " . $font_families[$value['font-family']] . ";";
            }

            foreach ($google_font_families as $google_font_v) {
                if (isset($google_font_v['separator'])) continue;

                $key = str_replace(' ', '-', strtolower($google_font_v['name']));

                if ($value['font-family'] == $key) {
                    $font[] = "font-family: '" . $google_font_v['name'] . "';";
                    self::dynamic_google_webfonts_register($google_font_v);

                    break;
                }
            }
        }

        if (isset($value['font-size']) && trim($value['font-size'])) {
            $font[] = "font-size: " . $value['font-size'] . ";";
        }

        if (isset($value['font-style']) && trim($value['font-style'])) {
            if ($value['font-style'] == 'bold-italic') {
                $font[] = "font-style: italic;";
                $font[] = "font-weight: bold;";
            } elseif ($value['font-style'] == 'bold') {
                $font[] = "font-weight: bold;";
            } else {
                $font[] = "font-style: " . $value['font-style'] . ";";
            }
        }

        if (empty($font)) return '';

        return $option['selector'] . '{' . implode('', $font) . '}';
    }
}
