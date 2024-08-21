// Import styles.
import './styles.scss';
// Register elements.
import 'vidstack/player';
import 'vidstack/player/layouts';
import 'vidstack/player/ui';
import HLS from 'hls.js';
import { isHLSProvider, type MediaProviderAdapter } from 'vidstack';

// Extend the MediaProviderAdapter type to include the HLS-specific `library` property.
interface HLSProviderAdapter extends MediaProviderAdapter {
  library?: typeof HLS;
}

const player = document.querySelector('media-player')!;

// Specify that the event is a CustomEvent with a detail property
player.addEventListener('provider-change', (event: CustomEvent) => {
  const provider = event.detail as HLSProviderAdapter;
  if (provider?.type === 'hls') {
    // Assign the HLS.js library to the provider
    provider.library = HLS;
  }
});
