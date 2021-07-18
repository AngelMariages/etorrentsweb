<?php

require_once dirname( __FILE__ ) . '/wpzoom-giphy-api.php';
require_once dirname( __FILE__ ) . '/wpzoom-oembed-thumbnail-api.php';

class WPZOOM_Giphy_Controller {

	public $giphy_instance = null;

	public $prefix = 'wpz';

	public $api_key = 'fa7SRYGtkRp7qmMrLVqKw9GJXgqhkvm5';

	public function __construct( $args ) {

		$filtered_args = wp_array_slice_assoc( $args, array( 'prefix', 'type' ) );
		$this->prefix  = $filtered_args['prefix'];

		if ( is_admin() ) {
			$this->register_ajax_callbacks();

			$this->giphy_instance = new WPZOOM_GIPHY_API( $this->get_api_key() );

			add_action( 'save_post', array( $this, 'save_metadata' ) );
			add_filter( 'zoom_options', array( $this, 'theme_options' ) );

		}
	}

	public function validate_key( $key ) {
		return ( ! empty( $key ) && 32 === strlen( $key ) );
	}

	public function get_api_key() {
		$user_api_key = trim( get_option( 'wpzoom_giphy_user_api_key' ) );

		return $this->validate_key( $user_api_key ) ? $user_api_key : $this->api_key;
	}

	public function theme_options( $data ) {

		$data['framework'][] = array( "type" => "preheader", "name" => __( "GIPHY Settings", 'wpzoom' ) );

		$data['framework'][] = array(
			"name" => "Default Duration",
			"desc" => __( "Set default video on hover duration range between<strong>  1 and 15 </strong>seconds.", 'wpzoom' ),
			"id"   => "giphy_default_duration",
			"std"  => 5,
			"type" => "text"
		);

		$data['framework'][] = array(
			"name" => "GIPHY API key",
			"desc" => sprintf( __( "To request A GIPHY API Key click on <strong><a href='%s'>this link</a></strong>.", 'wpzoom' ), 'https://support.giphy.com/hc/en-us/articles/360020283431-Request-A-GIPHY-API-Key' ),
			"id"   => "giphy_user_api_key",
			"std"  => '',
			"type" => "text"
		);

		return $data;
	}

	public function get_global_duration() {
		return get_option( 'wpzoom_giphy_default_duration', 5 );
	}

	public function get_prefix() {
		return $this->prefix;
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

		if ( isset( $_POST[ $this->get_prefix() . 'video_background_giphy_url' ] ) ) {
			update_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_url', esc_url_raw( $_POST[ $this->get_prefix() . 'video_background_giphy_url' ] ) );
		}

		if ( isset( $_POST[ $this->get_prefix() . 'video_background_giphy_error_type' ] ) ) {
			update_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_error_type', esc_attr( $_POST[ $this->get_prefix() . 'video_background_giphy_error_type' ] ) );
		}

		if ( isset( $_POST[ $this->get_prefix() . 'video_background_giphy_file_id' ] ) ) {
			update_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_file_id', intval( $_POST[ $this->get_prefix() . 'video_background_giphy_file_id' ] ) );

			$attached_media = get_attached_media( 'video', $post_id );
			$media_file_id  = intval( $_POST[ $this->get_prefix() . 'video_background_giphy_file_id' ] );

			if ( ! empty( $attached_media ) && ! empty( $media_file_id ) ) {
				foreach ( $attached_media as $media ) {

					if ( $media->ID === $media_file_id ) {
						continue;
					}

					$attached_file             = get_attached_file( $media->ID );
					$basename_of_attached_file = basename( $attached_file );

					if ( strpos( $basename_of_attached_file, 'wpz-' ) !== false ) {
						wp_delete_attachment( $media->ID, true );
					}
				}
			}
		}

	}


