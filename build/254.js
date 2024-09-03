"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[254],{265:(t,e,s)=>{s.d(e,{e:()=>i});var a=s(873);class i{#t;#e;constructor(t){this.#e=t}start(){(0,a.b0)(this.#t)&&this.#s()}stop(){(0,a.Et)(this.#t)&&window.cancelAnimationFrame(this.#t),this.#t=void 0}#s(){this.#t=window.requestAnimationFrame((()=>{(0,a.b0)(this.#t)||(this.#e(),this.#s())}))}}},635:(t,e,s)=>{s.r(e),s.d(e,{GoogleCastProvider:()=>u});var a=s(873),i=s(589),n=s(265),r=s(636),o=s(19);class c{#a;constructor(t){this.#a=new chrome.cast.media.MediaInfo(t.src,t.type)}build(){return this.#a}setStreamType(t){return t.includes("live")?this.#a.streamType=chrome.cast.media.StreamType.LIVE:this.#a.streamType=chrome.cast.media.StreamType.BUFFERED,this}setTracks(t){return this.#a.tracks=t.map(this.#i),this}setMetadata(t,e){return this.#a.metadata=new chrome.cast.media.GenericMediaMetadata,this.#a.metadata.title=t,this.#a.metadata.images=[{url:e}],this}#i(t,e){const s=new chrome.cast.media.Track(e,chrome.cast.media.TrackType.TEXT);return s.name=t.label,s.trackContentId=t.src,s.trackContentType="text/vtt",s.language=t.language,s.subtype=t.kind.toUpperCase(),s}}const h=chrome.cast.media.TrackType.TEXT,l=chrome.cast.media.TrackType.AUDIO;class d{#n;#r;#o;constructor(t,e,s){this.#n=t,this.#r=e,this.#o=s}setup(){const t=this.syncRemoteActiveIds.bind(this);(0,a.k6)(this.#r.audioTracks,"change",t),(0,a.k6)(this.#r.textTracks,"mode-change",t),(0,a.QZ)(this.#c.bind(this))}getLocalTextTracks(){return this.#r.$state.textTracks().filter((t=>t.src&&"vtt"===t.type))}#h(){return this.#r.$state.audioTracks()}#l(t){const e=this.#n.mediaInfo?.tracks??[];return t?e.filter((e=>e.type===t)):e}#d(){const t=[],e=this.#h().find((t=>t.selected)),s=this.getLocalTextTracks().filter((t=>"showing"===t.mode));if(e){const s=this.#l(l),a=this.#u(s,e);a&&t.push(a.trackId)}if(s?.length){const e=this.#l(h);if(e.length)for(const a of s){const s=this.#u(e,a);s&&t.push(s.trackId)}}return t}#c(){const t=this.getLocalTextTracks();if(!this.#n.isMediaLoaded)return;const e=this.#l(h);for(const s of t)if(!this.#u(e,s)){(0,a.vz)((()=>this.#o?.()));break}}syncRemoteTracks(t){if(!this.#n.isMediaLoaded)return;const e=this.#h(),s=this.getLocalTextTracks(),a=this.#l(l),i=this.#l(h);for(const s of a){if(this.#m(e,s))continue;const a={id:s.trackId.toString(),label:s.name,language:s.language,kind:s.subtype??"main",selected:!1};this.#r.audioTracks[r.j.add](a,t)}for(const e of i){if(this.#m(s,e))continue;const a={id:e.trackId.toString(),src:e.trackContentId,label:e.name,language:e.language,kind:e.subtype.toLowerCase()};this.#r.textTracks.add(a,t)}}syncRemoteActiveIds(t){if(!this.#n.isMediaLoaded)return;const e=this.#d(),s=new chrome.cast.media.EditTracksInfoRequest(e);this.#y(s).catch((t=>{}))}#y(t){const e=(0,o.Cs)();return new Promise(((s,a)=>e?.editTracksInfo(t,s,a)))}#m(t,e){return t.find((t=>this.#p(t,e)))}#u(t,e){return t.find((t=>this.#p(e,t)))}#p(t,e){return e.name===t.label&&e.language===t.language&&e.subtype.toLowerCase()===t.kind.toLowerCase()}}class u{$$PROVIDER_TYPE="GOOGLE_CAST";scope=(0,a.Ds)();#g;#r;#k;#T=null;#f="disconnected";#C=0;#v=0;#E=new i.zJ(0,0);#S=new n.e(this.#L.bind(this));#x;#I=null;#b=!1;constructor(t,e){this.#g=t,this.#r=e,this.#k=new d(t,e,this.#o.bind(this))}get type(){return"google-cast"}get currentSrc(){return this.#T}get player(){return this.#g}get cast(){return(0,o.vY)()}get session(){return(0,o.aQ)()}get media(){return(0,o.Cs)()}get hasActiveSession(){return(0,o.Qg)(this.#T)}setup(){this.#R(),this.#A(),this.#k.setup(),this.#r.notify("provider-setup",this)}#R(){(0,o.It)(cast.framework.CastContextEventType.CAST_STATE_CHANGED,this.#w.bind(this))}#A(){const t=cast.framework.RemotePlayerEventType,e={[t.IS_CONNECTED_CHANGED]:this.#w,[t.IS_MEDIA_LOADED_CHANGED]:this.#M,[t.CAN_CONTROL_VOLUME_CHANGED]:this.#P,[t.CAN_SEEK_CHANGED]:this.#D,[t.DURATION_CHANGED]:this.#N,[t.IS_MUTED_CHANGED]:this.#_,[t.VOLUME_LEVEL_CHANGED]:this.#_,[t.IS_PAUSED_CHANGED]:this.#G,[t.LIVE_SEEKABLE_RANGE_CHANGED]:this.#V,[t.PLAYER_STATE_CHANGED]:this.#O};this.#x=e;const s=this.#$.bind(this);for(const t of(0,a.YD)(e))this.#g.controller.addEventListener(t,s);(0,a.zp)((()=>{for(const t of(0,a.YD)(e))this.#g.controller.removeEventListener(t,s)}))}async play(){(this.#g.isPaused||this.#b)&&(this.#b?await this.#F(!1,0):this.#g.controller?.playOrPause())}async pause(){this.#g.isPaused||this.#g.controller?.playOrPause()}getMediaStatus(t){return new Promise(((e,s)=>{this.media?.getStatus(t,e,s)}))}setMuted(t){(t&&!this.#g.isMuted||!t&&this.#g.isMuted)&&this.#g.controller?.muteOrUnmute()}setCurrentTime(t){this.#g.currentTime=t,this.#r.notify("seeking",t),this.#g.controller?.seek()}setVolume(t){this.#g.volumeLevel=t,this.#g.controller?.setVolumeLevel()}async loadSource(t){if(this.#I?.src!==t&&(this.#I=null),(0,o.Qg)(t))return this.#H(),void(this.#T=t);this.#r.notify("load-start");const e=this.#z(t),s=await this.session.loadMedia(e);if(s)return this.#T=null,void this.#r.notify("error",Error((0,o.tM)(s)));this.#T=t}destroy(){this.#U(),this.#J()}#U(){this.#I||(this.#v=0,this.#E=new i.zJ(0,0)),this.#S.stop(),this.#C=0,this.#I=null}#H(){const t=new a.vP("resume-session",{detail:this.session});this.#M(t);const{muted:e,volume:s,savedState:i}=this.#r.$state,n=i();this.setCurrentTime(Math.max(this.#g.currentTime,n?.currentTime??0)),this.setMuted(e()),this.setVolume(s()),!1===n?.paused&&this.play()}#J(){this.cast.endCurrentSession(!0);const{remotePlaybackLoader:t}=this.#r.$state;t.set(null)}#Y(){const{savedState:t}=this.#r.$state;t.set({paused:this.#g.isPaused,currentTime:this.#g.currentTime}),this.#J()}#L(){this.#q()}#$(t){this.#x[t.type].call(this,t)}#w(t){const e=this.cast.getCastState(),s=e===cast.framework.CastState.CONNECTED?"connected":e===cast.framework.CastState.CONNECTING?"connecting":"disconnected";if(this.#f===s)return;const a={type:"google-cast",state:s},i=this.#Q(t);this.#f=s,this.#r.notify("remote-playback-change",a,i),"disconnected"===s&&this.#Y()}#M(t){if(!this.#g.isMediaLoaded)return;const e=(0,a.se)(this.#r.$state.source);Promise.resolve().then((()=>{if(e!==(0,a.se)(this.#r.$state.source)||!this.#g.isMediaLoaded)return;this.#U();const s=this.#g.duration;this.#E=new i.zJ(0,s);const n={provider:this,duration:s,buffered:new i.zJ(0,0),seekable:this.#B()},r=this.#Q(t);this.#r.notify("loaded-metadata",void 0,r),this.#r.notify("loaded-data",void 0,r),this.#r.notify("can-play",n,r),this.#P(),this.#D(t);const{volume:o,muted:c}=this.#r.$state;this.setVolume(o()),this.setMuted(c()),this.#S.start(),this.#k.syncRemoteTracks(r),this.#k.syncRemoteActiveIds(r)}))}#P(){this.#r.$state.canSetVolume.set(this.#g.canControlVolume)}#D(t){const e=this.#Q(t);this.#r.notify("stream-type-change",this.#K(),e)}#K(){const t=this.#g.mediaInfo?.streamType;return t===chrome.cast.media.StreamType.LIVE?this.#g.canSeek?"live:dvr":"live":"on-demand"}#q(){if(this.#I)return;const t=this.#g.currentTime;t!==this.#C&&(this.#r.notify("time-change",t),t>this.#v&&(this.#v=t,this.#V()),this.#r.$state.seeking()&&this.#r.notify("seeked",t),this.#C=t)}#N(t){if(!this.#g.isMediaLoaded||this.#I)return;const e=this.#g.duration,s=this.#Q(t);this.#E=new i.zJ(0,e),this.#r.notify("duration-change",e,s)}#_(t){if(!this.#g.isMediaLoaded)return;const e={muted:this.#g.isMuted,volume:this.#g.volumeLevel},s=this.#Q(t);this.#r.notify("volume-change",e,s)}#G(t){const e=this.#Q(t);this.#g.isPaused?this.#r.notify("pause",void 0,e):this.#r.notify("play",void 0,e)}#V(t){const e={seekable:this.#B(),buffered:new i.zJ(0,this.#v)},s=t?this.#Q(t):void 0;this.#r.notify("progress",e,s)}#O(t){const e=this.#g.playerState,s=chrome.cast.media.PlayerState;if(this.#b=e===s.IDLE,e===s.PAUSED)return;const a=this.#Q(t);switch(e){case s.PLAYING:this.#r.notify("playing",void 0,a);break;case s.BUFFERING:this.#r.notify("waiting",void 0,a);break;case s.IDLE:this.#S.stop(),this.#r.notify("pause"),this.#r.notify("end")}}#B(){return this.#g.liveSeekableRange?new i.zJ(this.#g.liveSeekableRange.start,this.#g.liveSeekableRange.end):this.#E}#Q(t){return t instanceof Event?t:new a.vP(t.type,{detail:t})}#X(t){const{streamType:e,title:s,poster:a}=this.#r.$state;return new c(t).setMetadata(s(),a()).setStreamType(e()).setTracks(this.#k.getLocalTextTracks()).build()}#z(t){const e=this.#X(t),s=new chrome.cast.media.LoadRequest(e),a=this.#r.$state.savedState();return s.autoplay=!1===(this.#I?.paused??a?.paused),s.currentTime=this.#I?.time??a?.currentTime??0,s}async#F(t,e){const s=(0,a.se)(this.#r.$state.source);this.#I={src:s,paused:t,time:e},await this.loadSource(s)}#o(){this.#F(this.#g.isPaused,this.#g.currentTime).catch((t=>{}))}}}}]);