<?php

/*
Plugin Name: RRZE Video Plugin
Plugin URI: https://github.com/RRZE-Webteam/rrze-video
Description: This is a video plugin to show videos on pages and in the social media footer.
Version: 1.0.1
Author: RRZE-Webteam
Author URI: http://blogs.fau.de/webworking/
License: GNU GPLv2
License URI: https://gnu.org/licenses/gpl.html
Text Domain: rrze-video

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

namespace RRZE\PostVideo;

const RRZE_PHP_VERSION = '5.5';
const RRZE_WP_VERSION = '4.7';
    
add_action('plugins_loaded', 'RRZE\PostVideo\init');
register_activation_hook(__FILE__, 'RRZE\PostVideo\activation');

function init() {
    
    textdomain();
    
    include_once('includes/posttype/rrze-video-posttype.php');
    include_once('includes/taxonomy/rrze-video-taxonomy.php');
    include_once('includes/metabox/rrze-video-add-metaboxes.php');
    include_once('includes/metabox/rrze-video-save-metaboxes.php');
    include_once('includes/posttype/rrze-video-admin-view.php');
    include_once('shortcodes/rrze-video-shortcode.php');
    include_once('widgets/rrze-video-widget.php'); 
    include_once('help/rrze-video-widget-tabmenu.php');
    include_once('help/rrze-video-plugin-tabmenu.php');
    
    
    
    add_action( 'wp_enqueue_scripts', 'RRZE\PostVideo\custom_libraries_scripts');
    add_action( 'body_class', 'RRZE\PostVideo\add_class_to_body');
    add_action( 'admin_notices', 'RRZE\PostVideo\video_admin_notice');
    
}

function textdomain() {
    load_plugin_textdomain('rrze-video', FALSE, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

function activation() {
    textdomain();
    system_requirements();
    
}

function system_requirements() {
    $error = '';

    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        $error = sprintf(__('Your server is running PHP version %s. Please upgrade at least to PHP version %s.', 'rrze-test'), PHP_VERSION, RRZE_PHP_VERSION);
    }

    if (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(__('Your Wordpress version is %s. Please upgrade at least to Wordpress version %s.', 'rrze-test'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }

    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if (!empty($error)) {
        deactivate_plugins(plugin_basename(__FILE__), FALSE, TRUE);
        wp_die($error);
    }
}

function custom_libraries_scripts() {
   
    wp_deregister_script( 'jquery');
    wp_register_script( 'jquery', plugins_url( 'rrze-video/assets/js/jquery.min.js', dirname(__FILE__) ),'','',true);
    wp_enqueue_script( 'jquery' );
    wp_register_style( 'bootstrapcss', plugins_url( 'rrze-video/assets/css/bootstrap.css', dirname(__FILE__) ) );
    wp_enqueue_style( 'bootstrapcss' );
    wp_register_script( 'bootstrapjs', plugins_url( 'rrze-video/assets/js/bootstrap.js', dirname(__FILE__)), array('jquery'),'', true);
    wp_enqueue_script( 'bootstrapjs' );
    wp_register_style( 'mediaelementplayercss', includes_url( 'js/mediaelement/mediaelementplayer.min.css', dirname(__FILE__) ) );
    wp_enqueue_style( 'mediaelementplayercss' );
    wp_register_script( 'mediaelementplayerjs', includes_url( 'js/mediaelement/mediaelement-and-player.min.js', dirname(__FILE__)), array('jquery'),'', true);
    wp_enqueue_script( 'mediaelementplayerjs' );
    wp_register_style( 'stylescss', plugins_url( 'rrze-video/assets/css/styles.css', dirname(__FILE__) ) );
    wp_enqueue_style( 'stylescss' );
    wp_register_script( 'myjs', plugins_url('rrze-video/assets/js/script.js', dirname(__FILE__)), array('jquery'),'' , true);
    wp_enqueue_script( 'myjs' );
}

function add_class_to_body() {
    
    $classes[] = 'is-mobile';
    
    return $classes;
    
}