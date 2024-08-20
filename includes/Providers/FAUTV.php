<?php

namespace RRZE\Video\Providers;

defined('ABSPATH') || exit;
use RRZE\Video\Player\StructuredMeta;
use RRZE\Video\Helper;
use RRZE\Video\Utils;
/**
 * Functions to handle and generate required FAU TV video embeds and html
 */
class FAUTV
{

/**
 * Generates HTML for FAU video or audio players based on the provided data.
 *
 * This function dynamically creates the HTML structure for either an audio or video player,
 * depending on the type of media specified in the `$data` array. It handles various media properties
 * including poster images, multiple video quality sources, transcripts, and playback options like looping
 * and clipping (start and end times). Additionally, the function supports advanced accessibility features 
 * and media controls, such as closed captions and screen orientation.
 *
 * The function now also includes the following enhancements:
 * 1. Accessibility: Adds `aria-label`, `role`, `tabindex`, and other ARIA attributes for better accessibility.
 * 2. Layout and Orientation: Dynamically determines and applies media player orientation and layout.
 * 3. Multiple Quality Sources: Supports adding multiple video source URLs for different resolutions.
 * 4. Advanced Media Controls: Includes controls for playback, captions, volume, fullscreen, and PiP (Picture-in-Picture).
 *
 * @param array $data Contains information required for generating the HTML, which can include:
 *      - 'video': array {
 *            'type': 'audio'|'video',
 *            'file': string 'URL of the media',
 *            'width': int 'Width of the video',
 *            'height': int 'Height of the video',
 *            'title': string 'Title of the media',
 *            'poster': string 'URL of the poster image',
 *            'alternative_Video_size_large_url': string 'URL of the large video source',
 *            'alternative_Video_size_large_width': int 'Width of the large video source',
 *            'alternative_Video_size_large_height': int 'Height of the large video source',
 *            'alternative_Video_size_small_url': string 'URL of the small video source',
 *            'alternative_Video_size_small_width': int 'Width of the small video source',
 *            'alternative_Video_size_small_height': int 'Height of the small video source',
 *            'transcript': string 'URL of the transcript file',
 *            ... (other video-related keys)
 *        }
 *      - 'loop': bool Whether the media should loop,
 *      - 'clipstart': int Start time for the video clip (in seconds),
 *      - 'clipend': int End time for the video clip (in seconds),
 *      - 'language': string 'Language of the media, e.g., "en-US"',
 *      - 'aspect_ratio': string 'Aspect ratio class for the video, e.g., "ar-16-9"',
 *      ... (other potential keys)
 *
 * @param int|string $id Unique identifier for the media data. Used for creating specific class names and HTML attributes.
 *
 * @return string Returns the generated HTML for the FAU video or audio player.
 */
public static function generate_fau_html($data, $id)
    {
        $poster = Utils\Utils::evaluatePoster($data, $id);
        $loop = $data['loop'] == 1 ? 'true' : 'false';
        $clipstart = $data['clipstart'] !== 0 ? 'clip-start-time="' . $data['clipstart'] . '"' : '';
        $clipend = $data['clipend'] !== 0 ? 'clip-end-time="' . $data['clipend'] . '"' : '';
    
        $res = [];
        $res[] = '<div itemscope itemtype="http://schema.org/VideoObject" ' . $clipstart . $clipend . ' class="rrze-video rrze-video-container-' . $id . '">';
        $res[] = '<div class="video-meta">';
        $res[] = StructuredMeta::get_html_structuredmeta($data);
        $res[] = '</div>';
    
        $res[] = '<media-player load="visible" loop="' . $loop . '" poster-load="visible" id="' . $id . '" crossorigin playsinline class="' . Utils\Utils::get_aspectratio_class($data) . '">';
    
        $res[] = '<media-provider>';
    
        // Add the primary video source
        $res[] = '<source itemprop="contentUrl" src="' . htmlspecialchars($data['video']['file'], ENT_QUOTES, 'UTF-8') . '" type="video/mp4" data-width="1920" data-height="1100">';
    
        // Add additional quality sources if they exist
        if (!empty($data['video']['alternative_Video_size_large_url'])) {
            $res[] = '<source itemprop="contentUrl" src="' . htmlspecialchars($data['video']['alternative_Video_size_large_url'], ENT_QUOTES, 'UTF-8') . '" type="video/mp4" data-width="' . htmlspecialchars($data['video']['alternative_Video_size_large_width'], ENT_QUOTES, 'UTF-8') . '" data-height="' . htmlspecialchars($data['video']['alternative_Video_size_large_height'], ENT_QUOTES, 'UTF-8') . '">';
        }
    
        if (!empty($data['video']['alternative_Video_size_small_url'])) {
            $res[] = '<source itemprop="contentUrl" src="' . htmlspecialchars($data['video']['alternative_Video_size_small_url'], ENT_QUOTES, 'UTF-8') . '" type="video/mp4" data-width="' . htmlspecialchars($data['video']['alternative_Video_size_small_width'], ENT_QUOTES, 'UTF-8') . '" data-height="' . htmlspecialchars($data['video']['alternative_Video_size_small_height'], ENT_QUOTES, 'UTF-8') . '">';
        }
    
        // Transcript and other video elements
        if (!empty($data['video']['transcript'])) {
            $transcriptHtml = Utils\Utils::get_fauvideo_transcript_tracks($data);
            $res[] = $transcriptHtml;
        }
    
        // Poster image inside the media player
        $res[] = '<media-poster itemprop="image" class="vds-poster" src="' . htmlspecialchars($poster, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($data['video']['title'], ENT_QUOTES, 'UTF-8') . '">';
        $res[] = '</media-poster>';
        
        // Close other media-player elements
        $res[] = '</media-provider>';
        $res[] = '<media-audio-layout>';
        $res[] = '</media-audio-layout>';
        $res[] = '<media-video-layout>';
        $res[] = '</media-video-layout>';
        $res[] = '</media-player>';
        $res[] = '</div>';
    
        return implode("\n", $res);
    }


    /**
     * Generates HTML for FAU video or audio players based on the provided data.
     *
     * This function dynamically creates either an audio or video player's HTML.
     * It checks the type of media ('audio' or 'video') from the provided `$data` and structures
     * the HTML accordingly. The function also handles various video properties like poster images,
     * multiple video sources, and transcripts. If the video or audio is not supported by the browser,
     * a fallback message is provided.
     *
     * @param array $data Contains information required for generating the HTML, which can include:
     *      - 'video': {
     *            'type': 'audio'|'video',
     *            'file': 'URL of the media',
     *            'width': 'Width of the video',
     *            'height': 'Height of the video',
     *            'title': 'Title of the media',
     *            ... (other video-related keys)
     *        }
     *      - 'url': 'Direct URL of the video or audio',
     *      - 'inLanguage' or 'language': 'Language of the media, e.g., "en-US"',
     *      ... (other potential keys)
     *
     * @param int|string $id Unique identifier for the media data. Used for creating specific class names and HTML attributes.
     *
     * @return string Returns the generated HTML for the FAU video or audio player.
     */
    public static function generate_fauApi_html($data, $id)
    {
        Utils\Helper::debug($data);
        $poster = Utils\Utils::evaluatePoster($data, $id);
        $classname = 'plyr-instance plyr-videonum-' . $id . ' ' . Utils\Utils::get_aspectratio_class($data);
        $mime_type = 'application/vnd.apple.mpegurl';
        $res = [];

        $res[] = '<div class="rrze-video rrze-video-container-' . $id . ' '. $classname . '">';
        $res[] = '<media-player load="visible" poster-load="visible" id="' . $id . '" title="' . $data['video']['title'] . '" crossorigin playsinline>';
        $res[] = '<media-provider>';
        $res[] = '<source itemprop="contentUrl" src="' . $data['url'] . '" type="' . $mime_type . '">';
        $res[] = '<media-poster  class="vds-poster" src="' . $poster . '" alt="' . $data['video']['title'] . '">';
        $res[] = '</media-poster>';
        if (!empty($data['vtt'])) {
            $res[] = '<track kind="captions" src="' . $data['vtt'] . '" srclang="' . $data['language'] . '" label="' . __("Untertitel") . '" default>';
        }
        $res[] = '</media-provider>';
        $res[] = '<media-audio-layout>';
        $res[] = '</media-audio-layout>';
        $res[] = '<media-video-layout>';
        $res[] = '</media-video-layout>';
        $res[] = '</media-player>';
        $res[] = '</div>';
        return implode("\n", $res);
    }
}