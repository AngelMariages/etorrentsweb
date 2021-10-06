<?php

class FW_Color_Palette_New extends FW_Option_Type {
	private $option_type = 'color-palette';
	private $custom_choice_key = 'fw-custom';

	public function get_type() {
		return $this->option_type;
	}

	/**
	 * @internal
	 */
	protected function _enqueue_static( $id, $option, $data ) {
		$uri = get_template_directory_uri() . '/theme-includes/includes/option-types/' . $this->get_type() . '/static';

		wp_enqueue_style(
			'fw-option-' . $this->get_type(),
			$uri . '/css/style.css'
		);

		fw()->backend->option_type( 'color-picker' )->enqueue_static();

		wp_enqueue_script(
			'fw-option-' . $this->get_type(),
			$uri . '/js/scripts.js',
			array( 'jquery' ),
			'',
			true
		);
	}

	/**
	 * @internal
	 */
	protected function _render( $id, $option, $data ) {
		$option['choices'][ $this->custom_choice_key ] = '';

		return fw_render_view( get_template_directory() . '/theme-includes/includes/option-types/' . $this->get_type() . '/view.php', array(
			'id'                => $id,
			'option'            => $option,
			'data'              => $data,
			'custom_choice_key' => $this->custom_choice_key,
			'type'              => $this->get_type()
		) );
	}

	/**
	 * @internal
	 */
	protected function _get_value_from_input( $option, $input_value ) {

		/**
		 * In this method you receive $input_value (from form submit or whatever)
		 * and must return correct and safe value that will be stored in database.
		 *
		 * $input_value can be null.
		 * In this case you should return default value from $option['value']
		 */

		if ( isset($input_value['id']) && $input_value['id'] == $this->custom_choice_key) {
				$input_value['color'] = isset( $input_value['color'] ) ? $input_value['color'] : '';
		}
		elseif ( is_null( $input_value ) ) {
			$option['choices'] = array_map( 'strtolower', $option['choices'] );

			$value  = !is_array($option['value']) ? strtolower( $option['value'] ) : $option['value']['id'];

			if(!is_array($option['value'])){
				if ( in_array( $value, $option['choices'] ) ) {
					$input_value['color'] = $value;
					$input_value['id']    = array_search( $value, $option['choices'] );
				} else {
					$input_value['color'] = $value;
					$input_value['id']    = $this->custom_choice_key;
				}
			}
			else
			{
				if ( array_key_exists( $value, $option['choices'] ) ) {
					$input_value['color'] = $option['choices'][$value];
					$input_value['id']    = $value;
				} else {
					$input_value['color'] = $option['value']['color'];
					$input_value['id']    = $value;
				}
			}
		}
		else {
			if ( isset( $input_value['color'] ) ) {
				$input_value['color'] = '';
			}

			else {
				$input_value = array( 'color' => '' );
			}
		}

		return $input_value;
	}

	/**
	 * @internal
	 */
	protected function _get_defaults() {
		/**
		 * These are default parameters that will be merged with option array.
		 * They makes possible that any option has
		 * only one required parameter array('type' => 'new').
		 */

		return array(
			'value' => ''
		);
	}
}

FW_Option_Type::register( 'FW_Color_Palette_New' );