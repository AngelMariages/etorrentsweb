/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _inspector = __webpack_require__(2);

var _inspector2 = _interopRequireDefault(_inspector);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var __ = wp.i18n.__;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var withSelect = wp.data.withSelect;
var _wp$components = wp.components,
    SelectControl = _wp$components.SelectControl,
    Button = _wp$components.Button,
    Spinner = _wp$components.Spinner,
    Toolbar = _wp$components.Toolbar,
    IconButton = _wp$components.IconButton;
var _wp$editor = wp.editor,
    BlockControls = _wp$editor.BlockControls,
    RichText = _wp$editor.RichText,
    AlignmentToolbar = _wp$editor.AlignmentToolbar;

var ModulaLinkEdit = function (_Component) {
	_inherits(ModulaLinkEdit, _Component);

	function ModulaLinkEdit(props) {
		_classCallCheck(this, ModulaLinkEdit);

		var _this = _possibleConstructorReturn(this, (ModulaLinkEdit.__proto__ || Object.getPrototypeOf(ModulaLinkEdit)).apply(this, arguments));

		_this.props.attributes.status = 'ready';

		return _this;
	}

	_createClass(ModulaLinkEdit, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			if (this.props.attributes.id !== 0) {
				this.onIdChange(this.props.attributes.id);
			}
		}
	}, {
		key: 'onIdChange',
		value: function onIdChange(id) {
			this.props.setAttributes({ status: 'loading' });
			this.onGalleryLoaded(id);
		}
	}, {
		key: 'onGalleryLoaded',
		value: function onGalleryLoaded(id) {

			this.props.setAttributes({ id: id, status: 'ready' });
			return;
		}
	}, {
		key: 'selectOptions',
		value: function selectOptions() {
			var options = [{ value: 0, label: __('select a gallery', 'modula-pro') }];

			this.props.galleries.forEach(function (gallery) {
				if (gallery.title.rendered.length == 0) {
					options.push({ value: gallery.id, label: __('Unnamed Gallery ', 'modula-pro') + gallery.id });
				} else {
					options.push({ value: gallery.id, label: gallery.title.rendered });
				}
			});

			return options;
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			var _props = this.props,
			    attributes = _props.attributes,
			    galleries = _props.galleries,
			    setAttributes = _props.setAttributes;
			var id = attributes.id,
			    images = attributes.images,
			    status = attributes.status,
			    textAlignment = attributes.textAlignment,
			    buttonText = attributes.buttonText,
			    fontSize = attributes.fontSize,
			    buttonSize = attributes.buttonSize,
			    buttonTextColor = attributes.buttonTextColor,
			    buttonBackgroundColor = attributes.buttonBackgroundColor,
			    buttonHoverTextColor = attributes.buttonHoverTextColor,
			    buttonHoverBackgroundColor = attributes.buttonHoverBackgroundColor,
			    borderWidth = attributes.borderWidth,
			    borderColor = attributes.borderColor,
			    borderType = attributes.borderType,
			    borderRadius = attributes.borderRadius;

			var className = 'modula-link-button-' + id;
			if (id == 0) {
				return [React.createElement(
					Fragment,
					null,
					React.createElement(_inspector2.default, _extends({ onIdChange: function onIdChange(id) {
							return _this2.onIdChange(id);
						} }, this.props)),
					React.createElement(
						'div',
						{ className: 'modula-block-preview' },
						React.createElement(
							'div',
							{ className: 'modula-block-preview__content' },
							React.createElement('div', { className: 'modula-block-preview__logo' }),
							galleries.length === 0 && React.createElement(
								Fragment,
								null,
								React.createElement(
									'p',
									null,
									__('You don\'t seem to have any galleries.', 'modula-pro')
								),
								React.createElement(
									Button,
									{ href: modulaLinkVars.adminURL + 'post-new.php?post_type=modula-gallery', target: '_blank', isDefault: true },
									__('Add New Gallery', 'modula-pro')
								)
							),
							galleries.length > 0 && React.createElement(
								Fragment,
								null,
								React.createElement(SelectControl, {
									key: id,
									value: id,
									options: this.selectOptions(),
									onChange: function onChange(value) {
										return _this2.onIdChange(parseInt(value));
									}
								})
							)
						)
					)
				)];
			}
			return [React.createElement(
				'div',
				null,
				React.createElement('style', { dangerouslySetInnerHTML: { __html: '\n                    #' + className + '{\n                        font-size: ' + fontSize + 'px;\n                        background: ' + buttonBackgroundColor.hex + ';\n                        padding: 20px;\n                        color: ' + buttonTextColor.hex + ';\n                        border: ' + borderWidth + 'px ' + borderType + ' ' + borderColor.hex + ';\n                        border-radius: ' + borderRadius + '%;\n                    }\n                    #' + className + ' span {\n                        width: 100%;\n                        text-align: ' + textAlignment + ';\n                    }\n                    #' + className + ':hover{\n                        color: ' + buttonHoverTextColor.hex + ';\n                        background: ' + buttonHoverBackgroundColor.hex + ';\n                    }\n                ' } }),
				React.createElement(_inspector2.default, _extends({ onIdChange: function onIdChange(id) {
						return _this2.onIdChange(id);
					} }, this.props)),
				React.createElement(
					BlockControls,
					null,
					React.createElement(AlignmentToolbar, {
						value: textAlignment,
						onChange: function onChange(textAlignment) {
							return setAttributes({ textAlignment: textAlignment });
						}
					})
				),
				React.createElement(
					Button,
					{ id: className,
						className: buttonSize,
						isPrimary: true,
						style: { width: "auto", minWidth: "fit-content" }
					},
					' ',
					React.createElement(RichText, { key: 'editable',
						tagName: 'span',
						placeholder: __("Enter your message here", 'modula-pro'),
						value: buttonText,
						onChange: function onChange(buttonText) {
							return setAttributes({ buttonText: buttonText });
						}
					})
				)
			)];
		}
	}]);

	return ModulaLinkEdit;
}(Component);

