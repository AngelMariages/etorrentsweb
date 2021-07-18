<?php return array(

/* Framework Admin Menu */
'menu' => array(
    'portfolio' => array(
        'name' => __('Portfolio Options', 'wpzoom')
    )
),

/* Framework Admin Options */
'portfolio' => array(
    array("type"  => "preheader",
          "name"  => __("Permalinks", 'wpzoom'),
          "desc"  => array(
              __('Here you can edit URLs for Portfolio post type. Don\'t forget to flush rules after changing them.', 'wpzoom'),
              sprintf(__('Just open the <a href="%s">Permalinks</a> page and hit <b>Save Options</b>.', 'wpzoom'), admin_url( 'options-permalink.php' ))
          )),

    array("name"  => __("Portfolio post slug", 'wpzoom'),
          "id"    => "portfolio_root",
          "std"   => "project",
          "desc"  => sprintf( '<strong>%s</strong><br/><br/><strong>NOTICE: Do not use the same slug for both options.</strong><br/> <br/>The <strong>"portfolio"</strong> term is the default value for option below, so in order to use it for individual posts, first set something unique to the option below, like "portfolio-category". ', home_url( option::get( 'portfolio_root' ) ) ),
          "type"  => "text"),

    array("name"  => __("Portfolio category (taxonomy) slug", 'wpzoom'),
          "id"    => "portfolio_base",
          "std"   => "",
          "desc"  => sprintf( '<strong>%s%s</strong>', home_url( trailingslashit( option::get( 'portfolio_root' ) . '/' . option::get( 'portfolio_base' ) ) ), '%portfolio%' ),
          "type"  => "text"),

)

);