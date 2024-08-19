<?php

namespace RRZE\Video\Providers;

defined('ABSPATH') || exit;
use RRZE\Video\StructuredMeta;
/**
 * Functions to handle and generate required YouTube video embeds and html
 */
class YouTube
{
     /**
     * Generates the HTML for YouTube embeds.
     *
     * This function constructs the necessary HTML markup to embed a YouTube video
     * using structured data and the provided video data. The function handles optional 
     * video title information and creates unique class names based on the given video ID.
     * The generated embed link is privacy-enhanced using the "youtube-nocookie" domain.
     *
     * @param array $data Array containing the video details.
     *      [
     *          'video' => [
     *              'title' => 'The video title',       // Optional: Title of the video.
     *              'v' => 'abcdefg'                    // Required: The YouTube video ID (part after "v=" in YouTube URLs).
     *          ]
     *      ]
     *
     * @param int|string $id Unique identifier used to generate a distinct class name for the video.
     *
     * @return string The generated HTML markup for the YouTube embed.
     */
    public static function generate_html($data, $id)
    {
        $res = [];
        $aspectRatio = isset($data['aspectratio']) ? $data['aspectratio'] : '16/9';
        $classname = 'plyr-videonum-' . $id;

        if ($aspectRatio !== '9/16') {
            $res[] = '<div class="youtube-video ' . $classname . '"';
            $res[] = ' itemscope itemtype="https://schema.org/Movie"';
            $res[] = '>';
        }
        $res[] = StructuredMeta::get_html_structuredmeta($data);
        if ($aspectRatio !== '9/16') {
            $res[] = '<div class="plyr__video-embed">';
        }
        $res[] = '<iframe';
        if ($aspectRatio === '9/16') {
            $res[] = 'width="315"';
            $res[] = 'height="560"';
        }
        if (!empty($data['video']['title'])) {
            $res[] = ' title="' . esc_html($data['video']['title']) . '"';
        }
        $res[] = '  src="https://www.youtube-nocookie.com/embed/' . $data['video']['v'] . '?rel=0&showinfo=0&iv_load_policy=3&modestbranding=1"';
        $res[] = ' frameborder="0"';
        $res[] = '  allowfullscreen';
        $res[] = '  allowtransparency';
        $res[] = '  allow="autoplay"';
        $res[] = '></iframe>';
        if ($aspectRatio !== '9/16') {
            $res[] = '</div>';
            $res[] = '</div>';
        }

        return implode("\n", $res);
    }
}