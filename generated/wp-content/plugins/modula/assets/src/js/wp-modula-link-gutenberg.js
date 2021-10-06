/**
 * Internal dependencies
 */
import Edit from './components/edit';
import icons from './utils/icons';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

class ModulaLinkGutenberg  {

	constructor() {
		this.registerBlock();
	}

	registerBlock() {

		this.blockName = 'modula/link';

		this.blockAttributes = {
			id: {
				type: 'number',
				default: 0,
			},
			textAlignment: {
				type: 'string',
			},
			buttonText: {
				type: 'string',
			},
			fontSize: {
				type: 'number',
				default: 16
			},
			buttonSize: {
				type: 'string'
			},
			buttonTextColor: {
				type: 'object',
				default: '#000',
			},
			buttonBackgroundColor : {
				type: 'object',
				default: '#fff',
			},
			buttonHoverTextColor: {
				type: 'object',
				default: '#fff',
			},
			buttonHoverBackgroundColor : {
				type: 'object',
				default: '#000',
			},
			borderWidth: {
				type: 'number'
			},
			borderRadius: {
				type: 'number'
			},
			borderColor: {
				type: 'object',
				default: 'transparent'
			},
			borderType:{
				type: 'string',
				default: 'solid'
			}

		};

		registerBlockType( this.blockName, {
			title: modulaLinkVars.gutenbergLinkTitle,
			icon: icons.modula,
			description: __( 'Make your galleries stand out.','modula-pro' ),
			keywords: [
				__( 'gallery' ),
				__( 'modula' ),
				__( 'link' ),
			],
			category: 'common',
			supports: {
				align: [ 'wide', 'full' ],
				customClassName: false,
			},
			attributes: this.blockAttributes,
			edit: Edit,
			save() {
				// Rendering in PHP
				return null;
			},
		} );

	}

}

let modulaLinkGutenberg = new ModulaLinkGutenberg();