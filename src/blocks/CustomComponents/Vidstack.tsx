import {
  MediaPlayer,
  MediaProvider,
  isYouTubeProvider,
  useMediaState,
  Track,
  type MediaProviderAdapter,
  type VTTContent
} from "@vidstack/react";
import {
  defaultLayoutIcons,
  DefaultVideoLayout,
} from "@vidstack/react/player/layouts/default";
import { Poster } from "@vidstack/react";
import { type ChapterMarker } from "./ChapterMarkerCreator";
import { useState, useEffect } from "@wordpress/element";

interface CustomVidStackProps {
  title: string;
  mediaurl: string;
  aspectratio: string;
  poster: string;
  clipend: number;
  clipstart: number;
  loop: boolean;
  onTimeUpdate: (times: {
    currentTime: number;
    clipStartTime: number;
    clipEndTime: number;
  }) => void;
  markers: ChapterMarker[];
}

const RRZEVidstackPlayer: React.FC<CustomVidStackProps> = ({
  title,
  mediaurl,
  aspectratio,
  poster,
  clipend,
  clipstart,
  loop,
  onTimeUpdate,
  markers,
}) => {
  const [cues, setCues] = useState<ChapterMarker[]>([]);

  useEffect(() => {
    setCues(markers);
    console.log(markers);
  }, [markers]);
  
  const content: VTTContent = {
  cues: cues,
};

  const handleProviderChange = (provider: MediaProviderAdapter | null) => {
    if (isYouTubeProvider(provider)) {
      provider.cookies = true;
    }
  };

  const MediaStateObserver: React.FC = () => {
    const currentTime = useMediaState("currentTime");
    const clipStartTime = useMediaState("clipStartTime");
    const clipEndTime = useMediaState("clipEndTime");

    useEffect(() => {
      if (onTimeUpdate) {
        onTimeUpdate({
          currentTime: currentTime,
          clipStartTime: clipStartTime,
          clipEndTime: clipEndTime,
        });
      }
    }, [currentTime, clipStartTime, clipEndTime]);

    return null;
  };

  return (
    <MediaPlayer
      title={title}
      src={mediaurl}
      aspectRatio={aspectratio}
      onProviderChange={handleProviderChange}
      clipEndTime={clipend}
      clipStartTime={clipstart}
      loop={loop}
    >
      <Poster src={poster} alt="" />
      <MediaProvider />
      <DefaultVideoLayout thumbnails={poster} icons={defaultLayoutIcons} />
      {markers && markers.length > 0 && (
        <Track content={content} default={true} kind="chapters" lang="de-DE" type="json" />
      )}
      <MediaStateObserver />
    </MediaPlayer>
  );
};

export { RRZEVidstackPlayer };
