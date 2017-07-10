<?php

namespace RRZE\PostVideo;

function custom_post_type_videos() {

    $labels = array(
        'name'                  => _x( 'Video', 'Post Type General Name', 'rrze-video' ),
        'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'rrze-video' ),
        'menu_name'             => __( 'Videos', 'rrze-video' ),
        'parent_item_colon'     => __( 'Übergeordneter Video', 'rrze-video' ),
        'all_items'             => __( 'Alle Video', 'rrze-video' ),
        'add_new_item'          => __( 'Neues Video hinzufügen', 'rrze-video' ),
        'add_new'               => __( 'Neues Video', 'rrze-video' ),
        'edit_item'             => __( 'Video bearbeiten', 'rrze-video' ),
        'update_item'           => __( 'Video aktualisieren', 'rrze-video' ),
        'view_item'             => __( 'Video anzeigen', 'rrze-video' ),
        'search_items'          => __( 'Video suchen', 'rrze-video' ),
        'not_found'             => __( 'Nicht gefunden', 'rrze-video' ),
        'not_found_in_trash'    => __( 'Nicht im Papierkorb gefunden', 'rrze-video' ),
    );
    $args = array(
        'label'                 => __( 'Video', 'rrze-video' ),
        'description'           => __( 'Videos auf der Webseite anzeigen', 'rrze-video' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail' ),
        'taxonomies'            => array( 'Genre' ),
        //'menu_icon'             => 'dashicons-admin-users',
        'hierarchical'          => false,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true, 
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'has_archive'           => true,		
        'exclude_from_search'   => false,
    );
    
    register_post_type( 'video', $args );

}

add_action( 'init', 'RRZE\PostVideo\custom_post_type_videos' );