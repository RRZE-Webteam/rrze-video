"use strict";(self.webpackChunkrrze_video=self.webpackChunkrrze_video||[]).push([[903],{903:(e,t,i)=>{i.r(t),i.d(t,{VideoProvider:()=>l});var n=i(873),s=i(934),r=i(491),o=i(387),c=i(445);class d{#e;#t;constructor(e,t){this.#e=e,this.#t=t,e.textTracks.onaddtrack=this.#i.bind(this),(0,n.zp)(this.#n.bind(this))}#i(e){const t=e.track;if(!t||function(e,t){return Array.from(e.children).find((e=>e.track===t))}(this.#e,t))return;const i=new c.to({id:t.id,kind:t.kind,label:t.label??"",language:t.language,type:"vtt"});i[c.Hp.native]={track:t},i[c.Hp.readyState]=2,i[c.Hp.nativeHLS]=!0;let n=0;const s=e=>{if(t.cues)for(let s=n;s<t.cues.length;s++)i.addCue(t.cues[s],e),n++};s(e),t.oncuechange=s,this.#t.textTracks.add(i,e),i.setMode(t.mode,e)}#n(){this.#e.textTracks.onaddtrack=null;for(const e of this.#t.textTracks){const t=e[c.Hp.native]?.track;t?.oncuechange&&(t.oncuechange=null)}}}class a{#e;#s;constructor(e,t){this.#e=e,this.#s=t,new n.En(e).add("enterpictureinpicture",this.#r.bind(this)).add("leavepictureinpicture",this.#o.bind(this))}get active(){return document.pictureInPictureElement===this.#e}get supported(){return(0,s.dE)(this.#e)}async enter(){return this.#e.requestPictureInPicture()}exit(){return document.exitPictureInPicture()}#r(e){this.#c(!0,e)}#o(e){this.#c(!1,e)}#c=(e,t)=>{this.#s.notify("picture-in-picture-change",e,t)}}class h{#e;#s;#d="inline";get mode(){return this.#d}constructor(e,t){this.#e=e,this.#s=t,(0,n.k6)(e,"webkitpresentationmodechanged",this.#a.bind(this))}get supported(){return(0,s.Jt)(this.#e)}async setPresentationMode(e){this.#d!==e&&this.#e.webkitSetPresentationMode(e)}#a(e){const t=this.#d;this.#d=this.#e.webkitPresentationMode,this.#s.player?.dispatch(new n.vP("video-presentation-change",{detail:this.#d,trigger:e})),["fullscreen","picture-in-picture"].forEach((i=>{this.#d!==i&&t!==i||this.#s.notify(`${i}-change`,this.#d===i,e)}))}}class u{#h;get active(){return"fullscreen"===this.#h.mode}get supported(){return this.#h.supported}constructor(e){this.#h=e}async enter(){this.#h.setPresentationMode("fullscreen")}async exit(){this.#h.setPresentationMode("inline")}}class p{#h;get active(){return"picture-in-picture"===this.#h.mode}get supported(){return this.#h.supported}constructor(e){this.#h=e}async enter(){this.#h.setPresentationMode("picture-in-picture")}async exit(){this.#h.setPresentationMode("inline")}}class l extends r.B{$$PROVIDER_TYPE="VIDEO";get type(){return"video"}airPlay;fullscreen;pictureInPicture;constructor(e,t){super(e,t),(0,n.P1)((()=>{if(this.airPlay=new o.z(e,t),(0,s.Jt)(e)){const i=new h(e,t);this.fullscreen=new u(i),this.pictureInPicture=new p(i)}else(0,s.dE)(e)&&(this.pictureInPicture=new a(e,t))}),this.scope)}setup(){super.setup(),(0,s.uo)(this.video)&&new d(this.video,this.ctx),this.ctx.textRenderers.attachVideo(this.video),(0,n.zp)((()=>{this.ctx.textRenderers.attachVideo(null)})),"video"===this.type&&this.ctx.notify("provider-setup",this)}get video(){return this.media}}}}]);