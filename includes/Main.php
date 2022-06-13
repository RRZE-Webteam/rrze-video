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
        Shortcode::instance()->loaded();

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
            'rrze-video-plyr',
            plugins_url('build/front.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_script(
            'rrze-video-plyr',
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
}
