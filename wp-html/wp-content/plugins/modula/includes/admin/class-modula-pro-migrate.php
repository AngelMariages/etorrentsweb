<?php

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Modula_Pro_Importer {

	/**
	 * Holds the class object.
	 *
	 * @since 2.4.2
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.4.2
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object The Modula_Pro_Importer object.
	 *
	 * @since 2.4.2
	 */
	public static function get_instance() {

		if ( !isset( self::$instance ) && !(self::$instance instanceof Modula_Pro_Importer) ) {
			self::$instance = new Modula_Pro_Importer();
		}

		return self::$instance;
	}

	/**
	 * Load migrator
	 *
	 * @since 2.4.2
	 */
	public function init() {

		// add pro data to migrator gallery data
		add_filter( 'modula_migrate_gallery_data', array( $this, 'migrate_pro_data' ), 20, 3 );
		// add pro data to migrator image data
		add_filter( 'modula_migrate_image_data', array( $this, 'migrator_pro_image_data' ), 20, 4 );

	}

	/**
	 * Add Modula Pro settings to migrator
	 *
	 * @param $modula_settings
	 * @param $guest_settings
	 * @param $source
	 *
	 * @return mixed
	 *
	 * @since 2.3.3
	 */
	public function migrate_pro_data( $modula_settings, $guest_settings, $source ) {

		if ( $source ) {
			switch ( $source ) {
				case 'envira':

					// lightbox caption and title setting
					if ( isset( $guest_settings['config']['lightbox_title_caption'] ) ) {
						if ( '0' == $guest_settings['config']['lightbox_title_caption'] ) {
							$modula_settings['showTitleLightbox']   = 0;
							$modula_settings['showCaptionLightbox'] = 0;
						} else if ( 'title' == $guest_settings['config']['lightbox_title_caption'] ) {
							$modula_settings['showTitleLightbox']   = 1;
							$modula_settings['showCaptionLightbox'] = 0;
						} else {
							$modula_settings['showTitleLightbox']   = 1;
							$modula_settings['showCaptionLightbox'] = 1;
						}
					}

					// lightbox loop setting
					if ( isset( $guest_settings['config']['loop'] ) && 1 == $guest_settings['config']['loop'] ) {
						$modula_settings['loop_lightbox'] = 1;
					}

					// lightbox animation setting
					if ( isset( $guest_settings['config']['lightbox_open_close_effect'] ) ) {
						if ( 'none' == $guest_settings['config']['lightbox_open_close_effect'] ) {
							$modula_settings['lightbox_animationEffect'] = 'false';
						} else {
							$modula_settings['lightbox_animationEffect'] = $guest_settings['config']['lightbox_open_close_effect'];
						}
					}

					// lightbox transition setting
					if ( isset( $guest_settings['config']['effect'] ) ) {
						$modula_settings['lightbox_transitionEffect'] = $guest_settings['config']['effect'];
					}

					// lightbox thumbnails button
					if ( isset( $guest_settings['config']['thumbnails_toggle'] ) && 1 == $guest_settings['config']['thumbnails_toggle'] ) {
						$modula_settings['lightbox_thumbs'] = 1;
					}

					// lightbox autostart thumbnails
					if ( isset( $guest_settings['config']['thumbnails'] ) && 1 == $guest_settings['config']['thumbnails'] ) {
						$modula_settings['lightbox_thumbsAutoStart'] = 1;
					}

					if ( isset( $guest_settings['config']['tags_filter'] ) && '' != $guest_settings['config']['tags_filter'] ) {
						$filters = explode( ',', $guest_settings['config']['tags_filter'] );
						foreach ( $filters as $filter ) {
							$modula_settings['filters'][] = $filter;
						}
					}

					if ( isset( $guest_settings['config']['social_lightbox'] ) && 1 == $guest_settings['config']['social_lightbox'] ) {
						$modula_settings['lightbox_share'] = 1;
					}

					if ( isset( $guest_settings['config']['social_lightbox_facebook'] ) && 1 == $guest_settings['config']['social_lightbox_facebook'] ) {
						$modula_settings['lightbox_facebook'] = 1;
					}

					if ( isset( $guest_settings['config']['social_lightbox_twitter'] ) && 1 == $guest_settings['config']['social_lightbox_twitter'] ) {
						$modula_settings['lightbox_twitter'] = 1;
					}

					if ( isset( $guest_settings['config']['social_lightbox_pinterest'] ) && 1 == $guest_settings['config']['social_lightbox_pinterest'] ) {
						$modula_settings['lightbox_pinterest'] = 1;
					}

					if ( isset( $guest_settings['config']['social_lightbox_linkedin'] ) && 1 == $guest_settings['config']['social_lightbox_linkedin'] ) {
						$modula_settings['lightbox_linkedin'] = 1;
					}

					if ( isset( $guest_settings['config']['social_lightbox_email'] ) && 1 == $guest_settings['config']['social_lightbox_email'] ) {
						$modula_settings['lightbox_email'] = 1;
					}

					$email_subject = esc_html__( 'Check out this awesome image !!', 'modula-pro' );
					$email_body    = esc_html__( 'Here is the link to the image : %%image_link%% and this is the link to the gallery : %%gallery_link%%', 'modula-pro' );

					if ( isset( $guest_settings['config']['social_email_subject'] ) && '' != $guest_settings['config']['social_email_subject'] ) {
						$email_subject = str_replace( '{title}', sanitize_text_field( get_the_title( $guest_settings['id'] ) ), $guest_settings['config']['social_email_subject'] );
					}

					if ( isset( $guest_settings['config']['social_email_message'] ) && '' != $guest_settings['config']['social_email_message'] ) {
						$email_body = str_replace( '{url}', '%%gallery_link%%', $guest_settings['config']['social_email_message'] );
						$email_body = str_replace( '{photo_url}', '%%image_link%%', $email_body );
					}

					$modula_settings['lightboxEmailSubject'] = $email_subject;
					$modula_settings['lightboxEmailMessage'] = $email_body;

					$modula_captions_title   = 1;
					$modula_captions_caption = 1;

					if ( isset( $guest_settings['config']['lightbox_title_caption'] ) ) {
						if ( '0' == $guest_settings['config']['lightbox_title_caption'] ) {
							$modula_captions_title   = 0;
							$modula_captions_caption = 0;
						} else if ( 'title' == $guest_settings['config']['lightbox_title_caption'] ) {
							$modula_captions_title   = 1;
							$modula_captions_caption = 0;
						} else if ( 'caption' == $guest_settings['config']['lightbox_title_caption'] ) {
							$modula_captions_title   = 0;
							$modula_captions_caption = 1;
						}
					}

					$modula_settings['showTitleLightbox']   = $modula_captions_title;
					$modula_settings['showCaptionLightbox'] = $modula_captions_caption;

					break;

				case 'foogallery':
					if ( 'thumbnail' == $guest_settings['grid_type'] ) {
						$modula_settings['maxImagesCount'] = 1;
					}
					break;
			}
		}

		return $modula_settings;
	}

	/**
	 * Add Modula PRO data to migrator image data
	 *
	 * @param $modula_image
	 * @param $guest_image
	 * @param $guest_settings
	 * @param $source
	 *
	 * @return mixed
	 *
	 * @since 2.3.3
	 */
	public function migrator_pro_image_data( $modula_image, $guest_image, $guest_settings, $source ) {

		if ( $source ) {
			switch ( $source ) {
				case 'envira' :

					if ( taxonomy_exists( 'envira-tag' ) ) {
						$filter_string = '';
						$filters       = get_the_terms( $guest_image, 'envira-tag' );
						$i             = 0;

						foreach ( $filters as $filter ) {
							$i++;
							$filter_string .= ($i < count( $filters )) ? $filter->name . ', ' : $filter->name;
						}

						$modula_image['filters'] = $filter_string;

					}

					break;
			}
		}

		return $modula_image;
	}

}

$modula_pro_importer = Modula_Pro_Importer::get_instance();