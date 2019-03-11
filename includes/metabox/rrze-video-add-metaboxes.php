<?php

namespace RRZE\PostVideo;

add_action( 'add_meta_boxes', 'RRZE\PostVideo\url_meta_box' );

function url_meta_box() {

    add_meta_box(
        'url_box',
        __( 'Url', 'rrze-video' ),
        'RRZE\PostVideo\url_callback',
        'video',
        'normal',
        'high'
    );

}

add_action( 'add_meta_boxes', 'RRZE\PostVideo\description_meta_box' );

function description_meta_box() {

    add_meta_box(
        'description_box',
        __( 'Beschreibung', 'rrze-video' ),
        'RRZE\PostVideo\description_callback',
        'video',
        'normal',
        'high'
    );

}
