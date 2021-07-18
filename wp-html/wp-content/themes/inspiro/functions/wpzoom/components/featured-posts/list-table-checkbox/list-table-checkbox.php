<?php

class WPZOOM_List_Table_Checkbox_Option_Type {

	public $type = 'checkbox';
	public $context = 'list_table';
	public $option;
	protected $component_uri = '';

	public function __construct( $option, $component_uri ) {

		$this->option        = $option;
		$this->component_uri = $component_uri;
		$this->option        = wp_parse_args( $option, $this->get_defaults() );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

		add_action( $this->get_ajax_action_name(), array(
			$this,
			'ajax_callback'
		) );

		add_filter( 'manage_edit-' . $this->option['post_type'] . '_columns', array(
			$this,
			'get_column_header'
		), 2 );
		add_action( 'manage_' . $this->option['post_type'] . '_posts_custom_column', array(
			$this,
			'get_column_content'
		), 10, 2 );
	}

	public function get_css_uri( $name = '' ) {
		return $this->get_assets_uri( 'css' ) . $name;
	}

	public function get_assets_uri( $name = '' ) {
		return trailingslashit( trailingslashit( $this->component_uri ) . 'assets/' . $name );
	}

	public function get_js_uri( $name = '' ) {
		return $this->get_assets_uri( 'js' ) . $name;
	}

	public function admin_enqueue() {
		wp_enqueue_style(
			$this->get_action_name(),
			$this->get_css_uri( 'style.css' ),
			array(),
			WPZOOM::$themeVersion
		);
		wp_enqueue_script(
			$this->get_action_name(),
			$this->get_js_uri( 'list-table-checkbox.js' ),
			array(
				'jquery',
				'wp-util'
			),
			WPZOOM::$themeVersion,
			true
		);
	}

	public function get_ajax_action_name() {
		return sprintf( 'wp_ajax_%s', $this->get_action_name() );
	}

	public function get_action_name() {
		return sprintf( '%s_%s', $this->type, $this->context );
	}

	public function render( $post_id ) {

		$out = sprintf( '<div class="list-table-checkbox-wrapper" data-action-name='.$this->get_action_name().' data-nonce-value="'.wp_create_nonce( $this->get_ajax_action_name().'-'.$post_id).'">' );
		$out .= '<p>';
		$out .= sprintf( '<input type="checkbox" name="%s" value="1" %s  class="list-table-checkbox"/>', $this->option['name'], checked( $this->get( $post_id ), true, false ) );
		$out .= sprintf( '<input type="hidden" name="%s" value="%s" />', 'post_id', $post_id );
		$out .= '<span class="dashicons dashicons-star-' . ( $this->get( $post_id ) ? 'filled' : 'empty' ) . '"></span>';
		$out .= '</p>';
		$out .= '</div>';

		return $out;
	}

	public function get_menu_order_limit( $limit = 'MIN', $post_type ) {

		global $wpdb;

		$limit = in_array( $limit, array( 'MAX', 'MIN' ) ) ? $limit : 'MIN';

		$sql = "SELECT $limit(menu_order) FROM $wpdb->posts
				WHERE post_status = 'publish' AND post_type = %s";

		return $wpdb->get_var( $wpdb->prepare( $sql, $post_type ) );

	}

	public function ajax_callback() {

		if ( ! empty( $_POST['post_id'] ) &&
		     ! empty( $_POST[ $this->get_action_name() ] ) &&
		     wp_verify_nonce( $_POST[ $this->get_action_name() ], $this->get_ajax_action_name().'-'.$_POST['post_id'] )
		) {
			$post_id   = $_POST['post_id'];
			$value     = $_POST[ $this->option['name'] ];
			$post_type = get_post_type( $post_id );
			$max_order = $this->get_menu_order_limit( 'MAX', $post_type );
			update_metadata( 'post', $post_id, $this->option['name'], $value );
			wp_update_post( array(
				'ID'         => $post_id,
				'menu_order' => $max_order + 1
			) );

			wp_send_json_success( array(
				$post_id,
				$this->option['name'],
				$value
			) );
		}

		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}


	public function get( $post_id ) {
		$is_meta = metadata_exists( 'post', $post_id, $this->option['name'] );

		return $is_meta ?
			wp_validate_boolean( get_metadata( 'post', $post_id, $this->option['name'], true ) ) :
			$this->option['value'];
	}

	public function get_column_header( $columns ) {
		$columns[ $this->option['id'] ] = $this->option['title'];

		return $columns;
	}

	public function get_column_content( $column, $post_id ) {

		if ( $column === $this->option['id'] ) {
			echo $this->render( $post_id );
		}
	}

	public function get_defaults() {
		return array(
			'title'     => 'Checkbox',
			'desc'      => 'Description',
			'value'     => true,
			'type'      => 'checkbox',
			'name'      => $this->option['id'],
			'context'   => $this->context,
			'show'      => true,
			'post_type' => 'post'
		);
	}
}