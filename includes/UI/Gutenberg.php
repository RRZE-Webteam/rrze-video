<?php

namespace RRZE\Video\UI;

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

        $chapter_markers = [];
        if (!empty($attributes['chapterMarkers'])) {
            $decoded_markers = json_decode($attributes['chapterMarkers'], true);

            if (is_array($decoded_markers)) {
                foreach ($decoded_markers as $marker) {
                    if (!is_array($marker)) {
                        continue;
                    }

                    $chapter_markers[] = [
                        'id' => sanitize_text_field($marker['id'] ?? ''),
                        'startTime' => floatval($marker['startTime'] ?? 0),
                        'endTime' => floatval($marker['endTime'] ?? 0),
                        'text' => sanitize_text_field($marker['text'] ?? ''),
                    ];
                }
            }
        }

        $chapter_markers_json = wp_json_encode($chapter_markers);
        if (false === $chapter_markers_json) {
            $chapter_markers_json = '[]';
        }

        // Keep marker data on the block instance so multiple players cannot overwrite each other.
        return sprintf(
            '<div class="rrze-video-container" data-video-id="%s" data-chapter-markers="%s">%s</div>',
            esc_attr($video_id),
            esc_attr($chapter_markers_json),
            $result
        );
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
