<?php
/*
Template Name: Homepage (Page Builder)
*/

get_header(); ?>

    <?php if ( option::is_on( 'featured_posts_show' ) ) : ?>

        <?php get_template_part( 'wpzoom-slider' ); ?>

    <?php endif; ?>

	<?php if ( ! option::is_on( 'featured_posts_show' ) ) : ?>

        <main id="content" class="clearfix <?php if (inspiro_maybeWithCover()) echo 'page-with-cover'; ?>" role="main">

    <?php endif; ?>

        <div class="builder-wrap">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php the_content(); ?>

            <?php endwhile; // end of the loop. ?>

        </div>

	<?php if ( ! option::is_on( 'featured_posts_show' ) ) : ?>

       </main><!-- #content -->

   <?php endif; ?>

<?php
get_footer();
