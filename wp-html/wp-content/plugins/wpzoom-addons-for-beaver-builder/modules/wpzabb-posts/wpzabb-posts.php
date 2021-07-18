<?php

/**
 * @class WPZABBPostsModule
 */
class WPZABBPostsModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Posts', 'wpzabb'),
			'description'   	=> __('Display posts as grid/list layout.', 'wpzabb'),
			'category'          => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'posts/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'posts/',
            'partial_refresh'	=> true,
			'icon'				=> 'schedule.svg',
		));
	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {
		if ( FLBuilderModel::is_builder_active() || 'grid' == $this->settings->layout ) {
			$this->add_js( 'jquery-imagesloaded' );
			$this->add_js( 'jquery-masonry' );

		}
		if ( FLBuilderModel::is_builder_active() || 'scroll' == $this->settings->pagination || 'load_more' == $this->settings->pagination ) {
			$this->add_js( 'jquery-infinitescroll' );
		}

		if ( FLBuilderModel::is_builder_active() || $this->settings->show_comments ) {
			$this->add_css( 'font-awesome-5' );
		}

		// Jetpack sharing has settings to enable sharing on posts, post types and pages.
		// If pages are disabled then jetpack will still show the share button in this module
		// but will *not* enqueue its scripts and fonts.
		// This filter forces jetpack to enqueue the sharing scripts.
		add_filter( 'sharing_enqueue_scripts', '__return_true' );
	}

	/**
	 * @since 1.0
	 */
	public function update( $settings ) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules( false );
		return $settings;
	}

	/**
	 * Returns the slug for the posts layout.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_layout_slug() {
		return $this->settings->layout;
	}

	/**
	 * Renders the CSS class for each post item.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function render_post_class() {
		$settings   = $this->settings;
		$layout     = $this->get_layout_slug();
		$show_image = has_post_thumbnail() && $settings->show_image;
		$classes    = array( 'wpzabb-post-' . $layout . '-post' );

		if ( $show_image ) {
			if ( 'list' == $layout ) {
				$classes[] = 'wpzabb-post-list-image-' . $settings->image_position;
			}
			if ( 'grid' == $layout ) {
				$classes[] = 'wpzabb-post-grid-image-' . $settings->grid_image_position;
			}
		}

		if ( in_array( $layout, array( 'grid', 'list' ) ) ) {
			$classes[] = 'wpzabb-post-align-' . $settings->post_align;
		}

		post_class( apply_filters( 'wpzabb_builder_posts_module_classes', $classes, $settings ) );
	}

	/**
	 * Renders the featured image for a post.
	 *
	 * @since 1.0
	 * @param string|array $position
	 * @return void
	 */
	public function render_featured_image( $position = 'above' ) {
		$settings = $this->settings;
		$render   = false;
		$position = ! is_array( $position ) ? array( $position ) : $position;
		$layout   = $this->get_layout_slug();

		if ( has_post_thumbnail() && $settings->show_image ) {

			if ( 'list' == $settings->layout && in_array( $settings->image_position, $position ) ) {
				$render = true;
			} elseif ( 'grid' == $settings->layout && in_array( $settings->grid_image_position, $position ) ) {
				$render = true;
			}

			if ( $render ) {
				include $this->dir . 'includes/featured-image.php';
			}
		}
	}

	/**
	 * Renders the post meta.
	 *
	 * @since 1.0
	 * @param string|array $position
	 * @return void
	 */
	public function render_meta( $position = 'above' ) {
		$module   = $this;
		$settings = $this->settings;
		$render   = false;
		$position = ! is_array( $position ) ? array( $position ) : $position;
		$layout   = $this->get_layout_slug();

		if ( 'list' == $settings->layout && in_array( $settings->info_position, $position ) ) {
			$render = true;
		} elseif ( 'grid' == $settings->layout && in_array( $settings->grid_info_position, $position ) ) {
			$render = true;
		}

		$settings->show_comments = ( 'grid' == $settings->layout ) ? $settings->grid_show_comments : $settings->show_comments;

		if ( $render ) {
			include $this->dir . 'includes/post-meta.php';
		}
	}


    /**
     * Renders the load more button.
     *
	 * @since 1.0
	 * @method render_button
	 * @return void
	 */
	public function render_more_button()
	{
		$btn_settings = array(
			'text'             			=> $this->settings->more_btn_text,
			'link'             			=> '#',
			'link_target'             	=> '_self',
			'align'             		=> 'center',
			'mob_align'             	=> 'center',
			'border_radius'             => $this->settings->more_btn_border_radius,
			'width' 					=> $this->settings->more_btn_width,
			'padding_top_bottom' 		=> $this->settings->more_btn_padding_top_bottom,
			'padding_left_right' 		=> $this->settings->more_btn_padding_left_right,
			'font_family'       		=> $this->settings->more_btn_font_family,
			'font_size_unit'   			=> $this->settings->more_btn_font_size,
			'line_height_unit' 			=> $this->settings->more_btn_line_height_unit,
			'letter_spacing' 			=> $this->settings->more_btn_custom_letter_spacing,
			'text_transform' 			=> $this->settings->more_btn_text_transform,
			'letter_spacing' 			=> $this->settings->more_btn_letter_spacing,
			'style' 					=> $this->settings->more_btn_style,
			'border_size' 				=> $this->settings->more_btn_border_size,
			'flat_options' 				=> $this->settings->more_btn_flat_options,
			'icon' 						=> $this->settings->more_btn_icon,
			'icon_position' 			=> $this->settings->more_btn_icon_position,
			'text_color' 				=> $this->settings->more_btn_text_color,
			'text_hover_color' 			=> $this->settings->more_btn_text_hover_color,
			'bg_color' 					=> $this->settings->more_btn_bg_color,
			'bg_color_opc' 				=> $this->settings->more_btn_bg_color_opc,
			'bg_hover_color' 			=> $this->settings->more_btn_bg_hover_color,
			'bg_hover_color_opc' 		=> $this->settings->more_btn_bg_hover_color_opc,
			'transparent_button_options' => 'none',
			'hover_attribute' 			=> $this->settings->more_btn_hover_attribute,
		);

		/* Render HTML Function */
		echo '<div class="fl-builder-pagination-load-more">';
		FLBuilder::render_module_html( 'wpzabb-button', $btn_settings );
		echo '</div>';
	}

	/**
	 * Checks to see if a featured image exists for a position.
	 *
	 * @since 1.0
	 * @param string|array $position
	 * @return void
	 */
	public function has_featured_image( $position = 'above' ) {
		$settings = $this->settings;
		$result   = false;
		$position = ! is_array( $position ) ? array( $position ) : $position;

		if ( has_post_thumbnail() && $settings->show_image ) {

			if ( 'list' == $settings->layout && in_array( $settings->image_position, $position ) ) {
				$result = true;
			} elseif ( 'grid' == $settings->layout && in_array( $settings->grid_image_position, $position ) ) {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * Renders the_content for a post.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function render_content() {
		ob_start();
		the_content();
		$content = ob_get_clean();

		if ( ! empty( $this->settings->content_length ) ) {
			$content = wpautop( wp_trim_words( $content, $this->settings->content_length, '...' ) );
		}

		echo $content;
	}

	/**
	 * Renders the_excerpt for a post.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function render_excerpt() {
		if ( ! empty( $this->settings->content_length ) ) {
			add_filter( 'excerpt_length', array( $this, 'set_custom_excerpt_length' ), 9999 );
		}

		the_excerpt();

		if ( ! empty( $this->settings->content_length ) ) {
			remove_filter( 'excerpt_length', array( $this, 'set_custom_excerpt_length' ), 9999 );
		}
	}

	/**
	 * Renders the excerpt for a post.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function set_custom_excerpt_length( $length ) {
		return $this->settings->content_length;
	}

	/**
	 * Get the terms for the current post.
	 *
	 * @since 1.0
	 * @return string|null
	 */
	public function get_post_terms() {
		$post_type = get_post_type();
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$terms_list = array();
		$terms_separator = '<span class="wpzabb-sep-term">' . $this->settings->terms_separator . '</span>';

		if ( ! $taxonomies || empty( $taxonomies ) ) {
			return;
		}

		foreach ( $taxonomies as $name => $tax ) {
			if ( ! $tax->hierarchical ) {
				continue;
			}

			$term_list = get_the_term_list( get_the_ID(), $name, '', $terms_separator, '' );
			if ( ! empty( $term_list ) ) {
				$terms_list[] = $term_list;
			}
		}

		if ( count( $terms_list ) > 0 ) {
			return join( $terms_separator, $terms_list );
		}
	}

	/**
	 * Renders the schema structured data for the current
	 * post in the loop.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function schema_meta() {
		// General Schema Meta
		echo '<meta itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="' . esc_url( get_permalink() ) . '" content="' . the_title_attribute( array(
			'echo' => false,
		) ) . '" />';
		echo '<meta itemprop="datePublished" content="' . get_the_time( 'Y-m-d' ) . '" />';
		echo '<meta itemprop="dateModified" content="' . get_the_modified_date( 'Y-m-d' ) . '" />';

		// Publisher Schema Meta
		echo '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
		echo '<meta itemprop="name" content="' . get_bloginfo( 'name' ) . '">';

		if ( class_exists( 'FLTheme' ) && 'image' == FLTheme::get_setting( 'wpzabb-logo-type' ) ) {
			echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
			echo '<meta itemprop="url" content="' . FLTheme::get_setting( 'wpzabb-logo-image' ) . '">';
			echo '</div>';
		}

		echo '</div>';

		// Author Schema Meta
		echo '<div itemscope itemprop="author" itemtype="https://schema.org/Person">';
		echo '<meta itemprop="url" content="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" />';
		echo '<meta itemprop="name" content="' . get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) . '" />';
		echo '</div>';

		// Image Schema Meta
		if ( has_post_thumbnail() ) {

			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

			if ( is_array( $image ) ) {
				echo '<div itemscope itemprop="image" itemtype="https://schema.org/ImageObject">';
				echo '<meta itemprop="url" content="' . $image[0] . '" />';
				echo '<meta itemprop="width" content="' . $image[1] . '" />';
				echo '<meta itemprop="height" content="' . $image[2] . '" />';
				echo '</div>';
			}
		}

		// Comment Schema Meta
		echo '<div itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">';
		echo '<meta itemprop="interactionType" content="https://schema.org/CommentAction" />';
		echo '<meta itemprop="userInteractionCount" content="' . wp_count_comments( get_the_ID() )->approved . '" />';
		echo '</div>';
	}

	/**
	 * Renders the schema itemtype for the current
	 * post in the loop.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function schema_itemtype() {
		global $post;

		if ( ! is_object( $post ) || ! isset( $post->post_type ) || 'post' != $post->post_type ) {
			echo 'https://schema.org/CreativeWork';
		} else {
			echo 'https://schema.org/BlogPosting';
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBPostsModule', array(
	'layout'        => array(
		'title'         => __( 'Layout', 'wpzabb' ),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'layout'        => array(
						'type'          => 'select',
						'label'         => __( 'Layout', 'wpzabb' ),
						'default'       => 'grid',
						'options'       => array(
							'grid'          => __( 'Grid', 'wpzabb' ),
							'list'          => __( 'List', 'wpzabb' ),
						),
						'toggle'        => array(
							'grid'          => array(
								'sections'      => array( 'posts', 'image', 'info', 'content', 'post_style', 'text_style' ),
								'fields'        => array( 'post_columns', 'post_spacing', 'post_padding', 'grid_image_position', 'grid_image_spacing', 'grid_image_margin_top', 'grid_image_margin_bottom', 'show_author', 'grid_info_position', 'grid_show_comments', 'info_separator', 'show_terms' ),
							),
							'list'          => array(
								'sections'      => array( 'posts', 'image', 'info', 'content', 'post_style', 'text_style' ),
								'fields'        => array( 'image_position', 'image_spacing', 'image_width', 'image_margin_top', 'image_margin_bottom', 'list_post_spacing', 'list_post_padding', 'show_author', 'show_comments', 'info_separator', 'show_terms', 'info_position', 'content_type' ),
							),
						),
					),
				),
			),
			'posts'         => array(
				'title'         => __( 'Posts', 'wpzabb' ),
				'fields'        => array(
					'post_columns'  => array(
						'type'          => 'unit',
						'label'         => __( 'Columns', 'wpzabb' ),
						'responsive'  => array(
							'default' 	  => array(
								'default'    => '3',
								'medium'     => '2',
								'responsive' => '2',
							),
						),
					),
					'post_spacing' => array(
						'type'          => 'text',
						'label'         => __( 'Post Spacing', 'wpzabb' ),
						'default'       => '60',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'list_post_spacing' => array(
						'type'          => 'text',
						'label'         => __( 'Post Spacing', 'wpzabb' ),
						'default'       => '40',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'post_padding' => array(
						'type'          => 'text',
						'label'         => __( 'Post Padding', 'wpzabb' ),
						'default'       => '20',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
						'preview'       => array(
							'type'			=> 'css',
							'selector'		=> '.wpzabb-post-grid-text',
							'property'		=> 'padding',
							'unit'			=> 'px',
						),
					),
					'list_post_padding' => array(
						'type'          => 'text',
						'label'         => __( 'Post Padding', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'post_align'    => array(
						'type'          => 'select',
						'label'         => __( 'Post Alignment', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'left'          => __( 'Left', 'wpzabb' ),
							'center'        => __( 'Center', 'wpzabb' ),
							'right'         => __( 'Right', 'wpzabb' ),
						),
					),
				),
			),
			'image'        => array(
				'title'         => __( 'Featured Image', 'wpzabb' ),
				'fields'        => array(
					'show_image'    => array(
						'type'          => 'select',
						'label'         => __( 'Image', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
					),
					'grid_image_position' => array(
						'type'          => 'select',
						'label'         => __( 'Image Position', 'wpzabb' ),
						'default'       => 'above-title',
						'options'       => array(
							'above-title'   => __( 'Above Title', 'wpzabb' ),
							'above'         => __( 'Above Content', 'wpzabb' ),
						),
					),
					'image_position' => array(
						'type'          => 'select',
						'label'         => __( 'Image Position', 'wpzabb' ),
						'default'       => 'above',
						'options'       => array(
							'above-title'			=> __( 'Above Title', 'wpzabb' ),
							'above'					=> __( 'Above Content', 'wpzabb' ),
							'beside'				=> __( 'Left', 'wpzabb' ),
							'beside-content'		=> __( 'Left Content', 'wpzabb' ),
							'beside-right'			=> __( 'Right', 'wpzabb' ),
							'beside-content-right'	=> __( 'Right Content', 'wpzabb' ),
						),
					),
					'image_size'    => array(
						'type'          => 'photo-sizes',
						'label'         => __( 'Image Size', 'wpzabb' ),
						'default'       => 'medium',
					),
					'grid_image_spacing' => array(
						'type'          => 'text',
						'label'         => __( 'Image Spacing', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'grid_image_margin_top' => array(
						'type'          => 'text',
						'label'         => __( 'Image Margin Top', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'grid_image_margin_bottom' => array(
						'type'          => 'text',
						'label'         => __( 'Image Margin Bottom', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'image_width'   => array(
						'type'          => 'text',
						'label'         => __( 'Image Width', 'wpzabb' ),
						'default'       => '33',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => '%',
					),
					'image_spacing' => array(
						'type'          => 'text',
						'label'         => __( 'Image Spacing', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'image_margin_top' => array(
						'type'          => 'text',
						'label'         => __( 'Image Margin Top', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'image_margin_bottom' => array(
						'type'          => 'text',
						'label'         => __( 'Image Margin Bottom', 'wpzabb' ),
						'default'       => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
				),
			),
			'info'          => array(
				'title'         => __( 'Post Info', 'wpzabb' ),
				'fields'        => array(
					'grid_info_position' => array(
						'type'          => 'select',
						'label'         => __( 'Info Meta Position', 'wpzabb' ),
						'default'       => 'above',
						'options'       => array(
							'above-title'   => __( 'Above Title', 'wpzabb' ),
							'above'         => __( 'Above Content', 'wpzabb' ),
						),
					),
					'info_position' => array(
						'type'          => 'select',
						'label'         => __( 'Info Meta Position', 'wpzabb' ),
						'default'       => 'above',
						'options'       => array(
							'above-title'   => __( 'Above Title', 'wpzabb' ),
							'above'         => __( 'Above Content', 'wpzabb' ),
						),
					),
					'show_author'   => array(
						'type'          => 'select',
						'label'         => __( 'Author', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
					),
					'show_date'     => array(
						'type'          => 'select',
						'label'         => __( 'Date', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'             => array(
								'fields'        => array( 'date_format' ),
							),
						),
					),
					'date_format'   => array(
						'type'          => 'select',
						'label'         => __( 'Date Format', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'		=> __( 'Default', 'wpzabb' ),
							'M j, Y'        => date( 'M j, Y' ),
							'F j, Y'        => date( 'F j, Y' ),
							'm/d/Y'         => date( 'm/d/Y' ),
							'm-d-Y'         => date( 'm-d-Y' ),
							'd M Y'         => date( 'd M Y' ),
							'd F Y'         => date( 'd F Y' ),
							'Y-m-d'         => date( 'Y-m-d' ),
							'Y/m/d'         => date( 'Y/m/d' ),
						),
					),
					'show_comments' => array(
						'type'          => 'select',
						'label'         => __( 'Comments', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
					),
					'grid_show_comments' => array(
						'type'          => 'select',
						'label'         => __( 'Comments', 'wpzabb' ),
						'default'       => '0',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
					),
					'info_separator' => array(
						'type'          => 'text',
						'label'         => __( 'Separator', 'wpzabb' ),
						'default'       => ' / ',
						'size'          => '4',
						'preview'       => array(
							'type'			=> 'text',
							'selector'		=> '.fl-sep',
						),
					),
					'show_terms'        => array(
						'type'          => 'select',
						'label'         => __( 'Terms', 'wpzabb' ),
						'default'       => '0',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'             => array(
								'fields'        => array( 'terms_separator', 'terms_list_label' ),
							),
						),
					),
					'terms_list_label' => array(
						'type'          => 'text',
						'label'         => __( 'Terms Label', 'wpzabb' ),
						'default'       => __( 'Posted in ', 'wpzabb' ),
						'preview'       => array(
							'type'			=> 'text',
							'selector'		=> '.wpzabb-terms-label',
						),
					),
					'terms_separator' => array(
						'type'          => 'text',
						'label'         => __( 'Terms Separator', 'wpzabb' ),
						'default'       => ', ',
						'size'          => '4',
						'preview'       => array(
							'type'			=> 'text',
							'selector'		=> '.wpzabb-sep-term',
						),
					),
				),
			),
			'content'       => array(
				'title'         => __( 'Content', 'wpzabb' ),
				'fields'        => array(
					'show_content'  => array(
						'type'          => 'select',
						'label'         => __( 'Content', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
					),
					'content_type'  => array(
						'type'          => 'select',
						'label'         => __( 'Content Type', 'wpzabb' ),
						'default'       => 'excerpt',
						'options'       => array(
							'excerpt'        => __( 'Excerpt', 'wpzabb' ),
							'full'           => __( 'Full Text', 'wpzabb' ),
						),
					),
					'content_length' => array(
						'type'          => 'text',
						'label'         => __( 'Content Length', 'wpzabb' ),
						'default'       => '',
						'size'          => '4',
						'sanitize'		=> 'absint',
						'description'   => __( 'words', 'wpzabb' ),
					),
					'show_more_link' => array(
						'type'          => 'select',
						'label'         => __( 'More Link', 'wpzabb' ),
						'default'       => '0',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'             => array(
								'fields'        => array( 'more_link_text' ),
							),
						),
					),
					'more_link_text' => array(
						'type'          => 'text',
						'label'         => __( 'More Link Text', 'wpzabb' ),
						'default'       => __( 'Read More', 'wpzabb' ),
					),
				),
			),
		),
	),
	'style'         => array(
		'title'         => __( 'Style', 'wpzabb' ),
		'sections'      => array(
			'post_style'    => array(
				'title'         => __( 'Posts', 'wpzabb' ),
				'fields'        => array(
					'bg_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Post Background Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'bg_color_opc'    => array(
						'type'          => 'text',
						'label'         => __( 'Post Background Opacity', 'wpzabb' ),
						'default'       => '100',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => '%',
					),
					'border_type'   => array(
						'type'          => 'select',
						'label'         => __( 'Post Border Type', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => _x( 'Default', 'Border type.', 'wpzabb' ),
							'none'          => _x( 'None', 'Border type.', 'wpzabb' ),
							'solid'         => _x( 'Solid', 'Border type.', 'wpzabb' ),
							'dashed'        => _x( 'Dashed', 'Border type.', 'wpzabb' ),
							'dotted'        => _x( 'Dotted', 'Border type.', 'wpzabb' ),
							'double'        => _x( 'Double', 'Border type.', 'wpzabb' ),
						),
						'toggle'        => array(
							'solid'         => array(
								'fields'        => array( 'border_color', 'border_size' ),
							),
							'dashed'        => array(
								'fields'        => array( 'border_color', 'border_size' ),
							),
							'dotted'        => array(
								'fields'        => array( 'border_color', 'border_size' ),
							),
							'double'        => array(
								'fields'        => array( 'border_color', 'border_size' ),
							),
						),
					),
					'border_color'  => array(
						'type'          => 'color',
						'label'         => __( 'Post Border Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'border_size'  => array(
						'type'          => 'text',
						'label'         => __( 'Post Border Size', 'wpzabb' ),
						'default'       => '1',
						'maxlength'     => '3',
						'size'          => '4',
						'sanitize'		=> 'absint',
						'description'   => 'px',
					),
				),
			),
			'text_style'    => array(
				'title'         => __( 'Text', 'wpzabb' ),
				'fields'        => array(
					'title_color'   => array(
						'type'          => 'color',
						'label'         => __( 'Title Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'title_hover_color'   => array(
						'type'          => 'color',
						'label'         => __( 'Title Hover Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'title_tag'           => array(
						'type'          => 'select',
						'label'         => __( 'Title HTML Tag', 'wpzabb' ),
						'default'       => 'h3',
						'options'       => array(
							'h1'            =>  'h1',
							'h2'            =>  'h2',
							'h3'            =>  'h3',
							'h4'            =>  'h4',
							'h5'            =>  'h5',
							'h6'            =>  'h6'
						)
					),
					'title_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 600
						),
						'label'         => __('Title Font', 'wpzabb'),
					),
					'title_font_size' => array(
						'type'          => 'text',
						'label'         => __( 'Title Font Size', 'wpzabb' ),
						'default'       => '',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'title_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-post-grid-title',
							'unit'		=> 'px',
						)
					),
					'title_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.wpzabb-post-grid-title',
							'unit'		=> 'px',
						)
					),
					'info_color'    => array(
						'type'          => 'color',
						'label'         => __( 'Post Info Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'info_link_color'    => array(
						'type'          => 'color',
						'label'         => __( 'Post Info Link Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'info_link_hover_color'    => array(
						'type'          => 'color',
						'label'         => __( 'Post Info Link Hover Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'info_font_size' => array(
						'type'          => 'text',
						'label'         => __( 'Post Info Font Size', 'wpzabb' ),
						'default'       => '',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'info_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Post Info Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-post-grid-meta, .wpzabb-post-list-meta',
							'unit'		=> 'px',
						)
					),
					'info_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Post Info Margin Bottom', 'wpzabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.wpzabb-post-grid-meta, .wpzabb-post-list-meta',
							'unit'		=> 'px',
						)
					),
					'content_color' => array(
						'type'          => 'color',
						'label'         => __( 'Content Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'content_font_size' => array(
						'type'          => 'text',
						'label'         => __( 'Content Font Size', 'wpzabb' ),
						'default'       => '',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'link_color'    => array(
						'type'          => 'color',
						'label'         => __( 'Link Color', 'wpzabb' ),
						'show_reset'    => true,
					),
					'link_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Link Hover Color', 'wpzabb' ),
						'show_reset'    => true,
					),
				),
			),
		),
	),
	'filter' => array(
		'title' => __( 'Filter', 'wpzabb' ),
		'file'  => FL_BUILDER_DIR . 'includes/loop-settings.php'
	),
	'pagination' => array(
		'title'      => __( 'Pagination', 'wpzabb' ),
		'sections'   => array(
			'pagination'   => array(
				'title'         => __( 'Pagination', 'wpzabb' ),
				'fields'        => array(
					'pagination'     => array(
						'type'          => 'select',
						'label'         => __( 'Pagination Style', 'wpzabb' ),
						'default'       => 'numbers',
						'options'       => array(
							'numbers'       => __( 'Numbers', 'wpzabb' ),
							'scroll'        => __( 'Scroll', 'wpzabb' ),
							'load_more'     => __( 'Load More Button', 'wpzabb' ),
							'none'          => _x( 'None', 'Pagination style.', 'wpzabb' ),
						),
						'toggle' 		=> array(
							'load_more' 	=> array(
								'sections' 		=> array( 'load_more_general' ),
							),
						),
					),
					'posts_per_page' => array(
						'type'          => 'text',
						'label'         => __( 'Posts Per Page', 'wpzabb' ),
						'default'       => '10',
						'size'          => '4',
					),
					'no_results_message' => array(
						'type' 				=> 'text',
						'label'				=> __( 'No Results Message', 'wpzabb' ),
						'default'			=> __( "Sorry, we couldn't find any posts. Please try a different search.", 'wpzabb' ),
					),
					'show_search'    => array(
						'type'          => 'select',
						'label'         => __( 'Show Search', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'1'             => __( 'Show', 'wpzabb' ),
							'0'             => __( 'Hide', 'wpzabb' ),
						),
						'help'          => __( 'Shows the search form if no posts are found.' ),
					),
				),
			),
			'load_more_general' => array(
				'title'         => __( 'Load More Button', 'wpzabb' ),
				'fields'        => array(
					'more_btn_text' => array(
						'type'          => 'text',
						'label'         => __( 'Button Text', 'wpzabb' ),
						'default'       => __( 'Load More', 'wpzabb' ),
					),
					'more_btn_style'    => array(
						'type'          => 'select',
						'label'         => __('Style', 'wpzabb'),
						'default'       => 'flat',
						'class'			=> 'creative_button_styles',
						'options'       => array(
							'flat'          => __('Flat', 'wpzabb'),
							'transparent'   => __('Transparent', 'wpzabb'),
						),
					),
					'more_btn_flat_options'         => array(
						'type'          => 'select',
						'label'         => __('Hover Styles', 'wpzabb'),
						'default'       => 'none',
						'options'       => array(
							'none'          => __('None', 'wpzabb'),
							'animate_to_left'      => __('Appear Icon From Right', 'wpzabb'),
							'animate_to_right'          => __('Appear Icon From Left', 'wpzabb'),
							'animate_from_top'      => __('Appear Icon From Top', 'wpzabb'),
							'animate_from_bottom'     => __('Appear Icon From Bottom', 'wpzabb'),
						),
					),
					'more_btn_icon'     => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'wpzabb'),
						'show_remove'   => true
					),
					'more_btn_icon_position' => array(
						'type'          => 'select',
						'label'         => __('Icon Position', 'wpzabb'),
						'default'       => 'after',
						'options'       => array(
							'before'        => __('Before Text', 'wpzabb'),
							'after'         => __('After Text', 'wpzabb')
						)
					),
					'more_btn_text_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Text Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
					'more_btn_text_hover_color'   => array( 
						'type'       => 'color',
                        'label'         => __('Text Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
					'more_btn_bg_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Background Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
                    'more_btn_bg_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'more_btn_bg_hover_color'        => array( 
						'type'       => 'color',
                        'label'      => __('Background Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
                    'more_btn_bg_hover_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
                    'more_btn_hover_attribute' => array(
                    	'type'          => 'select',
                        'label'         => __( 'Apply Hover Color To', 'wpzabb' ),
                        'default'       => 'bg',
                        'options'       => array(
                            'border'    => __( 'Border', 'wpzabb' ),
                            'bg'        => __( 'Background', 'wpzabb' ),
                        ),
                        'width'	=> '75px'
                    ),
					'more_btn_font_size' => array(
						'type'          => 'text',
						'label'         => __( 'Font Size', 'wpzabb' ),
						'default'       => '14',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
		            'more_btn_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
		            ),
		            'more_btn_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
		                'description'   => 'px',
		                'responsive' => array(
                            'placeholder' => array(
                                'default' => '',
                                'medium' => '',
                                'responsive' => '',
                            ),
                        ),
		            ),
		            'more_btn_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'responsive' => array(
                            'placeholder' => array(
                                'default' => '',
                                'medium' => '',
                                'responsive' => '',
                            ),
                        ),
		            ),
					'more_btn_text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'none',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
					),
					'more_btn_letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'more_btn_custom_letter_spacing' ),
							),
						),
					),
		            'more_btn_custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
		            ),
					'more_btn_padding_top_bottom'   => array(
						'type'          => 'text',
						'label'         => __( 'Padding Top/Bottom', 'wpzabb' ),
						'default'       => '10',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'more_btn_padding_left_right'   => array(
						'type'          => 'text',
						'label'         => __( 'Padding Left/Right', 'wpzabb' ),
						'default'       => '10',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'more_btn_border_size'   => array(
						'type'          => 'text',
						'label'         => __('Border Size', 'wpzabb'),
						'description'   => 'px',
						'maxlength'     => '3',
						'size'          => '5',
						'placeholder'   => '2'
					),
					'more_btn_border_radius' => array(
						'type'          => 'text',
						'label'         => __( 'Round Corners', 'wpzabb' ),
						'default'       => '2',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
					'more_btn_width'	 => array(
						'type'		  => 'select',
						'label'		  => __( 'Width', 'wpzabb' ),
						'default'		  => 'auto',
						'options'		  => array(
							'auto'		   => _x( 'Auto', 'Width.', 'wpzabb' ),
							'full'		   => __( 'Full Width', 'wpzabb' ),
						),
					),
				),
			),
		),
	),
));

?>