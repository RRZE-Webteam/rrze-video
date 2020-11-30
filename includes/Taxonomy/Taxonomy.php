<?php

namespace RRZE\Video\Taxonomy;

defined('ABSPATH') || exit;

use RRZE\Video\Main;
use RRZE\Video\Taxonomy\Video;
/**
 * Laden und definieren der Posttypes
 */
class Taxonomy extends Main {
    protected $pluginFile;
    private $settings = '';
    
    public function __construct($pluginFile, $settings) {
        $this->pluginFile = $pluginFile;
        $this->settings = $settings;
    }

    public function onLoaded() {
        $video_posttype = new Video( $this->pluginFile,  $this->settings);
        $video_posttype->onLoaded();
	
	
    }
}
