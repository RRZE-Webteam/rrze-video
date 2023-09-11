<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

$settings = [
    'rrzevideo' => [
        'id' => [
            'default' => 0,
            'label' => __('ID Number', 'rrze-video'),
            'message' => __('ID number of the video in the video library in the backend.', 'rrze-video'),
            'field_type' => 'number',
            'type' => 'key'
        ],
        'url' => [
            'default' => '',
            'field_type' => 'text', // Art des Feldes im Gutenberg Editor
            'label' => __('URL (Video)', 'rrze-video'),
            'type' => 'url' // Variablentyp der Eingabe
        ],
        'poster' => [
            'default' => '',
            'field_type' => 'text', // Art des Feldes im Gutenberg Editor
            'label' => __('URL (thumbnail)', 'rrze-video'),
            'type' => 'url' // Variablentyp der Eingabe
        ],
        'titletag' => [
            'default' => 'h2',
            'field_type' => 'text', // Art des Feldes im Gutenberg Editor
            'label' => __('Titletag', 'rrze-video'),
            'type' => 'text' // Variablentyp der Eingabe
        ],
        'rand' => [
            'default' => '',
            'field_type' => 'slug',
            'label' => __('Category', 'rrze-video'),
            'message' => __('Category (slug) of the video library from which a video is to be shown randomly.', 'rrze-video'),
            'type' => 'slug'
        ],
        'class' => [
            'default' => '',
            'field_type' => 'text',
            'label' => __('CSS Classes', 'rrze-video'),
            'message' => __('CSS classes that the shortcode should receive.', 'rrze-video'),
            'type' => 'class'
        ],
        'showtitle' => [
            'default' => false,
            'field_type' => 'bool',
            'label' => __('Show Title', 'rrze-video'),
            'message' => __('Display the title over the video.', 'rrze-video'),
            'type' => 'bool'
        ],
        'showinfo' => [
            'default' => false,
            'field_type' => 'bool',
            'label' => __('Show Info', 'rrze-video'),
            'message' => __('Show metainfo under the video.', 'rrze-video'),
            'type' => 'bool'
        ],
        'show' => [
            'default' => '',
            'field_type' => 'text',
            'label' => __('Fields', 'rrze-video'),
            'message' => __('Fields to be displayed, overwriting the above checkboxes.', 'rrze-video'),
            'type' => 'string'
        ],
        'aspectratio' => [
            'default' => '16/9',
            'field_type' => 'text',
            'label' => __('Aspect Ratio', 'rrze-video'),
            'message' => __('Aspect ratio of the video.', 'rrze-video'),
            'type' => 'aspectratio'
        ],
        'textAlign' => [
            'default' => 'has-text-align-left',
            'field_type' => 'text',
            'label' => __('CSS Classes', 'rrze-video'),
            'message' => __('CSS classes that the shortcode should receive.', 'rrze-video'),
            'type' => 'class'
        ],
    ],
];
