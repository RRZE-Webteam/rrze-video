"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[480,167],{480:(t,e,i)=>{i.r(e),i.d(e,{HLSProvider:()=>v});var s=i(321),n=i(510),r=i(167),o=i(895),a=i(431),c=i(971),h=i(91),d=i(982),l=i(327);class u{#t;#e;#i=null;#s=null;config={};#n=new Set;get instance(){return this.#i}constructor(t,e){this.#t=t,this.#e=e}setup(t){const{streamType:e}=this.#e.$state,i=(0,o.fj)(e).includes("live"),s=(0,o.fj)(e).includes("ll-");this.#i=new t({lowLatencyMode:s,backBufferLength:s?4:i?8:void 0,renderTextTracksNatively:!1,...this.config});const n=this.#r.bind(this);for(const e of Object.values(t.Events))this.#i.on(e,n);this.#i.on(t.Events.ERROR,this.#o.bind(this));for(const t of this.#n)t(this.#i);this.#e.player.dispatch("hls-instance",{detail:this.#i}),this.#i.attachMedia(this.#t),this.#i.on(t.Events.AUDIO_TRACK_SWITCHED,this.#a.bind(this)),this.#i.on(t.Events.LEVEL_SWITCHED,this.#c.bind(this)),this.#i.on(t.Events.LEVEL_LOADED,this.#h.bind(this)),this.#i.on(t.Events.NON_NATIVE_TEXT_TRACKS_FOUND,this.#d.bind(this)),this.#i.on(t.Events.CUES_PARSED,this.#l.bind(this)),this.#e.qualities[a.W.enableAuto]=this.#u.bind(this),(0,o.yl)(this.#e.qualities,"change",this.#p.bind(this)),(0,o.yl)(this.#e.audioTracks,"change",this.#v.bind(this)),this.#s=(0,o.cE)(this.#y.bind(this))}#g(t,e){return new o.yb((t=>(0,o.Kh)(t))(t),{detail:e})}#y(){if(!this.#e.$state.live())return;const t=new d.L(this.#b.bind(this));return t.start(),t.stop.bind(t)}#b(){this.#e.$state.liveSyncPosition.set(this.#i?.liveSyncPosition??1/0)}#r(t,e){this.#e.player?.dispatch(this.#g(t,e))}#d(t,e){const i=this.#g(t,e);let s=-1;for(let t=0;t<e.tracks.length;t++){const n=e.tracks[t],r=n.subtitleTrack??n.closedCaptions,o=new c.n8({id:`hls-${n.kind}-${t}`,src:r?.url,label:n.label,language:r?.lang,kind:n.kind,default:n.default});o[c.kA.readyState]=2,o[c.kA.onModeChange]=()=>{"showing"===o.mode?(this.#i.subtitleTrack=t,s=t):s===t&&(this.#i.subtitleTrack=-1,s=-1)},this.#e.textTracks.add(o,i)}}#l(t,e){const i=this.#i?.subtitleTrack,s=this.#e.textTracks.getById(`hls-${e.type}-${i}`);if(!s)return;const n=this.#g(t,e);for(const t of e.cues)t.positionAlign="auto",s.addCue(t,n)}#a(t,e){const i=this.#e.audioTracks[e.id];if(i){const s=this.#g(t,e);this.#e.audioTracks[h.H.select](i,!0,s)}}#c(t,e){const i=this.#e.qualities[e.level];if(i){const s=this.#g(t,e);this.#e.qualities[h.H.select](i,!0,s)}}#h(t,e){if(this.#e.$state.canPlay())return;const{type:i,live:s,totalduration:n,targetduration:r}=e.details,c=this.#g(t,e);this.#e.notify("stream-type-change",s?"EVENT"===i&&Number.isFinite(n)&&r>=10?"live:dvr":"live":"on-demand",c),this.#e.notify("duration-change",n,c);const d=this.#i.media;-1===this.#i.currentLevel&&this.#e.qualities[a.W.setAuto](!0,c);for(const t of this.#i.audioTracks){const e={id:t.id.toString(),label:t.name,language:t.lang||"",kind:"main"};this.#e.audioTracks[h.H.add](e,c)}for(const t of this.#i.levels){const e={id:t.id?.toString()??t.height+"p",width:t.width,height:t.height,codec:t.codecSet,bitrate:t.bitrate};this.#e.qualities[h.H.add](e,c)}d.dispatchEvent(new o.yb("canplay",{trigger:c}))}#o(t,e){e.fatal&&("mediaError"===e.type?this.#i?.recoverMediaError():this.#f(e.error))}#f(t){this.#e.notify("error",{message:t.message,code:1,error:t})}#u(){this.#i&&(this.#i.currentLevel=-1)}#p(){const{qualities:t}=this.#e;this.#i&&!t.auto&&(this.#i[t.switch+"Level"]=t.selectedIndex,n.G9&&(this.#t.currentTime=this.#t.currentTime))}#v(){const{audioTracks:t}=this.#e;this.#i&&this.#i.audioTrack!==t.selectedIndex&&(this.#i.audioTrack=t.selectedIndex)}onInstance(t){return this.#n.add(t),()=>this.#n.delete(t)}loadSource(t){(0,o.HD)(t.src)&&this.#i?.loadSource(t.src)}destroy(){this.#i?.destroy(),this.#i=null,this.#s?.(),this.#s=null}}class p{#x;#e;#k;constructor(t,e,i){this.#x=t,this.#e=e,this.#k=i,this.#E()}async#E(){const t={onLoadStart:this.#m.bind(this),onLoaded:this.#S.bind(this),onLoadError:this.#w.bind(this)};let e=await async function(t,e={}){if((0,o.HD)(t)){e.onLoadStart?.();try{if(await(0,s.ve)(t),!(0,o.mf)(window.Hls))throw Error("");const i=window.Hls;return e.onLoaded?.(i),i}catch(t){e.onLoadError?.(t)}}}(this.#x,t);if((0,o.o8)(e)&&!(0,o.HD)(this.#x)&&(e=await async function(t,e={}){if(!(0,o.o8)(t)){if(e.onLoadStart?.(),t.prototype&&t.prototype!==Function)return e.onLoaded?.(t),t;try{const i=(await t())?.default;if(!i||!i.isSupported)throw Error("");return e.onLoaded?.(i),i}catch(t){e.onLoadError?.(t)}}}(this.#x,t)),!e)return null;if(!e.isSupported()){const t="[vidstack] `hls.js` is not supported in this environment";return this.#e.player.dispatch(new o.yb("hls-unsupported")),this.#e.notify("error",{message:t,code:4}),null}return e}#m(){this.#e.player.dispatch(new o.yb("hls-lib-load-start"))}#S(t){this.#e.player.dispatch(new o.yb("hls-lib-loaded",{detail:t})),this.#k(t)}#w(t){const e=(0,l.D)(t);this.#e.player.dispatch(new o.yb("hls-lib-load-error",{detail:e})),this.#e.notify("error",{message:e.message,code:4,error:e})}}class v extends r.VideoProvider{$$PROVIDER_TYPE="HLS";#L=null;#T=new u(this.video,this.ctx);get ctor(){return this.#L}get instance(){return this.#T.instance}static supported=(0,n.tF)();get type(){return"hls"}get canLiveSync(){return!0}#P="https://cdn.jsdelivr.net/npm/hls.js@^1.5.0/dist/hls.min.js";get config(){return this.#T.config}set config(t){this.#T.config=t}get library(){return this.#P}set library(t){this.#P=t}preconnect(){(0,o.HD)(this.#P)&&(0,s.$P)(this.#P)}setup(){super.setup(),new p(this.#P,this.ctx,(t=>{this.#L=t,this.#T.setup(t),this.ctx.notify("provider-setup",this);const e=(0,o.fj)(this.ctx.$state.source);e&&this.loadSource(e)}))}async loadSource(t,e){(0,o.HD)(t.src)?(this.media.preload=e||"",this.appendSource(t,"application/x-mpegurl"),this.#T.loadSource(t),this.currentSrc=t):this.removeSource()}onInstance(t){const e=this.#T.instance;return e&&t(e),this.#T.onInstance(t)}destroy(){this.#T.destroy()}}},167:(t,e,i)=>{i.r(e),i.d(e,{VideoProvider:()=>p});var s=i(895),n=i(510),r=i(449),o=i(639),a=i(971);class c{#t;#e;constructor(t,e){this.#t=t,this.#e=e,t.textTracks.onaddtrack=this.#D.bind(this),(0,s.uG)(this.#A.bind(this))}#D(t){const e=t.track;if(!e||function(t,e){return Array.from(t.children).find((t=>t.track===e))}(this.#t,e))return;const i=new a.n8({id:e.id,kind:e.kind,label:e.label??"",language:e.language,type:"vtt"});i[a.kA.native]={track:e},i[a.kA.readyState]=2,i[a.kA.nativeHLS]=!0;let s=0;const n=t=>{if(e.cues)for(let n=s;n<e.cues.length;n++)i.addCue(e.cues[n],t),s++};n(t),e.oncuechange=n,this.#e.textTracks.add(i,t),i.setMode(e.mode,t)}#A(){this.#t.textTracks.onaddtrack=null;for(const t of this.#e.textTracks){const e=t[a.kA.native]?.track;e?.oncuechange&&(e.oncuechange=null)}}}class h{#t;#C;constructor(t,e){this.#t=t,this.#C=e,new s.Xs(t).add("enterpictureinpicture",this.#M.bind(this)).add("leavepictureinpicture",this.#I.bind(this))}get active(){return document.pictureInPictureElement===this.#t}get supported(){return(0,n.zG)(this.#t)}async enter(){return this.#t.requestPictureInPicture()}exit(){return document.exitPictureInPicture()}#M(t){this.#H(!0,t)}#I(t){this.#H(!1,t)}#H=(t,e)=>{this.#C.notify("picture-in-picture-change",t,e)}}class d{#t;#C;#O="inline";get mode(){return this.#O}constructor(t,e){this.#t=t,this.#C=e,(0,s.yl)(t,"webkitpresentationmodechanged",this.#$.bind(this))}get supported(){return(0,n.bv)(this.#t)}async setPresentationMode(t){this.#O!==t&&this.#t.webkitSetPresentationMode(t)}#$(t){const e=this.#O;this.#O=this.#t.webkitPresentationMode,this.#C.player?.dispatch(new s.yb("video-presentation-change",{detail:this.#O,trigger:t})),["fullscreen","picture-in-picture"].forEach((i=>{this.#O!==i&&e!==i||this.#C.notify(`${i}-change`,this.#O===i,t)}))}}class l{#_;get active(){return"fullscreen"===this.#_.mode}get supported(){return this.#_.supported}constructor(t){this.#_=t}async enter(){this.#_.setPresentationMode("fullscreen")}async exit(){this.#_.setPresentationMode("inline")}}class u{#_;get active(){return"picture-in-picture"===this.#_.mode}get supported(){return this.#_.supported}constructor(t){this.#_=t}async enter(){this.#_.setPresentationMode("picture-in-picture")}async exit(){this.#_.setPresentationMode("inline")}}class p extends r.F{$$PROVIDER_TYPE="VIDEO";get type(){return"video"}airPlay;fullscreen;pictureInPicture;constructor(t,e){super(t,e),(0,s.Yn)((()=>{if(this.airPlay=new o.f(t,e),(0,n.bv)(t)){const i=new d(t,e);this.fullscreen=new l(i),this.pictureInPicture=new u(i)}else(0,n.zG)(t)&&(this.pictureInPicture=new h(t,e))}),this.scope)}setup(){super.setup(),(0,n.O6)(this.video)&&new c(this.video,this.ctx),this.ctx.textRenderers.attachVideo(this.video),(0,s.uG)((()=>{this.ctx.textRenderers.attachVideo(null)})),"video"===this.type&&this.ctx.notify("provider-setup",this)}get video(){return this.media}}}}]);
//# sourceMappingURL=480.js.map