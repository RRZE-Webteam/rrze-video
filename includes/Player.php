<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;
use function RRZE\Video\Config\getConstants;
use function RRZE\Video\Config\getShortcodeSettings;
use RRZE\Video\OEmbed;

class Player {
    
    
    private static $counter = 0;

    public function __construct() {
      self::$counter++;
    }
    
    function get_player_html($provider, $data, $id = '') {
	$res = '';
	$providerlist = OEmbed::get_known_provider();
	if ((!isset($provider)) || (!isset($providerlist[$provider]))) {	 
	    $res .= '<div class="rrze-video alert clearfix clear alert-danger">';
	    $res .= __('Es wurde kein gültiger Videoprovider gefunden. Das Video kann daher nicht abgespielt werden oder konnte nicht erkannt werden.', 'rrze-video');
	    $res .= '</div>';	    
	    
	    return $res;
	}
	
	if (!empty($data['error'])) {
	     $res .= '<div class="rrze-video alert clearfix clear alert-danger">';
	     $res .= '<strong>';
	     $res .= __('fehler beim Abruf des Videos:','rrze-video');
	     $res .= '</strong><br>';
	    $res .= $data['error'];
	    $res .= '</div>';
	    return $res;
	}
	

	$thumbnail = '';
	if ($data['poster']) {
	    $thumbnail = $data['poster'];
	} elseif ($data['video']['thumbnail_url']) {
	    $thumbnail = $data['video']['thumbnail_url'];    
	} elseif ($data['video']['preview_image']) {
	    $thumbnail = $data['video']['preview_image'];
	}
	
	
	$res .= '<div class="rrze-video">';
	
	if ($id == '') {
		    // create Random number to make a uniq class name
		    // This is need to display more as one video embed in the same page
		    $id = self::$counter++;
		}
	$classname = 'plyr-videonum-'.$id;
	
	
	if ($provider == 'youtube') {
	    $id = $data['video']['v'];
	    
	    $res .= '<div class="'.$classname.'" data-plyr-provider="youtube" data-plyr-embed-id="'.$data['video']['v'].'"></div>';
	    
	} elseif ($provider == 'vimeo') {    
   
	    $res .= '<div class="'.$classname.'" data-plyr-provider="vimeo" data-plyr-embed-id="'.$data['video']['video_id'].'"></div>';

	} elseif ($provider == 'fau') {

	    $res .= '<video class="'.$classname.'" playsinline controls';
	    
	    if ($data['video']['title']) {
		$res .= ' data-plyr-config=\'{"title": "'.$data['video']['title'].'"}\'';
	    }
	    
	    if ($thumbnail) {
		$res .= ' data-poster="'.$thumbnail.'"';
	    }
	    
	    
	    $res .= '>';
	    
	    $path = parse_url($data['video']['file'], PHP_URL_PATH);
	    $ext = pathinfo($path, PATHINFO_EXTENSION);
	   
	    $res .= '<source src="'.$data['video']['file'].'" type="video/'.$ext.'">';
	    if ($ext == 'm4v') {
		// add also mp4 definiton for the same path due to old browsers
		$res .= '<source src="'.$data['video']['file'].'" type="video/mp4">';
	    }

	    
	    
	    if ($data['video']['transcript']) {
		  $res .= '<track kind="captions" label="English captions" src="'.$data['video']['transcript'].'" default />';
	    }
	    $res .= '</video>';
	} else {
	     $res .= '<div class="alert clearfix clear alert-danger">';
	    $res .= __('Videoprovider fehlerhaft definiert.', 'rrze-video');
	    $res .= '</div>';	   
	}
	
	
	
	 $res .= '</div>';
	  return $res;
    }

    
    
}