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
        // Generate a unique ID for the video block instance.
        $video_id = uniqid('rrze-video-');
        $attributes['videoId'] = $video_id;
    
        // Render the shortcode output.
        $result = Shortcode::instance()->shortcodeVideo($attributes);
    
        // Pass chapter markers data specific to this video instance if available.
        if (!empty($attributes['chapterMarkers'])) {
            $chapter_markers = json_decode($attributes['chapterMarkers'], true);
    
            // Register the localized script for frontend
            wp_enqueue_script('rrze-video-front-js');
    
            // Localize script with chapter markers data, keyed by video ID.
            $localized_data = wp_scripts()->get_data('rrze-video-front-js', 'data') ?: '{}';
            $video_data = json_decode(trim(str_replace('var rrzeVideoData =', '', $localized_data), ';'), true) ?: [];
            $video_data[$video_id] = ['chapterMarkers' => $chapter_markers];
    
            wp_localize_script('rrze-video-front-js', 'rrzeVideoData', $video_data);
        }
    
        // Add the unique ID as an HTML data attribute to identify the player.
        return sprintf('<div class="rrze-video-container" data-video-id="%s">%s</div>', esc_attr($video_id), $result);
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
