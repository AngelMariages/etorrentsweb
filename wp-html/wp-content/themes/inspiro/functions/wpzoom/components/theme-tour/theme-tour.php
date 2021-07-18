<?php
/**
 * Theme Activation Tour
 *
 * This class handles the pointers used in the introduction tour.
 *
 */

class WPZOOM_Theme_Tour {

    private $pointer_close_id = 'wpzoom_theme_tour'; //value can be cleared to retake tour

    /**
     * Class constructor.
     *
     * If user is on a pre pointer version bounce out.
     */
    function __construct() {
        global $wp_version;

        //pre 3.3 has no pointers
        if (version_compare($wp_version, '3.4', '<'))
            return false;

        // predefine pointer id if is multisite
        if ( is_multisite() ) {
            $demos = get_demos_details();

            $this->pointer_close_id = 'wpzoom_theme_tour_' . $demos['theme'];
        }

        //version is updated ::claps:: proceed
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    /**
     * Enqueue styles and scripts needed for the pointers.
     */
    function enqueue() {
        if (!current_user_can('manage_options'))
            return;

        // Assume pointer shouldn't be shown
        $enqueue_pointer_script_style = false;

        // Get array list of dismissed pointers for current user and convert it to array
        $dismissed_pointers = explode(',', get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));

        // Check if our pointer is not among dismissed ones
        if (!in_array($this->pointer_close_id, $dismissed_pointers)) {
            $enqueue_pointer_script_style = true;

            // Add footer scripts using callback function
            add_action('admin_print_footer_scripts', array($this, 'intro_tour'));
        }

        // Enqueue pointer CSS and JS files, if needed
        if ($enqueue_pointer_script_style) {
            wp_enqueue_style('wp-pointer');
            wp_enqueue_script('wp-pointer');
        }

    }


    /**
     * Load the introduction tour
     */
    function intro_tour() {
        $page = ''; $param = '';
        $screen = get_current_screen();
        $demos = get_demos_details();

        // Check for inactive required plugins
        $tgmpa = $GLOBALS['tgmpa'];
        $tgmpa_plugins = $tgmpa->plugins;
        $inactive_plugins = array();

        foreach ($tgmpa_plugins as $id => $plugin) {
            if ( ! is_plugin_active($plugin['file_path']) ) {
                $inactive_plugins[$id] = $plugin;
            }
        }

        $adminpages = array(

            //array name is the unique ID of the screen @see: http://codex.wordpress.org/Function_Reference/get_current_screen
            'wpzoom-license' => array(
                'content' => "<h3>" . sprintf(__('Welcome to %s!', 'wpzoom'), WPZOOM::$themeName) . "</h3>"
                    . "<p>" . sprintf(__('Congratulations! You have just installed %s. Please take the theme tour.', 'wpzoom'), WPZOOM::$themeName) . "</p>", //Content for this pointer
                'id' => 'toplevel_page_wpzoom_options', //ID of element where the pointer will point
                'position' => array(
                    'edge' => 'left', //Arrow position; change depending on where the element is located
                    'align' => 'center' //Alignment of Pointer
                ),
                'button2' => __('Next', 'wpzoom'), //text for the next button
                'function' => 'window.location="' . admin_url('admin.php?page=wpzoom_options') . '";' //where to take the user
            ),
            'wpzoom_options' => array(
                'content' => '<h3>' . __('Import Demo', 'wpzoom') . '</h3><p><strong>'. sprintf(__('Would you like to import the demo content to have the exact looks as our demo for %s theme', 'wpzoom'), WPZOOM::$themeName) .'</strong></p><p>' . sprintf( __('You can now import the demo content by clicking the button %s or from the <em>Demo Content</em> section here under Import/Export.', 'wpzoom'), '<i class="fa fa-download"></i>' ) . '</p>',
                'id' => 'wpz-demo-content-icon',
                'button2' => __('Next', 'wpzoom'),
                'function' => 'window.location="' . admin_url('admin.php?page=wpzoom_options&welcome_tour=1') . '";',
            ),
            'wpzoom_options_1' => array(
                'content' => '<h3>' . __('Personalize your Theme', 'wpzoom') . '</h3><p>' . __('Using the Live Customizer you can easily upload your logo image, change fonts, colors, widgets, menus and much more!', 'wpzoom') . ' <a href="'. admin_url('customize.php?return='. urlencode($_SERVER['REQUEST_URI'])) .'">'. __('Open Customizer', 'wpzoom') .'</a></p>',
                'id' => 'toplevel_page_wpzoom_options',
                'button2' => __('Next', 'wpzoom'),
                'function' => 'window.location="' . admin_url('admin.php?page=wpzoom_options&welcome_tour=2') . '";',
            ),
            'wpzoom_options_2' => array(
                'content' => '<h3>' . __('Need Help?', 'wpzoom') . '</h3><p><strong>'. __('Congratulations! Your theme is fully set up.', 'wpzoom') .'</strong></p><p>' . __('In case you need help with theme or have a question get in touch with our Support Team. We\'d love the opportunity to help you.', 'wpzoom') . '</p>',
                'id' => 'zoomInfo-support',
            ),
        );

        // Check for inactive plugins and show tooltip
        if ( ! empty($inactive_plugins) ) {
            $adminpages['wpzoom_options'] = array(
                'content' => '<h3>' . __('Install Recommended Plugins', 'wpzoom') . '</h3><p><strong>' . sprintf( __('We recommend you to install and/or activate required plugins for better experience with %s theme!', 'wpzoom'), WPZOOM::$themeName ) . '</strong></p>',
                'id' => 'setting-error-tgmpa',
                'position' => array(
                    'edge' => 'top',
                    'align' => 'left'
                ),
                'button2' => __('Next', 'wpzoom'),
                'function' => 'window.location="' . admin_url('admin.php?page=wpzoom_options&welcome_tour=1') . '";',
            );
        }

        // Check if user has been imported demo
        if ( $demos['imported'] ) {
            $adminpages['wpzoom_options'] = array(
                'content' => '<h3>' . __('Demo successfully imported', 'wpzoom') . '</h3><p><strong>'. sprintf(__('You have imported «%s» demo content for %s theme. Now we recommend to go to Next step.', 'wpzoom'), zoom_get_beauty_demo_title($demos['imported']), WPZOOM::$themeName) .'</strong></p>',
                'id' => 'wpz-demo-content-icon',
                'button2' => __('Next', 'wpzoom'),
                'function' => 'window.location="' . admin_url('admin.php?page=wpzoom_options&welcome_tour=1') . '";',
            );
        }


        //Check which page the user is on
        if ( isset($_GET['page']) ) {
            $page = $_GET['page'];
        }
        if ( empty($page) ) {
            $page = $screen->id;
        }

        if ( isset($_GET['welcome_tour']) ) {
            $param = $_GET['welcome_tour'];
        }
        if ( '' != $param ) {
            $page = $page.'_'.$param;
        }

        $function = '';
        $button2 = '';
        $opt_arr = array();

        //Location the pointer points
        if (!empty($adminpages[$page]['id'])) {
            $id = '#' . $adminpages[$page]['id'];
        } else {
            $id = '#' . $screen->id;
        }


        //Options array for pointer used to send to JS
        if ( '' != $page && in_array($page, array_keys($adminpages)) ) {

            $align = (is_rtl()) ? 'right' : 'left';

            $opt_arr = array(
                'content' => $adminpages[$page]['content'],
                'position' => array(
                    'edge' => (!empty($adminpages[$page]['position']['edge'])) ? $adminpages[$page]['position']['edge'] : 'left',
                    'align' => (!empty($adminpages[$page]['position']['align'])) ? $adminpages[$page]['position']['align'] : $align
                ),
                'pointerWidth' => 400
            );
            if (isset($adminpages[$page]['button2'])) {
                $button2 = (!empty($adminpages[$page]['button2'])) ? $adminpages[$page]['button2'] : __('Next', 'wpzoom');
            }
            if (isset($adminpages[$page]['function'])) {
                $function = $adminpages[$page]['function'];
            }

        }

        if ( ! empty($opt_arr) ) {
            $this->print_scripts($id, $opt_arr, __("Close", 'wpzoom'), $button2, $function);
        }
    }


