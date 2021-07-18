<?php
/**
 * @package WPZOOM
 */

/**
 * Class WPZOOM_Customizer_Control_Responsive
 *
 * Responsive control to manage values for multiple devices.
 *
 * @since 1.8.6
 */
class WPZOOM_Customizer_Control_Responsive extends WPZOOM_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'zoom_responsive';

	/**
	 * The responsive type.
	 *
	 * @access public
	 * @var string
	 */
	public $responsive = true;

	/**
	 * The control type.
	 *
	 * @access public
	 * @var array
	 */
	public $units = array();

	/**
	 * WPZOOM_Customizer_Control_Responsive constructor.
	 *
	 * @since 1.8.6
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string               $id
	 * @param array                $args
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
	    parent::__construct( $manager, $id, $args );

	    // Ensure this instance maintains the proper type value.
	    $this->type = 'zoom_responsive';
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-slider' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		$val = $this->value();
		$val = maybe_unserialize( $val );

		if ( ! is_array( $val ) || is_numeric( $val ) ) {
			$val = array(
				'desktop'      => $val,
				'tablet'       => isset( $this->json['default']['tablet'] ) ? $this->json['default']['tablet'] : '',
				'mobile'       => isset( $this->json['default']['mobile'] ) ? $this->json['default']['mobile'] : '',
				'desktop-unit' => '',
				'tablet-unit'  => '',
				'mobile-unit'  => '',
			);
		}

		$this->json['id']         = $this->id;
		$this->json['choices']    = $this->choices;
		$this->json['link']       = $this->get_link();
		$this->json['label']      = esc_html( $this->label );
		$this->json['value']      = $val;
		$this->json['units']      = $this->units;
		$this->json['responsive'] = $this->responsive;

		$this->json['input_attrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['input_attrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() { ?>
		<div class="zoom-responsive-container">

			<# if ( data.responsive ) { #>
			<ul class="zoom-responsive-btns">
				<li class="desktop active">
					<button type="button" class="preview-desktop active" data-device="desktop">
						<i class="dashicons dashicons-desktop"></i>
					</button>
				</li>
				<li class="tablet">
					<button type="button" class="preview-tablet" data-device="tablet">
						<i class="dashicons dashicons-tablet"></i>
					</button>
				</li>
				<li class="mobile">
					<button type="button" class="preview-mobile" data-device="mobile">
						<i class="dashicons dashicons-smartphone"></i>
					</button>
				</li>
			</ul>
			<# } #>

			<# if ( data.label ) { #>
				<label class="customize-control-title" for="{{ data.id }}">{{{ data.label }}}</label>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } 

			value_desktop = '';
			value_tablet  = '';
			value_mobile  = '';

			if ( data.value['desktop'] ) { 
				value_desktop = data.value['desktop'];
			} 

			if ( data.value['tablet'] ) { 
				value_tablet = data.value['tablet'];
			} 

			if ( data.value['mobile'] ) { 
				value_mobile = data.value['mobile'];
			} #>

			<div class="zoom-responsive-wrapper">

				<# if ( data.responsive ) { #>
					<div id="range_{{ data.id }}-desktop" class="zoom-range-container desktop active">
					    <input
					    	{{{ data.input_attrs }}}
					        id="input_{{ data.id }}-desktop"
					        data-id="desktop"
					        class="zoom-range-input"
					        type="number"
					        value="{{ value_desktop }}"
					        data-value="{{ value_desktop }}"
					    />
					    <div id="slider_{{ data.id }}-desktop" class="zoom-range-slider"></div>
						<select class="zoom-responsive-select desktop" data-id='desktop-unit' id="{{ data.id }}-desktop" <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
						<# _.each( data.units, function( value, key ) { #>
							<option value="{{{ key }}}" <# if ( data.value['desktop-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
						<# }); #>
						</select>
					</div>

					<div id="range_{{ data.id }}-tablet" class="zoom-range-container tablet">
					    <input
					    	{{{ data.input_attrs }}}
					        id="input_{{ data.id }}-tablet"
					        data-id="tablet"
					        class="zoom-range-input"
					        type="number"
					        value="{{ value_tablet }}"
					        data-value="{{ value_tablet }}"
					    />
					    <div id="slider_{{ data.id }}-tablet" class="zoom-range-slider"></div>
						<select class="zoom-responsive-select tablet" data-id='tablet-unit' id="{{ data.id }}-tablet" <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
						<# _.each( data.units, function( value, key ) { #>
							<option value="{{{ key }}}" <# if ( data.value['tablet-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
						<# }); #>
						</select>
					</div>

					<div id="range_{{ data.id }}-mobile" class="zoom-range-container mobile">
					    <input
					    	{{{ data.input_attrs }}}
					        id="input_{{ data.id }}-mobile"
					        data-id="mobile"
					        class="zoom-range-input"
					        type="number"
					        value="{{ value_mobile }}"
					        data-value="{{ value_mobile }}"
					    />
					    <div id="slider_{{ data.id }}-mobile" class="zoom-range-slider"></div>
						<select class="zoom-responsive-select mobile" data-id='mobile-unit' id="{{ data.id }}-mobile" <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
						<# _.each( data.units, function( value, key ) { #>
							<option value="{{{ key }}}" <# if ( data.value['mobile-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
						<# }); #>
						</select>
					</div>

				<# } else { #>
					<input {{{ data.input_attrs }}} data-id='desktop' id="{{ data.id }}-desktop" class="zoom-responsive-input zoom-non-reponsive desktop active" type="number" value="{{ value_desktop }}"/>
					<select class="zoom-responsive-select zoom-non-reponsive desktop" data-id='desktop-unit' id="{{ data.id }}-desktop" <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
					<# _.each( data.units, function( value, key ) { #>
						<option value="{{{ key }}}" <# if ( data.value['desktop-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
					<# }); #>
					</select>
				<# } #>
			</div>

		</div>
		<?php
	}
}
