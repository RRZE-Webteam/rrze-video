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
	$oldargs = $arguments;
	$arguments = self::translate_parameters($arguments);
	$arguments = Data::sanitize_shortcodeargs($arguments);
	
	$content = '';

	if ($arguments['url']) {
	    // check for oembed
	    $isoembed = OEmbed::is_oembed_provider($arguments['url']);
	    
	    if (empty($isoembed)) {
		$content .= '<div class="rrze-video alert clearfix clear alert-danger">';
		$content .= '<strong>';
		$content .= __('Unbekannte Videoquelle','rrze-video');
		$content .= '</strong><br>';
		$content .= __('Der folgenden Adresse konnte keinem bekannten Videoprovider zugeordnet werden oder dieser verfügt nicht über eine geeignete Standard-API (oEmbed) zum Abruf von Videos.','rrze-video');
		$content .= __('Bitte rufen Sie das Video daher auf, indem Sie direkt den folgenden Link folgen:','rrze-video');
		$content .= ' <a href="'.$arguments['url'].'" rel="nofollow">'.$arguments['url'].'</a>';
		$content .= '</div>';
		
	    } else {
	//	$content .= "Oembed: ".$isoembed;
		$oembeddata = OEmbed::get_oembed_data($isoembed,$arguments['url']);

		if (isset($oembeddata['error']) && (!empty($oembeddata['error']))) {

		    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
		    $content .= '<strong>';
		    $content .= __('Fehler beim Abruf des Videos:','rrze-video');
		    $content .= '</strong><br>';
		    $content .= $oembeddata['error'];
		    $content .= '</div>';

		} else {
		    $arguments['video'] = $oembeddata['video'];
	//	    $content .= Helper::get_html_var_dump($arguments);
		    $content .= Player::get_player_html($isoembed, $arguments);

		    Main::enqueueFrontendStyles(true);  


		}
	    }
	}
	return $content;
	
    }
    // Copies old direkt paraneters of the shortcode into show/hide-Parameter
    private static function translate_parameters($arguments) {
	if (!isset($arguments)) {
	    return;
	}
	$show = '';
	if (isset($arguments['show'])) {
	   $show = $arguments['show'];
	}
	
	
	// First we copy arguments, that stay as they was
	$validpars = 'id, url, class, titletag, poster';
	
	$oldargs = explode(',', $validpars);
	foreach ($oldargs as $value) {
	    $key = esc_attr(trim($value));
	    if ((!empty($key)) && (isset($arguments[$key]))) {
		$res[$key] = $arguments[$key];
	    }
	}
	
	$oldparams = 'showtitle,showinfo';
	$oldargs = explode(',', $oldparams);
	foreach ($oldargs as $value) {
	    $key = esc_attr(strtolower(trim($value)));
	    $newkey = preg_replace('/^show/','',$key);
	    if ((!empty($key)) && (isset($arguments[$key]))) {
		if (($arguments[$key] == 1) 
		    || ($arguments[$key] == "ja")
		    || ($arguments[$key] == "true")
		    || ($arguments[$key] == "+")
		    || ($arguments[$key] == "x")) {
		    
		    if (!empty($show)) {
			 $show .= ', '.$newkey;
		    } else {
			 $show = $newkey;
		    }
		}
	    }
	}
	if (!empty($show)) {    
	    $res['show'] = $show;
	} else {
	    $res['show'] = '';
	}

	return $res;
    }
        
   
}

