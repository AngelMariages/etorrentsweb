<?php
return array(
    'documentation' => array(
        'header' => __('Read Theme Documentation', 'wpzoom'),
        'content' => __('<strong>Theme Documentation</strong> is the place where you\'ll find the information needed to setup the theme quickly, and other details about theme-specific features.', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="https://www.wpzoom.com/documentation/'.str_replace('_', '-', WPZOOM::$theme_raw_name).'/" target="_blank">'.WPZOOM::$themeName.' Documentation &raquo;</a>
'
        )
    ),
    'demo-content' => array(
        'header' => __('Import the Demo Content', 'wpzoom'),
        'content' => __('If you’re installing the theme on a new site, installing the demo content is the best way to get familiarized. This feature can be found on the <a href="admin.php?page=wpzoom_options" target="_blank">Theme Options</a> page, in the <strong>Import/Export</strong> section.', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="https://www.wpzoom.com/docs/demo-content-importer/" target="_blank">View Instructions</a> &nbsp;&nbsp;',
            '<a class="button button-secondary" href="admin.php?page=wpzoom_options" target="_blank">Open Theme Options</a>'
        )
    ),
    'customizer' => array(
        'header' => __('Add your Logo & Customize the Theme', 'wpzoom'),
        'content' => __('Using the <strong>Live Customizer</strong> you can easily upload your <strong>logo image</strong>, change <strong>fonts, colors, widgets, menus</strong> and much more!', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="customize.php" target="_blank">Open Theme Customizer »</a>',
        )
    ),
    'plugins' => array(
        'header' => __('Install Required Plugins', 'wpzoom'),
        'content' => __('In order to enable all the features from your theme, you’ll need to install and activate recommended plugins such as <strong>Jetpack</strong> or <strong>WooCommerce</strong>, which are available for <strong>free</strong>.<br/>
            <h4>General Plugins</h4><ul><li><a href="https://wordpress.org/plugins/jetpack/" target="_blank">Jetpack by WordPress.com</a> <em>(Free)</em> - popular plugin that includes a dozen of features. <strong>Tiled Galleries, Lightbox</strong> are just some of the features recommended for <strong>Inspiro</strong></li> <li>&nbsp;</li><li><strong style="color:red;">NEW!</strong><br/> <strong><a href="https://www.wpbeaverbuilder.com/wpzoom/?fla=463" target="_blank">Beaver Builder</a></strong> <em>(Premium)</em> - a powerful and easy to use <strong>Page Builder</strong>. Click <a href="https://www.wpbeaverbuilder.com/wpzoom/?fla=463" target="_blank">here</a> to enable a <storng>$10 off discount</strong> when purchasing the plugin.</li><li>&nbsp;</li> <li><a href="https://wordpress.org/plugins/beaver-builder-lite-version/" target="_blank">Beaver Builder Lite</a> <em>(Free)</em> - the free version of the builder, with less features and widgets.</li> <li><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> <em>(Free)</em> - popular <strong>eCommerce</strong> plugin</li><li><a href="https://wordpress.org/plugins/social-icons-widget-by-wpzoom/" target="_blank">Social Icons Widget</a> <em>(Free)</em> - simple plugin to add social icons</li><li><a href="https://wordpress.org/plugins/instagram-widget-by-wpzoom/" target="_blank">Instagram Widget by WPZOOM</a> <em>(Free)</em> - Instagram timeline widget for your theme</li></ul>', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="admin.php?page=tgmpa-install-plugins" target="_blank">Install Required Plugins</a>&nbsp;&nbsp;',
            '<a class="button button-secondary" href="https://www.wpzoom.com/recommended-plugins/" target="_blank">Recommended Plugins by WPZOOM</a>'
        )
    ),
    'front-page' => array(
        'header' => __('Setup Homepage Template', 'wpzoom'),
        'content' => __('Don\'t want to display your latest posts on homepage? <br/><br/>Create a <a href="post-new.php?post_type=page">new page</a> and assign a special <strong>Page Template</strong> to it, depending on your needs (<a href="http://www.wpzoom.com/docs/page-templates/" target="_blank">view instructions</a>):<br/><ul><li><strong>Homepage (Widgetized)</strong> - <em>page template that includes a widget area. Manage widgets located on this page from <a href="widgets.php" target="_blank">Widgets</a> page or Customizer. </em></li><li><strong>Homepage (Page Builder)</strong> - <em>special page template for front page that integrates great with <strong>Beaver Page Builder</strong> and that displays the slideshow at the top.</em></li></ul>', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="post-new.php?post_type=page" target="_blank">Create a New Page »</a>&nbsp;&nbsp;',
            '<a class="button button-secondary" href="options-reading.php" target="_blank">Change what Front Page displays</a>'
        ),
    ),
    'slideshow' => array(
        'header' => __('Add a New Slideshow Post', 'wpzoom'),
        'content' => __('The <strong>Homepage Slideshow</strong> displays your latest <strong>Slideshow</strong> posts. Each post is a separate slide.', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="post-new.php?post_type=slider" target="_blank">Create a New Slideshow Post »</a>'
        ),
    ),
    'portfolio' => array(
        'header' => __('Add a New Gallery/Portfolio Post', 'wpzoom'),
        'content' => __('From the <strong>Portfolio</strong> menu you can create new posts with images for your Portfolio or Gallery page. These posts are separate from your blog posts, and you can display them on the Gallery page or in homepage widgets.', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="post-new.php?post_type=portfolio_item" target="_blank">Create a New Portfolio Post »</a>'
        ),
    ),
    'support' => array(
        'header' => __('Need one-to-one Assistance?', 'wpzoom'),
        'content' => __('Need help setting up your theme or have a question? Get in touch with our Support Team. We\'d love the opportunity to help you.', 'wpzoom'),
        'actions' => array(
            '<a class="button button-primary" href="https://www.wpzoom.com/support/tickets/" target="_blank">Open Support Desk »</a>'
        ),
    )
);