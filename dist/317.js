"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[317,903],{317:(t,e,i)=>{i.r(e),i.d(e,{HLSProvider:()=>v});var s=i(350),n=i(934),r=i(903),o=i(873),a=i(908),c=i(445),h=i(636),d=i(265),l=i(760);class u{#t;#e;#i=null;#s=null;config={};#n=new Set;get instance(){return this.#i}constructor(t,e){this.#t=t,this.#e=e}setup(t){const{streamType:e}=this.#e.$state,i=(0,o.se)(e).includes("live"),s=(0,o.se)(e).includes("ll-");this.#i=new t({lowLatencyMode:s,backBufferLength:s?4:i?8:void 0,renderTextTracksNatively:!1,...this.config});const n=this.#r.bind(this);for(const e of Object.values(t.Events))this.#i.on(e,n);this.#i.on(t.Events.ERROR,this.#o.bind(this));for(const t of this.#n)t(this.#i);this.#e.player.dispatch("hls-instance",{detail:this.#i}),this.#i.attachMedia(this.#t),this.#i.on(t.Events.AUDIO_TRACK_SWITCHED,this.#a.bind(this)),this.#i.on(t.Events.LEVEL_SWITCHED,this.#c.bind(this)),this.#i.on(t.Events.LEVEL_LOADED,this.#h.bind(this)),this.#i.on(t.Events.NON_NATIVE_TEXT_TRACKS_FOUND,this.#d.bind(this)),this.#i.on(t.Events.CUES_PARSED,this.#l.bind(this)),this.#e.qualities[a.k.enableAuto]=this.#u.bind(this),(0,o.k6)(this.#e.qualities,"change",this.#p.bind(this)),(0,o.k6)(this.#e.audioTracks,"change",this.#v.bind(this)),this.#s=(0,o.QZ)(this.#g.bind(this))}#y(t,e){return new o.vP((t=>(0,o.BW)(t))(t),{detail:e})}#g(){if(!this.#e.$state.live())return;const t=new d.e(this.#b.bind(this));return t.start(),t.stop.bind(t)}#b(){this.#e.$state.liveSyncPosition.set(this.#i?.liveSyncPosition??1/0)}#r(t,e){this.#e.player?.dispatch(this.#y(t,e))}#d(t,e){const i=this.#y(t,e);let s=-1;for(let t=0;t<e.tracks.length;t++){const n=e.tracks[t],r=n.subtitleTrack??n.closedCaptions,o=new c.to({id:`hls-${n.kind}-${t}`,src:r?.url,label:n.label,language:r?.lang,kind:n.kind,default:n.default});o[c.Hp.readyState]=2,o[c.Hp.onModeChange]=()=>{"showing"===o.mode?(this.#i.subtitleTrack=t,s=t):s===t&&(this.#i.subtitleTrack=-1,s=-1)},this.#e.textTracks.add(o,i)}}#l(t,e){const i=this.#i?.subtitleTrack,s=this.#e.textTracks.getById(`hls-${e.type}-${i}`);if(!s)return;const n=this.#y(t,e);for(const t of e.cues)t.positionAlign="auto",s.addCue(t,n)}#a(t,e){const i=this.#e.audioTracks[e.id];if(i){const s=this.#y(t,e);this.#e.audioTracks[h.j.select](i,!0,s)}}#c(t,e){const i=this.#e.qualities[e.level];if(i){const s=this.#y(t,e);this.#e.qualities[h.j.select](i,!0,s)}}#h(t,e){if(this.#e.$state.canPlay())return;const{type:i,live:s,totalduration:n,targetduration:r}=e.details,c=this.#y(t,e);this.#e.notify("stream-type-change",s?"EVENT"===i&&Number.isFinite(n)&&r>=10?"live:dvr":"live":"on-demand",c),this.#e.notify("duration-change",n,c);const d=this.#i.media;-1===this.#i.currentLevel&&this.#e.qualities[a.k.setAuto](!0,c);for(const t of this.#i.audioTracks){const e={id:t.id.toString(),label:t.name,language:t.lang||"",kind:"main"};this.#e.audioTracks[h.j.add](e,c)}for(const t of this.#i.levels){const e={id:t.id?.toString()??t.height+"p",width:t.width,height:t.height,codec:t.codecSet,bitrate:t.bitrate};this.#e.qualities[h.j.add](e,c)}d.dispatchEvent(new o.vP("canplay",{trigger:c}))}#o(t,e){e.fatal&&("mediaError"===e.type?this.#i?.recoverMediaError():this.#x(e.error))}#x(t){this.#e.notify("error",{message:t.message,code:1,error:t})}#u(){this.#i&&(this.#i.currentLevel=-1)}#p(){const{qualities:t}=this.#e;this.#i&&!t.auto&&(this.#i[t.switch+"Level"]=t.selectedIndex,n.G_&&(this.#t.currentTime=this.#t.currentTime))}#v(){const{audioTracks:t}=this.#e;this.#i&&this.#i.audioTrack!==t.selectedIndex&&(this.#i.audioTrack=t.selectedIndex)}onInstance(t){return this.#n.add(t),()=>this.#n.delete(t)}loadSource(t){(0,o.Kg)(t.src)&&this.#i?.loadSource(t.src)}destroy(){this.#i?.destroy(),this.#i=null,this.#s?.(),this.#s=null}}class p{#f;#e;#k;constructor(t,e,i){this.#f=t,this.#e=e,this.#k=i,this.#E()}async#E(){const t={onLoadStart:this.#m.bind(this),onLoaded:this.#S.bind(this),onLoadError:this.#w.bind(this)};let e=await async function(t,e={}){if((0,o.Kg)(t)){e.onLoadStart?.();try{if(await(0,s.k0)(t),!(0,o.Tn)(window.Hls))throw Error("");const i=window.Hls;return e.onLoaded?.(i),i}catch(t){e.onLoadError?.(t)}}}(this.#f,t);if((0,o.b0)(e)&&!(0,o.Kg)(this.#f)&&(e=await async function(t,e={}){if(!(0,o.b0)(t)){if(e.onLoadStart?.(),t.prototype&&t.prototype!==Function)return e.onLoaded?.(t),t;try{const i=(await t())?.default;if(!i||!i.isSupported)throw Error("");return e.onLoaded?.(i),i}catch(t){e.onLoadError?.(t)}}}(this.#f,t)),!e)return null;if(!e.isSupported()){const t="[vidstack] `hls.js` is not supported in this environment";return this.#e.player.dispatch(new o.vP("hls-unsupported")),this.#e.notify("error",{message:t,code:4}),null}return e}#m(){this.#e.player.dispatch(new o.vP("hls-lib-load-start"))}#S(t){this.#e.player.dispatch(new o.vP("hls-lib-loaded",{detail:t})),this.#k(t)}#w(t){const e=(0,l.r)(t);this.#e.player.dispatch(new o.vP("hls-lib-load-error",{detail:e})),this.#e.notify("error",{message:e.message,code:4,error:e})}}class v extends r.VideoProvider{$$PROVIDER_TYPE="HLS";#L=null;#P=new u(this.video,this.ctx);get ctor(){return this.#L}get instance(){return this.#P.instance}static supported=(0,n.m0)();get type(){return"hls"}get canLiveSync(){return!0}#T="https://cdn.jsdelivr.net/npm/hls.js@^1.5.0/dist/hls.min.js";get config(){return this.#P.config}set config(t){this.#P.config=t}get library(){return this.#T}set library(t){this.#T=t}preconnect(){(0,o.Kg)(this.#T)&&(0,s.kZ)(this.#T)}setup(){super.setup(),new p(this.#T,this.ctx,(t=>{this.#L=t,this.#P.setup(t),this.ctx.notify("provider-setup",this);const e=(0,o.se)(this.ctx.$state.source);e&&this.loadSource(e)}))}async loadSource(t,e){(0,o.Kg)(t.src)?(this.media.preload=e||"",this.appendSource(t,"application/x-mpegurl"),this.#P.loadSource(t),this.currentSrc=t):this.removeSource()}onInstance(t){const e=this.#P.instance;return e&&t(e),this.#P.onInstance(t)}destroy(){this.#P.destroy()}}},903:(t,e,i)=>{i.r(e),i.d(e,{VideoProvider:()=>p});var s=i(873),n=i(934),r=i(491),o=i(387),a=i(445);class c{#t;#e;constructor(t,e){this.#t=t,this.#e=e,t.textTracks.onaddtrack=this.#C.bind(this),(0,s.zp)(this.#M.bind(this))}#C(t){const e=t.track;if(!e||function(t,e){return Array.from(t.children).find((t=>t.track===e))}(this.#t,e))return;const i=new a.to({id:e.id,kind:e.kind,label:e.label??"",language:e.language,type:"vtt"});i[a.Hp.native]={track:e},i[a.Hp.readyState]=2,i[a.Hp.nativeHLS]=!0;let s=0;const n=t=>{if(e.cues)for(let n=s;n<e.cues.length;n++)i.addCue(e.cues[n],t),s++};n(t),e.oncuechange=n,this.#e.textTracks.add(i,t),i.setMode(e.mode,t)}#M(){this.#t.textTracks.onaddtrack=null;for(const t of this.#e.textTracks){const e=t[a.Hp.native]?.track;e?.oncuechange&&(e.oncuechange=null)}}}class h{#t;#I;constructor(t,e){this.#t=t,this.#I=e,new s.En(t).add("enterpictureinpicture",this.#D.bind(this)).add("leavepictureinpicture",this.#A.bind(this))}get active(){return document.pictureInPictureElement===this.#t}get supported(){return(0,n.dE)(this.#t)}async enter(){return this.#t.requestPictureInPicture()}exit(){return document.exitPictureInPicture()}#D(t){this.#O(!0,t)}#A(t){this.#O(!1,t)}#O=(t,e)=>{this.#I.notify("picture-in-picture-change",t,e)}}class d{#t;#I;#H="inline";get mode(){return this.#H}constructor(t,e){this.#t=t,this.#I=e,(0,s.k6)(t,"webkitpresentationmodechanged",this.#$.bind(this))}get supported(){return(0,n.Jt)(this.#t)}async setPresentationMode(t){this.#H!==t&&this.#t.webkitSetPresentationMode(t)}#$(t){const e=this.#H;this.#H=this.#t.webkitPresentationMode,this.#I.player?.dispatch(new s.vP("video-presentation-change",{detail:this.#H,trigger:t})),["fullscreen","picture-in-picture"].forEach((i=>{this.#H!==i&&e!==i||this.#I.notify(`${i}-change`,this.#H===i,t)}))}}class l{#_;get active(){return"fullscreen"===this.#_.mode}get supported(){return this.#_.supported}constructor(t){this.#_=t}async enter(){this.#_.setPresentationMode("fullscreen")}async exit(){this.#_.setPresentationMode("inline")}}class u{#_;get active(){return"picture-in-picture"===this.#_.mode}get supported(){return this.#_.supported}constructor(t){this.#_=t}async enter(){this.#_.setPresentationMode("picture-in-picture")}async exit(){this.#_.setPresentationMode("inline")}}class p extends r.B{$$PROVIDER_TYPE="VIDEO";get type(){return"video"}airPlay;fullscreen;pictureInPicture;constructor(t,e){super(t,e),(0,s.P1)((()=>{if(this.airPlay=new o.z(t,e),(0,n.Jt)(t)){const i=new d(t,e);this.fullscreen=new l(i),this.pictureInPicture=new u(i)}else(0,n.dE)(t)&&(this.pictureInPicture=new h(t,e))}),this.scope)}setup(){super.setup(),(0,n.uo)(this.video)&&new c(this.video,this.ctx),this.ctx.textRenderers.attachVideo(this.video),(0,s.zp)((()=>{this.ctx.textRenderers.attachVideo(null)})),"video"===this.type&&this.ctx.notify("provider-setup",this)}get video(){return this.media}}}}]);