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

		    $content .= Helper::get_html_var_dump($arguments);
		    $content .= Player::get_player_html($isoembed, $arguments);

		    Main::enqueueFrontendStyles('rrze-video');  


		}
	    }
	}
	return $content;
	
    }
   
   
}

