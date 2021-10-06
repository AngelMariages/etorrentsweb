<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      2.0.0
 */
class Modula_PRO {

	function __construct() {

		$this->load_dependencies();

		// Check for LITE version of the plugin
		if ( !$this->check_for_lite() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'register_gallery_scripts' ) );

		add_filter( 'modula_necessary_scripts', array( $this, 'enqueue_necessary_scripts' ) );
		add_filter( 'modula_necessary_styles', array( $this, 'modula_necessary_styles' ) );

		// Add Filters for Modula Troubleshooting
		add_filter( 'modula_troubleshooting_fields', array( $this, 'add_troubleshooting_fields' ) );
		add_filter( 'modula_troubleshooting_defaults', array( $this, 'add_troubleshooting_defaults' ) );
		add_filter( 'modula_troubleshooting_frontend_handles', array( $this, 'add_main_pro_files' ), 60, 2 );
		add_filter( 'modula_troubleshooting_frontend_handles', array( $this, 'check_hovereffect' ), 20, 2 );
		add_filter( 'modula_troubleshooting_frontend_handles', array( $this, 'check_linkshortcode' ), 20, 2 );

		// modula-link shortcode
		add_action( 'init', array( $this, 'add_shortcode' ) );

		// Modify Modula Gallery config
		add_filter( 'modula_gallery_settings', array( $this, 'modula_pro_config' ), 10, 2 );
		add_action( 'modula_shortcode_before_items', 'modula_pro_output_filters', 15 );
		add_filter( 'modula_gallery_template_data', 'modula_pro_extra_modula_section_classes' );
		add_action( 'modula_shortcode_after_items', 'modula_pro_output_filters', 15 );
		add_filter( 'modula_shortcode_item_data', 'modula_pro_add_filters', 30, 3 );
		add_filter( 'modula_gallery_images', array( $this, 'modula_pro_max_count' ), 10, 2 );
		add_filter( 'modula_pagination_links', array( $this, 'modula_pro_max_count' ), 10, 2 );
		add_action( 'modula_shortcode_after_items', array( $this, 'output_removed_items' ), 10, 3 );
		// Remove Albums upsell metabox
		add_action( 'do_meta_boxes', array( $this, 'remove_albums_upsell_metabox' ), 16, 1 );

		// Shortpixel fix
		add_filter( 'modula_shortcode_item_data', array( $this, 'shortpixel_fix' ), 99, 3 );

		// Modify CSS
		add_filter( 'modula_shortcode_css', array( $this, 'generate_new_css' ), 10, 3 );

		// Remove upsells
		add_filter( 'modula_show_upsells', '__return_false' );

		// Output lightboxes options
		add_filter( 'modula_fancybox_options', array( $this, 'output_lightbox_options' ), 10, 2 );
		add_action( 'modula_extra_scripts', array( $this, 'check_for_fonts' ) );

		add_filter( 'modula_shortcode_item_data', array( $this, 'modula_pro_extra_item_data' ), 16, 3 );

		add_action( 'modula_extra_scripts', array( $this, 'output_extra_effects_scripts' ) );

		add_action( 'modula_item_after_image', array( $this, 'extra_effects_extra_elements' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'preview_extra_effects_scripts' ) );

		// Add new path for templates
		add_filter( 'modula_template_paths', array( $this, 'add_modula_pro_templates_path' ), 20 );

		// Alter Shortcode column
		$cpt_name = apply_filters( 'modula_cpt_name', 'modula-gallery' );
		add_action( "manage_{$cpt_name}_posts_custom_column", array( $this, 'output_column' ), 20, 2 );
		add_action( 'modula_admin_after_shortcode_metabox', array( $this, 'output_link_shortcode' ) );

		add_action( 'admin_notices', array( $this, 'modula_license_notices' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'modula_pro_license_check' ) );

		add_filter( 'modula_gallery_template_data', array( $this, 'filter_class_helper' ), 20, 1 );

		add_filter( 'modula_uninstall_db_options', array( $this, 'uninstall_process' ), 25, 1 );

