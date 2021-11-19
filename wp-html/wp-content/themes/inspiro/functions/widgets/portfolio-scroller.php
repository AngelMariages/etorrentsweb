<?php

/*------------------------------------------*/
/* WPZOOM: Portfolio Scroller               */
/*------------------------------------------*/

class Wpzoom_Portfolio_Scroller extends WP_Widget {

    function __construct() {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'wpzoom-portfolio-scroller',
            'description' => esc_html__( 'Your portfolio posts in an attractive horizontal scroller.', 'wpzoom' )
        );

        /* Widget control settings. */
        $control_ops = array( 'id_base' => 'wpzoom-portfolio-scroller' );

        $this->defaults = array(
            'title'             => '',
            'category'          => 0,
            'items_num'         => 5,
            'hide_title'        => false,
            'hide_excerpt'      => false,
            'hide_button'       => false,
            'auto_scroll'       => true,
            'scroll_infinitely' => false
        );

        /* Create the widget. */
        parent::__construct(
            'wpzoom-portfolio-scroller',
            esc_html__( 'WPZOOM: Portfolio Scroller', 'wpzoom' ),
            $widget_ops,
            $control_ops
        );
    }

    function widget( $args, $instance ) {

        global $wp_query;

        extract( $args );

        /* Set up some default widget settings. */
        $instance = wp_parse_args( (array) $instance, $this->defaults );

        /* User-selected settings. */
        $title              = apply_filters( 'widget_title', $instance['title'] );
        $category           = absint( $instance['category'] );
        $items_num          = absint( $instance['items_num'] );
        $show_title         = $instance['hide_title'] === false;
        $show_excerpt       = $instance['hide_excerpt'] === false;
        $show_button        = $instance['hide_button'] === false;
        $auto_scroll        = $instance['auto_scroll'] == true;
        $scroll_infinitely  = $instance['scroll_infinitely'] === true;

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Title of widget (before and after defined by themes). */
        if ( $title )
            echo $before_title . $title . $after_title;

        ?>

       <div class='loading-wrapper' id="loading-<?php echo $this->get_field_id('id'); ?>">
            <div class="spinner">
                <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div>
            </div>
        </div>

        <div class="carousel_widget_wrapper" id="carousel_widget_wrapper-<?php echo $this->get_field_id('id'); ?>">

            <div data-auto-scroll='<?php echo $auto_scroll ?>'
                 data-scroll-infinitely='<?php echo $scroll_infinitely ?>'
                 class= 'flickity-wrapper'
                 id="carousel-<?php echo $this->get_field_id('id'); ?>">

                <?php $query_opts = apply_filters('wpzoom_query', array(
                    'posts_per_page' => $items_num,
                    'post_type' => 'portfolio_item',
                    'orderby' =>'menu_order date'
                ));
                if ( $category > 0 ) $query_opts['tax_query'] = array(
                    array(
                        'taxonomy' => 'portfolio',
                        'terms' => $category
                    )
                );
                $query = new WP_Query($query_opts);

                if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

                $articleClass = ( ! has_post_thumbnail() ) ? 'no-thumbnail ' : '';

                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( $articleClass ); ?>>

                    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">

                        <div class="entry-thumbnail-popover">
                            <div class="entry-thumbnail-popover-content popover-content--animated">

                                <?php
                                if ( $show_title) :
                                    the_title( '<h3 class="portfolio_item-title">', '</h3>' );
                                endif;

                                if ( $show_excerpt) :
                                    the_excerpt();
                                endif;
                                ?>

                                <?php if ( $show_excerpt) : ?>
                                    <span class="btn">
                                        <?php _e( 'Read More', 'wpzoom' ); ?>
                                    </span>
                                <?php endif; ?>

                            </div>
                        </div>

                        <?php the_post_thumbnail( 'portfolio-scroller-widget' ); ?>

                    </a>
                </article>

                <?php

                    endwhile; else:

                    echo '<p>' . __( 'Nothing to display&hellip;', 'wpzoom' ) . '</p>';

                endif; ?>

            </div><!-- /.carousel_widget_wrapper -->

        </div><!-- /#carousel-<?php echo $this->get_field_id('id'); ?>-->

        <?php

        //Reset query_posts
        wp_reset_postdata();

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance['title']        = sanitize_text_field( $new_instance['title'] );
        $instance['category']     = $new_instance['category'];
        $instance['items_num']    = absint( $new_instance['items_num'] );
        $instance['hide_title']   =  !empty($new_instance['hide_title']);
        $instance['hide_excerpt'] =  !empty($new_instance['hide_excerpt']);
        $instance['hide_button']  =  !empty($new_instance['hide_button']);
        $instance['scroll_infinitely'] = !empty($new_instance['scroll_infinitely']);
        $instance['auto_scroll']  = !empty($new_instance['auto_scroll']);

        return $instance;
    }

    function form( $instance ) {

        /* Set up some default widget settings. */
        $instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

        <p>
            <label>
                <?php _e( 'Title:', 'wpzoom' ); ?>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" type="text" class="widefat" />
            </label>
        </p>

        <p>
            <label>
                <?php _e( 'Category:', 'wpzoom' ); ?>
                <select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="widefat">
                    <option value="0" <?php if ( !$instance['category'] ) echo 'selected="selected"'; ?>>All</option>
                    <?php
                    $categories = get_categories( array( 'taxonomy' => 'portfolio' ) );

                    foreach( $categories as $cat ) {
                        echo '<option value="' . $cat->cat_ID . '"';

                        if ( $cat->cat_ID == $instance['category'] ) echo  ' selected="selected"';

                        echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';

                        echo '</option>';
                    }
                    ?>
                </select>
            </label>
        </p>

        <p>
            <label>
                <?php _e( 'Number of posts:', 'wpzoom' ); ?>
                <input id="<?php echo $this->get_field_id( 'items_num' ); ?>" name="<?php echo $this->get_field_name( 'items_num' ); ?>" value="<?php echo absint( $instance['items_num'] ); ?>" type="number" size="4" />
            </label>
            <span class="howto"><?php _e( 'Number of portfolio items to show at one time', 'wpzoom' ); ?></span>
        </p>

        <p>
            <label>
                <input class="checkbox" type="checkbox" <?php checked( $instance['hide_title'] ); ?> id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" />
                <?php _e( 'Hide Post Title', 'wpzoom' ); ?>
            </label>
        </p>

        <p>
            <label>
                <input class="checkbox" type="checkbox" <?php checked( $instance['hide_excerpt'] ); ?> id="<?php echo $this->get_field_id( 'hide_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'hide_excerpt' ); ?>" />
                <?php _e( 'Hide Excerpt', 'wpzoom' ); ?>
            </label>
        </p>

        <p>
            <label>
                <input class="checkbox" type="checkbox" <?php checked( $instance['hide_button'] ); ?> id="<?php echo $this->get_field_id( 'hide_button' ); ?>" name="<?php echo $this->get_field_name( 'hide_button' ); ?>" />
                <?php _e( 'Hide Read More button', 'wpzoom' ); ?>
            </label>
        </p>


        <p>
            <label>
                <input class="checkbox" type="checkbox" <?php checked( $instance['auto_scroll'] ); ?> id="<?php echo $this->get_field_id( 'auto_scroll' ); ?>" name="<?php echo $this->get_field_name( 'auto_scroll' ); ?>" />
                <?php _e( 'Auto-Scroll', 'wpzoom' ); ?>
            </label>
            <span class="howto"><?php _e( 'Automatically scroll through the portfolio items', 'wpzoom' ); ?></span>
        </p>

        <p>
            <label>
                <input class="checkbox" type="checkbox" <?php checked( $instance['scroll_infinitely'] ); ?> id="<?php echo $this->get_field_id( 'scroll_infinitely' ); ?>" name="<?php echo $this->get_field_name( 'scroll_infinitely' ); ?>" />
                <?php _e( 'Scroll Infinitely', 'wpzoom' ); ?>
            </label>
         </p>

        <?php
    }
}

function wpzoom_register_ps_widget() {
    register_widget('Wpzoom_Portfolio_Scroller');
}
add_action('widgets_init', 'wpzoom_register_ps_widget');