"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[434,903],{434:(t,e,i)=>{i.r(e),i.d(e,{DASHProvider:()=>v});var n=i(350),s=i(934),r=i(903),a=i(873),o=i(908),c=i(445),d=i(636),h=i(265),l=i(760);function u(t){try{return new Intl.DisplayNames(navigator.languages,{type:"language"}).of(t)??null}catch(t){return null}}class p{#t;#e;#i=null;#n=new Set;#s=null;config={};get instance(){return this.#i}constructor(t,e){this.#t=t,this.#e=e}setup(t){this.#i=t().create();const e=this.#r.bind(this);for(const i of Object.values(t.events))this.#i.on(i,e);this.#i.on(t.events.ERROR,this.#a.bind(this));for(const t of this.#n)t(this.#i);this.#e.player.dispatch("dash-instance",{detail:this.#i}),this.#i.initialize(this.#t,void 0,!1),this.#i.updateSettings({streaming:{text:{defaultEnabled:!1,dispatchForManualRendering:!0},buffer:{fastSwitchEnabled:!0}},...this.config}),this.#i.on(t.events.FRAGMENT_LOADING_STARTED,this.#o.bind(this)),this.#i.on(t.events.FRAGMENT_LOADING_COMPLETED,this.#c.bind(this)),this.#i.on(t.events.MANIFEST_LOADED,this.#d.bind(this)),this.#i.on(t.events.QUALITY_CHANGE_RENDERED,this.#h.bind(this)),this.#i.on(t.events.TEXT_TRACKS_ADDED,this.#l.bind(this)),this.#i.on(t.events.TRACK_CHANGE_RENDERED,this.#u.bind(this)),this.#e.qualities[o.k.enableAuto]=this.#p.bind(this),(0,a.k6)(this.#e.qualities,"change",this.#g.bind(this)),(0,a.k6)(this.#e.audioTracks,"change",this.#y.bind(this)),this.#s=(0,a.QZ)(this.#v.bind(this))}#m(t){return new a.vP((e=t.type,`dash-${(0,a.BW)(e)}`),{detail:t});var e}#v(){if(!this.#e.$state.live())return;const t=new h.e(this.#f.bind(this));return t.start(),t.stop.bind(t)}#f(){if(!this.#i)return;const t=this.#i.duration()-this.#i.time();this.#e.$state.liveSyncPosition.set(isNaN(t)?1/0:t)}#r(t){this.#e.player?.dispatch(this.#m(t))}#x=null;#T={};#k(t){const e=this.#x?.[c.Hp.native],i=(e?.track).cues;if(!e||!i)return;const n=this.#x.id,s=this.#T[n]??0,r=this.#m(t);for(let t=s;t<i.length;t++){const e=i[t];e.positionAlign||(e.positionAlign="auto"),this.#x.addCue(e,r)}this.#T[n]=i.length}#l(t){if(!this.#i)return;const e=t.tracks,i=[...this.#t.textTracks].filter((t=>"manualMode"in t)),n=this.#m(t);for(let t=0;t<i.length;t++){const s=e[t],r=i[t],a=`dash-${s.kind}-${t}`,o=new c.to({id:a,label:s?.label??s.labels.find((t=>t.text))?.text??(s?.lang&&u(s.lang))??s?.lang??void 0,language:s.lang??void 0,kind:s.kind,default:s.defaultTrack});o[c.Hp.native]={managed:!0,track:r},o[c.Hp.readyState]=2,o[c.Hp.onModeChange]=()=>{this.#i&&("showing"===o.mode?(this.#i.setTextTrack(t),this.#x=o):(this.#i.setTextTrack(-1),this.#x=null))},this.#e.textTracks.add(o,n)}}#u(t){const{mediaType:e,newMediaInfo:i}=t;if("audio"===e){const e=this.#e.audioTracks.getById(`dash-audio-${i.index}`);if(e){const i=this.#m(t);this.#e.audioTracks[d.j.select](e,!0,i)}}}#h(t){if("video"!==t.mediaType)return;const e=this.#e.qualities[t.newQuality];if(e){const i=this.#m(t);this.#e.qualities[d.j.select](e,!0,i)}}#d(t){if(this.#e.$state.canPlay()||!this.#i)return;const{type:e,mediaPresentationDuration:i}=t.data,n=this.#m(t);this.#e.notify("stream-type-change","static"!==e?"live":"on-demand",n),this.#e.notify("duration-change",i,n),this.#e.qualities[o.k.setAuto](!0,n);const r=this.#i.getVideoElement(),c=this.#i.getTracksForTypeFromManifest("video",t.data),h=[...new Set(c.map((t=>t.mimeType)))].find((t=>t&&(0,s.EI)(r,t))),l=c.filter((t=>h===t.mimeType))[0];let p=this.#i.getTracksForTypeFromManifest("audio",t.data);const g=[...new Set(p.map((t=>t.mimeType)))].find((t=>t&&(0,s.Te)(r,t)));if(p=p.filter((t=>g===t.mimeType)),l.bitrateList.forEach(((t,e)=>{const i={id:t.id?.toString()??`dash-bitrate-${e}`,width:t.width??0,height:t.height??0,bitrate:t.bandwidth??0,codec:l.codec,index:e};this.#e.qualities[d.j.add](i,n)})),(0,a.Et)(l.index)){const t=this.#e.qualities[l.index];t&&this.#e.qualities[d.j.select](t,!0,n)}p.forEach(((t,e)=>{const i=t.labels.find((t=>navigator.languages.some((e=>t.lang&&e.toLowerCase().startsWith(t.lang.toLowerCase())))))||t.labels[0],s={id:`dash-audio-${t?.index}`,label:i?.text??(t.lang&&u(t.lang))??t.lang??"",language:t.lang??"",kind:"main",mimeType:t.mimeType,codec:t.codec,index:e};this.#e.audioTracks[d.j.add](s,n)})),r.dispatchEvent(new a.vP("canplay",{trigger:n}))}#a(t){const{type:e,error:i}=t;27===i.code?this.#b(i):this.#E(i)}#o(){this.#w>=0&&this.#L()}#c(t){"text"===t.mediaType&&requestAnimationFrame(this.#k.bind(this,t))}#w=-1;#b(t){this.#L(),this.#i?.play(),this.#w=window.setTimeout((()=>{this.#w=-1,this.#E(t)}),5e3)}#L(){clearTimeout(this.#w),this.#w=-1}#E(t){this.#e.notify("error",{message:t.message??"",code:1,error:t})}#p(){this.#S("video",!0);const{qualities:t}=this.#e;this.#i?.setQualityFor("video",t.selectedIndex,!0)}#S(t,e){this.#i?.updateSettings({streaming:{abr:{autoSwitchBitrate:{[t]:e}}}})}#g(){const{qualities:t}=this.#e;this.#i&&!t.auto&&t.selected&&(this.#S("video",!1),this.#i.setQualityFor("video",t.selectedIndex,"current"===t.switch),s.G_&&(this.#t.currentTime=this.#t.currentTime))}#y(){if(!this.#i)return;const{audioTracks:t}=this.#e,e=this.#i.getTracksFor("audio").find((e=>t.selected&&t.selected.id===`dash-audio-${e.index}`));e&&this.#i.setCurrentTrack(e)}#P(){this.#L(),this.#x=null,this.#T={}}onInstance(t){return this.#n.add(t),()=>this.#n.delete(t)}loadSource(t){this.#P(),(0,a.Kg)(t.src)&&this.#i?.attachSource(t.src)}destroy(){this.#P(),this.#i?.destroy(),this.#i=null,this.#s?.(),this.#s=null}}class g{#M;#e;#A;constructor(t,e,i){this.#M=t,this.#e=e,this.#A=i,this.#D()}async#D(){const t={onLoadStart:this.#C.bind(this),onLoaded:this.#R.bind(this),onLoadError:this.#F.bind(this)};let e=await async function(t,e={}){if((0,a.Kg)(t)){e.onLoadStart?.();try{if(await(0,n.k0)(t),!(0,a.Tn)(window.dashjs.MediaPlayer))throw Error("");const i=window.dashjs.MediaPlayer;return e.onLoaded?.(i),i}catch(t){e.onLoadError?.(t)}}}(this.#M,t);if((0,a.b0)(e)&&!(0,a.Kg)(this.#M)&&(e=await async function(t,e={}){if(!(0,a.b0)(t)){if(e.onLoadStart?.(),(i=t)&&i.prototype&&i.prototype!==Function)return e.onLoaded?.(t),t;var i;if(y(t)){const i=t.MediaPlayer;return e.onLoaded?.(i),i}try{const i=(await t())?.default;if(y(i))return e.onLoaded?.(i.MediaPlayer),i.MediaPlayer;if(!i)throw Error("");return e.onLoaded?.(i),i}catch(t){e.onLoadError?.(t)}}}(this.#M,t)),!e)return null;if(!window.dashjs.supportsMediaSource()){const t="[vidstack] `dash.js` is not supported in this environment";return this.#e.player.dispatch(new a.vP("dash-unsupported")),this.#e.notify("error",{message:t,code:4}),null}return e}#C(){this.#e.player.dispatch(new a.vP("dash-lib-load-start"))}#R(t){this.#e.player.dispatch(new a.vP("dash-lib-loaded",{detail:t})),this.#A(t)}#F(t){const e=(0,l.r)(t);this.#e.player.dispatch(new a.vP("dash-lib-load-error",{detail:e})),this.#e.notify("error",{message:e.message,code:4,error:e})}}function y(t){return t&&"MediaPlayer"in t}class v extends r.VideoProvider{$$PROVIDER_TYPE="DASH";#I=null;#H=new p(this.video,this.ctx);get ctor(){return this.#I}get instance(){return this.#H.instance}static supported=(0,s.Hh)();get type(){return"dash"}get canLiveSync(){return!0}#O="https://cdn.jsdelivr.net/npm/dashjs@4.7.4/dist/dash.all.min.js";get config(){return this.#H.config}set config(t){this.#H.config=t}get library(){return this.#O}set library(t){this.#O=t}preconnect(){(0,a.Kg)(this.#O)&&(0,n.kZ)(this.#O)}setup(){super.setup(),new g(this.#O,this.ctx,(t=>{this.#I=t,this.#H.setup(t),this.ctx.notify("provider-setup",this);const e=(0,a.se)(this.ctx.$state.source);e&&this.loadSource(e)}))}async loadSource(t,e){(0,a.Kg)(t.src)?(this.media.preload=e||"",this.appendSource(t,"application/x-mpegurl"),this.#H.loadSource(t),this.currentSrc=t):this.removeSource()}onInstance(t){const e=this.#H.instance;return e&&t(e),this.#H.onInstance(t)}destroy(){this.#H.destroy()}}},903:(t,e,i)=>{i.r(e),i.d(e,{VideoProvider:()=>p});var n=i(873),s=i(934),r=i(491),a=i(387),o=i(445);class c{#t;#e;constructor(t,e){this.#t=t,this.#e=e,t.textTracks.onaddtrack=this.#$.bind(this),(0,n.zp)(this.#_.bind(this))}#$(t){const e=t.track;if(!e||function(t,e){return Array.from(t.children).find((t=>t.track===e))}(this.#t,e))return;const i=new o.to({id:e.id,kind:e.kind,label:e.label??"",language:e.language,type:"vtt"});i[o.Hp.native]={track:e},i[o.Hp.readyState]=2,i[o.Hp.nativeHLS]=!0;let n=0;const s=t=>{if(e.cues)for(let s=n;s<e.cues.length;s++)i.addCue(e.cues[s],t),n++};s(t),e.oncuechange=s,this.#e.textTracks.add(i,t),i.setMode(e.mode,t)}#_(){this.#t.textTracks.onaddtrack=null;for(const t of this.#e.textTracks){const e=t[o.Hp.native]?.track;e?.oncuechange&&(e.oncuechange=null)}}}class d{#t;#N;constructor(t,e){this.#t=t,this.#N=e,new n.En(t).add("enterpictureinpicture",this.#j.bind(this)).add("leavepictureinpicture",this.#q.bind(this))}get active(){return document.pictureInPictureElement===this.#t}get supported(){return(0,s.dE)(this.#t)}async enter(){return this.#t.requestPictureInPicture()}exit(){return document.exitPictureInPicture()}#j(t){this.#Q(!0,t)}#q(t){this.#Q(!1,t)}#Q=(t,e)=>{this.#N.notify("picture-in-picture-change",t,e)}}class h{#t;#N;#V="inline";get mode(){return this.#V}constructor(t,e){this.#t=t,this.#N=e,(0,n.k6)(t,"webkitpresentationmodechanged",this.#B.bind(this))}get supported(){return(0,s.Jt)(this.#t)}async setPresentationMode(t){this.#V!==t&&this.#t.webkitSetPresentationMode(t)}#B(t){const e=this.#V;this.#V=this.#t.webkitPresentationMode,this.#N.player?.dispatch(new n.vP("video-presentation-change",{detail:this.#V,trigger:t})),["fullscreen","picture-in-picture"].forEach((i=>{this.#V!==i&&e!==i||this.#N.notify(`${i}-change`,this.#V===i,t)}))}}class l{#G;get active(){return"fullscreen"===this.#G.mode}get supported(){return this.#G.supported}constructor(t){this.#G=t}async enter(){this.#G.setPresentationMode("fullscreen")}async exit(){this.#G.setPresentationMode("inline")}}class u{#G;get active(){return"picture-in-picture"===this.#G.mode}get supported(){return this.#G.supported}constructor(t){this.#G=t}async enter(){this.#G.setPresentationMode("picture-in-picture")}async exit(){this.#G.setPresentationMode("inline")}}class p extends r.B{$$PROVIDER_TYPE="VIDEO";get type(){return"video"}airPlay;fullscreen;pictureInPicture;constructor(t,e){super(t,e),(0,n.P1)((()=>{if(this.airPlay=new a.z(t,e),(0,s.Jt)(t)){const i=new h(t,e);this.fullscreen=new l(i),this.pictureInPicture=new u(i)}else(0,s.dE)(t)&&(this.pictureInPicture=new d(t,e))}),this.scope)}setup(){super.setup(),(0,s.uo)(this.video)&&new c(this.video,this.ctx),this.ctx.textRenderers.attachVideo(this.video),(0,n.zp)((()=>{this.ctx.textRenderers.attachVideo(null)})),"video"===this.type&&this.ctx.notify("provider-setup",this)}get video(){return this.media}}}}]);