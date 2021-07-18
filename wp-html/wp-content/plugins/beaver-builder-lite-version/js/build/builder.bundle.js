/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/builder/data/index.js":
/*!***********************************!*\
  !*** ./src/builder/data/index.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getSystemActions": () => /* reexport safe */ _system__WEBPACK_IMPORTED_MODULE_0__.getSystemActions,
/* harmony export */   "getSystemSelectors": () => /* reexport safe */ _system__WEBPACK_IMPORTED_MODULE_0__.getSystemSelectors,
/* harmony export */   "getSystemState": () => /* reexport safe */ _system__WEBPACK_IMPORTED_MODULE_0__.getSystemState,
/* harmony export */   "getSystemStore": () => /* reexport safe */ _system__WEBPACK_IMPORTED_MODULE_0__.getSystemStore,
/* harmony export */   "useSystemState": () => /* reexport safe */ _system__WEBPACK_IMPORTED_MODULE_0__.useSystemState
/* harmony export */ });
/* harmony import */ var _system__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./system */ "./src/builder/data/system/index.js");


/***/ }),

/***/ "./src/builder/data/registry/index.js":
/*!********************************************!*\
  !*** ./src/builder/data/registry/index.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "registerStore": () => /* binding */ registerStore,
/* harmony export */   "useStore": () => /* binding */ useStore,
/* harmony export */   "getStore": () => /* binding */ getStore,
/* harmony export */   "getDispatch": () => /* binding */ getDispatch,
/* harmony export */   "getSelectors": () => /* binding */ getSelectors
/* harmony export */ });
/* harmony import */ var _beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @beaverbuilder/app-core */ "@beaverbuilder/app-core");
/* harmony import */ var _beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_0__);


var _createStoreRegistry = (0,_beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_0__.createStoreRegistry)(),
    registerStore = _createStoreRegistry.registerStore,
    useStore = _createStoreRegistry.useStore,
    getStore = _createStoreRegistry.getStore,
    getDispatch = _createStoreRegistry.getDispatch,
    getSelectors = _createStoreRegistry.getSelectors;



/***/ }),

/***/ "./src/builder/data/system/actions.js":
/*!********************************************!*\
  !*** ./src/builder/data/system/actions.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "setShouldShowShortcuts": () => /* binding */ setShouldShowShortcuts,
/* harmony export */   "registerPanel": () => /* binding */ registerPanel,
/* harmony export */   "displayPanel": () => /* binding */ displayPanel,
/* harmony export */   "togglePanel": () => /* binding */ togglePanel,
/* harmony export */   "hideCurrentPanel": () => /* binding */ hideCurrentPanel,
/* harmony export */   "setIsEditing": () => /* binding */ setIsEditing,
/* harmony export */   "setColorScheme": () => /* binding */ setColorScheme
/* harmony export */ });
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var setShouldShowShortcuts = function setShouldShowShortcuts(value) {
  return {
    type: 'SET_SHOULD_SHOW_SHORTCUTS',
    value: value
  };
};
var registerPanel = function registerPanel() {
  var handle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'fl/untitled';
  var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var defaults = {
    label: '',
    root: null,
    render: function render() {
      return null;
    },

    /* Legacy Prop */
    className: null,
    routerProps: {},
    onHistoryChanged: function onHistoryChanged() {}
  };
  return {
    type: 'REGISTER_PANEL',
    handle: handle,
    options: _objectSpread(_objectSpread({}, defaults), options)
  };
};
var displayPanel = function displayPanel() {
  var name = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  return {
    type: 'SET_CURRENT_PANEL',
    name: name
  };
};
var togglePanel = function togglePanel() {
  var name = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  return {
    type: 'TOGGLE_PANEL',
    name: name
  };
};
var hideCurrentPanel = function hideCurrentPanel() {
  return {
    type: 'HIDE_CURRENT_PANEL'
  };
};
var setIsEditing = function setIsEditing() {
  var value = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
  return {
    type: 'SET_IS_EDITING',
    value: value
  };
};
var setColorScheme = function setColorScheme() {
  var value = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'light';
  return {
    type: 'SET_COLOR_SCHEME',
    value: value
  };
};

/***/ }),

/***/ "./src/builder/data/system/effects.js":
/*!********************************************!*\
  !*** ./src/builder/data/system/effects.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "before": () => /* binding */ before,
/* harmony export */   "after": () => /* binding */ after
/* harmony export */ });
/**
 * Effects that fire before an action.
 */
var before = {};
/**
 * Effects that fire after an action.
 */

var after = {
  TOGGLE_PANEL: function TOGGLE_PANEL(action, store) {
    var _store$getState = store.getState(),
        currentPanel = _store$getState.currentPanel;

    var html = document.querySelector('html');

    if (currentPanel) {
      FLBuilder._closePanel();
    }

    if ('assistant' === currentPanel) {
      html.classList.add('fl-builder-assistant-visible');
    } else {
      html.classList.remove('fl-builder-assistant-visible');
    }
  },
  HIDE_CURRENT_PANEL: function HIDE_CURRENT_PANEL() {
    var html = document.querySelector('html');
    html.classList.remove('fl-builder-assistant-visible');
  }
};

/***/ }),

/***/ "./src/builder/data/system/index.js":
/*!******************************************!*\
  !*** ./src/builder/data/system/index.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "useSystemState": () => /* binding */ useSystemState,
