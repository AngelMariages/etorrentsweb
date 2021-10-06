<?php

/*------------------------------------------*/
/* WPZOOM: Recent Posts           */
/*------------------------------------------*/

class Wpzoom_Feature_Posts extends WP_Widget {

	function __construct() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'feature-posts',
			'description' => esc_html__( 'A list of posts, optionally filter by category.', 'wpzoom' )
		);

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'wpzoom-feature-posts' );

		$this->defaults = array(
			'title'          => esc_html__( 'Recent Posts', 'wpzoom' ),
			'category'       => 0,
			'show_count'     => 3,
			'show_date'      => false,
			'show_thumb'     => false,
			'show_excerpt'   => false,
			'hide_title'     => false,
			'read_more'      => true,
			'excerpt_length' => 150
		);

		/* Create the widget. */
		parent::__construct(
			'wpzoom-feature-posts',
			esc_html__( 'WPZOOM: Recent Posts', 'wpzoom' ),
			$widget_ops,
			$control_ops
		);
	}

	function widget( $args, $instance ) {

		extract( $args );

		/* Set up some default widget settings. */
        $instance = wp_parse_args( (array) $instance, $this->defaults );

		/* User-selected settings. */
		$title 			= apply_filters('widget_title', $instance['title'] );
		$category 		= $instance['category'];
		$show_count 	= $instance['show_count'];
		$show_date 		= $instance['show_date'];
		$show_thumb 	= $instance['show_thumb'];
		$show_excerpt 	= $instance['show_excerpt'];
		$excerpt_length = $instance['excerpt_length'];
		$show_title 	= ! $instance['hide_title'];
		$read_more      = $instance['read_more'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<ul class="feature-posts-list">';

		$query_opts = apply_filters('wpzoom_query', array(
			'posts_per_page' => $show_count,
			'post_type' => 'post',
			'post__not_in' => get_option( 'sticky_posts' )

		));

		if ( $category ) $query_opts['cat'] = $category;

		$query = new WP_Query( $query_opts );

		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
 			$echo_title = $show_title && get_the_title();

			echo '<li>';
			?>

			<?php if ( $show_thumb && has_post_thumbnail() ) : ?>

				<div class="post-thumb">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'recent-thumbnail' ); ?></a>
				</div>

			<?php endif; ?>

			<?php
			if ( $show_date ) echo '<small>' . get_the_date() . '</small> <br />';

			if ( $show_title ) echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

			if ( $show_excerpt ) {
				$the_excerpt = get_the_excerpt();

				// cut to character limit
				$the_excerpt = substr( $the_excerpt, 0, $excerpt_length );

				// cut to last space
				$the_excerpt = substr( $the_excerpt, 0, strrpos( $the_excerpt, ' '));

				echo '<p>' . $the_excerpt . '</p>';
			}

			?>

			<?php if ( $read_more ) : ?>

				<a class="btn" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'wpzoom' ); ?></a>

			<?php endif; ?>

			<?php
			echo '<div class="clear"></div></li>';
			endwhile; else:
			endif;

			//Reset query_posts
			wp_reset_postdata();
		echo '</ul>';

		/* After widget (defined by themes). */
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title']          = sanitize_text_field( $new_instance['title'] );
		$instance['category']       = ( 0 <= (int) $new_instance['category'] ) ? (int) $new_instance['category'] : null;
		$instance['show_count']     = ( 0 !== (int) $new_instance['show_count'] ) ? (int) $new_instance['show_count'] : null;
		$instance['show_date']      =  !empty($new_instance['show_date']);
		$instance['show_thumb']     =  !empty($new_instance['show_thumb']);
		$instance['show_excerpt']   =  !empty($new_instance['show_excerpt']);
		$instance['hide_title']     =  !empty($new_instance['hide_title']);
		$instance['read_more']      =  !empty($new_instance['read_more']);
		$instance['excerpt_length'] = ( 0 <= (int) $new_instance['excerpt_length'] ) ? (int) $new_instance['excerpt_length'] : null;

		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>">Category:</label>
			<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
				<option value="0" <?php if ( !$instance['category'] ) echo 'selected="selected"'; ?>>All</option>
				<?php
				$categories = get_categories(array('type' => 'post'));

				foreach( $categories as $cat ) {
					echo '<option value="' . $cat->cat_ID . '"';

					if ( $cat->cat_ID == $instance['category'] ) echo  ' selected="selected"';

					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';

					echo '</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php esc_html_e( 'Show:', 'wpzoom' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" value="<?php echo $instance['show_count']; ?>" type="text" size="2" /> posts
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['hide_title'] ); ?> id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php esc_html_e( 'Hide post title', 'wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_date'] ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php esc_html_e( 'Display post date', 'wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_thumb'] ); ?> id="<?php echo $this->get_field_id( 'show_thumb' ); ?>" name="<?php echo $this->get_field_name( 'show_thumb' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php esc_html_e( 'Display post thumbnail', 'wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_excerpt'] ); ?> id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"><?php esc_html_e( 'Display post excerpt', 'wpzoom' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['read_more'] ); ?> id="<?php echo $this->get_field_id( 'read_more' ); ?>" name="<?php echo $this->get_field_name( 'read_more' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'read_more' ); ?>"><?php esc_html_e( 'Display read more button', 'wpzoom' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php esc_html_e( 'Excerpt character limit:', 'wpzoom' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" type="text" size="4" />
		</p>

		<?php
	}
}

function wpzoom_register_fp_widget() {
	register_widget('Wpzoom_Feature_Posts');
}
add_action('widgets_init', 'wpzoom_register_fp_widget');