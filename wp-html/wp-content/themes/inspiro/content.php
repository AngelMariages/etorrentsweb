<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ( option::is_on('index_thumb') ) {

        $image_ratio = option::get( 'post_view_blog' );
        $image_size = 'loop';
        if ($image_ratio == '3-columns') { $image_size = 'portfolio_item-thumbnail'; }

        if ( has_post_thumbnail() ) { ?>

            <div class="post-thumb"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_post_thumbnail($image_size); ?>
            </a></div>

      <?php }

    } ?>

    <section class="entry-body">
        <header class="entry-header">

            <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

            <div class="entry-meta">
                <?php if ( option::is_on( 'display_author' ) ) { ?><span class="entry-author"><?php _e('by', 'wpzoom'); ?> <?php the_author_posts_link(); ?></span><?php } ?>
                <?php if ( option::is_on( 'display_date' ) ) { ?><span class="entry-date"><?php echo get_the_date(); ?></span><?php } ?>

                <?php if ( option::is_on( 'display_category' ) ) printf( '<span class="cat-links">' .__('in', 'wpzoom') . ' %s</span>', get_the_category_list( ', ' ) ); ?>

                <?php if ( option::is_on( 'display_comments' ) ) { ?><span><?php comments_popup_link( __('0 comments', 'wpzoom'), __('1 comment', 'wpzoom'), __('% comments', 'wpzoom'), '', __('Comments are Disabled', 'wpzoom')); ?></span><?php } ?>

                <?php edit_post_link( __( 'Edit', 'wpzoom' ), '<span class="edit-link">', '</span>' ); ?>
            </div>

        </header>

        <div class="entry-content">
            <?php if (option::get('display_content') == 'Full Content') {
                the_content(''.__('Read More', 'wpzoom').'');
            }
            if (option::get('display_content') == 'Excerpt')  {
                the_excerpt();

                ?>

                <div class="clear"></div>
                <a class="more_link clearfix" href="<?php the_permalink(); ?>" rel="nofollow"><?php _e( 'Read More', 'wpzoom' ); ?></a>
            <?php } ?>
        </div>
    </section>

    <div class="clearfix"></div>
</article><!-- #post-## -->