/* harmony export */   "getSystemStore": () => /* binding */ getSystemStore,
/* harmony export */   "getSystemState": () => /* binding */ getSystemState,
/* harmony export */   "getSystemActions": () => /* binding */ getSystemActions,
/* harmony export */   "getSystemSelectors": () => /* binding */ getSystemSelectors
/* harmony export */ });
/* harmony import */ var _registry__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../registry */ "./src/builder/data/registry/index.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./actions */ "./src/builder/data/system/actions.js");
/* harmony import */ var _reducers__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./reducers */ "./src/builder/data/system/reducers.js");
/* harmony import */ var _effects__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./effects */ "./src/builder/data/system/effects.js");




var key = 'fl-builder/system';
(0,_registry__WEBPACK_IMPORTED_MODULE_0__.registerStore)(key, {
  actions: _actions__WEBPACK_IMPORTED_MODULE_1__,
  reducers: _reducers__WEBPACK_IMPORTED_MODULE_2__,
  effects: _effects__WEBPACK_IMPORTED_MODULE_3__,
  state: {
    isEditing: true,
    currentPanel: null,
    shouldShowShortcuts: false,
    colorScheme: FLBuilderConfig.userSettings.skin,
    panels: {}
  }
});
var useSystemState = function useSystemState() {
  return (0,_registry__WEBPACK_IMPORTED_MODULE_0__.useStore)(key);
};
var getSystemStore = function getSystemStore() {
  return (0,_registry__WEBPACK_IMPORTED_MODULE_0__.getStore)(key);
};
var getSystemState = function getSystemState() {
  return (0,_registry__WEBPACK_IMPORTED_MODULE_0__.getStore)(key).getState();
};
var getSystemActions = function getSystemActions() {
  return (0,_registry__WEBPACK_IMPORTED_MODULE_0__.getDispatch)(key);
};
var getSystemSelectors = function getSystemSelectors() {
  return (0,_registry__WEBPACK_IMPORTED_MODULE_0__.getSelectors)(key);
};

/***/ }),

/***/ "./src/builder/data/system/reducers.js":
/*!*********************************************!*\
  !*** ./src/builder/data/system/reducers.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "shouldShowShortcuts": () => /* binding */ shouldShowShortcuts,
/* harmony export */   "panels": () => /* binding */ panels,
/* harmony export */   "currentPanel": () => /* binding */ currentPanel,
/* harmony export */   "isEditing": () => /* binding */ isEditing,
/* harmony export */   "colorScheme": () => /* binding */ colorScheme
/* harmony export */ });
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var shouldShowShortcuts = function shouldShowShortcuts() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_SHOULD_SHOW_SHORTCUTS':
      return action.value ? true : false;

    default:
      return state;
  }
};
var panels = function panels() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'REGISTER_PANEL':
      return _objectSpread(_objectSpread({}, state), {}, _defineProperty({}, action.handle, action.options));

    default:
      return state;
  }
};
var currentPanel = function currentPanel() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_CURRENT_PANEL':
      return action.name;

    case 'HIDE_CURRENT_PANEL':
      return null;

    case 'TOGGLE_PANEL':
      return action.name === state ? null : action.name;

    default:
      return state;
  }
};
var isEditing = function isEditing() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_IS_EDITING':
      return action.value ? true : false;

    default:
      return state;
  }
};
var colorScheme = function colorScheme() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'light';
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_COLOR_SCHEME':
      return 'dark' === action.value ? 'dark' : 'light';

    default:
      return state;
  }
};

/***/ }),

/***/ "./src/builder/index.js":
/*!******************************!*\
  !*** ./src/builder/index.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-dom */ "react-dom");
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_dom__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _ui__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ui */ "./src/builder/ui/index.js");
/* harmony import */ var _data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./data */ "./src/builder/data/index.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }



 // Setup Store Registry and Initialize System Store



var _data$getSystemAction = _data__WEBPACK_IMPORTED_MODULE_3__.getSystemActions(),
    registerPanel = _data$getSystemAction.registerPanel,
    displayPanel = _data$getSystemAction.displayPanel,
    togglePanel = _data$getSystemAction.togglePanel; // Setup public API - window.FL.Builder


var api = window.FL || {};
var existing = api.Builder || {};

var Builder = _objectSpread(_objectSpread({}, existing), {}, {
  data: _data__WEBPACK_IMPORTED_MODULE_3__,
  registerPanel: registerPanel,
  displayPanel: displayPanel,
  togglePanel: togglePanel
});

window.FL = _objectSpread(_objectSpread({}, api), {}, {
  Builder: Builder
}); // Render UI

var root = document.getElementById('fl-ui-root');
root.classList.add('fluid', 'fl', 'uid');
(0,react_dom__WEBPACK_IMPORTED_MODULE_1__.render)( /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_ui__WEBPACK_IMPORTED_MODULE_2__.default, null), root);

/***/ }),

/***/ "./src/builder/ui/art/index.js":
/*!*************************************!*\
  !*** ./src/builder/ui/art/index.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "SVGSymbols": () => /* binding */ SVGSymbols,
/* harmony export */   "Icon": () => /* binding */ Icon
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/art/style.scss");


