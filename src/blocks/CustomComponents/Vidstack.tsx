///////////////////////////////
// Import WordPress Dependencies
import {useState, useEffect, useRef, memo} from "@wordpress/element";

// Import Vidstack Dependencies
import {
  MediaPlayer,
  MediaProvider,
  isYouTubeProvider,
  useMediaState,
  Track,
  type MediaProviderAdapter,
  type VTTContent,
  type MediaPlayerInstance,
} from "@vidstack/react";
import {
  defaultLayoutIcons,
  DefaultVideoLayout,
  DefaultAudioLayout,
} from "@vidstack/react/player/layouts/default";
import {Poster} from "@vidstack/react";

// Import Types
import {type ChapterMarker} from "./ChapterMarkerCreator";
import {getAspectRatioClass} from "../Utils/utils";

///////////////////////////////
// Interfaces
interface CustomVidStackProps {
  title: string;
  mediaurl: string;
  aspectratio: string;
  poster: string;
  clipend: number;
  clipstart: number;
  loop: boolean;
  onTimeUpdate: (times: {
    currentPlayerTime: number;
    playerClipStart: number;
    playerClipEnd: number;
    playerDuration: number;
  }) => void;
  markers: ChapterMarker[];
  viewType: 'video' | 'audio';
}

///////////////////////////////
// Custom Vidstack Player with Memo
const RRZEVidstackPlayer: React.FC<CustomVidStackProps> = memo(
  ({
     title,
     mediaurl,
     aspectratio,
     poster,
     clipend,
     clipstart,
     loop,
     onTimeUpdate,
     markers,
     viewType
   }) => {
    let player = useRef<MediaPlayerInstance>(null);
    const [cues, setCues] = useState<ChapterMarker[]>([]);
    const [showPoster, setShowPoster] = useState(true);
    const content: VTTContent = {
      cues: cues,
    };

    ///////////////////////////////
    // Use Effects

    const MediaStateObserver: React.FC = () => {
      const paused = useMediaState("paused");
      const currentTime = useMediaState("currentTime");
      const clipStartTime = useMediaState("clipStartTime");
      const clipEndTime = useMediaState("clipEndTime");
      const clipDuration = useMediaState("duration");

      useEffect(() => {
        if (paused && onTimeUpdate) {
          onTimeUpdate({
            currentPlayerTime: currentTime,
            playerClipStart: clipStartTime,
            playerClipEnd: clipEndTime,
            playerDuration: clipDuration,
          });
        }
      }, [paused, currentTime, clipStartTime, clipEndTime]);

      return null;
    };

    useEffect(() => {
      setCues(markers);
    }, [markers]);

    useEffect(() => {
      const playerElement = player.current?.el;
      const playerDocument = playerElement?.ownerDocument;
      const outerDocument = document;
      const playerWindow = playerDocument?.defaultView;
      const PointerEventConstructor = outerDocument.defaultView?.PointerEvent;

      if (
        !playerElement ||
        !playerDocument ||
        !playerWindow ||
        !PointerEventConstructor ||
        playerDocument === outerDocument
      ) {
        return;
      }

      const handlePointerEnd = (event: PointerEvent) => {
        const draggingTimeSlider = playerElement.querySelector(
          "[data-media-time-slider][data-dragging], " +
            ".vds-time-slider[data-dragging]"
        );

        if (!draggingTimeSlider) {
          return;
        }

        // Vidstack listens on the outer document while Gutenberg portals the
        // player into an iframe. Forward the missing release event so dragging
        // cannot remain active after the pointer is released inside the iframe.
        outerDocument.dispatchEvent(
          new PointerEventConstructor("pointerup", {
            bubbles: true,
            cancelable: true,
            composed: true,
            pointerId: event.pointerId,
            pointerType: event.pointerType,
            isPrimary: event.isPrimary,
            clientX: event.clientX,
            clientY: event.clientY,
            screenX: event.screenX,
            screenY: event.screenY,
            button: 0,
            buttons: 0,
            ctrlKey: event.ctrlKey,
            shiftKey: event.shiftKey,
            altKey: event.altKey,
            metaKey: event.metaKey,
          })
        );
      };

      playerDocument.addEventListener("pointerup", handlePointerEnd, true);
      playerDocument.addEventListener("pointercancel", handlePointerEnd, true);

      return () => {
        playerDocument.removeEventListener(
          "pointerup",
          handlePointerEnd,
          true
        );
        playerDocument.removeEventListener(
          "pointercancel",
          handlePointerEnd,
          true
        );
      };
    }, []);

    ///////////////////////////////
    // Event handlers
    const handleProviderChange = (provider: MediaProviderAdapter | null) => {
      if (isYouTubeProvider(provider)) {
        provider.cookies = true;
      }
    };

    const videoAspectRatio = viewType === 'video' && aspectratio
      ? { aspectRatio: aspectratio }
      : {};

    const aspectRatioClass = getAspectRatioClass(aspectratio);

    ///////////////////////////////
    // Render
    return (
      <MediaPlayer
        title={title}
        src={mediaurl}
        {...videoAspectRatio}
        className={aspectRatioClass}
        onProviderChange={handleProviderChange}
        clipEndTime={clipend}
        clipStartTime={clipstart}
        loop={loop}
        ref={player}
        crossOrigin
        playsInline
        viewType={viewType}
      >
        <MediaProvider>
          {viewType === 'video' && (
            <Poster src={poster} alt="" className="vds-poster"/>
          )}
          {markers && markers.length > 0 && (
            <Track
              content={content}
              default={true}
              kind="chapters"
              lang="de-DE"
              type="json"
            />
          )}
        </MediaProvider>
        <MediaStateObserver/>
        {/* Layouts */}
        <DefaultAudioLayout icons={defaultLayoutIcons}/>
        <DefaultVideoLayout icons={defaultLayoutIcons}/>
      </MediaPlayer>
    );
  },
  (prevProps, nextProps) => {
    return (
      prevProps.title === nextProps.title &&
      prevProps.mediaurl === nextProps.mediaurl &&
      prevProps.aspectratio === nextProps.aspectratio &&
      prevProps.poster === nextProps.poster &&
      prevProps.clipend === nextProps.clipend &&
      prevProps.clipstart === nextProps.clipstart &&
      prevProps.loop === nextProps.loop &&
      prevProps.markers === nextProps.markers &&
      prevProps.onTimeUpdate === nextProps.onTimeUpdate
    );
  }
);

export {RRZEVidstackPlayer};
