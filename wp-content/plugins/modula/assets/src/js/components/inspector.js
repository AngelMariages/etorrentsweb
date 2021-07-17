/**
 * WordPress dependencies
 */

const { __ } = wp.i18n;
const { Component, Fragment, useState } = wp.element;
const { InspectorControls } = wp.editor;
const { SelectControl, Button, PanelBody, PanelRow, ColorPicker, ColorControl, FontSizePicker, Radio, RadioGroup, RadioControl, CustomGradientPicker, ColorPalette, ContrastChecker, RangeControl  } = wp.components;



/**
 * Inspector controls
 */
export default class Inspector extends Component {

	constructor( props ) {
		super( ...arguments );
	}

	selectOptions() {
		let options = [ { value: 0, label: __( 'none' ) } ];

		this.props.galleries.forEach(function( gallery ) {
			if( gallery.title.rendered.length == 0 ) {
				options.push( { value: gallery.id, label: __( 'Unnamed Gallery', 'modula-pro' ) + gallery.id } );
			} else {
				options.push( { value: gallery.id, label: gallery.title.rendered } );
			}
		});

		return options;
	}

	render() {
		const { attributes, setAttributes, galleries } = this.props;
		const { id, fontSize, buttonSize, buttonTextColor, buttonBackgroundColor, buttonHoverTextColor, buttonHoverBackgroundColor, borderWidth, borderRadius, borderColor, borderType } = attributes;
        const fontSizes = [
                {
                    name: __( 'Small' ),
                    slug: 'small',
                    size: 12,
                },
                {
                    name: __( 'Medium'),
                    slug: 'medium',
                    size: 18,
                },
                {
                    name: __( 'Big' ),
                    slug: 'big',
                    size: 26,
                },
        ];


        const fallbackFontSize = 16;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Gallery Settings','modula-pro' ) } initialOpen={ true }>
						{ galleries.length === 0 && (
							<Fragment>
								<p>{ __( 'You don\'t seem to have any galleries.','modula-pro' ) }</p>
								<Button href={ modulaVars.adminURL + 'post-new.php?post_type=modula-gallery' } target="_blank" isDefault>{ __( 'Add New Gallery','modula-pro' ) }</Button>
							</Fragment>
						)}

						{ galleries.length > 0 && (
							<Fragment>
								<SelectControl
									key={id}
									label={ __( 'Select Gallery' ) }
									value={ id }
									options={ this.selectOptions() }
									onChange={ ( value ) => this.props.onIdChange( parseInt( value ) ) }
								/>
								{ id != 0 && (
									<Button target="_blank" href={ modulaVars.adminURL + 'post.php?post=' + id + '&action=edit' } isDefault>{ __( 'Edit gallery','modula-pro' ) }</Button>
								) }
							</Fragment>
						)}

					</PanelBody>
                    <PanelBody title={ __( 'Button Style Settings','modula-pro') } initialOpen={ true }>
                        <h3> { __( 'Button Font Size') } </h3>
                        <FontSizePicker
                            fontSizes={ fontSizes }
                            value={ fontSize }
                            fallbackFontSize={ fallbackFontSize }
                            onChange={ ( newFontSize ) => {
                                setAttributes( { fontSize: newFontSize } );
                            } }
                        />

                        <RadioControl
                            label={__("Button Size",'modula-pro')}
                            selected={ buttonSize }
                            options={[
                            { label: "Small", value: "modula-link-button-small" },
                            { label: "Medium", value: "modula-link-button-medium" },
                            { label: "Large", value: "modula-link-button-large" },
                            { label: "X-Large", value: "modula-link-button-x-large" }
                            ]}
                            onChange={ buttonSize => setAttributes({ buttonSize })}
                        />

                        <h4>{__('Button Text Color','modula-pro')} </h4>
                        <ColorPicker
                                    color={ buttonTextColor }
                                    onChangeComplete={ ( buttonTextColor ) => setAttributes( { buttonTextColor } ) }
                                    disableAlpha
                        />

                        <h4>{__('Background Color','modula-pro')} </h4>
                        <ColorPicker
                                    color={ buttonBackgroundColor }
                                    onChangeComplete={ ( buttonBackgroundColor ) => setAttributes( { buttonBackgroundColor } ) }
                                    disableAlpha
                        />

                        <h4> {__('Hover Text Color','modula-pro')} </h4>
                        <ColorPicker
                                    color={ buttonHoverTextColor }
                                    onChangeComplete={ ( buttonHoverTextColor ) => setAttributes( { buttonHoverTextColor } ) }
                                    disableAlpha
                        />

                        <h4> {__('Hover Background Color','modula-pro')} </h4>
                        <ColorPicker
                                    color={ buttonHoverBackgroundColor }
                                    onChangeComplete={ ( buttonHoverBackgroundColor ) => setAttributes( { buttonHoverBackgroundColor } ) }
                                    disableAlpha
                        />
                        <h2> {__('Border Settings','modula-pro')} </h2>
                        <RadioControl
                            label={ __("Border Type",'modula-pro') }
                            selected={ borderType }
                            options={[
                            { label: __( "Solid" ),  value: 'solid'  },
                            { label: __( "Double" ), value: 'double' },
                            { label: __( "Dotted" ), value: 'dotted' },
                            { label: __( "Ridge" ),  value: 'ridge'  }
                            ]}
                            onChange={ borderType => setAttributes({ borderType })}
                        />
                        <RangeControl
                            beforeIcon="arrow-left-alt2"
                            afterIcon="arrow-right-alt2"
                            label={ __("Border Width") }
                            value={ borderWidth }
                            onChange={borderWidth => setAttributes({ borderWidth })}
                            min={1}
                            max={10}
                        />
                        <h4> {__('Border Color','modula-pro')} </h4>
                        <ColorPicker
                                    color={ borderColor }
                                    onChangeComplete={ ( borderColor ) => setAttributes( { borderColor } ) }
                                    disableAlpha
                        />
                        <RangeControl
                            beforeIcon="arrow-left-alt2"
                            afterIcon="arrow-right-alt2"
                            label={ __( "Border Radius" ) }
                            value={borderRadius}
                            onChange={borderRadius => setAttributes({ borderRadius })}
                            min={1}
                            max={100}
                        />
                    </PanelBody>
				</InspectorControls>
			</Fragment>
		);
	}
}