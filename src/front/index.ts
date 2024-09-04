// Import styles.
import './styles.scss';
import { MediaProviderAdapter, type VTTContent } from 'vidstack';
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
  console.log(players);

  const content: VTTContent = {
    cues: [
      { startTime: 0, endTime: 50, text: 'Kapitel 1' },
      { startTime: 50, endTime: 100, text: 'Kapitel 2' },
    ],
  };
  


  if (!players.length) {
    console.error('No media player elements found!');
    return;
  }

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

  // Option 1. Provide JSON directly.
  player.textTracks.add({
    type: 'json',
    kind: 'chapters',
    language: 'en-US',
    default: true,
    content,
  });
  });
});
