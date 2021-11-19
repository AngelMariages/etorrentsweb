<?php

class WPZOOM_Self_Hosted_Controller {

	public $prefix = 'wpz';


	public function __construct( $args ) {


		$filtered_args = wp_array_slice_assoc( $args, array( 'prefix', 'type' ) );
		$this->prefix  = $filtered_args['prefix'];

		if ( is_admin() ) {
			add_action( 'save_post', array( $this, 'save_metadata' ) );
		}

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

		if ( isset( $_POST[ $this->get_prefix() . 'video_background_mp4' ] ) ) {
			update_post_meta( $post_id, $this->get_prefix() . 'video_background_mp4', esc_url_raw( $_POST[ $this->get_prefix() . 'video_background_mp4' ] ) );
		}

		if ( isset( $_POST[ $this->get_prefix() . 'video_background_webm' ] ) ) {
			update_post_meta( $post_id, $this->get_prefix() . 'video_background_webm', esc_url_raw( $_POST[ $this->get_prefix() . 'video_background_webm' ] ) );
		}

	}


	public function render( $post_id ) {
		?>
		<div class="wpzoom_self_hosted switch-wrapper">
            <p class="description"><?php _e( 'We recommend using a very small video file in a low quality, to make sure it loads fast enough (2-5 MB).', 'wpzoom' ); ?>

			<div class="wp-media-buttons" data-button="Set Video" data-title="Set Video"
			     data-target="#<?php echo $this->get_prefix(); ?>video_background">
				<a href="#" class="button add_media" title="Upload Video">
					<span class="wp-media-buttons-icon"></span>
					<?php _e( 'Upload Video', 'wpzoom' ); ?>
				</a>
			</div>

			<div class="clear"></div>

			<p>
				<label>
					<strong><?php _e( 'MP4 (h.264) video URL', 'wpzoom' ); ?></strong>
					<input type="text" name="<?php echo $this->get_prefix(); ?>video_background_mp4"
					       id="<?php echo $this->get_prefix(); ?>video_background_mp4"
					       class="widefat"
					       value="<?php echo esc_attr( get_post_meta( $post_id, $this->get_prefix() . 'video_background_mp4', true ) ); ?>"/>
				</label>

			</p>

			</p>

			<em><strong>Tips:</strong></em><br/>
			<ol class="wpz_list">
				<li>You can create quickly a short <strong>MP4</strong> video from a <strong>Vimeo</strong> or <strong>YouTube</strong>
					video using <a href="https://giphy.com/create/gifmaker/" target="_blank">GIF Maker</a> tool from
					<strong>GIPHY</strong>.
				</li>
				<li>Once you've created a GIF, click on the <strong>Media</strong> link (<a
						href="https://www.wpzoom.com/wp-content/uploads/2019/02/reel-video-background-mp4-giphy.png"
						target="_blank">view screenshot</a>) and you will find the link to MP4 file, which you can
					download or use directly that link.
				</li>

			</ol>

			<div class="wpz_border"></div>

			<p>

				<label>
					<strong><?php _e( 'WebM video URL', 'wpzoom' ); ?></strong> <em>(optional)</em>
					<input type="text" name="<?php echo $this->get_prefix(); ?>video_background_webm"
					       id="<?php echo $this->get_prefix(); ?>video_background_webm"
					       class="widefat"
					       value="<?php echo esc_attr( get_post_meta( $post_id, $this->get_prefix() . 'video_background_webm', true ) ); ?>"/>
				</label>
			</p>
			<p class="description"><?php _e( 'This format is optional for old versions of <strong>Mozilla Firefox</strong> that don\'t support <strong>MP4</strong> (v.21 and older).', 'wpzoom' ); ?></p>
		</div>
		<?php
	}

	public function get_data( $post_id ) {


		$url                   = '';
		$video_background_webm = get_post_meta( $post_id, $this->get_prefix() . 'video_background_webm', true );
		$video_background_mp4  = get_post_meta( $post_id, $this->get_prefix() . 'video_background_mp4', true );

		if ( ! empty( $video_background_webm ) ) {
			$url = $video_background_webm;
		}

		if ( ! empty( $video_background_mp4 ) ) {
			$url = $video_background_mp4;
		}

		return $url;
	}


}
