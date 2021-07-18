<?php if (!defined('FW')) die( 'Forbidden' ); ?>


    <?php

    $term_id   = (int) $atts['category'];

    $posts_per_page = (int) $atts['posts_number'];
    if ( $posts_per_page == 0 ) {
        $posts_per_page = - 1;
    }


    if ( $term_id == 0 ) {
        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'post',
            'orderby'        => 'date'
        );
    } else {
        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'post',
            'orderby'        => 'date',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'id',
                    'terms'    => $term_id
                )
            )
        );
    }


    $wp_query = new WP_Query( $args );


    ?>

    <?php if ( $wp_query->have_posts() ) : ?>



            <ul class="feature-posts-list">

                <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>


                    <li>


                        <?php if ( has_post_thumbnail() ) : ?>

                            <div class="post-thumb">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'recent-thumbnail-retina' ); ?></a>
                            </div>

                        <?php endif; ?>


                        <?php if ( $atts['date'] == 'date_show') {
                            echo '<small>' . get_the_date() . '</small> <br />';
                        } ?>


                        <?php the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>

                        <?php if ( $atts['excerpt'] == 'excerpt_show') { ?>
                            <?php the_excerpt(); ?>
                        <?php } ?>




                        <?php if ( $atts['button'] == 'button_show') { ?>
                            <a class="btn" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                                <?php echo $atts['button_label']; ?>
                            </a>
                        <?php } ?>


                    </li>

                <?php endwhile; ?>

            </ul>



    <?php endif; ?>