var SVGSymbols = function SVGSymbols() {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("svg", {
    id: "fl-symbol-container",
    version: "1.1",
    xmlns: "http://www.w3.org/2000/svg"
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("symbol", {
    id: "fl-down-caret",
    viewBox: "0 0 11 6"
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("polygon", {
    points: "0 0 2.05697559 0 5.49235478 3.74058411 8.93443824 0 11 0 5.5 6"
  })));
};
var Icon = function Icon() {};

Icon.Close = function () {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("svg", {
    width: "14px",
    height: "14px",
    viewBox: "0 0 14 14",
    version: "1.1",
    xmlns: "http://www.w3.org/2000/svg"
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("g", {
    stroke: "currentColor",
    strokeWidth: "2",
    fill: "none",
    fillRule: "evenodd",
    strokeLinecap: "round"
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("path", {
    d: "M13,1 L1,13"
  }), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("path", {
    d: "M1,1 L13,13"
  })));
};

/***/ }),

/***/ "./src/builder/ui/index.js":
/*!*********************************!*\
  !*** ./src/builder/ui/index.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => __WEBPACK_DEFAULT_EXPORT__
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! data */ "./src/builder/data/index.js");
/* harmony import */ var _notifications__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./notifications */ "./src/builder/ui/notifications/index.js");
/* harmony import */ var _inline_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./inline-editor */ "./src/builder/ui/inline-editor/index.js");
/* harmony import */ var _shortcuts_panel__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./shortcuts-panel */ "./src/builder/ui/shortcuts-panel/index.js");
/* harmony import */ var _art__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./art */ "./src/builder/ui/art/index.js");
/* harmony import */ var _panel_manager__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./panel-manager */ "./src/builder/ui/panel-manager/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/style.scss");








/**
 * Builder React-based UI Root
 *
 * Gets rendered onto the page and remains.
 */

var BeaverBuilderUI = function BeaverBuilderUI() {
  var _useSystemState = (0,data__WEBPACK_IMPORTED_MODULE_1__.useSystemState)(),
      isEditing = _useSystemState.isEditing,
      shouldShowShortcuts = _useSystemState.shouldShowShortcuts;

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment), null, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_inline_editor__WEBPACK_IMPORTED_MODULE_3__.default, null), isEditing && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment), null, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_art__WEBPACK_IMPORTED_MODULE_5__.SVGSymbols, null), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_notifications__WEBPACK_IMPORTED_MODULE_2__.NotificationsManager, null), shouldShowShortcuts && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_shortcuts_panel__WEBPACK_IMPORTED_MODULE_4__.default, null), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_panel_manager__WEBPACK_IMPORTED_MODULE_6__.default, null)));
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BeaverBuilderUI);

/***/ }),

/***/ "./src/builder/ui/inline-editor/index.js":
/*!***********************************************!*\
  !*** ./src/builder/ui/inline-editor/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => __WEBPACK_DEFAULT_EXPORT__
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/inline-editor/style.scss");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }



/**
 * Handles inline editing for builder layouts.
 *
 * @since 2.1
 * @class InlineEditor
 */

