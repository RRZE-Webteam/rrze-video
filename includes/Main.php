<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\Settings;
use RRZE\Video\Taxonomy\Taxonomy;
use RRZE\Video\Templates\Templates;
use RRZE\Video\Shortcodes\Shortcodes;
use RRZE\Video\Widgets\Widgets;
use RRZE\Video\Metaboxes\Metaboxes;
use RRZE\Video\Helper;
use function RRZE\Video\Config\getConstants;
	
	

/**
 * Hauptklasse (Main)
 */

class Main {
    protected $pluginFile;
    private $settings = '';
      /*    
    public static $options;
    
    protected static $instance = null;
    */
    public function __construct($pluginFile) {
        $this->pluginFile = $pluginFile;
    }

    /**
     * Es wird ausgeführt, sobald die Klasse instanziiert wird.
     */
    public function onLoaded() {
	 add_action('wp_enqueue_scripts', [$this, 'registerFrontendStyles']);

	 // Settings-Klasse wird instanziiert.

        $settings = new Settings($this->pluginFile);
        $settings->onLoaded();
	$this->settings = $settings;
	// $this->options = $settings->options;



	// Posttypes 
        $taxonomies = new Taxonomy($this->pluginFile, $settings);
        $taxonomies->onLoaded();
	

	// Add Metaboxes
	$metaboxes = new Metaboxes($this->pluginFile, $settings); 
        $metaboxes->onLoaded();
	
		// Add Shortcodes
        $shortcodes = new Shortcodes($this->pluginFile, $settings); 
        $shortcodes->onLoaded();
	
		 /*
	// Posttypes 
        $plugins = new Plugins($this->pluginFile, $settings);
        $plugins->onLoaded();

	
	// Templates 
        $templates = new Templates($this->pluginFile, $settings);
        $templates->onLoaded();
		
	
	// Backend Setting pages
        $backend = new BackendMenu($this->pluginFile, $settings);
        $backend->onLoaded();
	

		
	
	



		
	// Add Widget
        $widget = new Widgets($this->pluginFile, $settings); 
        $widget->onLoaded();
	
	*/
	return;			
    }
    
    
    
    
    public function registerFrontendStyles() {
	wp_register_style('rrze-video', plugins_url('css/rrze-video.css', plugin_basename($this->pluginFile)));
    }
        
    
    public static function enqueueFrontendStyles() {
	 wp_enqueue_style('rrze-video');  
    }
    

	
}


