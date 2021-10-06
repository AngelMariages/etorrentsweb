<?php

/*------------------------------------------*/
/* WPZOOM: Single Page                      */
/*------------------------------------------*/

class Wpzoom_Single_Page extends WP_Widget {

	/* Widget setup. */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'wpzoom-singlepage',
			'description' => esc_html__( 'Custom WPZOOM widget that displays a single specified static page.', 'wpzoom' )
		);

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'wpzoom-single-page' );

		$this->defaults = array(
			'page_id' 			=> 0,
			'link_title' 		=> true,
			'show_image' 		=> true,
			'use_image_as_background' => false,
			'split_text' 		=> true,
			'readmore_enabled' 	=> true,
			'readmore_text' 	=> esc_html__( 'Read More', 'wpzoom' )
		);

		/* Create the widget. */
		parent::__construct(
			'wpzoom-single-page',
			esc_html__( 'WPZOOM: Single Page', 'wpzoom' ),
			$widget_ops,
			$control_ops
		);
	}

	/* How to display the widget on the screen. */
	function widget( $args, $instance ) {
		extract( $args );

		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		/* Our variables from the widget settings. */
		$page_id                 	= absint( $instance['page_id'] );
		$show_image              	= $instance['show_image'] == true;
		$use_image_as_background 	= $instance['use_image_as_background'];
		$split_text              	= $instance['split_text'];
        $readmore_enabled 			= $instance['readmore_enabled'] == true;
        $readmore_text 				= $instance['readmore_text'];


		if ( empty( $page_id ) || $page_id < 1 ) return false;
		$page_data = get_page( $page_id );
		$title = apply_filters( 'widget_title', trim($page_data->post_title), $instance, $this->id_base );
		$link_title = (bool) $instance['link_title'];

		if ( ! empty( $page_data->post_content ) ) {
			$data_attr  = '';
			$class_attr = 'featured_page_wrap ';
			if ( $use_image_as_background ) {
				$image = get_the_image( array( 'post_id' => $page_data->ID, 'size' => 'featured', 'format' => 'array' ) );
				if ( isset( $image['src'] ) ) {
					$data_attr .= 'data-background="' . esc_url( $image['src'] ) . '"';
				}
			}

			if ( $split_text ) {
				$class_attr .= 'text-columns-2 ';
			}

			echo '<div class="'.$class_attr.'" '. $data_attr .'><div class="featured_page_inner_wrap">';

				echo $before_widget;


				/* Title of widget (before and after defined by themes). */
				if ( $title ) {
					echo $before_title;

					if ( $link_title ) echo '<a href="' . esc_url( get_permalink($page_data->ID) ) . '">';
					echo $title;
					if ( $link_title ) echo '</a>';

					echo $after_title;
				}

				$page_excerpt = trim( $page_data->post_excerpt );

	 			echo '<div class="featured_page_content">';

		  			if ( $show_image && $page_excerpt ) {

						echo '<div class="post-video"><div class="video_cover">';

							echo apply_filters( 'the_content', trim($page_data->post_excerpt) );

						echo '</div></div>';

					} else if ( $show_image ) {

                        ?>

                        <div class="post-thumb">

                            <?php echo get_the_post_thumbnail( $page_data->ID, 'entry-cover' );

                            ?>

                        </div>

                        <?php

 					}

					echo '<div class="post-content">';

						if ( false !== ($more_tag_pos = strpos( $page_data->post_content, '<!--more-->' ) ) ){

							echo apply_filters( 'the_content', force_balance_tags(trim(substr($page_data->post_content, 0, $more_tag_pos))));

						} else {
							echo apply_filters( 'the_content', $page_data->post_content);
						}

					echo '</div>';

				echo '</div>';


                if ( $readmore_enabled ) : ?>

                    <div class="view_all-link">
                        <a class="btn" href="<?php echo esc_url( get_permalink($page_data->ID) ); ?>" title="<?php echo esc_attr( $readmore_text ); ?>">
                            <?php echo esc_html( $readmore_text ); ?>
                        </a>
                    </div>

                <?php endif;

				echo $after_widget;

			echo '</div></div>';
		}
	}

		/* Update the widget settings.*/
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			/* Strip tags for title and name to remove HTML (important for text inputs). */
			$instance['page_id']                 = (int) $new_instance['page_id'];
			$instance['link_title']              = $new_instance['link_title'];
			$instance['show_image']              = $new_instance['show_image'] == 'on';
			$instance['use_image_as_background'] = (bool) $new_instance['use_image_as_background'];
			$instance['split_text']              = (bool) $new_instance['split_text'];
            $instance['readmore_enabled'] = $new_instance['readmore_enabled'] == 'on';
            $instance['readmore_text'] = $new_instance['readmore_text'];

			return $instance;
		}

		/** Displays the widget settings controls on the widget panel.
		 * Make use of the get_field_id() and get_field_name() function when creating your form elements. This handles the confusing stuff. */
		function form( $instance ) {
			
			/* Set up some default widget settings. */
			$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

			<p>
				<label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('Page to Display:', 'wpzoom'); ?></label>

				<?php
					wp_dropdown_pages(
						array(
							'name' => $this->get_field_name('page_id'),
							'id' => $this->get_field_id('page_id'),
							'selected' => absint( $instance['page_id'] )
						)
					);
				?>
			</p>

			<p>
				<label>
					<input class="checkbox" type="checkbox" <?php checked( $instance['show_image'] ); ?> id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>" />
					<?php _e( 'Display Image/Video at the Top', 'wpzoom' ); ?>
				</label>
			</p>


			<p class="description">
				<?php _e('To display a video in the widget, make sure to insert the <strong>embed code</strong> in the <strong>Excerpt</strong> field of the selected page.', 'wpzoom'); ?>
			</p>

			<p>
				<label>
					<input class="checkbox" type="checkbox" <?php checked( $instance['use_image_as_background'] ); ?> id="<?php echo $this->get_field_id( 'use_image_as_background' ); ?>" name="<?php echo $this->get_field_name( 'use_image_as_background' ); ?>" />
					<?php _e( 'Use Featured Image as Widget Background', 'wpzoom' ); ?>
				</label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['link_title'], 'on' ); ?> id="<?php echo $this->get_field_id( 'link_title' ); ?>" name="<?php echo $this->get_field_name( 'link_title' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'link_title' ); ?>"><?php _e('Link Page Title to Page', 'wpzoom'); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['split_text'] ); ?> id="<?php echo $this->get_field_id( 'split_text' ); ?>" name="<?php echo $this->get_field_name( 'split_text' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'split_text' ); ?>"><?php _e('Split text in 2 columns?', 'wpzoom'); ?></label>
			</p>


            <p>
                <input class="checkbox" type="checkbox" <?php checked( $instance['readmore_enabled'] ); ?> id="<?php echo $this->get_field_id( 'readmore_enabled' ); ?>" name="<?php echo $this->get_field_name( 'readmore_enabled' ); ?>" />
                <label for="<?php echo $this->get_field_id( 'readmore_enabled' ); ?>">Display Read More button</label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'readmore_text' ); ?>">Text for Read More button:</label><br />
                <input id="<?php echo $this->get_field_id( 'readmore_text' ); ?>" name="<?php echo $this->get_field_name( 'readmore_text' ); ?>" value="<?php echo $instance['readmore_text']; ?>" type="text" class="widefat" />
            </p>


			<?php
		}
}

function wpzoom_register_sp_widget() {
	register_widget('Wpzoom_Single_Page');
}
add_action('widgets_init', 'wpzoom_register_sp_widget');
?>