var InlineEditor = /*#__PURE__*/function (_Component) {
  _inherits(InlineEditor, _Component);

  var _super = _createSuper(InlineEditor);

  function InlineEditor(props) {
    var _this;

    _classCallCheck(this, InlineEditor);

    _this = _super.call(this, props);
    var postId = _this.props.postId;
    _this.layoutClass = ".fl-builder-content-".concat(postId ? postId : FLBuilderConfig.postId);
    return _this;
  }

  _createClass(InlineEditor, [{
    key: "componentDidMount",
    value: function componentDidMount() {
      this.setupHooks = this.setupHooks.bind(this);
      this.hooked = false;
      jQuery(document).on('tinymce-editor-init', this.setupHooks);
      this.setupHooks();
    }
  }, {
    key: "setupHooks",
    value: function setupHooks() {
      if ('ontouchstart' in document) {
        return;
      }

      if (!window.tinymce || this.hooked || !FLBuilderConfig.inlineEnabled) {
        return;
      }

      var initEditables = this.initEditables.bind(this);
      var refreshEditables = this.refreshEditables.bind(this); //const destroyEditables = this.destroyEditables.bind( this )

      var destroyAllEditables = this.destroyAllEditables.bind(this);
      var destroyLoadingEditables = this.destroyLoadingEditables.bind(this);

      if (FLBuilder) {
        // Init actions
        FLBuilder.addHook('settingsConfigLoaded', initEditables);
        FLBuilder.addHook('restartEditingSession', initEditables); // Destroy actions

        FLBuilder.addHook('endEditingSession', destroyAllEditables);
        FLBuilder.addHook('didStartNodeLoading', destroyLoadingEditables); // Refresh actions

        FLBuilder.addHook('didRenderLayoutComplete', refreshEditables);
        FLBuilder.addHook('didDeleteRow', refreshEditables);
        FLBuilder.addHook('didDeleteColumn', refreshEditables);
        FLBuilder.addHook('didDeleteModule', refreshEditables);
      }

      this.initEditables();
      this.hooked = true;
    }
  }, {
    key: "initEditables",
    value: function initEditables() {
      var _this2 = this;

      var _FLBuilderSettingsCon = FLBuilderSettingsConfig,
          editables = _FLBuilderSettingsCon.editables;
      var content = jQuery(this.layoutClass);

      if (content.length) {
        for (var key in editables) {
          var selector = ".fl-module[data-type=\"".concat(key, "\"]:not(.fl-editable):not(.fl-node-global)");
          content.find(selector).each(function (index, module) {
            module = jQuery(module);
            module.addClass('fl-editable');
            module.delegate('.fl-block-overlay', 'click.fl-inline-editing-init', function (e) {
              return _this2.initEditable(e, module);
            });
          });
        }
      }
    }
  }, {
    key: "initEditable",
    value: function initEditable(e, module) {
      var _this3 = this;

      var _FLBuilder = FLBuilder,
          preview = _FLBuilder.preview; // Don't setup if we have a parent that needs to save.

      if (preview) {
        var isParent = module.parents(".fl-node-".concat(preview.nodeId)).length;

        if (isParent && preview._settingsHaveChanged()) {
          return;
        }
      }

      this.setupEditable(module, function () {
        _this3.onModuleOverlayClick(e);
      });
      module.undelegate('.fl-block-overlay', 'click.fl-inline-editing-init');
    }
  }, {
    key: "setupEditable",
    value: function setupEditable(module) {
      var _this4 = this;

      var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
      var nodeId = module.data('node');
      var settings = FLBuilderSettingsConfig.nodes[nodeId];

      if ('undefined' === typeof settings) {
        return false;
      }

      var type = module.data('type');
      var config = FLBuilderSettingsConfig.editables[type];
      var nodeClass = ".fl-node-".concat(nodeId, " ");
      var editorId = "fl-inline-editor-".concat(nodeId);
      var overlay = jQuery("<div id=\"".concat(editorId, "\" class=\"fl-inline-editor\"></div>"));
      var form = jQuery(".fl-builder-settings[data-node=".concat(nodeId, "]"));
      var connections = settings.connections;
      module.append(overlay);
      module.delegate('.fl-block-overlay', 'click', this.onModuleOverlayClick.bind(this));
      module.on('mouseleave', this.onModuleMouseleave.bind(this));

      var _loop = function _loop(key) {
        var data = config[key];
        var selector = FLBuilderPreview.getFormattedSelector(nodeClass, data.selector);
        var editable = jQuery(selector);
        var editableHTML = editable.html();
        var connection = form.find("#fl-field-".concat(key, " .fl-field-connection-value"));

        if (!editable.length) {
          return "continue";
        } else if (connection.length && '' !== connection.val()) {
          return "continue";
        } else if (!connection.length && connections && connections[key]) {
          return "continue";
        }

        if (editable.hasClass('mce-content-body')) {
          tinymce.execCommand('mceRemoveEditor', true, editable.attr('id'));
        } else {
          editable.data('field', data.field);
          editable.on('drop', _this4.onEditorDrop.bind(_this4));
        }

        tinymce.init({
          selector: selector,
          inline: true,
          menubar: false,
          paste_as_text: true,
          relative_urls: false,
          convert_urls: false,
          skin: FLBuilder ? false : 'lightgray',
          skin_url: FLBuilder ? false : "".concat(tinyMCEPreInit.baseURL, "/skins/lightgray/"),
          theme: 'modern',
          theme_url: "".concat(tinyMCEPreInit.baseURL, "/themes/modern/"),
          fixed_toolbar_container: "#".concat(editorId),
          plugins: _this4.getEditorPluginConfig(data.field.type),
          toolbar: 'string' === typeof data.field.toolbar ? data.field.toolbar : _this4.getEditorToolbarConfig(data.field.type),
          init_instance_callback: function init_instance_callback(editor) {
            _this4.onEditorInit(editor);
            /**
             * TinyMCE can change the editable's HTML which changes the visual
             * appearance. To prevent this from happening, we reinsert the original
             * HTML after the editable has been initialized.
             */


            editable.html(editableHTML);
            callback();
          }
        });
      };

      for (var key in config) {
        var _ret = _loop(key);

        if (_ret === "continue") continue;
      }
    }
  }, {
    key: "getEditorPluginConfig",
    value: function getEditorPluginConfig(type) {
      switch (type) {
        case 'editor':
          return 'wordpress, wplink, lists, paste';

        default:
          return 'paste';
      }
    }
  }, {
    key: "getEditorToolbarConfig",
    value: function getEditorToolbarConfig(type) {
      switch (type) {
        case 'editor':
          return 'bold italic strikethrough link underline | alignleft aligncenter alignright';

        case 'unit':
          return false;

        default:
          return 'bold italic strikethrough underline';
      }
    }
  }, {
    key: "destroyEditables",
    value: function destroyEditables(modules) {
      var editables = modules.find('.mce-content-body');
      var overlays = modules.find('.fl-inline-editor');
      var extras = jQuery('.wplink-autocomplete, .ui-helper-hidden-accessible');
      editables.removeAttr('contenteditable');
      modules.undelegate('.fl-block-overlay', 'click');
      modules.off('mouseleave');
      modules.removeClass('fl-editable');
      overlays.remove();
      extras.remove();
    }
  }, {
    key: "destroyAllEditables",
    value: function destroyAllEditables() {
      var content = jQuery(this.layoutClass);
      var modules = content.find('.fl-editable');
      this.destroyEditables(modules);
    }
  }, {
    key: "destroyLoadingEditables",
    value: function destroyLoadingEditables(e, node) {
      var modules = jQuery(node);

      if (!modules.hasClass('fl-module')) {
        modules = modules.find('.fl-module');
      }

      this.destroyEditables(modules);
    }
  }, {
    key: "refreshEditables",
    value: function refreshEditables() {
      this.initEditables();
      tinymce.editors.map(function (editor) {
        if (editor.inline && !jQuery("#".concat(editor.id)).length) {
          setTimeout(function () {
            return tinymce.execCommand('mceRemoveEditor', true, editor.id);
          }, 1);
        }
      });
    }
  }, {
    key: "getEditorEventVars",
    value: function getEditorEventVars(target) {
      var editable = jQuery(target).closest('.mce-content-body');
      var editor = tinymce.get(editable.attr('id'));
      var field = editable.data('field');
      var module = editable.closest('.fl-module');
      var nodeId = module.data('node');
      return {
        editable: editable,
        module: module,
        editor: editor,
        field: field,
        nodeId: nodeId
      };
    }
  }, {
    key: "onEditorInit",
    value: function onEditorInit(editor) {
      editor.on('change', this.onEditorChange.bind(this));
      editor.on('keyup', this.onEditorChange.bind(this));
      editor.on('undo', this.onEditorChange.bind(this));
      editor.on('redo', this.onEditorChange.bind(this));
      editor.on('focus', this.onEditorFocus.bind(this));
      editor.on('blur', this.onEditorBlur.bind(this));
      editor.on('mousedown', this.onEditorMousedown.bind(this));
    }
  }, {
    key: "onEditorChange",
    value: function onEditorChange(e) {
      var target = e.target.bodyElement ? e.target.bodyElement : e.target;

      var _this$getEditorEventV = this.getEditorEventVars(target),
          editor = _this$getEditorEventV.editor,
          field = _this$getEditorEventV.field,
          nodeId = _this$getEditorEventV.nodeId;

      var settings = jQuery(".fl-builder-settings[data-node=\"".concat(nodeId, "\"]"));
      var content = editor.getContent();

      if (!settings.length) {
        return;
      } else if ('editor' === field.type) {
        var textarea = settings.find("#fl-field-".concat(field.name, " textarea.wp-editor-area"));
        var editorId = textarea.attr('id');

        if (textarea.closest('.tmce-active').length) {
          tinymce.get(editorId).setContent(content);
        } else {
          textarea.val(content);
        }
      } else {
        var _textarea = document.createElement('textarea');

        _textarea.innerHTML = content;
        settings.find("[name=\"".concat(field.name, "\"]")).val(_textarea.value);
      }
    }
  }, {
    key: "onEditorFocus",
    value: function onEditorFocus(e) {
      var _this$getEditorEventV2 = this.getEditorEventVars(e.target.bodyElement),
          editable = _this$getEditorEventV2.editable,
          editor = _this$getEditorEventV2.editor,
          module = _this$getEditorEventV2.module,
          field = _this$getEditorEventV2.field,
          nodeId = _this$getEditorEventV2.nodeId;

      var overlay = module.find('.fl-inline-editor');
      var settingHTML = this.getSettingHTML(nodeId, field);

      if (!this.matchHTML(editor.getContent(), settingHTML)) {
        editable.data('original', {
          settingHTML: settingHTML,
          editableHTML: editable.html()
        });
        editable.css('min-height', editable.height());
        editor.setContent(settingHTML);
        editor.selection.select(editor.getBody(), true);
        editor.selection.collapse(false);
      }

      if (editor.settings.toolbar) {
        overlay.removeClass('fl-inline-editor-no-toolbar');
      } else {
        overlay.addClass('fl-inline-editor-no-toolbar');
      }

      module.addClass('fl-editable-focused');
      this.showEditorOverlay(module);
      this.showModuleSettings(module);
    }
  }, {
    key: "onEditorBlur",
    value: function onEditorBlur(e) {
      var _this$getEditorEventV3 = this.getEditorEventVars(e.target.bodyElement),
          editable = _this$getEditorEventV3.editable,
          editor = _this$getEditorEventV3.editor,
          module = _this$getEditorEventV3.module;

      var overlay = module.find('.fl-inline-editor');
      var original = editable.data('original');
      overlay.removeClass('fl-inline-editor-no-toolbar');
      module.removeClass('fl-editable-focused');

      if (original && this.matchHTML(editor.getContent(), original.settingHTML)) {
        editable.html(original.editableHTML);
        editable.css('min-height', '');
      }
    }
  }, {
    key: "onEditorMousedown",
    value: function onEditorMousedown(e) {
      var _this$getEditorEventV4 = this.getEditorEventVars(e.target),
          module = _this$getEditorEventV4.module;

      this.showEditorOverlay(module);
    }
  }, {
    key: "onEditorDrop",
    value: function onEditorDrop(e) {
      e.preventDefault();
      return false;
    }
  }, {
    key: "onModuleOverlayClick",
    value: function onModuleOverlayClick(e) {
      var actions = jQuery(e.target).closest('.fl-block-overlay-actions');
      var module = jQuery(e.currentTarget).closest('.fl-module');
      var editorId = module.find('.mce-content-body').first().attr('id');

      if (actions.length || FLBuilder._colResizing) {
        return;
      }

      if (editorId) {
        tinymce.get(editorId).focus();
        module.addClass('fl-editable-focused');
      }
    }
  }, {
    key: "onModuleMouseleave",
    value: function onModuleMouseleave() {
      var panels = jQuery('.mce-inline-toolbar-grp:visible, .mce-floatpanel:visible');

      if (!panels.length) {
        this.hideEditorOverlays();
        this.showNodeOverlays();
      }
    }
  }, {
    key: "showEditorOverlay",
    value: function showEditorOverlay(module) {
      var overlay = module.find('.fl-inline-editor');
      this.hideNodeOverlays();
      this.hideEditorOverlays();
      overlay.show();
      var active = jQuery('.fl-inline-editor-active-toolbar');
      active.removeClass('fl-inline-editor-active-toolbar');
      var toolbar = overlay.find('> .mce-panel:visible');
      toolbar.addClass('fl-inline-editor-active-toolbar');
    }
  }, {
    key: "hideEditorOverlays",
    value: function hideEditorOverlays() {
      jQuery('.fl-inline-editor, .mce-floatpanel').hide();
    }
  }, {
    key: "showNodeOverlays",
    value: function showNodeOverlays() {
      jQuery('.fl-block-overlay').show();
    }
  }, {
    key: "hideNodeOverlays",
    value: function hideNodeOverlays() {
      jQuery('.fl-block-overlay').hide();
    }
  }, {
    key: "showModuleSettings",
    value: function showModuleSettings(module) {
      var type = module.data('type');
      var nodeId = module.data('node');
      var parentId = module.closest('.fl-col').data('node');
      var global = module.hasClass('fl-node-global');
      var settings = jQuery(".fl-builder-settings[data-node=\"".concat(nodeId, "\"]"));

      if (!settings.length) {
        FLBuilder._showModuleSettings({
          type: type,
          nodeId: nodeId,
          parentId: parentId,
          global: global
        });
      }
    }
  }, {
    key: "getSettingValue",
    value: function getSettingValue(nodeId, name) {
      var form = jQuery(".fl-builder-settings[data-node=\"".concat(nodeId, "\"]"));
      var settings = {};

      if (form.length) {
        settings = FLBuilder._getSettings(form);
      } else {
        settings = FLBuilderSettingsConfig.nodes[nodeId];
      }

      return settings[name];
    }
  }, {
    key: "getSettingHTML",
    value: function getSettingHTML(nodeId, field) {
      var html = this.getSettingValue(nodeId, field.name);

      if ('editor' === field.type && '' !== html) {
        return wp.editor.autop(html);
      }

      return html;
    }
  }, {
    key: "matchHTML",
    value: function matchHTML(a, b) {
      return this.cleanHTML(a) === this.cleanHTML(b);
    }
  }, {
    key: "cleanHTML",
    value: function cleanHTML(html) {
      var re = /(\r\n|\n|\r)/gm;
      return jQuery("<div>".concat(html, "</div>")).html().trim().replace(re, '');
    }
  }, {
    key: "render",
    value: function render() {
      return null;
    }
  }]);

  return InlineEditor;
}(react__WEBPACK_IMPORTED_MODULE_0__.Component);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (InlineEditor);

