// Import styles.
import './styles.css';
// Register elements.
import 'vidstack/player';
import 'vidstack/player/layouts';
import 'vidstack/player/ui';
import HLS from 'hls.js';

import { isHLSProvider, type TextTrackInit } from 'vidstack';

const player = document.querySelector('media-player')!;
console.log('I am a player', player);

player.addEventListener('provider-change', (event) => {
  const provider = event.detail;
  // We can configure provider's here.
  if (isHLSProvider(provider)) {
    provider.config = {
        
    };
  }
});

// We can listen for the `can-play` event to be notified when the player is ready.
player.addEventListener('can-play', () => {
  // ...
});

// ***********************************************************************************************
// Text Track Management
// ***********************************************************************************************

/**
 * You can add these tracks using HTML as well.
 *
 * @example
 * ```html
 * <media-provider>
 *   <track label="..." src="..." kind="..." srclang="..." default />
 *   <track label="..." src="..." kind="..." srclang="..." />
 * </media-provider>
 * ```
 */
// const tracks: TextTrackInit[] = [
//   // Subtitles
//   {
//     src: 'https://files.vidstack.io/sprite-fight/subs/english.vtt',
//     label: 'English',
//     language: 'en-US',
//     kind: 'subtitles',
//     default: true,
//   },
//   {
//     src: 'https://files.vidstack.io/sprite-fight/subs/spanish.vtt',
//     label: 'Spanish',
//     language: 'es-ES',
//     kind: 'subtitles',
//   },
//   // Chapters
//   {
//     src: 'https://files.vidstack.io/sprite-fight/chapters.vtt',
//     kind: 'chapters',
//     language: 'en-US',
//     default: true,
//   },
// ];

// for (const track of tracks) {
//   player.textTracks.add(track);
// }