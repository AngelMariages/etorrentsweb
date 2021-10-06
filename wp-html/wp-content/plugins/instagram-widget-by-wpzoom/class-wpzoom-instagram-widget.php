<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpzoom_Instagram_Widget extends WP_Widget {
	/**
	 * @var Wpzoom_Instagram_Widget_API
	 */
	protected $api;

	/**
	 * @var array Default widget settings.
	 */
	protected $defaults;

	public function __construct() {
		parent::__construct(
			'wpzoom_instagram_widget',
			esc_html__( 'Instagram Widget by WPZOOM', 'instagram-widget-by-wpzoom' ),
			array(
				'classname'   => 'zoom-instagram-widget',
				'description' => __( 'Displays a user\'s Instagram timeline.', 'instagram-widget-by-wpzoom' ),
			)
		);

		$this->defaults = array(
			'title'                         => esc_html__( 'Instagram', 'instagram-widget-by-wpzoom' ),
			'button_text'                   => esc_html__( 'View on Instagram', 'instagram-widget-by-wpzoom' ),
			'image-limit'                   => 9,
			'show-view-on-instagram-button' => true,
			'show-counts-on-hover'          => false,
			'show-user-info'                => false,
			'show-user-bio'                 => false,
			'lazy-load-images'              => false,
			'disable-video-thumbs'          => false,
			'display-media-type-icons'      => false,
			'images-per-row'                => 3,
			'image-width'                   => 120,
			'image-spacing'                 => 10,
			'image-resolution'              => 'default_algorithm',
			'username'                      => '',
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

		/**
		 * Enqueue styles and scripts for SiteOrigin Page Builder.
		 */
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'styles' ) );
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'register_scripts' ) );
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Convert $url to file path.
	 *
	 * @param $url
	 *
	 * @return string|string[]
	 */
	function convert_url_to_path( $url ) {
		return str_replace(
			wp_get_upload_dir()['baseurl'],
			wp_get_upload_dir()['basedir'],
			$url
		);
	}

	/**
	 * Load widget specific styles.
	 */
	public function styles() {
		wp_enqueue_style(
			'zoom-instagram-widget',
			plugin_dir_url( __FILE__ ) . 'css/instagram-widget.css',
			array( 'dashicons' ),
			WPZOOM_INSTAGRAM_VERSION
		);
	}

	/**
	 * Register widget specific scripts.
	 */
	public function register_scripts() {
		$file_mod_time = filemtime( plugin_dir_path( __FILE__ ) . 'js/jquery.lazy.min.js' );

		wp_register_script(
			'zoom-instagram-widget-lazy-load',
			plugin_dir_url( __FILE__ ) . 'js/jquery.lazy.min.js',
			array( 'jquery' ),
			strval( $file_mod_time ),
			true
		);
		wp_register_script(
			'zoom-instagram-widget',
			plugin_dir_url( __FILE__ ) . 'js/instagram-widget.js',
			array( 'jquery', 'underscore', 'wp-util' ),
			WPZOOM_INSTAGRAM_VERSION,
			true
		);
	}

	/**
	 * Load widget specific scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'zoom-instagram-widget-lazy-load' );
		wp_enqueue_script( 'zoom-instagram-widget' );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$this->api = Wpzoom_Instagram_Widget_API::getInstance();

		$this->enqueue_scripts();

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( $instance['title'] ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$items  = $this->api->get_items( $instance );
		$errors = $this->api->errors->get_error_messages();

		if ( ! is_array( $items ) ) {
			$this->display_errors( $errors );
		} else {
			if ( ! empty( $instance['show-user-info'] ) ) {
				$user_info = $this->api->get_user_info( $instance['username'] );

				if (
					is_object( $user_info ) &&
					! empty( $user_info ) &&
					! empty( $user_info->data )
				) {
					$this->display_user_info( $instance, $user_info );
				}
			}

			$this->display_items( $items['items'], $instance );
			$this->display_instagram_button( $instance, $items['username'] );
		}

		echo $args['after_widget'];
	}

	/**
	 * Output errors if widget is misconfigured and current user can manage options (plugin settings).
	 *
	 * @return void
	 */
	protected function display_errors( $errors ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			?>
			<p>
				<?php _e( 'Instagram Widget misconfigured or your Access Token <strong>expired</strong>. Please check', 'instagram-widget-by-wpzoom' ); ?>
				  <strong><a href="<?php echo admin_url( 'options-general.php?page=wpzoom-instagram-widget' ); ?>" target="_blank"><?php _e( 'Instagram Settings Page', 'instagram-widget-by-wpzoom' ); ?></a></strong> <?php _e( 'and make sure the plugin is properly configured', 'instagram-widget-by-wpzoom' ); ?>

			</p>

			<?php if ( ! empty( $errors ) ) : ?>
				<ul>
					<?php foreach ( $errors as $error ) : ?>
						<li><?php echo $error; ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php
		} else {
			echo '&#8230;';
		}
	}

	protected function display_user_info( $instance, $user_info ) {
		?>
		<div class="zoom-instagram-widget-user-info">
			<?php if ( ! empty( $user_info->data->profile_picture ) ) : ?>
				<div class="zoom-instagram-widget-user-info-picture">
					<a target="_blank" rel="noopener nofollow"
					   href="<?php printf( 'https://instagram.com/%s?ref=badge', esc_attr( $user_info->data->username ) ); ?>"><img
								width="90" src="<?php echo $user_info->data->profile_picture; ?>"
								alt="<?php echo esc_attr( $user_info->data->full_name ); ?>"/></a>
				</div>
			<?php endif; ?>
			<div class="zoom-instagram-widget-user-info-meta">
				<div class="zoom-instagram-widget-user-info-about-data">
					<div class="zoom-instagram-widget-user-info-names-wrapper">
						<?php if ( ! empty( $user_info->data->full_name ) ) : ?>
							<div class="zoom-instagram-widget-user-info-fullname">
								<?php esc_html_e( $user_info->data->full_name, 'instagram-widget-by-wpzoom' ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $user_info->data->username ) ) : ?>
							<div class="zoom-instagram-widget-user-info-username">
								<?php esc_html_e( '@' . $user_info->data->username, 'instagram-widget-by-wpzoom' ); ?>
							</div>
						<?php endif; ?>
					</div>
					<div>
						<a class="zoom-instagram-widget-user-info-follow-button" target="_blank" rel="noopener nofollow"
						   href="<?php printf( 'https://instagram.com/%s?ref=badge', esc_attr( $user_info->data->username ) ); ?>">
							<?php _e( 'Follow', 'instagram-widget-by-wpzoom' ); ?>
						</a>
					</div>
				</div>
				<div class="zoom-instagram-widget-user-info-stats">
					<?php if ( ! empty( $user_info->data->counts->media ) ) : ?>
						<div>
							<div class="zoom-instagram-widget-user-info-counts"
								 title="<?php echo number_format( $user_info->data->counts->media ); ?>">
								<?php echo $this->format_number( $user_info->data->counts->media ); ?>
							</div>
							<div class="zoom-instagram-widget-user-info-counts-subhead">
								<?php _e( 'posts', 'instagram-widget-by-wpzoom' ); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $user_info->data->counts->followed_by ) ) : ?>
						<div class="zoom-instagram-widget-user-info-middle-cell">
							<div class="zoom-instagram-widget-user-info-counts"
								 title="<?php echo number_format( $user_info->data->counts->followed_by ); ?>">
								<?php echo $this->format_number( $user_info->data->counts->followed_by ); ?>
							</div>
							<div class="zoom-instagram-widget-user-info-counts-subhead">
								<?php _e( 'followers', 'instagram-widget-by-wpzoom' ); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $user_info->data->counts->follows ) ) : ?>
						<div>
							<div class="zoom-instagram-widget-user-info-counts"
								 title="<?php echo number_format( $user_info->data->counts->follows ); ?>">
								<?php echo $this->format_number( $user_info->data->counts->follows ); ?>
							</div>
							<div class="zoom-instagram-widget-user-info-counts-subhead">
								<?php _e( 'following', 'instagram-widget-by-wpzoom' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
		<?php
		if ( ! empty( $instance['show-user-bio'] ) ) {
			if ( ! empty( $user_info->data->bio ) ) :
				?>
				<div class="zoom-instagram-widget-user-info-bio"><?php echo nl2br( $user_info->data->bio ); ?></div>
				<?php
			endif;
		}
		?>

		<?php
	}

	public function format_number( $num ) {
		if ( $num < 10000 ) {
			return number_format( $num );
		}

		$units = array( '', 'k', 'm', 'b', 't' );
		for ( $i = 0; $num >= 1000; $i ++ ) {
			$num /= 1000;
		}

		return round( $num, 1 ) . $units[ $i ];
	}

	protected function display_items( $items, $instance ) {
		$count                 = 0;
		$show_overlay          = wp_validate_boolean( $instance['show-counts-on-hover'] );
		$show_media_type_icons = wp_validate_boolean( $instance['display-media-type-icons'] );
		$small_class           = ( ! empty( $instance['image-width'] ) && $instance['image-width'] <= 180 ) ? 'small' : '';
		$svg_icons             = plugin_dir_url( __FILE__ ) . 'images/wpzoom-instagram-icons.svg';
		?>
		<ul class="zoom-instagram-widget__items zoom-instagram-widget__items--no-js"
			data-images-per-row="<?php echo esc_attr( $instance['images-per-row'] ); ?>"
			data-image-width="<?php echo esc_attr( $instance['image-width'] ); ?>"
			data-image-spacing="<?php echo esc_attr( $instance['image-spacing'] ); ?>"
			data-image-resolution="<?php echo esc_attr( $instance['image-resolution'] ); ?>"
			data-image-lazy-loading="<?php echo esc_attr( $instance['lazy-load-images'] ); ?>">

			<?php foreach ( $items as $item ) : ?>
				<?php

				$inline_attrs  = '';
				$overwrite_src = false;
				$link          = $item['link'];
				$src           = $item['image-url'];
				$media_id      = $item['image-id'];
				$alt           = esc_attr( $item['image-caption'] );
				$likes         = $item['likes_count'];
				$type          = in_array(
					$item['type'],
					array(
						'VIDEO',
						'CAROUSEL_ALBUM',
					)
				) ? strtolower( $item['type'] ) : false;
				$comments      = $item['comments_count'];

				if ( ! empty( $media_id ) && empty( $src ) ) {
					$inline_attrs  = 'data-media-id="' . esc_attr( $media_id ) . '"';
					$inline_attrs .= 'data-nonce="' . wp_create_nonce( WPZOOM_Instagram_Image_Uploader::get_nonce_action( $media_id ) ) . '"';

					$overwrite_src = true;
				}

				if (
					! empty( $media_id ) &&
					! empty( $src ) &&
					! file_exists( $this->convert_url_to_path( $src ) )
				) {
					$inline_attrs  = 'data-media-id="' . esc_attr( $media_id ) . '"';
					$inline_attrs .= 'data-nonce="' . wp_create_nonce( WPZOOM_Instagram_Image_Uploader::get_nonce_action( $media_id ) ) . '"';
					$inline_attrs .= 'data-regenerate-thumbnails="1"';

					$overwrite_src = true;
				}

				if ( $overwrite_src ) {
					$src = $item['original-image-url'];
				}
				?>

				<li class="zoom-instagram-widget__item" <?php echo $inline_attrs; ?>>

					<?php
					$inline_style  = 'width:' . esc_attr( $instance['image-width'] ) . 'px;';
					$inline_style .= 'height:' . esc_attr( $instance['image-width'] ) . 'px;';
					if ( empty( $instance['lazy-load-images'] ) ) {
						$inline_style .= "background-image: url('" . $src . "');";
					}

					if ( $show_overlay ) :
						?>
						<div class="hover-layout zoom-instagram-widget__overlay zoom-instagram-widget__black <?php echo $small_class; ?>">
							<?php if ( $show_media_type_icons && ! empty( $type ) ) : ?>
								<svg class="svg-icon" shape-rendering="geometricPrecision">
									<use xlink:href="<?php echo esc_url( $svg_icons ); ?>#<?php echo $type; ?>"></use>
								</svg>
							<?php endif; ?>

							<?php if ( ! empty( $likes ) && ! empty( $comments ) ) : ?>
								<div class="hover-controls">
									<span class="dashicons dashicons-heart"></span>
									<span class="counter"><?php echo $this->format_number( $likes ); ?></span>
									<span class="dashicons dashicons-format-chat"></span>
									<span class="counter"><?php echo $this->format_number( $comments ); ?></span>
								</div>
							<?php endif; ?>
							<div class="zoom-instagram-icon-wrap">
								<a class="zoom-svg-instagram-stroke" href="<?php echo $link; ?>" rel="noopener nofollow"
								   target="_blank" title="<?php echo $alt; ?>"></a>
							</div>


							<a class="zoom-instagram-link" data-src="<?php echo $src; ?>"
							   style="<?php echo $inline_style; ?>"
							   href="<?php echo $link; ?>" target="_blank" rel="noopener nofollow" title="<?php echo $alt; ?>"
							>
							</a>
						</div>
					<?php else : ?>
						<a class="zoom-instagram-link" data-src="<?php echo $src; ?>"
						   style="<?php echo $inline_style; ?>"
						   href="<?php echo $link; ?>" target="_blank" rel="noopener nofollow" title="<?php echo $alt; ?>"
						>
							<?php if ( $show_media_type_icons && ! empty( $type ) ) : ?>
								<svg class="svg-icon" shape-rendering="geometricPrecision">
									<use xlink:href="<?php echo esc_url( $svg_icons ); ?>#<?php echo $type; ?>"></use>
								</svg>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</li>

				<?php
				if ( ++ $count === $instance['image-limit'] ) {
					break;
				}
				?>

			<?php endforeach; ?>

		</ul>

		<div style="clear:both;"></div>
		<?php
	}

	protected function display_instagram_button( $instance, $username ) {
		$show_view_on_instagram_button = $instance['show-view-on-instagram-button'];

		if ( ! $show_view_on_instagram_button ) {
			return;
		}

		?>
		<div class="zoom-instagram-widget__follow-me">
			<a href="<?php printf( 'https://instagram.com/%s?ref=badge', esc_attr( $username ) ); ?>"
			   class="ig-b- ig-b-v-24" rel="noopener nofollow"
			   target="_blank"><?php echo esc_attr( $instance['button_text'] ); ?></a>
		</div>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance['title']       = sanitize_text_field( $new_instance['title'] );
		$instance['button_text'] = sanitize_text_field( $new_instance['button_text'] );

		$instance['image-limit'] = ( 0 !== (int) $new_instance['image-limit'] ) ? (int) $new_instance['image-limit'] : null;

		$instance['images-per-row']   = ( 0 !== (int) $new_instance['images-per-row'] ) ? (int) $new_instance['images-per-row'] : null;
		$instance['image-width']      = ( 0 !== (int) $new_instance['image-width'] ) ? (int) $new_instance['image-width'] : null;
		$instance['image-spacing']    = ( 0 <= (int) $new_instance['image-spacing'] ) ? (int) $new_instance['image-spacing'] : null;
		$instance['image-resolution'] = ! empty( $new_instance['image-resolution'] ) ? $new_instance['image-resolution'] : $this->defaults['image-resolution'];
		$instance['username']         = ! empty( $new_instance['username'] ) ? $new_instance['username'] : $this->defaults['username'];

		$instance['show-view-on-instagram-button'] = ! empty( $new_instance['show-view-on-instagram-button'] );
		$instance['show-counts-on-hover']          = ! empty( $new_instance['show-counts-on-hover'] );
		$instance['show-user-info']                = ! empty( $new_instance['show-user-info'] );
		$instance['show-user-bio']                 = ! empty( $new_instance['show-user-bio'] );
		$instance['lazy-load-images']              = ! empty( $new_instance['lazy-load-images'] );
		$instance['disable-video-thumbs']          = ! empty( $new_instance['disable-video-thumbs'] );
		$instance['display-media-type-icons']      = ! empty( $new_instance['display-media-type-icons'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$this->api = Wpzoom_Instagram_Widget_API::getInstance();

		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>

		<?php if ( empty( $instance['username'] ) && ! $this->api->is_configured() ) : ?>

			<p style="color: #d54e21">
				<?php
				printf(
					__( 'You need to configure <a href="%1$s">plugin settings</a> before using this widget.', 'instagram-widget-by-wpzoom' ),
					admin_url( 'options-general.php?page=wpzoom-instagram-widget' )
				);
				?>
			</p>

		<?php endif; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'instagram-widget-by-wpzoom' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				   value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>


		<p>
			<label for="<?php echo $this->get_field_id( 'image-limit' ); ?>"><?php esc_html_e( 'Number of Images Shown:', 'instagram-widget-by-wpzoom' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image-limit' ); ?>"
				   name="<?php echo $this->get_field_name( 'image-limit' ); ?>" type="number" min="1" max="30"
				   value="<?php echo esc_attr( $instance['image-limit'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'images-per-row' ); ?>"><?php esc_html_e( 'Desired number of Images per row:', 'instagram-widget-by-wpzoom' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'images-per-row' ); ?>"
				   name="<?php echo $this->get_field_name( 'images-per-row' ); ?>" type="number" min="1" max="20"
				   value="<?php echo esc_attr( $instance['images-per-row'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image-width' ); ?>"><?php esc_html_e( 'Desired Image width in pixels:', 'instagram-widget-by-wpzoom' ); ?>
				<small>(Just integer)</small>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image-width' ); ?>"
				   name="<?php echo $this->get_field_name( 'image-width' ); ?>" type="number" min="20"
				   value="<?php echo esc_attr( $instance['image-width'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image-resolution' ); ?>"><?php esc_html_e( 'Force image resolution:', 'instagram-widget-by-wpzoom' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'image-resolution' ); ?>"
					name="<?php echo $this->get_field_name( 'image-resolution' ); ?>">
				<option value="default_algorithm" <?php selected( $instance['image-resolution'], 'default_algorithm' ); ?>>
					<?php _e( 'By Default Algorithm', 'instagram-widget-by-wpzoom' ); ?>
				</option>
				<option value="thumbnail" <?php selected( $instance['image-resolution'], 'thumbnail' ); ?>>
					<?php _e( 'Thumbnail ( 150x150px )', 'instagram-widget-by-wpzoom' ); ?>
				</option>
				<option value="low_resolution" <?php selected( $instance['image-resolution'], 'low_resolution' ); ?>>
					<?php _e( 'Low Resolution ( 320x320px )', 'instagram-widget-by-wpzoom' ); ?>

				</option>
				<option value="standard_resolution" <?php selected( $instance['image-resolution'], 'standard_resolution' ); ?>>
					<?php _e( 'Standard Resolution ( 640x640px )', 'instagram-widget-by-wpzoom' ); ?>
				</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image-spacing' ); ?>"><?php esc_html_e( 'Image spacing in pixels:', 'instagram-widget-by-wpzoom' ); ?>
				<small>(Just integer)</small>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image-spacing' ); ?>"
				   name="<?php echo $this->get_field_name( 'image-spacing' ); ?>" type="number" min="0" max="50"
				   value="<?php echo esc_attr( $instance['image-spacing'] ); ?>"/>
		</p>

		<p class="description">
			<?php
			echo wp_kses_post(
				__( 'Fields above do not influence directly widget appearance. Final number of images per row and image width is calculated depending on browser resolution. This ensures your photos look beautiful on all devices.', 'instagram-widget-by-wpzoom' )
			);
			?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><strong><?php esc_html_e( 'Instagram @username:', 'instagram-widget-by-wpzoom' ); ?></strong></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>"
				   name="<?php echo $this->get_field_name( 'username' ); ?>" type="text"
				   value="<?php echo esc_attr( $instance['username'] ); ?>"/>
		</p>

		<p class="description">

			<?php
			printf(
				__( 'If you have already connected your Instagram account in the <a href="%1$s">plugin settings</a>, leave this field empty. You can use this option if you want to display the feed of a different Instagram account.', 'instagram-widget-by-wpzoom' ),
				admin_url( 'options-general.php?page=wpzoom-instagram-widget' )
			);
			?>

		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show-user-info'] ); ?>
				   id="<?php echo $this->get_field_id( 'show-user-info' ); ?>"
				   name="<?php echo $this->get_field_name( 'show-user-info' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'show-user-info' ); ?>"><?php _e( ' Display <strong>User Details</strong>', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show-user-bio'] ); ?>
				   id="<?php echo $this->get_field_id( 'show-user-bio' ); ?>"
				   name="<?php echo $this->get_field_name( 'show-user-bio' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'show-user-bio' ); ?>"><?php _e( ' Display <strong>Bio in User Details</strong>', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show-view-on-instagram-button'] ); ?>
				   id="<?php echo $this->get_field_id( 'show-view-on-instagram-button' ); ?>"
				   name="<?php echo $this->get_field_name( 'show-view-on-instagram-button' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'show-view-on-instagram-button' ); ?>"><?php _e( ' Display <strong>View on Instagram</strong> button', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show-counts-on-hover'] ); ?>
				   id="<?php echo $this->get_field_id( 'show-counts-on-hover' ); ?>"
				   name="<?php echo $this->get_field_name( 'show-counts-on-hover' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'show-counts-on-hover' ); ?>"><?php _e( ' Show <strong>overlay with Instagram icon</strong> on hover', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['lazy-load-images'] ); ?>
				   id="<?php echo $this->get_field_id( 'lazy-load-images' ); ?>"
				   name="<?php echo $this->get_field_name( 'lazy-load-images' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'lazy-load-images' ); ?>"><?php _e( 'Lazy Load <strong>images</strong>', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['disable-video-thumbs'] ); ?>
				   id="<?php echo $this->get_field_id( 'disable-video-thumbs' ); ?>"
				   name="<?php echo $this->get_field_name( 'disable-video-thumbs' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'disable-video-thumbs' ); ?>"><?php _e( 'Hide video <strong>thumbnails</strong>', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['display-media-type-icons'] ); ?>
				   id="<?php echo $this->get_field_id( 'display-media-type-icons' ); ?>"
				   name="<?php echo $this->get_field_name( 'display-media-type-icons' ); ?>"/>
			<label for="<?php echo $this->get_field_id( 'display-media-type-icons' ); ?>"><?php _e( 'Show <strong>media type icons</strong>', 'instagram-widget-by-wpzoom' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php esc_html_e( 'Button Text:', 'instagram-widget-by-wpzoom' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>"
				   name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text"
				   value="<?php echo esc_attr( $instance['button_text'] ); ?>"/>
		</p>


		<?php
	}
}