/***/ }),

/***/ "./src/builder/ui/notifications/index.js":
/*!***********************************************!*\
  !*** ./src/builder/ui/notifications/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "NotificationsManager": () => /* binding */ NotificationsManager
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! data */ "./src/builder/data/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/notifications/style.scss");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }





var renderHTML = function renderHTML(rawHTML) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement('div', {
    dangerouslySetInnerHTML: {
      __html: rawHTML
    }
  });
};

var lite = FLBuilderConfig.lite;

var Post = function Post(props) {
  var html = {
    __html: props.children
  },
      date = new Date(props.date).toDateString();
  var post;

  if ('string' === typeof props.url && '' !== props.url) {
    var url = lite ? props.url + '?utm_medium=bb-lite&utm_source=builder-ui&utm_campaign=notification-center' : props.url + '?utm_medium=bb-pro&utm_source=builder-ui&utm_campaign=notification-center';
    post = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("a", {
      className: "fl-builder-ui-post",
      href: url,
      target: "_blank",
      rel: "noopener noreferrer"
    }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-builder-ui-post-date"
    }, date), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-builder-ui-post-title"
    }, props.title), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-builder-ui-post-content",
      dangerouslySetInnerHTML: html
    }));
  } else {
    post = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span", {
      className: "fl-builder-ui-post"
    }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-builder-ui-post-date"
    }, date), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-builder-ui-post-title"
    }, props.title), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-builder-ui-post-content",
      dangerouslySetInnerHTML: html
    }));
  }

  return post;
};
/**
 * Notifications Sidebar Panel
 * Displayed when toggleNotifications hook is fired
 */


