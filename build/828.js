"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[828],{6828:(e,t,a)=>{a.r(t),a.d(t,{getCastContext:()=>n,getCastErrorMessage:()=>p,getCastSession:()=>i,getCastSessionMedia:()=>c,hasActiveCastSession:()=>l,listenCastContextEvent:()=>g,loader:()=>d});var r=a(1999),o=a(3952);function s(){return n().getCastState()===cast.framework.CastState.CONNECTED}function n(){return window.cast.framework.CastContext.getInstance()}function i(){return n().getCurrentSession()}function c(){return i()?.getSessionObj().media[0]}function l(e){const t=c()?.media.contentId;return t===e?.src}function p(e){return`Google Cast Error Code: ${e}`}function g(e,t){return(0,o.listenEvent)(n(),e,t)}var d=Object.freeze({__proto__:null,GoogleCastLoader:class{name="google-cast";target;#e;get cast(){return n()}mediaType(){return"video"}canPlay(e){return r.G_&&!r.pz&&(0,r.jx)(e)}async prompt(e){let t,a,r;try{t=await this.#t(e),this.#e||(this.#e=new cast.framework.RemotePlayer,new cast.framework.RemotePlayerController(this.#e)),a=e.player.createEvent("google-cast-prompt-open",{trigger:t}),e.player.dispatchEvent(a),this.#a(e,"connecting",a),await this.#r((0,o.peek)(e.$props.googleCast)),e.$state.remotePlaybackInfo.set({deviceName:i()?.getCastDevice().friendlyName}),s()&&this.#a(e,"connected",a)}catch(o){const n=o instanceof Error?o:this.#o((o+"").toUpperCase(),"Prompt failed.");throw r=e.player.createEvent("google-cast-prompt-error",{detail:n,trigger:a??t,cancelable:!0}),e.player.dispatch(r),this.#a(e,s()?"connected":"disconnected",r),n}finally{e.player.dispatch("google-cast-prompt-close",{trigger:r??a??t})}}async load(e){if(r.X3)throw Error("[vidstack] can not load google cast provider server-side");if(!this.#e)throw Error("[vidstack] google cast player was not initialized");return new((await a.e(911).then(a.bind(a,2911))).GoogleCastProvider)(this.#e,e)}async#t(e){if(window.cast?.framework)return;const t=e.player.createEvent("google-cast-load-start");e.player.dispatch(t),await(0,r.k0)("https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"),await customElements.whenDefined("google-cast-launcher");const a=e.player.createEvent("google-cast-loaded",{trigger:t});if(e.player.dispatch(a),!window.chrome?.cast?.isAvailable)throw this.#o("CAST_NOT_AVAILABLE","Google Cast not available on this platform.");return a}async#r(e){this.#s(e);const t=await this.cast.requestSession();if(t)throw this.#o(t.toUpperCase(),p(t))}#s(e){this.cast?.setOptions({language:"en-US",autoJoinPolicy:chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED,receiverApplicationId:chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID,resumeSavedSession:!0,androidReceiverCompatible:!0,...e})}#a(e,t,a){const r={type:"google-cast",state:t};e.notify("remote-playback-change",r,a)}#o(e,t){const a=Error(t);return a.code=e,a}}})}}]);