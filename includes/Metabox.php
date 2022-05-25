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
        require_once(plugin()->getPath('vendor/cmb2') . 'init.php');
        add_filter('cmb2_meta_boxes', array($this, 'cmb2VideoMetabox'));
    }

    public function cmb2VideoMetabox($meta_boxes)
    {
        $meta_boxes['rrze-video-metadata'] = [
            'id' => 'rrze-video-metadata',
            'title' => __('Video-Daten', 'rrze-video'),
            'object_types' => [CPT::POST_TYPE],
            'context' => 'normal',
            'priority' => 'default',
            'fields' => [
                [
                    'name' => __('URL', 'rrze-video'),
                    'desc' => __('Webadresse (URL) zum Video auf dem verwendeten Videoportal (FAU-Videoportal, YouTube, Vimeo oder andere).', 'rrze-video'),
                    'type' => 'text_url',
                    'id' => 'url',
                    'default'    => ''
                ],
            ]
        ];
        return $meta_boxes;
    }
}
