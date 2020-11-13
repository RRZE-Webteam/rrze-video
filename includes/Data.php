<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;
use function RRZE\Video\Config\getConstants;


class Data {
    
    
    private static function get_viewsettings($lookup = 'constants') {
	$settings = new Settings(PLUGIN_FILE);
	$settings->onLoaded();
	$options = $settings->options;
	    
	$viewopt = array();
	    
	foreach ($options as $section => $field) {
	    if ($lookup == 'sidebar') {
		if (substr($section,0,7) === 'sidebar') {
		    $keyname = preg_replace('/sidebar_/i','',$section);
		    $viewopt[$keyname] = $options[$section];
		}
	    } else {
		if (substr($section,0,9) === 'constants') {
		    $keyname = preg_replace('/constants_/i','',$section);
		    $viewopt[$keyname] = $options[$section];
		}
	    } 
	} 
	return $viewopt;
    }
    
    function is_fau_video( $url )  {
	
	$constants = getConstants();
	if (!isset($constants)) {
	    return false;
	}
	
	$fau_video_domains = $constants['oembed-provider']['fau']['domains'];
        $is_fau_video = false;
     
        if ( ! empty( wp_parse_url( $url ) ) ) {
            $test_url    = wp_parse_url( $url );
            $test_domain = preg_replace( '/^www\./', '', $test_url['host'] );
            if ( in_array( $test_domain, $fau_video_domains ) ) {
               $is_fau_video = true;
            }
        }
        return $is_fau_video;
    }

    function fetch_fau_video( $url ) {

        $fau_video =  array(
            'error'   => false,
            'video'   => false,
        );

        $fau_video_url = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de';
        preg_match( '/(clip|webplayer)\/id\/(\d+)/', $url, $matches);
        if( ! is_array( $matches ) ){
            $fau_video['error'] = 'no match in url';
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

    
}