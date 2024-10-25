// Import styles.
import './styles.scss';
import { MediaProviderAdapter, VTTContent } from 'vidstack';
import 'vidstack/player/styles/default/theme.css';
import 'vidstack/player/styles/default/layouts/video.css';

// Import necessary libraries.
import Hls from 'hls.js';
import 'vidstack/player';
import 'vidstack/player/layouts';
import 'vidstack/player/ui';

// Run code when the DOM is fully loaded.
document.addEventListener('DOMContentLoaded', () => {
  // Check if HLS is supported in the current environment.
  if (!Hls.isSupported()) {
    console.error('HLS is not supported in this environment.');
    return;
  }

  // Select all media-player elements on the page.
  const playerContainers = document.querySelectorAll<HTMLElement>('.rrze-video-container');

  if (!playerContainers.length) {
    console.error('No media player elements found!');
    return;
  }

  playerContainers.forEach((container) => {
    const videoId = container.dataset.videoId;
    const chapterMarkers = (window as any).rrzeVideoData?.[videoId]?.chapterMarkers;

    // Ensure chapter markers are available for this specific video.
    if (!chapterMarkers) {
      console.error(`No chapter markers found for video ID: ${videoId}`);
      return;
    }

    // Format chapter markers into the VTTContent structure.
    const content: VTTContent = {
      cues: chapterMarkers.map((marker: any) => ({
        startTime: marker.startTime,
        endTime: marker.endTime,
        text: marker.text,
      })),
    };

    // Select the media player within the container.
    const player = container.querySelector('media-player');
    if (!player) {
      console.error(`No media player element found in container for video ID: ${videoId}`);
      return;
    }

    // Extend the MediaProviderAdapter type to include the HLS-specific `library` property.
    interface HLSProviderAdapter extends MediaProviderAdapter {
      library?: typeof Hls | (() => Promise<typeof Hls>);
    }

    player.addEventListener('provider-change', async (event: CustomEvent) => {
      const provider = event.detail as HLSProviderAdapter;

      // Check if the provider type is 'hls'.
      if (provider?.type === 'hls') {
        provider.library = Hls;
        const videoElement = player.querySelector('video');
        if (videoElement) {
          const hls = new Hls();
          hls.attachMedia(videoElement);

          hls.on(Hls.Events.MEDIA_ATTACHED, () => {
            const videoSrc = videoElement.getAttribute('src');
            if (videoSrc) {
              hls.loadSource(videoSrc);
              hls.on(Hls.Events.MANIFEST_PARSED, () => {
                videoElement.play();
              });
            } else {
              console.error('No video source found.');
            }
          });
          hls.on(Hls.Events.ERROR, (event, data) => {
            console.error('HLS.js error:', data);
          });
        } else {
          console.error('No video element found in media player.');
        }
      }
    });

    // Add JSON chapter markers to the media player using the Track API.
    player.textTracks.add({
      type: 'json',
      kind: 'chapters',
      language: 'en-US',
      default: true,
      content,
    });
  });
});
