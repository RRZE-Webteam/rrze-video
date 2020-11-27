<?php

namespace RRZE\Video\Metaboxes;

defined('ABSPATH') || exit;

/**
 * Define Metaboxes for Kontakt-Edit
 */
class Video extends Metaboxes {

    protected $pluginFile;
    private $settings = '';
    
    public function __construct($pluginFile, $settings) {
        $this->pluginFile = $pluginFile;
        $this->settings = $settings;	
    }

    public function onLoaded()    {
	add_filter('cmb2_meta_boxes', array( $this, 'cmb2_video_metaboxes') );
    }
   
    

    public function cmb2_video_metaboxes( $meta_boxes ) {
	$prefix = $this->prefix;

	
	// Meta-Box Standortinformation - fau_standort_info
	$meta_boxes['rrze-video-metadata'] = array(
	    'id' => 'rrze-video-metadata',
	    'title' => __( 'Video-Daten', 'rrze-video' ),
	    'object_types' => array('video'), // post type
	    //'show_on' => array( 'key' => 'submenu-slug', 'value' => 'kontakt' ),        
	    'context' => 'normal',
	    'priority' => 'default',
	    'fields' => array(
		array(
		    'name' => __('URL', 'rrze-video'),
		    'desc' => __('Webadresse (URL) zum Video auf dem verwendeten Videoportal (FAU-Videoportal, YouTube, Vimeo oder andere).','rrze-video'),
		    'type' => 'text_url',
		    'id' => $prefix . 'url',
		    'default'	=> ''
		),
	/*	
		array(
		    'name' => __('Beschreibung', 'rrze-video'),
		    'desc' => __('', 'rrze-video'),
		    'type' => 'textarea_small',
		    'id' => $prefix . 'description',
		),
	  */
	 
	    )
	);

	return $meta_boxes;
    }
}