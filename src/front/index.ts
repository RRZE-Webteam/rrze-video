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
  const players = document.querySelectorAll('media-player');

  if (!players.length) {
    console.error('No media player elements found!');
    return;
  }

  // Retrieve the chapter markers data from the localized rrzeVideoData object.
  const chapterMarkers = (window as any).rrzeVideoData?.chapterMarkers;

  // Ensure chapterMarkers are available.
  if (!chapterMarkers) {
    console.error('Chapter markers data not available');
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

  // Extend the MediaProviderAdapter type to include the HLS-specific `library` property.
  interface HLSProviderAdapter extends MediaProviderAdapter {
    library?: typeof Hls | (() => Promise<typeof Hls>);
  }

  players.forEach((player) => {
    // Attach the provider-change event listener to each player.
    player.addEventListener('provider-change', async (event: CustomEvent) => {
      const provider = event.detail as HLSProviderAdapter;

      // Check if the provider type is 'hls'.
      if (provider?.type === 'hls') {
        // Optionally, use dynamic import for HLS.js if preferred.
        provider.library = Hls; // Static assignment as per the example.

        // Initialize HLS.js with the video element within the media player.
        const videoElement = player.querySelector('video');
        if (videoElement) {
          // Initialize the HLS instance.
          const hls = new Hls();
          hls.attachMedia(videoElement);

          // Listen to the event when media is attached to HLS.js.
          hls.on(Hls.Events.MEDIA_ATTACHED, () => {
            // Load the video source from the video element's src attribute.
            const videoSrc = videoElement.getAttribute('src');
            if (videoSrc) {
              hls.loadSource(videoSrc);

              // Listen for when the HLS manifest is parsed and ready.
              hls.on(Hls.Events.MANIFEST_PARSED, () => {
                // Automatically play the video when ready.
                videoElement.play();
              });
            } else {
              console.error('No video source found.');
            }
          });

          // Error handling for HLS.js.
          hls.on(Hls.Events.ERROR, (event, data) => {
            console.error('HLS.js error:', data);
          });
        } else {
          console.error('No video element found in media player.');
        }
      }
    });

    // Add JSON chapter markers to each media player using the Track API.
    player.textTracks.add({
      type: 'json',
      kind: 'chapters',
      language: 'en-US',
      default: true,
      content,
    });
  });
});