exports.default = withSelect(function (select, props) {
	var _select = select('core'),
	    getEntityRecords = _select.getEntityRecords;

	var query = {
		post_status: 'publish',
		per_page: -1
	};

	return {
		galleries: getEntityRecords('postType', 'modula-gallery', query) || []
	};
})(ModulaLinkEdit);

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});
var icons = {};

icons.modula = React.createElement(
	"svg",
	{ xmlns: "http://www.w3.org/2000/svg", x: "0px", y: "0px", viewBox: "364 242.9 312.2 357" },
	React.createElement(
		"g",
		null,
		React.createElement("path", { d: "M528.1,242.9c8.5,16.9,17,33.8,25.6,50.6c13.4,26.4,26.9,52.7,39.9,79.7c-41.8-23.3-83.6-46.7-125.4-70.1 c0.3-1.9,1.7-2.6,2.7-3.5c17.7-17.7,35.4-35.4,53.1-53c1.1-1.1,2.6-2,3.1-3.7C527.4,242.9,527.8,242.9,528.1,242.9z" }),
		React.createElement("path", { d: "M602.3,463.3c11.3-6.9,22.6-13.9,33.9-20.8c5.5-3.4,11.1-6.7,16.5-10.3c2.2-1.4,2.9-1.1,3.5,1.5 c6.4,25.3,13,50.6,19.6,75.8c0.6,2.2,1,3.7-2.4,3.5c-46.7-2.1-93.5-4.1-140.2-6.1c-0.2,0-0.3-0.1-0.5-0.2c0.5-1.7,2.1-2,3.3-2.7 c20-12.3,39.9-24.7,60-36.8c3.4-2.1,5.1-3.7,4.8-8.5c-1.4-21.3-1.8-42.6-2.6-63.9c-0.9-24.1-1.8-48.3-2.8-72.4 c-0.2-6.1-0.2-6.1,5.5-4.6c23.8,6.2,47.6,12.5,71.5,18.5c3.9,1,4.2,1.9,2.1,5.4c-23.4,38.5-46.7,77.1-70,115.7c-1,1.7-2,3.4-3,5.1 C601.7,462.8,602,463,602.3,463.3z" }),
		React.createElement("path", { d: "M372.8,326.9c48,2.6,95.8,5.1,143.9,7.7c-0.9,2-2.5,2.3-3.7,3.1c-38.6,23.2-77.3,46.4-115.9,69.6c-3,1.8-4.3,2.6-5.4-1.9 c-5.9-24.9-12.2-49.7-18.3-74.6C373.1,329.6,373,328.4,372.8,326.9z" }),
		React.createElement("path", { d: "M517.6,599.9c-23.2-43.7-45.9-86.6-69.2-130.5c2.3,1.2,3.5,1.8,4.7,2.4c39.8,21.5,79.5,43.1,119.3,64.5 c3.2,1.7,4.1,2.5,1,5.6c-17.7,17.8-35.2,35.9-52.8,53.9C519.7,596.9,518.9,598.2,517.6,599.9z" }),
		React.createElement("path", { d: "M364.9,505.1c26.6-40.5,53.1-80.8,79.7-121.3c1.3,1.3,0.9,2.5,0.9,3.6c0,46-0.1,92-0.1,137.9c0,3.1-0.2,4.5-4,3.3 c-24.9-7.7-49.9-15.2-74.9-22.8C366,505.8,365.7,505.5,364.9,505.1z" })
	)
);

