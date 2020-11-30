<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;
use function RRZE\Video\Config\getConstants;
use function RRZE\Video\Config\getShortcodeSettings;


class Data {
    
    // sanitized all arguments for shortcodes, based on the type defined in 
    // config for the shortcodes
    
    static function sanitize_shortcodeargs( $args, $shortcode = 'fauvideo' ) {
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
			case 'string':	    
			    $value = sanitize_text_field($value);
			    break;
			
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
    
   
    
}