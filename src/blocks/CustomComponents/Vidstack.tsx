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