exports.default = icons;

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */

var __ = wp.i18n.__;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment,
    useState = _wp$element.useState;
var InspectorControls = wp.editor.InspectorControls;
var _wp$components = wp.components,
    SelectControl = _wp$components.SelectControl,
    Button = _wp$components.Button,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    ColorPicker = _wp$components.ColorPicker,
    ColorControl = _wp$components.ColorControl,
    FontSizePicker = _wp$components.FontSizePicker,
    Radio = _wp$components.Radio,
    RadioGroup = _wp$components.RadioGroup,
    RadioControl = _wp$components.RadioControl,
    CustomGradientPicker = _wp$components.CustomGradientPicker,
    ColorPalette = _wp$components.ColorPalette,
    ContrastChecker = _wp$components.ContrastChecker,
    RangeControl = _wp$components.RangeControl;

/**
 * Inspector controls
 */

var Inspector = function (_Component) {
  _inherits(Inspector, _Component);

  function Inspector(props) {
    _classCallCheck(this, Inspector);

    return _possibleConstructorReturn(this, (Inspector.__proto__ || Object.getPrototypeOf(Inspector)).apply(this, arguments));
  }

  _createClass(Inspector, [{
    key: 'selectOptions',
    value: function selectOptions() {
      var options = [{ value: 0, label: __('none') }];

      this.props.galleries.forEach(function (gallery) {
        if (gallery.title.rendered.length == 0) {
          options.push({ value: gallery.id, label: __('Unnamed Gallery', 'modula-pro') + gallery.id });
        } else {
          options.push({ value: gallery.id, label: gallery.title.rendered });
        }
      });

      return options;
    }
  }, {
    key: 'render',
    value: function render() {
      var _this2 = this;

      var _props = this.props,
          attributes = _props.attributes,
          setAttributes = _props.setAttributes,
          galleries = _props.galleries;
      var id = attributes.id,
          fontSize = attributes.fontSize,
          buttonSize = attributes.buttonSize,
          buttonTextColor = attributes.buttonTextColor,
          buttonBackgroundColor = attributes.buttonBackgroundColor,
          buttonHoverTextColor = attributes.buttonHoverTextColor,
          buttonHoverBackgroundColor = attributes.buttonHoverBackgroundColor,
          borderWidth = attributes.borderWidth,
          borderRadius = attributes.borderRadius,
          borderColor = attributes.borderColor,
          borderType = attributes.borderType;

      var fontSizes = [{
        name: __('Small'),
        slug: 'small',
        size: 12
      }, {
        name: __('Medium'),
        slug: 'medium',
        size: 18
      }, {
        name: __('Big'),
        slug: 'big',
        size: 26
      }];

      var fallbackFontSize = 16;

      return React.createElement(
        Fragment,
        null,
        React.createElement(
          InspectorControls,
          null,
          React.createElement(
            PanelBody,
            { title: __('Gallery Settings', 'modula-pro'), initialOpen: true },
            galleries.length === 0 && React.createElement(
              Fragment,
              null,
              React.createElement(
                'p',
                null,
                __('You don\'t seem to have any galleries.', 'modula-pro')
              ),
              React.createElement(
                Button,
                { href: modulaVars.adminURL + 'post-new.php?post_type=modula-gallery', target: '_blank', isDefault: true },
                __('Add New Gallery', 'modula-pro')
              )
            ),
            galleries.length > 0 && React.createElement(
              Fragment,
              null,
              React.createElement(SelectControl, {
                key: id,
                label: __('Select Gallery'),
                value: id,
                options: this.selectOptions(),
                onChange: function onChange(value) {
                  return _this2.props.onIdChange(parseInt(value));
                }
              }),
              id != 0 && React.createElement(
                Button,
                { target: '_blank', href: modulaVars.adminURL + 'post.php?post=' + id + '&action=edit', isDefault: true },
                __('Edit gallery', 'modula-pro')
              )
            )
          ),
          React.createElement(
            PanelBody,
            { title: __('Button Style Settings', 'modula-pro'), initialOpen: true },
            React.createElement(
              'h3',
              null,
              ' ',
              __('Button Font Size'),
              ' '
            ),
            React.createElement(FontSizePicker, {
              fontSizes: fontSizes,
              value: fontSize,
              fallbackFontSize: fallbackFontSize,
              onChange: function onChange(newFontSize) {
                setAttributes({ fontSize: newFontSize });
              }
            }),
            React.createElement(RadioControl, {
              label: __("Button Size", 'modula-pro'),
              selected: buttonSize,
              options: [{ label: "Small", value: "modula-link-button-small" }, { label: "Medium", value: "modula-link-button-medium" }, { label: "Large", value: "modula-link-button-large" }, { label: "X-Large", value: "modula-link-button-x-large" }],
              onChange: function onChange(buttonSize) {
                return setAttributes({ buttonSize: buttonSize });
              }
            }),
            React.createElement(
              'h4',
              null,
              __('Button Text Color', 'modula-pro'),
              ' '
            ),
            React.createElement(ColorPicker, {
              color: buttonTextColor,
              onChangeComplete: function onChangeComplete(buttonTextColor) {
                return setAttributes({ buttonTextColor: buttonTextColor });
              },
              disableAlpha: true
            }),
            React.createElement(
              'h4',
              null,
              __('Background Color', 'modula-pro'),
              ' '
            ),
            React.createElement(ColorPicker, {
              color: buttonBackgroundColor,
              onChangeComplete: function onChangeComplete(buttonBackgroundColor) {
                return setAttributes({ buttonBackgroundColor: buttonBackgroundColor });
              },
              disableAlpha: true
            }),
            React.createElement(
              'h4',
              null,
              ' ',
              __('Hover Text Color', 'modula-pro'),
              ' '
            ),
            React.createElement(ColorPicker, {
              color: buttonHoverTextColor,
              onChangeComplete: function onChangeComplete(buttonHoverTextColor) {
                return setAttributes({ buttonHoverTextColor: buttonHoverTextColor });
              },
              disableAlpha: true
            }),
            React.createElement(
              'h4',
              null,
              ' ',
              __('Hover Background Color', 'modula-pro'),
              ' '
            ),
            React.createElement(ColorPicker, {
              color: buttonHoverBackgroundColor,
              onChangeComplete: function onChangeComplete(buttonHoverBackgroundColor) {
                return setAttributes({ buttonHoverBackgroundColor: buttonHoverBackgroundColor });
              },
              disableAlpha: true
            }),
            React.createElement(
              'h2',
              null,
              ' ',
              __('Border Settings', 'modula-pro'),
              ' '
            ),
            React.createElement(RadioControl, {
              label: __("Border Type", 'modula-pro'),
              selected: borderType,
              options: [{ label: __("Solid"), value: 'solid' }, { label: __("Double"), value: 'double' }, { label: __("Dotted"), value: 'dotted' }, { label: __("Ridge"), value: 'ridge' }],
              onChange: function onChange(borderType) {
                return setAttributes({ borderType: borderType });
              }
            }),
            React.createElement(RangeControl, {
              beforeIcon: 'arrow-left-alt2',
              afterIcon: 'arrow-right-alt2',
              label: __("Border Width"),
              value: borderWidth,
              onChange: function onChange(borderWidth) {
                return setAttributes({ borderWidth: borderWidth });
              },
              min: 1,
              max: 10
            }),
            React.createElement(
              'h4',
              null,
              ' ',
              __('Border Color', 'modula-pro'),
              ' '
            ),
            React.createElement(ColorPicker, {
              color: borderColor,
              onChangeComplete: function onChangeComplete(borderColor) {
                return setAttributes({ borderColor: borderColor });
              },
              disableAlpha: true
            }),
            React.createElement(RangeControl, {
              beforeIcon: 'arrow-left-alt2',
              afterIcon: 'arrow-right-alt2',
              label: __("Border Radius"),
              value: borderRadius,
              onChange: function onChange(borderRadius) {
                return setAttributes({ borderRadius: borderRadius });
              },
              min: 1,
              max: 100
            })
          )
        )
      );
    }
  }]);

  return Inspector;
}(Component);

