<?php

namespace RRZE\Video;


defined('ABSPATH') || exit;

/**
 * Class Main
 * @package RRZE\Video
 */
class Main
{
    public function __construct()
    {
        // Set the video custom post type.
        new UI\CPT();

        // Metabox
        new UI\Metabox();
        new UI\Settings();
        new UI\RESTAPI();

        // Set the video shortcode.
        Shortcode::instance()->loaded();

        // Set the video widget.
        add_action('widgets_init', [$this, 'registerWidget']);

        // Enqueue scripts.
        add_action('wp_enqueue_scripts', [$this, 'registerFrontendStyles']);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);

        // Register the Custom RRZE Category, if it is not set by another plugin
        add_filter('block_categories_all', [$this, 'my_custom_block_category'], 10, 2);
    }

    /**
     * Register the video widget.
     */
    public function registerWidget()
    {
        register_widget(__NAMESPACE__ . '\UI\Widget');
    }

    /**
     * Register the scripts for the frontend.
     */
    public function registerFrontendStyles()
    {
        wp_register_style(
            'rrze-video-plyr',
            plugins_url('build/front.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_script(
            'rrze-video-front-js',
            plugins_url('build/front.js', plugin()->getBasename()),
            ['jquery-core'],
            plugin()->getVersion(),
            true
        );
    }

    public function adminEnqueueScripts()
    {
        wp_enqueue_style(
            'rrze-video-admin',
            plugins_url('build/admin.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
    }

    /**
     * Adds custom block category if not already present.
     *
     * @param array   $categories Existing block categories.
     * @param WP_Post $post       Current post object.
     * @return array Modified block categories.
     */
    public function my_custom_block_category($categories, $post): array
    {
        // Check if there is already a RRZE category present
        foreach ($categories as $category) {
            if (isset($category['slug']) && $category['slug'] === 'rrze') {
                return $categories;
            }
        }

        $custom_category = [
            'slug'  => 'rrze',
            'title' => __('RRZE Plugins', 'rrze-video'),
        ];

        // Add RRZE to the end of the categories array
        $categories[] = $custom_category;

        return $categories;
    }
}
