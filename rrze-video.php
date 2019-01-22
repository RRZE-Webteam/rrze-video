<?php

/*
Plugin Name: RRZE Video Plugin
Plugin URI: https://github.com/RRZE-Webteam/rrze-video
Description: This is a video plugin to show videos on pages and in the social media footer.
Version: 1.3.2
Author: RRZE-Webteam
Author URI: http://blogs.fau.de/webworking/
License: GNU GPLv2
License URI: https://gnu.org/licenses/gpl.html
Text Domain: rrze-video

RRZE Video Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

RRZE Video Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

namespace RRZE\PostVideo;

const RRZE_PHP_VERSION = '7.0';
const RRZE_WP_VERSION = '4.9';

add_action('plugins_loaded', 'RRZE\PostVideo\init');
register_activation_hook(__FILE__, 'RRZE\PostVideo\activation');
register_deactivation_hook(__FILE__, 'RRZE\PostVideo\deactivation');

function init() {

    textdomain();

    include_once('includes/posttype/rrze-video-posttype.php');
    include_once('includes/taxonomy/rrze-video-taxonomy.php');
    include_once('includes/metabox/rrze-video-add-metaboxes.php');
    include_once('includes/metabox/rrze-video-save-metaboxes.php');
    include_once('includes/posttype/rrze-video-admin-view.php');
    include_once('includes/functions/rrze-video-functions.php');
    include_once('includes/ajax/rrze-video-player-js.php');
    include_once('settings/rrze-video-settings-page.php');
    include_once('shortcodes/rrze-video-shortcode.php');
    include_once('widgets/rrze-video-widget.php');
    include_once('help/rrze-video-plugin-tabmenu.php');
    require_once('includes/endpoint/video-endpoint.php');
    new VideoEndpoint;



    add_action( 'wp_enqueue_scripts', 'RRZE\PostVideo\custom_libraries_scripts');
    add_action( 'admin_notices', 'RRZE\PostVideo\video_admin_notice');

}

function textdomain() {
    load_plugin_textdomain('rrze-video', FALSE, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

function activation() {
    textdomain();

    system_requirements();

    require_once __DIR__ . '/includes/endpoint/video-endpoint.php';
    $obj = new VideoEndpoint;
    $obj->default_options();
    $obj->rewrite();

    flush_rewrite_rules();
}

function deactivation() {
    flush_rewrite_rules();
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

    global $post;

    $theme_name = wp_get_theme();
   // $THEMES_WITH_CSS = array("FAU-Einrichtungen", "FAU-Einrichtungen [BETA]", "FAU-Medfak", "FAU-Natfak", "FAU-Philfak", "FAU-RWfak", "FAU-Techfak", "RRZE 2015");

    wp_register_script( 'rrze-main-js', plugins_url( 'rrze-video/assets/js/rrze-ajax.js', dirname(__FILE__)), array('jquery'),'', true);
    wp_register_style( 'mediaelementplayercss', includes_url( 'js/mediaelement/mediaelementplayer.min.css', dirname(__FILE__) ) );
    wp_register_script( 'mediaelementplayerjs', includes_url( 'js/mediaelement/mediaelement-and-player.min.js', dirname(__FILE__)), array('jquery'),'', true);
    wp_register_style( 'rrze-video-css', plugins_url( 'rrze-video/assets/css/rrze-video.css', dirname(__FILE__) ) );
    wp_register_script( 'rrze-video-js', plugins_url('rrze-video/assets/js/scripts.min.js', dirname(__FILE__)), array('jquery'),'' , true);

    /*if (!in_array($theme_name, $THEMES_WITH_CSS)) {
        wp_enqueue_style( 'stylescss' );
    }*/

    wp_localize_script( 'rrze-main-js', 'videoajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

}
