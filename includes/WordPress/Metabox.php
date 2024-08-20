<?php

namespace RRZE\Video\WordPress;

defined('ABSPATH') || exit;

/**
 * Class Metabox
 * @package RRZE\Video
 */
class Metabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addVideoMetabox']);
        add_action('save_post', [$this, 'saveVideoMetabox']);
    }

    public function addVideoMetabox()
    {
        add_meta_box(
            'rrze-video-metadata',
            __('Video data', 'rrze-video'),
            [$this, 'renderVideoMetabox'],
            CPT::POST_TYPE,
            'normal',
            'default'
        );
    }

    public function renderVideoMetabox($post)
    {
        $url = get_post_meta($post->ID, 'url', true);
        $description = __('Web address (URL) for the video on the video portal used (FAU video portal, YouTube, Vimeo or others).', 'rrze-video');
        ?>
        <label for="url"><?php _e('URL', 'rrze-video'); ?></label>
        <input type="text" name="url" id="url" value="<?php echo esc_attr($url); ?>" />

        <span class="sr-only"><?php echo esc_html($description); ?></span>
        <?php
    }

    public function saveVideoMetabox($post_id)
    {
        if (!isset($_POST['url'])) {
            return;
        }

        $url = sanitize_text_field($_POST['url']);
        update_post_meta($post_id, 'url', $url);
    }
}
