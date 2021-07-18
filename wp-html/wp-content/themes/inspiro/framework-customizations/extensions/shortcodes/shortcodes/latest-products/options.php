<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$admin_url           = admin_url();


if ( ! post_type_exists( 'product' ) )  {

    $options = array(

        'category'     => array(
            'type'    => 'html-fixed',
            'width' => 'full',
            'label' => '',
            'html'  => 'Please install or activate <strong>WooCommerce</strong> plugin. <br/>',
        )
    );

} else {

$options = array(

    'category'     => array(
        'label'   => esc_html__( 'Display From', 'fw' ),
        'desc'    => esc_html__( 'Select a product category', 'fw' ),
        'type'    => 'select',
        'value'   => '',
        'choices' => fw_get_category_term_list( 'product' ),
    ),

    'posts_number' => array(
        'label' => esc_html__( 'Number of Products', 'fw' ),
        'desc'  => esc_html__( 'Enter the number of posts to display. Ex: 4, 8, 12', 'fw' ),
        'type'  => 'short-text',
        'value' => '8'
    ),


    'products_orderby'  => array(
        'label'   => __( 'Order by', 'fw' ),
        'type'    => 'select',
        'choices' => array(
            'date'      => __('Date', 'fw'),
            'price' => __( 'Price', 'fw' ),
            'rand'  => __( 'Random', 'fw' ),
            'sales'  => __( 'Sales', 'fw' ),
        )
    ),

    'products_order'  => array(
        'label'   => __( 'Order', 'fw' ),
        'type'    => 'select',
        'choices' => array(
            'desc'      => __('DESC', 'fw'),
            'asc' => __( 'ASC', 'fw' ),
        )
    ),

    'product_price' => array(
        'type'  => 'switch',
        'label'   => __( 'Product Price', 'fw' ),
        'value' => 'price_show',
        'right-choice' => array(
            'value' => 'price_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'price_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),

    'product_btn' => array(
        'type'  => 'switch',
        'label'   => __( 'Add to Cart Button', 'fw' ),
        'value' => 'price_show',
        'right-choice' => array(
            'value' => 'cart_show',
            'label' => __('Show', 'fw'),
        ),
        'left-choice' => array(
            'value' => 'cart_hide',
            'label' => __('Hide', 'fw'),
        ),
    ),

);

}