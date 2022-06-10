<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

/**
 * Class Metabox
 * @package RRZE\Video
 */
class Metabox
{
    public function __construct()
    {
        require_once(plugin()->getPath('vendor/CMB2-2.10.1') . 'init.php');
        add_filter('cmb2_meta_boxes', [$this, 'cmb2VideoMetabox']);
    }

    public function cmb2VideoMetabox($metaBoxes)
    {
        $metaBoxes['rrze-video-metadata'] = [
            'id' => 'rrze-video-metadata',
            'title' => __('Video data', 'rrze-video'),
            'object_types' => [CPT::POST_TYPE],
            'context' => 'normal',
            'priority' => 'default',
            'fields' => [
                [
                    'name' => __('URL', 'rrze-video'),
                    'desc' => __('Web address (URL) for the video on the video portal used (FAU video portal, YouTube, Vimeo or others).', 'rrze-video'),
                    'type' => 'text_url',
                    'id' => 'url',
                    'default'    => ''
                ],
            ]
        ];
        return $metaBoxes;
    }
}
