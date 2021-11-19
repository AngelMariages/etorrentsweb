<?php
/*
Template Name: Portfolio (Clean)
*/

get_header(); ?>

<main id="main" <?php post_class( (has_post_thumbnail() ? ' portfolio-with-post-cover' : '') ); ?> role="main">

    <section class="portfolio-archive">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'portfolio/includes/portfolio-start' ); ?>

            <?php get_template_part( 'portfolio/includes/filter-isotope' ); ?>

        <?php endwhile; // end of the loop. ?>

        <?php
        $portfolio_ajax_atts = '';
        $col_number          = option::get( 'portfolio_grid_col' );
        $subcategory = get_post_meta($post->ID, 'wpzoom_portfolio_page_category_name', true);

        if (!$subcategory ) {
            $subcategory = option::get( 'portfolio_category_displayed' );
        }

        $args                = array(
            'post_type'      => 'portfolio_item',
            'posts_per_page' => - 1,
            'orderby'        => 'menu_order date'
        );

        if ( option::is_on( 'portfolio_ajax_items_loading' ) ) {
            $args['posts_per_page'] = (int) option::get( 'portfolio_posts' );
            $portfolio_ajax_atts    = array_to_data_atts(
                array(
                    'data-nonce'              => esc_attr( wp_create_nonce( 'wpz_get_portfolio_filtered_items' ) ),
                    'data-ajax-items-loading' => 1,
                    'data-callback-template' =>  basename( get_page_template() )
                ) );
        }

        if ( ! empty( $subcategory ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portfolio',
                    'terms'    =>  $subcategory,
                    'field'    => 'term_id',
                )
            );
        }

        $wp_query = new WP_Query( $args );

        $always_play_background_video = option::is_on('always_play_portfolio_background_video');
        $always_play_background_video_class = empty($always_play_background_video) ? '' : ' always-play-background-video';
        ?>

        <?php if ( $wp_query->have_posts() ) : ?>

            <div class="inner-wrap portfolio_template_clean">

                <div <?php echo $portfolio_ajax_atts; ?>
                     class="portfolio-grid col_no_<?php echo $col_number; ?> <?php if ( option::is_on( 'portfolio_whitespace' ) ) { ?> portfolio_with_space<?php } ?><?php echo $always_play_background_video_class; ?>">

                    <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

                        <?php get_template_part( 'portfolio/content-clean' ); ?>

                        <?php endwhile; ?>

                    </div>

                <?php else: ?>

                    <?php get_template_part( 'content', 'none' ); ?>

                <?php endif; ?>

            </section><!-- .portfolio-archive -->

        </main><!-- #main -->

<?php get_footer(); ?>