<?php

namespace RRZE\Video\Player;

defined('ABSPATH') || exit;

use RRZE\Video\Utils;

/**
 * Class to generate required structured meta data for video embeds
 */
class StructuredMeta
{
    /**
     * Generates a series of meta tags containing structured data for a video.
     *
     * This function constructs a set of HTML meta tags that encapsulate structured data 
     * about a video, based on the provided `$data`. The structured data is defined using 
     * the `itemprop` attribute and includes details such as the video's title, poster image, 
     * creation date, author, and other metadata.
     *
     * @param array $data Contains details related to the video. Key details include:
     *      - 'video': array Contains video metadata.
     *          - 'title': string (optional) The title of the video.
     *          - 'preview_image': string (optional) The preview image URL for the video.
     *          - 'thumbnail_url': string (optional) The thumbnail URL for the video.
     *          - 'upload_date': string (optional) The date when the video was uploaded.
     *          - 'author_name': string (optional) The name of the video's author.
     *          - 'provider_name': string (optional) The name of the video's provider.
     *          - 'duration': string (optional) The duration of the video.
     *          - 'version': string (optional) The version of the video.
     *          - 'description': string (optional) A description of the video.
     *      - 'poster': string (optional) URL of the video's poster image.
     *      - 'inLanguage': string (optional) The language of the video content.
     *      - 'language': string (optional) The language of the video content. Used as fallback if 'inLanguage' is not set.
     *
     * @return string Returns a string containing meta tags with structured data for the video.
     */
    public static function get_html_structuredmeta($data)
    {
        $meta = [];

        // Video Title
        if (!empty($data['video']['title'])) {
            $meta[] = '<meta itemprop="name" content="' . htmlspecialchars($data['video']['title'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // Poster Image
        $poster = $data['poster'] ?? $data['video']['preview_image'] ?? $data['video']['thumbnail_url'] ?? '';
        if (!empty($poster)) {
            $meta[] = '<meta itemprop="image" content="' . htmlspecialchars($poster, ENT_QUOTES, 'UTF-8') . '">';
        }

        // Date Created (ISO 8601)
        if (!empty($data['video']['upload_date'])) {
            $dateCreated = date(DATE_W3C, strtotime($data['video']['upload_date']));
            $meta[] = '<meta itemprop="dateCreated" content="' . htmlspecialchars($dateCreated, ENT_QUOTES, 'UTF-8') . '">';
        }

        // Director/Author
        if (!empty($data['video']['author_name'])) {
            $meta[] = '<meta itemprop="director" content="' . htmlspecialchars($data['video']['author_name'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // Language
        $lang = $data['inLanguage'] ?? $data['language'] ?? '';
        if (!empty($lang)) {
            $hreflang = explode("-", $lang)[0];
            $meta[] = '<meta itemprop="inLanguage" content="' . htmlspecialchars($hreflang, ENT_QUOTES, 'UTF-8') . '">';
        }

        // Provider (Simplified)
        if (!empty($data['video']['provider_name'])) {
            $meta[] = '<meta itemprop="provider" content="' . htmlspecialchars($data['video']['provider_name'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // Thumbnail URL (If different from poster)
        if (!empty($data['video']['thumbnail_url']) && ($data['video']['thumbnail_url'] != $poster)) {
            $meta[] = '<meta itemprop="thumbnailUrl" content="' . htmlspecialchars($data['video']['thumbnail_url'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // Duration (ISO 8601)
        if (!empty($data['video']['duration'])) {
            $duration = Utils\Utils::format_duration_iso8601($data['video']['duration']);
            $meta[] = '<meta itemprop="duration" content="' . htmlspecialchars($duration, ENT_QUOTES, 'UTF-8') . '">';
        }

        // Version
        if (!empty($data['video']['version'])) {
            $meta[] = '<meta itemprop="version" content="' . htmlspecialchars($data['video']['version'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // Description (formerly abstract)
        if (!empty($data['video']['description'])) {
            $meta[] = '<meta itemprop="description" content="' . htmlspecialchars($data['video']['description'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // Return the concatenated meta tags
        return implode("\n", $meta);
    }
}
