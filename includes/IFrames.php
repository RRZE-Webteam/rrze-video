<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;


class IFrames {
    static function get_known_iframe_provider() {
        return [
            'br'	=> [
		'domains'	=> [
			'www.br.de', 
			'br.de'
		    ],
		'home'	=> 'https://www.br.de',
		'name'	=> 'BR Mediathek'
		],
	   'twitch' => [
		'domains'	=> [
			'www.twitch.tv'
		    ],
		'home'	=> 'https://www.twitch.tv',
		'name'	=> 'twitch'
		],
        ];
    }
    
    static function get_iframe( $url ) {
	if (!isset($url)) {
	    return '';
	}
	$provider = self::is_iframe_provider($url);
	if ($provider) {
	    return self::get_iframe_data($provider, $url);
	} else {
	     $videodata =  array(
		'error'   => __('Der Video-Provider ist unbekannt. Ein Embedding des Videos ist daher nicht möglich.','rrze-video'),
		'video'   => false,
		);
	     return $videodata;
	}
    }
    
    static function is_iframe_provider( $url ) {
	if (!isset($url)) {
	    return '';
	}
	
	$known = self::get_known_iframe_provider();
	$url = esc_url_raw($url);
	$searchdom = parse_url($url, PHP_URL_HOST);
	$res = '';
	
	foreach ($known as $name => $pdata) {
	    foreach ($pdata['domains'] as $dom) {
		if ($dom == $searchdom) {
		    $res = $name;
		    break;
		}
	    }
	}
	return $res;
    }
    
    static function get_iframe_data( $provider, $url ) {
	if (!isset($provider)) {
	    return;
	}

	if ($provider == 'br') {
	    return self::fetch_iframe_br($url);
	} elseif ($provider == 'twitch') {
	    return self::fetch_iframe_twitch($url);
	}
	return;
	
    }
    
     static function fetch_iframe_twitch( $url ) {
	$known = self::get_known_iframe_provider();
        $videodata =  array(
            'error'   => false,
            'video'   => false,
        );
	// example Video URL: https://www.twitch.tv/videos/442305299
	// Docs: https://dev.twitch.tv/docs/embed/video-and-clips
	
	if (preg_match('/\/videos\/[a-z0-9\-:\.]+$/',$url)) {
	    $embedurl = preg_replace('/\/videos\/([a-z0-9\-:\.]+)$/','/?video=$1',$url);
	    $siteurl = get_site_url();
	    $parent = parse_url($siteurl, PHP_URL_HOST);
	    $embedurl .= '&parent='.$parent;
	    $videodata['video']['html'] = '<iframe class="remoteembed" frameborder="0" scrolling="no" allowfullscreen="true" src="'.$embedurl.'"></iframe>';
	    $videodata['video']['orig_url'] = $url;
	    $videodata['video']['embed_url'] = $embedurl;
	    $videodata['video']['provider_url'] = $known['twitch']['home'];
	    $videodata['video']['provider_name'] = $known['twitch']['name'];
	    $videodata['video']['provider'] = 'twitch';
	} else {
	    $videodata['error'] = __('Die URL für das Twitch Video ist ungültig oder fehlerhaft.','rrze-video');
	}
        return $videodata;
    }
    static function fetch_iframe_br( $url ) {
	$known = self::get_known_iframe_provider();
        $videodata =  array(
            'error'   => false,
            'video'   => false,
        );

	if (preg_match('/\/mediathek\/video\/[a-z0-9\-:\.]+$/',$url)) {
	    $embedurl = preg_replace('/\/mediathek\/video\/([a-z0-9\-:\.]+)$/','/mediathek/embed/$1',$url);

	    $videodata['video']['html'] = '<iframe class="remoteembed" allowfullscreen src="'.$embedurl.'"></iframe>';
	    $videodata['video']['orig_url'] = $url;
	    $videodata['video']['embed_url'] = $embedurl;
	    $videodata['video']['provider_url'] = $known['br']['home'];
	    $videodata['video']['provider_name'] = $known['br']['name'];
	    $videodata['video']['provider'] = 'br';
	} else {
	    $videodata['error'] = __('Die URL für die BR Mediathek ist ungültig oder fehlerhaft.','rrze-video');
	}
        return $videodata;
    }


}



