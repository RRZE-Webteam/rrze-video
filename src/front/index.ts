// Import styles.
import './styles.scss';
import { MediaProviderAdapter } from 'vidstack';
import 'vidstack/player/styles/default/theme.css';
import 'vidstack/player/styles/default/layouts/video.css';

// Run code when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', async () => {
  // Declare HLS variable in the broader scope
  let HLS: typeof import('hls.js').default;
  let vidstack;

  // Dynamically import the player modules and HLS.js
  try {
    [{ default: HLS }, vidstack] = await Promise.all([
      import('hls.js'),
      import('vidstack/player'),
    ]);
  } catch (error) {
    console.error('Error loading player libraries:', error);
    return;
  }  

  // Register additional elements and layouts dynamically if needed
  await Promise.all([
    import('vidstack/player/layouts'),
    import('vidstack/player/ui'),
  ]);

  // Select all media-player elements on the page
  const players = document.querySelectorAll('media-player');

  if (!players.length) {
    console.error('No media player elements found!');
    return;
  }

  // Extend the MediaProviderAdapter type to include the HLS-specific `library` property.
  interface HLSProviderAdapter extends MediaProviderAdapter {
    library?: typeof HLS;
  }

  players.forEach((player) => {
    console.log(player);
    // Attach the provider-change event listener to each player
    player.addEventListener('provider-change', (event: CustomEvent) => {
      const provider = event.detail as HLSProviderAdapter;
      if (provider?.type === 'hls') {
        // Assign the HLS.js library to the provider
        provider.library = HLS;
      }
    });
  });
});
