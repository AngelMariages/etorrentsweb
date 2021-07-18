/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@beaverbuilder/fluid/dist/index.es.js":
/*!************************************************************!*\
  !*** ./node_modules/@beaverbuilder/fluid/dist/index.es.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Button": () => /* binding */ V,
/* harmony export */   "Collection": () => /* binding */ fe,
/* harmony export */   "Layout": () => /* binding */ W,
/* harmony export */   "List": () => /* binding */ le,
/* harmony export */   "Menu": () => /* binding */ Q,
/* harmony export */   "Page": () => /* binding */ $,
/* harmony export */   "Text": () => /* binding */ C
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var framer_motion__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! framer-motion */ "framer-motion");
/* harmony import */ var framer_motion__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(framer_motion__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react-router-dom */ "react-router-dom");
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_router_dom__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _beaverbuilder_icons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @beaverbuilder/icons */ "@beaverbuilder/icons");
/* harmony import */ var _beaverbuilder_icons__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_beaverbuilder_icons__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react_laag__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react-laag */ "react-laag");
/* harmony import */ var react_laag__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_laag__WEBPACK_IMPORTED_MODULE_6__);
function x(e,t,l){return t in e?Object.defineProperty(e,t,{value:l,enumerable:!0,configurable:!0,writable:!0}):e[t]=l,e}function w(){return(w=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var l=arguments[t];for(var a in l)Object.prototype.hasOwnProperty.call(l,a)&&(e[a]=l[a])}return e}).apply(this,arguments)}function k(e,t){var l=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),l.push.apply(l,a)}return l}function T(e){for(var t=1;t<arguments.length;t++){var l=null!=arguments[t]?arguments[t]:{};t%2?k(Object(l),!0).forEach((function(t){x(e,t,l[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(l)):k(Object(l)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(l,t))}))}return e}function P(e,t){if(null==e)return{};var l,a,n=function(e,t){if(null==e)return{};var l,a,n={},r=Object.keys(e);for(a=0;a<r.length;a++)l=r[a],t.indexOf(l)>=0||(n[l]=e[l]);return n}(e,t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);for(a=0;a<r.length;a++)l=r[a],t.indexOf(l)>=0||Object.prototype.propertyIsEnumerable.call(e,l)&&(n[l]=e[l])}return n}const S=t=>{let{tag:l="div",eyebrow:a,eyebrowTag:n="div",subtitle:r,subtitleTag:i="div",children:s,className:c}=t,o=P(t,["tag","eyebrow","eyebrowTag","subtitle","subtitleTag","children","className"]);const d=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-text-title",c);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(l,w({className:d},o),a&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(n,{className:"fluid-text-eyebrow"},a),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{style:{display:"inline-flex"}},s),r&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(i,{className:"fluid-text-subtitle"},r))};var C=Object.freeze({__proto__:null,Title:S});const j=t=>{let{className:l,style:a,padX:n=!0,padY:r=!0,outset:i=!1,tag:s="div"}=t,c=P(t,["className","style","padX","padY","outset","tag"]);const o=classnames__WEBPACK_IMPORTED_MODULE_1___default()({"fluid-box":!0,"fluid-pad-x":n&&!i,"fluid-pad-y":r,"fluid-box-outset":i},l);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(s,w({className:o,style:a},c))},z=e=>Number.isInteger(e)&&0!==e?e+"px":"lg"===e||"large"===e?"var(--fluid-lg-space)":"med"===e||"medium"===e||"sm"===e||"small"===e?"var(--fluid-med-space)":e,D=(e,t,l)=>{if(t&&l)return l/t*100+"%";switch(e){case"square":case"1:1":return"100%";case"video":case"16:9":return"56.25%";case"poster":case"3:4":return"133.3%";default:const t=e.split(":");return 100/t[0]*t[1]+"%"}},B=t=>{let{children:l,className:a,ratio:n="square",style:r,width:i,height:s}=t,c=P(t,["children","className","ratio","style","width","height"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(j,w({padY:!1,padX:!1,className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-aspect-box",a),style:T(T({},r),{},{paddingTop:D(n,i,s)})},c),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",null,l))},I=t=>{let{className:l,align:a="center",style:n,padX:r=!1,padY:i=!1,gap:s=0,direction:c}=t,o=P(t,["className","align","style","padX","padY","gap","direction"]);const d=T({justifyContent:(m=a,"left"===m?"flex-start":"right"===m?"flex-end":m),"--fluid-gap":z(s),flexDirection:(e=>"reverse"===e?"row-reverse":e)(c)},n);var m;const u=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-row",l);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(j,w({padX:r,padY:i,className:u,style:d},o))},L=t=>{let{tag:l="div",className:a,children:n,size:r,style:i}=t,s=P(t,["tag","className","children","size","style"]);const c=Number.isInteger(r)?"".concat(r,"px"):r,o=T(T({},i),{},{flex:void 0!==c&&"0 0 ".concat(c)});return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(l,w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-split-pane",a),style:o},s),n)},Y=["lg","med","sm"],_=t=>{let{className:l,size:a="lg",isSticky:n=!0,tag:r="div"}=t,i=P(t,["className","size","isSticky","tag"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(r,w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-toolbar",{["fluid-size-".concat(a)]:Y.includes(a),"fluid-is-sticky":n},l)},i))},H=e=>{e.preventDefault(),e.stopPropagation()},X=({children:t})=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(framer_motion__WEBPACK_IMPORTED_MODULE_3__.motion.div,{initial:{scale:.8},animate:{scale:1},style:{background:"var(--fluid-box-background)",border:"2px solid var(--fluid-line-color)",flex:"1 1 auto",pointerEvents:"none",display:"flex",justifyContent:"center",alignItems:"center"}},t),F=(0,react__WEBPACK_IMPORTED_MODULE_0__.createContext)(),M=l=>{let{tag:a="div",children:n,className:r,onDrop:i=(()=>{}),hoverMessage:s=react__WEBPACK_IMPORTED_MODULE_0___default().createElement("h1",null,(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("You're Hovering..."))}=l,c=P(l,["tag","children","className","onDrop","hoverMessage"]);const[o,d]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(!1),[m,u]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)([]),g=e=>u(m.filter((t=>t.name!==e))),h={files:m,setFiles:u,removeFile:g},E=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-drop-area",{"is-hovering":o},r),N=e=>{d(!0),e.preventDefault(),e.stopPropagation()},v=e=>{d(!1),e.preventDefault(),e.stopPropagation()};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(F.Provider,{value:h},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(a,w({className:E},c,{onDrag:H,onDragStart:H,onDragOver:N,onDragLeave:v,onDragEnter:N,onDragEnd:v,onDrop:e=>{const t=Array.from(e.nativeEvent.dataTransfer.files);u(t),d(!1),0<t.length&&i(t,g),e.preventDefault(),e.stopPropagation()}}),o?react__WEBPACK_IMPORTED_MODULE_0___default().createElement(X,null,s):n))};M.use=()=>(0,react__WEBPACK_IMPORTED_MODULE_0__.useContext)(F);var W=Object.freeze({__proto__:null,Box:j,Row:I,Loading:t=>{let{className:l}=t,a=P(t,["className"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-loading-bar",l)},a),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-dot"}),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-dot"}),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-dot"}))},Headline:t=>{let{className:l}=t,a=P(t,["className"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-headline",l),role:"heading","aria-level":"2"},a))},Message:t=>{let{status:l,icon:a,className:n,children:r,tag:i="div"}=t,s=P(t,["status","icon","className","children","tag"]);const c=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-message",{"fluid-status-alert":"alert"==l,"fluid-status-destructive":"destructive"==l,"fluid-status-primary":"primary"==l},n);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(i,w({className:c},s),a&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-message-icon"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(a,null)),r)},AspectBox:B,Split:a=>{let{tag:n="div",panes:r=[],sizes:i=[240],className:s,isShowingFirstPane:c=!0,onToggleFirstPane:o=(()=>{})}=a,d=P(a,["tag","panes","sizes","className","isShowingFirstPane","onToggleFirstPane"]);const[m,u]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(c);(0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)((()=>u(c)),[c]);const f=T(T({},d),{},{toggleFirstPane:()=>{const e=!m;u(e),o(e)},isFirstPaneHidden:!m});return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(n,w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-split",s)},d),0<r.length&&r.map(((t,l)=>0!==l||m?react__WEBPACK_IMPORTED_MODULE_0___default().createElement(L,{className:"fluid-split-pane",key:l,size:i[l]},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,f)):null)))},Toolbar:_,ContentBoundary:t=>{let{tag:l="div",className:a}=t,n=P(t,["tag","className"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(l,w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-content-boundary",a)},n))},DropArea:M});class A extends react__WEBPACK_IMPORTED_MODULE_0__.Component{constructor(e){super(e),this.state={hasError:!1,error:null}}static getDerivedStateFromError(e){return{hasError:!0,error:e}}render(){const{alternate:e=R,children:t}=this.props,{hasError:l,error:a}=this.state;return l?(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(e,{error:a}):t}}const R=({error:t})=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-default-error-message",style:{display:"flex",flexDirection:"column",flex:"1 0 auto",justifyContent:"center",alignItems:"center",padding:20}},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",null,"There seems to be an error."),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("code",null,t.message)),G=["normal","transparent","elevator"],q=["sm","med","lg"],J=["round"],K=(0,react__WEBPACK_IMPORTED_MODULE_0__.forwardRef)(((t,l)=>{const{tag:a,className:n,to:r,type:i="button",href:s,onClick:c,isSelected:o=!1,appearance:d="normal",status:m,icon:u,size:f,shape:g,isLoading:h=!1,disabled:N,children:y}=t,b=P(t,["tag","className","to","type","href","onClick","isSelected","appearance","status","icon","size","shape","isLoading","disabled","children"]);let O=T({ref:l,className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-button",{"is-selected":o,["fluid-status-".concat(m)]:m,["fluid-size-".concat(f)]:q.includes(f),["fluid-appearance-".concat(d)]:G.includes(d),["fluid-shape-".concat(g)]:g&&J.includes(g)},n),role:"button",disabled:N||h},b),x="button";return a?x=a:r||s?(x="a",s?O.href=s:(x=react_router_dom__WEBPACK_IMPORTED_MODULE_4__.Link,O.to=r)):(O.onClick=c,O.type=i),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(x,O,(u||h)&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{className:"fluid-button-icon"},!0===h?react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_beaverbuilder_icons__WEBPACK_IMPORTED_MODULE_5__.Loading,null):u),y&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",null,y))})),Q=t=>{let{children:l,content:a,isShowing:n,onOutsideClick:r=(()=>{}),className:i,style:s}=t,o=P(t,["children","content","isShowing","onOutsideClick","className","style"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react_laag__WEBPACK_IMPORTED_MODULE_6__.ToggleLayer,{isOpen:n,closeOnOutsideClick:!0,onOutsideClick:r,placement:{anchor:"BOTTOM_RIGHT",possibleAnchors:["BOTTOM_LEFT","BOTTOM_CENTER","BOTTOM_RIGHT"]},renderLayer:({layerProps:t,isOpen:l})=>l&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({},o,t,{style:T(T({},s),t.style),className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-menu",t.className,i)}),a)},(({triggerRef:e})=>(0,react__WEBPACK_IMPORTED_MODULE_0__.cloneElement)(l,{ref:e})))};Q.Item=t=>{let{className:l}=t,a=P(t,["className"]);const n=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-menu-item",l);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(V,w({className:n,appearance:"transparent"},a))};const U=({className:t,direction:l="horizontal",isHidden:a=!1})=>{const n=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-divider",{"fluid-vertical-divider":"vertical"===l,"fluid-horizontal-divider":"horizontal"===l,"fluid-is-hidden":a},t);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("hr",{className:n})},V=K;V.Group=l=>{let{tag:a="div",children:n,className:r,direction:i="row",appearance:s="normal",shouldHandleOverflow:c=!1,label:u,moreMenu:f}=l,g=P(l,["tag","children","className","direction","appearance","shouldHandleOverflow","label","moreMenu"]);const[h,E]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),[N,v]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(!0),b=(0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(),O="normal"===s,x="row"===i?"vertical":"horizontal";let k=react__WEBPACK_IMPORTED_MODULE_0__.Children.map(n,(e=>e||null));(0,react__WEBPACK_IMPORTED_MODULE_0__.useLayoutEffect)((()=>{if(c){if(b.current){const e=b.current,t=window.getComputedStyle(e),l=parseInt(t.paddingLeft)+parseInt(t.paddingRight),a=e.querySelector(".fluid-more-button"),n=e.clientWidth-l;if((a?e.scrollWidth-(l+a.offsetWidth):e.scrollWidth-l)>n){v(!0);const t=n-a.offsetWidth;let l=0,r=0;for(let a of e.childNodes)l+=a.offsetWidth,l>t||r++;E(r)}else v(!1),E(null)}}else v(!1)}),[n]);const S=classnames__WEBPACK_IMPORTED_MODULE_1___default()({"fluid-button-group":!0,["fluid-button-group-".concat(i)]:i,["fluid-button-group-appearance-".concat(s)]:s},r),C=T(T({},g),{},{className:S,role:g.role?g.role:"group",ref:b}),j=()=>f||react__WEBPACK_IMPORTED_MODULE_0__.Children.map(n,((t,l)=>!t||t.props.excludeFromMenu?null:react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Q.Item,w({key:l},t.props)))),z=()=>{const[l,a]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(!1);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment),null,O&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(U,{className:"fluid-more-button-divider",direction:x}),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Q,{content:react__WEBPACK_IMPORTED_MODULE_0___default().createElement(j,null),isShowing:l,onOutsideClick:()=>a(!1)},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(K,{className:"fluid-more-button",isSelected:l,onClick:()=>a(!l)},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_beaverbuilder_icons__WEBPACK_IMPORTED_MODULE_5__.More,null))))};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment),null,u&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("label",null,u),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(a,C,((e,t=null)=>Number.isInteger(t)?react__WEBPACK_IMPORTED_MODULE_0__.Children.map(e,((e,l)=>l+1>t?null:e)):e)(k,h),N&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(z,null)))};const Z=t=>{const l=(0,react_router_dom__WEBPACK_IMPORTED_MODULE_4__.useHistory)();return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(V,w({className:"fluid-back-button",appearance:"transparent",onClick:l.goBack},t),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_beaverbuilder_icons__WEBPACK_IMPORTED_MODULE_5__.BackArrow,null))},$=t=>{let{children:a,className:n,hero:r,title:i,icon:s,toolbar:c,topContentStyle:o,actions:d,header:m,footer:u,onLoad:f=(()=>{}),shouldScroll:g=!0,shouldShowBackButton:h=(e=>e),style:E={},padX:N=!0,padY:v=!0,contentWrapStyle:y=null,tag:b="div",contentBoxTag:O="div",contentBoxProps:x={},contentBoxStyle:k=null,overlay:S}=t,C=P(t,["children","className","hero","title","icon","toolbar","topContentStyle","actions","header","footer","onLoad","shouldScroll","shouldShowBackButton","style","padX","padY","contentWrapStyle","tag","contentBoxTag","contentBoxProps","contentBoxStyle","overlay"]);const z=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-page",n);(0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(f,[]);const D="function"==typeof h?h():h,B=({children:t})=>{if(!t)return null;const l="string"==typeof t;return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{style:{transformOrigin:"0 0",flex:"0 0 auto",borderBottom:"2px solid var(--fluid-line-color)"}},l&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("img",{src:t,style:{width:"100%"}}),!l&&t)},I=T(T({},E),{},{overflowX:"hidden",overflowY:g?"scroll":"hidden",perspective:1,perspectiveOrigin:"0 0"}),L=T({maxHeight:g?"":"100%",minHeight:0,flexShrink:g?0:1},k),Y=T({flexGrow:1,flexShrink:1,minHeight:0,maxHeight:"100%"},y);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(b,{className:"fluid-page-wrap",style:{flex:"1 1 auto",position:"relative",minHeight:0,maxHeight:"100%",minWidth:0,maxWidth:"100%",display:"flex",flexDirection:"column"}},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({className:z},C,{style:I}),r&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(B,null,r),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(O,w({className:"fluid-page-content"},x,{style:L}),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-sticky-element fluid-page-top-content",style:o},c,!1!==c&&!c&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_,{className:"fluid-page-top-toolbar"},D&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Z,null),s&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{className:"fluid-page-title-icon"},s),i&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-page-toolbar-content"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{className:"fluid-page-title",role:"heading","aria-level":"1",style:{flex:"1 1 auto"}},i)),d&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{className:"fluid-page-actions"},d)),m&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_,{size:"sm",className:"fluid-page-header"},m)),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(j,{padX:N,padY:v,style:Y},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(A,null,a)))),u&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-page-footer"},u),S&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-page-overlay"},S))};$.Section=t=>{let{children:l,className:a,label:n,handle:r,contentStyle:i={},padX:s=!0,padY:c=!0,footer:o,description:d}=t,m=P(t,["children","className","label","handle","contentStyle","padX","padY","footer","description"]);const u=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-section",{["".concat(r,"-section")]:r},a);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({className:u},m),n&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-section-title"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{className:"fluid-section-title-text"},n)),d&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(I,{className:"fluid-section-description"},d),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(j,{className:"fluid-section-content",padX:s,padY:c,style:i},l),o&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(j,{padY:!1,className:"fluid-section-footer"},o))};const ee=({isOpen:t=!1})=>{const l={transform:t?"rotate( 90deg )":"rotate( 0deg )"};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("svg",{width:"6",height:"10",viewBox:"0 0 6 10",fill:"none",xmlns:"http://www.w3.org/2000/svg",style:l},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("path",{d:"M1 9L5 5.1875L1 1.375",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}))},te=(e,t,l={})=>l;var le=Object.freeze({__proto__:null,Section:t=>{let{tag:l="div",contentTag:a="ul",title:n,className:r,children:i}=t,s=P(t,["tag","contentTag","title","className","children"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(l,w({className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-list-section",r)},s),n&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-list-section-title"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",null,n)),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(a,{className:"fluid-list-section-content fluid-list"},i))},Item:l=>{let{tag:a="li",contentTag:n="div",title:r,eyebrow:i,subtitle:s,to:c,href:o,onClick:m,rel:u,target:f,thumbnail:g,size:h="med",children:E,showChildren:N=!0,actions:v,className:y}=l,b=P(l,["tag","contentTag","title","eyebrow","subtitle","to","href","onClick","rel","target","thumbnail","size","children","showChildren","actions","className"]);const[O,x]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(N),k=0<react__WEBPACK_IMPORTED_MODULE_0__.Children.count(E),T=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-item",{"fluid-size-sm":"sm"===h,"fluid-size-med":"med"===h,"fluid-size-lg":"lg"===h,"fluid-has-children":k},y);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(a,w({className:T},b),r&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(n,{className:"fluid-item-content"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(V,{className:"fluid-item-primary-action",to:c,href:o,onClick:m,rel:u,target:f},g&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-item-thumbnail"},g),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(S,{eyebrow:i,subtitle:s},r)),v&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-item-actions"},v),k&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-item-gutter-content"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",{onClick:()=>x(!O)},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(ee,{isOpen:O})))),E&&O&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("ul",{className:"fluid-list"},E))},Iterator:({items:t=[],getCell:l=(()=>react__WEBPACK_IMPORTED_MODULE_0__.Fragment),getCellProps:a=te,getCellNames:n=(()=>["main"]),getRow:r=(()=>react__WEBPACK_IMPORTED_MODULE_0__.Fragment),getRowProps:i=(()=>{})})=>{const s=n(t),c=r(t);return(e=>Array.isArray(e)?e:Object.values(e))(t).map(((t,n)=>{const r=i(t);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(c,w({key:n},r),s.map(((n,r)=>{const i=l(n,t),s=a(n,t,r);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(i,w({key:r},s))})))}))}});const ae=(0,react__WEBPACK_IMPORTED_MODULE_0__.createContext)({appearance:"grid"}),ne={initial:{scale:0},normal:{scale:1}},re={layoutX:{duration:0},layoutY:!1},ie=t=>{let{tag:l=framer_motion__WEBPACK_IMPORTED_MODULE_3__.motion.li,title:a,description:r,thumbnail:i,thumbnailProps:s,truncateTitle:c=!0,icon:o,onClick:d,href:m,to:u,className:f,children:h}=t,E=P(t,["tag","title","description","thumbnail","thumbnailProps","truncateTitle","icon","onClick","href","to","className","children"]);const{appearance:N}=(0,react__WEBPACK_IMPORTED_MODULE_0__.useContext)(ae),v="list"===N?de:oe,y={title:a,truncateTitle:c,thumbnail:i,thumbnailProps:s,description:r,icon:o},b={onClick:d,href:m,to:u,className:"fluid-collection-item-primary-action",appearance:"transparent"};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(l,w({layout:!0,initial:!1,animate:"normal",exit:"initial",variants:ne,transition:re,className:classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-collection-item",f)},E),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(V,b,(a||i)&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(v,y),h))},se=t=>{let{children:l,ratio:a="4:3"}=t,n=P(t,["children","ratio"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:"fluid-collection-item-thumbnail"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(B,w({ratio:a},n),l))},ce=t=>{let{title:l,description:a,truncate:n,icon:r}=t,i=P(t,["title","description","truncate","icon"]);if(!l&&!a)return null;const s=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-item-title",{"fluid-truncate":n});return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({className:"fluid-collection-item-text"},i),(l||r)&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:s},r&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",null,r),l),a&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",null,a))},oe=t=>{let{title:l,description:a,truncateTitle:n,thumbnail:r,thumbnailProps:i,icon:s,tag:c="div"}=t,o=P(t,["title","description","truncateTitle","thumbnail","thumbnailProps","icon","tag"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(c,w({className:"fluid-collection-item-grid-content"},o),r&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(se,i,r),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(ce,{title:l,truncate:n,description:a,icon:s}))},de=t=>{let{title:l,description:a,truncateTitle:n,thumbnail:r,thumbnailProps:i,icon:s}=t,c=P(t,["title","description","truncateTitle","thumbnail","thumbnailProps","icon"]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",w({className:"fluid-collection-item-list-content"},c),r&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(se,i,r),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(ce,{title:l,truncate:n,description:a,icon:s}))},me=t=>{let l=w({},t);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(ie,w({thumbnail:!0,title:"loading..."},l))},ue=["grid","list"],pe=({total:t=4})=>Array(t).fill(0).map(((t,l)=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(me,{key:l}))),fe=t=>{let{tag:l="ul",appearance:a="grid",maxItems:n,className:r,children:i,isLoading:s=!1,loadingItems:c}=t,o=P(t,["tag","appearance","maxItems","className","children","isLoading","loadingItems"]);const m=classnames__WEBPACK_IMPORTED_MODULE_1___default()("fluid-collection",{["fluid-collection-appearance-".concat(a)]:ue.includes(a)},r),u={appearance:a};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(ae.Provider,{value:u},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(framer_motion__WEBPACK_IMPORTED_MODULE_3__.AnimatePresence,null,react__WEBPACK_IMPORTED_MODULE_0___default().createElement(l,w({className:m},o),s&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(pe,{total:c}),!s&&((e,t=null)=>Number.isInteger(t)?react__WEBPACK_IMPORTED_MODULE_0__.Children.map(e,((e,l)=>l+1>t?null:e)):react__WEBPACK_IMPORTED_MODULE_0__.Children.toArray(e))(i,n))))};fe.Item=ie,fe.use=()=>(0,react__WEBPACK_IMPORTED_MODULE_0__.useContext)(ae);


/***/ }),

/***/ "./src/vendors/bb-fluid.js":
/*!*********************************!*\
  !*** ./src/vendors/bb-fluid.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vendor_fluid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vendor-fluid */ "./node_modules/@beaverbuilder/fluid/dist/index.es.js");
/* harmony import */ var vendor_fluid_dist_index_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vendor-fluid/dist/index.css */ "./node_modules/@beaverbuilder/fluid/dist/index.css");


window.FL = window.FL || {};
FL.vendors = FL.vendors || {};
FL.vendors.BBFluid = vendor_fluid__WEBPACK_IMPORTED_MODULE_0__;

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

/***/ "./node_modules/@beaverbuilder/fluid/dist/index.css":
/*!**********************************************************!*\
  !*** ./node_modules/@beaverbuilder/fluid/dist/index.css ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@beaverbuilder/icons":
/*!*************************************!*\
  !*** external "FL.vendors.BBIcons" ***!
  \*************************************/
/***/ ((module) => {

"use strict";
module.exports = FL.vendors.BBIcons;

/***/ }),

/***/ "framer-motion":
/*!*******************************!*\
  !*** external "FramerMotion" ***!
  \*******************************/
/***/ ((module) => {

"use strict";
module.exports = FramerMotion;

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

"use strict";
module.exports = React;

/***/ }),

/***/ "react-laag":
/*!****************************!*\
  !*** external "ReactLaag" ***!
  \****************************/
/***/ ((module) => {

"use strict";
module.exports = ReactLaag;

/***/ }),

/***/ "react-router-dom":
/*!*********************************!*\
  !*** external "ReactRouterDOM" ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = ReactRouterDOM;

/***/ }),

/***/ "@wordpress/i18n":
/*!**************************!*\
  !*** external "wp.i18n" ***!
  \**************************/
/***/ ((module) => {

"use strict";
module.exports = wp.i18n;

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
/******/ 	__webpack_require__("./src/vendors/bb-fluid.js");
/******/ 	// This entry module used 'exports' so it can't be inlined
/******/ })()
;
//# sourceMappingURL=vendor-bb-fluid.bundle.js.map