var NotificationsPanel = /*#__PURE__*/function (_Component) {
  _inherits(NotificationsPanel, _Component);

  var _super = _createSuper(NotificationsPanel);

  function NotificationsPanel() {
    _classCallCheck(this, NotificationsPanel);

    return _super.apply(this, arguments);
  }

  _createClass(NotificationsPanel, [{
    key: "getPosts",
    value: function getPosts(posts) {
      var view,
          renderedPosts,
          strings = FLBuilderStrings.notifications;

      if (0 < posts.length) {
        renderedPosts = posts.map(function (item) {
          return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Post, {
            key: item.id,
            title: renderHTML(item.title.rendered),
            date: item.date,
            url: item.meta._fl_notification[0]
          }, item.content.rendered);
        });
        view = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment), null, renderedPosts);
      } else {
        view = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
          className: "fl-panel-no-message"
        }, strings.none);
      }

      return view;
    }
  }, {
    key: "componentDidMount",
    value: function componentDidMount() {
      FLBuilder._initScrollbars();
    }
  }, {
    key: "componentDidUpdate",
    value: function componentDidUpdate() {
      FLBuilder._initScrollbars();
    }
  }, {
    key: "render",
    value: function render() {
      var content = this.getPosts(this.props.posts),
          strings = FLBuilderStrings.notifications;
      return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
        className: "fl-notifications-panel"
      }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
        className: "fl-panel-title"
      }, strings.title), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
        className: "fl-nanoscroller",
        ref: this.setupScroller
      }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
        className: "fl-nanoscroller-content"
      }, content)));
    }
  }]);

  return NotificationsPanel;
}(react__WEBPACK_IMPORTED_MODULE_0__.Component);
/**
* Non-UI Manager Object. Handles state for the notifications system
*/


