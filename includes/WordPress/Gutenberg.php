<?php

namespace RRZE\Video\WordPress;

defined('ABSPATH') || exit;

use RRZE\Video\Shortcode;
use RRZE\Video\Utils\Helper;

class Gutenberg
{
    /**
     * Renders the video block for the frontend
     *
     * @param array $attributes
     * @return string
     */
    public static function rrze_video_render_block($attributes)
    {
        // Prepare and render the shortcode result
        $result = Shortcode::instance()->shortcodeVideo($attributes);

        // Pass chapter markers data only if available in attributes
        if (!empty($attributes['chapterMarkers'])) {
            $chapter_markers = json_decode($attributes['chapterMarkers'], true);

            // Register the localized script for frontend
            wp_enqueue_script('rrze-video-front-js');
            wp_localize_script('rrze-video-front-js', 'rrzeVideoData', [
                'chapterMarkers' => $chapter_markers,
            ]);
        }

        return $result;
    }

    /**
     * Register block assets and render callback
     */
    public static function register_block()
    {
        // Register the block script and set the render callback
        register_block_type(__DIR__ . '/build', [
            'render_callback' => [self::class, 'rrze_video_render_block'],
        ]);
    }
}

add_action('init', [Gutenberg::class, 'register_block']);
