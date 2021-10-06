<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * @var string $id
 * @var  array $option
 * @var  array $data
 * @var  array $custom_choice_key
 */

{
	$wrapper_attr = $option['attr'];

	unset(
		$wrapper_attr['value'],
		$wrapper_attr['name']
	);
}

?>
<div <?php echo fw_attr_to_html( $wrapper_attr ) ?>>
	<div class="predefined">
		<?php
		if ( ! is_array( $data['value'] ) && array_search( $data['value'], $option['choices'] ) ) {
			$data['value'] = array( 'id'    => array_search( $data['value'], $option['choices'] ),
			                        'color' => $data['value']
			);
		}

		$data_palette = array(
			'value'       => isset( $data['value']['id'] ) ? $data['value']['id'] : '',
			'id_prefix'   => $data['id_prefix'] . $id . '-',
			'name_prefix' => $data['name_prefix'] . '[' . $id . ']'
		);

		$option_palette = array(
			'value'   => (string) $data_palette['value'],
			'choices' => $option['choices'],
			'attr'    => $option['attr']
		);

		echo fw_render_view( get_template_directory() . '/theme-includes/includes/option-types/' . $option['type'] . '/includes/palette-view.php', array(
			'id'                => 'id',
			'option'            => $option_palette,
			'data'              => $data_palette,
			'custom_choice_key' => $custom_choice_key
		) );
		?>
	</div>

	<div class="custom">
		<?php
		if ( ! is_array( $data['value'] ) && ! array_search( $data['value'], $option['choices'] ) ) {
			$data['value'] = array( 'id' => $custom_choice_key, 'color' => $data['value'] );
		}

		echo fw()->backend->option_type( 'color-picker' )->render(
			'color',
			array(),
			array(
				'value'       => ( isset( $data['value']['id'] ) && $data['value']['id'] == $custom_choice_key ) ? $data['value']['color'] : '',
				'id_prefix'   => $data['id_prefix'] . $id . '-',
				'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
			)
		);
		?>
	</div>
</div>