var NotificationsManager = /*#__PURE__*/function (_Component2) {
  _inherits(NotificationsManager, _Component2);

  var _super2 = _createSuper(NotificationsManager);

  function NotificationsManager(props) {
    var _this;

    _classCallCheck(this, NotificationsManager);

    _this = _super2.call(this, props);
    var out = {};
    var data = FLBuilderConfig.notifications.data; // make sure we have valid json.

    try {
      out = JSON.parse(data);
    } catch (e) {
      out = {};
    }

    _this.state = {
      shouldShowNotifications: false,
      posts: out
    };
    FLBuilder.addHook('toggleNotifications', _this.onToggleNotifications.bind(_assertThisInitialized(_this)));
    return _this;
  }

  _createClass(NotificationsManager, [{
    key: "onToggleNotifications",
    value: function onToggleNotifications() {
      var _getSystemActions = (0,data__WEBPACK_IMPORTED_MODULE_1__.getSystemActions)(),
          hideCurrentPanel = _getSystemActions.hideCurrentPanel;

      this.setState({
        shouldShowNotifications: !this.state.shouldShowNotifications
      });
      hideCurrentPanel();
    }
  }, {
    key: "render",
    value: function render() {
      var _this$state = this.state,
          shouldShowNotifications = _this$state.shouldShowNotifications,
          posts = _this$state.posts;
      FLBuilder.triggerHook('notificationsLoaded');
      return shouldShowNotifications && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(NotificationsPanel, {
        posts: posts
      });
    }
  }]);

  return NotificationsManager;
}(react__WEBPACK_IMPORTED_MODULE_0__.Component);

/***/ }),

/***/ "./src/builder/ui/panel-manager/frame/index.js":
/*!*****************************************************!*\
  !*** ./src/builder/ui/panel-manager/frame/index.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => __WEBPACK_DEFAULT_EXPORT__
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }




var Frame = function Frame(_ref) {
  var className = _ref.className,
      rest = _objectWithoutProperties(_ref, ["className"]);

  var classes = classnames__WEBPACK_IMPORTED_MODULE_1___default()('fl-builder-workspace-panel', className);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", _extends({
    className: classes
  }, rest));
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Frame);

/***/ }),

/***/ "./src/builder/ui/panel-manager/index.js":
/*!***********************************************!*\
  !*** ./src/builder/ui/panel-manager/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => __WEBPACK_DEFAULT_EXPORT__
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @beaverbuilder/app-core */ "@beaverbuilder/app-core");
/* harmony import */ var _beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! data */ "./src/builder/data/index.js");
/* harmony import */ var _frame__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./frame */ "./src/builder/ui/panel-manager/frame/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/panel-manager/style.scss");
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }








var handleObjectOrFunction = function handleObjectOrFunction(obj) {
  return 'function' === typeof obj ? obj() : obj;
};

var PanelManager = function PanelManager() {
  var _useSystemState = (0,data__WEBPACK_IMPORTED_MODULE_3__.useSystemState)(),
      currentPanel = _useSystemState.currentPanel,
      panels = _useSystemState.panels,
      colorScheme = _useSystemState.colorScheme;

  var panel = null;

  if (currentPanel in panels) {
    panel = panels[currentPanel];
  } else {
    return null;
  }

  var _panel = panel,
      routerProps = _panel.routerProps,
      onHistoryChanged = _panel.onHistoryChanged,
      root = _panel.root,
      render = _panel.render,
      _panel$frame = _panel.frame,
      frame = _panel$frame === void 0 ? _frame__WEBPACK_IMPORTED_MODULE_4__.default : _panel$frame,
      panelClassName = _panel.className,
      wrapClassName = _panel.wrapClassName;
  var Frame = false === frame ? react__WEBPACK_IMPORTED_MODULE_0__.Fragment : frame;
  var PanelContent = root ? root : render;
  /* support legacy render prop */

  var wrapClasses = classnames__WEBPACK_IMPORTED_MODULE_1___default()(_defineProperty({}, "fluid-color-scheme-".concat(colorScheme), colorScheme), wrapClassName);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: wrapClasses
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Frame, {
    className: false !== frame && panelClassName
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_beaverbuilder_app_core__WEBPACK_IMPORTED_MODULE_2__.Root, {
    routerProps: handleObjectOrFunction(routerProps),
    onHistoryChanged: onHistoryChanged,
    colorScheme: colorScheme
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(PanelContent, null))));
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PanelManager);

/***/ }),

