<?php

namespace RRZE\PostVideo;

function video_columns( $columns ) {

	$columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Title', 'rrze-video' ),
            'id'            => __( 'ID', 'rrze-video'),
            'url'           => __( 'Url', 'rrze-video' ),
            'thumbnail'     => __( 'Thumbnail', 'rrze-video' ),
            'description'   => __( 'Beschreibung', 'rrze-video' ),
            'genre'         => __( 'Genre', 'rrze-video' ),
            'date'          => __( 'Datum', 'rrze-video' ),
	);

	return $columns;
}

add_filter( 'manage_edit-video_columns', 'RRZE\PostVideo\video_columns') ;

function show_video_columns($column_name) {
    global $post;
    switch ($column_name) {
        case 'title':
            $title = get_post_meta($post->ID, 'title', true);
            echo $title;
            break;
        case 'id':
            $id = get_the_ID();
            echo $id;
            break;
        case 'url':
            $video = get_post_meta($post->ID, 'url', true);
            echo $video;
            break;
        case 'video':
            $video = get_post_meta($post->ID, 'video_id', true);
            echo $video;
            break;
        case 'youtube':
            $youtube = get_post_meta($post->ID, 'youtube_id', true);
            echo $youtube;
            break;
        case 'description':
            $description = get_post_meta($post->ID, 'description', true);
            echo $description;
            break;
        case 'thumbnail':
            $thumbnail = get_the_post_thumbnail($post->ID,  array( 80, 45));
            echo $thumbnail;
            break;
         case 'genre':
            $genre = get_the_term_list($post->ID, 'genre');
            echo $genre;
            break;
    }
}

add_action('manage_posts_custom_column',  'RRZE\PostVideo\show_video_columns');

function video_sortable_columns() {
  return array(
    'genre'   => 'genre',
  );
}

add_filter( 'manage_edit-video_sortable_columns', 'RRZE\PostVideo\video_sortable_columns' );
