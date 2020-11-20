<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;
use function RRZE\Video\Config\getConstants;
use function RRZE\Video\Config\getShortcodeSettings;


class Data {
    
    // sanitized all arguments for shortcodes, based on the type defined in 
    // config for the shortcodes
    
    function sanitize_shortcodeargs( $args, $shortcode = 'fauvideo' ) {
	$shortcodesettings = getShortcodeSettings();
	if (!empty($shortcode)) {
	    $arglist = $shortcodesettings[$shortcode];
	    if (isset($arglist)) {
		foreach ($args as $name => $value) {
		    $type = '';
		    if (isset($arglist[$name])) {
			if (isset($arglist[$name]['type'])) {
			    $type =  $arglist[$name]['type'];
			}
		    }
		    switch ($type) {
			case 'textarea':
			    $value = sanitize_textarea_field($value);
			    break;
			case 'text':
			    $value = sanitize_text_field($value);
			    break;
			case 'string':
			case 'slug':
			    $value = sanitize_title($value);
			    break;
			case 'class':
			case 'classname':
			    $value = sanitize_html_class($value);
			    break;
			case 'email':
			    $value = sanitize_email($value);
			    break;
			case 'url':
			    $value = esc_url_raw($value);
			    break;
			case 'key':		
			    $value = sanitize_key($value);
			    break;
			case 'number':
			case 'integer':
			    $value = intval($value);
			    break;
			case 'boolean':
			case 'bool':
			    if (($value == 1) 
				|| ($value == "ja")
				|| ($value == "yo")
				|| ($value == "yes")
				|| ($value == "true")
				|| ($value == "+")
				|| ($value == "x")) {
				$value = true;
			    } elseif (($value == 0) 
				|| empty($value)
				|| ($value == "-")
				|| ($value == "nein")
				|| ($value == "nope")
				|| ($value == "false")
				|| ($value == "no")) {
				$value = false;
			    } else {
				$value = true;
				// cause its not empty
			    }
			    break;
			default:
			    // nix aendern
		    }
		    $args[$name] = $value;
		} 
		   
	    }
	}
	return $args;
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