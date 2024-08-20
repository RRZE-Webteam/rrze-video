<?php

namespace RRZE\Video\Providers;

defined('ABSPATH') || exit;
use RRZE\Video\Player\StructuredMeta;
/**
 * Functions to handle and generate required YouTube video embeds and html
 */
class Vimeo
{
    /**
     * Generates the HTML for Vimeo embeds.
     *
     * This function will construct the necessary HTML markup to embed a Vimeo video
     * using structured data and the given video data. It also handles optional video
     * title information and creates unique class names based on the video ID.
     *
     * @param array $data Array containing the video details.
     *      [
     *          'video' => [
     *              'title' => 'The video title',       // Optional: Title of the video.
     *              'video_id' => '12345678'            // Required: The Vimeo video ID.
     *          ]
     *      ]
     *
     * @param int|string $id Unique identifier to append to class name.
     *
     * @return string The generated HTML markup for the Vimeo embed.
     */
    public static function generate_vimeo_html($data, $id)
    {
        $classname = 'plyr-videonum-' . $id;
        $res = [];
        $res[] = '<div class="vimeo-video ' . $classname . '"';
        $res[] = ' itemscope itemtype="https://schema.org/Movie"';
        $res[] = '>';
        $res[] = StructuredMeta::get_html_structuredmeta($data);
        $res[] = '<div class="plyr__video-embed">';
        $res[] = '<iframe';
        if (!empty($data['video']['title'])) {
            $res[] = ' title="' . esc_html($data['video']['title']) . '"';
        }
        $res[] = '  src="https://player.vimeo.com/video/' . $data['video']['video_id'] . '?autoplay=0&loop=0&title=0&byline=0&portrait=0"';
        $res[] = '  allowfullscreen';
        $res[] = '  allowtransparency';
        $res[] = '  allow="autoplay"';
        $res[] = '></iframe>';
        $res[] = '</div>';
        $res[] = '</div>';
        return implode("\n", $res);
    }
}