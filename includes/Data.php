<?php

namespace RRZE_Video;
defined('ABSPATH') || exit;


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
    
}