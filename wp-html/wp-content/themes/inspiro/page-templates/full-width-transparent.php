<?php
/**
 Template Name: Page Builder (Transparent Header, Without Title)
 */

get_header(); ?>

<main id="main" class="site-main page-with-cover" role="main">

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="builder-wrap full-width">

            <article id="post-<?php the_ID(); ?>" class="has-post-cover">

                <div class="entry-content">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->

            </article><!-- #post-## -->

        </div><!-- .full-width -->

    <?php endwhile; // end of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