		add_action( 'modula_shortcode_before_items', array( $this, 'modula_gallery_title' ), 5 );


	}

	/**
	 * Check if Modula Lite is Active
	 *
	 * @return bool
	 * @since 2.4.1
	 */
	private function check_for_lite() {

		$check = array(
			'installed' => Modula_Pro_Helper::check_plugin_is_installed( 'modula-best-grid-gallery' ),
			'active'    => Modula_Pro_Helper::check_plugin_is_active( 'modula-best-grid-gallery' ),
		);

		if ( $check['active'] ) {
			return true;
		}

		if ( is_admin() ) {
			add_action( 'admin_notices', array( $this, 'display_lite_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_lite_scripts' ) );
		}

		return false;
	}

	/**
	 * Display Install & Activate LITE Notice
	 *
	 * @since 2.4.1
	 */
	public function display_lite_notice() {

		$check = array(
			'installed' => Modula_Pro_Helper::check_plugin_is_installed( 'modula-best-grid-gallery' ),
			'active'    => Modula_Pro_Helper::check_plugin_is_active( 'modula-best-grid-gallery' ),
		);

		if ( !$check['installed'] ) {
			$label  = esc_html__( 'Install & Activate: Modula Lite', 'modula-pro' );
			$action = 'install';
			$url    = '#';
		} else {
			$label  = esc_html__( 'Activate: Modula Lite', 'modula-pro' );
			$action = 'activate';
			$url    = add_query_arg(
				array(
					'action'        => 'activate',
					'plugin'        => rawurlencode( Modula_Pro_Helper::_get_plugin_basename_from_slug( 'modula-best-grid-gallery' ) ),
					'plugin_status' => 'all',
					'paged'         => '1',
					'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . Modula_Pro_Helper::_get_plugin_basename_from_slug( 'modula-best-grid-gallery' ) ),
				),
				admin_url( 'plugins.php' )
			);
		}

		wp_enqueue_style( 'modula-pro-install-lite', MODULA_PRO_URL . 'assets/css/install-lite.css' );

		echo '<div id="modula-install-lite" class="notice">';
		echo '<h1>' . esc_html__( 'Install & Activate Modula Lite', 'modula-pro' ) . '</h1>';
		echo '<h4>' . esc_html__( 'In order for Modula PRO to work properly, you\'ll also need to have Modula Lite installed & activated', 'modula-pro' ) . '</h4>';
		echo '<a href="' . esc_url( $url ) . '" data-action="' . esc_attr( $action ) . '" class="button button-primary button-hero" id="install-modula-lite">' . $label . '</a>';
		echo '</div>';
	}


	/**
	 * Displays an inline notice with some Modula styling.
	 *
	 * @param string  $notice  Programmatic Notice Name.
	 * @param string  $title   Title.
	 * @param string  $message Message.
	 * @param string  $type    Message Type (updated|warning|error) - green, yellow/orange and red respectively.
	 * @param integer $seconds Number of seconds transient is good for, after expires then notice re-appears - 0 means no transient, it's an option.
	 *
	 * @since 1.3.5
	 *
	 */
	public function display_inline_notice( $notice, $title, $message, $type = 'success', $button_text = '', $button_url = '', $is_dismissible = true, $seconds = false ) {

		// Display inline notice.
		?>
		<div class="updated modula-notice <?php echo sanitize_html_class( $type . ($is_dismissible ? ' -is-dismissible' : '') ); ?>"
		     data-seconds="<?php echo esc_attr( $seconds ); ?>" data-notice="<?php echo esc_attr( $notice ); ?>">
			<?php
			// Message.
			if ( !empty( $message ) ) {
				?>
				<p><?php echo $message; // @codingStandardsIgnoreLine ?></p>
				<?php
			}
			?>
		</div>
		<?php

	}

	public function modula_license_notices() {

		$licenses_status = get_option( 'modula_pro_license_status', false );

		if ( !get_option( 'modula_pro_license_status', false ) || 'valid' != get_option( 'modula_pro_license_status', false )->license ) {

			$message = sprintf( esc_html__( '%sModula Pro%s: No valid license key has been entered, so any installed Modula Addons have been turned off. %sPlease click here to enter your license key and begin receiving automatic updates.%s', 'modula-pro' ), '<strong>', '</strong>', '<a href="' . admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' ) . '">', '</a>' );

			$this->display_inline_notice( 'warning-license-key', false, $message, 'error ', DAY_IN_SECONDS );

		}

	}

	public function modula_pro_license_check() {
		wp_enqueue_style( 'modula-pro-style', MODULA_PRO_URL . 'assets/css/modula-pro-admin-style.css' );
	}


	public function add_shortcode() {
		add_shortcode( 'modula-link', array( $this, 'modula_link_shortcode' ) );
	}


	public function modula_pro_extra_item_data( $item_data, $image, $settings ) {

		/**
		 * @since 2.2.2 after fancyBox update to v3.5.7
		 */
		$thumb = wp_get_attachment_image_src( $image['id'], 'thumbnail' );
		if ( $thumb ) {
			$item_data['link_attributes']['data-thumb'] = $thumb[0];
		} else {
			$item_data['link_attributes']['data-thumb'] = $item_data['img_attributes']['data-src'];
		}


		/**
		 * @since 2.3.0
		 */
		if ( isset( $item_data['link_attributes']['data-caption'] ) ) {

			if ( (isset( $settings['showTitleLightbox'] ) && '1' == $settings['showTitleLightbox']) && (isset( $settings['showCaptionLightbox'] ) && '1' == $settings['showCaptionLightbox']) ) {
				$item_data['link_attributes']['data-caption'] = esc_attr( '<p>' . $image['title'] . '</p><p>' . $image['description'] . '</p>' );
			} else if ( (isset( $settings['showTitleLightbox'] ) && '1' == $settings['showTitleLightbox']) && (!isset( $settings['showCaptionLightbox'] ) || '1' != $settings['showCaptionLightbox']) ) {
				$item_data['link_attributes']['data-caption'] = esc_attr( '<p>' . $image['title'] . '</p>' );
			} else if ( (!isset( $settings['showTitleLightbox'] ) || '1' != $settings['showTitleLightbox']) && (isset( $settings['showCaptionLightbox'] ) && '1' == $settings['showCaptionLightbox']) ) {
				$item_data['link_attributes']['data-caption'] = esc_attr( '<p>' . $image['description'] . '</p>' );
			} else {
				$item_data['link_attributes']['data-caption'] = '';
			}

		}

		if ( isset( $image['link'] ) && '' != $image['link'] ) {

			unset( $item_data['link_attributes']['data-caption'] );
			unset( $item_data['link_attributes']['rel'] );
			unset( $item_data['link_attributes']['data-fancybox'] );

			$item_data['link_attributes']['href'] = $image['link'];

			if ( isset( $image['target'] ) && 1 == $image['target'] ) {
				$item_data['link_attributes']['target'] = '_blank';
			}

			$item_data['link_attributes']['class'][] = 'modula-simple-link';
			$item_data['item_classes'][]             = 'modula-simple-link';

		}


		return $item_data;
	}


	private function load_dependencies() {

		require_once MODULA_PRO_PATH . 'includes/modula-pro-helper-functions.php';
		require_once MODULA_PRO_PATH . 'includes/class-modula-pro-helper.php';
		require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-settings.php';
		require_once MODULA_PRO_PATH . 'includes/class-modula-pro-gutenberg.php';

		if ( is_admin() ) {
			require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-addon.php';
			require_once MODULA_PRO_PATH . 'includes/admin/modula-pro-addon-ajax.php';
			require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-license-activator.php';
			require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-migrate.php';
		}

	}

	// Register all pro scripts & style in order to be enqueue
	public function register_gallery_scripts() {

		// Modula PRO
		wp_register_style( 'modula-pro-effects', MODULA_PRO_URL . 'assets/css/effects.min.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'modula-pro', MODULA_PRO_URL . 'assets/js/modula-pro.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Modula Pro scripts used for Tilt Hover Effects
		wp_register_script( 'modula-pro-tilt', MODULA_PRO_URL . 'assets/js/modula-pro-tilt.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Modula Link script
		wp_register_script( 'modula-link-shortcode', MODULA_PRO_URL . 'assets/js/modula-link.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
	}


	public function check_for_fonts( $settings ) {

		if ( 'Default' == $settings['captionsFontFamily'] && 'Default' == $settings['titleFontFamily'] ) {
			return;
		}

		$fonts = array();

		if ( 'Default' != $settings['titleFontFamily'] ) {
			if ( 'normal' == $settings['titleFontWeight'] ) {
				$fonts[$settings['titleFontFamily']] = array( 300, 400, 700 );
			} else {
				$fonts[$settings['titleFontFamily']] = array( intval( $settings['titleFontWeight'] ) );
			}
		}

		if ( 'Default' != $settings['captionsFontFamily'] ) {
			if ( 'normal' == $settings['captionFontWeight'] ) {
				$fonts_weights = array( 300, 400, 700 );
			} else {
				$fonts_weights = array( intval( $settings['captionFontWeight'] ) );
			}

			if ( isset( $fonts[$settings['captionsFontFamily']] ) ) {
				$fonts[$settings['captionsFontFamily']] = array_merge( $fonts[$settings['captionsFontFamily']], $fonts_weights );
			} else {
				$fonts[$settings['captionsFontFamily']] = $fonts_weights;
			}

		}

		if ( empty( $fonts ) ) {
			return;
		}

		$new_fonts = array();
		foreach ( $fonts as $font => $weights ) {
			$font_name   = str_replace( ' ', '+', $font );
			$new_fonts[] = $font_name . ':' . implode( ',', array_unique( $weights ) );
		}

		$fonts_string = implode( '|', $new_fonts );
		$font_url     = 'https://fonts.googleapis.com/css?family=' . $fonts_string . '&display=swap';
		wp_enqueue_style( 'modula-pro-font', $font_url, MODULA_PRO_VERSION, null );

	}

	public function output_lightbox_options( $fancybox_options, $settings ) {

		$fancybox_options['buttons'] = array();

		if ( isset( $settings['loop_lightbox'] ) && '1' == $settings['loop_lightbox'] ) {
			$fancybox_options['loop'] = true;
		}

		if ( isset( $fancybox_options['options']['clickContent'] ) ) {
			unset( $fancybox_options['options']['clickContent'] );
		}

		if ( isset( $settings['lightbox_toolbar'] ) && '1' == $settings['lightbox_toolbar'] ) {
			$fancybox_options['toolbar'] = true;
		} else {
			$fancybox_options['toolbar'] = false;
		}


		if ( isset( $settings['lightbox_infobar'] ) && '1' == $settings['lightbox_infobar'] ) {
			$fancybox_options['infobar'] = true;
		} else {
			$fancybox_options['infobar'] = false;
		}

		// Add all buttons

		if ( isset( $settings['lightbox_zoom'] ) && '1' == $settings['lightbox_zoom'] ) {
			$fancybox_options['buttons'][] = 'zoom';
		}

		if ( isset( $settings['lightbox_share'] ) && '1' == $settings['lightbox_share'] ) {
			$fancybox_options['buttons'][] = 'share';

			if ( isset( $settings['lightbox_facebook'] ) && '1' == $settings['lightbox_facebook'] ) {
				$fancybox_options['modulaShare'][] = 'facebook';
			}

			if ( isset( $settings['lightbox_twitter'] ) && '1' == $settings['lightbox_twitter'] ) {
				$fancybox_options['modulaShare'][] = 'twitter';
			}

			if ( isset( $settings['lightbox_whatsapp'] ) && '1' == $settings['lightbox_whatsapp'] ) {
				$fancybox_options['modulaShare'][] = 'whatsapp';
			}


			if ( isset( $settings['lightbox_linkedin'] ) && '1' == $settings['lightbox_linkedin'] ) {
				$fancybox_options['modulaShare'][] = 'linkedin';
			}

			if ( isset( $settings['lightbox_pinterest'] ) && '1' == $settings['lightbox_pinterest'] ) {
				$fancybox_options['modulaShare'][] = 'pinterest';
			}

			if ( isset( $settings['lightbox_email'] ) && '1' == $settings['lightbox_email'] ) {
				$fancybox_options['modulaShare'][] = 'email';
			}

			if ( isset( $settings['lightboxEmailSubject'] ) ) {
				$fancybox_options['lightboxEmailSubject'] = esc_html( $settings['lightboxEmailSubject'] );
			} else {
				$fancybox_options['lightboxEmailSubject'] = esc_html__( 'Check out this awesome image !!', 'modula-pro' );
			}

			if ( isset( $settings['lightboxEmailMessage'] ) ) {
				$fancybox_options['lightboxEmailMessage'] = esc_html( $settings['lightboxEmailMessage'] );
			} else {
				$fancybox_options['lightboxEmailMessage'] = esc_html__( 'Here is the link to the image : %%image_link%% and this is the link to the gallery : %%gallery_link%% ', 'modula-pro' );
			}

			if ( isset( $settings['galleryMessage'] ) ) {
				$fancybox_options['messageGallery'] = $settings['galleryMessage'];
			} else {
				$fancybox_options['messageGallery'] = esc_html__( 'Here is the link to the gallery :', 'modula-pro' );
			}

		}

		if ( isset( $settings['lightbox_download'] ) && '1' == $settings['lightbox_download'] ) {
			$fancybox_options['buttons'][] = 'download';
		}

		if ( isset( $settings['lightbox_thumbs'] ) && '1' == $settings['lightbox_thumbs'] ) {
			$fancybox_options['buttons'][] = 'thumbs';
		}

		if ( isset( $settings['lightbox_close'] ) && '1' == $settings['lightbox_close'] ) {
			$fancybox_options['buttons'][] = 'close';
		}

		if ( isset( $settings['lightbox_keyboard'] ) && '1' == $settings['lightbox_keyboard'] ) {
			$fancybox_options['keyboard'] = true;
		} else {
			$fancybox_options['keyboard'] = false;
		}

		if ( isset( $settings['lightbox_wheel'] ) && '1' == $settings['lightbox_wheel'] ) {
			$fancybox_options['wheel'] = true;
		} else {
			$fancybox_options['wheel'] = false;
		}

		if ( isset( $settings['lightbox_clickSlide'] ) && '1' == $settings['lightbox_clickSlide'] ) {
			$fancybox_options['clickSlide'] = 'close';
		}

		if ( isset( $settings['lightbox_dblclickSlide'] ) && '1' == $settings['lightbox_dblclickSlide'] ) {
			$fancybox_options['dblclickSlide'] = 'close';
		}

		if ( isset( $settings['lightbox_animationEffect'] ) && 'false' != $settings['lightbox_animationEffect'] ) {
			$fancybox_options['animationEffect'] = esc_html( $settings['lightbox_animationEffect'] );
		} else {
			$fancybox_options['animationEffect'] = false;
		}

		if ( isset( $settings['lightbox_animationDuration'] ) ) {
			$fancybox_options['animationDuration'] = (int)$settings['lightbox_animationDuration'];
		}

		if ( isset( $settings['lightbox_transitionEffect'] ) && 'false' != $settings['lightbox_transitionEffect'] ) {
			$fancybox_options['transitionEffect'] = esc_html( $settings['lightbox_transitionEffect'] );
		} else {
			$fancybox_options['transitionEffect'] = false;
		}

		if ( isset( $settings['lightbox_transitionDuration'] ) ) {
			$fancybox_options['transitionDuration'] = (int)$settings['lightbox_transitionDuration'];
		}

		if ( isset( $settings['lightbox_touch'] ) && '1' == $settings['lightbox_touch'] ) {
			$fancybox_options['touch'] = array(
				'vertical' => true,
				'momentum' => true
			);
		}

		if ( isset( $settings['lightbox_thumbsAutoStart'] ) && '1' == $settings['lightbox_thumbsAutoStart'] ) {
			$fancybox_options['thumbs']['autoStart'] = true;
		} else {
			$fancybox_options['thumbs']['autoStart'] = false;
		}

		if ( isset( $settings['lightbox_thumbsAxis'] ) && (!isset( $settings['lightbox_bottomThumbs'] ) || '1' != $settings['lightbox_bottomThumbs']) ) {
			$fancybox_options['thumbs']['axis'] = $settings['lightbox_thumbsAxis'];
		}

		if ( isset( $settings['lightbox_bottomThumbs'] ) && '1' == $settings['lightbox_bottomThumbs'] ) {
			$fancybox_options['thumbs']['axis'] = 'y';
		}

		return $fancybox_options;

	}

	// Add extra scripts for shortcode to enqueue
	public function enqueue_necessary_scripts( $scripts ) {

		$scripts[] = 'modula-pro';
		return $scripts;

	}

	// Add extra css for shortcode to enqueue
	public function modula_necessary_styles( $styles ) {

		// Search for css for effect in lite and remove it.
		$lite_effects = array_search( 'modula-effects', $styles );
		if ( false !== $lite_effects ) {
			unset( $styles[$lite_effects] );
		}

		$styles[] = 'modula-pro';
		$styles[] = 'modula-pro-effects';
		return $styles;

	}

	// Add extra parameter for javascript config
	public function modula_pro_config( $js_config, $settings ) {
		$js_config['lightbox'] = $settings['lightbox'];

		if ( apply_filters( 'modula_disable_lightboxes', true ) && !in_array( $settings['lightbox'], array( 'no-link', 'direct', 'attachment-page' ) ) ) {
			$js_config['lightbox'] = 'fancybox';
		}

		if ( isset( $settings['filterClick'] ) ) {
			$js_config['filterClick'] = esc_attr( $settings['filterClick'] );
		}

		if ( isset( $settings['dropdownFilters'] ) ) {
			$js_config['dropdownFilters'] = esc_attr( $settings['dropdownFilters'] );
		}

		$js_config['defaultActiveFilter'] = esc_attr( sanitize_title( $settings['defaultActiveFilter'] ) );
		$js_config['initLightbox']        = 'modula_pro_init_lightbox';

		$js_config['haveFilters'] = 0;
		if ( isset( $settings['filters'] ) ) {
			$filters = Modula_Pro_Helper::remove_empty_items( $settings['filters'] );

			if ( is_array( $filters ) && !empty( $filters ) ) {
				$js_config['haveFilters'] = 1;
			}

		}

		return $js_config;
	}

	public function generate_new_css( $css, $gallery_id, $settings ) {

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item .modula-item-overlay, html body #{$gallery_id}.modula .modula-items .modula-item.effect-layla, html body #{$gallery_id}.modula .modula-items .modula-item.effect-ruby,html body #{$gallery_id}.modula .modula-items .modula-item.effect-bubba,html body #{$gallery_id}.modula .modula-items .modula-item.effect-sarah,html body #{$gallery_id}.modula .modula-items .modula-item.effect-milo,html body #{$gallery_id}.modula .modula-items .modula-item.effect-julia,html body #{$gallery_id}.modula .modula-items .modula-item.effect-hera,html body #{$gallery_id}.modula .modula-items .modula-item.effect-winston,html body #{$gallery_id}.modula .modula-items .modula-item.effect-selena,html body #{$gallery_id}.modula .modula-items .modula-item.effect-terry,html body #{$gallery_id}.modula .modula-items .modula-item.effect-phoebe,html body #{$gallery_id}.modula .modula-items} .modula-item.effect-apollo,html body #{$gallery_id}.modula .modula-items .modula-item.effect-steve,html body #{$gallery_id}.modula .modula-items .modula-item.effect-ming{ background-color:" . modula_pro_sanitize_color( $settings['hoverColor'] ) . "; }";

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item.effect-oscar { background: -webkit-linear-gradient(45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#9b4a1b 40%," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 100%);background: linear-gradient(45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#9b4a1b 40%," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 100%);}";

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item.effect-roxy {background: -webkit-linear-gradient(45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#05abe0 100%);background: linear-gradient(45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#05abe0 100%);}";

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item.effect-dexter {background: -webkit-linear-gradient(top," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,rgba(104,60,19,1) 100%); background: linear-gradient(to bottom," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,rgba(104,60,19,1) 100%);}";

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item.effect-jazz {background: -webkit-linear-gradient(-45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#f33f58 100%);background: linear-gradient(-45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#f33f58 100%);}";

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item.effect-lexi {background: -webkit-linear-gradient(-45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#fff 100%);background: linear-gradient(-45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#fff 100%);}";

		$css .= "html body #{$gallery_id}.modula .modula-items .modula-item.effect-duke {background: -webkit-linear-gradient(-45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#cc6055 100%);background: linear-gradient(-45deg," . modula_pro_sanitize_color( $settings['hoverColor'] ) . " 0,#cc6055 100%);}";

		if ( absint( $settings['hoverOpacity'] ) <= 100 && 'none' != $settings['effect'] ) {
			$css .= "html body #{$gallery_id}.modula .modula-items .modula-item:hover img { opacity: " . (1 - absint( $settings['hoverOpacity'] ) / 100) . "; }";
		}

        // Settings for cursor preview
        if ( 'custom' == $settings['cursor'] && $settings['uploadCursor'] != 0 ) {
            $image_src = wp_get_attachment_image_src( $settings['uploadCursor'] );
            $css       .= "html body #{$gallery_id}.modula .modula-items .modula-item > a, html body #{$gallery_id}.modula .modula-items .modula-item, html body #{$gallery_id}.modula .modula-items .modula-item-content > a { cursor:url(" . esc_url( $image_src[0] ) . "),auto ; } ";
        }

        //Settings for font family caption and title
        if ( 'Default' != $settings['captionsFontFamily'] && '' != $settings['captionsFontFamily'] ) {
            $css .= "html body #{$gallery_id}.modula .modula-items .modula-item .description{ font-family:" . esc_attr( $settings['captionsFontFamily'] ) . "; }";
        }

        if ( 'Default' != $settings['titleFontFamily'] && '' != $settings['titleFontFamily'] ) {
            $css .= "html body #{$gallery_id}.modula .modula-items .modula-item .jtg-title{ font-family:" . esc_attr( $settings['titleFontFamily'] ) . "; }";
        }
		// End of font family caption and title

		//Settings for Title Font Weight
		if ( 'default' != $settings['titleFontWeight'] ) {
			$css .= "html body #{$gallery_id}.modula .modula-items .modula-item .jtg-title {font-weight:" . esc_attr( $settings['titleFontWeight'] ) . "; }";
		}


		//Settings for Captions Font Weight
		if ( 'default' != $settings['captionFontWeight'] ) {
			$css .= "html body #{$gallery_id}.modula .modula-items .modula-item p.description {font-weight:" . esc_attr( $settings['captionFontWeight'] ) . "; }";
		}

		$css .= "#{$gallery_id}:not(.modula-loaded-scale)  .modula-item .modula-item-content { transform: scale(" . sanitize_text_field( $settings['loadedScale'] ) / 100 . ") translate(" . sanitize_text_field( $settings['loadedHSlide'] ) . 'px,' . sanitize_text_field( $settings['loadedVSlide'] ) . "px) rotate(" . sanitize_text_field( $settings['loadedRotate'] ) . "deg); }";

		$css .= "@keyframes modulaScaling { 0% {transform: scale(1) translate(0px,p0x) rotate(0deg);} 50%{transform: scale(" . sanitize_text_field( $settings['loadedScale'] ) / 100 . ") translate(" . sanitize_text_field( $settings['loadedHSlide'] ) . 'px,' . sanitize_text_field( $settings['loadedVSlide'] ) . "px) rotate(" . sanitize_text_field( $settings['loadedRotate'] ) . "deg);}100%{transform: scale(1) translate(0px,p0x) rotate(0deg);}}";

		// Filter Text Alignment
		if ( 'none' != $settings['filterTextAlignment'] ) {
			$css .= 'html body #' . $gallery_id . '.modula-gallery .filters { text-align: ' . esc_attr( $settings['filterTextAlignment'] ) . ';}';
		}

		if ( isset( $settings['filterLinkColor'] ) && '' != $settings['filterLinkColor'] ) {
			$css .= 'html body #' . $gallery_id . '.modula-gallery .filters a {color: ' . modula_pro_sanitize_color( $settings['filterLinkColor'] ) . ' !important;}';
		}

		if ( isset( $settings['filterLinkHoverColor'] ) && '' != $settings['filterLinkHoverColor'] ) {

			$css .= 'html body #' . $gallery_id . ' .menu--ceres .modula_menu__item::before, html body #' . $gallery_id . ' .menu--ceres .modula_menu__item::after, html body #' . $gallery_id . ' .menu--ceres .modula_menu__link::after,html body #' . $gallery_id . ' .menu--ariel .modula_menu__item::before,html body #' . $gallery_id . ' .menu--ariel .modula_menu__item::after,html body #' . $gallery_id . ' .menu--ariel .modula_menu__link::after,html body #' . $gallery_id . ' .menu--ariel .modula_menu__link::before{background-color: ' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ' !important;}html body #' . $gallery_id . '.modula-gallery .filters a:hover,html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current a {color: ' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ' !important;border-color: ' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ' !important}html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current a:hover:before,html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current a:hover:after,html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current:hover:before,html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current:hover:after { border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . '}html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current a:before,#' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current a:after,html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current:before,html body #' . $gallery_id . '.modula-gallery .filters li.modula_menu__item--current:after{border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . '; background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Antonio
			$css .= 'html body #' . $gallery_id . '.modula .menu--antonio .modula_menu__item::after,html body #' . $gallery_id . '.modula .menu--antonio .modula_menu__item::before,html body #' . $gallery_id . '.modula .menu--antonio .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--antonio .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Caliban
			$css .= 'html body #' . $gallery_id . '.modula .menu--caliban .modula_menu__link::before,html body #' . $gallery_id . '.modula .menu--caliban .modula_menu__link::after{border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Ferdinand
			$css .= 'html body #' . $gallery_id . '.modula .menu--ferdinand .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Francisco & Trinculo
			$css .= 'html body #' . $gallery_id . '.modula .menu--francisco .modula_menu__link::before,html body #' . $gallery_id . '.modula .menu--trinculo .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Horatio
			$css .= 'html body #' . $gallery_id . '.modula .menu--horatio .modula_menu__item a::after,html body #' . $gallery_id . '.modula .menu--horatio .modula_menu__item a::before,html body #' . $gallery_id . '.modula .menu--horatio .modula_menu__item::after,html body #' . $gallery_id . '.modula .menu--horatio .modula_menu__item::before{border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . '}';

			// Invulner
			$css .= 'html body ' . $gallery_id . '.modula .menu--invulner .modula_menu__link::before{border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . '}';

			// Iris
			$css .= 'html body #' . $gallery_id . '.modula .menu--iris .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--iris .modula_menu__link::before{border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . '}';

			// Juno
			$css .= 'html body #' . $gallery_id . '.modula .menu--juno .modula_menu__item::after,html body #' . $gallery_id . '.modula .menu--juno .modula_menu__item::before,html body #' . $gallery_id . '.modula .menu--juno .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--juno .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Maria
			$css .= 'html body #' . $gallery_id . '.modula .menu--maria .modula_menu__item::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Miranda
			$css .= 'html body #' . $gallery_id . '.modula .menu--miranda .modula_menu__item::after,#' . $gallery_id . '.modula .menu--miranda .modula_menu__item::before,#' . $gallery_id . '.modula .menu--miranda .modula_menu__link::after,#' . $gallery_id . '.modula .menu--miranda .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Prospero
			$css .= 'html body #' . $gallery_id . '.modula .menu--prospero .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Sebastian
			$css .= 'html body #' . $gallery_id . '.modula .menu--sebastian .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--sebastian .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Shylock
			$css .= 'html body #' . $gallery_id . '.modula .menu--shylock .modula_menu__link::after{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Stephano
			$css .= 'html body #' . $gallery_id . '.modula .menu--stephano .modula_menu__item::before,html body #' . $gallery_id . '.modula .menu--stephano .modula_menu__link::after,#' . $gallery_id . '.modula .menu--stephano .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			// Tantalid
			$css .= 'html body #' . $gallery_id . '.modula .menu--tantalid .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--tantalid .modula_menu__link::before{border-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . '}';

			// Valentin
			$css .= 'html body #' . $gallery_id . '.modula .menu--valentine .modula_menu__item::after,html body #' . $gallery_id . '.modula .menu--valentine .modula_menu__item::before,html body #' . $gallery_id . '.modula .menu--valentine .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--valentine .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

			//
			$css .= 'html body #' . $gallery_id . '.modula .menu--viola .modula_menu__item::after,html body #' . $gallery_id . '.modula .menu--viola .modula_menu__item::before,html body #' . $gallery_id . '.modula .menu--viola .modula_menu__link::after,html body #' . $gallery_id . '.modula .menu--viola .modula_menu__link::before{background-color:' . modula_pro_sanitize_color( $settings['filterLinkHoverColor'] ) . ';}';

		}

		// Terry
		$css .= 'html body #' . $gallery_id . '.modula-gallery .modula-item.effect-terry .jtg-social a:not(:last-child){margin-bottom:' . absint( $settings['socialIconPadding'] ) . 'px;}';

		// caption positioning
		if ( isset( $settings['captionPosition'] ) ) {

			$css .= '.modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-caption .modula-fancybox-caption__body, .modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-caption .modula-fancybox-caption__body p {text-align:' . esc_attr( $settings['captionPosition'] ) . '}';
		}

		if ( isset( $settings['lightbox_bottomThumbs'] ) && '1' == $settings['lightbox_bottomThumbs'] ) {
			$css .= '@media all and (min-width: 768px) { .modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-thumbs { top: auto !important; width: auto !important; bottom: 0; left: 0; right : 0; height: 95px; padding: 10px 10px 5px 10px; box-sizing: border-box; background: rgba(0, 0, 0, 0.6) !important;} .modula-fancybox-container.modula-lightbox-' . $gallery_id . '.modula-fancybox-show-thumbs .modula-fancybox-inner {  right: 0 !important; bottom: 95px !important; } }';
		}

		if ( isset( $settings['lightbox_background_color'] ) && '' != $settings['lightbox_background_color'] ) {
			$css .= '.modula-fancybox-container.modula-lightbox-'.$gallery_id.' .modula-fancybox-bg{background:' . $settings['lightbox_background_color'] . ';opacity:1 !important;}';
		}

		return $css;
	}

	public function admin_lite_scripts() {
		wp_enqueue_script( 'modula-install-lite', MODULA_PRO_URL . 'assets/js/install-lite.js', array( 'jquery', 'updates' ), null, true );
	}

	public function modula_pro_max_count( $images, $settings ) {
		$supported_types = apply_filters( 'modula_supported_types', array( 'creative-gallery', 'custom-grid', 'grid' ) );
		if ( in_array( $settings['type'], $supported_types ) ) {
			if ( isset( $settings['maxImagesCount'] ) && absint( $settings['maxImagesCount'] ) > 0 ) {
				$images = array_slice( $images, 0, absint( $settings['maxImagesCount'] ) );
			}
		}

		return $images;
	}

	public function output_removed_items( $settings, $item_data, $images ) {

		if ( absint( $settings['maxImagesCount'] ) == 0 ) {
			return;
		}

		if ( absint( $settings['showAllOnLightbox'] ) != 1 ) {
			return;
		}

		$images_ids = wp_list_pluck( $images, 'id' );

		$chuks      = explode( '-', $settings['gallery_id'] );
		$gallery_id = $chuks[1];

		$all_images = get_post_meta( $gallery_id, 'modula-images', true );

		echo '<div class="hidden-items">';
		foreach ( $all_images as $image ) {

			if ( in_array( $image['id'], $images_ids ) ) {
				continue;
			}

			$attr = array(
				'class' => array( 'tile-inner', 'modula-hidden-item' ),
			);

			if ( isset( $image['filters'] ) ) {
				$filters = explode( ',', $image['filters'] );
				foreach ( $filters as $filter ) {
					$attr['class'][] = 'jtg-filter-all jtg-filter-' . esc_attr( urldecode( sanitize_title( $filter ) ) );
				}
			}

			$image_full = wp_get_attachment_image_src( $image['id'], 'full' );
			if ( is_array( $image_full ) ) {
				$attr['href'] = $image_full[0];
			}
			if ( isset( $item_data['link_attributes']['data-fancybox'] ) ) {
				$attr['data-fancybox'] = $item_data['link_attributes']['data-fancybox'];
			}
			$caption = isset( $image['description'] ) ? $image['description'] : '';

			if ( '' == $caption ) {
				$caption = wp_get_attachment_caption( $image['id'] );
			}

			if ( 'fancybox' == $settings['lightbox'] ) {
				if ( (isset( $settings['showTitleLightbox'] ) && '1' == $settings['showTitleLightbox']) && (isset( $settings['showCaptionLightbox'] ) && '1' == $settings['showCaptionLightbox']) ) {
					$attr['data-caption'] = esc_attr( '<p>' . $image['title'] . '</p><p>' . $image['description'] . '</p>' );
				} else if ( (isset( $settings['showTitleLightbox'] ) && '1' == $settings['showTitleLightbox']) && (!isset( $settings['showCaptionLightbox'] ) || '1' != $settings['showCaptionLightbox']) ) {
					$attr['data-caption'] = esc_attr( '<p>' . $image['title'] . '</p>' );
				} else if ( (!isset( $settings['showTitleLightbox'] ) || '1' != $settings['showTitleLightbox']) && (isset( $settings['showCaptionLightbox'] ) && '1' == $settings['showCaptionLightbox']) ) {
					$attr['data-caption'] = esc_attr( '<p>' . $image['description'] . '</p>' );
				} else {
					$attr['data-caption'] = '';
				}
			} else {
				$attr['data-title'] = $caption;
			}

			echo '<a ' . Modula_Helper::generate_attributes( apply_filters( 'modula_hidden_items_data', $attr, $settings, $image ) ) . '></a>';
		}
		echo '</div>';
	}

	public function shortpixel_fix( $item_data ) {
		if ( isset( $item_data['img_attributes']['src'] ) && strpos( $item_data['img_attributes']['src'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['img_attributes']['src'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['img_attributes']['src'] );
		}
		if ( isset( $item_data['image_full'] ) && strpos( $item_data['image_full'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['image_full'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['image_full'] );
		}

		if ( isset( $item_data['image_url'] ) && strpos( $item_data['image_url'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['image_url'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['image_url'] );
		}
		if ( isset( $item_data['img_attributes']['data-src'] ) && strpos( $item_data['img_attributes']['data-src'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['img_attributes']['data-src'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['img_attributes']['data-src'] );
		}
		if ( isset( $item_data['link_attributes']['href'] ) && strpos( $item_data['link_attributes']['href'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['link_attributes']['href'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['img_attributes']['data-src'] );
		}
		return $item_data;
	}


	/**
	 * Enqueue only the scripts that are required by a specific effect
	 *
	 * @param $settings
	 */
	public function output_extra_effects_scripts( $settings ) {

		$effect      = $settings['effect'];
		$tilt_effect = array( 'tilt_1', 'tilt_2', 'tilt_3', 'tilt_4', 'tilt_5', 'tilt_6', 'tilt_7', 'tilt_8' );

		if ( in_array( $effect, $tilt_effect ) ) {
			wp_enqueue_script( 'modula-pro-tilt' );
		}
	}


	/**
	 * Enqueue needed scripts for the tilt and caption hover effects
	 *
	 */
	public function preview_extra_effects_scripts() {

		$current_screen = get_current_screen();

		// Register and Enqueue scripts only for Modula Gallery posts
		if ( 'modula-gallery' == $current_screen->id ) {

			// Modula Pro scripts used for Tilt Hover Effects
			wp_register_script( 'modula-pro-tilt', MODULA_PRO_URL . 'assets/js/modula-pro-tilt.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
			wp_enqueue_script( 'modula-pro-tilt' );
		}
	}

	/**
	 * Add extra elements for the tilt effect
	 *
	 * @param $data
	 */
	public function extra_effects_extra_elements( $data ) {

		if ( count( $data->item_classes ) > 1 ) {
			$hover_effect  = $data->item_classes[1];
			$effect        = explode( '-', $hover_effect );
			$effect_array  = array( 'tilt_1', 'tilt_2', 'tilt_3', 'tilt_4', 'tilt_5', 'tilt_6', 'tilt_7', 'tilt_8' );
			$overlay_array = array( 'tilt_2', 'tilt_3', 'tilt_4', 'tilt_5', 'tilt_6', 'tilt_7', 'tilt_8' );
			$svg_array     = array( 'tilt_1', 'tilt_2', 'tilt_4', 'tilt_5', 'tilt_6', 'tilt_7', 'tilt_8' );
			if ( in_array( $effect[1], $effect_array ) ) {
				echo '<div class="tilter__deco tilter__deco--shine"><div></div></div>';

				if ( in_array( $effect[1], $overlay_array ) ) {
					echo '<div class="tilter__deco tilter__deco--overlay"></div>';
				}

				if ( in_array( $effect[1], $svg_array ) ) {
					echo '<div class="tilter__deco tilter__deco--lines"></div>';
				}

			}
		}
	}

	public function modula_link_shortcode( $atts, $content = null ) {

		$default_atts = array(
			'id' => false,
		);

		$atts = wp_parse_args( $atts, $default_atts );

		if ( '' == $atts['id'] || !$atts['id'] ) {
			return esc_html( 'Gallery ID not found !' );
		}

		$gallery = get_post( absint( $atts['id'] ) );

		if ( 'modula-gallery' != get_post_type( $gallery ) ) {
			return esc_html__( 'Given ID doesn\'t belong to a Modula Gallery!', 'modula-pro' );
		}

		$script_manager = false;
		if ( class_exists( 'Modula_Script_Manager' ) ) {
			$script_manager = Modula_Script_Manager::get_instance();
		}


		$settings = get_post_meta( $atts['id'], 'modula-settings', true );
		$defaults = Modula_CPT_Fields_Helper::get_defaults();
		$settings = wp_parse_args( $settings, $defaults );

		// Need this for Modula Deeplink. Added the 'jtg-' string to comply with the search and replace
		$settings['gallery_id'] = 'jtg-link-' . $atts['id'];

		$pre_gallery_html = apply_filters( 'modula_pre_output_filter_check', false, $settings, $gallery );

		if ( false !== $pre_gallery_html ) {

			// If there is HTML, then we stop trying to display the gallery and return THAT HTML.
			$pre_output = apply_filters( 'modula_pre_output_filter', '', $settings, $gallery );
			return $pre_output;

		}

		$fancybox_options = Modula_Helper::lightbox_default_options();

		// This comes from the LITE version, se we need to set it here for modula link
		if ( isset( $settings['show_navigation'] ) && '1' == $settings['show_navigation'] ) {
			$fancybox_options['arrows'] = true;
		}

		$fancybox_options = apply_filters( 'modula_fancybox_options', $fancybox_options, $settings );

		// Enqueue only after we found a gallery
		$necessary_scripts = apply_filters( 'modula_link_necessary_scripts', array( 'modula-fancybox', 'modula-link-shortcode' ), $settings );

		$necessary_styles = apply_filters( 'modula_link_necessary_styles', array( 'modula-fancybox', 'modula' ), $settings );


		if ( !empty( $necessary_scripts ) ) {
			if ( !$script_manager ) {
				foreach ( $necessary_scripts as $script ) {
					wp_enqueue_script( $script );
				}
			} else {
				$script_manager->add_scripts( $necessary_scripts );
			}

		}

		if ( !empty( $necessary_styles ) ) {
			foreach ( $necessary_styles as $style ) {
				wp_enqueue_style( $style );
			}
		}

		do_action( 'modula_link_extra_scripts', $settings );

		$images      = get_post_meta( $atts['id'], 'modula-images', true );
		$imagesArray = array();

		if ( !empty( $images ) ) {
			foreach ( $images as $image ) {

				$image_url   = wp_get_attachment_image_src( $image['id'], 'full' );
				$image_thumb = wp_get_attachment_image_src( $image['id'] );

				$new_image = apply_filters( 'modula_link_item',
				                            array(
					                            'src'  => $image_url[0],
					                            'opts' => array(
						                            'caption'  => Modula_Pro::check_lightbox_link_title_caption( $settings, $image['title'], $image['description'] ),
						                            'thumb'    => $image_thumb[0],
						                            'image_id' => $image['id'] ),
				                            ), $image, $atts['id'], $settings );

				$imagesArray[] = $new_image;
			}
		}
		ob_start();

		$not_allowed_lightbox = array( 'no-link', 'direct', 'attachment-page' );
		$not_allowed_types    = array( 'slider' );

		$lightbox = 'true';

		if ( in_array( $settings['lightbox'], $not_allowed_lightbox ) || in_array( $settings['type'], $not_allowed_types ) ) {
			$lightbox = 'false';
		}

		$link_js_config = apply_filters( 'modula_link_gallery_settings', array(
			'lightbox'     => $lightbox,
			'lightboxOpts' => $fancybox_options
		), $settings );


		echo $this->modula_link_css( $atts['id'], $settings );

		echo '<a id="' . esc_attr( $settings['gallery_id'] ) . '" data-images="' . esc_attr( json_encode( $imagesArray ) ) . '"' . ' data-config="' . esc_attr( json_encode( $link_js_config ) ) . '" class="jtg-' . $atts['id'] . ' modula-link" href="#">' . do_shortcode( $content ) . '</a>';

		$html = ob_get_clean();

		return $html;

	}

	/**
	 * Output for link lightbox settings
	 *
	 * @param $settings
	 *
	 * @param $title
	 * @param $caption
	 *
	 * @return string $html
	 */
	public static function check_lightbox_link_title_caption( $settings, $title, $caption ) {

		$html = '';
		if ( 1 == $settings['showTitleLightbox'] ) {
			$html .= "<p> {$title} </p> ";
		}
		if ( 1 == $settings['showCaptionLightbox'] ) {
			$html .= "<p> {$caption} </p> ";
		}
		return $html;
	}

	public function output_column( $column, $post_id ) {

		if ( 'shortcode' == $column ) {
			$shortcode = '[modula-link id="' . $post_id . '"]' . esc_html__( 'Click here', 'modula-pro' ) . '[/modula-link]';
			echo '<div class="modula-copy-shortcode">';
			echo '<input type="text" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
			echo '<a href="#" title="' . esc_attr__( 'Copy shortcode', 'modula-pro' ) . '" class="copy-modula-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span></span>';
			echo '</div>';
		}

	}

	public function output_link_shortcode( $post ) {
		$shortcode = '[modula-link id="' . $post->ID . '"]' . esc_html__( 'Click here', 'modula-pro' ) . '[/modula-link]';
		echo '<div class="modula-copy-shortcode">';
		echo '<input type="text" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
		echo '<a href="#" title="' . esc_attr__( 'Copy shortcode', 'modula-pro' ) . '" class="copy-modula-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span></span>';
		echo '<p>' . sprintf( esc_html__( 'You can use this to display your newly created gallery by clicking on a %s link or image %s ', 'modula-pro' ), '<u>', '</u>' ) . '</p>';
		echo '</div>';
	}


	/**
	 * Remove Albums upsells metabox
	 *
	 * @param $metaboxes
	 *
	 * @return array
	 * @since 2.2.1
	 */
	public function remove_albums_upsell_metabox() {

		remove_meta_box( 'modula-albums-upsell', 'modula-gallery', 'normal' );
	}

	/**
	 * Add extra path for modula templates
	 *
	 * @param $paths
	 *
	 * @return mixed
	 */
	public function add_modula_pro_templates_path( $paths ) {
		$paths[50] = trailingslashit( MODULA_PRO_PATH ) . '/includes/templates';
		return $paths;
	}

	/* Modula Troubleshooting */
	public function add_troubleshooting_fields( $fields ) {

		$fields['hover_effects'] = array(
			'label'       => esc_html__( 'Hover Effects', 'modula-pro' ),
			'description' => esc_html__( 'Check this if you\'re using hover effects with tilt effect', 'modula-pro' ),
			'type'        => 'toggle',
			'priority'    => 50,
		);

		$fields['link_shortcode'] = array(
			'label'       => esc_html__( 'Link Shortcode', 'modula-pro' ),
			'description' => esc_html__( 'Check this if you\'re using modula link ( [modula-link] ) shortcode', 'modula-pro' ),
			'type'        => 'toggle',
			'priority'    => 50,
		);

		return $fields;

	}

	public function add_troubleshooting_defaults( $defaults ) {
		$defaults['hover_effects']  = false;
		$defaults['link_shortcode'] = false;
		return $defaults;
	}

	public function add_main_pro_files( $handles ) {

		// remove modula lite effects
		$lite_effects = array_search( 'modula-effects', $handles['styles'] );
		if ( false !== $lite_effects ) {
			unset( $handles['styles'][$lite_effects] );
		}

		// add modula pro main files
		$handles['scripts'][] = 'modula-pro';
		$handles['styles'][]  = 'modula-pro-effects';

		return $handles;
	}

	public function check_hovereffect( $handles, $options ) {

		if ( $options['hover_effects'] ) {
			$handles['scripts'][] = 'modula-pro-tilt';
		}

		return $handles;

	}

	public function check_linkshortcode( $handles, $options ) {

		if ( $options['link_shortcode'] ) {
			$handles['scripts'][] = 'modula-link-shortcode';
		}

		return $handles;

	}

	/**
	 * Modula Link CSS
	 *
	 * @param $gallery_id
	 * @param $settings
	 *
	 * @return string
	 *
	 * @since 2.2.4
	 */
	public function modula_link_css( $gallery_id, $settings ) {

		$css = '<style>';

		if ( isset( $settings['lightbox_bottomThumbs'] ) && '1' == $settings['lightbox_bottomThumbs'] ) {
			$css .= '@media all and (min-width: 768px) { .modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-thumbs { top: auto !important; width: auto !important; bottom: 0; left: 0; right : 0; height: 95px; padding: 10px 10px 5px 10px; box-sizing: border-box; background: rgba(0, 0, 0, 0.6) !important;} .modula-fancybox-container.modula-lightbox-' . $gallery_id . '.modula-fancybox-show-thumbs .modula-fancybox-inner {  right: 0 !important; bottom: 95px !important; } }';
		}

		if ( isset( $settings['lightbox_background_color'] ) && '' != $settings['lightbox_background_color'] ) {
			$css .= '.modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-bg{background:' . $settings['lightbox_background_color'] . ';opacity:1 !important;}';
		}

		if ( isset( $settings['captionPosition'] ) ) {

			$css .= '.modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-caption .modula-fancybox-caption__body, .modula-fancybox-container.modula-lightbox-' . $gallery_id . ' .modula-fancybox-caption .modula-fancybox-caption__body p {text-align:' . esc_attr( $settings['captionPosition'] ) . '}';
		}

		if ( strlen( $settings['style'] ) ) {
			$css .= esc_html( $settings['style'] );
		}

		$css = apply_filters( 'modula_link_shortcode_css', $css, $gallery_id, $settings );

		$css .= '</style>';

		return $css;
	}


	/**
	 * Add helper classes for filters
	 *
	 * @param $template_data
	 *
	 * @return mixed
	 *
	 * @since 2.3.0
	 */
	public function filter_class_helper( $template_data ) {

		if ( isset( $template_data['settings']['filters'] ) && '' != $template_data['settings']['filters'] ) {
			if ( isset( $template_data['settings']['filterPositioning'] ) ) {

				$horizontal_position = array( 'top', 'bottom', 'top_bottom' );
				$vertical_position   = array( 'left', 'right', 'left_right' );

				if ( in_array( $template_data['settings']['filterPositioning'], $vertical_position ) && !empty( Modula_Pro_Helper::remove_empty_items( $template_data['settings']['filters'] ) ) ) {
					$template_data['gallery_container']['class'][] = 'vertical-filters';
				}

				if ( in_array( $template_data['settings']['filterPositioning'], $horizontal_position ) && !empty( Modula_Pro_Helper::remove_empty_items( $template_data['settings']['filters'] ) ) ) {
					$template_data['gallery_container']['class'][] = 'horizontal-filters';
				}

			}
		}

		return $template_data;
	}


	/**
	 * Uninstall process
	 *
	 * @param $db_options
	 *
	 * @return mixed
	 *
	 * @since 2.3.0
	 */
	public function uninstall_process( $db_options ) {

		$db_options[] = 'modula_pro_license_status';
		$db_options[] = 'modula_pro_license_key';

		return $db_options;
	}

	/**
	 * Add gallery title to page
	 *
	 * @param $settings
	 *
	 * @since 2.3.2
	 */
	public function modula_gallery_title( $settings ) {

		$title = get_the_title( explode( '-', $settings['gallery_id'] )[1] );
		if ( '1' == $settings['show_gallery_title'] ) {

			echo " <" . esc_attr( $settings['gallery_title_type'] ) . " class='modula-gallery-title'> " . esc_html( $title ) . " </" . esc_attr( $settings['gallery_title_type'] ) . "> ";
		}

	}

}
