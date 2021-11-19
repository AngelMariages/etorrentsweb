<?php
$subcategory = get_post_meta($post->ID, 'wpzoom_portfolio_page_category_name', true);

if (!$subcategory ) {
    $subcategory = option::get( 'portfolio_category_displayed' );
}

?>


<nav class="portfolio-archive-taxonomies">
    <ul class="portfolio-taxonomies portfolio-taxonomies-filter-by">
        <li class="cat-item current-cat cat-item-all" <?php echo( ! empty( $subcategory ) ? 'data-subcategory="' . $subcategory . '"' : '' ); ?>>
            <a href="<?php echo get_page_link( option::get( 'portfolio_url' ) ); ?>"><?php _e( 'All', 'wpzoom' ); ?></a>
        </li>

        <?php wp_list_categories( array(
            'title_li'     => '',
            'hierarchical' => false,
            'taxonomy'     => 'portfolio',
            'child_of'     => $subcategory
        ) ); ?>

    </ul>
</nav>