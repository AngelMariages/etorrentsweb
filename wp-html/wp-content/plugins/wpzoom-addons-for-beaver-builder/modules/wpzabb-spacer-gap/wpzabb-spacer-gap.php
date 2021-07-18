<?php

class WPZABBSpacerGap extends FLBuilderModule {

    /**
     * @method __construct
     */
    public function __construct()
    {
        parent::__construct(array(
            'name'            => __( 'Spacer / Gap', 'wpzabb' ),
            'description'     => __( 'A totally awesome module!', 'wpzabb' ),
            'category'      => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
            'dir'             => BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'spacer-gap/',
            'url'             => BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'spacer-gap/',
            'editor_export'   => true, // Defaults to true and can be omitted.
            'enabled'         => true, // Defaults to true and can be omitted.
            'partial_refresh' => false, // Defaults to false and can be omitted.
            'icon'            => 'minus.svg'
        ));
    }
}

FLBuilder::register_module('WPZABBSpacerGap', array(
    'spacer_gap_general'       => array( // Tab
        'title'         => __('General', 'wpzabb'), // Tab title
        'sections'      => array( // Tab Sections
            'spacer_gap_general'       => array( // Section
                'title'         => '', // Section Title
                'fields'        => array( // Section Fields
                    'desktop_space'   => array(
                        'type'          => 'text',
                        'label'         => __('Desktop', 'wpzabb'),
                        'size'          => '8',
                        'placeholder'   => '10',
                        'class'         => 'wpzabb-spacer-gap-desktop',
                        'description'   => 'px',
                        'help'          => __( 'This value will work for all devices.', 'wpzabb' )
                    ),
                    'medium_device'   => array(
                        'type'          => 'text',
                        'label'         => __('Medium Device ( Tabs )', 'wpzabb'),
                        'default'       => '',
                        'size'          => '8',
                        'class'         => 'wpzabb-spacer-gap-tab-landscape',
                        'description'   => 'px',
                    ),

                    'small_device'   => array(
                        'type'          => 'text',
                        'label'         => __('Small Device ( Mobile )', 'wpzabb'),
                        'default'       => '',
                        'size'          => '8',
                        'class'         => 'wpzabb-spacer-gap-mobile',
                        'description'   => 'px',
                    ),
                )
            )
        )
    )
));