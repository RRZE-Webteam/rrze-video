"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[974],{853:(e,t,r)=>{r.d(t,{Aj:()=>l,KE:()=>s,KH:()=>g,N9:()=>c,VF:()=>h,W7:()=>d,XU:()=>u,Zl:()=>p,qh:()=>o,t0:()=>i,x5:()=>n});var a=r(895);function o(){return"https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"}function n(){return!!window.cast?.framework}function s(){return!!window.chrome?.cast?.isAvailable}function i(){return c().getCastState()===cast.framework.CastState.CONNECTED}function c(){return window.cast.framework.CastContext.getInstance()}function l(){return c().getCurrentSession()}function p(){return l()?.getSessionObj().media[0]}function g(e){const t=p()?.media.contentId;return t===e?.src}function d(){return{language:"en-US",autoJoinPolicy:chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED,receiverApplicationId:chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID,resumeSavedSession:!0,androidReceiverCompatible:!0}}function h(e){return`Google Cast Error Code: ${e}`}function u(e,t){return(0,a.yl)(c(),e,t)}},974:(e,t,r)=>{r.r(t),r.d(t,{GoogleCastLoader:()=>i});var a=r(510),o=r(321),n=r(853),s=r(895);class i{name="google-cast";target;#e;get cast(){return(0,n.N9)()}mediaType(){return"video"}canPlay(e){return a.G9&&!a.cj&&(0,a.Ew)(e)}async prompt(e){let t,r,a;try{t=await this.#t(e),this.#e||(this.#e=new cast.framework.RemotePlayer,new cast.framework.RemotePlayerController(this.#e)),r=e.player.createEvent("google-cast-prompt-open",{trigger:t}),e.player.dispatchEvent(r),this.#r(e,"connecting",r),await this.#a((0,s.fj)(e.$props.googleCast)),e.$state.remotePlaybackInfo.set({deviceName:(0,n.Aj)()?.getCastDevice().friendlyName}),(0,n.t0)()&&this.#r(e,"connected",r)}catch(o){const s=o instanceof Error?o:this.#o((o+"").toUpperCase(),"Prompt failed.");throw a=e.player.createEvent("google-cast-prompt-error",{detail:s,trigger:r??t,cancelable:!0}),e.player.dispatch(a),this.#r(e,(0,n.t0)()?"connected":"disconnected",a),s}finally{e.player.dispatch("google-cast-prompt-close",{trigger:a??r??t})}}async load(e){if(!this.#e)throw Error("[vidstack] google cast player was not initialized");return new((await r.e(299).then(r.bind(r,299))).GoogleCastProvider)(this.#e,e)}async#t(e){if((0,n.x5)())return;const t=e.player.createEvent("google-cast-load-start");e.player.dispatch(t),await(0,o.ve)((0,n.qh)()),await customElements.whenDefined("google-cast-launcher");const r=e.player.createEvent("google-cast-loaded",{trigger:t});if(e.player.dispatch(r),!(0,n.KE)())throw this.#o("CAST_NOT_AVAILABLE","Google Cast not available on this platform.");return r}async#a(e){this.#n(e);const t=await this.cast.requestSession();if(t)throw this.#o(t.toUpperCase(),(0,n.VF)(t))}#n(e){this.cast?.setOptions({...(0,n.W7)(),...e})}#r(e,t,r){const a={type:"google-cast",state:t};e.notify("remote-playback-change",a,r)}#o(e,t){const r=Error(t);return r.code=e,r}}}}]);
//# sourceMappingURL=974.js.map