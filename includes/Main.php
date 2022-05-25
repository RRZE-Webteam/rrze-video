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
        new CPT();

        // Metabox
        new Metabox();

        // Set the video shortcode.
        new Shortcode();

        // Set the video widget.
        add_action('widgets_init', [$this, 'registerWidget']);

        // Enqueue scripts.
        add_action('wp_enqueue_scripts', [$this, 'registerFrontendStyles']);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);
    }

    /**
     * Register the video widget.
     */
    public function registerWidget()
    {
        register_widget(__NAMESPACE__ . '\Widget');
    }

    /**
     * Register the scripts for the frontend.
     */
    public function registerFrontendStyles()
    {
        wp_register_style(
            'rrze-video',
            plugins_url('css/rrze-video.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_script(
            'plyr',
            plugins_url('js/plyr.js', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_script(
            'rrze-video-scripts',
            plugins_url('js/rrze-video.js', plugin()->getBasename()),
            ['plyr'],
            plugin()->getVersion()
        );
    }

    public function adminEnqueueScripts()
    {
        wp_enqueue_style(
            'rrze-video-adminstyle',
            plugins_url('css/rrze-video-admin.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
    }
}
