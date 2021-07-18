<?php get_header(); ?>

<?php
$taxonomy_obj = $wp_query->get_queried_object();
$taxonomy_id = $taxonomy_obj->term_id;
$taxonomy_nice_name = $taxonomy_obj->name;
$taxonomy_description = $taxonomy_obj->description;
$portfolio_page = option::get( 'portfolio_url' );
$col_number = option::get('portfolio_grid_col');

?>
<main id="main" role="main"<?php if ( !empty($portfolio_page) && has_post_thumbnail($portfolio_page) ) { echo ' class="portfolio-with-post-cover"'; } ?>>

    <section class="portfolio-archive">

        <div class="portfolio-header-cover">

            <?php
                $entryCoverBackground = wp_get_attachment_image_src( get_post_thumbnail_id( $portfolio_page ), 'entry-cover' );
                $small_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($portfolio_page), 'featured-small');
                $style = ' data-smallimg="' . $small_image_url[0] . '" data-bigimg="' . $entryCoverBackground[0] . '"';
                $style .= 'style="background-image:url(\''. $small_image_url[0] .'\')"';
            ?>

            <?php if ( !empty( $entryCoverBackground ) ) : ?>

                <div class="portfolio-header-cover-image" <?php echo $style; ?>></div>

            <?php endif; ?>

            <div class="portfolio-header-info">
                <div class="entry-info">
                    <h2 class="section-title"><?php echo $taxonomy_nice_name; ?></h2>

                    <div class="entry-header-excerpt"><?php echo $taxonomy_description; ?></div>

                </div>
            </div><!-- .portfolio-header-info -->

        </div><!-- .portfolio-header-cover -->

        <nav class="portfolio-archive-taxonomies">

            <ul class="portfolio-taxonomies">

            <?php

                $children = get_term_children($taxonomy_id, 'portfolio');
                $taxonomy_parent = $taxonomy_obj->parent;
                $term_link = get_term_link( $taxonomy_parent );

                if( !empty( $children ) && !empty( $taxonomy_parent ) ) { ?>

                     <span class="return_to_parent"><a href="<?php echo $term_link; ?>"><?php _e( '&larr; back', 'wpzoom' ); ?></a></span>

                <?php } ?>

                <?php
                    if( !empty( $children ) ) {
                  ?>

                        <li class="cat-item current-cat cat-item-all" >
                            <a href="<?php echo get_page_link( option::get( 'portfolio_url' ) ); ?>"><?php _e( 'All', 'wpzoom' ); ?></a>
                        </li>

                        <?php wp_list_categories( array(
                            'title_li'     => '',
                            'hierarchical' => false,
                            'taxonomy'     => 'portfolio',
                            'child_of'     => $taxonomy_id
                        ) ); ?>

                <?php } elseif (!empty( $taxonomy_parent ) ) { ?>

                        <li class="cat-item cat-item-all">
                            <a href="<?php echo $term_link; ?>"><?php _e( 'All', 'wpzoom' ); ?></a>
                        </li>

                        <?php wp_list_categories( array(
                            'title_li'     => '',
                            'hierarchical' => false,
                            'taxonomy'     => 'portfolio',
                            'child_of'     => $taxonomy_parent
                        ) ); ?>

                <?php } ?>
            </ul>

        </nav>

        <?php if ( $wp_query->have_posts() ) : ?>

            <div class="portfolio-grid col_no_<?php echo $col_number; ?> <?php if ( option::is_on( 'portfolio_whitespace' ) ) { ?> portfolio_with_space<?php } ?>">

                <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

                    <?php get_template_part( 'portfolio/content' ); ?>

                <?php endwhile; ?>

            </div>

            <?php get_template_part( 'pagination' ); ?>

        <?php else: ?>

            <div class="recent-posts">
                <?php get_template_part( 'content', 'none' ); ?>
            </div>

        <?php endif; ?>

    </section><!-- .portfolio-archive -->

</main><!-- .site-main -->

<?php
get_footer();
