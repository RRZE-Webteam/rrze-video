<?php

namespace RRZE\Video\Config;

defined('ABSPATH') || exit;

class ShortcodeSettings
{
    private static $settings = [
        'rrzevideo' => [
            'id' => [
                'default' => 0,
                'label' => 'ID Number',
                'message' => 'ID number of the video in the video library in the backend.',
                'field_type' => 'number',
                'type' => 'key'
            ],
            'secureclipid' => [
                'default' => 0,
                'label' => 'Clip ID Number',
                'message' => 'Clip ID number of the video Clip',
                'field_type' => 'number',
                'type' => 'key'
            ],
            'start' => [
                'default' => 0,
                'label' => 'Starting time of the Clip',
                'message' => 'Starts the player at the selected time',
                'field_type' => 'number',
                'type' => 'integer'
            ],
            'clipend' => [
                'default' => 9,
                'label' => 'Clips the of the video for looping video content',
                'message' => 'Clips the end of the video. Number in seconds',
                'field_type' => 'number',
                'type' => 'integer'
            ],
            'clipstart' => [
                'default' => 8,
                'label' => 'Clips the start of the video for looping video content',
                'message' => 'Clips the beginning of the video. Number in seconds',
                'field_type' => 'number',
                'type' => 'integer'
            ],
            'loop' => [
                'default' => false,
                'label' => 'Loops the video',
                'message' => 'Loops the video',
                'field_type' => 'bool',
                'type' => 'bool'
            ],
            'url' => [
                'default' => '',
                'field_type' => 'text',
                'label' => 'URL (Video)',
                'type' => 'url'
            ],
            'poster' => [
                'default' => '',
                'field_type' => 'text',
                'label' => 'URL (thumbnail)',
                'type' => 'url'
            ],
            'titletag' => [
                'default' => 'h2',
                'field_type' => 'text',
                'label' => 'Titletag',
                'type' => 'text'
            ],
            'rand' => [
                'default' => '',
                'field_type' => 'slug',
                'label' => 'Category',
                'message' => 'Category (slug) of the video library from which a video is to be shown randomly.',
                'type' => 'slug'
            ],
            'class' => [
                'default' => '',
                'field_type' => 'text',
                'label' => 'CSS Classes',
                'message' => 'CSS classes that the shortcode should receive.',
                'type' => 'class'
            ],
            'showtitle' => [
                'default' => false,
                'field_type' => 'bool',
                'label' => 'Show Title',
                'message' => 'Display the title over the video.',
                'type' => 'bool'
            ],
            'showinfo' => [
                'default' => false,
                'field_type' => 'bool',
                'label' => 'Show Info',
                'message' => 'Show metainfo under the video.',
                'type' => 'bool'
            ],
            'show' => [
                'default' => '',
                'field_type' => 'text',
                'label' => 'Fields',
                'message' => 'Fields to be displayed, overwriting the above checkboxes.',
                'type' => 'string'
            ],
            'aspectratio' => [
                'default' => '16/9',
                'field_type' => 'text',
                'label' => 'Aspect Ratio',
                'message' => 'Aspect ratio of the video.',
                'type' => 'aspectratio'
            ],
            'textAlign' => [
                'default' => 'has-text-align-left',
                'field_type' => 'text',
                'label' => 'CSS Classes',
                'message' => 'CSS classes that the shortcode should receive.',
                'type' => 'class'
            ],
        ],
    ];

    public static function getSettings(string $field = ''): array
    {
        if (empty($field)) {
            return self::$settings;
        }
        return self::$settings[$field] ?? [];
    }

    public static function getDefaults(string $field = ''): array
    {
        $defaults = [];
        $settings = self::getSettings($field);
        foreach ($settings as $key => $value) {
            $defaults[$key] = $value['default'];
        }
        return $defaults;
    }

    public static function getLocalizedLabel(string $field, string $name): string
    {
        $settings = self::getSettings($field);
        return isset($settings[$name]) ? __($settings[$name]['label'], 'rrze-video') : '';
    }

    public static function getLocalizedMessage(string $field, string $name): string
    {
        $settings = self::getSettings($field);
        return isset($settings[$name]) ? __($settings[$name]['message'], 'rrze-video') : '';
    }
}
