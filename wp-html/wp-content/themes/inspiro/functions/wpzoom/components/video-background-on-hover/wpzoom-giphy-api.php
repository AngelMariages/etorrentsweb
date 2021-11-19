<?php

class WPZOOM_GIPHY_API {

	protected $api_key = '';

	public function __construct( $api_key ) {

		$this->api_key = trim( $api_key );

	}

	public function upload_to_giphy( $url ) {
		$upload_uri = 'https://upload.giphy.com/v1/gifs';

		$post_response = wp_remote_post( $upload_uri, array(
			'timeout' => 120,
			'body'    => array(
				'api_key'          => $this->api_key,
				'source_image_url' => esc_url_raw( $url )
			)
		) );

		if ( is_wp_error( $post_response ) || 200 != wp_remote_retrieve_response_code( $post_response ) ) {

			return false;
		}

		return json_decode( wp_remote_retrieve_body( $post_response ), true );
	}

	public function upload_to_media_library( $url, $post_id ) {


		if ( empty( $url ) || empty( $post_id ) ) {
			return false;
		}


		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$is_uploaded = $this->media_sideload_image( $url, $post_id, null, 'id' );

		if ( is_wp_error( $is_uploaded ) ) {

			return false;
		}

		return $is_uploaded;

	}

	/**
	 * Clone function that added support to mp4.
	 *
	 * @param $file
	 * @param $post_id
	 * @param null $desc
	 * @param string $return
	 *
	 * @return false|int|mixed|object|string|WP_Error
	 */
	function media_sideload_image( $file, $post_id, $desc = null, $return = 'html' ) {
		if ( ! empty( $file ) ) {

			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png|mp4)\b/i', $file, $matches );
			if ( ! $matches ) {
				return new WP_Error( 'image_sideload_failed', __( 'Invalid image URL' ) );
			}

			$file_array         = array();
			$uuid               = wp_generate_uuid4();
			$file_array['name'] = 'wpz-' . $uuid . '-' . basename( $matches[0] );

			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, $post_id, $desc );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );

				return $id;
				// If attachment id was requested, return it early.
			} elseif ( $return === 'id' ) {
				return $id;
			}

			$src = wp_get_attachment_url( $id );
		}

		// Finally, check to make sure the file has been saved, then return the HTML.
		if ( ! empty( $src ) ) {
			if ( $return === 'src' ) {
				return $src;
			}

			$alt  = isset( $desc ) ? esc_attr( $desc ) : '';
			$html = "<img src='$src' alt='$alt' />";

			return $html;
		} else {
			return new WP_Error( 'image_sideload_failed' );
		}
	}

	public function get_giphy_data_by_id( $id ) {
		$api_uri     = 'https://api.giphy.com/v1/gifs';
		$api_request = add_query_arg( array(
			'api_key' => $this->api_key,
			'ids'     => $id
		), $api_uri );

		$get_response = wp_remote_get( $api_request );

		if ( is_wp_error( $get_response ) || 200 != wp_remote_retrieve_response_code( $get_response ) ) {
			return false;
		}

		$get_response = json_decode( wp_remote_retrieve_body( $get_response ), true );

		return $get_response;
	}

	public function insert_gif_in_media_library() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'insert_gif_in_media_library' ) ) {
			wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
		}

		$url     = $_POST['url'];
		$post_id = $_POST['post_id'];

		$media_file_id = $this->upload( $url, $post_id );

		if ( ! $media_file_id ) {
			wp_send_json_error( array( 'message' => 'Error on upload' ) );
		}

		update_post_meta( $post_id, 'wpz_giphy_data', $media_file_id );

		wp_send_json_success( array( 'message' => 'Uploaded done', 'id' => $media_file_id ) );

	}

	public function get_track_duration( $args ) {
		$sliced = wp_array_slice_assoc( $args, array( 'url' ) );

		if ( empty( $sliced['url'] ) ) {
			return false;
		}

		$get_url = add_query_arg( array(
			'url' => urlencode( $sliced['url'] ),
		), 'https://imaging.giphy.com/fetch/video/info' );

		$response = wp_remote_get( $get_url, array( 'timeout' => 120, 'httpversion' => '1.1' ) );


		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {

			return false;
		}

		$data   = [
			'duration' => 0,
			'public'   => true
		];

		$response = wp_remote_retrieve_body( $response );
		$response = json_decode( $response, true );

		if ( !empty( $response['duration'] ) ) {
			$data['duration']= $response['duration'];
		}

		if ( isset( $response['privacy']['download'] ) ) {
			$data['public'] = $response['privacy']['download'];
		}

		return $data;
	}

	public function get_trimmed_url( $args, $loop = 4 ) {

		$sliced = wp_array_slice_assoc( $args, array( 'url', 'start', 'duration' ) );
		$sliced = wp_parse_args( $sliced, array( 'start' => 0, 'duration' => 5 ) );

		if ( empty( $sliced['url'] ) ) {
			return false;
		}


		$get_url = add_query_arg( array(
			'media_url' => urlencode( $sliced['url'] ),
			'start'     => $sliced['start'],
			'duration'  => $sliced['duration'],
		), 'https://imaging.giphy.com/imaging/trim' );

		$response = wp_remote_get( $get_url, array( 'timeout' => 120, 'httpversion' => '1.1' ) );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {

			if ( $loop > 0 ) {
				return $this->get_trimmed_url( $args, $loop - 1 );
			}

			return false;
		}

		return $get_url;

	}



}
