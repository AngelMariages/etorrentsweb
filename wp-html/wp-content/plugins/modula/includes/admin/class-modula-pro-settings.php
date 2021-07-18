<?php

/**
 *
 */
class Modula_PRO_Settings {

	function __construct() {

		// New CSS & JS for PRO version
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'modula_scripts_before_wp_modula', array( $this, 'modula_pro_backbone' ) );
		add_action( 'modula_scripts_after_wp_modula', array( $this, 'modula_pro_main' ) );
		add_action( 'modula_defaults_scripts_after_wp_modula', array( $this, 'modula_pro_main' ) );

		// Scripts used for modula-defaults
		add_action( 'modula_defaults_scripts_before_wp_modula', array( $this, 'modula_defaults_pro_backbone' ) );

		// Filter Modula Tabs
		add_filter( 'modula_gallery_tabs', array( $this, 'modula_pro_tabs' ) );

		// Filter Modula Fields
		add_filter( 'modula_gallery_fields', array( $this, 'modula_pro_fields' ) );


		// Filter Cursors
		add_filter( 'modula_available_cursor', array( $this, 'modula_pro_cursor' ) );
		add_filter( 'modula_pro_cursor', '__return_false' );

		// Add cursor upload field type

		add_filter( 'modula_render_cursor_upload_field_type', array( $this, 'cursor_upload_field_type' ), 10, 5 );

		// Flter Hover Effects
		add_filter( 'modula_available_hover_effects', array( $this, 'modula_pro_hover_effects' ) );
		add_filter( 'modula_pro_hover_effects', '__return_false' );

		// Create preview of new hover effects
		add_filter( 'modula_hover_effect_preview', array( $this, 'modula_pro_hover_effect_preview' ), 10, 2 );

		// Create filters field
		add_filter( 'modula_render_filters_field_type', array( $this, 'modula_pro_filters_field' ), 10, 3 );
		add_filter( 'modula_render_font-selector_field_type', array(
			$this,
			'modula_pro_fontselector_field',
		), 10, 3 );

		// Custom CSS gallery ID
		add_filter( 'modula_render_field_type', array( $this, 'custom_css_gallery_id' ), 10, 3 );

		// Filter Defaults
		add_filter( 'modula_lite_default_settings', array( $this, 'default_settings' ) );

		// Save Filters for our items
		add_filter( 'modula_gallery_image_attributes', array( $this, 'add_pro_item_fields' ) );

		/* Add templates for our plugin */
		add_action( 'admin_footer', array( $this, 'print_modula_pro_templates' ) );

		// Add new input for item
		add_action( 'modula_item_extra_fields', array( $this, 'extra_item_fields' ) );

		// Add license tab
		add_filter( 'modula_admin_page_tabs', array( $this, 'add_license_tab' ) );

		/* Show pro vs lite tab content */
		add_action( 'modula_admin_tab_licenses', array( $this, 'show_licenses_tab' ) );


		/* Add values for sanitizations */
		add_filter( 'modula_effect_values', array( $this, 'add_effects_pro' ) );


		// Sanitize filter's fields
		add_filter( 'modula_settings_field_sanitization', array( $this, 'sanitize_settings' ), 20, 4 );
		add_filter( 'modula_image_field_sanitization', array( $this, 'sanitize_image_fields' ), 20, 3 );

		// Add Bulk edit button
		add_action( 'modula_after_helper_grid', array( $this, 'show_bulk_edit_button' ) );

		// Render FIeld Type
		add_filter( 'modula_render_content_field_type', array( $this, 'render_field_type' ), 10, 3 );

		// Change Field Type Format
		add_filter( 'modula_field_type_content_format', array( $this, 'content_format' ), 10, 2 );
		add_filter( 'modula_field_type_select_format', array( $this, 'filter_link_style_extra' ), 10, 2 );

		// Add Sorting Metabox
		/* Fire our meta box setup function on the post editor screen. */
		add_action( 'load-post.php', array( $this, 'meta_boxes_setup' ) );
		add_action( 'load-post-new.php', array( $this, 'meta_boxes_setup' ) );

		// Add replace button
		add_action( 'modula_admin_gallery_image_after_actions', array( $this, 'add_replace_button' ) );

