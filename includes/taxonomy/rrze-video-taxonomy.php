<?php

namespace RRZE\PostVideo;

function taxonomy_genre() {

    $labels = array(
        'name'                        => _x( 'Genre', 'Post Type General Name', 'rrze-video' ),
        'singular_name'               => _x( 'Genre', 'Post Type Singular Name', 'rrze-video' ),
        'menu_name'                   => __( 'Genre', 'rrze-video' ),
        'all_items'                   => __( 'Alle Genres anzeigen', 'rrze-video' ),
        'parent_item'                 => __( 'Übergeordnetes Genre', 'rrze-video' ),
        'parent_item_colon'           => __( 'Übergeordnetes Genre', 'rrze-video' ),
        'new_item_name'               => __( 'Neue Genre', 'rrze-video' ),
        'add_new_item'                => __( 'Neues Genre hinzufügen', 'rrze-video' ),
        'edit_item'                   => __( 'Genre bearbeiten', 'rrze-video' ),
        'update_item'                 => __( 'Genre aktualisieren', 'rrze-video' ),
        'separate_items_with_commas'  => __( 'Elemente mit Komma trennen', 'rrze-video' ),
        'search_items'                => __( 'Genre suchen', 'rrze-video' ),
        'add_or_remove_items'         => __( 'Genre hinzufügen oder entfernen', 'rrze-video' ),
        'choose_from_most_used'       => __( 'Am häufigsten verwendet', 'rrze-video' ),
        'not_found'                   => __( 'Nicht gefunden', 'rrze-video' ),
    );
    $args = array(
        'labels'                      => $labels,
        'hierarchical'                => true,
        'public'                      => true,
        'show_ui'                     => true,
        'show_admin_column'           => true,
        'show_in_nav_menus'           => true,
    );
    register_taxonomy( 'genre', array( 'video' ), $args );

}

add_action( 'init', 'RRZE\PostVideo\taxonomy_genre' );