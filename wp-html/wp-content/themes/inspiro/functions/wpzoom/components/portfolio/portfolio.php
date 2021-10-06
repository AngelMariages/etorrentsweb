<?php

require_once sprintf( '%s/walker-category-filter.php', dirname( __FILE__ ) );

class ZOOM_Portfolio {
    public function __construct() {
        add_filter( 'post_type_link', array( $this, 'post_type_link' ), 10, 2 );

        // add_filter( 'archive_template', array( $this, 'locate_template_archive' ) );
        add_filter( 'single_template', array( $this, 'locate_template_single' ) );
        add_filter( 'taxonomy_template', array( $this, 'locate_template_taxonomy' ) );

        add_filter( 'zoom_option_files', array ( $this, 'register_options' ) );
        add_filter( 'zoom_field_save_portfolio_root', array ( $this, 'save_portfolio_root' ) );

        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );

        add_action( 'admin_head', array( $this, 'admin_head_style' ) );
        add_action( 'admin_menu', array( $this, 'register_meta_boxes_options' ) );

        add_action( 'save_post', array( $this, 'save_post' ) );
    }

    /**
     * Register post types for portfolio component.
     *
     * @return void
     */
    public function register_post_types() {
        $args = array(
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 12,
             'menu_icon'          => 'dashicons-portfolio',
            'can_export'          => true,
            'delete_with_user'    => false,
            'hierarchical'        => false,
            // 'has_archive'         => option::get( 'portfolio_root' ),
            'query_var'           => 'portfolio_item',
            'show_in_rest'        => true,

            /* The rewrite handles the URL structure. */
            'rewrite' => array(
                'slug'       => trim( option::get( 'portfolio_root' ) ),
                'with_front' => false,
                'pages'      => true,
                'feeds'      => true,
                'ep_mask'    => EP_PERMALINK,
            ),

            /* What features the post type supports. */
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'author',
                'thumbnail',
                'comments'
            ),

            /* Labels used when displaying the posts. */
            'labels' => array(
                'name'               => __( 'Portfolio Items',                   'wpzoom' ),
                'singular_name'      => __( 'Portfolio Item',                    'wpzoom' ),
                'menu_name'          => __( 'Portfolio',                         'wpzoom' ),
                'name_admin_bar'     => __( 'Portfolio Item',                    'wpzoom' ),
                'add_new'            => __( 'Add New',                           'wpzoom' ),
                'add_new_item'       => __( 'Add New Portfolio Item',            'wpzoom' ),
                'edit_item'          => __( 'Edit Portfolio Item',               'wpzoom' ),
                'new_item'           => __( 'New Portfolio Item',                'wpzoom' ),
                'view_item'          => __( 'View Portfolio Item',               'wpzoom' ),
                'search_items'       => __( 'Search Portfolio',                  'wpzoom' ),
                'not_found'          => __( 'No portfolio items found',          'wpzoom' ),
                'not_found_in_trash' => __( 'No portfolio items found in trash', 'wpzoom' ),
                'all_items'          => __( 'Portfolio Items',                   'wpzoom' ),

                // Custom labels b/c WordPress doesn't have anything to handle this.
                'archive_title'      => __( 'Portfolio',                         'wpzoom' ),
            )
        );

        /* Register the portfolio post type */
        register_post_type( 'portfolio_item', $args );
    }

    /**
     * Register taxonomies for portfolio component.
     *
     * @return void
     */
    public function register_taxonomies() {
        /* Set up the arguments for the portfolio taxonomy. */
        $args = array(
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_admin_column' => true,
            'hierarchical'      => true,
            'query_var'         => 'portfolio',
            'show_in_rest'      => true,

            /* The rewrite handles the URL structure. */
            'rewrite' => array(
                'slug'       => trim( option::get( 'portfolio_base' ) )
                                    ? option::get( 'portfolio_base' )
                                    : 'portfolio',
                'with_front'   => false,
                'hierarchical' => false,
                'ep_mask'      => EP_NONE
            ),

            /* Labels used when displaying taxonomy and terms. */
            'labels' => array(
                'name'                       => __( 'Portfolios',                           'wpzoom' ),
                'singular_name'              => __( 'Portfolio',                            'wpzoom' ),
                'menu_name'                  => __( 'Portfolios',                           'wpzoom' ),
                'name_admin_bar'             => __( 'Portfolio',                            'wpzoom' ),
                'search_items'               => __( 'Search Portfolios',                    'wpzoom' ),
                'popular_items'              => __( 'Popular Portfolios',                   'wpzoom' ),
                'all_items'                  => __( 'All Portfolios',                       'wpzoom' ),
                'edit_item'                  => __( 'Edit Portfolio',                       'wpzoom' ),
                'view_item'                  => __( 'View Portfolio',                       'wpzoom' ),
                'update_item'                => __( 'Update Portfolio',                     'wpzoom' ),
                'add_new_item'               => __( 'Add New Portfolio',                    'wpzoom' ),
                'new_item_name'              => __( 'New Portfolio Name',                   'wpzoom' ),
                'separate_items_with_commas' => __( 'Separate portfolios with commas',      'wpzoom' ),
                'add_or_remove_items'        => __( 'Add or remove portfolios',             'wpzoom' ),
                'choose_from_most_used'      => __( 'Choose from the most used portfolios', 'wpzoom' ),
            )
        );

        /* Register the 'portfolio' taxonomy. */
        register_taxonomy( 'portfolio', array( 'portfolio_item' ), $args );
    }

    public function register_meta_boxes_options() {

    }

    /**
     * Hook for saving custom fields for portfolio_item post type.
     *
     * @param  int $post_id The ID of the post which contains the field you will edit.
     * @return void
     */
    public function save_post( $post_id ) {
        // called after a post or page is saved
        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }

        if ( ! isset( $_POST['save'] ) && ! isset( $_POST['publish'] ) ) return;

        if ( $_POST['post_type'] !== 'portfolio_item' ) return;
    }

    /**
     * Filter on 'post_type_link' to allow users to use '%portfolio' in their
     * portfolio items URLs.
     *
     * @param  string $post_link
     * @param  object $post
     * @return string
     */
    public function post_type_link( $post_link, $post ) {
        if ( $post->post_type !== 'portfolio_item' ) {
            return $post_link;
        }

        if ( strpos( $post_link, '%portfolio%' ) !== false ) {
            $terms = get_the_terms( $post, 'portfolio' );
            if ( $terms ) {
                usort( $terms, '_usort_terms_by_ID' );
                $post_link = str_replace( '%portfolio%', $terms[0]->slug, $post_link );
            } else {
                $post_link = str_replace( '%portfolio%', 'item', $post_link );
            }
        }

        return $post_link;
    }

    // /**
    //  * Filter on 'archive_template' to use custom templates for
    //  * portfolio archive page.
    //  *
    //  * @param  string $template_path
    //  * @return string
    //  */
    // public function locate_template_archive( $template_path ) {
    //     $post_types = array_filter( (array) get_query_var( 'post_type' ) );

    //     if ( count( $post_types ) === 1 ) {
    //         $post_type = reset( $post_types );

    //         if ( $post_type !== 'portfolio_item' ) return $template_path;

    //         $selected = option::get( 'portfolio_archive_template' );
    //         $found = $this->locate_template( 'archive', $selected );

    //         if ( $found ) return $found;
    //     }

    //     return $template_path;
    // }

    /**
     * Filter on 'single_template' to use custom templates for
     * portfolio single page.
     *
     * @param  string $template_path
     * @return string
     */
    public function locate_template_single( $template_path ) {
        $post_types = array_filter( (array) get_query_var( 'post_type' ) );

        if ( count( $post_types ) === 1 ) {
            $post_type = reset( $post_types );

            if ( $post_type !== 'portfolio_item' ) return $template_path;

            $selected = option::get( 'portfolio_single_template' );
            $found = $this->locate_template( 'single', $selected );

            if ( $found ) return $found;
        }

        return $template_path;
    }

    /**
     * Filter on 'taxonomy_template' to use custom templates for
     * portfolio single page.
     *
     * @param  string $template_path
     * @return string
     */
    public function locate_template_taxonomy( $template_path ) {
        $term = get_queried_object();

        if ( $term ) {
            $taxonomy = $term->taxonomy;

            if ( $taxonomy !== 'portfolio' ) return $template_path;

            /* Use simple archive template for taxonomies. */
            $found = $this->locate_template( 'taxonomy' );

            if ( $found ) return $found;
        }

        return $template_path;
    }

    public function locate_template( $type, $selected = false ) {
        if ( ! $type || ! is_string( $type ) ) return false;

        /* Try to find selected template. */
        if ( $selected ) {
            /* Child theme. */
            $paths = glob( get_stylesheet_directory() . "/portfolio/$type*.php" );
            foreach ( $paths as $path ) {
                $template_info = get_file_data( $path, array( 'Portfolio Style' ) );

                /* If selected template is found load it. */
                if ( $template_info[0] === $selected ) {
                    return $path;
                }
            }

            /* Parent theme. */
            $paths = glob( get_template_directory() . "/portfolio/$type*.php" );
            foreach ( $paths as $path ) {
                $template_info = get_file_data( $path, array( 'Portfolio Style' ) );

                /* If selected template is found load it. */
                if ( $template_info[0] === $selected ) {
                    return $path;
                }
            }
        }

        /* Child theme. */
        if ( file_exists( get_stylesheet_directory() . "/portfolio/$type.php" ) ) {
            return get_stylesheet_directory() . "/portfolio/$type.php";
        }

        /* Parent theme. */
        if ( file_exists( get_template_directory() . "/portfolio/$type.php" ) ) {
            return get_template_directory() . "/portfolio/$type.php";
        }

        return false;
    }

    /**
     * Finds all available `$type` templates for portfolio post type.
     *
     * @param string $type
     * @return array
     */
    public static function get_available_templates( $type ) {
        $paths = glob( get_template_directory() . "/portfolio/$type*.php" );
        $names = array();

        foreach ( $paths as $path ) {
            $file_data = get_file_data( $path, array( 'Portfolio Style' ) );
            $names[] = $file_data[0];
        }

        return $names;
    }

    /**
     * Filter on `portfolio_root` option, can't be blank.
     *
     * @param  string $value
     * @return string
     */
    public function save_portfolio_root( $value ) {
        if ( ! $value ) {
            $value = 'portfolio';
        }

        return $value;
    }

    /**
     * Updates meta field for portfolio post type,
     * creates them if don't exist.
     *
     * @param  int    $post_id     The ID of the post to which a custom field should be added.
     * @param  string $meta_value  The value of the custom field which should be added. If an array is given, it will be serialized into a string.
     * @param  mixed  $meta_key    The key of the custom field which should be added.
     * @return void
     */
    public function update_meta( $post_id, $meta_value, $meta_key ) {
        // To create new meta
        if ( ! get_post_meta( $post_id, $meta_key ) ) {
            add_post_meta( $post_id, $meta_key, $meta_value );
        } else {
            // or to update existing meta
            update_post_meta( $post_id, $meta_key, $meta_value );
        }
    }

    /**
     * Overwrites the screen icon for portfolio section.
     * @return void
     */
    public function admin_head_style() {
        global $post_type;

        if ( $post_type === 'portfolio_item' ) { ?>
            <style type="text/css">
                #icon-edit.icon32-posts-portfolio_item {
                    background: transparent url( '<?php echo WPZOOM::$assetsPath . '/images/components/portfolio/portfolio-32.png'; ?>' ) no-repeat;
                }
            </style>
        <?php }
    }

    /**
     * Register component options for zoom framework.
     *
     * @param  array $zoom_options
     * @return array
     */
    public function register_options( $zoom_options ) {
        $zoom_options[] = sprintf( '%s/options.php', dirname( __FILE__ ) );

        return $zoom_options;
    }
}
