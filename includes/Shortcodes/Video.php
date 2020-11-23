<?php

namespace RRZE\Video\Shortcodes;
use function RRZE\Video\Config\getShortcodeSettings;
use function RRZE\Video\Config\getShortcodeDefaults;
use function RRZE\Video\Data\sanitize_shortcodeargs;

use RRZE\Video\Data;
use RRZE\Video\OEmbed;
use RRZE\Video\Helper;
use RRZE\Video\Player;
use RRZE\Video\Main;

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
	add_shortcode('rrzevideo', [$this, 'shortcode_video'], 10, 2);
    }
   

    public static function shortcode_video( $atts, $content = null) {
	$defaults = getShortcodeDefaults('fauvideo');         
	$arguments = shortcode_atts($defaults, $atts);
	$arguments = Data::sanitize_shortcodeargs($arguments);
	
	$content = '';
	$content .= Helper::get_html_var_dump($arguments);

	if ($arguments['url']) {
	    // check for oembed
	    $isoembed = OEmbed::is_oembed_provider($arguments['url']);
	    
	    $content .= "Oembed: ".$isoembed;
	    $oembeddata = OEmbed::get_oembed_data($isoembed,$arguments['url']);
	    
	    $content .= Helper::get_html_var_dump($oembeddata);
	    $content .= Player::get_player_html($isoembed, $oembeddata);
		
	   Main::enqueueFrontendStyles('rrze-video');  
	}
	return $content;
	
    }
   
}

