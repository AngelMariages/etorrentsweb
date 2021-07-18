<?php

require_once dirname( __FILE__ ) . '/wpzoom-giphy-api.php';
require_once dirname( __FILE__ ) . '/wpzoom-giphy-controller.php';
require_once dirname( __FILE__ ) . '/wpzoom-self-hosted-controller.php';

class WPZOOM_Video_Background_On_Hover {

	public $giphy_instance = null;

	public $giphy;

	public $self_hosted;

	public static $prefix = 'wpz';
	public static $type = array( 'self_hosted' );

	public $allowed_types = array( 'self_hosted', 'giphy' );
	public $child_classes = array(
		'self_hosted' => 'WPZOOM_Self_Hosted_Controller',
		'giphy'       => 'WPZOOM_Giphy_Controller'
	);

	public $allowed_screens = [ 'portfolio_item' ];

	public function __construct( $args ) {

		$filtered_args = wp_array_slice_assoc( $args, array( 'prefix', 'type', 'allowed_screens' ) );

		self::$type   = $filtered_args['type'];
		self::$prefix = $filtered_args['prefix'];

		if ( ! empty( $filtered_args['allowed_screens'] ) && is_array( $filtered_args['allowed_screens'] ) ) {
			$this->allowed_screens = array_merge( $this->allowed_screens, $filtered_args['allowed_screens'] );
		}

		foreach ( $this->child_classes as $key => $instance ) {
			if ( $this->is_type( $key ) ) {
				$this->{$key} = new $instance( $args );
			}
		}

		if ( is_admin() ) {

			add_action( 'save_post', array( $this, 'save_metadata' ) );
			add_action( 'wpz_render_background_video_on_hover', array( $this, 'render_background_wrapper' ) );
			add_action( 'current_screen', [ $this, 'check_current_screen' ] );

		}
	}

	function check_current_screen( $current_screen ) {

		if ( in_array($current_screen->id, $this->allowed_screens ) ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		}
	}

	public static function get_prefix() {
		return self::$prefix;
	}

	public function get_allowed_types() {
		return apply_filters( 'wpz_video_background_on_hover_allowed_types', $this->allowed_types );
	}

	public function is_type_self_hosted() {
		return $this->is_type( 'self_hosted' );
	}

	public function is_type_giphy() {
		return $this->is_type( 'giphy' );
	}

	public function is_type( $type ) {
		$cast = (array) self::$type;

		return in_array( $type, $cast );
	}


	public function save_metadata( $post_id ) {

		// Ignore autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Ignore revisions and autosaves.
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return $post_id;
		}

		$post = get_post( $post_id );

		// Ignore unexisting post.
		if ( ! $post ) {
			return $post_id;
		}

