<?php

if (!class_exists('Zoom_Customizer_Parser')) :
    /**
     * Singleton to collect and print CSS based on user input.
     *
     * This class provides a mechanism to gather all of the CSS needed to implement theme options. It allows for handling
     * of conflicting rules and sorts out what the final CSS should be. The primary function is `add()`. It allows the
     * caller to add a new rule to be generated in the CSS.
     */
    class Zoom_Customizer_Parser
    {

        /**
         * The one instance of Zoom_Customizer_Parser.
         *
         * @var   Zoom_Customizer_Parser    The one instance for the singleton.
         */
        private static $instance;

        /**
         * The array for storing added CSS rule data.
         *
         * @var   array    Holds the data to be printed out.
         */

        public $data = array();

        /**
         * Optional line ending character for debug mode.
         *
         * @var   string    Line ending character used to better style the CSS.
         */
        private $line_ending = '';

        /**
         * Optional tab character for debug mode.
         *
         * @var   string    Line ending character used to better style the CSS.
         */
        private $tab = '';

        /**
         * Instantiate or return the one Zoom_Customizer_Parser instance.
         *
         * @return Zoom_Customizer_Parser
         */
        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Initialize the object.
         */
        function __construct()
        {
            // Set line ending and tab
            if (defined('SCRIPT_DEBUG') && true === SCRIPT_DEBUG) {
                $this->line_ending = "\n";
                $this->tab = "\t";
            }
        }

        /**
         * Add a new CSS rule to the array.
         *
         * Accepts data to eventually be turned into CSS. Usage:
         *
         * zoom_customizer_get_css()->add( array(
         *     'selectors'    => array( '.site-header-main' ),
         *     'declarations' => array(
         *         'background-color' => $header_background_color
         *     ),
         *     'media' => 'print',
         * ) );
         *
         * Selectors represent the CSS selectors; declarations are the CSS properties and values with keys being properties
         * and values being values. 'media' can also be declared to specify the media query.
         *
         * Note that data *must* be sanitized when adding to the data array. Because every piece of CSS data has special
         * sanitization concerns, it must be handled at the time of addition, not at the time of output. The theme handles
         * this in the the other helper files, i.e., the data is already sanitized when `add()` is called.
         *
         * @param  array $data The selectors and properties to add to the CSS.
         * @return void
         */
        public function add( $data )
        {
            if ( ! isset( $data['selectors'] ) || ! isset( $data['declarations'] ) ) {
                return;
            }

            $entry = array();

            /**
             * Filter CSS as it is registered.
             *
             * @since 1.2.3
             *
             * @param array $data The selectors and properties to add to the CSS.
             */
            $data = apply_filters('zoom_customizer_css_add', $data);

            // Sanitize selectors
            $entry['selectors'] = array_map('trim', (array)$data['selectors']);
            $entry['selectors'] = array_unique($entry['selectors']);

            // Sanitize declarations
            if ( isset( $data['media']['desktop'] ) ) {
                $entry['declarations'] = $data['declarations'];
            } else {
                $entry['declarations'] = array_map('trim', (array)$data['declarations']);
            }

            /**
             * Check if we have responsive media queries (desktop, tablet, mobile) in $data array
             * 
             * @since 1.8.6
             */
            if ( isset( $data['media'] ) ) {
                if ( is_array( $data['media'] ) ) {
                    foreach ( $data['media'] as $media_type => $media ) {
                        $this->set_data_media_query( $entry, $media );
                    }

                    return;

                } else {
                    $media = $data['media'];
                }
            } else {
                $media = 'all';
            }

            $this->set_data_media_query( $entry, $media );
        }

        /**
         * Compile the data array into standard CSS syntax
         *
         * @return string    The CSS that is built from the data.
         */
        public function build()
        {
            if ( empty( $this->data ) ) {
                return '';
            }

            $n = $this->line_ending;

            // Make sure the 'all' array is first
            if ( isset( $this->data['all'] ) && count( $this->data ) > 1 ) {
                $all = array( 'all' => $this->data['all'] );
                unset( $this->data['all'] );
                $this->data = array_merge( $all, $this->data );
            }

            $output = '';

            foreach ( $this->data as $query => $ruleset ) {
                $t = '';

                if ( 'all' !== $query ) {
                    $output .= "\n@media " . $query . '{' . $n;
                    $t = $this->tab;
                }

                // Build each rule
                foreach ( $ruleset as $rule ) {

                    $media_queries = apply_filters( 'wpzoom_customizer_custom_media_queries', array() );
                    $media_type = array_search( $query , $media_queries );

                    $output .= $this->parse_selectors( $rule['selectors'], $t ) . '{' . $n;
                    $output .= $this->parse_declarations( $rule['declarations'], $t, $media_type );
                    $output .= $t . '}' . $n;
                }

                if ('all' !== $query) {
                    $output .= '}' . $n;
                }
            }

            return $output;
        }

        /**
         * Compile the selectors in a rule into a string.
         *
         * @param  array $selectors Selectors to combine into single selector.
         * @param  string $tab Tab character.
         * @return string                  Results of the selector combination.
         */
        private function parse_selectors($selectors, $tab = '')
        {
            /**
             * Note that these selectors are hardcoded in the code base. They are never the result of user input and can
             * thus be trusted to be sane.
             */
            $n = $this->line_ending;
            $output = $tab . implode(",{$n}{$tab}", $selectors);
            return $output;
        }

        /**
         * Compile the declarations in a rule into a string.
         *
         * @param  array $declarations  Declarations for a selector.
         * @param  string $tab          Tab character.
         * @param  string $media_type   For which media screen add values.
         * @return string               The combines declarations.
         */
        private function parse_declarations( $declarations, $tab = '', $media_type = 'all' )
        {
            $n = $this->line_ending;
            $t = $this->tab . $tab;

            $output = '';

            /**
             * Note that when this output is prepared, it is not escaped, sanitized or otherwise altered. The sanitization
             * routines are implemented when the developer calls `zoom_customizer_get_css->add()`. Because every property value has
             * special sanitization needs, it is handled at that point.
             */
            foreach ( $declarations as $property => $value ) {
                // Exception for px/rem font size
                if ('font-size-px' === $property || 'font-size-rem' === $property) {
                    $property = 'font-size';
                }

                $media_type = !is_bool( $media_type ) ? $media_type : 'all';
                $parsed_value = '';

                if ( 'font-family' !== $property ) {
                    if ( isset( $value[ $media_type ] ) ) {
                        $val = $value[ $media_type ] . $value[ $media_type . '-unit' ];
                        $parsed_value = "{$t}{$property}:{$val};$n";
                    }
                    if ( is_string( $value ) ) {
                        $parsed_value = "{$t}{$property}:{$value};$n";
                    }
                }
                else {
                    $has_quotes = preg_match( '/"/', $value );

                    if ( $has_quotes ) {
                        $parsed_value = "{$t}{$property}:{$value};$n";
                    } else {
                        $parsed_value = "{$t}{$property}:\"{$value}\";$n";
                    }
                }

                /**
                 * Filter the final CSS declaration after being parsed.
                 *
                 * @since 1.2.3.
                 *
                 * @param string $parsed_value The full CSS declaration.
                 * @param string $property The property being parsed.
                 * @param string $value The value for the property.
                 * @param string $t The tab character.
                 * @param string $n The newline character.
                 */
                $output .= apply_filters('zoom_customizer_parse_declaration', $parsed_value, $property, $value, $t, $n);
            }

            /**
             * Filter the full list of parsed declarations.
             *
             * @param string $output The full CSS output.
             * @param array $declarations The list of CSS declarations.
             * @param string $tab The tab character.
             */
            return apply_filters('zoom_customizer_css_parse_declarations', $output, $declarations, $tab);
        }


        /**
         * Set new media query in data array
         * 
         * @since 1.8.6
         * 
         * @param array $entry
         * @param array|string $media
         * @return boolean
         */
        public function set_data_media_query( $entry, $media = 'all' )
        {
            // Create new media query if it doesn't exist yet
            if ( ! isset( $this->data[ $media ] ) || ! is_array( $this->data[ $media ] ) ) {
                $this->data[ $media ] = array();
            }
            // Look for matching selector sets
            $match = false;
            foreach ( $this->data[ $media ] as $key => $rule ) {
                $diff1 = array_diff( $rule['selectors'], $entry['selectors'] );
                $diff2 = array_diff( $entry['selectors'], $rule['selectors'] );

                if ( empty( $diff1 ) && empty( $diff2 ) ) {
                    $match = $key;
                    break;
                }
            }

            // No matching selector set, add a new entry
            if ( false === $match ) {
                $this->data[ $media ][] = $entry;
            } // Yes, matching selector set, merge declarations
            else {
                $this->data[ $media ][ $match ]['declarations'] = array_merge( $this->data[ $media ][ $match ]['declarations'], $entry['declarations']);
            }

            return true;
        }
    }
endif;

if (!function_exists('zoom_customizer_get_css')) :
    /**
     * Return the one Zoom_Customizer_Parser object.
     *
     * @return Zoom_Customizer_Parser    The one Zoom_Customizer_Parser object.
     */
    function zoom_customizer_get_css()
    {
        return Zoom_Customizer_Parser::instance();
    }
endif;

