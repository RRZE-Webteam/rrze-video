import {
  MediaPlayer,
  MediaProvider,
  isYouTubeProvider,
  type MediaProviderAdapter,
} from "@vidstack/react";
import {
  defaultLayoutIcons,
  DefaultVideoLayout,
} from "@vidstack/react/player/layouts/default";

interface CustomVidStackProps {
  title: string;
  mediaurl: string;
  aspectratio: string;
  poster: string;
  clipend: number;
  clipstart: number;
  loop: boolean;
}

const RRZEVidstackPlayer: React.FC<CustomVidStackProps> = ({
  title,
  mediaurl,
  aspectratio,
  poster,
  clipend,
  clipstart,
  loop,
}) => {
  const handleProviderChange = (provider: MediaProviderAdapter | null) => {
    if (isYouTubeProvider(provider)) {
      provider.cookies = true;
    }
  };

  return (
    <MediaPlayer
      title={title}
      src={mediaurl}
      aspectRatio={aspectratio}
      poster={poster}
      onProviderChange={handleProviderChange}
      clipEndTime={clipend}
      clipStartTime={clipstart}
      loop={loop}
    >
      <MediaProvider />
      <DefaultVideoLayout thumbnails={poster} icons={defaultLayoutIcons} />
    </MediaPlayer>
  );
};

export { RRZEVidstackPlayer };