    /**
     * Prints the pointer script
     *
     * @param string $selector The CSS selector the pointer is attached to.
     * @param array $options The options for the pointer.
     * @param string $button1 Text for button 1
     * @param string|bool $button2 Text for button 2 (or false to not show it, defaults to false)
     * @param string $button2_function The JavaScript function to attach to button 2
     * @param string $button1_function The JavaScript function to attach to button 1
     */
    function print_scripts($selector, $options, $button1, $button2 = false, $button2_function = '', $button1_function = '') {
        ?>
        <script type="text/javascript">
            //<![CDATA[
            (function ($) {

                var wordimpress_pointer_options = <?php echo json_encode( $options ); ?>, setup;

                //Userful info here
                wordimpress_pointer_options = $.extend(wordimpress_pointer_options, {
                    buttons: function (event, t) {
                        button = jQuery('<a id="pointer-close" style="margin-left:5px" class="button-secondary">' + '<?php echo esc_html( $button1 ); ?>' + '</a>');
                        button.bind('click.pointer', function () {
                            t.element.pointer('close');
                        });
                        return button;
                    }
                });

                setup = function () {
                    $('<?php echo esc_attr($selector); ?>').pointer(wordimpress_pointer_options).pointer('open');
                    <?php
                    if ( $button2 ) { ?>
                    jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php echo esc_html($button2); ?>' + '</a>');
                    <?php } ?>
                    jQuery('#pointer-primary').click(function () {
                        <?php echo $button2_function; ?>
                    });
                    jQuery('#pointer-close').click(function () {
                        <?php if ( $button1_function == '' ) { ?>
                        $.post(ajaxurl, {
                            pointer: '<?php echo esc_attr($this->pointer_close_id); ?>', // pointer ID
                            action: 'dismiss-wp-pointer'
                        });

                        <?php } else { ?>
                        <?php echo $button1_function; ?>
                        <?php } ?>
                    });

                };

                if (wordimpress_pointer_options.position && wordimpress_pointer_options.position.defer_loading) {
                    $(window).bind('load.wp-pointers', setup);
                } else {

                    $(document).ready(setup);
                }

            })(jQuery);
            //]]>
        </script>
    <?php
    }
}

$wordimpress_theme_tour = new WPZOOM_Theme_Tour();