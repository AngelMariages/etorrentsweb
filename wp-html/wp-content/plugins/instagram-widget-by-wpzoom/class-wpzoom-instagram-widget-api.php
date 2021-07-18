<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpzoom_Instagram_Widget_API {
	/**
	 * @var Wpzoom_Instagram_Widget_API The reference to *Singleton* instance of this class
	 */
	private static $instance;
	/**
	 * Request headers.
	 *
	 * @var array
	 */
	public $headers = [];
	/**
	 * Errors collector.
	 *
	 * @var array|WP_Error
	 */
	public $errors = [];
	/**
	 * Instagram Access Token
	 *
	 * @var string
	 */
	protected $access_token;

	protected function __construct() {
		$options = get_option( 'wpzoom-instagram-widget-settings', wpzoom_instagram_get_default_settings() );

		$this->request_type = ! empty( $options['request-type'] ) ? $options['request-type'] : '';
		$this->access_token = ! empty( $options['basic-access-token'] ) ? $options['basic-access-token'] : '';

		$this->username                 = ! empty( $options['username'] ) ? $options['username'] : '';
		$this->transient_lifetime_type  = ! empty( $options['transient-lifetime-type'] ) ? $options['transient-lifetime-type'] : 'days';
		$this->transient_lifetime_value = ! empty( $options['transient-lifetime-value'] ) ? $options['transient-lifetime-value'] : 1;
		$this->is_forced_timeout        = ! empty( $options['is-forced-timeout'] ) ? wp_validate_boolean( $options['is-forced-timeout'] ) : false;
		$this->request_timeout_value    = ! empty( $options['request-timeout-value'] ) ? $options['request-timeout-value'] : 15;

		if ( $this->is_forced_timeout && ! empty( $this->request_timeout_value ) ) {
			$this->headers['timeout'] = $this->request_timeout_value;
		}

		$this->image_uploader = WPZOOM_Instagram_Image_Uploader::getInstance();

		$this->errors = new WP_Error();

	}

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Wpzoom_Instagram_Widget_API The *Singleton* instance.
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function reset_cache() {
		delete_transient( 'zoom_instagram_is_configured' );
		delete_transient( 'zoom_instagram_user_info' );
	}

	/**
	 * @param $screen_name string Instagram username
	 * @param $image_limit int    Number of images to retrieve
	 * @param $image_width int    Desired image width to retrieve
	 *
	 * @return array|bool Array of tweets or false if method fails
	 */
	public function get_items( $instance ) {

		$sliced = wp_array_slice_assoc( $instance, array(
			'image-limit',
			'image-width',
			'image-resolution',
			'username',
			'disable-video-thumbs'
		) );

		$image_limit          = $sliced['image-limit'];
		$image_width          = $sliced['image-width'];
		$image_resolution     = ! empty( $sliced['image-resolution'] ) ? $sliced['image-resolution'] : 'default_algorithm';
		$injected_username    = ! empty( $sliced['username'] ) ? $sliced['username'] : '';
		$disable_video_thumbs = ! empty( $sliced['disable-video-thumbs'] );

		$transient = 'zoom_instagram_is_configured';

		$injected_username = trim( $injected_username );

		if ( ! empty( $injected_username ) && 'without-access-token' === $this->request_type ) {
			$injected_username = str_replace( '@', '', $injected_username );
			$transient         = $transient . '_' . $injected_username;
		}

		if ( false !== ( $data = json_decode( get_transient( $transient ) ) ) && is_object( $data ) && ! empty( $data->data ) ) {

			return $this->processing_response_data( $data, $image_width, $image_resolution, $image_limit, $disable_video_thumbs );
		}

		$is_external_username = ! empty( $this->username ) || ! empty( $injected_username );
		$external_username    = ! empty( $injected_username ) ? $injected_username : $this->username;


		if ( ! empty( $this->access_token ) ) {

			$request_url = add_query_arg( [
				'fields'       => 'media_url,media_type,caption,username,permalink,thumbnail_url',
				'access_token' => $this->access_token
			],
				'https://graph.instagram.com/me/media' );

			$response = wp_remote_get( $request_url, $this->headers );

			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $transient, wp_json_encode( false ), MINUTE_IN_SECONDS );

				$error_data = $this->get_error( 'items-with-token-invalid-response' );
				$this->errors->add( $error_data['code'], $error_data['message'] );

				return false;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ) );

			$data = $this->convert_items_to_old_structure( $data );
		}

		if ( 'without-access-token' === $this->request_type && ! empty( $is_external_username ) ) {


			$data = $this->get_items_without_token( $external_username );

			if ( is_wp_error( $data ) ) {
				set_transient( $transient, wp_json_encode( false ), MINUTE_IN_SECONDS );

				return false;
			}

		}

		if ( ! empty( $data->data ) ) {
			set_transient( $transient, wp_json_encode( $data ), $this->get_transient_lifetime() );
		} else {
			set_transient( $transient, wp_json_encode( false ), MINUTE_IN_SECONDS );

			$error_data = $this->get_error( 'items-with-token-invalid-data-structure' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return false;
		}

		return $this->processing_response_data( $data, $image_width, $image_resolution, $image_limit, $disable_video_thumbs );
	}

	public function processing_response_data( $data, $image_width, $image_resolution = 'default_algorithm', $image_limit, $disable_video_thumbs = false ) {

		$result   = array();
		$username = '';

		foreach ( $data->data as $key => $item ) {

			if ( empty( $username ) ) {
				$username = $item->user->username;
			}

			if ( $key === $image_limit ) {
				break;
			}

			if ( ! empty( $disable_video_thumbs ) && isset( $item->type ) && 'VIDEO' == $item->type ) {
				$image_limit ++;
				continue;

			}

			$best_size = $this->get_best_size( $image_width, $image_resolution );
			$image_url = $item->images->{$best_size}->url;

			$result[] = array(
				'link'               => $item->link,
				'image-url'          => $image_url,
				'original-image-url' => $item->media_url,
				'type'               => $item->type,
				'image-id'           => ! empty( $item->id ) ? esc_attr( $item->id ) : '',
				'image-caption'      => ! empty( $item->caption->text ) ? esc_attr( $item->caption->text ) : '',
				'likes_count'        => ! empty( $item->likes->count ) ? esc_attr( $item->likes->count ) : 0,
				'comments_count'     => ! empty( $item->comments->count ) ? esc_attr( $item->comments->count ) : 0
			);
		}

		$result = array( 'items' => $result, 'username' => $username );

		return $result;
	}

	/**
	 * @param $desired_width int Desired image width in pixels
	 *
	 * @return string Image size for Instagram API
	 */
	protected function get_best_size( $desired_width, $image_resolution = 'default_algorithm' ) {
		$size  = 'thumbnail';
		$sizes = array(
			'thumbnail'           => 150,
			'low_resolution'      => 306,
			'standard_resolution' => 640
		);

		$diff = PHP_INT_MAX;

		if ( array_key_exists( $image_resolution, $sizes ) ) {
			return $image_resolution;
		}

		foreach ( $sizes as $key => $value ) {
			if ( abs( $desired_width - $value ) < $diff ) {
				$size = $key;
				$diff = abs( $desired_width - $value );
			}
		}

		return $size;
	}

	/**
	 * Retrieve error message by key.
	 *
	 * @param $key
	 *
	 * @return bool|mixed
	 */
	public function get_error( $key ) {
		$errors = $this->get_errors();

		return array_key_exists( $key, $errors ) ? $errors[ $key ] : false;
	}

	/**
	 * Get error messages collection.
	 *
	 * @return array
	 */
	public function get_errors() {
		return [
			'user-info-without-token'                                   => [
				'code'    => 'user-info-without-token',
				'message' => __( 'Empty json user info from Public Feed.', 'wpzoom-instagram-widget' )
			],
			'response-data-without-token-from-json-invalid-response'    => [
				'code'    => 'response-data-without-token-from-json-invalid-response',
				'message' => __( 'The request from the Public Feed failed. Invalid server response from Public JSON API url.', 'wpzoom-instagram-widget' )
			],
			'response-data-without-token-from-json-invalid-json-format' => [
				'code'    => 'response-data-without-token-from-json-invalid-json-format',
				'message' => __( 'The request from the Public Feed failed. Invalid JSON format from Public JSON API url.', 'wpzoom-instagram-widget' )
			],
			'response-data-without-token-from-html-invalid-response'    => [
				'code'    => 'response-data-without-token-from-html-invalid-response',
				'message' => __( 'The request from the Public Feed failed. Check username.', 'wpzoom-instagram-widget' )
			],
			'response-data-without-token-from-html-invalid-json-format' => [
				'code'    => 'response-data-without-token-from-html-invalid-json-format',
				'message' => __( 'The request from the Public Feed failed. Invalid JSON format from parsed html body.', 'wpzoom-instagram-widget' )
			],
			'items-without-token-invalid-response'                      => [
				'code'    => 'items-without-token-invalid-response',
				'message' => __( 'Get items from the Public Feed failed. Invalid response.', 'wpzoom-instagram-widget' )
			],
			'items-without-token-invalid-json-structure'                => [
				'code'    => 'items-without-token-invalid-json-structure',
				'message' => __( 'Get items from the Public Feed failed. Malformed data structure.', 'wpzoom-instagram-widget' )
			],
			'items-with-token-invalid-response'                         => [
				'code'    => 'items-with-token-invalid-response',
				'message' => __( 'Geting items from the Instagram API Feed failed. Invalid response.', 'wpzoom-instagram-widget' )
			],
			'items-with-token-invalid-data-structure'                   => [
				'code'    => 'items-with-token-invalid-data-structure',
				'message' => __( 'Get items from the Instagram API Feed failed. Malformed data structure.', 'wpzoom-instagram-widget' )
			],
			'user-with-token-invalid-response'                          => [
				'code'    => 'user-with-token-invalid-response',
				'message' => __( 'Get user data from the Instagram API Feed failed. Invalid response.', 'wpzoom-instagram-widget' )
			],
			'user-with-token-invalid-data-structure'                    => [
				'code'    => 'user-with-token-invalid-data-structure',
				'message' => __( 'Get user data from the Instagram API Feed failed. Malformed data structure.', 'wpzoom-instagram-widget' )
			],

		];
	}

	function convert_items_to_old_structure( $data ) {
		$converted       = new stdClass;
		$converted->data = array();

		foreach ( $data->data as $key => $item ) {

			$converted->data[] = (object) array(
				'id'           => $item->id,
				'media_url'    => ( 'VIDEO' === $item->media_type ) ? $item->thumbnail_url : $item->media_url,
				'user'         => (object) array(
					'id'              => null,
					'fullname'        => null,
					'profile_picture' => null,
					'username'        => $item->username
				),
				'images'       => (object) array(
					'thumbnail'           => (object) array(
						'url'    => $this->image_uploader->get_image( 'thumbnail', $item->media_url, $item->id ),
						'width'  => 150,
						'height' => 150
					),
					'low_resolution'      => (object) array(
						'url'    => $this->image_uploader->get_image( 'low_resolution', $item->media_url, $item->id ),
						'width'  => 320,
						'height' => 320
					),
					'standard_resolution' => (object) array(
						'url'    => $this->image_uploader->get_image( 'standard_resolution', $item->media_url, $item->id ),
						'width'  => 640,
						'height' => 640
					),
				),
				'type'         => $item->media_type,
				'likes'        => null,
				'comments'     => null,
				'created_time' => null,
				'link'         => $item->permalink,
				'caption'      => (object) array(
					'text' => isset( $item->caption ) ? $item->caption : ''
				)
			);

		}

		return $converted;
	}

	function get_items_without_token( $user ) {

		$result = $this->get_response_without_token( $user );

		if ( is_wp_error( $result ) ) {
			$error_data = $this->get_error( 'items-without-token-invalid-response' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return new WP_Error( $error_data['code'], $error_data['message'] );
		}

		if ( isset( $result->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges ) ) {
			$edges = $result->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges;
		} elseif ( isset( $result->graphql->user->edge_owner_to_timeline_media->edges ) ) {
			$edges = $result->graphql->user->edge_owner_to_timeline_media->edges;
		} else {
			$error_data = $this->get_error( 'items-without-token-invalid-json-structure' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return new WP_Error( $error_data['code'], $error_data['message'] );
		}

		$converted       = new stdClass;
		$converted->data = array();
		foreach ( $edges as $edge ) {

			$node = $edge->node;

			$converted->data[] = (object) array(
				'user'         => (object) array(
					'id'              => $node->owner->id,
					'fullname'        => '',
					'profile_picture' => '',
					'username'        => $node->owner->username
				),
				'images'       => (object) array(
					'thumbnail'           => (object) array(
						'url'    => $node->thumbnail_resources[0]->src,
						'width'  => $node->thumbnail_resources[0]->config_width,
						'height' => $node->thumbnail_resources[0]->config_height
					),
					'low_resolution'      => (object) array(
						'url'    => $node->thumbnail_resources[2]->src,
						'width'  => $node->thumbnail_resources[2]->config_width,
						'height' => $node->thumbnail_resources[2]->config_height
					),
					'standard_resolution' => (object) array(
						'url'    => $node->thumbnail_resources[4]->src,
						'width'  => $node->thumbnail_resources[4]->config_width,
						'height' => $node->thumbnail_resources[4]->config_height
					),
				),
				'type'         => $this->get_media_type_without_token( $node->__typename ),
				'likes'        => isset( $node->edge_liked_by ) ? $node->edge_liked_by : 0,
				'comments'     => isset( $node->edge_media_to_comment ) ? $node->edge_media_to_comment : 0,
				'created_time' => $node->taken_at_timestamp,
				'link'         => sprintf( 'https://www.instagram.com/p/%s/', $node->shortcode ),
				'caption'      => (object) array(
					'text' => isset( $node->edge_media_to_caption->edges[0]->node->text ) ? $node->edge_media_to_caption->edges[0]->node->text : ''
				)
			);
		}

		return $converted;
	}

	function get_response_without_token( $user ) {

		$user = trim( $user );
		$url  = 'https://instagram.com/' . str_replace( '@', '', $user );

		$request = wp_remote_get( $url, $this->headers );

		if ( is_wp_error( $request ) || 200 != wp_remote_retrieve_response_code( $request ) ) {

			$error_data = $this->get_error( 'response-data-without-token-from-html-invalid-response' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			$result = $this->get_response_without_token_from_json( $user );

			if ( is_wp_error( $result ) ) {
				return new WP_Error( 'invalid_response', __( 'Invalid response from Instagram', 'wpzoom-instagram-widget' ) );
			} else {
				return $result;
			}
		}

		$body = wp_remote_retrieve_body( $request );

		$doc = new DOMDocument();

		@$doc->loadHTML( $body );

		$script_tags = $doc->getElementsByTagName( 'script' );

		$json = '';

		foreach ( $script_tags as $script_tag ) {
			if ( strpos( $script_tag->nodeValue, 'window._sharedData = ' ) !== false ) {
				$json = $script_tag->nodeValue;
				break;
			}
		}

		$json   = str_replace( array( 'window._sharedData = ', '};' ), array( '', '}' ), $json );
		$result = json_decode( $json );

		if ( empty( $result ) ) {

			$error_data = $this->get_error( 'response-data-without-token-from-html-invalid-json-format' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			$result = $this->get_response_without_token_from_json( $user );

			if ( is_wp_error( $result ) ) {
				return new WP_Error( 'empty-json', __( 'Empty json decoded data.', 'wpzoom-instagram-widget' ) );
			}
		}

		return $result;
	}

	function get_response_without_token_from_json( $user ) {

		$user = trim( $user );
		$url  = 'https://instagram.com/' . str_replace( '@', '', $user ) . '/?__a=1';

		$request = wp_remote_get( $url, $this->headers );

		if ( is_wp_error( $request ) || 200 != wp_remote_retrieve_response_code( $request ) ) {
			$error_data = $this->get_error( 'response-data-without-token-from-json-invalid-response' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return new WP_Error( $error_data['code'], $error_data['message'] );
		}

		$result = json_decode( wp_remote_retrieve_body( $request ) );

		if ( empty( $result ) ) {
			$error_data = $this->get_error( 'response-data-without-token-from-json-invalid-json-format' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return new WP_Error( $error_data['code'], $error_data['message'] );
		}

		return $result;
	}

	function get_media_type_without_token( $media_type ) {
		$media_types = [ 'GraphImage' => 'IMAGE', 'GraphSidecar' => 'CAROUSEL_ALBUM', 'GraphVideo' => 'VIDEO' ];

		return array_key_exists( $media_type, $media_types ) ? $media_types[ $media_type ] : array_shift( $media_types );
	}

	function get_transient_lifetime() {

		$values = array( 'minutes' => MINUTE_IN_SECONDS, 'hours' => HOUR_IN_SECONDS, 'days' => DAY_IN_SECONDS );
		$keys   = array_keys( $values );
		$type   = in_array( $this->transient_lifetime_type, $keys ) ? $values[ $this->transient_lifetime_type ] : $values['minutes'];

		return $type * $this->transient_lifetime_value;
	}

	public function get_user_info( $injected_username = '' ) {


		$transient = 'zoom_instagram_user_info';

		$injected_username = rtrim( $injected_username );

		if ( ! empty( $injected_username ) && 'without-access-token' === $this->request_type ) {
			$injected_username = str_replace( '@', '', $injected_username );
			$transient         = $transient . '_' . $injected_username;
		}

		if ( false !== ( $data = json_decode( get_transient( $transient ) ) ) && is_object( $data ) && ! empty( $data->data ) ) {

			return $data;
		}

		$is_external_username = ! empty( $this->username ) || ! empty( $injected_username );
		$external_username    = ! empty( $injected_username ) ? $injected_username : $this->username;

		if ( ! empty( $this->access_token ) ) {

			$request_url = add_query_arg( [
				'access_token' => $this->access_token,
				'fields'       => 'account_type,id,media_count,username'
			],
				'https://graph.instagram.com/me' );

			$response = wp_remote_get( $request_url, $this->headers );

			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $transient, wp_json_encode( false ), MINUTE_IN_SECONDS );

				$error_data = $this->get_error( 'user-with-token-invalid-response' );
				$this->errors->add( $error_data['code'], $error_data['message'] );

				return false;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ) );
			$data = $this->convert_user_info_to_old_structure( $data );

		}

		if ( 'without-access-token' === $this->request_type && ! empty( $is_external_username ) ) {

			$data = $this->get_user_info_without_token( $external_username );

			if ( is_wp_error( $data ) ) {
				set_transient( $transient, wp_json_encode( false ), MINUTE_IN_SECONDS );

				return false;
			}

		}

		if ( ! empty( $data->data ) ) {
			set_transient( $transient, wp_json_encode( $data ), $this->get_transient_lifetime() );
		} else {
			set_transient( $transient, wp_json_encode( false ), MINUTE_IN_SECONDS );

			$error_data = $this->get_error( 'user-with-token-invalid-data-structure' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return false;
		}

		return $data;

	}

	function convert_user_info_to_old_structure( $user_info ) {

		$converted = new stdClass;

		$user_info_from_settings = get_option( 'wpzoom-instagram-widget-settings', wpzoom_instagram_get_default_settings() );

		$avatar = null;

		if ( ! empty( $user_info_from_settings['user-info-avatar'] ) ) {
			$img_src = wp_get_attachment_image_src( $user_info_from_settings['user-info-avatar'] );
			if ( ! empty( $img_src ) && is_array( $img_src ) ) {
				$avatar = $img_src[0];
			}
		}

		$fullname = ! empty( $user_info->username ) ? $user_info->username : null;

		if ( ! empty( $user_info_from_settings['user-info-fullname'] ) ) {
			$fullname = $user_info_from_settings['user-info-fullname'];
		}

		$converted->data = (object) array(
			'bio'             => ! empty( $user_info_from_settings['user-info-biography'] ) ? $user_info_from_settings['user-info-biography'] : null,
			'counts'          => (object) array(
				'followed_by' => null,
				'follows'     => null,
				'media'       => null,
			),
			'full_name'       => $fullname,
			'id'              => ! empty( $user_info->id ) ? $user_info->id : '',
			'is_business'     => null,
			'profile_picture' => $avatar,
			'username'        => ! empty( $user_info->username ) ? $user_info->username : '',
			'website'         => null
		);

		return $converted;

	}


	function get_user_info_without_token( $user ) {

		$response = $this->get_response_without_token( $user );

		if ( is_wp_error( $response ) ) {
			$error_data = $this->get_error( 'user-info-without-token' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return new WP_Error( $error_data['code'], $error_data['message'] );
		}

		if ( isset( $response->entry_data->ProfilePage[0]->graphql->user ) ) {
			$user_info = $response->entry_data->ProfilePage[0]->graphql->user;
		} elseif ( isset( $response->graphql->user ) ) {
			$user_info = $response->graphql->user;
		} else {
			$error_data = $this->get_error( 'user-info-without-token' );
			$this->errors->add( $error_data['code'], $error_data['message'] );

			return new WP_Error( $error_data['code'], $error_data['message'] );
		}

		$converted = new stdClass;

		$converted->data = (object) array(
			'bio'             => ! empty( $user_info->biography ) ? $user_info->biography : '',
			'counts'          => (object) array(
				'followed_by' => ! empty( $user_info->edge_followed_by->count ) ? $user_info->edge_followed_by->count : 0,
				'follows'     => ! empty( $user_info->edge_follow->count ) ? $user_info->edge_follow->count : 0,
				'media'       => ! empty( $user_info->edge_owner_to_timeline_media->count ) ? $user_info->edge_owner_to_timeline_media->count : 0,
			),
			'full_name'       => ! empty( $user_info->full_name ) ? $user_info->full_name : '',
			'id'              => ! empty( $user_info->id ) ? $user_info->id : '',
			'is_business'     => ! empty( $user_info->is_business_account ) ? $user_info->is_business_account : '',
			'profile_picture' => ! empty( $user_info->profile_pic_url ) ? $user_info->profile_pic_url : '',
			'username'        => ! empty( $user_info->username ) ? $user_info->username : '',
			'website'         => ! empty( $user_info->external_url ) ? $user_info->external_url : ''
		);

		return $converted;

	}

	public function is_configured() {
		$transient = 'zoom_instagram_is_configured';

		if ( false !== ( $result = json_decode( get_transient( $transient ) ) ) ) {
			if ( 'yes' === $result ) {
				return true;
			}

			if ( 'no' === $result ) {
				return false;
			}

			if ( ! empty( $result ) ) {
				return true;
			}
		}

		if ( empty( $this->username ) ) {
			$condition = $this->is_access_token_valid( $this->access_token, $this->request_type );

		} else {
			$condition = true;
		}


		if ( true === $condition ) {
			set_transient( $transient, wp_json_encode( 'yes' ), DAY_IN_SECONDS );

			return true;
		}

		set_transient( $transient, wp_json_encode( 'no' ), DAY_IN_SECONDS );

		return false;
	}

	/**
	 * Check if given access token is valid for Instagram Api.
	 */
	public static function is_access_token_valid( $access_token, $request_type = '' ) {


		if ( empty( $access_token ) ) {
			return false;
		}

		$request_url = add_query_arg( [
			'fields'       => 'username',
			'access_token' => $access_token

		], 'https://graph.instagram.com/me' );

		$response = wp_remote_get( $request_url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		return true;
	}
}