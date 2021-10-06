<?php

class WPZOOM_Importer extends WXR_Importer
{

    protected $force_delete = true;

    /**
     * Parses the WXR file and prepares us for the task of processing parsed data
     *
     * @param string $file Path to the WXR file for importing
     */
    protected function import_start($file)
    {
        // Suspend bunches of stuff in WP core
        wp_defer_term_counting(true);
        wp_defer_comment_counting(true);
        wp_suspend_cache_invalidation(true);

        // Prefill exists calls if told to
        if ($this->options['prefill_existing_posts']) {
            $this->prefill_existing_posts();
        }
        if ($this->options['prefill_existing_comments']) {
            $this->prefill_existing_comments();
        }
        if ($this->options['prefill_existing_terms']) {
            $this->prefill_existing_terms();
        }

        /**
         *  Set empty array to image_size in order
         */
        add_action('intermediate_image_sizes_advanced', '__return_empty_array');

        /**
         * Begin the import.
         *
         * Fires before the import process has begun. If you need to suspend
         * caching or heavy processing on hooks, do so here.
         */
        do_action('import_start');

    }

    /**
     * Parses the WXR file and prepares us for the task of processing parsed data
     *
     * @param string $file Path to the WXR file for importing
     */
    protected function erase_start($file)
    {
        // if ( ! is_file( $file ) ) {
        //     return new WP_Error( 'wxr_importer.file_missing', __( 'The file does not exist, please try again.', 'wpzoom' ) );
        // }

        // Suspend bunches of stuff in WP core
        wp_defer_term_counting( true );
        wp_defer_comment_counting( true );
        wp_suspend_cache_invalidation( true );

        // Prefill exists calls if told to
        if ( $this->options['prefill_existing_posts'] ) {
            $this->prefill_existing_posts();
        }
        if ( $this->options['prefill_existing_comments'] ) {
            $this->prefill_existing_comments();
        }
        if ( $this->options['prefill_existing_terms'] ) {
            $this->prefill_existing_terms();
        }

        /**
         * Begin the erase.
         *
         * Fires before the erase process has begun. If you need to suspend
         * caching or heavy processing on hooks, do so here.
         */
        do_action( 'erase_demo_start' );
    }

