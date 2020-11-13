<?php

namespace RRZE\Video\Metaboxes;

defined('ABSPATH') || exit;

use RRZE\Video\Main;
use RRZE\Video\Metaboxes\Video;


class Metaboxes  {
    protected $pluginFile;
    private $settings = '';
    public $prefix = ''; // rrze_video_';
      
    public function __construct($pluginFile, $settings) {
        $this->pluginFile = $pluginFile;
        $this->settings = $settings;
    }

    public function onLoaded()     {
	

	require_once(plugin_dir_path($this->pluginFile) . 'vendor/cmb2/init.php');
   

	$videometabox = new Video($this->pluginFile,  $this->settings);
	$videometabox->onLoaded();

    }
    

}