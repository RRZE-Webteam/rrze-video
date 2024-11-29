import HLS from 'hls.js';
import './styles.scss';
import { isHLSProvider, MediaProviderAdapter, VTTContent } from 'vidstack';
import 'vidstack/player/styles/default/theme.css';
import 'vidstack/player/styles/default/layouts/video.css';
import 'vidstack/player';
import 'vidstack/player/layouts';
import 'vidstack/player/ui';

document.addEventListener('DOMContentLoaded', () => {

  // Select all media-player elements on the page.
  const playerContainers = document.querySelectorAll<HTMLElement>('.rrze-video-container');

  if (!playerContainers.length) {
    console.error('No media player elements found!');
    return;
  }

  playerContainers.forEach((container) => {
    const videoId = container.dataset.videoId;
    const chapterMarkers = (window as any).rrzeVideoData?.[videoId]?.chapterMarkers;

    // Select the media player within the container.
    const player = container.querySelector('media-player');
    if (!player) {
      console.error(`No media player element found in container for video ID: ${videoId}`);
      return;
    }

    // Ensure chapter markers are available for this specific video.
    if (chapterMarkers) {
      // Format chapter markers into the VTTContent structure.
      const content: VTTContent = {
        cues: chapterMarkers.map((marker: any) => ({
          startTime: marker.startTime,
          endTime: marker.endTime,
          text: marker.text,
        })),
      };

      // Add JSON chapter markers to the media player using the Track API.
      player.textTracks.add({
        type: 'json',
        kind: 'chapters',
        language: 'en-US',
        default: true,
        content,
      });
    }

    // Add support for HLS playback.
    player.addEventListener('provider-change', (event: CustomEvent) => {
      const provider = event.detail;
      if (isHLSProvider(provider)) {
        provider.library = HLS;
      }
    });
  });
});
