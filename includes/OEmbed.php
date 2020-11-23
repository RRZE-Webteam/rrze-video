<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;
use function RRZE\Video\Config\getConstants;
use function RRZE\Video\Config\getShortcodeSettings;


class OEmbed {
    static function get_known_provider() {
        return [
            'fau'	=> [
		'domains'	=> [
			'video.uni-erlangen.de', 'video.fau.de',
			'www.video.uni-erlangen.de', 'www.video.fau.de',
			'fau.tv'
		    ],
		'api-endpoint'  => 'https://www.video.uni-erlangen.de/services/oembed'
		],
	    'youtube'	=> [
		'domains'   => [
		    'www.youtube.com', 'youtube.com', 'youtu.be',
		],
		'api-endpoint'  => 'https://www.youtube.com/oembed'
		
	    ],
	    'vimeo' => [
		'domains'   => [
		    'vimeo.com', 'player.vimeo.com'
		],
		'api-endpoint'  => 'https://vimeo.com/api/oembed.json'
	    ],
	 //    'instagram' => [
	//	'domains'   => [
	//	    'instagram.com', 'www.instagram.com','instagr.am','www.instagr.am'
	//	],
	//	'api-endpoint'  => 'https://api.instagram.com/oembed'
	 //   ]
	    // Facebook wants an access token :/
        ];
    }
    

    static function is_oembed_provider( $url ) {
	if (!isset($url)) {
	    return '';
	}
	
	$known = self::get_known_provider();
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
    
    static function get_oembed_data( $provider, $url ) {
	if (!isset($provider)) {
	    return;
	}
	
	if ($provider == 'fau') {
	    return self::sanitize_oembed_data(self::fetch_fau_video($url));
	} elseif ($provider == 'youtube') {
	    return self::sanitize_oembed_data(self::fetch_youtube_video($url));
	} else {
	    return self::sanitize_oembed_data(self::fetch_defaultoembed_video($provider, $url));
	}
	
    }
    static function fetch_defaultoembed_video( $provider, $url ) {
	$known = self::get_known_provider();
        $videodata =  array(
            'error'   => false,
            'video'   => false,
        );

        $endpoint_url = $known[$provider]['api-endpoint'].'?url='.$url;

            $oembed_url    = $endpoint_url;
            $remote_get    = wp_safe_remote_get( $oembed_url, array( 'sslverify' => true ));
            if ( is_wp_error( $remote_get ) ) {
                $videodata['error'] = $remote_get->get_error_message();
            } else {
                $videodata['video'] = json_decode( wp_remote_retrieve_body( $remote_get ), true);
            }
       

        return $videodata;

    }
    
    static function fetch_youtube_video( $url ) {
	$known = self::get_known_provider();
        $videodata =  array(
            'error'   => false,
            'video'   => false,
        );

        $endpoint_url = $known['youtube']['api-endpoint'].'?url='.$url;

            $oembed_url    = $endpoint_url;
            $remote_get    = wp_safe_remote_get( $oembed_url, array( 'sslverify' => true ));
            if ( is_wp_error( $remote_get ) ) {
                $videodata['error'] = $remote_get->get_error_message();
            } else {
                $videodata['video'] = json_decode( wp_remote_retrieve_body( $remote_get ), true);
            }
	    
	    // add Video Id for Plyr

	    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
	   $videodata['video']['v'] = $matches[1];

        return $videodata;

    }

    static function fetch_fau_video( $url ) {
	$known = self::get_known_provider();
        $fau_video =  array(
            'error'   => false,
            'video'   => false,
        );

        $fau_video_url = $known['fau']['api-endpoint'].'?url=https://www.video.uni-erlangen.de';
        preg_match( '/(clip|webplayer)\/id\/(\d+)/', $url, $matches);
        if( ! is_array( $matches ) ){
            $fau_video['error'] = 'FAU-Video URL enthält keine gültige Video Id';
        } else {
            $oembed_url    = $fau_video_url . '/' . $matches[1] . '/id/' . $matches[2] . '&format=json';
            $remote_get    = wp_safe_remote_get( $oembed_url, array( 'sslverify' => true ));
            if ( is_wp_error( $remote_get ) ) {
                $fau_video['error'] = $remote_get->get_error_message();
            } else {
                $fau_video['video'] = json_decode( wp_remote_retrieve_body( $remote_get ), true);
            }
        }

        return $fau_video;

    }

    static function sanitize_oembed_data($data) {
	// dont trust them ;)
	$urllist = array("file", "url", "preview_image", "poster", "thumbnail_url", "alternative_Video_size_large_url", "alternative_Video_size_medium_url", "transcript","provider_url");
	$textstrings = array("inLanguage", "author_name", "title", "provider_name", "type", "version");
	$textareastrings  = array("description");
	$htmllist = array("html");
	$numbers = array("width", "height", "thumbnail_width", "thumbnail_height", "alternative_Video_size_large_width", "alternative_Video_size_large_height", "alternative_Video_size_medium_width", "alternative_Video_size_medium_height");
	
	if (is_array($data)) {
	    if (isset($data['error'])) {
		$data['error'] = sanitize_text_field($data['error']);
	    }
	    if (is_array($data['video'])) {
		foreach ($data['video'] as $key => $value) {
		    if (in_array($key, $urllist)) {
			$data['video'][$key] = esc_url_raw($data['video'][$key]);
		    } elseif (in_array($key, $textstrings)) {
			$data['video'][$key] = sanitize_text_field($data['video'][$key]);
		    } elseif (in_array($key, $textareastrings)) {
			$data['video'][$key] = sanitize_textarea_field($data['video'][$key]);
		    } elseif (in_array($key, $htmllist)) {
			   // stay intact
			$data['video'][$key] = $data['video'][$key];
		    } elseif (in_array($key, $numbers)) {
			$data['video'][$key] = intval($data['video'][$key]);	
		    } else {
			$data['video'][$key] = sanitize_text_field($data['video'][$key]);
		    }
		}
	    }
	}
	return $data;
    }
}



