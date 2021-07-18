<?php
/**
 * Template Name: Full-width Gallery
 */

get_header(); ?>

<main id="main" class="site-main full-width <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?>" role="main">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( (has_post_thumbnail() ? ' has-post-cover' : '') ); ?>>
            <div class="entry-cover">

                <?php
                    $entryCoverBackground = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'entry-cover' );
                    $small_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'featured-small');
                    $style = ' data-smallimg="' . $small_image_url[0] . '" data-bigimg="' . $entryCoverBackground[0] . '"';
                ?>

                <?php if ( !empty( $entryCoverBackground ) ) : ?>

                    <div class="entry-cover-image" <?php echo $style; ?>></div>

                <?php endif; ?>

                <header class="entry-header">
                    <div class="entry-info">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                    </div>
                </header><!-- .entry-header -->
            </div><!-- .entry-cover -->

            <div class="entry-content">
                <?php the_content(); ?>
                <?php
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . __( 'Pages:', 'wpzoom' ),
                        'after'  => '</div>',
                    ) );
                ?>
            </div><!-- .entry-content -->

        </article><!-- #post-## -->

    <?php endwhile; // end of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
