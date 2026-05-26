import { useState, useEffect, useRef, memo } from "@wordpress/element";

// Import Vidstack Dependencies
import {
  MediaPlayer,
  MediaProvider,
  isYouTubeProvider,
  Track,
  type MediaProviderAdapter,
  type VTTContent,
  type MediaPlayerInstance,
} from "@vidstack/react";
import {
  defaultLayoutIcons,
  DefaultVideoLayout,
} from "@vidstack/react/player/layouts/default";
import { Poster } from "@vidstack/react";

// Import Types
import { type ChapterMarker } from "./ChapterMarkerCreator";
import { getAspectRatioClass } from "../Utils/utils";

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
  markers: ChapterMarker[];
}

///////////////////////////////
// Custom Vidstack Player with Memo
const YouTubeVidstackPlayer: React.FC<CustomVidStackProps> = memo(
  ({
    title,
    mediaurl,
    aspectratio,
    poster,
    clipend,
    clipstart,
    loop,
    markers,
  }) => {
    const player = useRef<MediaPlayerInstance>(null);
    const [cues, setCues] = useState<ChapterMarker[]>([]);
    const content: VTTContent = {
      cues,
    };

    ///////////////////////////////
    // Use Effects

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

    const videoAspectRatio = aspectratio ? { aspectRatio: aspectratio } : {};

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
        viewType="video"
      >
        <MediaProvider>
          <Poster src={poster} alt="" className="vds-poster" />
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
        {/* Layouts */}
        <DefaultVideoLayout icons={defaultLayoutIcons} />
      </MediaPlayer>
    );
  },
);

export { YouTubeVidstackPlayer };
