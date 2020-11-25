<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;
use function RRZE\Video\Config\getConstants;
use function RRZE\Video\Config\getShortcodeSettings;
use RRZE\Video\OEmbed;

class Player {
    private static $counter = 1;

    public function __construct() {
      self::$counter++;
    }
    
    static function get_player_html($provider, $data, $id = '') {
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
	$showvals = explode(',', $data['show']);
	$showtitle = $showmeta =  $showdesc = $showlink = false;
	
	foreach ($showvals as $value) {
	    $key = esc_attr(trim($value));
	    switch($key) {
		case 'title':
		    $showtitle = true;
		    break;
		case 'info':
		    $showmeta = true;
		    $showlink = true;
		    $showdesc = true;
		    break;
		case 'meta':
		    $showmeta = true;
		     break;
		case 'desc':
		    $showdesc = true;
		     break;
		case 'link':
		    $showlink = true;
		     break;
	    }
	    
	}

	$thumbnail = '';
	if (isset($data['poster']) && (!empty($data['poster']))) {
	    $poster = $data['poster'];
	} elseif (isset($data['video']['preview_image']) && (!empty($data['video']['preview_image']))) {
	    $poster = $data['video']['preview_image'];    
	} elseif (isset($data['video']['thumbnail_url']) && (!empty($data['video']['thumbnail_url']))) {
	    $poster = $data['video']['thumbnail_url'];    
	}
	  $lang = $hreflang = '';
	
	    if (isset($data['inLanguage'])) {
		$lang = $data['inLanguage'];
		$hreflang = explode("-",$lang)[0];
	    } elseif (isset($data['language'])) {
		$lang = $data['language'];
		$hreflang = explode("-",$lang)[0];
	    }
	
	
	$res .= '<div class="rrze-video';
	
	if (isset($data['class']) && (!empty($data['class']))) {
	    $res .= ' '.$data['class'];
	}
	$res .= '">';
	
	if ($showtitle) {
	    $titletag = $data['titletag'];
	    $res .= '<'.$titletag.'>'.$data['video']['title'].'</'.$titletag.'>';
	}
	
	
	if ($id == '') {
		    // create Random number to make a uniq class name
		    // This is need to display more as one video embed in the same page
		    $id = self::$counter++;
		}
	$classname = 'plyr-instance plyr-videonum-'.$id;
	
	
	if ($provider == 'youtube') {
	    
	    $classname = 'plyr-videonum-'.$id;
	    $res .= '<div class="youtube-video '.$classname.'"';
	    $res .= ' itemscope itemtype="https://schema.org/Movie"';
	    $res .= '>';
	    $res .= self::get_html_structuredmeta($data);
	    $res .= '<div class="plyr-instance" data-plyr-provider="youtube" data-plyr-embed-id="'.$data['video']['v'].'"';
	    
	    $res .= ' data-plyr-config=\'{';
	    $res .= ' "youtube": "{ noCookie: true }"';
	    if ($data['video']['title']) {
		$res .= ', "title": "'.$data['video']['title'].'"';
	    } 
	    $res .= '}\'';
	    $res .= '></div>';
	    $res .= '</div>';
	} elseif ($provider == 'vimeo') {    
	    $classname = 'plyr-videonum-'.$id;
	    $res .= '<div class="vimeo-video '.$classname.'"';
	    $res .= ' itemscope itemtype="https://schema.org/Movie"';
	    $res .= '>';
	    $res .= self::get_html_structuredmeta($data);
	    $res .= '<div class="plyr-instance" data-plyr-provider="vimeo" data-plyr-embed-id="'.$data['video']['video_id'].'"';
	    if ($data['video']['title']) {
		$res .= ' data-plyr-config=\'{"title": "'.$data['video']['title'].'"}\'';
	    } 
	    $res .= '></div>';
	    $res .= '</div>';
	} elseif ($provider == 'fau') {
	    $classname = 'plyr-instance plyr-videonum-'.$id;
	    $res .= '<video class="'.$classname.'" playsinline controls crossorigin="anonymous"';
	    
	    if ($data['video']['title']) {
		$res .= ' data-plyr-config=\'{"title": "'.$data['video']['title'].'"}\'';
	    } 
	    if ($poster) {
		$res .= ' poster="'.$poster.'" data-poster="'.$poster.'"';
	    }
	    if (isset($data['video']['width'])) {
		$res .= ' width="'.$data['video']['width'].'"';
	    }
	     if (isset($data['video']['height'])) {
		$res .= ' height="'.$data['video']['height'].'"';
	    }
	    
	    $res .= ' itemscope itemtype="https://schema.org/Movie"';
	    $res .= '>';
	    
	    $res .= self::get_html_structuredmeta($data);
	    
	    $path = parse_url($data['video']['file'], PHP_URL_PATH);
	    $ext = pathinfo($path, PATHINFO_EXTENSION);
	   
	    $res .= '<source src="'.$data['video']['file'].'" type="video/'.$ext.'">';
	    if ($ext == 'm4v') {
		// add also mp4 definiton for the same path due to old browsers
		$res .= '<source src="'.$data['video']['file'].'" type="video/mp4">';
	    }

	    if (isset($data['video']['alternative_Video_size_large']) && isset($data['video']['alternative_Video_size_large_url'])) {
		$path = parse_url($data['video']['alternative_Video_size_large_url'], PHP_URL_PATH);
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$res .= '<source src="'.$data['video']['alternative_Video_size_large_url'].'" type="video/'.$ext.'" size="'.$data['video']['alternative_Video_size_large_width'].'">';
	    }
	    if (isset($data['video']['alternative_Video_size_medium']) && isset($data['video']['alternative_Video_size_medium_url'])) {
		$path = parse_url($data['video']['alternative_Video_size_medium_url'], PHP_URL_PATH);
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$res .= '<source src="'.$data['video']['alternative_Video_size_medium_url'].'" type="video/'.$ext.'" size="'.$data['video']['alternative_Video_size_medium_width'].'">';
	    }
	    
	    if (isset($data['video']['transcript']) && (!empty($data['video']['transcript']))) {
		  $res .= '<track kind="captions" label="'.__('Audiotranskription','rrze-video').'" src="'.$data['video']['transcript'].'" default';
		  if ($hreflang) {
		      $res .= ' hreflang="'.$hreflang.'"';
		  }
		  $res .= '>';
	    }
	   // $res .= '<div class="alert alert-warning">';
	    $res .= __('Ihr Browser unterstützt leider keine HTML5 Videoformate. ', 'rrze-video');

	    if (isset($data['url'])) {
		$res .= 'Rufen Sie daher das Video <a href="'.$data['url'].'">'.$data['video']['title'].'</a> im Videoportal auf.';
	    } else {
		$res .= 'Rufen Sie die Videodatei <a href="'.$data['video']['file'].'">'.$data['video']['title'].'</a> direkt auf.';
	    }
	    

	    $res .= '</video>';
	} else {
	    $res .= '<div class="alert clearfix clear alert-danger">';
	    $res .= __('Videoprovider fehlerhaft definiert.', 'rrze-video');
	    $res .= '</div>';	
	    return $res;
	}
	
	if (($showdesc) && isset($data['video']['description']) && (!empty($data['video']['description']))) {
	    $res .= '<p class="desc">'.$data['video']['description'].'</p>';
	}
	if ($showmeta) {
	    $meta = '';
	
	    if (isset($data['video']['author_name']) && (!empty($data['video']['author_name']))) {
		$meta .= '<dt>'.__('Autor','rrze-video').'</dt><dd>';
		 if (isset($data['video']['author_url']) && (!empty($data['video']['author_url']))) {
		      $meta .= '<a href="'.data['video']['author_url'].'">';
		 }
		 $meta .= $data['video']['author_name'];
		 if (isset($data['video']['author_url']) && (!empty($data['video']['author_url']))) {
		      $meta .= '</a>';
		 }
		$meta .=  '</dd>';    
	    }
	    if (isset($data['url']) && (!empty($data['url']))) {
		$meta .= '<dt>'.__('Quelle','rrze-video').'</dt><dd><a href="'.$data['url'].'">'.$data['url'].'</a></dd>';    
	    }
	    if (isset($data['video']['provider_name']) && (!empty($data['video']['provider_name']))) {
		$meta .= '<dt>'.__('Provider','rrze-video').'</dt><dd>';
		if (isset($data['video']['provider_url']) && (!empty($data['video']['provider_url']))) {
		    $meta .= '<a href="'.data['video']['provider_url'].'">';
		}
		$meta .= $data['video']['provider_name'];
		
		if (isset($data['video']['provider_url']) && (!empty($data['video']['provider_url']))) {
		    $meta .= '</a>';
		}
		$meta .= '</dd>';    
	    }
	    if (!empty($meta)) {
		$res .= '<dl class="meta">'.$meta.'</dl>';
	    }
	} elseif ($showlink && isset($data['url']) && (!empty($data['url']))) {
	    $res .= '<p class="link">'.__('Quelle','rrze-video').': <a href="'.$data['url'].'">'.$data['url'].'</a></p>';    
	}

	
	 $res .= '</div>';
	  return $res;
    }

    
    static function get_html_structuredmeta($data) {
	    if (isset($data['video']['title'])) {
		 $res = '<meta itemprop="name" content="'.$data['video']['title'].'">';
	    }
	   if (isset($data['poster']) && (!empty($data['poster']))) {
		$poster = $data['poster'];
	    } elseif (isset($data['video']['preview_image']) && (!empty($data['video']['preview_image']))) {
		$poster = $data['video']['preview_image'];    
	    } elseif (isset($data['video']['thumbnail_url']) && (!empty($data['video']['thumbnail_url']))) {
		$poster = $data['video']['thumbnail_url'];    
	    }
	    $lang = $hreflang = '';
	
	    if (isset($data['inLanguage'])) {
		$lang = $data['inLanguage'];
		$hreflang = explode("-",$lang)[0];
	    } elseif (isset($data['language'])) {
		$lang = $data['language'];
		$hreflang = explode("-",$lang)[0];
	    }
	
	    
	    $res .= '<meta itemprop="image" content="'.$poster.'">';
	    if (isset($data['video']['upload_date'])) {
		 $res .= '<meta itemprop="dateCreated" content="'.$data['video']['upload_date'].'">';
	    }
	    if (isset($data['video']['author_name']) && (!empty($data['video']['author_name']))) {
		 $res .= '<meta itemprop="director" content="'.$data['video']['author_name'].'">';
	    }
	    if ($hreflang) {
		$res .= '<meta itemprop="inLanguage" content="'.$hreflang.'">';
	    }
	   if (isset($data['video']['provider_name']) && (!empty($data['video']['provider_name']))) {
		$res .= '<meta itemprop="provider" content="'.$data['video']['provider_name'].'">';
	    }
	    if (isset($data['video']['thumbnail_url']) && ($data['video']['thumbnail_url'] != $poster) ) {
		$res .= '<meta itemprop="thumbnailUrl" content="'.$data['video']['thumbnail_url'].'">';
	    }
	    if (isset($data['video']['duration'])) {
		$res .= '<meta itemprop="duration" content="'.$data['video']['duration'].'">';
	    }
	    if (isset($data['video']['version'])) {
		$res .= '<meta itemprop="version" content="'.$data['video']['version'].'">';
	    }
	    if (isset($data['video']['description']) && (!empty($data['video']['description']))) {
		$res .= '<meta itemprop="abstract" content="'.$data['video']['description'].'">';
	    }
	    return $res;
    }
    
}