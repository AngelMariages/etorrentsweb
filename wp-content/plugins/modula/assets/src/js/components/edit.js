import Inspector from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { withSelect } = wp.data;
const { SelectControl, Button, Spinner, Toolbar, IconButton } = wp.components;
const { BlockControls,  RichText, AlignmentToolbar, } = wp.editor;


class ModulaLinkEdit extends Component {

    constructor( props ) {
        super( ...arguments );
        this.props.attributes.status = 'ready';

    }

	componentDidMount() {
		if( this.props.attributes.id !== 0 ) {
			this.onIdChange( this.props.attributes.id );
		}
	}

	onIdChange( id ) {
		this.props.setAttributes( { status: 'loading' } );
        this.onGalleryLoaded( id );
	}

	onGalleryLoaded( id  ) {

		this.props.setAttributes( { id: id, status: 'ready' } );
		return;

	}


	selectOptions() {
		let options = [ { value: 0, label: __( 'select a gallery','modula-pro' ) } ];

		this.props.galleries.forEach(function( gallery ) {
			if( gallery.title.rendered.length == 0 ) {
				options.push( { value: gallery.id, label: __('Unnamed Gallery ','modula-pro') + gallery.id } );
			} else {
				options.push( { value: gallery.id, label: gallery.title.rendered } );
			}
		});

		return options;
	}

    render() {
 		const { attributes, galleries, setAttributes } = this.props;
		const { id, images, status, textAlignment, buttonText, fontSize, buttonSize, buttonTextColor, buttonBackgroundColor, buttonHoverTextColor, buttonHoverBackgroundColor, borderWidth, borderColor, borderType, borderRadius } = attributes;
        const className = `modula-link-button-${id}`;
		if( id == 0  ) {
			return [
				<Fragment>
                    <Inspector onIdChange={ ( id ) => this.onIdChange( id ) } { ...this.props } />
					<div className="modula-block-preview">
						<div className="modula-block-preview__content">
							<div className="modula-block-preview__logo"></div>
							{ ( galleries.length === 0 ) && (
								<Fragment>
									<p>{ __( 'You don\'t seem to have any galleries.','modula-pro' ) }</p>
									<Button href={ modulaLinkVars.adminURL + 'post-new.php?post_type=modula-gallery' } target="_blank" isDefault>{ __( 'Add New Gallery','modula-pro' ) }</Button>
								</Fragment>
							)}
							{ ( galleries.length > 0 ) && (
								<Fragment>
									<SelectControl
										key={id}
										value={ id }
										options={ this.selectOptions() }
										onChange={ ( value ) => this.onIdChange( parseInt( value ) ) }
									/>
								</Fragment>
							)}
						</div>
					</div>

				</Fragment>
			];
		}
        return [
            <div>
                <style dangerouslySetInnerHTML={{__html: `
                    #${className}{
                        font-size: ${fontSize}px;
                        background: ${buttonBackgroundColor.hex};
                        padding: 20px;
                        color: ${buttonTextColor.hex};
                        border: ${borderWidth}px ${borderType} ${borderColor.hex};
                        border-radius: ${borderRadius}%;
                    }
                    #${className} span {
                        width: 100%;
                        text-align: ${textAlignment};
                    }
                    #${className}:hover{
                        color: ${buttonHoverTextColor.hex};
                        background: ${buttonHoverBackgroundColor.hex};
                    }
                `}}/>
                <Inspector onIdChange={ ( id ) => this.onIdChange( id ) } { ...this.props } />
                <BlockControls>
                    <AlignmentToolbar
                        value={ textAlignment }
                        onChange={ textAlignment => setAttributes( { textAlignment } ) }
                    />
                </BlockControls>
                <Button id={className}
                        className={buttonSize}
                        isPrimary
                        style={{width: "auto", minWidth: "fit-content"}}
                > <RichText key="editable"
                            tagName="span"
                            placeholder={__( "Enter your message here",'modula-pro' ) }
                            value={buttonText}
                            onChange={ buttonText => setAttributes({ buttonText })}
                            /> 
                </Button>

            </div>
        ]
    }
}

export default withSelect( ( select, props ) => {
	const { getEntityRecords } = select( 'core' );
	const query = {
		post_status: 'publish',
		per_page: -1,
	}

	return {
		galleries: getEntityRecords( 'postType', 'modula-gallery', query ) || [],
	};
} )( ModulaLinkEdit );

