<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$admin_url           = admin_url();

$options = array(

    'category'     => array(
        'label'   => esc_html__( 'Display From', 'fw' ),
        'desc'    => esc_html__( 'Select a category', 'fw' ),
        'type'    => 'select',
        'value'   => '',
        'choices' => fw_get_category_term_list(),
    ),

    'posts_number' => array(
        'label' => esc_html__( 'Number of Posts', 'fw' ),
        'desc'  => esc_html__( 'Enter the number of posts to display. Ex: 3, 6, 9', 'fw' ),
        'type'  => 'short-text',
        'value' => '3'
    ),

    'date' => array(
        'type'  => 'switch',
        'label'   => __( 'Post Date', 'fw' ),
        'value' => 'date_show',
        'right-choice' => array(
            'value' => 'date_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'date_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),

    'thumbnail' => array(
        'type'  => 'switch',
        'label'   => __( 'Post Thumbnail', 'fw' ),
        'value' => 'thumbnail_show',
        'right-choice' => array(
            'value' => 'thumbnail_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'thumbnail_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),


    'excerpt' => array(
        'type'  => 'switch',
        'label'   => __( 'Post Excerpt', 'fw' ),
        'value' => 'excerpt_show',
        'right-choice' => array(
            'value' => 'excerpt_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'excerpt_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),

    'button' => array(
        'type'  => 'switch',
        'label'   => __( 'Read More Button', 'fw' ),
        'value' => 'button_show',
        'right-choice' => array(
            'value' => 'button_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'button_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),

    'button_label'  => array(
        'label' => '',
        'desc'  => __( 'Button Label', 'fw' ),
        'type'  => 'text',
        'value' => 'Read More'
    ),

);