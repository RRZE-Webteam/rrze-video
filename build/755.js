"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[755],{6752:(t,e,s)=>{var n;s.d(e,{XX:()=>D,c0:()=>q,ge:()=>Q,qy:()=>k,s6:()=>C});const i=window,o=i.trustedTypes,a=o?o.createPolicy("lit-html",{createHTML:t=>t}):void 0,l="$lit$",r=`lit$${(Math.random()+"").slice(9)}$`,c="?"+r,d=`<${c}>`,u=document,p=()=>u.createComment(""),m=t=>null===t||"object"!=typeof t&&"function"!=typeof t,v=Array.isArray,h=t=>v(t)||"function"==typeof(null==t?void 0:t[Symbol.iterator]),y="[ \t\n\f\r]",$=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,b=/-->/g,g=/>/g,f=RegExp(`>|${y}(?:([^\\s"'>=/]+)(${y}*=${y}*(?:[^ \t\n\f\r"'\`<>=]|("|')|))|$)`,"g"),_=/'/g,A=/"/g,w=/^(?:script|style|textarea|title)$/i,x=t=>(e,...s)=>({_$litType$:t,strings:e,values:s}),k=x(1),q=(x(2),Symbol.for("lit-noChange")),C=Symbol.for("lit-nothing"),S=new WeakMap,T=u.createTreeWalker(u,129,null,!1);function M(t,e){if(!Array.isArray(t)||!t.hasOwnProperty("raw"))throw Error("invalid template strings array");return void 0!==a?a.createHTML(e):e}const P=(t,e)=>{const s=t.length-1,n=[];let i,o=2===e?"<svg>":"",a=$;for(let e=0;e<s;e++){const s=t[e];let c,u,p=-1,m=0;for(;m<s.length&&(a.lastIndex=m,u=a.exec(s),null!==u);)m=a.lastIndex,a===$?"!--"===u[1]?a=b:void 0!==u[1]?a=g:void 0!==u[2]?(w.test(u[2])&&(i=RegExp("</"+u[2],"g")),a=f):void 0!==u[3]&&(a=f):a===f?">"===u[0]?(a=null!=i?i:$,p=-1):void 0===u[1]?p=-2:(p=a.lastIndex-u[2].length,c=u[1],a=void 0===u[3]?f:'"'===u[3]?A:_):a===A||a===_?a=f:a===b||a===g?a=$:(a=f,i=void 0);const v=a===f&&t[e+1].startsWith("/>")?" ":"";o+=a===$?s+d:p>=0?(n.push(c),s.slice(0,p)+l+s.slice(p)+r+v):s+r+(-2===p?(n.push(void 0),e):v)}return[M(t,o+(t[s]||"<?>")+(2===e?"</svg>":"")),n]};class I{constructor({strings:t,_$litType$:e},s){let n;this.parts=[];let i=0,a=0;const d=t.length-1,u=this.parts,[m,v]=P(t,e);if(this.el=I.createElement(m,s),T.currentNode=this.el.content,2===e){const t=this.el.content,e=t.firstChild;e.remove(),t.append(...e.childNodes)}for(;null!==(n=T.nextNode())&&u.length<d;){if(1===n.nodeType){if(n.hasAttributes()){const t=[];for(const e of n.getAttributeNames())if(e.endsWith(l)||e.startsWith(r)){const s=v[a++];if(t.push(e),void 0!==s){const t=n.getAttribute(s.toLowerCase()+l).split(r),e=/([.?@])?(.*)/.exec(s);u.push({type:1,index:i,name:e[2],strings:t,ctor:"."===e[1]?V:"?"===e[1]?B:"@"===e[1]?G:N})}else u.push({type:6,index:i})}for(const e of t)n.removeAttribute(e)}if(w.test(n.tagName)){const t=n.textContent.split(r),e=t.length-1;if(e>0){n.textContent=o?o.emptyScript:"";for(let s=0;s<e;s++)n.append(t[s],p()),T.nextNode(),u.push({type:2,index:++i});n.append(t[e],p())}}}else if(8===n.nodeType)if(n.data===c)u.push({type:2,index:i});else{let t=-1;for(;-1!==(t=n.data.indexOf(r,t+1));)u.push({type:7,index:i}),t+=r.length-1}i++}}static createElement(t,e){const s=u.createElement("template");return s.innerHTML=t,s}}function W(t,e,s=t,n){var i,o,a,l;if(e===q)return e;let r=void 0!==n?null===(i=s._$Co)||void 0===i?void 0:i[n]:s._$Cl;const c=m(e)?void 0:e._$litDirective$;return(null==r?void 0:r.constructor)!==c&&(null===(o=null==r?void 0:r._$AO)||void 0===o||o.call(r,!1),void 0===c?r=void 0:(r=new c(t),r._$AT(t,s,n)),void 0!==n?(null!==(a=(l=s)._$Co)&&void 0!==a?a:l._$Co=[])[n]=r:s._$Cl=r),void 0!==r&&(e=W(t,r._$AS(t,e.values),r,n)),e}class O{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){var e;const{el:{content:s},parts:n}=this._$AD,i=(null!==(e=null==t?void 0:t.creationScope)&&void 0!==e?e:u).importNode(s,!0);T.currentNode=i;let o=T.nextNode(),a=0,l=0,r=n[0];for(;void 0!==r;){if(a===r.index){let e;2===r.type?e=new E(o,o.nextSibling,this,t):1===r.type?e=new r.ctor(o,r.name,r.strings,this,t):6===r.type&&(e=new L(o,this,t)),this._$AV.push(e),r=n[++l]}a!==(null==r?void 0:r.index)&&(o=T.nextNode(),a++)}return T.currentNode=u,i}v(t){let e=0;for(const s of this._$AV)void 0!==s&&(void 0!==s.strings?(s._$AI(t,s,e),e+=s.strings.length-2):s._$AI(t[e])),e++}}class E{constructor(t,e,s,n){var i;this.type=2,this._$AH=C,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=s,this.options=n,this._$Cp=null===(i=null==n?void 0:n.isConnected)||void 0===i||i}get _$AU(){var t,e;return null!==(e=null===(t=this._$AM)||void 0===t?void 0:t._$AU)&&void 0!==e?e:this._$Cp}get parentNode(){let t=this._$AA.parentNode;const e=this._$AM;return void 0!==e&&11===(null==t?void 0:t.nodeType)&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=W(this,t,e),m(t)?t===C||null==t||""===t?(this._$AH!==C&&this._$AR(),this._$AH=C):t!==this._$AH&&t!==q&&this._(t):void 0!==t._$litType$?this.g(t):void 0!==t.nodeType?this.$(t):h(t)?this.T(t):this._(t)}k(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}$(t){this._$AH!==t&&(this._$AR(),this._$AH=this.k(t))}_(t){this._$AH!==C&&m(this._$AH)?this._$AA.nextSibling.data=t:this.$(u.createTextNode(t)),this._$AH=t}g(t){var e;const{values:s,_$litType$:n}=t,i="number"==typeof n?this._$AC(t):(void 0===n.el&&(n.el=I.createElement(M(n.h,n.h[0]),this.options)),n);if((null===(e=this._$AH)||void 0===e?void 0:e._$AD)===i)this._$AH.v(s);else{const t=new O(i,this),e=t.u(this.options);t.v(s),this.$(e),this._$AH=t}}_$AC(t){let e=S.get(t.strings);return void 0===e&&S.set(t.strings,e=new I(t)),e}T(t){v(this._$AH)||(this._$AH=[],this._$AR());const e=this._$AH;let s,n=0;for(const i of t)n===e.length?e.push(s=new E(this.k(p()),this.k(p()),this,this.options)):s=e[n],s._$AI(i),n++;n<e.length&&(this._$AR(s&&s._$AB.nextSibling,n),e.length=n)}_$AR(t=this._$AA.nextSibling,e){var s;for(null===(s=this._$AP)||void 0===s||s.call(this,!1,!0,e);t&&t!==this._$AB;){const e=t.nextSibling;t.remove(),t=e}}setConnected(t){var e;void 0===this._$AM&&(this._$Cp=t,null===(e=this._$AP)||void 0===e||e.call(this,t))}}class N{constructor(t,e,s,n,i){this.type=1,this._$AH=C,this._$AN=void 0,this.element=t,this.name=e,this._$AM=n,this.options=i,s.length>2||""!==s[0]||""!==s[1]?(this._$AH=Array(s.length-1).fill(new String),this.strings=s):this._$AH=C}get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}_$AI(t,e=this,s,n){const i=this.strings;let o=!1;if(void 0===i)t=W(this,t,e,0),o=!m(t)||t!==this._$AH&&t!==q,o&&(this._$AH=t);else{const n=t;let a,l;for(t=i[0],a=0;a<i.length-1;a++)l=W(this,n[s+a],e,a),l===q&&(l=this._$AH[a]),o||(o=!m(l)||l!==this._$AH[a]),l===C?t=C:t!==C&&(t+=(null!=l?l:"")+i[a+1]),this._$AH[a]=l}o&&!n&&this.j(t)}j(t){t===C?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,null!=t?t:"")}}class V extends N{constructor(){super(...arguments),this.type=3}j(t){this.element[this.name]=t===C?void 0:t}}const H=o?o.emptyScript:"";class B extends N{constructor(){super(...arguments),this.type=4}j(t){t&&t!==C?this.element.setAttribute(this.name,H):this.element.removeAttribute(this.name)}}class G extends N{constructor(t,e,s,n,i){super(t,e,s,n,i),this.type=5}_$AI(t,e=this){var s;if((t=null!==(s=W(this,t,e,0))&&void 0!==s?s:C)===q)return;const n=this._$AH,i=t===C&&n!==C||t.capture!==n.capture||t.once!==n.once||t.passive!==n.passive,o=t!==C&&(n===C||i);i&&this.element.removeEventListener(this.name,this,n),o&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){var e,s;"function"==typeof this._$AH?this._$AH.call(null!==(s=null===(e=this.options)||void 0===e?void 0:e.host)&&void 0!==s?s:this.element,t):this._$AH.handleEvent(t)}}class L{constructor(t,e,s){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=s}get _$AU(){return this._$AM._$AU}_$AI(t){W(this,t)}}const Q={O:l,P:r,A:c,C:1,M:P,L:O,R:h,D:W,I:E,V:N,H:B,N:G,U:V,F:L},K=i.litHtmlPolyfillSupport;null==K||K(I,E),(null!==(n=i.litHtmlVersions)&&void 0!==n?n:i.litHtmlVersions=[]).push("2.8.0");const D=(t,e,s)=>{var n,i;const o=null!==(n=null==s?void 0:s.renderBefore)&&void 0!==n?n:e;let a=o._$litPart$;if(void 0===a){const t=null!==(i=null==s?void 0:s.renderBefore)&&void 0!==i?i:null;o._$litPart$=a=new E(e.insertBefore(p(),t),t,void 0,null!=s?s:{})}return a._$AI(t),a}},1422:(t,e,s)=>{function n(t,e){return[...t].sort(e?o:i)}function i(t,e){return t.height===e.height?(t.bitrate??0)-(e.bitrate??0):t.height-e.height}function o(t,e){return e.height===t.height?(e.bitrate??0)-(t.bitrate??0):e.height-t.height}function a(t){return()=>t()?"true":"false"}s.d(e,{F:()=>n,M:()=>a})},1622:(t,e,s)=>{s.d(e,{W:()=>i});var n=s(6752);class i extends HTMLElement{rootPart=null;connectedCallback(){this.rootPart=(0,n.XX)(this.render(),this,{renderBefore:this.firstChild}),this.rootPart.setConnected(!0)}disconnectedCallback(){this.rootPart?.setConnected(!1),this.rootPart=null,(0,n.XX)(null,this)}}},7755:(t,e,s)=>{var n=s(9873),i=s(6455),o=s(1192),a=s(6752);const l=t=>null!=t?t:a.s6,r=t=>(...e)=>({_$litDirective$:t,values:e});class c{constructor(t){}get _$AU(){return this._$AM._$AU}_$AT(t,e,s){this._$Ct=t,this._$AM=e,this._$Ci=s}_$AS(t,e){return this.update(t,e)}update(t,e){return this.render(...e)}}class d extends c{constructor(t){if(super(t),this.et=a.s6,2!==t.type)throw Error(this.constructor.directiveName+"() can only be used in child bindings")}render(t){if(t===a.s6||null==t)return this.ft=void 0,this.et=t;if(t===a.c0)return t;if("string"!=typeof t)throw Error(this.constructor.directiveName+"() called with a non-string value");if(t===this.et)return this.ft;this.et=t;const e=[t];return e.raw=e,this.ft={_$litType$:this.constructor.resultType,strings:e,values:[]}}}d.directiveName="unsafeHTML",d.resultType=1;const u=r(d);class p extends d{}p.directiveName="unsafeSVG",p.resultType=2;const m=r(p),{I:v}=a.ge,h={},y=(t,e)=>{var s,n;const i=t._$AN;if(void 0===i)return!1;for(const t of i)null===(n=(s=t)._$AO)||void 0===n||n.call(s,e,!1),y(t,e);return!0},$=t=>{let e,s;do{if(void 0===(e=t._$AM))break;s=e._$AN,s.delete(t),t=e}while(0===(null==s?void 0:s.size))},b=t=>{for(let e;e=t._$AM;t=e){let s=e._$AN;if(void 0===s)e._$AN=s=new Set;else if(s.has(t))break;s.add(t),_(e)}};function g(t){void 0!==this._$AN?($(this),this._$AM=t,b(this)):this._$AM=t}function f(t,e=!1,s=0){const n=this._$AH,i=this._$AN;if(void 0!==i&&0!==i.size)if(e)if(Array.isArray(n))for(let t=s;t<n.length;t++)y(n[t],!1),$(n[t]);else null!=n&&(y(n,!1),$(n));else y(this,t)}const _=t=>{var e,s,n,i;2==t.type&&(null!==(e=(n=t)._$AP)&&void 0!==e||(n._$AP=f),null!==(s=(i=t)._$AQ)&&void 0!==s||(i._$AQ=g))};class A extends c{constructor(){super(...arguments),this._$AN=void 0}_$AT(t,e,s){super._$AT(t,e,s),b(this),this.isConnected=t._$AU}_$AO(t,e=!0){var s,n;t!==this.isConnected&&(this.isConnected=t,t?null===(s=this.reconnected)||void 0===s||s.call(this):null===(n=this.disconnected)||void 0===n||n.call(this)),e&&(y(this,t),$(this))}setValue(t){if((()=>void 0===this._$Ct.strings)())this._$Ct._$AI(t,this);else{const e=[...this._$Ct._$AH];e[this._$Ci]=t,this._$Ct._$AI(e,this,0)}}disconnected(){}reconnected(){}}class w extends A{#t=null;#e=!1;#s=null;constructor(t){super(t),this.#e=1===t.type||4===t.type}render(t){return t!==this.#t&&(this.disconnected(),this.#t=t,this.isConnected&&this.#n()),this.#t?this.#i((0,n.se)(this.#t)):a.s6}reconnected(){this.#n()}disconnected(){this.#s?.(),this.#s=null}#n(){this.#t&&(this.#s=(0,n.QZ)(this.#o.bind(this)))}#i(t){return this.#e?l(t):t}#a(t){this.setValue(this.#i(t))}#o(){this.#a(this.#t?.())}}function x(t){return r(w)((0,n.EW)(t))}class k{#l;#r;elements=new Set;constructor(t,e){this.#l=t,this.#r=e}connect(){this.#c();const t=new MutationObserver(this.#d);for(const e of this.#l)t.observe(e,{childList:!0,subtree:!0});(0,n.zp)((()=>t.disconnect())),(0,n.zp)(this.disconnect.bind(this))}disconnect(){this.elements.clear()}assign(t,e){(0,n.vA)(t)?(e.textContent="",e.append(t)):((0,a.XX)(null,e),(0,a.XX)(t,e)),e.style.display||(e.style.display="contents");const s=e.firstElementChild;if(!s)return;const i=e.getAttribute("data-class");i&&s.classList.add(...i.split(" "))}#d=(0,n.s_)(this.#c.bind(this));#c(t){if(t&&!t.some((t=>t.addedNodes.length)))return;let e=!1,s=this.#l.flatMap((t=>[...t.querySelectorAll("slot")]));for(const t of s)t.hasAttribute("name")&&!this.elements.has(t)&&(this.elements.add(t),e=!0);e&&this.#r(this.elements)}}let q=0,C="data-slot-id";class S{#l;slots;constructor(t){this.#l=t,this.slots=new k(t,this.#c.bind(this))}connect(){this.slots.connect(),this.#c();const t=new MutationObserver(this.#d);for(const e of this.#l)t.observe(e,{childList:!0});(0,n.zp)((()=>t.disconnect()))}#d=(0,n.s_)(this.#c.bind(this));#c(){for(const t of this.#l)for(const e of t.children){if(1!==e.nodeType)continue;const t=e.getAttribute("slot");if(!t)continue;e.style.display="none";let s=e.getAttribute(C);s||e.setAttribute(C,s=++q+"");for(const n of this.slots.elements){if(n.getAttribute("name")!==t||n.getAttribute(C)===s)continue;const i=document.importNode(e,!0);t.includes("-icon")&&i.classList.add("vds-icon"),i.style.display="",i.removeAttribute("slot"),this.slots.assign(i,n),n.setAttribute(C,s)}}}}function T({name:t,class:e,state:s,paths:i,viewBox:o="0 0 32 32"}){return a.qy`<svg
    class="${"vds-icon"+(e?` ${e}`:"")}"
    viewBox="${o}"
    fill="none"
    aria-hidden="true"
    focusable="false"
    xmlns="http://www.w3.org/2000/svg"
    data-icon=${l(t??s)}
  >
    ${(0,n.Kg)(i)?m(i):x(i)}
  </svg>`}class M{#u={};#p=!1;slots;constructor(t){this.slots=new k(t,this.#m.bind(this))}connect(){this.slots.connect()}load(){this.loadIcons().then((t=>{this.#u=t,this.#p=!0,this.#m()}))}*#v(){for(const t of Object.keys(this.#u)){const e=`${t}-icon`;for(const s of this.slots.elements)s.name===e&&(yield{icon:this.#u[t],slot:s})}}#m(){if(this.#p)for(const{icon:t,slot:e}of this.#v())this.slots.assign(t,e)}}class P extends M{connect(){super.connect();const{player:t}=(0,i.$c)();if(!t.el)return;let e,s=new IntersectionObserver((t=>{t[0]?.isIntersecting&&(e?.(),e=void 0,this.load())}));s.observe(t.el),e=(0,n.zp)((()=>s.disconnect()))}}var I=s(9777),W=s(1422);const O=new WeakMap,E=r(class extends A{render(t){return a.s6}update(t,[e]){var s;const n=e!==this.G;return n&&void 0!==this.G&&this.ot(void 0),(n||this.rt!==this.lt)&&(this.G=e,this.dt=null===(s=t.options)||void 0===s?void 0:s.host,this.ot(this.lt=t.element)),a.s6}ot(t){var e;if("function"==typeof this.G){const s=null!==(e=this.dt)&&void 0!==e?e:globalThis;let n=O.get(s);void 0===n&&(n=new WeakMap,O.set(s,n)),void 0!==n.get(this.G)&&this.G.call(this.dt,void 0),n.set(this.G,t),void 0!==t&&this.G.call(this.dt,t)}else this.G.value=t}get rt(){var t,e,s;return"function"==typeof this.G?null===(e=O.get(null!==(t=this.dt)&&void 0!==t?t:globalThis))||void 0===e?void 0:e.get(this.G):null===(s=this.G)||void 0===s?void 0:s.value}disconnected(){this.rt===this.lt&&this.ot(void 0)}reconnected(){this.ot(this.lt)}});var N=s(8350);const V=(0,n.q6)();function H(){return(0,n.NT)(V)}const B={colorScheme:"system",download:null,customIcons:!1,disableTimeSlider:!1,menuContainer:null,menuGroup:"bottom",noAudioGain:!1,noGestures:!1,noKeyboardAnimations:!1,noModal:!1,noScrubGesture:!1,playbackRates:{min:0,max:2,step:.25},audioGains:{min:0,max:300,step:25},seekStep:10,sliderChaptersMinWidth:325,hideQualityBitrate:!1,smallWhen:!1,thumbnails:null,translations:null,when:!1};class G extends n.uA{static props=B;#h;#y=(0,n.EW)((()=>{const t=this.$props.when();return this.#$(t)}));#b=(0,n.EW)((()=>{const t=this.$props.smallWhen();return this.#$(t)}));get isMatch(){return this.#y()}get isSmallLayout(){return this.#b()}onSetup(){this.#h=(0,i.$c)(),this.setAttributes({"data-match":this.#y,"data-sm":()=>this.#b()?"":null,"data-lg":()=>this.#b()?null:"","data-size":()=>this.#b()?"sm":"lg","data-no-scrub-gesture":this.$props.noScrubGesture}),(0,n.Pp)(V,{...this.$props,when:this.#y,smallWhen:this.#b,userPrefersAnnouncements:(0,n.O)(!0),userPrefersKeyboardAnimations:(0,n.O)(!0),menuPortal:(0,n.O)(null)})}onAttach(t){(0,o.GU)(t,this.$props.colorScheme)}#$(t){return"never"!==t&&((0,n.Lm)(t)?t:(0,n.EW)((()=>t(this.#h.player.state)))())}}const L=G.prototype;function Q(t,e){(0,n.QZ)((()=>{const{player:s}=(0,i.$c)(),o=s.el;return o&&(0,n.Bq)(o,"data-layout",e()&&t),()=>o?.removeAttribute("data-layout")}))}function K(t,e){return t()?.[e]??e}function D(){return x((()=>{const{translations:t,userPrefersAnnouncements:e}=H();return e()?a.qy`<media-announcer .translations=${x(t)}></media-announcer>`:null}))}function F(t,e=""){return a.qy`<slot
    name=${`${t}-icon`}
    data-class=${`vds-icon vds-${t}-icon${e?` ${e}`:""}`}
  ></slot>`}function R(t){return t.map((t=>F(t)))}function z(t,e){return x((()=>K(t,e)))}function U({tooltip:t}){const{translations:e}=H(),{remotePlaybackState:s}=(0,i.nV)(),o=x((()=>`${K(e,"AirPlay")} ${(0,n.Fb)(s())}`)),l=z(e,"AirPlay");return a.qy`
    <media-tooltip class="vds-airplay-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-airplay-button class="vds-airplay-button vds-button" aria-label=${o}>
          ${F("airplay")}
        </media-airplay-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${t}>
        <span class="vds-airplay-tooltip-text">${l}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function Z({tooltip:t}){const{translations:e}=H(),{remotePlaybackState:s}=(0,i.nV)(),o=x((()=>`${K(e,"Google Cast")} ${(0,n.Fb)(s())}`)),l=z(e,"Google Cast");return a.qy`
    <media-tooltip class="vds-google-cast-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-google-cast-button class="vds-google-cast-button vds-button" aria-label=${o}>
          ${F("google-cast")}
        </media-google-cast-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${t}>
        <span class="vds-google-cast-tooltip-text">${l}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function j({tooltip:t}){const{translations:e}=H(),s=z(e,"Play"),n=z(e,"Pause");return a.qy`
    <media-tooltip class="vds-play-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-play-button
          class="vds-play-button vds-button"
          aria-label=${z(e,"Play")}
        >
          ${R(["play","pause","replay"])}
        </media-play-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${t}>
        <span class="vds-play-tooltip-text">${s}</span>
        <span class="vds-pause-tooltip-text">${n}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function X({tooltip:t,ref:e=n.lQ}){const{translations:s}=H(),i=z(s,"Mute"),o=z(s,"Unmute");return a.qy`
    <media-tooltip class="vds-mute-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-mute-button
          class="vds-mute-button vds-button"
          aria-label=${z(s,"Mute")}
          ${E(e)}
        >
          ${R(["mute","volume-low","volume-high"])}
        </media-mute-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${t}>
        <span class="vds-mute-tooltip-text">${o}</span>
        <span class="vds-unmute-tooltip-text">${i}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function Y({tooltip:t}){const{translations:e}=H(),s=z(e,"Closed-Captions On"),n=z(e,"Closed-Captions Off");return a.qy`
    <media-tooltip class="vds-caption-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-caption-button
          class="vds-caption-button vds-button"
          aria-label=${z(e,"Captions")}
        >
          ${R(["cc-on","cc-off"])}
        </media-caption-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${t}>
        <span class="vds-cc-on-tooltip-text">${n}</span>
        <span class="vds-cc-off-tooltip-text">${s}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function J(){const{translations:t}=H(),e=z(t,"Enter PiP"),s=z(t,"Exit PiP");return a.qy`
    <media-tooltip class="vds-pip-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-pip-button
          class="vds-pip-button vds-button"
          aria-label=${z(t,"PiP")}
        >
          ${R(["pip-enter","pip-exit"])}
        </media-pip-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content">
        <span class="vds-pip-enter-tooltip-text">${e}</span>
        <span class="vds-pip-exit-tooltip-text">${s}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function tt({tooltip:t}){const{translations:e}=H(),s=z(e,"Enter Fullscreen"),n=z(e,"Exit Fullscreen");return a.qy`
    <media-tooltip class="vds-fullscreen-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-fullscreen-button
          class="vds-fullscreen-button vds-button"
          aria-label=${z(e,"Fullscreen")}
        >
          ${R(["fs-enter","fs-exit"])}
        </media-fullscreen-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${t}>
        <span class="vds-fs-enter-tooltip-text">${s}</span>
        <span class="vds-fs-exit-tooltip-text">${n}</span>
      </media-tooltip-content>
    </media-tooltip>
  `}function et({backward:t,tooltip:e}){const{translations:s,seekStep:n}=H(),i=t?"Seek Backward":"Seek Forward",o=z(s,i);return a.qy`
    <media-tooltip class="vds-seek-tooltip vds-tooltip">
      <media-tooltip-trigger>
        <media-seek-button
          class="vds-seek-button vds-button"
          seconds=${x((()=>(t?-1:1)*n()))}
          aria-label=${o}
        >
          ${F(t?"seek-backward":"seek-forward")}
        </media-seek-button>
      </media-tooltip-trigger>
      <media-tooltip-content class="vds-tooltip-content" placement=${e}>
        ${z(s,i)}
      </media-tooltip-content>
    </media-tooltip>
  `}function st(){const{translations:t}=H(),{live:e}=(0,i.nV)(),s=z(t,"Skip To Live"),n=z(t,"LIVE");return e()?a.qy`
        <media-live-button class="vds-live-button" aria-label=${s}>
          <span class="vds-live-button-text">${n}</span>
        </media-live-button>
      `:null}function nt(){return x((()=>{const{download:t,translations:e}=H(),s=t();if((0,n.gD)(s))return null;const{source:o,title:l}=(0,i.nV)(),r=o(),c=(0,N.d_)({title:l(),src:r,download:s});return c?a.qy`
          <media-tooltip class="vds-download-tooltip vds-tooltip">
            <media-tooltip-trigger>
              <a
                role="button"
                class="vds-download-button vds-button"
                aria-label=${z(e,"Download")}
                href=${c.url+`?download=${c.name}`}
                download=${c.name}
                target="_blank"
              >
                <slot name="download-icon" data-class="vds-icon" />
              </a>
            </media-tooltip-trigger>
            <media-tooltip-content class="vds-tooltip-content" placement="top">
              ${z(e,"Download")}
            </media-tooltip-content>
          </media-tooltip>
        `:null}))}function it(){const{translations:t}=H();return a.qy`
    <media-captions
      class="vds-captions"
      .exampleText=${z(t,"Captions look like this")}
    ></media-captions>
  `}function ot(){return a.qy`<div class="vds-controls-spacer"></div>`}function at(t,e){return a.qy`
    <media-menu-portal .container=${x(t)} disabled="fullscreen">
      ${e}
    </media-menu-portal>
  `}function lt(t,e,s,a){let l=(0,n.Kg)(e)?document.querySelector(e):e;l||(l=t?.closest("dialog")),l||(l=document.body);const r=document.createElement("div");r.style.display="contents",r.classList.add(s),l.append(r),(0,n.QZ)((()=>{if(!r)return;const{viewType:t}=(0,i.nV)(),e=a();(0,n.Bq)(r,"data-view-type",t()),(0,n.Bq)(r,"data-sm",e),(0,n.Bq)(r,"data-lg",!e),(0,n.Bq)(r,"data-size",e?"sm":"lg")}));const{colorScheme:c}=H();return(0,o.GU)(r,c),r}function rt({placement:t,tooltip:e,portal:s}){const{textTracks:o}=(0,i.$c)(),{viewType:l,clipStartTime:r,clipEndTime:c}=(0,i.nV)(),{translations:d,thumbnails:u,menuPortal:p,noModal:m,menuGroup:v,smallWhen:h}=H();if((0,n.EW)((()=>{const t=r(),e=c()||1/0,s=(0,n.O)(null);(0,I.q)(o,"chapters",s.set);const i=s()?.cues.filter((s=>s.startTime<=e&&s.endTime>=t));return!i?.length}))())return null;const y=(0,n.EW)((()=>m()?(0,n.oA)(t):h()?null:(0,n.oA)(t))),$=(0,n.EW)((()=>h()||"bottom"!==v()||"video"!==l()?0:26)),b=(0,n.O)(!1),g=a.qy`
    <media-menu-items
      class="vds-chapters-menu-items vds-menu-items"
      placement=${x(y)}
      offset=${x($)}
    >
      ${x((()=>b()?a.qy`
          <media-chapters-radio-group
            class="vds-chapters-radio-group vds-radio-group"
            .thumbnails=${x(u)}
          >
            <template>
              <media-radio class="vds-chapter-radio vds-radio">
                <media-thumbnail class="vds-thumbnail"></media-thumbnail>
                <div class="vds-chapter-radio-content">
                  <span class="vds-chapter-radio-label" data-part="label"></span>
                  <span class="vds-chapter-radio-start-time" data-part="start-time"></span>
                  <span class="vds-chapter-radio-duration" data-part="duration"></span>
                </div>
              </media-radio>
            </template>
          </media-chapters-radio-group>
        `:null))}
    </media-menu-items>
  `;return a.qy`
    <media-menu class="vds-chapters-menu vds-menu" @open=${function(){b.set(!0)}} @close=${function(){b.set(!1)}}>
      <media-tooltip class="vds-tooltip">
        <media-tooltip-trigger>
          <media-menu-button
            class="vds-menu-button vds-button"
            aria-label=${z(d,"Chapters")}
          >
            ${F("menu-chapters")}
          </media-menu-button>
        </media-tooltip-trigger>
        <media-tooltip-content
          class="vds-tooltip-content"
          placement=${(0,n.Tn)(e)?x(e):e}
        >
          ${z(d,"Chapters")}
        </media-tooltip-content>
      </media-tooltip>
      ${s?at(p,g):g}
    </media-menu>
  `}function ct(t){const{style:e}=new Option;return e.color=t,e.color.match(/\((.*?)\)/)[1].replace(/,/g," ")}(0,n._w)(L,"isMatch"),(0,n._w)(L,"isSmallLayout");const dt={type:"color"},ut={type:"radio",values:{"Monospaced Serif":"mono-serif","Proportional Serif":"pro-serif","Monospaced Sans-Serif":"mono-sans","Proportional Sans-Serif":"pro-sans",Casual:"casual",Cursive:"cursive","Small Capitals":"capitals"}},pt={type:"radio",values:["None","Drop Shadow","Raised","Depressed","Outline"]},mt={fontFamily:"pro-sans",fontSize:"100%",textColor:"#ffffff",textOpacity:"100%",textShadow:"none",textBg:"#000000",textBgOpacity:"100%",displayBg:"#000000",displayBgOpacity:"0%"},vt=Object.keys(mt).reduce(((t,e)=>({...t,[e]:(0,n.O)(mt[e])})),{});for(const t of Object.keys(vt)){const e=localStorage.getItem(`vds-player:${(0,n.BW)(t)}`);(0,n.Kg)(e)&&vt[t].set(e)}function ht(){for(const t of Object.keys(vt)){const e=mt[t];vt[t].set(e)}}let yt=!1,$t=new Set;function bt(t,e,s){switch(e){case"fontFamily":const e="capitals"===s?"small-caps":"";return t.el?.style.setProperty("--media-user-font-variant",e),function(t){switch(t){case"mono-serif":return'"Courier New", Courier, "Nimbus Mono L", "Cutive Mono", monospace';case"mono-sans":return'"Deja Vu Sans Mono", "Lucida Console", Monaco, Consolas, "PT Mono", monospace';case"pro-sans":return'Roboto, "Arial Unicode Ms", Arial, Helvetica, Verdana, "PT Sans Caption", sans-serif';case"casual":return'"Comic Sans MS", Impact, Handlee, fantasy';case"cursive":return'"Monotype Corsiva", "URW Chancery L", "Apple Chancery", "Dancing Script", cursive';case"capitals":return'"Arial Unicode Ms", Arial, Helvetica, Verdana, "Marcellus SC", sans-serif + font-variant=small-caps';default:return'"Times New Roman", Times, Georgia, Cambria, "PT Serif Caption", serif'}}(s);case"fontSize":case"textOpacity":case"textBgOpacity":case"displayBgOpacity":return function(t){return(parseInt(t)/100).toString()}(s);case"textColor":return`rgb(${ct(s)} / var(--media-user-text-opacity, 1))`;case"textShadow":return function(t){switch(t){case"drop shadow":return"rgb(34, 34, 34) 1.86389px 1.86389px 2.79583px, rgb(34, 34, 34) 1.86389px 1.86389px 3.72778px, rgb(34, 34, 34) 1.86389px 1.86389px 4.65972px";case"raised":return"rgb(34, 34, 34) 1px 1px, rgb(34, 34, 34) 2px 2px";case"depressed":return"rgb(204, 204, 204) 1px 1px, rgb(34, 34, 34) -1px -1px";case"outline":return"rgb(34, 34, 34) 0px 0px 1.86389px, rgb(34, 34, 34) 0px 0px 1.86389px, rgb(34, 34, 34) 0px 0px 1.86389px, rgb(34, 34, 34) 0px 0px 1.86389px, rgb(34, 34, 34) 0px 0px 1.86389px";default:return""}}(s);case"textBg":return`rgb(${ct(s)} / var(--media-user-text-bg-opacity, 1))`;case"displayBg":return`rgb(${ct(s)} / var(--media-user-display-bg-opacity, 1))`}}let gt=0;function ft({label:t="",value:e="",children:s}){if(!t)return a.qy`
      <div class="vds-menu-section">
        <div class="vds-menu-section-body">${s}</div>
      </div>
    `;const n="vds-menu-section-"+ ++gt;return a.qy`
    <section class="vds-menu-section" role="group" aria-labelledby=${n}>
      <div class="vds-menu-section-title">
        <header id=${n}>${t}</header>
        ${e?a.qy`<div class="vds-menu-section-value">${e}</div>`:null}
      </div>
      <div class="vds-menu-section-body">${s}</div>
    </section>
  `}function _t({label:t,children:e}){return a.qy`
    <div class="vds-menu-item">
      <div class="vds-menu-item-label">${t}</div>
      ${e}
    </div>
  `}function At({label:t,icon:e,hint:s}){return a.qy`
    <media-menu-button class="vds-menu-item">
      ${F("menu-arrow-left","vds-menu-close-icon")}
      ${e?F(e,"vds-menu-item-icon"):null}
      <span class="vds-menu-item-label">${x(t)}</span>
      <span class="vds-menu-item-hint" data-part="hint">${s?x(s):null} </span>
      ${F("menu-arrow-right","vds-menu-open-icon")}
    </media-menu-button>
  `}function wt(){return a.qy`
    <div class="vds-slider-track"></div>
    <div class="vds-slider-track-fill vds-slider-track"></div>
    <div class="vds-slider-thumb"></div>
  `}function xt(){return a.qy`
    <media-slider-steps class="vds-slider-steps">
      <template>
        <div class="vds-slider-step"></div>
      </template>
    </media-slider-steps>
  `}function kt({label:t=null,value:e=null,upIcon:s="",downIcon:n="",children:i,isMin:o,isMax:l}){const r=t||e,c=[n?F(n,"down"):null,i,s?F(s,"up"):null];return a.qy`
    <div
      class=${"vds-menu-item vds-menu-slider-item"+(r?" group":"")}
      data-min=${x((()=>o()?"":null))}
      data-max=${x((()=>l()?"":null))}
    >
      ${r?a.qy`
            <div class="vds-menu-slider-title">
              ${[t?a.qy`<div>${t}</div>`:null,e?a.qy`<div>${e}</div>`:null]}
            </div>
            <div class="vds-menu-slider-body">${c}</div>
          `:c}
    </div>
  `}const qt={type:"slider",min:0,max:400,step:25,upIcon:null,downIcon:null,upIcon:"menu-opacity-up",downIcon:"menu-opacity-down"},Ct={type:"slider",min:0,max:100,step:5,upIcon:null,downIcon:null,upIcon:"menu-opacity-up",downIcon:"menu-opacity-down"};function St(){const{translations:t}=H();return a.qy`
    <button class="vds-menu-item" role="menuitem" @click=${ht}>
      <span class="vds-menu-item-label">${x((()=>K(t,"Reset")))}</span>
    </button>
  `}function Tt({label:t,option:e,type:s}){const{player:o}=(0,i.$c)(),{translations:l}=H(),r=vt[s],c=()=>K(l,t);function d(){(0,n.io)(),o.dispatchEvent(new Event("vds-font-change"))}if("color"===e.type){let t=function(t){r.set(t.target.value),d()};return _t({label:x(c),children:a.qy`
        <input
          class="vds-color-picker"
          type="color"
          .value=${x(r)}
          @input=${t}
        />
      `})}if("slider"===e.type){let t=function(t){r.set(t.detail+"%"),d()};const{min:s,max:n,step:i,upIcon:o,downIcon:l}=e;return kt({label:x(c),value:x(r),upIcon:o,downIcon:l,isMin:()=>r()===s+"%",isMax:()=>r()===n+"%",children:a.qy`
        <media-slider
          class="vds-slider"
          min=${s}
          max=${n}
          step=${i}
          key-step=${i}
          .value=${x((()=>parseInt(r())))}
          aria-label=${x(c)}
          @value-change=${t}
          @drag-value-change=${t}
        >
          ${wt()}${xt()}
        </media-slider>
      `})}const u=(p=e.values,(0,n.cy)(p)?p.map((t=>({label:t,value:t.toLowerCase()}))):Object.keys(p).map((t=>({label:t,value:p[t]}))));var p;return a.qy`
    <media-menu class=${`vds-${(0,n.BW)(s)}-menu vds-menu`}>
      ${At({label:c,hint:()=>{const t=r(),e=u.find((e=>e.value===t))?.label||"";return K(l,(0,n.Kg)(e)?e:e())}})}
      <media-menu-items class="vds-menu-items">
        ${function({value:t=null,options:e,hideLabel:s=!1,children:i=null,onChange:o=null}){function l(t){const{value:e,label:o}=t;return a.qy`
      <media-radio class="vds-radio" value=${e}>
        ${F("menu-radio-check")}
        ${s?null:a.qy`
              <span class="vds-radio-label" data-part="label">
                ${(0,n.Kg)(o)?o:x(o)}
              </span>
            `}
        ${(0,n.Tn)(i)?i(t):i}
      </media-radio>
    `}return a.qy`
    <media-radio-group
      class="vds-radio-group"
      value=${(0,n.Kg)(t)?t:t?x(t):""}
      @change=${o}
    >
      ${(0,n.cy)(e)?e.map(l):x((()=>e().map(l)))}
    </media-radio-group>
  `}({value:r,options:u,onChange({detail:t}){r.set(t),d()}})}
      </media-menu-items>
    </media-menu>
  `}function Mt({label:t,checked:e,defaultChecked:s=!1,storageKey:i,onChange:o}){const{translations:l}=H(),r=i?localStorage.getItem(i):null,c=(0,n.O)(!!(r??s)),d=(0,n.O)(!1),u=x((0,W.M)(c)),p=z(l,t);function m(t){1!==t?.button&&(c.set((t=>!t)),i&&localStorage.setItem(i,c()?"1":""),o(c(),t),d.set(!1))}return i&&o((0,n.se)(c)),e&&(0,n.QZ)((()=>{c.set(e())})),a.qy`
    <div
      class="vds-menu-checkbox"
      role="menuitemcheckbox"
      tabindex="0"
      aria-label=${p}
      aria-checked=${u}
      data-active=${x((()=>d()?"":null))}
      @pointerup=${m}
      @pointerdown=${function(t){0===t.button&&d.set(!0)}}
      @keydown=${function(t){(0,n.SK)(t)&&m()}}
    ></div>
  `}function Pt(){const{userPrefersAnnouncements:t,translations:e}=H(),s="Announcements";return _t({label:z(e,s),children:Mt({label:s,storageKey:"vds-player::announcements",onChange(e){t.set(e)}})})}function It(){const{translations:t}=H(),e=z(t,"Boost"),s=Wt,n=Ot,i=Et;return a.qy`
    <media-audio-gain-slider
      class="vds-audio-gain-slider vds-slider"
      aria-label=${e}
      min=${x(s)}
      max=${x(n)}
      step=${x(i)}
      key-step=${x(i)}
    >
      ${wt()}${xt()}
    </media-audio-gain-slider>
  `}function Wt(){const{audioGains:t}=H(),e=t();return(0,n.cy)(e)?e[0]??0:e.min}function Ot(){const{audioGains:t}=H(),e=t();return(0,n.cy)(e)?e[e.length-1]??300:e.max}function Et(){const{audioGains:t}=H(),e=t();return(0,n.cy)(e)?e[1]-e[0]||25:e.step}function Nt(){const{remote:t}=(0,i.$c)(),{translations:e}=H(),s="Loop";return _t({label:z(e,s),children:Mt({label:s,storageKey:"vds-player::user-loop",onChange(e,s){t.userPrefersLoopChange(e,s)}})})}function Vt(){const{playbackRates:t}=H(),e=t();return(0,n.cy)(e)?e[0]??0:e.min}function Ht(){const{playbackRates:t}=H(),e=t();return(0,n.cy)(e)?e[e.length-1]??2:e.max}function Bt(){const{playbackRates:t}=H(),e=t();return(0,n.cy)(e)?e[1]-e[0]||.25:e.step}function Gt(){const{translations:t}=H(),e=z(t,"Speed"),s=Vt,n=Ht,i=Bt;return a.qy`
    <media-speed-slider
      class="vds-speed-slider vds-slider"
      aria-label=${e}
      min=${x(s)}
      max=${x(n)}
      step=${x(i)}
      key-step=${x(i)}
    >
      ${wt()}${xt()}
    </media-speed-slider>
  `}function Lt(){const{remote:t,qualities:e}=(0,i.$c)(),{autoQuality:s,canSetQuality:o,qualities:a}=(0,i.nV)(),{translations:l}=H(),r="Auto";return(0,n.EW)((()=>!o()||a().length<=1))()?null:_t({label:z(l,r),children:Mt({label:r,checked:s,onChange(s,n){s?t.requestAutoQuality(n):t.changeQuality(e.selectedIndex,n)}})})}function Qt(){const{translations:t}=H(),e=z(t,"Quality");return a.qy`
    <media-quality-slider class="vds-quality-slider vds-slider" aria-label=${e}>
      ${wt()}${xt()}
    </media-quality-slider>
  `}function Kt({placement:t,portal:e,tooltip:s}){return x((()=>{const{viewType:o}=(0,i.nV)(),{translations:l,menuPortal:r,noModal:c,menuGroup:d,smallWhen:u}=H(),p=(0,n.EW)((()=>c()?(0,n.oA)(t):u()?null:(0,n.oA)(t))),m=(0,n.EW)((()=>u()||"bottom"!==d()||"video"!==o()?0:26)),v=(0,n.O)(!1);!function(){const{player:t}=(0,i.$c)();$t.add(t),(0,n.zp)((()=>$t.delete(t))),yt||((0,n.P1)((()=>{for(const e of(0,n.YD)(vt)){const s=vt[e],i=mt[e],o=`--media-user-${(0,n.BW)(e)}`,a=`vds-player:${(0,n.BW)(e)}`;(0,n.QZ)((()=>{const n=s(),l=n===i,r=l?null:bt(t,e,n);for(const t of $t)t.el?.style.setProperty(o,r);l?localStorage.removeItem(a):localStorage.setItem(a,n)}))}}),null),yt=!0)}();const h=a.qy`
      <media-menu-items
        class="vds-settings-menu-items vds-menu-items"
        placement=${x(p)}
        offset=${x(m)}
      >
        ${x((()=>v()?[x((()=>{const{translations:t}=H();return a.qy`
      <media-menu class="vds-playback-menu vds-menu">
        ${At({label:()=>K(t,"Playback"),icon:"menu-playback"})}
        <media-menu-items class="vds-menu-items">
          ${[ft({children:Nt()}),x((()=>{const{translations:t}=H(),{canSetPlaybackRate:e,playbackRate:s}=(0,i.nV)();return e()?ft({label:z(t,"Speed"),value:x((()=>1===s()?K(t,"Normal"):s()+"x")),children:[kt({upIcon:"menu-speed-up",downIcon:"menu-speed-down",children:Gt(),isMin:()=>s()===Vt(),isMax:()=>s()===Ht()})]}):null})),x((()=>{const{hideQualityBitrate:t,translations:e}=H(),{canSetQuality:s,qualities:o,quality:a}=(0,i.nV)(),l=(0,n.EW)((()=>!s()||o().length<=1)),r=(0,n.EW)((()=>(0,W.F)(o())));return l()?null:ft({label:z(e,"Quality"),value:x((()=>{const s=a()?.height,n=t()?null:a()?.bitrate,i=n&&n>0?`${(n/1e6).toFixed(2)} Mbps`:null,o=K(e,"Auto");return s?`${s}p${i?` (${i})`:""}`:o})),children:[kt({upIcon:"menu-quality-up",downIcon:"menu-quality-down",children:Qt(),isMin:()=>r()[0]===a(),isMax:()=>r().at(-1)===a()}),Lt()]})}))]}
        </media-menu-items>
      </media-menu>
    `})),x((()=>{const{translations:t}=H();return a.qy`
      <media-menu class="vds-accessibility-menu vds-menu">
        ${At({label:()=>K(t,"Accessibility"),icon:"menu-accessibility"})}
        <media-menu-items class="vds-menu-items">
          ${[ft({children:[Pt(),x((()=>{const{translations:t,userPrefersKeyboardAnimations:e,noKeyboardAnimations:s}=H(),{viewType:o}=(0,i.nV)();if((0,n.EW)((()=>"video"!==o()||s()))())return null;const a="Keyboard Animations";return _t({label:z(t,a),children:Mt({label:a,defaultChecked:!0,storageKey:"vds-player::keyboard-animations",onChange(t){e.set(t)}})})}))]}),ft({children:[x((()=>{const{hasCaptions:t}=(0,i.nV)(),{translations:e}=H();return t()?a.qy`
      <media-menu class="vds-font-menu vds-menu">
        ${At({label:()=>K(e,"Caption Styles")})}
        <media-menu-items class="vds-menu-items">
          ${[ft({label:z(e,"Font"),children:[Tt({label:"Family",option:ut,type:"fontFamily"}),Tt({label:"Size",option:qt,type:"fontSize"})]}),ft({label:z(e,"Text"),children:[Tt({label:"Color",option:dt,type:"textColor"}),Tt({label:"Shadow",option:pt,type:"textShadow"}),Tt({label:"Opacity",option:Ct,type:"textOpacity"})]}),ft({label:z(e,"Text Background"),children:[Tt({label:"Color",option:dt,type:"textBg"}),Tt({label:"Opacity",option:Ct,type:"textBgOpacity"})]}),ft({label:z(e,"Display Background"),children:[Tt({label:"Color",option:dt,type:"displayBg"}),Tt({label:"Opacity",option:Ct,type:"displayBgOpacity"})]}),ft({children:[St()]})]}
        </media-menu-items>
      </media-menu>
    `:null}))]})]}
        </media-menu-items>
      </media-menu>
    `})),x((()=>{const{noAudioGain:t,translations:e}=H(),{audioTracks:s,canSetAudioGain:o}=(0,i.nV)();return(0,n.EW)((()=>!(o()&&!t())&&s().length<=1))()?null:a.qy`
      <media-menu class="vds-audio-menu vds-menu">
        ${At({label:()=>K(e,"Audio"),icon:"menu-audio"})}
        <media-menu-items class="vds-menu-items">
          ${[x((()=>{const{translations:t}=H(),{audioTracks:e}=(0,i.nV)(),s=z(t,"Default");return(0,n.EW)((()=>e().length<=1))()?null:ft({children:a.qy`
        <media-menu class="vds-audio-tracks-menu vds-menu">
          ${At({label:()=>K(t,"Track")})}
          <media-menu-items class="vds-menu-items">
            <media-audio-radio-group
              class="vds-audio-track-radio-group vds-radio-group"
              empty-label=${s}
            >
              <template>
                <media-radio class="vds-audio-track-radio vds-radio">
                  <slot name="menu-radio-check-icon" data-class="vds-icon"></slot>
                  <span class="vds-radio-label" data-part="label"></span>
                </media-radio>
              </template>
            </media-audio-radio-group>
          </media-menu-items>
        </media-menu>
      `})})),x((()=>{const{noAudioGain:t,translations:e}=H(),{canSetAudioGain:s}=(0,i.nV)();if((0,n.EW)((()=>!s()||t()))())return null;const{audioGain:o}=(0,i.nV)();return ft({label:z(e,"Boost"),value:x((()=>Math.round(100*((o()??1)-1))+"%")),children:[kt({upIcon:"menu-audio-boost-up",downIcon:"menu-audio-boost-down",children:It(),isMin:()=>100*((o()??1)-1)<=Wt(),isMax:()=>100*((o()??1)-1)===Ot()})]})}))]}
        </media-menu-items>
      </media-menu>
    `})),x((()=>{const{translations:t}=H(),{hasCaptions:e}=(0,i.nV)(),s=z(t,"Off");return e()?a.qy`
      <media-menu class="vds-captions-menu vds-menu">
        ${At({label:()=>K(t,"Captions"),icon:"menu-captions"})}
        <media-menu-items class="vds-menu-items">
          <media-captions-radio-group
            class="vds-captions-radio-group vds-radio-group"
            off-label=${s}
          >
            <template>
              <media-radio class="vds-caption-radio vds-radio">
                <slot name="menu-radio-check-icon" data-class="vds-icon"></slot>
                <span class="vds-radio-label" data-part="label"></span>
              </media-radio>
            </template>
          </media-captions-radio-group>
        </media-menu-items>
      </media-menu>
    `:null}))]:null))}
      </media-menu-items>
    `;return a.qy`
      <media-menu class="vds-settings-menu vds-menu" @open=${function(){v.set(!0)}} @close=${function(){v.set(!1)}}>
        <media-tooltip class="vds-tooltip">
          <media-tooltip-trigger>
            <media-menu-button
              class="vds-menu-button vds-button"
              aria-label=${z(l,"Settings")}
            >
              ${F("menu-settings","vds-rotate-icon")}
            </media-menu-button>
          </media-tooltip-trigger>
          <media-tooltip-content
            class="vds-tooltip-content"
            placement=${(0,n.Tn)(s)?x(s):s}
          >
            ${z(l,"Settings")}
          </media-tooltip-content>
        </media-tooltip>
        ${e?at(r,h):h}
      </media-menu>
    `}))}function Dt({orientation:t,tooltip:e}){return x((()=>{const{pointer:s,muted:r,canSetVolume:c}=(0,i.nV)();if("coarse"===s()&&!r())return null;if(!c())return X({tooltip:e});const d=(0,n.O)(void 0),u=(0,o._T)(d);return a.qy`
      <div class="vds-volume" ?data-active=${x(u)} ${E(d.set)}>
        ${X({tooltip:e})}
        <div class="vds-volume-popup">${function({orientation:t}={}){const{translations:e}=H(),s=z(e,"Volume");return a.qy`
    <media-volume-slider
      class="vds-volume-slider vds-slider"
      aria-label=${s}
      orientation=${l(t)}
    >
      <div class="vds-slider-track"></div>
      <div class="vds-slider-track-fill vds-slider-track"></div>
      <media-slider-preview class="vds-slider-preview" no-clamp>
        <media-slider-value class="vds-slider-value"></media-slider-value>
      </media-slider-preview>
      <div class="vds-slider-thumb"></div>
    </media-volume-slider>
  `}({orientation:t})}</div>
      </div>
    `}))}function Ft(){const t=(0,n.O)(void 0),e=(0,n.O)(0),{thumbnails:s,translations:i,sliderChaptersMinWidth:l,disableTimeSlider:r,seekStep:c,noScrubGesture:d}=H(),u=z(i,"Seek"),p=x(r),m=x((()=>e()<l())),v=x(s);return(0,o.wY)(t,(()=>{const s=t();s&&e.set(s.clientWidth)})),a.qy`
    <media-time-slider
      class="vds-time-slider vds-slider"
      aria-label=${u}
      key-step=${x(c)}
      ?disabled=${p}
      ?no-swipe-gesture=${x(d)}
      ${E(t.set)}
    >
      <media-slider-chapters class="vds-slider-chapters" ?disabled=${m}>
        <template>
          <div class="vds-slider-chapter">
            <div class="vds-slider-track"></div>
            <div class="vds-slider-track-fill vds-slider-track"></div>
            <div class="vds-slider-progress vds-slider-track"></div>
          </div>
        </template>
      </media-slider-chapters>
      <div class="vds-slider-thumb"></div>
      <media-slider-preview class="vds-slider-preview">
        <media-slider-thumbnail
          class="vds-slider-thumbnail vds-thumbnail"
          .src=${v}
        ></media-slider-thumbnail>
        <div class="vds-slider-chapter-title" data-part="chapter-title"></div>
        <media-slider-value class="vds-slider-value"></media-slider-value>
      </media-slider-preview>
    </media-time-slider>
  `}function Rt(){return x((()=>{const{live:t}=(0,i.nV)();return t()?st():a.qy`
    <div class="vds-time-group">
      ${x((()=>{const{duration:t}=(0,i.nV)();return t()?[a.qy`<media-time class="vds-time" type="current"></media-time>`,a.qy`<div class="vds-time-divider">/</div>`,a.qy`<media-time class="vds-time" type="duration"></media-time>`]:null}))}
    </div>
  `}))}function zt(){return x((()=>{const{textTracks:t}=(0,i.$c)(),{title:e,started:s}=(0,i.nV)(),o=(0,n.O)(null);return(0,I.q)(t,"chapters",o.set),!o()||!s()&&e()?a.qy`<media-title class="vds-chapter-title"></media-title>`:Ut()}))}function Ut(){return a.qy`<media-chapter-title class="vds-chapter-title"></media-chapter-title>`}class Zt extends P{async loadIcons(){const t=(await s.e(21).then(s.bind(s,3021))).icons,e={};for(const s of Object.keys(t))e[s]=T({name:s,paths:t[s]});return e}}var jt=s(1622);let Xt=class extends G{static props={...super.props,when:({viewType:t})=>"audio"===t,smallWhen:({width:t})=>t<576}};function Yt(){const t="top end";return[rt({tooltip:"top",placement:t,portal:!0}),Kt({tooltip:"top end",placement:t,portal:!0})]}class Jt extends((0,n.xr)(jt.W,Xt)){static tagName="media-audio-layout";static attrs={smallWhen:{converter:t=>"never"!==t&&!!t}};#h;#g=(0,n.O)(!1);onSetup(){this.forwardKeepAlive=!1,this.#h=(0,i.$c)(),this.classList.add("vds-audio-layout"),this.#f()}onConnect(){Q("audio",(()=>this.isMatch)),this.#_()}render(){return x(this.#A.bind(this))}#A(){return this.isMatch?[D(),it(),a.qy`
      <media-controls class="vds-controls">
        <media-controls-group class="vds-controls-group">
          ${[et({backward:!0,tooltip:"top start"}),j({tooltip:"top"}),et({tooltip:"top"}),x((()=>{let t=(0,n.O)(void 0),e=(0,n.O)(!1),s=(0,i.$c)(),{title:l,started:r,currentTime:c,ended:d}=(0,i.nV)(),{translations:u}=H(),p=(0,o.ZG)(t),m=()=>r()||c()>0;const v=()=>{const t=d()?"Replay":m()?"Continue":"Play";return`${K(u,t)}: ${l()}`};function h(){return a.qy`
        <span class="vds-title-text">
          ${x(v)}${x((()=>m()?Ut():null))}
        </span>
      `}return(0,n.QZ)((()=>{p()&&document.activeElement===document.body&&s.player.el?.focus({preventScroll:!0})})),(0,o.wY)(t,(function(){const s=t(),i=!!s&&!p()&&s.clientWidth<s.children[0].clientWidth;s&&(0,n.p1)(s,"vds-marquee",i),e.set(i)})),l()?a.qy`
          <span class="vds-title" title=${x(v)} ${E(t.set)}>
            ${[h(),x((()=>e()&&!p()?h():null))]}
          </span>
        `:ot()})),Ft(),x((()=>{const{live:t,duration:e}=(0,i.nV)();return t()?st():e()?a.qy`<media-time class="vds-time" type="current" toggle remainder></media-time>`:null})),Dt({orientation:"vertical",tooltip:"top"}),Y({tooltip:"top"}),nt(),U({tooltip:"top"}),Yt()]}
        </media-controls-group>
      </media-controls>
    `]:null}#_(){const{menuPortal:t}=H();(0,n.QZ)((()=>{if(!this.isMatch)return;const e=lt(this,this.menuContainer,"vds-audio-layout",(()=>this.isSmallLayout)),s=e?[this,e]:[this];return(this.$props.customIcons()?new S(s):new Zt(s)).connect(),t.set(e),()=>{e.remove(),t.set(null)}}))}#f(){const{pointer:t}=this.#h.$state;(0,n.QZ)((()=>{"coarse"===t()&&(0,n.QZ)(this.#w.bind(this))}))}#w(){this.#g()?((0,n.k6)(this,"pointerdown",(t=>t.stopPropagation())),(0,n.k6)(window,"pointerdown",this.#x.bind(this))):(0,n.k6)(this,"pointerdown",this.#k.bind(this),{capture:!0})}#k(t){const{target:e}=t;(0,o.sb)(e)&&e.closest(".vds-time-slider")&&(t.stopImmediatePropagation(),this.setAttribute("data-scrubbing",""),this.#g.set(!0))}#x(){this.#g.set(!1),this.removeAttribute("data-scrubbing")}}const te=r(class extends c{constructor(){super(...arguments),this.key=a.s6}render(t,e){return this.key=t,e}update(t,[e,s]){return e!==this.key&&(((t,e=h)=>{t._$AH=e})(t),this.key=e),s}});class ee extends G{static props={...super.props,when:({viewType:t})=>"video"===t,smallWhen:({width:t,height:e})=>t<576||e<380}}function se(){return x((()=>{const t=(0,i.$c)(),{noKeyboardAnimations:e,userPrefersKeyboardAnimations:s}=H();if((0,n.EW)((()=>e()||!s()))())return null;const l=(0,n.O)(!1),{lastKeyboardAction:r}=t.$state;(0,n.QZ)((()=>{l.set(!!r());const t=setTimeout((()=>l.set(!1)),500);return()=>{l.set(!1),window.clearTimeout(t)}}));const c=(0,n.EW)((()=>{const t=r()?.action;return t&&l()?(0,n.BW)(t):null})),d=(0,n.EW)((()=>"vds-kb-action"+(l()?"":" hidden"))),u=(0,n.EW)(ne),p=(0,n.EW)((()=>{const t=function(){const{$state:t}=(0,i.$c)(),e=t.lastKeyboardAction()?.action;switch(e){case"togglePaused":return t.paused()?"kb-pause-icon":"kb-play-icon";case"toggleMuted":return t.muted()||0===t.volume()?"kb-mute-icon":t.volume()>=.5?"kb-volume-up-icon":"kb-volume-down-icon";case"toggleFullscreen":return`kb-fs-${t.fullscreen()?"enter":"exit"}-icon`;case"togglePictureInPicture":return`kb-pip-${t.pictureInPicture()?"enter":"exit"}-icon`;case"toggleCaptions":return t.hasCaptions()?`kb-cc-${t.textTrack()?"on":"off"}-icon`:null;case"volumeUp":return"kb-volume-up-icon";case"volumeDown":return"kb-volume-down-icon";case"seekForward":return"kb-seek-forward-icon";case"seekBackward":return"kb-seek-backward-icon";default:return null}}();return t?(0,o.TL)(t):null}));return a.qy`
      <div class=${x(d)} data-action=${x(c)}>
        <div class="vds-kb-text-wrapper">
          <div class="vds-kb-text">${x(u)}</div>
        </div>
        ${x((()=>te(r(),function(){const t=p();return t?a.qy`
        <div class="vds-kb-bezel">
          <div class="vds-kb-icon">${t}</div>
        </div>
      `:null}())))}
      </div>
    `}))}function ne(){const{$state:t}=(0,i.$c)(),e=t.lastKeyboardAction()?.action,s=t.audioGain()??1;switch(e){case"toggleMuted":return t.muted()?"0%":ie(t.volume(),s);case"volumeUp":case"volumeDown":return ie(t.volume(),s);default:return""}}function ie(t,e){return`${Math.round(t*e*100)}%`}function oe(){return a.qy`
    <div class="vds-buffering-indicator">
      <media-spinner class="vds-buffering-spinner"></media-spinner>
    </div>
  `}function ae(){const{menuGroup:t,smallWhen:e}=H(),s=()=>"top"===t()||e()?"bottom":"top",i=(0,n.EW)((()=>`${s()} ${"top"===t()?"end":"center"}`)),o=(0,n.EW)((()=>`${s()} end`));return[rt({tooltip:i,placement:o,portal:!0}),Kt({tooltip:i,placement:o,portal:!0})]}function le(){return x((()=>{const{noGestures:t}=H();return t()?null:a.qy`
      <div class="vds-gestures">
        <media-gesture class="vds-gesture" event="pointerup" action="toggle:paused"></media-gesture>
        <media-gesture
          class="vds-gesture"
          event="pointerup"
          action="toggle:controls"
        ></media-gesture>
        <media-gesture
          class="vds-gesture"
          event="dblpointerup"
          action="toggle:fullscreen"
        ></media-gesture>
        <media-gesture class="vds-gesture" event="dblpointerup" action="seek:-10"></media-gesture>
        <media-gesture class="vds-gesture" event="dblpointerup" action="seek:10"></media-gesture>
      </div>
    `}))}class re extends((0,n.xr)(jt.W,ee)){static tagName="media-video-layout";static attrs={smallWhen:{converter:t=>"never"!==t&&!!t}};#h;onSetup(){this.forwardKeepAlive=!1,this.#h=(0,i.$c)(),this.classList.add("vds-video-layout")}onConnect(){Q("video",(()=>this.isMatch)),this.#_()}render(){return x(this.#A.bind(this))}#_(){const{menuPortal:t}=H();(0,n.QZ)((()=>{if(!this.isMatch)return;const e=lt(this,this.menuContainer,"vds-video-layout",(()=>this.isSmallLayout)),s=e?[this,e]:[this];return(this.$props.customIcons()?new S(s):new Zt(s)).connect(),t.set(e),()=>{e.remove(),t.set(null)}}))}#A(){const{load:t}=this.#h.$props,{canLoad:e,streamType:s,nativeControls:n}=this.#h.$state;return!n()&&this.isMatch?"play"!==t()||e()?"unknown"===s()?oe():this.isSmallLayout?[D(),le(),oe(),it(),se(),a.qy`<div class="vds-scrim"></div>`,a.qy`
      <media-controls class="vds-controls">
        <media-controls-group class="vds-controls-group">
          ${[U({tooltip:"top start"}),Z({tooltip:"bottom start"}),ot(),Y({tooltip:"bottom"}),nt(),ae(),Dt({orientation:"vertical",tooltip:"bottom end"})]}
        </media-controls-group>

        ${ot()}

        <media-controls-group class="vds-controls-group" style="pointer-events: none;">
          ${[ot(),j({tooltip:"top"}),ot()]}
        </media-controls-group>

        ${ot()}

        <media-controls-group class="vds-controls-group">
          ${[Rt(),zt(),tt({tooltip:"top end"})]}
        </media-controls-group>

        <media-controls-group class="vds-controls-group">
          ${Ft()}
        </media-controls-group>
      </media-controls>
    `,x((()=>{const{duration:t}=(0,i.nV)();return 0===t()?null:a.qy`
      <div class="vds-start-duration">
        <media-time class="vds-time" type="duration"></media-time>
      </div>
    `}))]:[D(),le(),oe(),se(),it(),a.qy`<div class="vds-scrim"></div>`,a.qy`
      <media-controls class="vds-controls">
        ${[a.qy`
    <media-controls-group class="vds-controls-group">
      ${x((()=>{const{menuGroup:t}=H();return"top"===t()?[ot(),ae()]:null}))}
    </media-controls-group>
  `,ot(),a.qy`<media-controls-group class="vds-controls-group"></media-controls-group>`,ot(),a.qy`
            <media-controls-group class="vds-controls-group">
              ${Ft()}
            </media-controls-group>
          `,a.qy`
            <media-controls-group class="vds-controls-group">
              ${[j({tooltip:"top start"}),Dt({orientation:"horizontal",tooltip:"top"}),Rt(),zt(),Y({tooltip:"top"}),x((()=>{const{menuGroup:t}=H();return"bottom"===t()?ae():null})),U({tooltip:"top"}),Z({tooltip:"top"}),nt(),J(),tt({tooltip:"top end"})]}
            </media-controls-group>
          `]}
      </media-controls>
    `]:a.qy`
    <div class="vds-load-container">
      ${[oe(),j({tooltip:"top"})]}
    </div>
  `:null}}const ce=(0,n.q6)();function de(){return(0,n.NT)(ce)}const ue={clickToPlay:!0,clickToFullscreen:!0,controls:["play-large","play","progress","current-time","mute+volume","captions","settings","pip","airplay","fullscreen"],customIcons:!1,displayDuration:!1,download:null,markers:null,invertTime:!0,thumbnails:null,toggleTime:!0,translations:null,seekTime:10,speed:[.5,.75,1,1.25,1.5,1.75,2,4]};class pe extends n.uA{static props=ue;#h;onSetup(){this.#h=(0,i.$c)(),(0,n.Pp)(ce,{...this.$props,previewTime:(0,n.O)(0)})}}class me extends P{async loadIcons(){const t=(await s.e(663).then(s.bind(s,5663))).icons,e={};for(const s of Object.keys(t))e[s]=T({name:s,paths:t[s],viewBox:"0 0 18 18"});return e}}function ve(t,e){return t()?.[e]??e}function he(){const t=(0,i.$c)(),{translations:e}=de(),{title:s}=t.$state,n=x((()=>`${ve(e,"Play")}, ${s()}`));return a.qy`
    <media-play-button
      class="plyr__control plyr__control--overlaid"
      aria-label=${n}
      data-plyr="play"
    >
      <slot name="play-icon"></slot>
    </button>
  `}function ye(){const{controls:t}=de();return x((()=>t().includes("play-large")?he():null))}function $e(){const{thumbnails:t,previewTime:e}=de();return a.qy`
    <media-thumbnail
      .src=${x(t)}
      class="plyr__preview-scrubbing"
      time=${x((()=>e()))}
    ></media-thumbnail>
  `}function be(){const t=(0,i.$c)(),{poster:e}=t.$state,s=x((()=>`background-image: url("${e()}");`));return a.qy`<div class="plyr__poster" style=${s}></div>`}function ge(){const{controls:t}=de(),e=x((()=>t().map(fe)));return a.qy`<div class="plyr__controls">${e}</div>`}function fe(t){switch(t){case"airplay":return function(){const{translations:t}=de();return a.qy`
    <media-airplay-button class="plyr__controls__item plyr__control" data-plyr="airplay">
      <slot name="airplay-icon"></slot>
      <span class="plyr__tooltip">${Me(t,"AirPlay")}</span>
    </media-airplay-button>
  `}();case"captions":return function(){const{translations:t}=de(),e=Me(t,"Disable captions"),s=Me(t,"Enable captions");return a.qy`
    <media-caption-button
      class="plyr__controls__item plyr__control"
      data-no-label
      data-plyr="captions"
    >
      <slot name="captions-on-icon" data-class="icon--pressed"></slot>
      <slot name="captions-off-icon" data-class="icon--not-pressed"></slot>
      <span class="label--pressed plyr__tooltip">${e}</span>
      <span class="label--not-pressed plyr__tooltip">${s}</span>
    </media-caption-button>
  `}();case"current-time":return function(){const t=(0,i.$c)(),{translations:e,invertTime:s,toggleTime:o,displayDuration:l}=de(),r=(0,n.O)((0,n.se)(s));function c(t){!o()||l()||(0,n.kx)(t)&&!(0,n.SK)(t)||r.set((t=>!t))}return x((()=>{const{streamType:s}=t.$state,n=Me(e,"LIVE"),i=Me(e,"Current time"),o=x((()=>!l()&&r()));return"live"===s()||"ll-live"===s()?a.qy`
          <media-live-button
            class="plyr__controls__item plyr__control plyr__live-button"
            data-plyr="live"
          >
            <span class="plyr__live-button__text">${n}</span>
          </media-live-button>
        `:a.qy`
          <media-time
            type="current"
            class="plyr__controls__item plyr__time plyr__time--current"
            tabindex="0"
            role="timer"
            aria-label=${i}
            ?remainder=${o}
            @pointerup=${c}
            @keydown=${c}
          ></media-time>
          ${x((()=>l()?we():null))}
        `}))}();case"download":return x((()=>{const t=(0,i.$c)(),{translations:e,download:s}=de(),{title:n,source:o}=t.$state,l=o(),r=s(),c=(0,N.d_)({title:n(),src:l,download:r}),d=Me(e,"Download");return c?a.qy`
          <a
            class="plyr__controls__item plyr__control"
            href=${c.url+`?download=${c.name}`}
            download=${c.name}
            target="_blank"
          >
            <slot name="download-icon" />
            <span class="plyr__tooltip">${d}</span>
          </a>
        `:null}));case"duration":return we();case"fast-forward":return function(){const{translations:t,seekTime:e}=de(),s=x((()=>`${ve(t,"Forward")} ${e()}s`)),n=x(e);return a.qy`
    <media-seek-button
      class="plyr__controls__item plyr__control"
      seconds=${n}
      data-no-label
      data-plyr="fast-forward"
    >
      <slot name="fast-forward-icon"></slot>
      <span class="plyr__tooltip">${s}</span>
    </media-seek-button>
  `}();case"fullscreen":return function(){const{translations:t}=de(),e=Me(t,"Enter Fullscreen"),s=Me(t,"Exit Fullscreen");return a.qy`
    <media-fullscreen-button
      class="plyr__controls__item plyr__control"
      data-no-label
      data-plyr="fullscreen"
    >
      <slot name="enter-fullscreen-icon" data-class="icon--pressed"></slot>
      <slot name="exit-fullscreen-icon" data-class="icon--not-pressed"></slot>
      <span class="label--pressed plyr__tooltip">${s}</span>
      <span class="label--not-pressed plyr__tooltip">${e}</span>
    </media-fullscreen-button>
  `}();case"mute":case"volume":case"mute+volume":return function(t){return x((()=>{const e="mute"===t||"mute+volume"===t,s="volume"===t||"mute+volume"===t;return a.qy`
      <div class="plyr__controls__item plyr__volume">
        ${[e?_e():null,s?Ae():null]}
      </div>
    `}))}(t);case"pip":return function(){const{translations:t}=de(),e=Me(t,"Enter PiP"),s=Me(t,"Exit PiP");return a.qy`
    <media-pip-button class="plyr__controls__item plyr__control" data-no-label data-plyr="pip">
      <slot name="pip-icon"></slot>
      <slot name="enter-pip-icon" data-class="icon--pressed"></slot>
      <slot name="exit-pip-icon" data-class="icon--not-pressed"></slot>
      <span class="label--pressed plyr__tooltip">${s}</span>
      <span class="label--not-pressed plyr__tooltip">${e}</span>
    </media-pip-button>
  `}();case"play":return function(){const{translations:t}=de(),e=Me(t,"Play"),s=Me(t,"Pause");return a.qy`
    <media-play-button class="plyr__controls__item plyr__control" data-no-label data-plyr="play">
      <slot name="pause-icon" data-class="icon--pressed"></slot>
      <slot name="play-icon" data-class="icon--not-pressed"></slot>
      <span class="label--pressed plyr__tooltip">${s}</span>
      <span class="label--not-pressed plyr__tooltip">${e}</span>
    </media-play-button>
  `}();case"progress":return function(){let t=(0,i.$c)(),{duration:e,viewType:s}=t.$state,{translations:o,markers:l,thumbnails:r,seekTime:c,previewTime:d}=de(),p=Me(o,"Seek"),m=(0,n.O)(null),v=x((()=>{const t=m();return t?a.qy`<span class="plyr__progress__marker-label">${u(t.label)}<br /></span>`:null}));function h(){m.set(this)}function y(){m.set(null)}return a.qy`
    <div class="plyr__controls__item plyr__progress__container">
      <div class="plyr__progress">
        <media-time-slider
          class="plyr__slider"
          data-plyr="seek"
          pause-while-dragging
          key-step=${x(c)}
          aria-label=${p}
          @media-seeking-request=${function(t){d.set(t.detail)}}
        >
          <div class="plyr__slider__track"></div>
          <div class="plyr__slider__thumb"></div>
          <div class="plyr__slider__buffer"></div>
          ${x((function(){const t=r(),e=x((()=>"audio"===s()));return t?a.qy`
          <media-slider-preview class="plyr__slider__preview" ?no-clamp=${e}>
            <media-slider-thumbnail .src=${t} class="plyr__slider__preview__thumbnail">
              <span class="plyr__slider__preview__time-container">
                ${v}
                <media-slider-value class="plyr__slider__preview__time"></media-slider-value>
              </span>
            </media-slider-thumbnail>
          </media-slider-preview>
        `:a.qy`
          <span class="plyr__tooltip">
            ${v}
            <media-slider-value></media-slider-value>
          </span>
        `}))}${x((function(){const t=e();return Number.isFinite(t)?l()?.map((e=>a.qy`
        <span
          class="plyr__progress__marker"
          @mouseenter=${h.bind(e)}
          @mouseleave=${y}
          style=${`left: ${e.time/t*100}%;`}
        ></span>
      `)):null}))}
        </media-time-slider>
      </div>
    </div>
  `}();case"restart":return function(){const{translations:t}=de(),{remote:e}=(0,i.$c)(),s=Me(t,"Restart");function o(t){(0,n.kx)(t)&&!(0,n.SK)(t)||e.seek(0,t)}return a.qy`
    <button
      type="button"
      class="plyr__control"
      data-plyr="restart"
      @pointerup=${o}
      @keydown=${o}
    >
      <slot name="restart-icon"></slot>
      <span class="plyr__tooltip">${s}</span>
    </button>
  `}();case"rewind":return function(){const{translations:t,seekTime:e}=de(),s=x((()=>`${ve(t,"Rewind")} ${e()}s`)),n=x((()=>-1*e()));return a.qy`
    <media-seek-button
      class="plyr__controls__item plyr__control"
      seconds=${n}
      data-no-label
      data-plyr="rewind"
    >
      <slot name="rewind-icon"></slot>
      <span class="plyr__tooltip">${s}</span>
    </media-seek-button>
  `}();case"settings":return function(){const{translations:t}=de(),e=Me(t,"Settings");return a.qy`
    <div class="plyr__controls__item plyr__menu">
      <media-menu>
        <media-menu-button class="plyr__control" data-plyr="settings">
          <slot name="settings-icon" />
          <span class="plyr__tooltip">${e}</span>
        </media-menu-button>
        <media-menu-items class="plyr__menu__container" placement="top end">
          <div><div>${[ke({label:"Audio",children:qe()}),ke({label:"Captions",children:Se()}),ke({label:"Quality",children:Te()}),ke({label:"Speed",children:Ce()})]}</div></div>
        </media-menu-items>
      </media-menu>
    </div>
  `}();default:return null}}function _e(){const{translations:t}=de(),e=Me(t,"Mute"),s=Me(t,"Unmute");return a.qy`
    <media-mute-button class="plyr__control" data-no-label data-plyr="mute">
      <slot name="muted-icon" data-class="icon--pressed"></slot>
      <slot name="volume-icon" data-class="icon--not-pressed"></slot>
      <span class="label--pressed plyr__tooltip">${s}</span>
      <span class="label--not-pressed plyr__tooltip">${e}</span>
    </media-mute-button>
  `}function Ae(){const{translations:t}=de(),e=Me(t,"Volume");return a.qy`
    <media-volume-slider class="plyr__slider" data-plyr="volume" aria-label=${e}>
      <div class="plyr__slider__track"></div>
      <div class="plyr__slider__thumb"></div>
    </media-volume-slider>
  `}function we(){const{translations:t}=de(),e=Me(t,"Duration");return a.qy`
    <media-time
      type="duration"
      class="plyr__controls__item plyr__time plyr__time--duration"
      role="timer"
      tabindex="0"
      aria-label=${e}
    ></media-time>
  `}function xe(){const t=(0,i.$c)(),e=(0,n.O)(void 0),s=x((()=>u(e()?.text)));return(0,n.QZ)((()=>{const s=t.$state.textTrack();if(s)return i(),(0,n.k6)(s,"cue-change",i);function i(){e.set(s?.activeCues[0])}})),a.qy`
    <div class="plyr__captions" dir="auto">
      <span class="plyr__caption">${s}</span>
    </div>
  `}function ke({label:t,children:e}){const s=(0,n.O)(!1);return a.qy`
    <media-menu @open=${()=>s.set(!0)} @close=${()=>s.set(!1)}>
      ${function({open:t,label:e}){const{translations:s}=de(),n=x((()=>"plyr__control plyr__control--"+(t()?"back":"forward")));return a.qy`
    <media-menu-button class=${n} data-plyr="settings">
      <span class="plyr__menu__label" aria-hidden=${i=t,x((()=>i()?"true":"false"))}>
        ${Me(s,e)}
      </span>
      <span class="plyr__menu__value" data-part="hint"></span>
      ${function(){const e=Me(s,"Go back to previous menu");return x((()=>t()?a.qy`<span class="plyr__sr-only">${e}</span>`:null))}()}
    </media-menu-button>
  `;var i}({label:t,open:s})}
      <media-menu-items>${e}</media-menu-items>
    </media-menu>
  `}function qe(){const{translations:t}=de();return a.qy`
    <media-audio-radio-group empty-label=${Me(t,"Default")}>
      <template>
        <media-radio class="plyr__control" data-plyr="audio">
          <span data-part="label"></span>
        </media-radio>
      </template>
    </media-audio-radio-group>
  `}function Ce(){const{translations:t,speed:e}=de();return a.qy`
    <media-speed-radio-group .rates=${e} normal-label=${Me(t,"Normal")}>
      <template>
        <media-radio class="plyr__control" data-plyr="speed">
          <span data-part="label"></span>
        </media-radio>
      </template>
    </media-speed-radio-group>
  `}function Se(){const{translations:t}=de();return a.qy`
    <media-captions-radio-group off-label=${Me(t,"Disabled")}>
      <template>
        <media-radio class="plyr__control" data-plyr="captions">
          <span data-part="label"></span>
        </media-radio>
      </template>
    </media-captions-radio-group>
  `}function Te(){const{translations:t}=de();return a.qy`
    <media-quality-radio-group auto-label=${Me(t,"Auto")}>
      <template>
        <media-radio class="plyr__control" data-plyr="quality">
          <span data-part="label"></span>
        </media-radio>
      </template>
    </media-quality-radio-group>
  `}function Me(t,e){return x((()=>ve(t,e)))}class Pe extends((0,n.xr)(jt.W,pe)){static tagName="media-plyr-layout";#h;onSetup(){this.forwardKeepAlive=!1,this.#h=(0,i.$c)()}onConnect(){this.#h.player.el?.setAttribute("data-layout","plyr"),(0,n.zp)((()=>this.#h.player.el?.removeAttribute("data-layout"))),function(t,e){const{canAirPlay:s,canFullscreen:i,canPictureInPicture:o,controlsHidden:a,currentTime:l,fullscreen:r,hasCaptions:c,isAirPlayConnected:d,paused:u,pictureInPicture:p,playing:m,pointer:v,poster:h,textTrack:y,viewType:$,waiting:b}=e.$state;t.classList.add("plyr"),t.classList.add("plyr--full-ui");const g={"plyr--airplay-active":d,"plyr--airplay-supported":s,"plyr--fullscreen-active":r,"plyr--fullscreen-enabled":i,"plyr--hide-controls":a,"plyr--is-touch":()=>"coarse"===v(),"plyr--loading":b,"plyr--paused":u,"plyr--pip-active":p,"plyr--pip-enabled":o,"plyr--playing":m,"plyr__poster-enabled":h,"plyr--stopped":()=>u()&&0===l(),"plyr--captions-active":y,"plyr--captions-enabled":c},f=(0,n.z2)();for(const e of Object.keys(g))f.add((0,n.QZ)((()=>{t.classList.toggle(e,!!g[e]())})));f.add((0,n.QZ)((()=>{const e=`plyr--${$()}`;return t.classList.add(e),()=>t.classList.remove(e)})),(0,n.QZ)((()=>{const{$provider:s}=e,n=s()?.type,i=`plyr--${function(t){return"audio"===t||"video"===t}(n)?"html5":n}`;return t.classList.toggle(i,!!n),()=>t.classList.remove(i)})))}(this,this.#h),(0,n.QZ)((()=>{this.$props.customIcons()?new S([this]).connect():new me([this]).connect()}))}render(){return x(this.#A.bind(this))}#A(){const{viewType:t}=this.#h.$state;return"audio"===t()?function(){const t=new Set(["captions","pip","airplay","fullscreen"]),{controls:e}=de(),s=x((()=>e().filter((e=>!t.has(e))).map(fe)));return a.qy`<div class="plyr__controls">${s}</div>`}():"video"===t()?function(){const t=(0,i.$c)(),{load:e}=t.$props,{canLoad:s}=t.$state;return(0,n.EW)((()=>"play"===e()&&!s()))()?[he(),be()]:[ye(),$e(),be(),ge(),x((()=>{const{clickToPlay:t,clickToFullscreen:e}=de();return[t()?a.qy`
            <media-gesture
              class="plyr__gesture"
              event="pointerup"
              action="toggle:paused"
            ></media-gesture>
          `:null,e()?a.qy`
            <media-gesture
              class="plyr__gesture"
              event="dblpointerup"
              action="toggle:fullscreen"
            ></media-gesture>
          `:null]})),xe()]}():null}}(0,n.Xq)(Jt),(0,n.Xq)(re),(0,n.Xq)(Pe)}}]);