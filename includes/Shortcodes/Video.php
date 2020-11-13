<?php

namespace RRZE\Video\Shortcodes;
use function RRZE\Video\Config\getShortcodeSettings;
use function RRZE\Video\Config\getShortcodeDefaults;


use RRZE\Video\Helper;

defined('ABSPATH') || exit;

/**
 * Define Shortcodes for Standort Custom Type
 */
class Video extends Shortcodes {
    protected $pluginFile;
    private $settings = '';
    private $shortcodesettings = '';
    
    public function __construct($pluginFile, $settings) {
	$this->pluginFile = $pluginFile;
	$this->settings = $settings;	
	$this->shortcodesettings = getShortcodeSettings();
    }


    public function onLoaded() {	
	add_shortcode('fauvideo', [$this, 'shortcode_video'], 10, 2);
    }
   

    public static function shortcode_video( $atts, $content = null) {
	$defaults = getShortcodeDefaults('fauvideo');         
	$arguments = shortcode_atts($defaults, $atts);

	$content = '';
	$content .= Helper::get_html_var_dump($defaults);
	$content .= Helper::get_html_var_dump($arguments);
	return $content;
	
    }
   
}

