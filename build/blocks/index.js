(()=>{"use strict";const e=window.wp.blocks,t=window.wp.element,r=window.wp.i18n,l=window.wp.components,a=window.wp.primitives,o=(0,t.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(a.Path,{d:"M20 5h-5.7c0-1.3-1-2.3-2.3-2.3S9.7 3.7 9.7 5H4v2h1.5v.3l1.7 11.1c.1 1 1 1.7 2 1.7h5.7c1 0 1.8-.7 2-1.7l1.7-11.1V7H20V5zm-3.2 2l-1.7 11.1c0 .1-.1.2-.3.2H9.1c-.1 0-.3-.1-.3-.2L7.2 7h9.6z"})),i=window.wp.blockEditor,n=window.wp.editor,c=(0,t.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(a.Path,{d:"M9 11.1H5v-4H3v10h2v-4h4v4h2v-10H9v4zm8 4c.5-.4.6-.6 1.1-1.1.4-.4.8-.8 1.2-1.3.3-.4.6-.8.9-1.3.2-.4.3-.8.3-1.3 0-.4-.1-.9-.3-1.3-.2-.4-.4-.7-.8-1-.3-.3-.7-.5-1.2-.6-.5-.2-1-.2-1.5-.2-.4 0-.7 0-1.1.1-.3.1-.7.2-1 .3-.3.1-.6.3-.9.5-.3.2-.6.4-.8.7l1.2 1.2c.3-.3.6-.5 1-.7.4-.2.7-.3 1.2-.3s.9.1 1.3.4c.3.3.5.7.5 1.1 0 .4-.1.8-.4 1.1-.3.5-.6.9-1 1.2-.4.4-1 .9-1.6 1.4-.6.5-1.4 1.1-2.2 1.6v1.5h8v-2H17z"})),s=(0,t.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(a.Path,{d:"M9 11H5V7H3v10h2v-4h4v4h2V7H9v4zm11.3 1.7c-.4-.4-1-.7-1.6-.8v-.1c.6-.2 1.1-.5 1.5-.9.3-.4.5-.8.5-1.3 0-.4-.1-.8-.3-1.1-.2-.3-.5-.6-.8-.8-.4-.2-.8-.4-1.2-.5-.6-.1-1.1-.2-1.6-.2-.6 0-1.3.1-1.8.3s-1.1.5-1.6.9l1.2 1.4c.4-.2.7-.4 1.1-.6.3-.2.7-.3 1.1-.3.4 0 .8.1 1.1.3.3.2.4.5.4.8 0 .4-.2.7-.6.9-.7.3-1.5.5-2.2.4v1.6c.5 0 1 0 1.5.1.3.1.7.2 1 .3.2.1.4.2.5.4s.1.4.1.6c0 .3-.2.7-.5.8-.4.2-.9.3-1.4.3s-1-.1-1.4-.3c-.4-.2-.8-.4-1.2-.7L13 15.6c.5.4 1 .8 1.6 1 .7.3 1.5.4 2.3.4.6 0 1.1-.1 1.6-.2.4-.1.9-.2 1.3-.5.4-.2.7-.5.9-.9.2-.4.3-.8.3-1.2 0-.6-.3-1.1-.7-1.5z"})),d=(0,t.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(a.Path,{d:"M20 13V7h-3l-4 6v2h5v2h2v-2h1v-2h-1zm-2 0h-2.8L18 9v4zm-9-2H5V7H3v10h2v-4h4v4h2V7H9v4z"})),u=(0,t.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(a.Path,{d:"M9 11H5V7H3v10h2v-4h4v4h2V7H9v4zm11.7 1.2c-.2-.3-.5-.7-.8-.9-.3-.3-.7-.5-1.1-.6-.5-.1-.9-.2-1.4-.2-.2 0-.5.1-.7.1-.2.1-.5.1-.7.2l.1-1.9h4.3V7H14l-.3 5 1 .6.5-.2.4-.1c.1-.1.3-.1.4-.1h.5c.5 0 1 .1 1.4.4.4.2.6.7.6 1.1 0 .4-.2.8-.6 1.1-.4.3-.9.4-1.4.4-.4 0-.9-.1-1.3-.3-.4-.2-.7-.4-1.1-.7 0 0-1.1 1.4-1 1.5.5.4 1 .8 1.6 1 .7.3 1.5.4 2.3.4.5 0 1-.1 1.5-.3s.9-.4 1.3-.7c.4-.3.7-.7.9-1.1s.3-.9.3-1.4-.1-1-.3-1.4z"})),m=(0,t.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(a.Path,{d:"M20.7 12.4c-.2-.3-.4-.6-.7-.9s-.6-.5-1-.6c-.4-.2-.8-.2-1.2-.2-.5 0-.9.1-1.3.3s-.8.5-1.2.8c0-.5 0-.9.2-1.4l.6-.9c.2-.2.5-.4.8-.5.6-.2 1.3-.2 1.9 0 .3.1.6.3.8.5 0 0 1.3-1.3 1.3-1.4-.4-.3-.9-.6-1.4-.8-.6-.2-1.3-.3-2-.3-.6 0-1.1.1-1.7.4-.5.2-1 .5-1.4.9-.4.4-.8 1-1 1.6-.3.7-.4 1.5-.4 2.3s.1 1.5.3 2.1c.2.6.6 1.1 1 1.5.4.4.9.7 1.4.9 1 .3 2 .3 3 0 .4-.1.8-.3 1.2-.6.3-.3.6-.6.8-1 .2-.5.3-.9.3-1.4s-.1-.9-.3-1.3zm-2 2.1c-.1.2-.3.4-.4.5-.1.1-.3.2-.5.2-.2.1-.4.1-.6.1-.2.1-.5 0-.7-.1-.2 0-.3-.2-.5-.3-.1-.2-.3-.4-.4-.6-.2-.3-.3-.7-.3-1 .3-.3.6-.5 1-.7.3-.1.7-.2 1-.2.4 0 .8.1 1.1.3.3.3.4.7.4 1.1 0 .2 0 .5-.1.7zM9 11H5V7H3v10h2v-4h4v4h2V7H9v4z"})),p=e=>{switch(e){case"h2":default:return c;case"h3":return s;case"h4":return d;case"h5":return u;case"h6":return m}},_=({attributes:e,setAttributes:r})=>{const a=e=>{r({titletag:e})};return(0,t.createElement)(l.ToolbarDropdownMenu,{icon:p(e.titletag),label:"Select heading level",value:e.titletag,controls:[{title:"H2",isDisabled:"h2"===e.titletag,onClick:()=>a("h2")},{title:"H3",isDisabled:"h3"===e.titletag,onClick:()=>a("h3")},{title:"H4",isDisabled:"h4"===e.titletag,onClick:()=>a("h4")},{title:"H5",isDisabled:"h5"===e.titletag,onClick:()=>a("h5")},{title:"H6",isDisabled:"h6"===e.titletag,onClick:()=>a("h6")}]})},v=({attributes:e,setAttributes:a})=>(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.__experimentalToggleGroupControl,{label:(0,r.__)("Heading level","rrze-video"),value:e.titletag,onChange:e=>{a({titletag:e})},isBlock:!0},(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"h2",label:"H2"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"h3",label:"H3"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"h4",label:"H4"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"h5",label:"H5"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"h6",label:"H6"})),(0,t.createElement)(l.__experimentalDivider,null)),h=({attributes:e,setAttributes:a})=>{const[o,i]=(0,t.useState)([]),n=(e,t)=>{a({rand:t})},c=e=>wp.apiFetch({path:`/wp/v2/genre/${e}`}).then((e=>({slug:e.slug,name:e.name,id:e.id,parent:e.parent}))).catch((e=>(console.log(e),{})));return(0,t.useEffect)((()=>{wp.apiFetch({path:"/wp/v2/rrze-video/"}).then((e=>{let t=[];e.forEach((e=>{t.push(e.genre)}));let r=t.flat();return[...new Set(r)]})).catch((e=>(console.log(e),[]))).then((e=>{Promise.all(e.map(c)).then((e=>{i(e)})).catch((e=>{console.log(e)}))}))}),[]),(0,t.createElement)(t.Fragment,null,o.length>0&&(0,t.createElement)(l.RadioControl,{label:(0,r.__)("Categories","rrze-video"),options:[{label:(0,r.__)("Don't show random videos.","rrze-video"),value:""},...o.map((e=>({label:e.name,value:e.slug})))],onChange:e=>{if(""===e)n(0,"");else{const t=o.find((t=>t.slug===e));n(t.id,t.slug)}},selected:e.rand}),0===o.length&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.__experimentalDivider,null),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.Notice,{status:"info",isDismissible:!1},(0,r.__)("Add Categories via Dashboard > Video Library > Categories to use this feature.","rrze-video")))))},g=e=>{const[a,o]=(0,t.useState)([]),{attributes:i,setAttributes:n}=e;return(0,t.useEffect)((()=>{wp.apiFetch({path:"/wp/v2/rrze-video/"}).then((e=>{let t=[];e.forEach((e=>{t.push({id:e.id,title:e.title.rendered})}));let r=t.flat();return[...new Set(r)]})).catch((e=>(console.log(e),[]))).then((e=>{Promise.all(e).then((e=>{o(e)})).catch((e=>{console.log(e)}))}))}),[]),(0,t.createElement)(t.Fragment,null,0!==a.length&&(0,t.createElement)(l.RadioControl,{label:"Video ID",selected:i.id,options:[{label:(0,r.__)("No video selected","rrze-video"),value:""},...a.map((e=>({label:`${e.title} (${e.id})`,value:e.id.toString()})))],onChange:e=>{n({id:e})}}),0===a.length&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.__experimentalDivider,null),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.Notice,{status:"info",isDismissible:!1},(0,r.__)("Add Videos via Dashboard > Video Library to use this feature.","rrze-video")))))},b=window.wp.data,{MediaUpload:w,MediaUploadCheck:E}=wp.blockEditor,{Button:x,ResponsiveWrapper:z,Spinner:y}=wp.components,{compose:C}=wp.compose,f=["image"],S=C([(0,b.withSelect)(((e,t)=>{const{getMedia:r}=e("core"),{bgImageId:l}=t.attributes;return{bgImage:l?r(l):null}}))])((e=>{const{attributes:l,setAttributes:a,bgImage:o}=e,{bgImageId:i}=l,n=(0,t.createElement)("p",null,(0,r.__)("To edit the background image, you need permission to upload media.","rrze-video"));let c={};o&&o.source_url&&o.source_url;const s=e=>{a({bgImageId:e.id,poster:e.url})};return(0,t.createElement)("div",{className:"rrze-video-block-image"},(0,t.createElement)(E,{fallback:n},(0,t.createElement)(w,{title:(0,r.__)("Background image","rrze-video"),onSelect:s,allowedTypes:f,value:i,render:({open:e})=>(0,t.createElement)(x,{className:i?"editor-post-featured-image__preview":"editor-post-featured-image__toggle",onClick:e},!i&&(0,r.__)("Set Video Thumbnail","rrze-video"),!!i&&!o&&(0,t.createElement)(y,null),!!i&&o&&(0,t.createElement)(z,{naturalWidth:o.media_details.width,naturalHeight:o.media_details.height},(0,t.createElement)("img",{src:o.source_url,alt:(0,r.__)("Video Thumbnail","rrze-video")})))})),!!i&&o&&(0,t.createElement)(E,null,(0,t.createElement)(w,{title:(0,r.__)("Video Thumbnail","rrze-video"),onSelect:s,allowedTypes:f,value:i,render:({open:e})=>(0,t.createElement)(x,{onClick:e,variant:"secondary"},(0,r.__)("Replace Video Thumbnail","rrze-video"))})),!!i&&(0,t.createElement)(E,null,(0,t.createElement)(x,{onClick:()=>{a({bgImageId:void 0,poster:""})},isLink:!0,isDestructive:!0},(0,r.__)("Remove Video Thumbnail","rrze-video"))))})),T=(e,t)=>void 0===t?(console.log(`commaSeparatedString is undefined: ${t} ${e}`),!1):!!t.toLowerCase().split(",").includes(e.toLowerCase()),V=e=>/(www\.youtube\.com\/)shorts\//.test(e)?"youtubeShorts":/(www\.youtube\.com\/embed\/)|(www\.youtube\.com\/)/.test(e)?"youtube":/(www\.vimeo\.com\/)/.test(e)?"vimeo":/(www\.fau\.de\/)/.test(e)?"fauvideo":/(www\.br\.de\/)/.test(e)?"br":/(www\.ard\.de\/)/.test(e)?"ard":"fauvideo",k=({attributes:e,setAttributes:a})=>{const o=t=>{let r=e.show?e.show.toLowerCase().split(","):[];if(r.includes(t.toLowerCase())){let e=r.filter((e=>e!==t.toLowerCase()));a({show:e.join(",")})}else a({show:e.show?`${e.show},${t}`:t})};return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.CheckboxControl,{label:(0,r.__)("Show Title","rrze-video"),checked:T("Title",e.show),onChange:()=>o("title")}),T("Title",e.show)&&(0,t.createElement)(v,{attributes:e,setAttributes:a}),(0,t.createElement)(l.CheckboxControl,{label:(0,r.__)("Show Videolink","rrze-video"),checked:T("link",e.show),onChange:()=>o("link")}),(0,t.createElement)(l.CheckboxControl,{label:(0,r.__)("Show Metadata","rrze-video"),checked:T("meta",e.show),onChange:()=>o("meta")}),(0,t.createElement)(l.CheckboxControl,{label:(0,r.__)("Show Description","rrze-video"),checked:T("desc",e.show),onChange:()=>o("desc")}))},A=({attributes:e,setAttributes:a})=>{const[o,n]=(0,t.useState)(e.url),[c,s]=(0,t.useState)(e.secureclipid),[d,u]=(0,t.useState)(e.clipstart),[m,p]=(0,t.useState)(e.clipend),[_,v]=(0,t.useState)(e.start),{textAlign:b}=e;return(0,t.createElement)(i.InspectorControls,null,(0,t.createElement)(l.PanelBody,{title:(0,r.__)("URL Settings","rrze-video"),icon:"format-video",initialOpen:!1},(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalText,null,(0,r.__)("Enter a video url from FAU Videoportal, YouTube, Vimeo, ARD, BR or Twitter.","rrze-video"))),(0,t.createElement)("form",{onSubmit:e=>{e.preventDefault();const t=o,r=/(www\.youtube\.com\/)shorts\//,l=/(www\.youtube\.com\/)watch\?v=/;let i={};i=r.test(t)?{aspectratio:"9/16",provider:"youtube",orientation:"vertical",url:t.replace(r,"$1embed/")}:l.test(t)?{aspectratio:"16/9",provider:"youtube",orientation:"landscape",url:t.replace(l,"$1embed/")}:/(www\.youtube\.com\/)embed\//.test(t)?{aspectratio:"16/9",provider:"youtube",orientation:"landscape",url:t}:{aspectratio:"16/9",provider:V(t),orientation:"landscape",url:t},a(i)}},(0,t.createElement)(l.BaseControl,{label:(0,r.__)("Video URL","rrze-video"),id:"rrze-video-url"},(0,t.createElement)("input",{className:"rrze-video-input-field",type:"url",value:o,onChange:e=>(e=>{const t=e.target.value;n(t)})(e),placeholder:(0,r.__)("Update the Video URL","rrze-video"),style:{width:"100%"}})),(0,t.createElement)(l.Button,{isPrimary:!0,type:"submit"},(0,r.__)("Embed Video from URL","rrze-video")))),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Video Display Settings","rrze-video"),icon:"admin-appearance",initialOpen:!0},"fauvideo"===e.provider&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(k,{attributes:e,setAttributes:a}),(0,t.createElement)(l.__experimentalDivider,null),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Individual Thumbnail","rrze-video")),(0,t.createElement)(l.__experimentalText,null,(0,r.__)("Replaces the Thumbnail with the image you selected.","rrze-video"))),(0,t.createElement)(S,{attributes:e,setAttributes:a}),(0,t.createElement)(l.__experimentalDivider,null)),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Aspect Ratio","rrze-video")),"fauvideo"===e.provider&&(0,t.createElement)(l.__experimentalText,null,(0,r.__)("In rare cases it can be useful to select an aspect ratio to prevent black borders. Only affects FAU Video embeds.","rrze-video"))),"youtube"===e.provider&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalText,null,(0,r.__)("Controls the video orientation. Vertical videos are displayed in portrait mode.","rrze-video"))),(0,t.createElement)(l.__experimentalToggleGroupControl,{label:(0,r.__)("Orientation","rrze-video"),value:e.orientation,onChange:e=>{a("landscape"===e?{orientation:e,aspectratio:"16/9"}:{orientation:e,aspectratio:"9/16"})},isBlock:!0},(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"landscape",label:(0,r.__)("Landscape mode","rrze-video")}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"vertical",label:(0,r.__)("Vertical video","rrze-video")})),"vertical"===e.orientation&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalText,null,(0,r.__)("Controls the video alignment for vertical Videos.","rrze-video"))),(0,t.createElement)(l.__experimentalToggleGroupControl,{label:(0,r.__)("Alignment","rrze-video"),value:b,onChange:e=>{a({textAlign:e})},isBlock:!0},(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"",label:(0,r.__)("Left","rrze-video")}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"has-text-align-center",label:(0,r.__)("Center","rrze-video")}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"has-text-align-right",label:(0,r.__)("Right","rrze-video")})))),"fauvideo"===e.provider&&(0,t.createElement)(l.__experimentalToggleGroupControl,{label:(0,r.__)("Aspect ratio","rrze-video"),value:e.aspectratio,onChange:e=>{a({aspectratio:e})},isBlock:!0},(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"16/9",label:"16:9"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"4/3",label:"4:3"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"1/1",label:"1:1"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"2.35/1",label:"2.35:1"}),(0,t.createElement)(l.__experimentalToggleGroupControlOption,{value:"2.40/1",label:"2.40:1"}))),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Video Library","rrze-video"),icon:"video-alt3",initialOpen:!1},(0,t.createElement)(l.__experimentalText,null,(0,r.__)("You can add videos to your video library by navigating to Dashboard\n            | Video library | Add new.","rrze-video")),(0,t.createElement)(l.__experimentalDivider,null),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Random Output","rrze-video")),(0,t.createElement)(l.__experimentalText,null,(0,r.__)("You can select a Video library category and a random video will be displayed from this category.","rrze-video"))),(0,t.createElement)(h,{attributes:null!=e?e:{},setAttributes:a}),(0,t.createElement)(l.__experimentalDivider,null),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Individual Videos","rrze-video")),(0,t.createElement)(l.__experimentalText,null,(0,r.__)("You can select a Video from within your Video library.","rrze-video"))),(0,t.createElement)(g,{attributes:null!=e?e:{},setAttributes:a})),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("SSO secured video embed","rrze-video"),initialOpen:!1},(0,t.createElement)(l.__experimentalText,null,(0,r.__)("This feature only works with a FAU Videoportal API key. You can add a FAU Videoportal API key in the settings of this plugin.","rrze-video")),(0,t.createElement)(l.__experimentalDivider,null),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Secure video embed","rrze-video")),(0,t.createElement)(l.__experimentalText,null,(0,r.__)("Enter the ID of your SSO-secured video here. You need a working FAU Video API key for this feature.","rrze-video"))),(0,t.createElement)(l.__experimentalInputControl,{label:(0,r.__)("Secure Video ID","rrze-video"),value:e.secureclipid,onChange:e=>s(e)}),(0,t.createElement)(l.Button,{isPrimary:!0,onClick:()=>a({secureclipid:c})},(0,r.__)("Embed secure video","rrze-video"))),(0,t.createElement)(l.PanelBody,{title:(0,r.__)("Player controls","rrze-video"),initialOpen:!1},(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Loop mode","rrze-video")),(0,t.createElement)(l.__experimentalText,null,(0,r.__)("Activates the loop feature. The video will be played in a loop.","rrze-video"))),(0,t.createElement)(l.ToggleControl,{checked:e.loop,onChange:e=>a({loop:e}),label:(0,r.__)("Activate looping","rrze-video")}),e.loop&&(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalText,null,(0,r.__)("The loop mode is activated. The video will be played in a loop. If your video contains branding, the default settings should be sufficient. Else you can control the position in the clip where the loop should get triggered (clipend) and the position where the looped video should start (clipstart) with the following settings:","rrze-video")),(0,t.createElement)(l.__experimentalNumberControl,{label:(0,r.__)("Start of the looping section","rrze-video"),value:d,onChange:e=>u(e)}),(0,t.createElement)(l.__experimentalNumberControl,{label:(0,r.__)("End of the looping section","rrze-video"),value:m,onChange:e=>p(e)}),(0,t.createElement)(l.Button,{isPrimary:!0,onClick:()=>a({clipstart:d,clipend:m})},(0,r.__)("Update loop settings","rrze-video"))),(0,t.createElement)(l.__experimentalHeading,{level:3},(0,r.__)("Start position on first play","rrze-video")),(0,t.createElement)(l.__experimentalSpacer,null,(0,t.createElement)(l.__experimentalText,null,(0,r.__)("The first time the video plays, start it at the following position in seconds:","rrze-video")),(0,t.createElement)(l.__experimentalNumberControl,{label:(0,r.__)("Start of the video","rrze-video"),value:_,onChange:e=>v(e)}),(0,t.createElement)(l.Button,{isPrimary:!0,onClick:()=>a({start:_})},(0,r.__)("Update start position","rrze-video")))))},H=(0,t.createElement)(a.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,t.createElement)(a.Path,{d:"M18.7 3H5.3C4 3 3 4 3 5.3v13.4C3 20 4 21 5.3 21h13.4c1.3 0 2.3-1 2.3-2.3V5.3C21 4 20 3 18.7 3zm.8 15.7c0 .4-.4.8-.8.8H5.3c-.4 0-.8-.4-.8-.8V5.3c0-.4.4-.8.8-.8h13.4c.4 0 .8.4.8.8v13.4zM10 15l5-3-5-3v6z"})),D=({attributes:e,setAttributes:a})=>{const[o,i]=(0,t.useState)(e.url);return(0,t.createElement)(l.Placeholder,{icon:H,label:(0,r.__)("Add your Video URL","rrze-video")},(0,t.createElement)("p",null,(0,r.__)("Add your Video URL from FAU Videoportal, YouTube, ARD, ZDF or Vimeo.","rrze-video")),(0,t.createElement)("br",null),(0,t.createElement)("form",{onSubmit:e=>{e.preventDefault();const t=o,r=/(www\.youtube\.com\/)shorts\//,l=/(www\.youtube\.com\/)watch\?v=/;let i={};i=r.test(t)?{aspectratio:"9/16",provider:"youtube",orientation:"vertical",url:t.replace(r,"$1embed/")}:l.test(t)?{aspectratio:"16/9",provider:"youtube",orientation:"landscape",url:t.replace(l,"$1embed/")}:/(www\.youtube\.com\/)embed\//.test(t)?{aspectratio:"16/9",provider:"youtube",orientation:"landscape",url:t}:{aspectratio:"16/9",provider:V(t),orientation:"landscape",url:t},a(i)}},(0,t.createElement)(l.BaseControl,{label:(0,r.__)("Video URL","rrze-video"),id:"rrze-video-url",style:{width:"100%"},className:"rrze-video-editor-input"},(0,t.createElement)("input",{className:"rrze-video-input-field",type:"url",value:o,onChange:e=>(e=>{const t=e.target.value;i(t)})(e),placeholder:(0,r.__)("Update the Video URL","rrze-video"),style:{width:"100%"}})),(0,t.createElement)(l.Button,{isPrimary:!0,type:"submit"},(0,r.__)("Embed Video from URL","rrze-video"))))},B=JSON.parse('{"u2":"rrze/rrze-video"}');(0,e.registerBlockType)(B.u2,{icon:{src:"format-video",background:"#00458c"},transforms:{from:[{type:"shortcode",tag:"fauvideo",attributes:{id:{type:"string",shortcode:e=>e.named.id},url:{type:"string",shortcode:e=>e.named.url},poster:{type:"string",shortcode:e=>e.named.poster},show:{type:"string",shortcode:e=>e.named.show},rand:{type:"string",shortcode:e=>e.named.rand},titletag:{type:"string",shortcode:e=>["h2","h3","h4","h5","h6"].includes(e.named.titletag)?e.named.titletag:"h2"},aspectratio:{type:"string",shortcode:e=>e.named.aspectratio?e.named.aspectratio:"16/9"},class:{type:"string",shortcode:e=>e.named.class}}}]},edit:function(e){const a=Math.random().toString(36).substring(2,15),c=(0,t.useRef)(),s=(0,i.useBlockProps)(),{attributes:d,setAttributes:u}=e,{id:m,url:p,rand:v,aspectratio:h,secureclipid:g}=d,[b,w]=(0,t.useState)(d.url);(0,t.useEffect)((()=>{try{const e=new MutationObserver((()=>{const e=c.current.querySelector("video");e&&(e.style.aspectRatio=h,e.style.backgroundColor="#000000")}));return c.current&&e.observe(c.current,{childList:!0,subtree:!0}),()=>e.disconnect()}catch(e){console.log(e)}}),[h]),(0,t.useEffect)((()=>{switch(V(b)){case"youtube":case"youtubeShorts":u({provider:"youtube"});break;case"vimeo":u({provider:"vimeo"});break;case"fauvideo":default:u({provider:"fauvideo"});break;case"br":u({provider:"br"});break;case"ard":u({provider:"ard"})}}),[b,u]);const E=()=>{u({url:"",rand:"",id:"",provider:"fauvideo",aspectratio:"16/9",orientation:"landscape",textAlign:"has-text-align-left",poster:""}),w("")};return(0,t.createElement)("div",{...s},(0,t.createElement)(A,{attributes:d,setAttributes:u}),m||p||v||g?(0,t.createElement)(t.Fragment,null,(0,t.createElement)(i.BlockControls,null,(0,t.createElement)(l.ToolbarGroup,null,T("Title",d.show)&&(0,t.createElement)(_,{attributes:d,setAttributes:u}),(0,t.createElement)(l.ToolbarItem,null,(()=>(0,t.createElement)(l.ToolbarButton,{icon:o,label:(0,r.__)("Reset Video block","rrze-video"),onClick:E}))))),(0,t.createElement)("div",{className:`rrze-video-container-${a}${g?" securedVideo":""}`,ref:c},(0,t.createElement)(n.ServerSideRender,{block:"rrze/rrze-video",attributes:{url:d.url,show:d.show,rand:d.rand,id:d.id,titletag:d.titletag,poster:d.poster,aspectratio:d.aspectratio,textAlign:d.textAlign,secureclipid:d.secureclipid,loop:d.loop,start:d.start,clipstart:d.clipstart,clipend:d.clipend}}))):(0,t.createElement)(D,{attributes:d,setAttributes:u}))},save:function(){return null}})})();
//# sourceMappingURL=index.js.map