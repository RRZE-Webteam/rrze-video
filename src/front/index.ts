import HLS from 'hls.js';
import './styles.scss';
import { isHLSProvider, VTTContent } from 'vidstack';
import 'vidstack/player/styles/default/theme.css';
import 'vidstack/player/styles/default/layouts/video.css';
import 'vidstack/player';
import 'vidstack/player/layouts';
import 'vidstack/player/ui';

interface ChapterMarker {
  startTime: number;
  endTime: number;
  text: string;
}

interface ChapterMarkerData {
  chapterMarkers?: ChapterMarker[];
}

interface RRZEVideoWindow extends Window {
  rrzeVideoData?: Record<string, ChapterMarkerData>;
}

const parseChapterMarkers = (
  container: HTMLElement | null
): ChapterMarker[] => {
  if (!container) {
    return [];
  }

  const serializedMarkers = container.dataset.chapterMarkers;

  if (serializedMarkers) {
    try {
      const markers = JSON.parse(serializedMarkers);
      return Array.isArray(markers) ? markers : [];
    } catch (error) {
      console.error('Invalid chapter marker data.', error);
      return [];
    }
  }

  // Backward compatibility for cached markup rendered by older plugin versions.
  const videoId = container.dataset.videoId;
  return videoId
    ? (window as RRZEVideoWindow).rrzeVideoData?.[videoId]?.chapterMarkers ?? []
    : [];
};

const initializePlayers = () => {
  const players = document.querySelectorAll('media-player');

  if (!players.length) {
    return;
  }

  players.forEach((player) => {
    if (player.dataset.rrzeVideoInitialized === "true") {
      return;
    }

    player.dataset.rrzeVideoInitialized = "true";

    const container = player.closest<HTMLElement>(
      ".rrze-video-container[data-video-id]",
    );
    const chapterMarkers = parseChapterMarkers(container);

    if (chapterMarkers.length > 0) {
      // Format chapter markers into the VTTContent structure.
      const content: VTTContent = {
        cues: chapterMarkers.map((marker) => ({
          startTime: marker.startTime,
          endTime: marker.endTime,
          text: marker.text,
        })),
      };

      // Add JSON chapter markers to the media player using the Track API.
      player.textTracks.add({
        type: "json",
        kind: "chapters",
        language: "en-US",
        default: true,
        content,
      });
    }

    // Add support for HLS playback.
    player.addEventListener("provider-change", (event: CustomEvent) => {
      const provider = event.detail;
      if (isHLSProvider(provider)) {
        provider.library = HLS;
      }
    });
    // Reset to clip start after clip ends to allow replay
    const clipStartAttr = player.getAttribute("data-clip-start");
    const clipEndAttr = player.getAttribute("data-clip-end");

    if (clipStartAttr && clipEndAttr) {
      const clipStart = parseFloat(clipStartAttr);
      const clipEnd = parseFloat(clipEndAttr);
      const clipDuration = clipEnd - clipStart;

      if (
        !Number.isNaN(clipStart) &&
        !Number.isNaN(clipEnd) &&
        clipEnd > clipStart
      ) {
        let clipFinished = false;

        player.addEventListener("timeupdate", () => {
          const media = player as any;
          const currentTime = media.currentTime ?? 0;

          if (!clipFinished && currentTime >= clipDuration - 0.05) {
            clipFinished = true;
            media.pause();
            media.currentTime = 0;
          }
        });

        player.addEventListener("play", () => {
          const media = player as any;

          if (clipFinished || media.currentTime >= clipDuration - 0.05) {
            media.currentTime = 0;
            clipFinished = false;
          }
        });

        player.addEventListener("seeking", () => {
          const media = player as any;
          if (media.currentTime > clipDuration) {
            media.currentTime = 0;
          }
        });
      }
    }
  });
};

initializePlayers();

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializePlayers, {
    once: true,
  });
}
