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
        
            // Sanitize chapter markers data
            foreach ($chapter_markers as &$marker) {
                $marker['id'] = sanitize_text_field($marker['id']);
                $marker['startTime'] = floatval($marker['startTime']);
                $marker['endTime'] = floatval($marker['endTime']);
                $marker['text'] = sanitize_text_field($marker['text']);
            }
        
            // Register the localized script for frontend
            wp_enqueue_script('rrze-video-front-js');
        
            // Prepare data for localization
            $video_data = [];
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

    /**
     * Adds custom block category for grouping RRZE Video block in the block editor underneath the category RRZE.
     *
     * @param array $categories Existing block categories.
     * @param WP_Post $post Current post object.
     * @return array Modified block categories.
     */
    public function my_custom_block_category($categories, $post)
    {
        $custom_category = [
            'slug'  => 'rrze_elements',
            'title' => __('RRZE Elements', 'rrze-elements-blocks'),
            'icon'  => 'layout',
        ];

        array_unshift($categories, $custom_category);

        return $categories;
    }
}

add_action('init', [Gutenberg::class, 'register_block']);
