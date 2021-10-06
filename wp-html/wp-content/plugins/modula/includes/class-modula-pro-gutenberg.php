<?php

class Modula_Pro_Gutenberg {

	function __construct() {

		// Return early if this function does not exist.
		if ( !function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'register_block_type' ) );
		add_action( 'init', array( $this, 'generate_js_vars' ) );
	}

	public function register_block_type() {

		wp_register_script( 'modula-link-gutenberg', MODULA_PRO_URL . 'assets/js/admin/modula_link.js', array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-data' ) );
		wp_register_style( 'modula-link-gutenberg', MODULA_PRO_URL . 'assets/css/modula-link-gutenberg.css', array(), true );

		register_block_type(
			'modula/link',
			array(
				'render_callback' => array( $this, 'render_modula_gallery' ),
				'editor_script'   => 'modula-link-gutenberg',
				'editor_style'    => 'modula-link-gutenberg',
			)
		);

	}

	public function generate_js_vars() {

		wp_localize_script(
			'modula-link-gutenberg',
			'modulaLinkVars',
			apply_filters('modula_link_gutenberg_vars', array(
				'adminURL' => admin_url(),
				'ajaxURL'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'modula_nonce' ),
				'gutenbergLinkTitle' => esc_html__( 'Modula Link', 'modula-pro')
			) )
		);
	}

	public function render_modula_gallery( $atts ) {
		if ( !isset( $atts['id'] ) ) {
			return;
		}

		if ( !isset( $atts['align'] ) ) {
			$atts['align'] = '';
		}

		$font_size     = isset( $atts['fontSize'] ) ? 'font-size:' . absint( $atts['fontSize'] ) . 'px;' : '';
		$background    = isset( $atts['buttonBackgroundColor']['hex'] ) ? 'background:' . sanitize_hex_color( $atts['buttonBackgroundColor']['hex'] ) . ';' : '';
		$color         = isset( $atts['buttonTextColor']['hex'] ) ? 'color: ' . sanitize_hex_color( $atts['buttonTextColor']['hex'] ) . ';' : '';
		$border_width  = isset( $atts['borderWidth'] ) ? absint( $atts['borderWidth'] ) . 'px' : '';
		$border_type   = isset( $atts['borderType'] ) ? esc_html( $atts['borderType'] ) : '';
		$border_color  = isset( $atts['borderColor']['hex'] ) ? sanitize_hex_color( $atts['borderColor']['hex'] ) : '';
		$border_radius = isset( $atts['borderRadius'] ) ? absint( $atts['borderRadius'] ) . '%' : '';
		$text_align    = isset( $atts['textAlignment'] ) ? 'text-align: ' . esc_html( $atts['textAlignment'] ) . ';' : '';
		$text_color    = isset( $atts['buttonHoverTextColor']['hex'] ) ? 'color: ' . sanitize_hex_color( $atts['buttonHoverTextColor']['hex'] ) . ';' : '';
		$btn_hover_bcg = isset( $atts['buttonHoverBackgroundColor']['hex'] ) ? 'background: ' . sanitize_hex_color( $atts['buttonHoverBackgroundColor']['hex'] ) . ';' : '';

		$html = '<style>';
		$html .= "#jtg-link-" . absint( $atts['id'] ) . "{";
		$html .= "padding: 20px;";
		$html .= $font_size;
		$html .= $background;
		$html .= $color . ';';
		$html .= "border: " . $border_width . " " . $border_type . " " . $border_color . ";";
		$html .= 'border-radius: ' . $border_radius;
		$html .= '}';
		$html .= '#jtg-link-' . absint( $atts['id'] ) . ' {';
		$html .= 'width: 100%;';
		$html .= $text_align;
		$html .= '}';
		$html .= '#jtg-link-' . absint( $atts['id'] ) . ':hover{';
		$html .= $text_color;
		$html .= $btn_hover_bcg;
		$html .= '}';
		$html .= '</style>';


		return $html . '[modula-link id=' . absint( $atts['id'] ) . ' align=' . esc_attr( $atts['align'] ) . ']' . wp_kses_post( $atts['buttonText'] ) . '[/modula-link]';
	}


}

new Modula_Pro_Gutenberg();


