/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@beaverbuilder/app-core/dist/index.es.js":
/*!***************************************************************!*\
  !*** ./node_modules/@beaverbuilder/app-core/dist/index.es.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "App": () => /* binding */ ee,
/* harmony export */   "Error": () => /* binding */ F,
/* harmony export */   "Root": () => /* binding */ G,
/* harmony export */   "createAppState": () => /* binding */ S,
/* harmony export */   "createStoreRegistry": () => /* binding */ D
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var redux__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! redux */ "redux");
/* harmony import */ var redux__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(redux__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-router-dom */ "react-router-dom");
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_router_dom__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
const A={handle:"",label:"",render:()=>null,icon:()=>null,isEnabled:!0},S=(e=A)=>({reducers:{apps:(t={},r)=>{switch(r.type){case"REGISTER_APP":return{[r.handle]:{...e,handle:r.handle,...r.config},...t};case"UNREGISTER_APP":return delete t[r.handle],{...t};case"UPDATE_APP":return{...t[r.handle],...r.state,...t};default:return t}}},actions:{registerApp:(e="",t={})=>({type:"REGISTER_APP",handle:e,config:t}),unregisterApp:(e="")=>({type:"UNREGISTER_APP",handle:e}),updateApp:(e="",t={})=>({type:"UPDATE_APP",state:t})}});var O="undefined"!=typeof globalThis?globalThis:"undefined"!=typeof window?window:"undefined"!=typeof __webpack_require__.g?__webpack_require__.g:"undefined"!=typeof self?self:{};var P,z=(function(e,t){var r="__lodash_hash_undefined__",n=9007199254740991,o="[object Arguments]",a="[object Array]",s="[object Boolean]",c="[object Date]",i="[object Error]",u="[object Function]",l="[object Map]",f="[object Number]",p="[object Object]",h="[object Promise]",d="[object RegExp]",y="[object Set]",_="[object String]",g="[object Symbol]",b="[object WeakMap]",v="[object ArrayBuffer]",m="[object DataView]",E=/^\[object .+?Constructor\]$/,j=/^(?:0|[1-9]\d*)$/,w={};w["[object Float32Array]"]=w["[object Float64Array]"]=w["[object Int8Array]"]=w["[object Int16Array]"]=w["[object Int32Array]"]=w["[object Uint8Array]"]=w["[object Uint8ClampedArray]"]=w["[object Uint16Array]"]=w["[object Uint32Array]"]=!0,w[o]=w[a]=w[v]=w[s]=w[m]=w[c]=w[i]=w[u]=w[l]=w[f]=w[p]=w[d]=w[y]=w[_]=w[b]=!1;var A="object"==typeof O&&O&&O.Object===Object&&O,S="object"==typeof self&&self&&self.Object===Object&&self,P=A||S||Function("return this")(),z=t&&!t.nodeType&&t,C=z&&e&&!e.nodeType&&e,x=C&&C.exports===z,U=x&&A.process,T=function(){try{return U&&U.binding&&U.binding("util")}catch(e){}}(),k=T&&T.isTypedArray;function R(e,t){for(var r=-1,n=null==e?0:e.length;++r<n;)if(t(e[r],r,e))return!0;return!1}function I(e){var t=-1,r=Array(e.size);return e.forEach((function(e,n){r[++t]=[n,e]})),r}function L(e){var t=-1,r=Array(e.size);return e.forEach((function(e){r[++t]=e})),r}var B,D,N,F=Array.prototype,M=Function.prototype,$=Object.prototype,G=P["__core-js_shared__"],H=M.toString,V=$.hasOwnProperty,J=(B=/[^.]+$/.exec(G&&G.keys&&G.keys.IE_PROTO||""))?"Symbol(src)_1."+B:"",W=$.toString,X=RegExp("^"+H.call(V).replace(/[\\^$.*+?()[\]{}|]/g,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$"),Z=x?P.Buffer:void 0,q=P.Symbol,K=P.Uint8Array,Q=$.propertyIsEnumerable,Y=F.splice,ee=q?q.toStringTag:void 0,te=Object.getOwnPropertySymbols,re=Z?Z.isBuffer:void 0,ne=(D=Object.keys,N=Object,function(e){return D(N(e))}),oe=Te(P,"DataView"),ae=Te(P,"Map"),se=Te(P,"Promise"),ce=Te(P,"Set"),ie=Te(P,"WeakMap"),ue=Te(Object,"create"),le=Le(oe),fe=Le(ae),pe=Le(se),he=Le(ce),de=Le(ie),ye=q?q.prototype:void 0,_e=ye?ye.valueOf:void 0;function ge(e){var t=-1,r=null==e?0:e.length;for(this.clear();++t<r;){var n=e[t];this.set(n[0],n[1])}}function be(e){var t=-1,r=null==e?0:e.length;for(this.clear();++t<r;){var n=e[t];this.set(n[0],n[1])}}function ve(e){var t=-1,r=null==e?0:e.length;for(this.clear();++t<r;){var n=e[t];this.set(n[0],n[1])}}function me(e){var t=-1,r=null==e?0:e.length;for(this.__data__=new ve;++t<r;)this.add(e[t])}function Ee(e){var t=this.__data__=new be(e);this.size=t.size}function je(e,t){var r=Ne(e),n=!r&&De(e),o=!r&&!n&&Fe(e),a=!r&&!n&&!o&&Ve(e),s=r||n||o||a,c=s?function(e,t){for(var r=-1,n=Array(e);++r<e;)n[r]=t(r);return n}(e.length,String):[],i=c.length;for(var u in e)!t&&!V.call(e,u)||s&&("length"==u||o&&("offset"==u||"parent"==u)||a&&("buffer"==u||"byteLength"==u||"byteOffset"==u)||Ie(u,i))||c.push(u);return c}function we(e,t){for(var r=e.length;r--;)if(Be(e[r][0],t))return r;return-1}function Ae(e){return null==e?void 0===e?"[object Undefined]":"[object Null]":ee&&ee in Object(e)?function(e){var t=V.call(e,ee),r=e[ee];try{e[ee]=void 0;var n=!0}catch(e){}var o=W.call(e);return n&&(t?e[ee]=r:delete e[ee]),o}(e):function(e){return W.call(e)}(e)}function Se(e){return He(e)&&Ae(e)==o}function Oe(e,t,r,n,u){return e===t||(null==e||null==t||!He(e)&&!He(t)?e!=e&&t!=t:function(e,t,r,n,u,h){var b=Ne(e),E=Ne(t),j=b?a:Re(e),w=E?a:Re(t),A=(j=j==o?p:j)==p,S=(w=w==o?p:w)==p,O=j==w;if(O&&Fe(e)){if(!Fe(t))return!1;b=!0,A=!1}if(O&&!A)return h||(h=new Ee),b||Ve(e)?Ce(e,t,r,n,u,h):function(e,t,r,n,o,a,u){switch(r){case m:if(e.byteLength!=t.byteLength||e.byteOffset!=t.byteOffset)return!1;e=e.buffer,t=t.buffer;case v:return!(e.byteLength!=t.byteLength||!a(new K(e),new K(t)));case s:case c:case f:return Be(+e,+t);case i:return e.name==t.name&&e.message==t.message;case d:case _:return e==t+"";case l:var p=I;case y:var h=1&n;if(p||(p=L),e.size!=t.size&&!h)return!1;var b=u.get(e);if(b)return b==t;n|=2,u.set(e,t);var E=Ce(p(e),p(t),n,o,a,u);return u.delete(e),E;case g:if(_e)return _e.call(e)==_e.call(t)}return!1}(e,t,j,r,n,u,h);if(!(1&r)){var P=A&&V.call(e,"__wrapped__"),z=S&&V.call(t,"__wrapped__");if(P||z){var C=P?e.value():e,x=z?t.value():t;return h||(h=new Ee),u(C,x,r,n,h)}}return!!O&&(h||(h=new Ee),function(e,t,r,n,o,a){var s=1&r,c=xe(e),i=c.length,u=xe(t).length;if(i!=u&&!s)return!1;for(var l=i;l--;){var f=c[l];if(!(s?f in t:V.call(t,f)))return!1}var p=a.get(e);if(p&&a.get(t))return p==t;var h=!0;a.set(e,t),a.set(t,e);for(var d=s;++l<i;){var y=e[f=c[l]],_=t[f];if(n)var g=s?n(_,y,f,t,e,a):n(y,_,f,e,t,a);if(!(void 0===g?y===_||o(y,_,r,n,a):g)){h=!1;break}d||(d="constructor"==f)}if(h&&!d){var b=e.constructor,v=t.constructor;b==v||!("constructor"in e)||!("constructor"in t)||"function"==typeof b&&b instanceof b&&"function"==typeof v&&v instanceof v||(h=!1)}return a.delete(e),a.delete(t),h}(e,t,r,n,u,h))}(e,t,r,n,Oe,u))}function Pe(e){return!(!Ge(e)||function(e){return!!J&&J in e}(e))&&(Me(e)?X:E).test(Le(e))}function ze(e){if(r=(t=e)&&t.constructor,n="function"==typeof r&&r.prototype||$,t!==n)return ne(e);var t,r,n,o=[];for(var a in Object(e))V.call(e,a)&&"constructor"!=a&&o.push(a);return o}function Ce(e,t,r,n,o,a){var s=1&r,c=e.length,i=t.length;if(c!=i&&!(s&&i>c))return!1;var u=a.get(e);if(u&&a.get(t))return u==t;var l=-1,f=!0,p=2&r?new me:void 0;for(a.set(e,t),a.set(t,e);++l<c;){var h=e[l],d=t[l];if(n)var y=s?n(d,h,l,t,e,a):n(h,d,l,e,t,a);if(void 0!==y){if(y)continue;f=!1;break}if(p){if(!R(t,(function(e,t){if(s=t,!p.has(s)&&(h===e||o(h,e,r,n,a)))return p.push(t);var s}))){f=!1;break}}else if(h!==d&&!o(h,d,r,n,a)){f=!1;break}}return a.delete(e),a.delete(t),f}function xe(e){return function(e,t,r){var n=t(e);return Ne(e)?n:function(e,t){for(var r=-1,n=t.length,o=e.length;++r<n;)e[o+r]=t[r];return e}(n,r(e))}(e,Je,ke)}function Ue(e,t){var r,n,o=e.__data__;return("string"==(n=typeof(r=t))||"number"==n||"symbol"==n||"boolean"==n?"__proto__"!==r:null===r)?o["string"==typeof t?"string":"hash"]:o.map}function Te(e,t){var r=function(e,t){return null==e?void 0:e[t]}(e,t);return Pe(r)?r:void 0}ge.prototype.clear=function(){this.__data__=ue?ue(null):{},this.size=0},ge.prototype.delete=function(e){var t=this.has(e)&&delete this.__data__[e];return this.size-=t?1:0,t},ge.prototype.get=function(e){var t=this.__data__;if(ue){var n=t[e];return n===r?void 0:n}return V.call(t,e)?t[e]:void 0},ge.prototype.has=function(e){var t=this.__data__;return ue?void 0!==t[e]:V.call(t,e)},ge.prototype.set=function(e,t){var n=this.__data__;return this.size+=this.has(e)?0:1,n[e]=ue&&void 0===t?r:t,this},be.prototype.clear=function(){this.__data__=[],this.size=0},be.prototype.delete=function(e){var t=this.__data__,r=we(t,e);return!(r<0||(r==t.length-1?t.pop():Y.call(t,r,1),--this.size,0))},be.prototype.get=function(e){var t=this.__data__,r=we(t,e);return r<0?void 0:t[r][1]},be.prototype.has=function(e){return we(this.__data__,e)>-1},be.prototype.set=function(e,t){var r=this.__data__,n=we(r,e);return n<0?(++this.size,r.push([e,t])):r[n][1]=t,this},ve.prototype.clear=function(){this.size=0,this.__data__={hash:new ge,map:new(ae||be),string:new ge}},ve.prototype.delete=function(e){var t=Ue(this,e).delete(e);return this.size-=t?1:0,t},ve.prototype.get=function(e){return Ue(this,e).get(e)},ve.prototype.has=function(e){return Ue(this,e).has(e)},ve.prototype.set=function(e,t){var r=Ue(this,e),n=r.size;return r.set(e,t),this.size+=r.size==n?0:1,this},me.prototype.add=me.prototype.push=function(e){return this.__data__.set(e,r),this},me.prototype.has=function(e){return this.__data__.has(e)},Ee.prototype.clear=function(){this.__data__=new be,this.size=0},Ee.prototype.delete=function(e){var t=this.__data__,r=t.delete(e);return this.size=t.size,r},Ee.prototype.get=function(e){return this.__data__.get(e)},Ee.prototype.has=function(e){return this.__data__.has(e)},Ee.prototype.set=function(e,t){var r=this.__data__;if(r instanceof be){var n=r.__data__;if(!ae||n.length<199)return n.push([e,t]),this.size=++r.size,this;r=this.__data__=new ve(n)}return r.set(e,t),this.size=r.size,this};var ke=te?function(e){return null==e?[]:(e=Object(e),function(e,t){for(var r=-1,n=null==e?0:e.length,o=0,a=[];++r<n;){var s=e[r];t(s,r,e)&&(a[o++]=s)}return a}(te(e),(function(t){return Q.call(e,t)})))}:function(){return[]},Re=Ae;function Ie(e,t){return!!(t=null==t?n:t)&&("number"==typeof e||j.test(e))&&e>-1&&e%1==0&&e<t}function Le(e){if(null!=e){try{return H.call(e)}catch(e){}try{return e+""}catch(e){}}return""}function Be(e,t){return e===t||e!=e&&t!=t}(oe&&Re(new oe(new ArrayBuffer(1)))!=m||ae&&Re(new ae)!=l||se&&Re(se.resolve())!=h||ce&&Re(new ce)!=y||ie&&Re(new ie)!=b)&&(Re=function(e){var t=Ae(e),r=t==p?e.constructor:void 0,n=r?Le(r):"";if(n)switch(n){case le:return m;case fe:return l;case pe:return h;case he:return y;case de:return b}return t});var De=Se(function(){return arguments}())?Se:function(e){return He(e)&&V.call(e,"callee")&&!Q.call(e,"callee")},Ne=Array.isArray,Fe=re||function(){return!1};function Me(e){if(!Ge(e))return!1;var t=Ae(e);return t==u||"[object GeneratorFunction]"==t||"[object AsyncFunction]"==t||"[object Proxy]"==t}function $e(e){return"number"==typeof e&&e>-1&&e%1==0&&e<=n}function Ge(e){var t=typeof e;return null!=e&&("object"==t||"function"==t)}function He(e){return null!=e&&"object"==typeof e}var Ve=k?function(e){return function(t){return e(t)}}(k):function(e){return He(e)&&$e(e.length)&&!!w[Ae(e)]};function Je(e){return null!=(t=e)&&$e(t.length)&&!Me(t)?je(e):ze(e);var t}e.exports=function(e,t){return Oe(e,t)}}(P={exports:{}},P.exports),P.exports);const C=(e,t,r)=>"boolean"==typeof e?e:"function"==typeof e?e(t,r):"string"==typeof e?!z(t[e],r[e]):!!Array.isArray(e)&&e.some((e=>!z(t[e],r[e]))),x=(e,t)=>{if("string"!=typeof e&&!Array.isArray(e))throw new TypeError("Expected the input to be `string | string[]`");t=Object.assign({pascalCase:!1},t);if(0===(e=Array.isArray(e)?e.map((e=>e.trim())).filter((e=>e.length)).join("-"):e.trim()).length)return"";if(1===e.length)return t.pascalCase?e.toUpperCase():e.toLowerCase();return e!==e.toLowerCase()&&(e=(e=>{let t=!1,r=!1,n=!1;for(let o=0;o<e.length;o++){const a=e[o];t&&/[a-zA-Z]/.test(a)&&a.toUpperCase()===a?(e=e.slice(0,o)+"-"+e.slice(o),t=!1,n=r,r=!0,o++):r&&n&&/[a-zA-Z]/.test(a)&&a.toLowerCase()===a?(e=e.slice(0,o-1)+"-"+e.slice(o-1),n=r,r=!1,t=!0):(t=a.toLowerCase()===a&&a.toUpperCase()!==a,n=r,r=a.toUpperCase()===a&&a.toLowerCase()!==a)}return e})(e)),e=e.replace(/^[_.\- ]+/,"").toLowerCase().replace(/[_.\- ]+(\w|$)/g,((e,t)=>t.toUpperCase())).replace(/\d+(\w|$)/g,(e=>e.toUpperCase())),r=e,t.pascalCase?r.charAt(0).toUpperCase()+r.slice(1):r;var r};var U=x,T=x;U.default=T;const k=(e,t,r)=>(Object.entries(r).map((([r])=>{if(!t[r]){const t="SET_".concat(r.toUpperCase()),n=U("set_".concat(r));e[n]=e=>({type:t,value:e})}})),e),R=(e,t)=>Object.keys(e).length||Object.keys(t).length?(Object.entries(t).map((([t,r])=>{e[t]||(e[t]=(e=r,n)=>{switch(n.type){case"SET_".concat(t.toUpperCase()):return n.value;default:return e}})})),(0,redux__WEBPACK_IMPORTED_MODULE_1__.combineReducers)(e)):e=>e,I=(e,t)=>{const r=window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__;return(r?r({name:e}):redux__WEBPACK_IMPORTED_MODULE_1__.compose)((0,redux__WEBPACK_IMPORTED_MODULE_1__.applyMiddleware)(L(t)))},L=e=>{const{before:t,after:r}=e;return e=>n=>o=>{t&&t[o.type]&&t[o.type](o,e);const a=n(o);return r&&r[o.type]&&r[o.type](o,e),a}},B=e=>e.charAt(0).toUpperCase()+e.slice(1);const D=()=>{const e={};return{registerStore:(t,{state:r={},cache:n=[],actions:o={},reducers:a={},selectors:s={},effects:c={}})=>{if(!t)throw new Error("Missing key required for registerStore.");if(e[t])throw new Error("A store with the key '".concat(t,"' already exists."));const i=((e,t)=>{const r=localStorage.getItem(e);if(r){const e=JSON.parse(r);return{...t,...e}}return t})(t,r);var u,l,f;e[t]={actions:k(o,a,i),store:(0,redux__WEBPACK_IMPORTED_MODULE_1__.createStore)(R(a,i),i,I(t,c))},e[t].selectors=((e,t)=>{const r={},n=t.getState();return Object.entries(n).map((([t])=>{const r=U("get_".concat(t));e[r]||(e[r]=e=>e[t])})),Object.entries(e).map((([e,n])=>{r[e]=(...e)=>n(t.getState(),...e)})),r})(s,e[t].store),u=t,l=e[t].store,(f=n).length&&l.subscribe((()=>{const e=l.getState(),t={};f.map((r=>{t[r]=e[r]})),localStorage.setItem(u,JSON.stringify(t))}))},useStore:(n,a=!0)=>{const{store:s}=e[n],c=s.getState(),i=(0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(c),[u,l]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(c);return (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)((()=>(l(s.getState()),s.subscribe((()=>{const e=s.getState();C(a,i.current,e)&&l({...e}),i.current=e})))),[]),u},getStore:t=>e[t].store,getDispatch:t=>{const{actions:r,store:n}=e[t];return (0,redux__WEBPACK_IMPORTED_MODULE_1__.bindActionCreators)(r,n.dispatch)},getSelectors:t=>e[t].selectors,getHooks:o=>{const{actions:a,store:s}=e[o];return((e,o)=>{const a=e.getState(),s={};return Object.keys(a).map((a=>{const c="use".concat(B(a));s[c]=(s=!0)=>{const[c,i]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(e.getState()[a]),u=(0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(e.getState()[a]);(0,react__WEBPACK_IMPORTED_MODULE_0__.useLayoutEffect)((()=>(i(e.getState()[a]),u.current=e.getState()[a],e.subscribe((()=>{const t=e.getState();C(s,c,u.current)&&i(t[a]),u.current=t[a]})))),[]);const l="set".concat(B(a));let f=o[l];return[c,f]}})),s})(s,(0,redux__WEBPACK_IMPORTED_MODULE_1__.bindActionCreators)(a,s.dispatch))}}};function N(){return(N=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e}).apply(this,arguments)}const F={};const M=({error:t,title:r=(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("There seems to be an error"),children:n,style:o={},...a})=>{const s={...o,display:"flex",flexDirection:"column",flex:"1 1 auto",justifyContent:"center",alignItems:"center",padding:40,textAlign:"center",minHeight:0,maxHeight:"100%"};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",N({style:s},a),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("h1",{style:{marginBottom:20}},r),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("code",{style:{padding:10}},t.message),n)};F.Boundary=class extends react__WEBPACK_IMPORTED_MODULE_0__.Component{constructor(e){super(e),this.state={hasError:!1,error:null}}static getDerivedStateFromError(e){return{hasError:!0,error:e}}render(){const{alternate:e=M,children:t}=this.props,{hasError:r,error:n}=this.state;return r?(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(e,{error:n}):t}},F.Boundary.displayName="Error.Boundary",F.Page=M,F.Page.displayName="Error.Page";const $=()=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",null,"Loading..."),G=({children:t,error:r,loading:n=$,router:o=react_router_dom__WEBPACK_IMPORTED_MODULE_2__.MemoryRouter,routerProps:a={}})=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(F.Boundary,{alternate:r},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(o,a,react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react__WEBPACK_IMPORTED_MODULE_0__.Suspense,{fallback:react__WEBPACK_IMPORTED_MODULE_0___default().createElement(n,null)},t))),H={handle:null,label:null,isAppRoot:!1},V=(0,react__WEBPACK_IMPORTED_MODULE_0__.createContext)(H),J=()=>(0,react__WEBPACK_IMPORTED_MODULE_0__.useContext)(V),W=t=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(F.Page,N({title:(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("App Core: There seems to be an issue rendering current app.")},t)),X=({loading:t,error:r=Q,apps:n})=>{const a=(0,react_router_dom__WEBPACK_IMPORTED_MODULE_2__.useLocation)(),{app:s}=(0,react_router_dom__WEBPACK_IMPORTED_MODULE_2__.useParams)();if((0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)((()=>{if(n[s]&&"function"==typeof n[s].onMount)return n[s].onMount()}),[s]),!n[s])return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,null);const{label:i="",root:u=(()=>{}),onMount:l=(()=>{})}=n[s],f=2>=a.pathname.split("/").length,p={...H,handle:s,baseURL:"/".concat(s),label:i,isAppRoot:f};return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(V.Provider,{value:p},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(F.Boundary,{alternate:Q},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react__WEBPACK_IMPORTED_MODULE_0__.Suspense,{fallback:react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,null)},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Z,N({root:u},p)))))},Z=(0,react__WEBPACK_IMPORTED_MODULE_0__.memo)((({root:t,...r})=>t?react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,r):react__WEBPACK_IMPORTED_MODULE_0___default().createElement(K,null))),q=()=>null,K=()=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Y,null,react__WEBPACK_IMPORTED_MODULE_0___default().createElement("h1",null,(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("App Not Found"))),Q=t=>{const{label:r}=J();return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(F.Page,N({title:(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.sprintf)("There seems to be an issue with the %s app.",r)},t))},Y=({children:t})=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{style:{flex:"1 1 auto",minHeight:0,maxHeight:"100%",display:"flex",flexDirection:"column",justifyContent:"center",alignItems:"center"}},t),ee={};ee.use=J,ee.Content=({apps:t={},defaultApp:r="home",notFound:n=K,loading:o=q})=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(F.Boundary,{alternate:W},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Switch,null,react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route,{exact:!0,path:"/"},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Redirect,{to:"/".concat(r)})),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route,{path:"/:app",render:()=>react__WEBPACK_IMPORTED_MODULE_0___default().createElement(X,{loading:o,apps:t})}),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route,{component:n})));


/***/ }),

/***/ "./src/vendors/bb-app-core.js":
/*!************************************!*\
  !*** ./src/vendors/bb-app-core.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vendor_app_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vendor-app-core */ "./node_modules/@beaverbuilder/app-core/dist/index.es.js");

window.FL = window.FL || {};
FL.vendors = FL.vendors || {};
FL.vendors.BBAppCore = vendor_app_core__WEBPACK_IMPORTED_MODULE_0__;

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = React;

/***/ }),

/***/ "react-router-dom":
/*!*********************************!*\
  !*** external "ReactRouterDOM" ***!
  \*********************************/
/***/ ((module) => {

module.exports = ReactRouterDOM;

/***/ }),

/***/ "redux":
/*!************************!*\
  !*** external "Redux" ***!
  \************************/
/***/ ((module) => {

module.exports = Redux;

/***/ }),

/***/ "@wordpress/i18n":
/*!**************************!*\
  !*** external "wp.i18n" ***!
  \**************************/
/***/ ((module) => {

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
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
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
/******/ 	__webpack_require__("./src/vendors/bb-app-core.js");
/******/ 	// This entry module used 'exports' so it can't be inlined
/******/ })()
;
//# sourceMappingURL=vendor-bb-app-core.bundle.js.map