		add_action('modula_elementor_after_enqueue_styles',array($this,'elementor_enqueued_styles'));


	}

	public function admin_scripts( $hook ) {

		global $id, $post;


		// Get current screen.
		$screen = get_current_screen();

		// Add license protection scrip
		// Previous was inline
		if ( 'modula-gallery_page_modula' == $screen->base ) {
			wp_enqueue_script( 'modula-license-protection', MODULA_PRO_URL . 'assets/js/license-protection.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
		}


		// Check if is modula custom post type
		if ( 'modula-gallery' !== $screen->post_type ) {
			return;
		}

		// Set the post_id
		$post_id = isset( $post->ID ) ? $post->ID : (int)$id;


		if ( 'post-new.php' == $hook || 'post.php' == $hook ) {

			// Modula PRO hover effects
			wp_enqueue_style( 'modula-pro-effects', MODULA_PRO_URL . 'assets/css/effects.min.css' );
			wp_enqueue_style( 'modula-selectize', MODULA_URL . 'assets/css/admin/selectize.min.css' );
			wp_enqueue_style( 'modula-selectize-default', MODULA_URL . 'assets/css/admin/selectize.default.css' );
			wp_enqueue_style( 'modula-pro-style', MODULA_PRO_URL . 'assets/css/modula-pro-admin-style.css' );

		}

	}

	public function modula_pro_backbone() {

		// Modula PRO effects
		wp_enqueue_script( 'modula-selectize', MODULA_URL . 'assets/js/admin/selectize.js', array( 'jquery' ), MODULA_LITE_VERSION, true );
		wp_enqueue_script( 'modula-pro-replace', MODULA_PRO_URL . 'assets/js/wp-modula-pro-replace.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-filters', MODULA_PRO_URL . 'assets/js/wp-modula-filters.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-items', MODULA_PRO_URL . 'assets/js/wp-modula-pro-items.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-upload', MODULA_PRO_URL . 'assets/js/wp-modula-pro-upload.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-bulkedit', MODULA_PRO_URL . 'assets/js/wp-modula-bulkedit.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-sorting', MODULA_PRO_URL . 'assets/js/wp-modula-sorting.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-settings', MODULA_PRO_URL . 'assets/js/wp-modula-pro-settings.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-conditions', MODULA_PRO_URL . 'assets/js/wp-modula-pro-conditions.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-cursor', MODULA_PRO_URL . 'assets/js/modula-pro-cursor.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		$colorpicker_l10n = array(
			'clear'            => __( 'Clear', 'modula-pro' ),
			'clearAriaLabel'   => __( 'Clear color', 'modula-pro' ),
			'defaultString'    => __( 'Default', 'modula-pro' ),
			'defaultAriaLabel' => __( 'Select default color', 'modula-pro' ),
			'pick'             => __( 'Select Color', 'modula-pro' ),
			'defaultLabel'     => __( 'Color value', 'modula-pro' ),
		);

		wp_enqueue_script( 'modula-pro-wp-color-picker-alpha', MODULA_PRO_URL . 'assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '2.1.3', true );
		wp_localize_script( 'modula-pro-wp-color-picker-alpha', 'wpColorPickerL10n', $colorpicker_l10n );

	}

	public function modula_pro_main() {
		// Modula PRO main JS
		wp_enqueue_script( 'modula-pro', MODULA_PRO_URL . 'assets/js/wp-modula-pro.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		$fonts = json_decode( Modula_Pro_Helper::get_google_fonts() );
		wp_localize_script( 'modula', 'modulaFonts', $fonts );


	}

	/**
	 * Enqueue scripts used for modula-defaults
	 *
	 * @since 2.3.3
	 */
	public function modula_defaults_pro_backbone() {

		// Modula PRO effects
		wp_enqueue_script( 'modula-selectize', MODULA_URL . 'assets/js/admin/selectize.js', array( 'jquery' ), MODULA_LITE_VERSION, true );
		wp_enqueue_script( 'modula-pro-conditions', MODULA_PRO_URL . 'assets/js/wp-modula-pro-conditions.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-replace', MODULA_PRO_URL . 'assets/js/wp-modula-pro-replace.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-filters', MODULA_PRO_URL . 'assets/js/wp-modula-filters.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-cursor', MODULA_PRO_URL . 'assets/js/modula-pro-cursor.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
		wp_enqueue_script( 'modula-pro-settings', MODULA_PRO_URL . 'assets/js/wp-modula-pro-settings.js', array( 'jquery', 'jquery-ui-sortable' ), MODULA_PRO_VERSION, true );
		$colorpicker_l10n = array(
			'clear'            => __( 'Clear', 'modula-pro' ),
			'clearAriaLabel'   => __( 'Clear color', 'modula-pro' ),
			'defaultString'    => __( 'Default', 'modula-pro' ),
			'defaultAriaLabel' => __( 'Select default color', 'modula-pro' ),
			'pick'             => __( 'Select Color', 'modula-pro' ),
			'defaultLabel'     => __( 'Color value', 'modula-pro' ),
		);

		wp_enqueue_script( 'modula-pro-wp-color-picker-alpha', MODULA_PRO_URL . 'assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '2.1.3', true );
		wp_localize_script( 'modula-pro-wp-color-picker-alpha', 'wpColorPickerL10n', $colorpicker_l10n );

	}

	// Modula PRO Tabs
	public function modula_pro_tabs( $tabs ) {

		if ( !isset( $tabs['filters'] ) ) {
			$tabs['filters'] = array(
				'label'    => esc_html__( 'Filters', 'modula-pro' ),
				"icon"     => "dashicons dashicons-filter",
				'priority' => 15,
			);
		} else {
			unset( $tabs['filters']['badge'] );
		}

		$tabs['video']['badge']            = esc_html__( 'not installed', 'modula-pro' );
		$tabs['misc']['badge']             = esc_html__( 'not installed', 'modula-pro' );
		$tabs['slideshow']['badge']        = esc_html__( 'not installed', 'modula-pro' );
		$tabs['password_protect']['badge'] = esc_html__( 'not installed', 'modula-pro' );
		$tabs['watermark']['badge']        = esc_html__( 'not installed', 'modula-pro' );
		$tabs['exif']['badge']             = esc_html__( 'not installed', 'modula-pro' );
		$tabs['download']['badge']         = esc_html__( 'not installed', 'modula-pro' );
		$tabs['zoom']['badge']             = esc_html__( 'not installed', 'modula-pro' );


		return $tabs;
	}

	// Modula PRO Fields
	public function modula_pro_fields( $fields ) {

		// Remove restrictions on lightboxes
		if ( isset( $fields['general']['lightbox'] ) ) {

			// Remove disabled lightboxes
			if ( isset( $fields['general']['lightbox']['disabled'] ) ) {
				unset( $fields['general']['lightbox']['disabled'] );
			}

		}

		$fields['lightboxes']['loop_lightbox'] = array(
			"name"        => esc_html__( 'Loop navigation', 'modula-pro' ),
			"type"        => "toggle",
			"description" => esc_html__( 'Enable this to allow loop navigation inside lightbox.', 'modula-pro' ),
			"default"     => 0,
			'priority'    => 1,
		);

		// Add image title to lightbox
		$fields['lightboxes']['showTitleLightbox'] = array(
			"name"        => esc_html__( 'Show image title', 'modula-pro' ),
			"type"        => "toggle",
			"description" => esc_html__( 'Toggle on to show the image title in the lightbox above the caption.', 'modula-pro' ),
			'default'     => 0,
			"priority"    => 9,
		);

		// Add image caption to lightbox
		$fields['lightboxes']['showCaptionLightbox'] = array(
			"name"        => esc_html__( 'Show image caption', 'modula-pro' ),
			"type"        => "toggle",
			"description" => esc_html__( 'Toggle on to show the image caption in the lightbox.', 'modula-pro' ),
			'default'     => 1,
			"priority"    => 9,

		);

		$fields['lightboxes']['captionPosition'] = array(
			"name"        => esc_html__( 'Title and caption position', 'modula-pro' ),
			"type"        => "select",
			"description" => esc_html__( 'Select the position of the caption and title inside the lightbox.', 'modula-pro' ),
			"values"      => array(
				'left'   => esc_html__( 'Left', 'modula-pro' ),
				'right'  => esc_html__( 'Right', 'modula-pro' ),
				'center' => esc_html__( 'Center', 'modula-pro' ),
			),
			'default'     => 'left',
			"is_child"    => true,
			"priority"    => 9,

		);


		// Fancybox options
		$fields['lightboxes']['lightbox_keyboard'] = array(
			"name"        => esc_html__( 'Keyboard navigation', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enable or disable keyboard navigation inside lightbox.', 'modula-pro' ),
			"priority"    => 20
		);

		$fields['lightboxes']['lightbox_wheel'] = array(
			"name"        => esc_html__( 'Mousewheel navigation', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enable or disable mousewheel navigation inside lightbox.', 'modula-pro' ),
			"priority"    => 20
		);

		$fields['lightboxes']['lightbox_toolbar'] = array(
			"name"        => esc_html__( 'Toolbar', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 1,
			"description" => esc_html__( 'Display the toolbar which contains the action buttons on top right corner.', 'modula-pro' ),
			"priority"    => 30
		);

		$fields['lightboxes']['lightbox_close'] = array(
			"name"        => esc_html__( 'Close button', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 1,
			"description" => esc_html__( 'Show or hide close button in lightbox toolbar.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 31
		);

		$fields['lightboxes']['lightbox_thumbs'] = array(
			"name"        => esc_html__( 'Thumbnails button', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Show or hide thumbnails button in lightbox toolbar.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 31
		);

		$fields['lightboxes']['lightbox_download'] = array(
			"name"        => esc_html__( 'Download button', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Show or hide download button in lightbox toolbar.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 31
		);

		$fields['lightboxes']['lightbox_zoom'] = array(
			"name"        => esc_html__( 'Zoom button', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Show or hide zoom button in lightbox toolbar.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 31
		);

		$fields['lightboxes']['lightbox_share'] = array(
			"name"        => esc_html__( 'Share button', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Show or hide share button in lightbox toolbar.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 31
		);

		$fields['lightboxes']['lightbox_facebook'] = array(
			"name"        => esc_html__( 'Facebook', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enables Facebook social sharing in FancyBox lightbox.', 'modula-pro' ),
			"is_child"    => 'two',
			"priority"    => 32
		);

		$fields['lightboxes']['lightbox_twitter'] = array(
			"name"        => esc_html__( 'Twitter', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enables Twitter social sharing in FancyBox lightbox.', 'modula-pro' ),
			"is_child"    => 'two',
			"priority"    => 32
		);
		$fields['lightboxes']['lightbox_pinterest'] = array(
			"name"        => esc_html__( 'Pinterest', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enables Pinterest social sharing in FancyBox lightbox.', 'modula-pro' ),
			"is_child"    => 'two',
			"priority"    => 32
		);
		$fields['lightboxes']['lightbox_whatsapp'] = array(
			"name"        => esc_html__( 'WhatsApp', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enables WhatsApp social sharing in FancyBox lightbox.', 'modula-pro' ),
			"is_child"    => 'two',
			"priority"    => 32
		);
		$fields['lightboxes']['lightbox_linkedin'] = array(
			"name"        => esc_html__( 'LinkedIn', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enables LinkedIn social sharing in FancyBox lightbox.', 'modula-pro' ),
			"is_child"    => 'two',
			"priority"    => 32
		);

		$fields['lightboxes']['lightbox_email'] = array(
			"name"        => esc_html__( 'Email', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enables Email sharing in FancyBox lightbox.', 'modula-pro' ),
			"is_child"    => 'two',
			"priority"    => 33
		);
		$fields['lightboxes']["lightboxEmailSubject"]   = array(
			"name"        => esc_html__( 'Email subject', 'modula-pro' ),
			"type"        => "text",
			"default"     => esc_html__( 'Check out this awesome image !!','modula-pro' ),
			"description" => esc_html__( 'Email subject text, used in Lightbox social sharing', 'modula-pro' ),
			'is_child'    => 'two',
			'priority'    => 34,
		);
		$fields['lightboxes']["lightboxEmailMessage"]   = array(
			"name"        => esc_html__( 'Email message', 'modula-pro' ),
			"type"        => "textarea-placeholder",
			"values"      => array(
				'%%image_link%%'      => esc_html__( 'Image Link', 'modula-pro' ),
				'%%gallery_link%%'    => esc_html__( 'Gallery Link', 'modula-pro' ),
			),
			"default"     => esc_html__( 'Here is the link to the image : %%image_link%% and this is the link to the gallery : %%gallery_link%% ','modula-pro'),
			"description" => esc_html__( 'Text for message body, used in Lightbox social sharing', 'modula-pro' ),
			'is_child'    => 'two',
			'priority'    => 34,
		);

		$fields['lightboxes']['lightbox_clickSlide'] = array(
			"name"        => esc_html__( 'Close on slide click', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Close the slide if user clicks on slide( not image ).', 'modula-pro' ),
			"priority"    => 35
		);

		$fields['lightboxes']['lightbox_dblclickSlide'] = array(
			"name"        => esc_html__( 'Close on slide double click', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Close the slide if user double clicks on slide( not image ).', 'modula-pro' ),
			"priority"    => 35
		);

		$fields['lightboxes']['lightbox_infobar'] = array(
			"name"        => esc_html__( 'Infobar', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 1,
			"description" => esc_html__( 'Display the counter at the top left corner.', 'modula-pro' ),
			"priority"    => 35
		);

		$fields['lightboxes']['lightbox_animationEffect'] = array(
			"name"        => esc_html__( 'Open/Close animation', 'modula-pro' ),
			"type"        => "select",
			"default"     => 'false',
			'values'      => array(
				'false'       => esc_html__( 'None', 'modula-pro' ),
				'zoom'        => esc_html__( 'Zoom', 'modula-pro' ),
				'fade'        => esc_html__( 'Fade', 'modula-pro' ),
				'zoom-in-out' => esc_html__( 'Zoom-in-out', 'modula-pro' )
			),
			"description" => esc_html__( 'Choose the open/close animation effect of the lightbox.', 'modula-pro' ),
			"priority"    => 38
		);

		$fields['lightboxes']['lightbox_animationDuration'] = array(
			"name"        => esc_html__( 'Open/Close animation speed', 'modula-pro' ),
			"type"        => "text",
			"default"     => '366',
			"description" => esc_html__( 'Enter how long the duration of the open/close lightbox animation should last in ms.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 39
		);

		$fields['lightboxes']['lightbox_transitionEffect'] = array(
			"name"        => esc_html__( 'Transition effect', 'modula-pro' ),
			"type"        => "select",
			"default"     => 'false',
			'values'      => array(
				'false'       => esc_html__( 'None', 'modula-pro' ),
				'fade'        => esc_html__( 'Fade', 'modula-pro' ),
				'slide'       => esc_html__( 'Slide', 'modula-pro' ),
				'circular'    => esc_html__( 'Circular', 'modula-pro' ),
				'tube'        => esc_html__( 'Tube', 'modula-pro' ),
				'zoom-in-out' => esc_html__( 'Zoom-in-out', 'modula-pro' ),
				'rotate'      => esc_html__( 'Rotate', 'modula-pro' )
			),
			"description" => esc_html__( 'Choose the lightbox transition effect between slides.', 'modula-pro' ),
			"priority"    => 40
		);

		$fields['lightboxes']['lightbox_transitionDuration'] = array(
			"name"        => esc_html__( 'Transition speed', 'modula-pro' ),
			"type"        => "text",
			"default"     => '366',
			"description" => esc_html__( 'Enter the desired duration in ms for transition animation of the slides.', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 41
		);

		//@todo: for the comment we will comment this. maybe in the future option will be used
		/*$fields['lightboxes']['lightbox_gutter'] = array(
				"name"        => esc_html__( 'Lightbox gutter', 'modula-pro' ),
				"type"        => "ui-slider",
				"default"     => 0,
				'min'         => 0,
				'max'         => 100,
				'step'        => 1,
				"description" => esc_html__( 'Horizontal space between slides', 'modula-pro' ),
				"priority"    => 40
		);*/

		$fields['lightboxes']['showAllOnLightbox'] = array(
			"name"        => esc_html__( 'Show all images', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Toggle ON to show all images', 'modula-pro' ),
			"priority"    => 50
		);

		// @todo: for the moment comment these. will see in the future if we use them and after we update fancybox
		/* $fields['lightboxes']['lightbox_hideScrollbar'] = array(
				   "name"        => esc_html__( 'Hide Scrollbar ', 'modula-pro' ),
				   "type"        => "toggle",
				   "default"     => 0,
				   "description" => esc_html__( 'Hide browser vertical scrollbars.', 'modula-pro' ),
				   "priority" => 40
		   );*/

		// @todo: for the moment comment these. will see in the future if we use them and after we update fancybox
		/* $fields['lightboxes']['lightbox_autoFocus'] = array(
				   "name"        => esc_html__( 'AutoFocus ', 'modula-pro' ),
				   "type"        => "toggle",
				   "default"     => 0,
				   "description" => esc_html__( 'Try to focus on the first focusable element after opening.', 'modula-pro' ),
				   "priority" => 40
		   );

		   $fields['lightboxes']['lightbox_backFocus'] = array(
				   "name"        => esc_html__( 'BackFocus ', 'modula-pro' ),
				   "type"        => "toggle",
				   "default"     => 0,
				   "description" => esc_html__( 'Put focus back to active element after closing.', 'modula-pro' ),
				   "priority" => 40
		   );*/

		/*$fields['lightboxes']['lightbox_trapFocus'] = array(
				"name"        => esc_html__( 'TrapFocus ', 'modula-pro' ),
				"type"        => "toggle",
				"default"     => 0,
				"description" => esc_html__( 'Do not let user to focus on element outside modal content.', 'modula-pro' ),
				"priority" => 40
		);*/

		$fields['lightboxes']['lightbox_touch'] = array(
			"name"        => esc_html__( 'Allow swiping ', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Allow panning/swiping', 'modula-pro' ),
			"priority"    => 50
		);

		$fields['lightboxes']['lightbox_thumbsAutoStart'] = array(
			"name"        => esc_html__( 'Auto start thumbnails ', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Display thumbnails on lightbox opening.', 'modula-pro' ),
			"priority"    => 50
		);

		$fields['lightboxes']['lightbox_thumbsAxis'] = array(
			"name"        => esc_html__( 'Thumb axis ', 'modula-pro' ),
			"type"        => "select",
			"default"     => 'y',
			"values"      => array(
				"y" => esc_html__( 'Vertical Scrolling', 'modula-pro' ),
				"x" => esc_html__( 'Horizontal Scrolling', 'modula-pro' )
			),
			"description" => esc_html__( 'Select vertical or horizontal scrolling for thumbnails.', 'modula-pro' ),
			"priority"    => 50
		);

		$fields['lightboxes']['lightbox_bottomThumbs'] = array(
			"name"        => esc_html__( 'Thumbnails at bottom ', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Place the thumbnails at the bottom of the lightbox. This will automatically put `y` axis for thumbnails.', 'modula-pro' ),
			"priority"    => 50
		);

		$fields['lightboxes']['lightbox_background_color'] =  array(
			"name"        => esc_html__( 'Lightbox background color', 'modula-pro' ),
			"type"        => "color",
			'alpha'       => true,
			"description" => esc_html__( 'Set the lightbox background color', 'modula-pro' ),
			"default"     => "",
			'priority'    => 60,
		);

		// Add Max Image field
		$fields['general']['maxImagesCount'] = array(
			"name"        => esc_html__( 'Max Images Count', 'modula-pro' ),
			"type"        => "text",
			"default"     => 0,
			"description" => esc_html__( 'Use 0 to show all images', 'modula-pro' ),
			"priority"    => 80
		);


		// Add filters settings
		$fields['filters'] = array(
			'filters'                  => array(
				"name"     => esc_html__( 'Filters', 'modula-pro' ),
				"type"     => "filters",
				'priority' => 10,
			),
			'dropdownFilters'         => array(
				"name"        => esc_html__( 'Dropdown Filters', 'modula-pro' ),
				"type"        => "toggle",
				"default"     => 0,
				"description" => esc_html__( 'Enable this option to change the appereance of your filters to dropdown filters','modula-pro'),
				"priority"    => 15,
			),
			'filterClick'              => array(
				"name"        => esc_html__( 'Reload Page On Filter Click', 'modula-pro' ),
				"type"        => "toggle",
				"default"     => 0,
				"description" => esc_html__( 'Choose whether pages reload to sort images when a filter is clicked.', 'modula-pro' ),
				'priority'    => 20,
			),
			'hideAllFilter'            => array(
				"name"        => esc_html__( 'Hide "All" filter', 'modula-pro' ),
				"type"        => "toggle",
				"default"     => 0,
				"description" => esc_html__( 'Choose to show or hide the “All” filter.', 'modula-pro' ),
				'priority'    => 30,
			),
			'allFilterLabel'           => array(
				"name"        => esc_html__( 'Text For "All" filter', 'modula-pro' ),
				"type"        => "text",
				'default'     => esc_html__( 'All', 'modula-pro' ),
				"description" => esc_html__( 'Set the label you want to use for the “All” filter that will contain all the images in your gallery.', 'modula-pro' ),
				"is_child"    => true,
				'priority'    => 30,
			),
			'filterStyle'              => array(
				"name"        => esc_html__( 'Filter link style', 'modula-pro' ),
				"type"        => "select",
				"values"      => array(
					'default'   => esc_html__( 'Default style', 'modula-pro' ),
					'antonio'   => esc_html__( 'Antonio', 'modula-pro' ),
					'ariel'     => esc_html__( 'Ariel', 'modula-pro' ),
					'caliban'   => esc_html__( 'Caliban', 'modula-pro' ),
					'ceres'     => esc_html__( 'Ceres', 'modula-pro' ),
					'cordelia'  => esc_html__( 'Cordelia', 'modula-pro' ),
					'ferdinand' => esc_html__( 'Ferdinand', 'modula-pro' ),
					'francisco' => esc_html__( 'Francisco', 'modula-pro' ),
					'horatio'   => esc_html__( 'Horatio', 'modula-pro' ),
					'invulner'  => esc_html__( 'Invulner', 'modula-pro' ),
					'iris'      => esc_html__( 'Iris', 'modula-pro' ),
					// 'juliet'        => esc_html__( 'Juliet', 'modula-pro' ),
					'juno'      => esc_html__( 'Juno', 'modula-pro' ),
					'luce'      => esc_html__( 'Luce', 'modula-pro' ),
					'maria'     => esc_html__( 'Maria', 'modula-pro' ),
					'miranda'   => esc_html__( 'Miranda', 'modula-pro' ),
					'prospero'  => esc_html__( 'Prospero', 'modula-pro' ),
					'sebastian' => esc_html__( 'Sebastian', 'modula-pro' ),
					'shylock'   => esc_html__( 'Shylock', 'modula-pro' ),
					'stephano'  => esc_html__( 'Stephano', 'modula-pro' ),
					'tantalid'  => esc_html__( 'Tantalid', 'modula-pro' ),
					'trinculo'  => esc_html__( 'Trinculo', 'modula-pro' ),
					'valentine' => esc_html__( 'Valentine', 'modula-pro' ),
					'viola'     => esc_html__( 'Viola', 'modula-pro' ),
				),
				'default'     => 'default',
				"description" => esc_html__( 'Choose the style for the filter links you want to use.', 'modula-pro' ),
				'priority'    => 30,
			),
			'filterLinkColor'          => array(
				"name"        => esc_html__( 'Filter link color', 'modula-pro' ),
				"type"        => "color",
				'alpha'       => true,
				'default'     => '',
				"description" => esc_html__( 'Choose the color for filter links you want to use', 'modula-pro' ),
				'priority'    => 30,
			),
			'filterLinkHoverColor'     => array(
				"name"        => esc_html__( 'Filter link hover color', 'modula-pro' ),
				"type"        => "color",
				'alpha'       => true,
				'default'     => '',
				"description" => esc_html__( 'Choose the color for filter hover links you want to use', 'modula-pro' ),
				'priority'    => 30,
			),
			'defaultActiveFilter'      => array(
				"name"        => esc_html__( 'Default active filter', 'modula-pro' ),
				"type"        => "select",
				"values"      => modula_pro_current_active_filter(),
				"default"     => 'all',
				"description" => esc_html__( 'Type a default active filter on which the gallery should start. It should not contain "," , "."', 'modula-pro' ),
				'priority'    => 30,
			),
			'filterPositioning'        => array(
				"name"        => esc_html__( 'Fiter positioning', 'modula-pro' ),
				"type"        => "select",
				"values"      => array(
					'top'        => esc_html__( 'Top', 'modula-pro' ),
					'bottom'     => esc_html__( 'Bottom', 'modula-pro' ),
					'left'       => esc_html__( 'Left', 'modula-pro' ),
					'right'      => esc_html__( 'Right', 'modula-pro' ),
					'top_bottom' => esc_html__( 'Top & Bottom', 'modula-pro' ),
					'left_right' => esc_html__( 'Left & Right', 'modula-pro' ),
				),
				"default"     => 'top',
				"description" => esc_html__( 'Choose the position of the filters', 'modula-pro' ),
				'priority'    => 30,
			),
			'filterTextAlignment'      => array(
				"name"        => esc_html__( 'Filters Text Align', 'modula-pro' ),
				"type"        => "select",
				"values"      => array(
					'none'   => esc_html__( 'None', 'modula-pro' ),
					'left'   => esc_html__( 'Left', 'modula-pro' ),
					'center' => esc_html__( 'Center', 'modula-pro' ),
					'right'  => esc_html__( 'Right', 'modula-pro' ),
				),
				"default"     => 'none',
				"description" => esc_html__( 'None will inherit from the theme.', 'modula-pro' ),
				'priority'    => 30,
			),
			'enableCollapsibleFilters' => array(
				"name"        => esc_html__( 'Collapsible Filters', 'modula-pro' ),
				"type"        => "toggle",
				"default"     => 0,
				"description" => esc_html__( 'Check to enable Collapsible Filters (available only on mobile).', 'modula-pro' ),
				'priority'    => 30,
			),

			'collapsibleActionText' => array(
				"name"        => esc_html__( 'Collapsible Action Text', 'modula-pro' ),
				"type"        => "text",
				"default"     => esc_html__( 'Filter by', 'modula-pro' ),
				"description" => esc_html__( 'Text used for Collapsible Action button.', 'modula-pro' ),
				"is_child"    => true,
				'priority'    => 30,
			),
		);

		$fields['captions']['show_gallery_title'] = array(
			"name"        => esc_html__( 'Show Gallery Title ', 'modula-pro' ),
			"type"        => "toggle",
			"default"     => 0,
			"description" => esc_html__( 'Enable this to show the title of your gallery.', 'modula-pro' ),
			'priority'    => 5,

		);

		$fields['captions']['gallery_title_type'] = array(
			"name"        => esc_html__( 'Title type ', 'modula-pro' ),
			"type"        => "select",
			"default"     => 'p',
			'values'      => array(
				'p'  => esc_html__( 'Paragraph', 'modula-pro' ),
				'h1' => esc_html__( 'Heading 1', 'modula-pro' ),
				'h2' => esc_html__( 'Heading 2', 'modula-pro' ),
				'h3' => esc_html__( 'Heading 3', 'modula-pro' ),
				'h4' => esc_html__( 'Heading 4', 'modula-pro' ),
				'h5' => esc_html__( 'Heading 5', 'modula-pro' ),
				'h6' => esc_html__( 'Heading 6', 'modula-pro' ),
			),
			"description" => esc_html__( 'Choose what kind of wrapper should the gallery title have.', 'modula-pro' ),
			'priority'    => 6,
			'is_child'    => true

		);

		$fields['captions']['titleFontFamily'] = array(
			"name"        => esc_html__( 'Title Font', 'modula-pro' ),
			"type"        => "font-selector",
			"description" => esc_html__( 'Set the font family of your title.', 'modula-pro' ),
			'values'      => array(),
			'default'     => 'Default',
			"is_child"    => true,
			"priority"    => 41,

		);

		$fields['captions']['titleFontWeight'] = array(
			"name"        => esc_html__( 'Title Font Weight', 'modula-pro' ),
			"type"        => "select",
			"description" => esc_html__( 'Set the font weight of your title.', 'modula-pro' ),
			'values'      => array(
				'default' => esc_html__( 'Default', 'modula-pro' ),
				'300'     => esc_html__( 'Light', 'modula-pro' ),
				'400'     => esc_html__( 'Regular', 'modula-pro' ),
				'700'     => esc_html__( 'Bold', 'modula-pro' ),
			),
			'default'     => '400',
			"is_child"    => true,
			"priority"    => 42,
		);


		$fields['captions']['captionsFontFamily'] = array(
			"name"        => esc_html__( 'Captions Font', 'modula-pro' ),
			"type"        => "font-selector",
			"description" => esc_html__( 'Set the font family of your captions.', 'modula-pro' ),
			'values'      => array(),
			'default'     => 'Default',
			"is_child"    => true,
			"priority"    => 81,

		);

		$fields['captions']['captionFontWeight'] = array(
			"name"        => esc_html__( 'Captions Font Style', 'modula-pro' ),
			"type"        => "select",
			"description" => esc_html__( 'Set the font style of your captions.', 'modula-pro' ),
			"values"      => array(
				'normal' => esc_html__( 'Default', 'modula-pro' ),
				'300'    => esc_html__( 'Light', 'modula-pro' ),
				'400'    => esc_html__( 'Regular', 'modula-pro' ),
				'700'    => esc_html__( 'Bold', 'modula-pro' ),
			),
			'default'     => 'normal',
			"is_child"    => true,
			"priority"    => 82,

		);

		// Add image loaded effects
		$fields['image-loaded-effects']['loadedRotate'] = array(
			"name"        => esc_html__( 'Rotate', 'modula-pro' ),
			"description" => esc_html__( 'To add a rotate effect set this value to positive for counter-clockwise rotations and negative for clockwise rotations.', 'modula-pro' ),
			"type"        => "ui-slider",
			"min"         => -180,
			"max"         => 180,
			"default"     => 0,
			'priority'    => 20,
		);
		$fields['image-loaded-effects']['loadedHSlide'] = array(
			"name"        => esc_html__( 'Horizontal Slide', 'modula-pro' ),
			"description" => esc_html__( 'Set this to a negative value if you want your gallery’s image to slide in from the left or to positive if you would prefer for them to slide in from the right.', 'modula-pro' ),
			"type"        => "ui-slider",
			"min"         => -100,
			"max"         => 100,
			"default"     => 0,
			'priority'    => 30,
		);
		$fields['image-loaded-effects']['loadedVSlide'] = array(
			"name"        => esc_html__( 'Vertical Slide', 'modula-pro' ),
			"description" => esc_html__( 'Set this to a negative value if you want your gallery’s image to slide in from the top or to positive if you would prefer for them to slide in from the bottom.', 'modula-pro' ),
			"type"        => "ui-slider",
			"min"         => -100,
			"max"         => 100,
			"default"     => 0,
			'priority'    => 40,
		);

		// Hover Effects and Cursor
		if ( isset( $fields['hover-effect']['cursor'] ) ) {
			if ( isset( $fields['hover-effect']['cursor']['disabled'] ) ) {
				unset( $fields['hover-effect']['cursor']['disabled'] );
			}

			$fields['hover-effect']['cursor']['values'] = array(
				'pointer'     => esc_html__( 'Pointer', 'modula-pro' ),
				'zoom-in'     => esc_html__( 'Magnifying Glass', 'modula-pro' ),
				'wait'        => esc_html__( 'Loading', 'modula-pro' ),
				'cell'        => esc_html__( 'Cell', 'modula-pro' ),
				'crosshair'   => esc_html__( 'Crosshair', 'modula-pro' ),
				'nesw-resize' => esc_html__( 'Resize 1', 'modula-pro' ),
				'nwse-resize' => esc_html__( 'Resize 2', 'modula-pro' ),
				'custom'      => esc_html__( 'Custom', 'modula-pro' ),
			);

		}

		$fields['hover-effect']['uploadCursor'] = array(
			"name"        => esc_html__( 'Upload your cursor', 'modula-pro' ),
			"type"        => "cursor_upload",
			"default"     => 0,
			"description" => esc_html__( 'The cursor size has to be 128px x 128px or smaller , otherwise the cursor will not work .','modula-pro' ),
			'class'       => 'button insert-media-url',
			'button_text' => esc_html__( 'Upload Cursor', 'modula-pro' ),
			"is_child"    => true,
			"priority"    => 14

		);

		$fields['hover-effect']['hoverColor']   = array(
			"name"        => esc_html__( 'Hover Color', 'modula-pro' ),
			"type"        => "color",
			"description" => '',
			"default"     => "#ffffff",
			'priority'    => 20,
		);
		$fields['hover-effect']['hoverOpacity'] = array(
			"name"        => esc_html__( 'Hover Opacity', 'modula-pro' ),
			"description" => esc_html__( 'Adjust the transparency of your chosen hover effect.', 'modula-pro' ),
			"type"        => "ui-slider",
			"min"         => 0,
			"max"         => 100,
			"default"     => 50,
			'priority'    => 30,
		);

		if(isset($fields['lightboxes']['lightbox'])){
			$fields['lightboxes']['lightbox']['afterrow'] =  esc_html__('You can combine images that open in lightbox with images that open URL by completing the image URL field from Edit Image settings.','modula-pro');
		}

		// Check if modula video it's installed & activated
		if ( !class_exists( 'Modula_Video' ) ) {
			$fields['video']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'In order to add videos to your gallery you\'ll need to install the extension Modula Video from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

		// Check if modula speed up it's installed & activated
		if ( !class_exists( 'Modula_SpeedUp' ) ) {
			$fields['speedup']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'By installing the Modula Speed UP extension, you\'ll benefit from unlimited image optimization through ShortPixel\'s servers as well as unlimited CDN image delivery through StackPath. You\'ll need to install the extension Modula Speed Up from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

		// Check if both Modula Deeplink and Modula Protection are installed & activated
		if ( !class_exists( 'Modula_Deeplink' ) && !class_exists( 'Modula_Protection' ) ) {
			$fields['misc']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'In order to extend deeplink functionality or add right click protection to your galleries you\'ll need to install the extensions Modula Deeplink and Modula Protection from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

		// Check if Modula Protection is activated and Modula Deeplink is inactive
		if ( !class_exists( 'Modula_Deeplink' ) && class_exists( 'Modula_Protection' ) ) {
			$fields['misc']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'In order to extend deeplink functionality to your galleries you\'ll need to install the extension Modula Deeplink from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

		// Check if Modula Protection is inactive and Modula Deeplink is activated
		if ( class_exists( 'Modula_Deeplink' ) && !class_exists( 'Modula_Protection' ) ) {
			$fields['misc']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'In order to add right click protection to your galleries you\'ll need to install the extension Modula Protection from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

		// Check if Modula Watermark is installed & activated
		if ( !class_exists( 'Modula_Watermark' ) ) {
			$fields['watermark']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'In order to add watermark to your image galleries you\'ll need to install the extension Modula Watermark from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

		// Check if Modula Password Protect is installed & activated
		if ( !class_exists( 'Modula_Password_Protect' ) ) {
			$fields['password_protect']['helper-message'] = array(
				"name"     => ' ',
				"type"     => "content",
				"content"  => sprintf( esc_html__( 'In order to add password protection to your galleries you\'ll need to install the extension Modula Password Protect from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
				'priority' => 5,
			);
		}

			// Check if modula speed up it's installed & activated
			if ( ! class_exists( 'Modula_Slideshow' ) ) {
				$fields['slideshow']['helper-message'] = array(
					"name"     => '',
					"type"     => "content",
					"content"  => sprintf( esc_html__( 'In order to create slideshows within your galleries you\'ll need to install the extension Modula Slideshow from %shere%s.', 'modula-pro' ), '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula-addons' ) . '" target="blank">', '</a>' ),
					'priority' => 5,
				);
			}

		// Alpha colors
		$fields['captions']['titleColor']['alpha']    = true;
		$fields['captions']['captionColor']['alpha']  = true;
		$fields['social']['socialIconColor']['alpha'] = true;
		$fields['style']['borderColor']['alpha']      = true;
		$fields['style']['shadowColor']['alpha']      = true;

		return $fields;
	}

	//Add new cursors in pro version

	public function modula_pro_cursor( $cursor ) {
		return array(
			'pointer'     => esc_html__( 'Pointer', 'modula-pro' ),
			'zoom-in'     => esc_html__( 'Magnifying Glass', 'modula-pro' ),
			'wait'        => esc_html__( 'Loading', 'modula-pro' ),
			'cell'        => esc_html__( 'Cell', 'modula-pro' ),
			'crosshair'   => esc_html__( 'Crosshair', 'modula-pro' ),
			'nesw-resize' => esc_html__( 'Zoom In 1', 'modula-pro' ),
			'nwse-resize' => esc_html__( 'Zoom In 2', 'modula-pro' ),
		);
	}

	// Add new hover effects in pro version
	public function modula_pro_hover_effects( $effects ) {

		return array(
			'none'      => esc_html__( 'None', 'modula-pro' ),
			'pufrobo'   => esc_html__( '1. Pufrobo', 'modula-pro' ),
			'under'     => esc_html__( '2. Under Image', 'modula-pro' ),
			'fluid-up'  => esc_html__( '3. Fluid Up', 'modula-pro' ),
			'hide'      => esc_html__( '4. Hide', 'modula-pro' ),
			'quiet'     => esc_html__( '5. Quiet', 'modula-pro' ),
			'catinelle' => esc_html__( '6. Catinelle', 'modula-pro' ),
			'reflex'    => esc_html__( '7. Reflex', 'modula-pro' ),
			'curtain'   => esc_html__( '8. Curtain', 'modula-pro' ),
			'lens'      => esc_html__( '9. Lens', 'modula-pro' ),
			'appear'    => esc_html__( '10. Appear', 'modula-pro' ),
			'crafty'    => esc_html__( '11. Crafty', 'modula-pro' ),
			'seemo'     => esc_html__( '12. Seemo', 'modula-pro' ),
			'comodo'    => esc_html__( '13. Comodo', 'modula-pro' ),
			'lily'      => esc_html__( '14. Lily', 'modula-pro' ),
			'sadie'     => esc_html__( '15. Sadie', 'modula-pro' ),
			'honey'     => esc_html__( '16. Honey', 'modula-pro' ),
			'layla'     => esc_html__( '17. Layla', 'modula-pro' ),
			'zoe'       => esc_html__( '18. Zoe', 'modula-pro' ),
			'oscar'     => esc_html__( '19. Oscar', 'modula-pro' ),
			'marley'    => esc_html__( '20. Marley', 'modula-pro' ),
			'ruby'      => esc_html__( '21. Ruby', 'modula-pro' ),
			'roxy'      => esc_html__( '22. Roxy', 'modula-pro' ),
			'bubba'     => esc_html__( '23. Bubba', 'modula-pro' ),
			'dexter'    => esc_html__( '24. Dexter', 'modula-pro' ),
			'sarah'     => esc_html__( '25. Sarah', 'modula-pro' ),
			'chico'     => esc_html__( '26. Chico', 'modula-pro' ),
			'milo'      => esc_html__( '27. Milo', 'modula-pro' ),
			'julia'     => esc_html__( '28. Julia', 'modula-pro' ),
			'hera'      => esc_html__( '29. Hera', 'modula-pro' ),
			'winston'   => esc_html__( '30. Winston', 'modula-pro' ),
			'selena'    => esc_html__( '31. Selena', 'modula-pro' ),
			'terry'     => esc_html__( '32. Terry', 'modula-pro' ),
			'phoebe'    => esc_html__( '33. Phoebe', 'modula-pro' ),
			'apollo'    => esc_html__( '34. Apollo', 'modula-pro' ),
			'steve'     => esc_html__( '35. Steve', 'modula-pro' ),
			'jazz'      => esc_html__( '36. Jazz', 'modula-pro' ),
			'ming'      => esc_html__( '37. Ming', 'modula-pro' ),
			'lexi'      => esc_html__( '38. Lexi', 'modula-pro' ),
			'duke'      => esc_html__( '39. Duke', 'modula-pro' ),
			'tilt_1'    => esc_html__( '40. Tilt Effect 1', 'modula-pro' ),
			'tilt_3'    => esc_html__( '41. Tilt Effect 2', 'modula-pro' ),
			'tilt_7'    => esc_html__( '42. Tilt Effect 3', 'modula-pro' ),
		);

	}

	// Generate hover effect preview html
	public function modula_pro_hover_effect_preview( $preview_html, $effect ) {

		$effect_elements = Modula_Helper::hover_effects_elements( $effect );
		$effect_array    = array( 'tilt_1', 'tilt_3', 'tilt_7', );
		$overlay_array   = array( 'tilt_2', 'tilt_3', 'tilt_7' );
		$svg_array       = array( 'tilt_1', 'tilt_7' );
		$jtg_body        = array( 'lily', 'sadie', 'ruby', 'bubba', 'dexter', 'chico', 'ming' );

		$html = '';
		$html .= '<div class="panel panel-' . $effect . ' modula-items clearfix">';
		$html .= '<div class="modula-item effect-' . $effect . '">';

		if ( 'under' == $effect ) {
			$html .= '<div class="modula-item-image-continer"><img src="' . MODULA_URL . '/assets/images/effect.jpg" class="pic"></div>';
		} else {
			$html .= '<img src="' . MODULA_URL . '/assets/images/effect.jpg" class="pic">';
		}

		if ( in_array( $effect, $effect_array ) ) {
			$html .= '<div class="tilter__deco tilter__deco--shine"><div></div></div>';
			if ( in_array( $effect, $overlay_array ) ) {
				$html .= '<div class="tilter__deco tilter__deco--overlay"></div>';
			}
			if ( in_array( $effect, $svg_array ) ) {
				$html .= '<div class="tilter__deco tilter__deco--lines"></div>';
			}

		}
		$html .= '<div class="figc"><div class="figc-inner">';
		if ( $effect_elements['title'] ) {
			$html .= '<h2>Lorem ipsum</h2>';
		}

		if ( in_array( $effect, $jtg_body ) ) {
			$html .= '<div class="jtg-body">';
		}

		if ( $effect_elements['description'] ) {
			$html .= '<p class="description">Quisque diam erat, mollisvitae enim eget</p>';
		} else {
			$html .= '<p class="description"></p>';
		}
		if ( $effect_elements['social'] ) {
			$html .= '<div class="jtg-social">';
			$html .= '<a href="#">' . Modula_Helper::get_icon( 'twitter' ) . '</a>';
			$html .= '<a href="#">' . Modula_Helper::get_icon( 'facebook' ) . '</a>';
			$html .= '<a href="#">' . Modula_Helper::get_icon( 'pinterest' ) . '</a>';
			$html .= '<a href="#">' . Modula_Helper::get_icon( 'whatsapp' ) . '</a>';
			$html .= '<a href="#">' . Modula_Helper::get_icon( 'linkedin' ) . '</a>';
			$html .= '<a href="#">' . Modula_Helper::get_icon( 'email' ) . '</a>';
			$html .= '</div>';
		}

		if ( in_array( $effect, $jtg_body ) ) {
			$html .= '</div>';
		}

		$html .= '</div></div></div>';
		$html .= '<div class="effect-compatibility">';
		$html .= '<p class="description">' . esc_html__( 'This effect is compatible with:', 'modula-pro' );

		if ( $effect_elements['title'] ) {
			$html .= '<span><strong> ' . esc_html__( 'Title', 'modula-pro' ) . '</strong></span>,';
		}

		if ( $effect_elements['description'] ) {
			$html .= '<span><strong> ' . esc_html__( 'Description', 'modula-pro' ) . '</strong></span>,';
		}

		if ( $effect_elements['social'] ) {
			$html .= '<span><strong> ' . esc_html__( 'Social Icons', 'modula-pro' ) . '</strong></span>';
		}
		$html .= '</p>';

		if ( $effect_elements['scripts'] ) {
			$html .= '<p class="description">' . esc_html__( 'This effect will add an extra js script to your gallery', 'modula-pro' ) . '</p>';
		}

		$html .= '</div>';
		$html .= '</div>';

		return $html;

	}



	public function modula_pro_filters_field( $html, $field, $value ) {

		$html = '<div id="modula-filters" class="modula-filters-container">';
		$html .= '<div class="modula-filters">';
		if ( !is_array( $value ) ) {
			$value = explode( '|', $value );
		}
		if ( empty( $value ) ) {
			$html .= '<div class="modula-filter-input"><span class="dashicons dashicons-move"></span><input type="text" name="modula-settings[' . esc_attr( $field['id'] ) . '][]" value=""><a href="#" class="modula-delete-filter"><span class="dashicons dashicons-trash"></span></a></div>';
		} else {
			foreach ( $value as $filter ) {
				$html .= '<div class="modula-filter-input"><span class="dashicons dashicons-move"></span><input type="text" name="modula-settings[' . esc_attr( $field['id'] ) . '][]" value="' . esc_attr( $filter ) . '" class="regular-text"><a href="#" class="modula-delete-filter"><span class="dashicons dashicons-trash"></span></a></div>';
			}
		}
		$html .= '</div>';
		$html .= '<a href="#" id="modula-add-filter" class="button" data-field-name="' . esc_attr( $field['id'] ) . '"><span class="dashicons dashicons-plus"></span>' . esc_html__( 'Add new filter', 'modula-pro' ) . '</a>';
		$html .= '</div>';

		return $html;

	}


	public function modula_pro_fontselector_field( $html, $field, $value ) {

		$html = '<select name="modula-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" class="regular-text modula-font-selector" data-value="' . esc_attr( $value ) . '">';
		$html .= '</select>';

		return $html;

	}

	public function cursor_upload_field_type( $html, $field, $value ) {
		$style = array(
			'upload'  => '',
			'replace' => 'display:none;',
			'delete'  => 'display:none;',
		);

		if ( 0 != absint( $value ) ) {
			$style['upload']  = 'display:none;';
			$style['replace'] = '';
			$style['delete']  = '';
		}

		$html = '<input type="hidden" name="modula-settings[' . esc_attr( $field['id'] ) . ']" id="' . esc_attr( $field['id'] ) . '" value="' . absint( $value ) . '">';
		$html .= '<div class="modula_cursor_preview">';
		if ( $value ) {
			$image = wp_get_attachment_image_src( $value );
			if ( $image ) {
				$html .= '<img src="' . esc_url( $image[0] ) . '" id="modula_cursor_preview">';
			}
		}
		$html .= '</div>';
		$html .= '<input type="button" style="' . esc_attr( $style['upload'] ) . '" class="button button-primary" id="upload_cursor_file" value="' . esc_attr__( 'Upload', 'modula-pro' ) . '">';
		$html .= '<input type="button" style="' . esc_attr( $style['replace'] ) . '" class="button button-primary" id="replace_cursor_file" value="' . esc_attr__( 'Replace', 'modula-pro' ) . '">';
		$html .= '<input type="button" style="' . esc_attr( $style['delete'] ) . '" class="button" id="delete_cursor_file" value="' . esc_attr__( 'Delete', 'modula-pro' ) . '">';

		return $html;
	}

	public function default_settings( $defaults ) {

		$defaults['maxImagesCount']              = 0;
		$defaults['showAllOnLightbox']           = 0;
		$defaults['filters']                     = array( '' );
		$defaults['filterClick']                 = 0;
		$defaults['allFilterLabel']              = esc_html__( 'All', 'modula-pro' );
		$defaults['dropdownFilters']             = 0;
		$defaults['loadedRotate']                = 0;
		$defaults['loadedHSlide']                = 0;
		$defaults['loadedVSlide']                = 0;
		$defaults['hoverColor']                  = '#fff';
		$defaults['uploadCursor']                = 0;
		$defaults['hoverOpacity']                = 50;
		$defaults['hideAllFilter']               = 0;
		$defaults['enableCollapsibleFilters']    = 0;
		$defaults['filterPositioning']           = 'top';
		$defaults['filterTextAlignment']         = 'right';
		$defaults['defaultActiveFilter']         = 'All';
		$defaults['filterLinkColor']             = '';
		$defaults['filterLinkHoverColor']        = '';
		$defaults['filterStyle']                 = 'default';
		$defaults['showTitleLightbox']           = 0;
		$defaults['showCaptionLightbox']         = 1;
		$defaults['captionPosition']             = 'center';
		$defaults['show_gallery_title']          = 0;
		$defaults['gallery_title_type']          = 'p';
		$defaults['titleFontFamily']             = 'Default';
		$defaults['titleFontWeight']             = 'normal';
		$defaults['captionsFontFamily']          = 'Default';
		$defaults['captionFontWeight']           = 'normal';
		$defaults['collapsibleActionText']       = __( 'Filter by', 'modula-pro' );
		$defaults['loop_lightbox']               = 0;
		$defaults['lightbox_bottomThumbs']       = 0;
		$defaults['lightbox_background_color']   = '';
		$defaults['lightbox_keyboard']           = 0;
		$defaults['lightbox_wheel']              = 0;
		$defaults['lightbox_toolbar']            = 1;
		$defaults['lightbox_close']              = 1;
		$defaults['lightbox_download']           = 0;
		$defaults['lightbox_thumbs']             = 0;
		$defaults['lightbox_zoom']               = 0;
		$defaults['lightbox_share']              = 0;
		$defaults['lightbox_facebook']           = 0;
		$defaults['lightbox_twitter']            = 0;
		$defaults['lightbox_whatsapp']           = 0;
		$defaults['lightbox_pinterest']          = 0;
		$defaults['lightbox_linkedin']           = 0;
		$defaults['lightbox_email']              = 0;
		$defaults['lightboxEmailSubject']        = esc_html__( 'Check out this awesome image !!','modula-pro');
		$defaults['lightboxEmailMessage']        = esc_html__( 'Here is the link to the image : %%image_link%% and this is the link to the gallery : %%gallery_link%% ','modula-pro');
		$defaults['lightbox_infobar']            = 1;
		$defaults['lightbox_dblclickSlide']      = 0;
		$defaults['lightbox_clickSlide']         = 0;
		$defaults['lightbox_animationEffect']    = false;
		$defaults['lightbox_animationDuration']  = 366;
		$defaults['lightbox_transitionEffect']   = false;
		$defaults['lightbox_transitionDuration'] = 366;
		$defaults['lightbox_touch']              = 0;
		$defaults['lightbox_thumbsAutoStart']    = 0;
		$defaults['lightbox_thumbsAxis']         = 'y';
		$defaults['lightbox_bottomThumbs']       = 0;

		if ( !isset( $defaults['cursor'] ) ) {
			$defaults['cursor'] = 'magnifying-glass';
		}

		return $defaults;


	}

	public function print_modula_pro_templates() {
		include 'modula-pro-js-templates.php';
	}

	public function extra_item_fields() {
		echo '<input type="hidden" name="modula-images[filters][{{data.index}}]" class="modula-image-filters" value="{{ data.filters }}">';
	}


	public function add_pro_item_fields( $fields ) {

		$fields[] = 'filters';

		return $fields;

	}

	public function add_license_tab( $tabs ) {

		$tabs['licenses'] = array(
			'label'    => esc_html__( 'Licenses', 'modula-pro' ),
			'priority' => -1,
		);

		return $tabs;

	}

	public function show_licenses_tab() {
		include 'tabs/license.php';
	}


	public function add_effects_pro( $values ) {

		$values = array_merge( $values, array(
			'under',
			'fluid-up',
			'hide',
			'quiet',
			'catinelle',
			'reflex',
			'curtain',
			'lens',
			'appear',
			'crafty',
			'seemo',
			'comodo',
			'lily',
			'sadie',
			'honey',
			'layla',
			'zoe',
			'oscar',
			'marley',
			'ruby',
			'roxy',
			'bubba',
			'dexter',
			'sarah',
			'chico',
			'milo',
			'julia',
			'hera',
			'winston',
			'selena',
			'terry',
			'phoebe',
			'apollo',
			'steve',
			'jazz',
			'ming',
			'lexi',
			'duke',
			'tilt_1',
			'tilt_3',
			'tilt_7',
		) );

		return $values;
	}

	public function sanitize_settings( $sanitized_value, $value, $field_id, $field ) {

		if ( 'filters' == $field_id ) {
			$new_value = array();
			if ( is_array( $value ) && !empty( $value ) ) {
				foreach ( $value as $filter ) {
					$new_value[] = sanitize_text_field( $filter );
				}
			}

			return $new_value;
		}

		return $sanitized_value;
    
	}


	public function sanitize_image_fields( $sanitized_value, $value, $field_id ) {


		return $sanitized_value;
	}

	public function show_bulk_edit_button() {
		echo '<a href="#" id="modula-bulk-edit" class="button button-primary"><span class="dashicons dashicons-forms"></span>' . __( 'Bulk Edit', 'modula-pro' ) . '</a>';
	}

	public function render_field_type( $html, $field, $value ) {

		$html = '<p class="content addon-required">';
		$html .= wp_kses_post( $field['content'] );
		$html .= '</p>';

		return $html;
	}


	public function content_format( $format, $field ) {

		$format = '<tr class="no-paddings" data-container="' . esc_attr( $field['id'] ) . '"><td colspan="2"><label class="th-label">%s</label>%s<div>%s</div></td></tr>';

		return $format;

	}

	public function filter_link_style_extra( $format, $field ) {

		if ( 'filterStyle' == $field['id'] ) {
			$gallery_settings = get_post_meta( get_the_ID(), 'modula-settings', true );
			$current_style    = isset( $gallery_settings['filterStyle'] ) ? esc_attr( $gallery_settings['filterStyle'] ) : 'default';

			$preview = '<div class="modula-pro-live-preview modula">';
			$preview .= '<span class="modula-pro-badge">Live Preview</span>';
			$preview .= '<nav class="menu filter-style-preview filters menu--' . esc_attr( $current_style ) . '">' .
			            '<ul class="modula_menu__list">' .
			            '<li class="modula_menu__item modula_menu__item--current"><a href="#" class="modula_menu__link filter_style_preview">' . __( 'Filter 1', 'modula-pro' ) . '</a></li>' .
			            '<li class="modula_menu__item"><a href="#" class="modula_menu__link filter_style_preview">' . __( 'Filter 2', 'modula-pro' ) . '</a></li>' .
			            '<li class="modula_menu__item"><a href="#" class="modula_menu__link filter_style_preview">' . __( 'Filter 3', 'modula-pro' ) . '</a></li>' .
			            '<li class="modula_menu__line"></li>' .
			            '</ul>' .
			            '</nav></div>';
			$preview .= '<p class="description"><strong>' . esc_html__( 'Hover over the links above to see the effect.', 'modula-pro' ) . '</strong></p>';

			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><th scope="row"><label>%s</label>%s</th><td>%s<div>' . $preview . '</div></td></tr>';

		}

		return $format;
	}

	/**
	 * Setup sorting meta box
	 */
	public function meta_boxes_setup() {

		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'modula_save_sorting' ), 15, 2 );

	}

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * @return mixed
	 *
	 * Save modulaSorting meta
	 */
	public function modula_save_sorting( $post_id, $post ) {

		if ( !current_user_can( "edit_post", $post_id ) ) {
			return $post_id;
		}

		if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$slug = "modula-gallery";
		if ( $slug != $post->post_type ) {
			return $post_id;
		}

		$modula_sorting = "";

		if ( isset( $_POST["modula_sorting"] ) ) {
			$modula_sorting = $_POST["modula_sorting"];
		}
		update_post_meta( $post_id, "modulaSorting", $modula_sorting );

	}

	public function add_meta_boxes() {

		add_meta_box(
			'modula-gallery-sorting',
			esc_html__( 'Gallery Sorting', 'modula-pro' ),
			array( $this, 'output_gallery_sorting' ),
			'modula-gallery',         // Admin page (or post type)
			'side',         // Context
			'high'         // Priority
		);

	}

	public function output_gallery_sorting( $post ) {
		echo '<div class="modula-sorting-container">';
		$value = get_post_meta( $post->ID, 'modulaSorting', true );

		if ( empty( $value ) ) {
			$value = 'manual';
		}
		$available = array(
			'manual'            => esc_html__( 'Manual', 'modula-pro' ),
			'dateCreatedNew'    => esc_html__( 'Date created - newest first', 'modula-pro' ),
			'dateCreatedOld'    => esc_html__( 'Date created - oldest first', 'modula-pro' ),
			'dateModifiedFirst' => esc_html__( 'Date modified - most recent first', 'modula-pro' ),
			'dateModifiedLast'  => esc_html__( 'Date modified - most recent last', 'modula-pro' ),
			'titleAZ'           => esc_html__( 'Title alphabetically', 'modula-pro' ),
			'titleZA'           => esc_html__( 'Title reverse', 'modula-pro' ),
			'random'            => esc_html__( 'Random', 'modula-pro' ),
		);

		foreach ( $available as $k => $v ) {
			$checked = $value === $k ? 'checked="checked"' : '';
			echo '<input id="' . esc_attr( $k ) . '" type="radio" name="modula_sorting" ' . $checked . ' value="' . esc_attr( $k ) . '">';
			echo '<label for="' . esc_attr( $k ) . '">' . esc_html( $v ) . '</label>';
			echo '<br />';

		}

		echo '</div>';
	}

	public function add_replace_button() {
		echo '<a href="#" class="modula-replace-image" title="' . esc_attr__( 'Replace Image', 'modula-pro' ) . '"><span class="dashicons dashicons-randomize"></span></a>';
	}


	/**
	 * @param $post
	 * Display an id of the gallery in the custom CSS tab
	 */
	public function custom_css_gallery_id( $html, $field, $value ) {

		if ( 'style' != $field['id'] ) {
			return $html;
		}

		global $post;
		$post_id = $post->ID;
		$append  = '<div class="custom-css-gallery-id">';
		$append  .= '<p class="description">';
		$append  .= 'The ID of the gallery is: <code id="copyGalleryId"> jtg-' . $post_id . '</code>';
		$append  .= '</p>';
		$append  .= '</div>';
		return $append . $html;
	}

	/**
	 * Enqueue Modula Pro styles in Elementor preview
	 *
	 * @since 2.3.0
	 */
	public function elementor_enqueued_styles(){
		wp_enqueue_style( 'modula-pro-effects', MODULA_PRO_URL . 'assets/css/effects.min.css' );
	}

}

new Modula_PRO_Settings();