/***/ "./src/builder/ui/shortcuts-panel/index.js":
/*!*************************************************!*\
  !*** ./src/builder/ui/shortcuts-panel/index.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => __WEBPACK_DEFAULT_EXPORT__
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _panel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./panel */ "./src/builder/ui/shortcuts-panel/panel/index.js");
/* harmony import */ var data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! data */ "./src/builder/data/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/shortcuts-panel/style.scss");





var ShortcutsList = function ShortcutsList(_ref) {
  var shortcuts = _ref.shortcuts;

  if (0 === Object.keys(shortcuts).length) {
    return null;
  }

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("ul", {
    className: "fl-ui-shortcut-list"
  }, Object.values(shortcuts).map(function (item, i) {
    var label = item.label,
        keyLabel = item.keyLabel;
    var key = {
      __html: keyLabel
    };
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("li", {
      key: i
    }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span", null, label), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span", {
      className: "fl-ui-shortcut-item-keycode",
      dangerouslySetInnerHTML: key
    }));
  }));
};

var ShortcutsPanel = function ShortcutsPanel() {
  var _getSystemActions = (0,data__WEBPACK_IMPORTED_MODULE_2__.getSystemActions)(),
      setShouldShowShortcuts = _getSystemActions.setShouldShowShortcuts;

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_panel__WEBPACK_IMPORTED_MODULE_1__.Panel, {
    title: "Keyboard Shortcuts",
    onClose: function onClose() {
      return setShouldShowShortcuts(false);
    },
    className: "fl-ui-help",
    style: {
      width: 360,
      maxWidth: '95vw'
    }
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(ShortcutsList, {
    shortcuts: FLBuilderConfig.keyboardShortcuts
  }));
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ShortcutsPanel);

/***/ }),

/***/ "./src/builder/ui/shortcuts-panel/panel/index.js":
/*!*******************************************************!*\
  !*** ./src/builder/ui/shortcuts-panel/panel/index.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Panel": () => /* binding */ Panel
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _art__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../art */ "./src/builder/ui/art/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./src/builder/ui/shortcuts-panel/panel/style.scss");
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }





var Panel = function Panel(_ref) {
  var className = _ref.className,
      children = _ref.children,
      title = _ref.title,
      actions = _ref.actions,
      _ref$showCloseButton = _ref.showCloseButton,
      showCloseButton = _ref$showCloseButton === void 0 ? true : _ref$showCloseButton,
      _ref$onClose = _ref.onClose,
      onClose = _ref$onClose === void 0 ? function () {} : _ref$onClose,
      rest = _objectWithoutProperties(_ref, ["className", "children", "title", "actions", "showCloseButton", "onClose"]);

  var classes = classnames__WEBPACK_IMPORTED_MODULE_1___default()({
    'fl-ui-panel-area': true
  }, className);

  var TrailingActions = function TrailingActions() {
    if (!actions && !showCloseButton) {
      return null;
    }

    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
      className: "fl-ui-panel-trailing-actions"
    }, actions, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button", {
      onClick: onClose,
      className: "fl-ui-button"
    }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_art__WEBPACK_IMPORTED_MODULE_2__.Icon.Close, null)));
  };

  var stopProp = function stopProp(e) {
    return e.stopPropagation();
  };

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: classes,
    onClick: onClose
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", _extends({
    className: "fl-ui-panel"
  }, rest, {
    onClick: stopProp
  }), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: "fl-ui-panel-topbar"
  }, title && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: "fl-ui-panel-title"
  }, title), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(TrailingActions, null)), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: "fl-ui-panel-content"
  }, children)));
};

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg) && arg.length) {
				var inner = classNames.apply(null, arg);
				if (inner) {
					classes.push(inner);
				}
			} else if (argType === 'object') {
				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./src/builder/ui/art/style.scss":
/*!***************************************!*\
  !*** ./src/builder/ui/art/style.scss ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/builder/ui/inline-editor/style.scss":
/*!*************************************************!*\
  !*** ./src/builder/ui/inline-editor/style.scss ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/builder/ui/notifications/style.scss":
/*!*************************************************!*\
  !*** ./src/builder/ui/notifications/style.scss ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/builder/ui/panel-manager/style.scss":
/*!*************************************************!*\
  !*** ./src/builder/ui/panel-manager/style.scss ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/builder/ui/shortcuts-panel/panel/style.scss":
/*!*********************************************************!*\
  !*** ./src/builder/ui/shortcuts-panel/panel/style.scss ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/builder/ui/shortcuts-panel/style.scss":
/*!***************************************************!*\
  !*** ./src/builder/ui/shortcuts-panel/style.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/builder/ui/style.scss":
/*!***********************************!*\
  !*** ./src/builder/ui/style.scss ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@beaverbuilder/app-core":
/*!***************************************!*\
  !*** external "FL.vendors.BBAppCore" ***!
  \***************************************/
/***/ ((module) => {

"use strict";
module.exports = FL.vendors.BBAppCore;

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

"use strict";
module.exports = React;

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/***/ ((module) => {

"use strict";
module.exports = ReactDOM;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => module['default'] :
/******/ 				() => module;
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => Object.prototype.hasOwnProperty.call(obj, prop)
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	// startup
/******/ 	// Load entry module
/******/ 	__webpack_require__("./src/builder/index.js");
/******/ 	// This entry module used 'exports' so it can't be inlined
/******/ })()
;
//# sourceMappingURL=builder.bundle.js.map