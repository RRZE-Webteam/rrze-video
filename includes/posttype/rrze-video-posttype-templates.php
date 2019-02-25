<?php

namespace RRZE\PostVideo;

function get_rrze_video_single_template($single_template) {
    global $post;
    if ($post->post_type == 'video') {
        $single_template = plugin_dir_path( plugin_dir_path(__DIR__) ) . '/templates/rrze-video-posttype-single-template.php';
    }
    return $single_template;
}
add_filter( 'single_template', 'RRZE\PostVideo\get_rrze_video_single_template' );  //for single page

function get_rrze_video_archive_template($archive_template) {
    global $post;
    if ($post->post_type == 'video') {
        $archive_template = plugin_dir_path( plugin_dir_path(__DIR__) ) . '/templates/rrze-video-posttype-archive-template.php';
    }
    return $archive_template;
}
add_filter( 'archive_template', 'RRZE\PostVideo\get_rrze_video_archive_template' ); //for archive
