<?php get_header(); ?>

<main id="main" class="site-main" role="main">

    <section class="recent-posts">

        <h2 class="section-title"><?php _e('Error 404', 'wpzoom'); ?></h2>

        <?php get_template_part( 'content', 'none' ); ?>

    </section><!-- .recent-posts -->

</main><!-- .site-main -->

<?php
get_footer();
