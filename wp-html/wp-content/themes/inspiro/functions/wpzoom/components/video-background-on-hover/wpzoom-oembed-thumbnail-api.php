<?php

class WPZOOM_Oembed_Thumbnail_API {

	static function get_youtube_id( $url ) {
		return preg_match(
			"/(?:[\/]|v=)([a-zA-Z0-9-_]{11})/",
			$url,
			$id )
			? $id[1]
			: false;
	}

	static function get_youtube_thumbnail( $video_id, $size = 'highresolution' ) {
		$output = '';

		$youtube_sizes = array(
			'highresolution' => 'maxresdefault',
			'large'          => 'sddefault',
			'medium'         => 'hqdefault',
			'small'          => 'mqdefault'
		);

		$is_size = empty( $youtube_sizes[ $size ] ) ? false : $youtube_sizes[ $size ];

		foreach ( $youtube_sizes as $current_size ) {
			$url = sprintf( 'http%s://img.youtube.com/vi/%s/%s.jpg', ( is_ssl() ? 's' : '' ), $video_id, $current_size );

			if ( $is_size == $current_size && false !== ( $img = getimagesize( $url ) ) && is_array( $img ) ) {
				$output = $url;
				break;
			}
		}

		return $output;
	}

	static function get_thumbnail( $url ) {
		if ( $url === false ) {
			return false;
		}

		require_once( ABSPATH . WPINC . '/class-oembed.php' );
		$oembed = _wp_oembed_get_object();

		$provider = $oembed->get_provider( $url );
		if ( ! $provider ) {
			return false;
		}


		if ( strpos( $provider, 'youtube' ) !== false ) {
			$yt_url = self::get_youtube_thumbnail( self::get_youtube_id( $url ) );
		}

		if ( ! empty( $yt_url ) ) {
			$data  = $oembed->fetch( $provider, $url );
			$title = ! empty( $data->title ) ? $data->title : 'Video';

			return array( 'url' => $yt_url, 'title' => $title );
		}

		$data = $oembed->fetch( $provider, $url );
		if ( ! $data ) {
			return false;
		}

		return isset( $data->thumbnail_url ) && ! empty( $data->thumbnail_url ) ?
			array( 'url' => $data->thumbnail_url, 'title' => $data->title ) :
			array();
	}
}