    public function erase($file)
    {
        add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
        add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

        $result = $this->erase_start( $file );
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Let's run the actual importer now, woot
        $reader = $this->get_reader( $file );
        if ( is_wp_error( $reader ) ) {
            return $reader;
        }

        // Set the version to compatibility mode first
        $this->version = '1.0';

        // Reset other variables
        $this->base_url = '';

        // Start parsing!
        while ( $reader->read() ) {
            // Only deal with element opens
            if ( $reader->nodeType !== XMLReader::ELEMENT ) {
                continue;
            }

            switch ( $reader->name ) {
                case 'wp:wxr_version':
                    // Upgrade to the correct version
                    $this->version = $reader->readString();

                    if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) ) {
                        $this->logger->warning( sprintf(
                            __( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'wpzoom' ),
                            $this->version,
                            self::MAX_WXR_VERSION
                        ) );
                    }

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                case 'wp:base_site_url':
                    $this->base_url = $reader->readString();

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                case 'item':
                    $node = $reader->expand();
                    $parsed = $this->parse_post_node( $node );
                    if ( is_wp_error( $parsed ) ) {
                        $this->log_error( $parsed );

                        // Skip the rest of this post
                        $reader->next();
                        break;
                    }

                    $this->delete_post( $parsed['data'], $parsed['meta'], $parsed['comments'], $parsed['terms'] );

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                case 'wp:wp_author':
                    $node = $reader->expand();

                    $parsed = $this->parse_author_node( $node );
                    if ( is_wp_error( $parsed ) ) {
                        $this->log_error( $parsed );

                        // Skip the rest of this post
                        $reader->next();
                        break;
                    }

                    $status = $this->delete_author( $parsed['data'], $parsed['meta'] );
                    if ( is_wp_error( $status ) ) {
                        $this->log_error( $status );
                    }

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                case 'wp:category':
                    $node = $reader->expand();

                    $parsed = $this->parse_term_node( $node, 'category' );
                    if ( is_wp_error( $parsed ) ) {
                        $this->log_error( $parsed );

                        // Skip the rest of this post
                        $reader->next();
                        break;
                    }

                    $status = $this->delete_term( $parsed['data'], $parsed['meta'] );

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                case 'wp:tag':
                    $node = $reader->expand();

                    $parsed = $this->parse_term_node( $node, 'tag' );
                    if ( is_wp_error( $parsed ) ) {
                        $this->log_error( $parsed );

                        // Skip the rest of this post
                        $reader->next();
                        break;
                    }

                    $status = $this->delete_term( $parsed['data'], $parsed['meta'] );

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                case 'wp:term':
                    $node = $reader->expand();

                    $parsed = $this->parse_term_node( $node );
                    if ( is_wp_error( $parsed ) ) {
                        $this->log_error( $parsed );

                        // Skip the rest of this post
                        $reader->next();
                        break;
                    }

                    $status = $this->delete_term( $parsed['data'], $parsed['meta'] );

                    // Handled everything in this node, move on to the next
                    $reader->next();
                    break;

                default:
                    // Skip this node, probably handled by something already
                    break;
            }
        }

        $this->erase_end();
    }

    protected function erase_end()
    {
        // Re-enable stuff in core
        wp_suspend_cache_invalidation( false );
        wp_cache_flush();
        wp_defer_term_counting( false );
        wp_defer_comment_counting( false );

        /**
         * Complete the erase.
         *
         * Fires after the erase process has finished. If you need to update
         * your cache or re-enable processing, do so here.
         */
        do_action( 'erase_demo_end' );
    }

    /**
     * Delete posts based on import information
     *
     * Posts marked as having a parent which doesn't exist will become top level items.
     * Doesn't create a new post if: the post type doesn't exist, the given post ID
     * is already noted as imported or a post with the same title and date already exists.
     * Note that new/updated terms, comments and meta are imported for the last of the above.
     */
    protected function delete_post( $data, $meta, $comments, $terms )
    {
        /**
         * Pre-process post data.
         *
         * @param array $data Post data. (Return empty to skip.)
         * @param array $meta Meta data.
         * @param array $comments Comments on the post.
         * @param array $terms Terms on the post.
         */
        $data = apply_filters( 'wxr_importer.pre_process.post', $data, $meta, $comments, $terms );
        if ( empty( $data ) ) {
            return false;
        }

        $original_id = isset( $data['post_id'] )     ? (int) $data['post_id']     : 0;
        $parent_id   = isset( $data['post_parent'] ) ? (int) $data['post_parent'] : 0;
        $author_id   = isset( $data['post_author'] ) ? (int) $data['post_author'] : 0;

        // Have we already processed this?
        if ( isset( $this->mapping['post'][ $original_id ] ) ) {
            return;
        }

        $post_type_object = get_post_type_object( $data['post_type'] );

        // Is this type even valid?
        if ( ! $post_type_object ) {
            $this->logger->warning( sprintf(
                __( 'Failed to delete "%s": Invalid post type %s', 'wpzoom' ),
                $data['post_title'],
                $data['post_type']
            ) );
            return false;
        }

        $post_exists = $this->post_exists( $data );

        if ( $post_exists ) {
            $exists_key = $data['guid'];

            if ( isset( $this->exists['post'][ $exists_key ] ) ) {
                $post_id = $this->exists['post'][ $exists_key ];
            }
        } else {
            $post_id = $original_id;
        }

        $result = wp_delete_post($post_id, $this->force_delete);

        if ( is_wp_error( $result ) ) {
            $this->logger->error( sprintf(
                __( 'Failed to delete "%s" (%s)', 'wpzoom' ),
                $data['post_title'],
                $post_type_object->labels->singular_name
            ) );
            $this->logger->debug( $result->get_error_message() );
            return false;
        }

        $this->logger->info( sprintf(
            __( 'Deleted "%s" (%s)', 'wpzoom' ),
            $data['post_title'],
            $post_type_object->labels->singular_name
        ) );

        do_action( 'wp_import_delete_post', $post_id, $data );
    }

    protected function delete_author( $data, $meta ) {
        /**
         * Pre-process user data.
         *
         * @param array $data User data. (Return empty to skip.)
         * @param array $meta Meta data.
         */
        $data = apply_filters( 'wxr_importer.pre_process.user', $data, $meta );
        if ( empty( $data ) ) {
            return false;
        }

        // Have we already handled this user?
        $original_id = isset( $data['ID'] ) ? $data['ID'] : 0;
        $original_slug = $data['user_login'];

        if ( isset( $this->mapping['user'][ $original_id ] ) ) {
            $existing = $this->mapping['user'][ $original_id ];

            // Note the slug mapping if we need to too
            if ( ! isset( $this->mapping['user_slug'][ $original_slug ] ) ) {
                $this->mapping['user_slug'][ $original_slug ] = $existing;

                $user_id = $this->mapping['user_slug'][ $original_slug ];
            }
        }

        if ( isset( $this->mapping['user_slug'][ $original_slug ] ) ) {
            $existing = $this->mapping['user_slug'][ $original_slug ];

            // Ensure we note the mapping too
            $this->mapping['user'][ $original_id ] = $existing;

            $user_id = $this->mapping['user'][ $original_id ];
        }

        $result = wp_delete_user( $user_id );

        if ( is_wp_error( $result ) ) {
            $this->logger->error( sprintf(
                __( 'Failed to import user "%s"', 'wpzoom' ),
                $userdata['user_login']
            ) );
            $this->logger->debug( $result->get_error_message() );
            return false;
        }

        $this->logger->info( sprintf(
            __( 'Deleted user "%s"', 'wpzoom' ),
            $userdata['user_login']
        ) );

        do_action( 'wp_import_delete_user', $user_id, $data );
    }

    protected function delete_term( $data, $meta ) {
        /**
         * Pre-process term data.
         *
         * @param array $data Term data. (Return empty to skip.)
         * @param array $meta Meta data.
         */
        $data = apply_filters( 'wxr_importer.pre_process.term', $data, $meta );
        if ( empty( $data ) ) {
            return false;
        }

        $original_id = isset( $data['id'] )      ? (int) $data['id']      : 0;
        $parent_id   = isset( $data['parent'] )  ? (int) $data['parent']  : 0;

        $mapping_key = sha1( $data['taxonomy'] . ':' . $data['slug'] );
        
        if ( $existing = $this->term_exists( $data ) ) {
            $this->mapping['term'][ $mapping_key ] = $existing;
            $this->mapping['term_id'][ $original_id ] = $existing;

            $term_id = $this->mapping['term'][ $mapping_key ];
        }

        // Permanently delete the menu by term_id
        if ( $data['taxonomy'] == 'nav_menu' ) {
            $result = wp_delete_nav_menu( $term_id );
        } else {
            $result = wp_delete_term( $term_id, $data['taxonomy'] );
        }
        
        if ( is_wp_error( $result ) ) {
            $this->logger->warning( sprintf(
                __( 'Failed to delete %s %s', 'wpzoom' ),
                $data['taxonomy'],
                $data['name']
            ) );
            $this->logger->debug( $result->get_error_message() );
            do_action( 'wp_import_insert_term_failed', $result, $data );
            return false;
        }

        $this->logger->info( sprintf(
            __( 'Deleted "%s" (%s)', 'wpzoom' ),
            $data['name'],
            $data['taxonomy']
        ) );

        do_action( 'wp_import_delete_term', $term_id, $data );
    }
}