	public function render( $post_id ) {
		$poster           = WPZOOM::$wpzoomPath . '/components/video-background-on-hover/assets/img/video.poster.jpg';
		$giphy_url        = get_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_url', true );
		$giphy_file_id    = get_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_file_id', true );
		$giphy_error_type = get_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_error_type', true );
		$video_src     = '';
		if ( ! empty( $giphy_url ) && ! empty( $giphy_file_id ) ) {
			$video_src = wp_get_attachment_url( get_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_file_id', true ) );
		}
		?>
		<div class="wpzoom_giphy switch-wrapper wp-clearfix">

            <p class="description"><?php _e( 'This feature is powered by <strong>GIPHY</strong> and works only with YouTube videos shorter than <strong>15 minutes</strong> and Vimeo videos which are available for Download. <a href="https://www.wpzoom.com/docs/adding-background-videos-on-hover/" target="_blank">View more details</a>', 'wpzoom' ); ?>
            <p class="description"><?php _e( 'This is an experimental feature and some videos may take up to 30 seconds to be processed.', 'wpzoom' ); ?>

			<div class="wrapper_with_progress">

                <p>
    				<label>

                        <strong><?php _e( 'Insert Video URL', 'wpzoom' ); ?></strong> <em><?php _e( '(YouTube and Vimeo only)', 'wpzoom' ); ?></em>

    						<span class="preview-video-input-span">
    							<input type="hidden"
    							       name="<?php echo $this->get_prefix(); ?>video_background_giphy_file_id"
    							       id="<?php echo $this->get_prefix(); ?>video_background_giphy_file_id"
    							       class="wpzoom_video_background_giphy_file_id"
    							       value="<?php echo esc_attr( $giphy_file_id ); ?>"
    							/>
							    <input type="hidden"
							           name="<?php echo $this->get_prefix(); ?>video_background_giphy_error_type"
							           id="<?php echo $this->get_prefix(); ?>video_background_giphy_error_type"
							           class="wpzoom_video_background_giphy_error_type"
							           value="<?php echo esc_attr( $giphy_error_type ); ?>"
							    />
    						<input type="text" name="<?php echo $this->get_prefix(); ?>video_background_giphy_url"
    						       id="<?php echo $this->get_prefix(); ?>video_background_giphy_url"
    						       class="widefat wpzoom_video_background_giphy_url"
    						       value="<?php echo esc_attr( $giphy_url ); ?>"/>
    						<img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16"
    						     alt=""
    						     class="wpzoom-preloader hidden"/>
    							<span class="wpzoom-giphy-icon dashicons dashicons-yes"></span>
    							<span class="wpzoom-giphy-icon dashicons dashicons-warning"></span>
    							<span class="wpzoom-giphy-reload-icon dashicons dashicons-update-alt"></span>
    							</span>
    				</label>
    			</p>
    			<div class="wpzoom-giphy-progressbar"></div>

            </div>

			<div class="track-controls"></div>
			<div class='wpzoom-attachment-wrapper'>

                <?php /* <h3><?php _e('Current video:', 'wpzoom'); ?></h3> */ ?>

				<video width="400"
				       height="260"
				       loop
				       autoplay
				       muted
				       src="<?php echo esc_url( $video_src ) ?>"
				       poster="<?php echo esc_attr( $poster ) ?>"
				></video>
			</div>
			<div class="thumbnails-wrapper"></div>


            <div class="giphy_attr"></div>

		</div>
		<?php
	}

	public function register_ajax_callbacks() {

		add_action( 'wp_ajax_get_track_duration', array( $this, 'get_track_duration' ) );
		add_action( 'wp_ajax_get_trimmed_url', array( $this, 'get_trimmed_url' ) );
		add_action( 'wp_ajax_upload_to_giphy', array( $this, 'upload_to_giphy' ) );
		add_action( 'wp_ajax_get_giphy_data_by_id', array( $this, 'get_giphy_data_by_id' ) );
		add_action( 'wp_ajax_upload_to_media_library', array( $this, 'upload_to_media_library' ) );
		add_action( 'wp_ajax_get_thumbnail', array( $this, 'get_thumbnail' ) );
		add_action( 'wp_ajax_upload_thumbnail', array( $this, 'upload_thumbnail' ) );

	}

	public function get_track_duration() {

		$fail = false;

		if ( ! wp_verify_nonce( $_POST['nonce'], 'get_track_duration' ) ) {
			$fail = true;
			wp_send_json_error( array( 'message' => 'Invalid nonce', 'fail' => $fail ) );
		}

		$url        = sanitize_text_field( $_POST['url'] );
		$track_info = $this->giphy_instance->get_track_duration( array( 'url' => $url ) );

		$thumbnail = (array) WPZOOM_Oembed_Thumbnail_API::get_thumbnail( $url );

		$duration                   = empty( $track_info['duration'] ) ? 0 : $track_info['duration'];
		$is_public                  = empty( $track_info['public'] ) ? false : $track_info['public'];
		$fifteen_minutes_in_seconds = 15 * 60;

		$msg = __( 'Track info get with success.', 'wpzoom' );
		$error_type =false;

		if ( $duration > $fifteen_minutes_in_seconds ) {
			$msg  = __( 'Videos must be 15 minutes or less.', 'wpzoom' );
			$error_type = 'time-limit';
			$fail = true;
		}

		if ( false == $is_public ) {
			$msg  = __( 'This video is not supported. If you\'re the owner of the video, please make it available for Download (Vimeo only).', 'wpzoom' );
			$error_type = 'restricted-access';
			$fail = true;

		}

		wp_send_json_success( [
			'fail'    => $fail,
			'message' => $msg,
			'error_type'=>$error_type,
			'data'    => [ 'duration' => $duration, 'url' => $url, 'thumbnail' => $thumbnail, 'public' => $is_public ]
		] );
	}

	public function get_trimmed_url() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'get_trimmed_url' ) ) {
			wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
		}

		$url      = sanitize_text_field( $_POST['url'] );
		$start    = sanitize_text_field( $_POST['start'] );
		$duration = sanitize_text_field( $_POST['duration'] );
		$response = $this->giphy_instance->get_trimmed_url( array(
			'url'      => $url,
			'start'    => $start,
			'duration' => $duration
		) );

		if ( $response ) {
			$url = $response;
		}

		wp_send_json_success( array( 'message' => 'Trimmed url get with success', 'data' => array( 'url' => $url ) ) );

	}

	public function upload_to_giphy() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'upload_to_giphy' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'wpzoom' ) ) );
		}

		$url      = $_POST['url'];
		$response = $this->giphy_instance->upload_to_giphy( $url );

		if ( empty( $response ) && empty( $response['data']['id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Upload to giphy failed.', 'wpzoom' ) ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Trimmed url get with success', 'wpzoom' ),
			'data'    => array( 'giphy_id' => $response['data']['id'] )
		) );

	}

	public function get_giphy_data_by_id() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'get_giphy_data_by_id' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'wpzoom' ) ) );
		}

		$giphy_id = sanitize_text_field( $_POST['giphy_id'] );
		$response = $this->giphy_instance->get_giphy_data_by_id( $giphy_id );

		if ( empty( $response ) && empty( $response['data'][0]['images']['original_mp4']['mp4'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Giphy can\'t respond.', 'wpzoom' ) ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Trimmed url get with success', 'wpzoom' ),
			'data'    => array( 'url' => $response['data'][0]['images']['original_mp4']['mp4'] )
		) );

	}

	public function get_thumbnail() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'get_thumbnail' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'wpzoom' ) ) );
		}

		$url       = esc_url_raw( $_POST['url'] );
		$thumbnail = WPZOOM_Oembed_Thumbnail_API::get_thumbnail( $url );

		if ( empty( $thumbnail ) ) {
			wp_send_json_error( array( 'message' => __( 'Can not fetch thumbnail from the url', 'wpzoom' ) ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Return thumbnail', 'wpzoom' ),
			'data'    => array( 'thumbnail' => $thumbnail['url'], 'title' => $thumbnail['title'] )
		) );
	}

	public function upload_thumbnail() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'upload_thumbnail' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'wpzoom' ) ) );
		}

		$url     = esc_url_raw( $_POST['url'] );
		$post_id = (int) $_POST['post_id'];

		if ( empty( $url ) || empty( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Empty url or post_id', 'wpzoom' ) ) );
		}

		$attachment_id = media_sideload_image( $url, $post_id, null, 'id' );

		if ( empty( $attachment_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Failed to upload image', 'wpzoom' ) ) );
		}

		update_post_meta( $post_id, 'giphy_featured_thumbnail_id', $attachment_id );

		wp_send_json_success( array(
			'message' => __( 'Upload thumbnail', 'wpzoom' ),
			'data'    => array( 'attachment_id' => $attachment_id )
		) );

	}

	public function is_featured_image( $post_id ) {
		$featured_image = get_post_thumbnail_id( $post_id );
		$uploaded_image = get_post_meta( $post_id, 'giphy_featured_thumbnail_id', true );

		if ( empty( $featured_image ) || empty( $uploaded_image ) ) {
			return false;
		}

		return $featured_image == $uploaded_image;
	}

	public function upload_to_media_library() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'upload_to_media_library' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'wpzoom' ) ) );
		}

		$url           = esc_url_raw( $_POST['url'] );
		$post_id       = (int) $_POST['post_id'];
		$media_file_id = $this->giphy_instance->upload_to_media_library( $url, $post_id );


		if ( empty( $media_file_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Upload to media library failed.', 'wpzoom' ) ) );
		}

		wp_send_json_success(
			array(
				'message' => __( 'Upload to media library with success', 'wpzoom' ),
				'data'    => array(
					'media_file_id'  => $media_file_id,
					'attachment_url' => wp_get_attachment_url( $media_file_id ),
					'attached_media' => get_attached_file( $media_file_id ),
					'attached_files' => get_attached_media( 'video', $post_id )
				)
			)
		);

	}

	public function get_data( $post_id ) {

		$url                            = '';
		$video_background_giphy         = get_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_url', true );
		$video_background_giphy_data_id = get_post_meta( $post_id, $this->get_prefix() . 'video_background_giphy_file_id', true );

		if ( ! empty( $video_background_giphy ) &&
		     ! empty( $video_background_giphy_data_id )
		) {
			$url = wp_get_attachment_url( $video_background_giphy_data_id );
		}

		return $url;
	}
}