		// Check user permission.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( isset( $_POST[ self::get_prefix() . 'background_type' ] ) ) {
			update_custom_meta( $post_id, $_POST[ self::get_prefix() . 'background_type' ], self::get_prefix() . 'background_type' );
		}

	}

	public function render_background_wrapper( $post_id ) {

		if ( $this->is_type_self_hosted() && $this->is_type_giphy() ) {
			$this->render_radio_switcher( $post_id );
		}

		if ( $this->is_type_self_hosted() ) {
			$this->self_hosted->render( $post_id );
		}

		if ( $this->is_type_giphy() ) {
			$this->giphy->render( $post_id );
		}
	}

	public function render_radio_switcher( $post_id ) {
		$postmeta_background_videotype = get_post_meta( $post_id, 'wpzoom_portfolio_background_type', true );
		$post_meta_background          = empty( $postmeta_background_videotype ) ? 'self_hosted' : $postmeta_background_videotype;
		?>
		<div class="radio-switcher wpz-giphy-controller">

            <p class="description"><?php _e( 'Here you can add a short video that will play automatically when hovering current Portfolio post in the Portfolio page or Portfolio widgets on the homepage.', 'wpzoom' ); ?></p>


            <h3><?php _e('Select Video Source:', 'wpzoom'); ?></h3>


		  <input type="radio" name="<?php echo $this->get_prefix(); ?>background_type" id="video_sf_wpz" value="self_hosted" <?php checked( $post_meta_background, 'self_hosted' ); ?>>
                <label class="label_vid_self" for="video_sf_wpz"><?php _e( 'Self-Hosted Video', 'wpzoom' ) ?>

			</label>

			 &nbsp;&nbsp;&nbsp;<input type="radio" name="<?php echo $this->get_prefix(); ?>background_type" id="video_yt_wpz" value="giphy" <?php checked( $post_meta_background, 'giphy' ); ?>>
                <label class="label_vid_url" for="video_yt_wpz"><strong class="wpz_video_embed_icons"><i class="fa fa-youtube-play"></i><?php _e( 'YouTube', 'wpzoom' ) ?> <span class="wpz_embed_sep">/</span> <i class="fa fa-vimeo"></i><?php _e( 'Vimeo', 'wpzoom' ) ?></strong> <sup>NEW</sup></label>

			</label>

		</div>

		<?php
	}

	public function enqueue_scripts() {
		global $post;

		wp_enqueue_style( 'giphy-background', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/css/style.css' );
		wp_enqueue_style( 'giphy-background-range-slider', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/css/ion.rangeSlider.min.css' );
		wp_enqueue_script( 'giphy-background-moment-js', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/js/moment.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'giphy-background-moment-duration-js', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/js/moment-duration-format.js', array(
			'jquery',
			'giphy-background-moment-js'
		), '1.0.0', true );
		wp_enqueue_script( 'giphy-background-progressbar', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/js/progressbar.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'giphy-background-range-slider', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/js/ion.rangeSlider.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'giphy-background', WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/js/general.js', array(
			'jquery',
			'underscore',
			'wp-util'
		) );

		$localized_data = [
			'nonce-get-track-duration'      => wp_create_nonce( 'get_track_duration' ),
			'nonce-get-trimmed-url'         => wp_create_nonce( 'get_trimmed_url' ),
			'nonce-upload-to-giphy'         => wp_create_nonce( 'upload_to_giphy' ),
			'nonce-get-giphy-data-by-id'    => wp_create_nonce( 'get_giphy_data_by_id' ),
			'nonce-upload-to-media-library' => wp_create_nonce( 'upload_to_media_library' ),
			'nonce-get-thumbnail'           => wp_create_nonce( 'get_thumbnail' ),
			'nonce-upload-thumbnail'        => wp_create_nonce( 'upload_thumbnail' ),
			'nonce-set-featured-image'      => wp_create_nonce( 'set_post_thumbnail-' . $post->ID ),
			'insertBtnLabel'                => __( 'Use this video', 'wpzoom' ),
			'insertTxtNode'                 => __( ' video detected. Use it here?', 'wpzoom' ),
			'set-featured-button-active'    => __( 'Use This as the Featured Image', 'wpzoom' ),
			'set-featured-button-disabled'  => __( 'This is the Featured Image', 'wpzoom' ),
			'generate-video-label'          => __( 'Insert Video', 'wpzoom' ),
			'generate-start-time-label'     => __( 'Start Time', 'wpzoom' ),
			'generate-duration-label'       => __( 'Duration', 'wpzoom' ),
			'error-messages'                => [
				'invalid-url'       => __( 'Invalid video URL', 'wpzoom' ),
				'time-limit'        => __( 'Videos must be 15 minutes or less.', 'wpzoom' ),
				'restricted-access' => __( 'This video is not supported. If you\'re the owner of the video, please make it available for Download (Vimeo only).', 'wpzoom' ),
				'request-fail'      => __( 'Incorrect URL or this Vimeo video is not available for Download.', 'wpzoom' )
			],
			'video-poster'                  => WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/img/video.poster.jpg'
		];

		if ( $this->is_type_giphy() ) {
			$localized_data['global-track-duration'] = $this->giphy->get_global_duration();
		}

		wp_localize_script( 'giphy-background', 'giphy_embed_option_type', $localized_data );
	}

	public function get_data( $post_id ) {

		$url                          = '';
		$video_background_select_type = get_post_meta( $post_id, self::get_prefix() . 'background_type', true );

		if ( empty( $video_background_select_type ) ) {
			$video_background_select_type = 'self_hosted';
		}

		if ( $this->is_type( $video_background_select_type ) ) {
			$url = $this->{$video_background_select_type}->get_data( $post_id );
		}

		return $url;


	}

}