exports.default = Inspector;

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Internal dependencies
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      */


var _edit = __webpack_require__(0);

var _edit2 = _interopRequireDefault(_edit);

var _icons = __webpack_require__(1);

var _icons2 = _interopRequireDefault(_icons);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * WordPress dependencies
 */
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

var ModulaLinkGutenberg = function () {
	function ModulaLinkGutenberg() {
		_classCallCheck(this, ModulaLinkGutenberg);

		this.registerBlock();
	}

	_createClass(ModulaLinkGutenberg, [{
		key: 'registerBlock',
		value: function registerBlock() {

			this.blockName = 'modula/link';

			this.blockAttributes = {
				id: {
					type: 'number',
					default: 0
				},
				textAlignment: {
					type: 'string'
				},
				buttonText: {
					type: 'string'
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
					default: '#000'
				},
				buttonBackgroundColor: {
					type: 'object',
					default: '#fff'
				},
				buttonHoverTextColor: {
					type: 'object',
					default: '#fff'
				},
				buttonHoverBackgroundColor: {
					type: 'object',
					default: '#000'
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
				borderType: {
					type: 'string',
					default: 'solid'
				}

			};

			registerBlockType(this.blockName, {
				title: modulaLinkVars.gutenbergLinkTitle,
				icon: _icons2.default.modula,
				description: __('Make your galleries stand out.', 'modula-pro'),
				keywords: [__('gallery'), __('modula'), __('link')],
				category: 'common',
				supports: {
					align: ['wide', 'full'],
					customClassName: false
				},
				attributes: this.blockAttributes,
				edit: _edit2.default,
				save: function save() {
					// Rendering in PHP
					return null;
				}
			});
		}
	}]);

	return ModulaLinkGutenberg;
}();

var modulaLinkGutenberg = new ModulaLinkGutenberg();

/***/ })
/******/ ]);