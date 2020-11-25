<?php

namespace RRZE\Video\Config;

defined('ABSPATH') || exit;

/**
 * Gibt der Name der Option zurück.
 * @return array [description]
 */
function getOptionName() {
    return 'rrze_video_plugin_options';
}

/**
 * Fixe und nicht aenderbare Plugin-Optionen
 * @return array 
 */
function getConstants() {
        $options = array(


	    'fauthemes' => [
		'FAU-Einrichtungen', 
		'FAU-Philfak',
		'FAU-Natfak', 
		'FAU-RWFak', 
		'FAU-Medfak', 
		'FAU-Techfak',
		'FAU-Jobs'
		],
	    'admin_posts_per_page'  => 25,

        );               
        // für ergänzende Optionen aus anderen Plugins
        $options = apply_filters('rrze_video_constants', $options);
        return $options; // Standard-Array für zukünftige Optionen
    }

/**
 * Gibt die Einstellungen des Menus zurück.
 * @return array [description]
 */
function getMenuSettings() {
    return [
        'page_title'    => __('RRZE Video', 'rrze-video'),
        'menu_title'    => __('RRZE Video', 'rrze-video'),
        'capability'    => 'manage_options',
        'menu_slug'     => 'rrze-video',
        'title'         => __('RRZE-Video', 'rrze-video').' '.__('Einstellungen', 'rrze-video'),
    ];
}


/**
 * Gibt die Einstellungen der Optionsbereiche zurück.
 * @return array [description]
 */
function getSections() {
    return [
	[
            'id'    => 'constants',
            'title' => __('Einstellungen', 'rrze-video')
        ],
      
    ];
}

/**
 * Gibt die Einstellungen der Optionsfelder zurück.
 * @return array [description]
 */
function getFields() {
    $imagesizes = array();
    
    
    return [

	'constants' => [
	     [
		'name'  => 'youtube_activate_checkbox',
		'label' => __('Nutze YouTube Player', 'rrze-video'),
		'desc'  => __('Falls der Default YouTube-Player statt des WordPress Mediaelement-Players genutzt werden soll, hier aktivieren', 'rrze-video'),
		'type'  => 'checkbox',
		'default' => false,
            ],
	     [
		'name'  => 'preview_image',
		'label' => __('Video Thumbnail', 'rrze-video'),
		'desc'  => __('Allgemeines Video-Thumbnail, welches verwendet wird, wenn die Videos kein eigenes Vorschaubild mit bringen.', 'rrze-video'),
		'type'  => 'file',
		'default' => plugin_dir_url(__DIR__) . 'img/_preview.png'
            ],
	    [
		'name'  => 'preview_image_vendor',
		'label' => __('Vorschaubild vom Video-Providers', 'rrze-video'),		
		'desc'  => __('Bei Auswahl dieser Option werden die Angaben zum lokalen Vorschau-Bild ignoriert und Besucher-Daten können schon vor Laden des Videos an den Server des Anbieters übertragen werden.', 'rrze-video'),
		'type'  => 'checkbox',
		'default' => false,
		
	    ],

	     
	],
	
       
    ];
}



/**
 * Gibt die Default-Werte eines gegebenen Feldes aus den Shortcodesettings zurück
 * @return array [description]
 */
function getShortcodeDefaults($field = ''){
    if (empty($field)) {
	return;
    }
    $settings = getShortcodeSettings();
    if (!isset($settings[$field])) {
	return;
    }
    $res = array();
    foreach ($settings[$field] as $fieldname => $value ) {
	$res[$fieldname] = $value['default'];
    }
    return $res;
}

/**
 * Gibt die Einstellungen der Parameter für Shortcode für den klassischen Editor und für Gutenberg zurück.
 * @return array [description]
 */

/*
        'youtube-support'       => '0',
        'rand'                  => ''
 */
function getShortcodeSettings(){
    return [
	    'fauvideo' => [
	       'id' => [
		    'default' => 0,
		    'label' => __( 'Id-Number des Eintrags', 'rrze-video' ),
		    'message' => __( 'Nummer der Eintrags der Videothek im Backend.', 'rrze-video' ), 
		   'field_type' => 'number',
		    'type' => 'key'
	       ],
		'url' => [
			'default' => '',
			'field_type' => 'text', // Art des Feldes im Gutenberg Editor
			'label' => __( 'URL (Video)', 'rrze-video' ),
			'type' => 'url' // Variablentyp der Eingabe
		],
		'poster' => [
			'default' => '',
			'field_type' => 'text', // Art des Feldes im Gutenberg Editor
			'label' => __( 'URL (Vorschaubild)', 'rrze-video' ),
			'type' => 'url' // Variablentyp der Eingabe
		],
		'titletag' => [
			'default' => 'h2',
			'field_type' => 'text', // Art des Feldes im Gutenberg Editor
			'label' => __( 'Titletag', 'rrze-video' ),
			'type' => 'text' // Variablentyp der Eingabe
		],

		'class' => [
			'default' => '',
			'field_type' => 'text',
			'label' => __( 'CSS Klassen, die der Shordcode erhalten soll.', 'rrze-video' ),
			'type' => 'class' 
		],
		'showtitle' => [
			'default' => false,
			'field_type' => 'bool',
			'label' => __( 'Titel über den Video anzeigen', 'rrze-video' ),
			'type' => 'bool' 
		],
		'showinfo' => [
			'default' => false,
			'field_type' => 'bool',
			'label' => __( 'Metainfo unter den Video anzeigen', 'rrze-video' ),
			'type' => 'bool' 
		],
		'show' => [
			'default' => '',
			'field_type' => 'text',
			'label' => __( 'Anzuzeigende Felder, obige Checkboxen überschreibend', 'rrze-video' ),
			'type' => 'string' 
		],
	
		
	    ],

	    

    ];
    

}

 function get_rrze_video_capabilities() {
	return [
		'edit_post'	=> 'edit_video',
		'read_post'	=> 'read_video',
		'delete_post'	=> 'delete_video',
		'edit_posts'	=> 'edit_videos',
		'edit_others_posts' => 'edit_others_videos',
		'publish_posts'	=> 'publish_videos',
		'read_private_posts' => 'read_private_videos',
		'delete_posts'	=> 'delete_videos',
		'delete_private_posts' => 'delete_private_videos',
		'delete_published_posts' => 'delete_published_videos',
		'delete_others_posts' => 'delete_others_videos',
		'edit_private_posts' => 'edit_private_videos',
		'edit_published_posts' => 'edit_published_videos'
	    ];
    }