"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[299],{982:(t,e,s)=>{s.d(e,{L:()=>i});var a=s(895);class i{#t;#e;constructor(t){this.#e=t}start(){(0,a.o8)(this.#t)&&this.#s()}stop(){(0,a.hj)(this.#t)&&window.cancelAnimationFrame(this.#t),this.#t=void 0}#s(){this.#t=window.requestAnimationFrame((()=>{(0,a.o8)(this.#t)||(this.#e(),this.#s())}))}}},299:(t,e,s)=>{s.r(e),s.d(e,{GoogleCastProvider:()=>u});var a=s(895),i=s(481),n=s(982),r=s(91),o=s(853);class c{#a;constructor(t){this.#a=new chrome.cast.media.MediaInfo(t.src,t.type)}build(){return this.#a}setStreamType(t){return t.includes("live")?this.#a.streamType=chrome.cast.media.StreamType.LIVE:this.#a.streamType=chrome.cast.media.StreamType.BUFFERED,this}setTracks(t){return this.#a.tracks=t.map(this.#i),this}setMetadata(t,e){return this.#a.metadata=new chrome.cast.media.GenericMediaMetadata,this.#a.metadata.title=t,this.#a.metadata.images=[{url:e}],this}#i(t,e){const s=new chrome.cast.media.Track(e,chrome.cast.media.TrackType.TEXT);return s.name=t.label,s.trackContentId=t.src,s.trackContentType="text/vtt",s.language=t.language,s.subtype=t.kind.toUpperCase(),s}}const h=chrome.cast.media.TrackType.TEXT,l=chrome.cast.media.TrackType.AUDIO;class d{#n;#r;#o;constructor(t,e,s){this.#n=t,this.#r=e,this.#o=s}setup(){const t=this.syncRemoteActiveIds.bind(this);(0,a.yl)(this.#r.audioTracks,"change",t),(0,a.yl)(this.#r.textTracks,"mode-change",t),(0,a.cE)(this.#c.bind(this))}getLocalTextTracks(){return this.#r.$state.textTracks().filter((t=>t.src&&"vtt"===t.type))}#h(){return this.#r.$state.audioTracks()}#l(t){const e=this.#n.mediaInfo?.tracks??[];return t?e.filter((e=>e.type===t)):e}#d(){const t=[],e=this.#h().find((t=>t.selected)),s=this.getLocalTextTracks().filter((t=>"showing"===t.mode));if(e){const s=this.#l(l),a=this.#u(s,e);a&&t.push(a.trackId)}if(s?.length){const e=this.#l(h);if(e.length)for(const a of s){const s=this.#u(e,a);s&&t.push(s.trackId)}}return t}#c(){const t=this.getLocalTextTracks();if(!this.#n.isMediaLoaded)return;const e=this.#l(h);for(const s of t)if(!this.#u(e,s)){(0,a.Zw)((()=>this.#o?.()));break}}syncRemoteTracks(t){if(!this.#n.isMediaLoaded)return;const e=this.#h(),s=this.getLocalTextTracks(),a=this.#l(l),i=this.#l(h);for(const s of a){if(this.#m(e,s))continue;const a={id:s.trackId.toString(),label:s.name,language:s.language,kind:s.subtype??"main",selected:!1};this.#r.audioTracks[r.H.add](a,t)}for(const e of i){if(this.#m(s,e))continue;const a={id:e.trackId.toString(),src:e.trackContentId,label:e.name,language:e.language,kind:e.subtype.toLowerCase()};this.#r.textTracks.add(a,t)}}syncRemoteActiveIds(t){if(!this.#n.isMediaLoaded)return;const e=this.#d(),s=new chrome.cast.media.EditTracksInfoRequest(e);this.#y(s).catch((t=>{}))}#y(t){const e=(0,o.Zl)();return new Promise(((s,a)=>e?.editTracksInfo(t,s,a)))}#m(t,e){return t.find((t=>this.#p(t,e)))}#u(t,e){return t.find((t=>this.#p(e,t)))}#p(t,e){return e.name===t.label&&e.language===t.language&&e.subtype.toLowerCase()===t.kind.toLowerCase()}}class u{$$PROVIDER_TYPE="GOOGLE_CAST";scope=(0,a.tp)();#g;#r;#f;#k=null;#T="disconnected";#C=0;#E=0;#v=new i.tn(0,0);#S=new n.L(this.#L.bind(this));#x;#I=null;#b=!1;constructor(t,e){this.#g=t,this.#r=e,this.#f=new d(t,e,this.#o.bind(this))}get type(){return"google-cast"}get currentSrc(){return this.#k}get player(){return this.#g}get cast(){return(0,o.N9)()}get session(){return(0,o.Aj)()}get media(){return(0,o.Zl)()}get hasActiveSession(){return(0,o.KH)(this.#k)}setup(){this.#R(),this.#A(),this.#f.setup(),this.#r.notify("provider-setup",this)}#R(){(0,o.XU)(cast.framework.CastContextEventType.CAST_STATE_CHANGED,this.#w.bind(this))}#A(){const t=cast.framework.RemotePlayerEventType,e={[t.IS_CONNECTED_CHANGED]:this.#w,[t.IS_MEDIA_LOADED_CHANGED]:this.#M,[t.CAN_CONTROL_VOLUME_CHANGED]:this.#N,[t.CAN_SEEK_CHANGED]:this.#P,[t.DURATION_CHANGED]:this.#_,[t.IS_MUTED_CHANGED]:this.#D,[t.VOLUME_LEVEL_CHANGED]:this.#D,[t.IS_PAUSED_CHANGED]:this.#G,[t.LIVE_SEEKABLE_RANGE_CHANGED]:this.#V,[t.PLAYER_STATE_CHANGED]:this.#H};this.#x=e;const s=this.#O.bind(this);for(const t of(0,a.uc)(e))this.#g.controller.addEventListener(t,s);(0,a.uG)((()=>{for(const t of(0,a.uc)(e))this.#g.controller.removeEventListener(t,s)}))}async play(){(this.#g.isPaused||this.#b)&&(this.#b?await this.#F(!1,0):this.#g.controller?.playOrPause())}async pause(){this.#g.isPaused||this.#g.controller?.playOrPause()}getMediaStatus(t){return new Promise(((e,s)=>{this.media?.getStatus(t,e,s)}))}setMuted(t){(t&&!this.#g.isMuted||!t&&this.#g.isMuted)&&this.#g.controller?.muteOrUnmute()}setCurrentTime(t){this.#g.currentTime=t,this.#r.notify("seeking",t),this.#g.controller?.seek()}setVolume(t){this.#g.volumeLevel=t,this.#g.controller?.setVolumeLevel()}async loadSource(t){if(this.#I?.src!==t&&(this.#I=null),(0,o.KH)(t))return this.#$(),void(this.#k=t);this.#r.notify("load-start");const e=this.#U(t),s=await this.session.loadMedia(e);if(s)return this.#k=null,void this.#r.notify("error",Error((0,o.VF)(s)));this.#k=t}destroy(){this.#j(),this.#q()}#j(){this.#I||(this.#E=0,this.#v=new i.tn(0,0)),this.#S.stop(),this.#C=0,this.#I=null}#$(){const t=new a.yb("resume-session",{detail:this.session});this.#M(t);const{muted:e,volume:s,savedState:i}=this.#r.$state,n=i();this.setCurrentTime(Math.max(this.#g.currentTime,n?.currentTime??0)),this.setMuted(e()),this.setVolume(s()),!1===n?.paused&&this.play()}#q(){this.cast.endCurrentSession(!0);const{remotePlaybackLoader:t}=this.#r.$state;t.set(null)}#K(){const{savedState:t}=this.#r.$state;t.set({paused:this.#g.isPaused,currentTime:this.#g.currentTime}),this.#q()}#L(){this.#B()}#O(t){this.#x[t.type].call(this,t)}#w(t){const e=this.cast.getCastState(),s=e===cast.framework.CastState.CONNECTED?"connected":e===cast.framework.CastState.CONNECTING?"connecting":"disconnected";if(this.#T===s)return;const a={type:"google-cast",state:s},i=this.#X(t);this.#T=s,this.#r.notify("remote-playback-change",a,i),"disconnected"===s&&this.#K()}#M(t){if(!this.#g.isMediaLoaded)return;const e=(0,a.fj)(this.#r.$state.source);Promise.resolve().then((()=>{if(e!==(0,a.fj)(this.#r.$state.source)||!this.#g.isMediaLoaded)return;this.#j();const s=this.#g.duration;this.#v=new i.tn(0,s);const n={provider:this,duration:s,buffered:new i.tn(0,0),seekable:this.#Y()},r=this.#X(t);this.#r.notify("loaded-metadata",void 0,r),this.#r.notify("loaded-data",void 0,r),this.#r.notify("can-play",n,r),this.#N(),this.#P(t);const{volume:o,muted:c}=this.#r.$state;this.setVolume(o()),this.setMuted(c()),this.#S.start(),this.#f.syncRemoteTracks(r),this.#f.syncRemoteActiveIds(r)}))}#N(){this.#r.$state.canSetVolume.set(this.#g.canControlVolume)}#P(t){const e=this.#X(t);this.#r.notify("stream-type-change",this.#Z(),e)}#Z(){const t=this.#g.mediaInfo?.streamType;return t===chrome.cast.media.StreamType.LIVE?this.#g.canSeek?"live:dvr":"live":"on-demand"}#B(){if(this.#I)return;const t=this.#g.currentTime;t!==this.#C&&(this.#r.notify("time-change",t),t>this.#E&&(this.#E=t,this.#V()),this.#r.$state.seeking()&&this.#r.notify("seeked",t),this.#C=t)}#_(t){if(!this.#g.isMediaLoaded||this.#I)return;const e=this.#g.duration,s=this.#X(t);this.#v=new i.tn(0,e),this.#r.notify("duration-change",e,s)}#D(t){if(!this.#g.isMediaLoaded)return;const e={muted:this.#g.isMuted,volume:this.#g.volumeLevel},s=this.#X(t);this.#r.notify("volume-change",e,s)}#G(t){const e=this.#X(t);this.#g.isPaused?this.#r.notify("pause",void 0,e):this.#r.notify("play",void 0,e)}#V(t){const e={seekable:this.#Y(),buffered:new i.tn(0,this.#E)},s=t?this.#X(t):void 0;this.#r.notify("progress",e,s)}#H(t){const e=this.#g.playerState,s=chrome.cast.media.PlayerState;if(this.#b=e===s.IDLE,e===s.PAUSED)return;const a=this.#X(t);switch(e){case s.PLAYING:this.#r.notify("playing",void 0,a);break;case s.BUFFERING:this.#r.notify("waiting",void 0,a);break;case s.IDLE:this.#S.stop(),this.#r.notify("pause"),this.#r.notify("end")}}#Y(){return this.#g.liveSeekableRange?new i.tn(this.#g.liveSeekableRange.start,this.#g.liveSeekableRange.end):this.#v}#X(t){return t instanceof Event?t:new a.yb(t.type,{detail:t})}#z(t){const{streamType:e,title:s,poster:a}=this.#r.$state;return new c(t).setMetadata(s(),a()).setStreamType(e()).setTracks(this.#f.getLocalTextTracks()).build()}#U(t){const e=this.#z(t),s=new chrome.cast.media.LoadRequest(e),a=this.#r.$state.savedState();return s.autoplay=!1===(this.#I?.paused??a?.paused),s.currentTime=this.#I?.time??a?.currentTime??0,s}async#F(t,e){const s=(0,a.fj)(this.#r.$state.source);this.#I={src:s,paused:t,time:e},await this.loadSource(s)}#o(){this.#F(this.#g.isPaused,this.#g.currentTime).catch((t=>{}))}}}}]);