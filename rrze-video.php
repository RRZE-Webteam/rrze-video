<?php
/**
Plugin Name: RRZE Video Plugin
Plugin URI: https://github.com/RRZE-Webteam/rrze-video
Description: This is a video plugin to show videos on pages and in the social media footer.
Version: 3.0.1-2
Author: RRZE-Webteam
Author URI: http://blogs.fau.de/webworking/
License: GNU GPLv2
License URI: https://gnu.org/licenses/gpl.html
Text Domain: rrze-video
 */

	

namespace RRZE_Video;

defined('ABSPATH') || exit;

use RRZE_Video\Main;

// Laden der Konfigurationsdatei
require_once __DIR__ . '/config/config.php';


// Automatische Laden von Klassen.
spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__;
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

const RRZE_PHP_VERSION = '7.4';
const RRZE_WP_VERSION = '5.3';

// Registriert die Plugin-Funktion, die bei Aktivierung des Plugins ausgeführt werden soll.
register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
// Registriert die Plugin-Funktion, die ausgeführt werden soll, wenn das Plugin deaktiviert wird.
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');
// Wird aufgerufen, sobald alle aktivierten Plugins geladen wurden.
add_action('plugins_loaded', __NAMESPACE__ . '\loaded');


/**
 * Einbindung der Sprachdateien.
 */
function loadTextDomain() {
    load_plugin_textdomain('rrze-video', false, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/**
 * Überprüft die Systemvoraussetzungen.
 */
function systemRequirements() {
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        /* Übersetzer: 1: aktuelle PHP-Version, 2: erforderliche PHP-Version */
        $error = sprintf(__('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'rrze-video'), PHP_VERSION, RRZE_PHP_VERSION);
    } elseif (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        /* Übersetzer: 1: aktuelle WP-Version, 2: erforderliche WP-Version */
        $error = sprintf(__('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'rrze-video'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }
    return $error;
}

/**
 * Wird nach der Aktivierung des Plugins ausgeführt.
 */
function activation() {
    // Sprachdateien werden eingebunden.
    loadTextDomain();

    // Überprüft die minimal erforderliche PHP- u. WP-Version.
    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if ($error = systemRequirements()) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die($error);
    }
    
    
   // CPT-Capabilities für die Administrator- und Redakteurs-Rolle zuweisen
    rrze_video_set_caps_to_roles();		    
	

}

/**
 * Wird durchgeführt, nachdem das Plugin deaktiviert wurde.
 */
function deactivation() {  
    rrze_video_remove_caps();
    flush_rewrite_rules();    
}

/**
 * Wird durchgeführt, nachdem das WP-Grundsystem hochgefahren
 * und alle Plugins eingebunden wurden.
 */
function loaded() {
    // Sprachdateien werden eingebunden.
    loadTextDomain();

    // Überprüft die Systemvoraussetzungen.
    if ($error = systemRequirements()) {
        add_action('admin_init', function () use ($error) {
            $pluginData = get_plugin_data(__FILE__);
            $pluginName = $pluginData['Name'];
            $tag = is_plugin_active_for_network(plugin_basename(__FILE__)) ? 'network_admin_notices' : 'admin_notices';
            add_action($tag, function () use ($pluginName, $error) {
                printf(
                    '<div class="notice notice-error"><p>' . __('Plugins: %1$s: %2$s', 'rrze-video') . '</p></div>',
                    esc_html($pluginName),
                    esc_html($error)
                );
            });
        });
        // Das Plugin wird nicht mehr ausgeführt.
        return;
    }

    // Hauptklasse (Main) wird instanziiert.	
    $main = new Main(__FILE__);
    $main->onLoaded();
    

    
}

 function rrze_video_remove_caps() {    
	$roles = array('editor','administrator');   
	$caps_person = Config\get_rrze_video_capabilities();
	foreach($roles as $the_role) {
	    $role = get_role($the_role);
	    if (isset($role)) {
		foreach($caps_person as $cap => $value) {
		    $role->remove_cap($value);
		}  
	    }
	}    
    }

    
    function rrze_video_set_caps_to_roles() {    
	$roles = array('editor','administrator');   
	$caps_person = Config\get_rrze_video_capabilities();

	foreach($roles as $the_role) {
	    $role = get_role($the_role);
	    if (isset($role)) {
		foreach($caps_person as $cap => $value) {
		    $role->add_cap($value);
		}  
	   }
	}    
	return;
    }




    
/*


namespace RRZE\PostVideo;

const RRZE_PHP_VERSION = '7.4';
const RRZE_WP_VERSION  = '5.3';

add_action('plugins_loaded', 'RRZE\PostVideo\init');
register_activation_hook(__FILE__, 'RRZE\PostVideo\activation');
register_deactivation_hook(__FILE__, 'RRZE\PostVideo\deactivation');

function init() {

    textdomain();

    include_once('includes/posttype/rrze-video-posttype.php');
    include_once('includes/posttype/rrze-video-posttype-templates.php');
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

    add_action( 'wp_enqueue_scripts', 'RRZE\PostVideo\custom_libraries_scripts');
    add_action( 'admin_notices', 'RRZE\PostVideo\video_admin_notice');
    add_action( 'admin_enqueue_scripts', 'RRZE\PostVideo\rrze_video_admin_styles');

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
        $error = sprintf(__('Your server is running PHP version %s. Please upgrade at least to PHP version %s.', 'rrze-video'), PHP_VERSION, RRZE_PHP_VERSION);
    }

    if (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(__('Your Wordpress version is %s. Please upgrade at least to Wordpress version %s.', 'rrze-video'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }

    // check auf altes FAU-Video plugin
    if ( is_plugin_active('fau-video/fau-video.php') ) {
        $error = sprintf( __('An older version of the FAU video plugin is active. Please deactivate it before enabling the RRZE Video plugin.', 'rrze-video') );
    }


    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if (!empty($error)) {
        deactivate_plugins(plugin_basename(__FILE__), FALSE, TRUE);
        die( $error );
    }
}

function rrze_video_admin_styles() {
    wp_enqueue_style('rrze-video-admin-styles', plugins_url( 'rrze-video/css/rrze-video-admin.css', dirname(__FILE__) ) );
}

function custom_libraries_scripts() {

    global $post;

    wp_register_script( 'rrze-main-js', plugins_url( 'rrze-video/js/rrze-ajax.js', dirname(__FILE__)), array('jquery'),'', true);
    wp_register_style( 'mediaelementplayercss', includes_url( 'js/mediaelement/mediaelementplayer.min.css', dirname(__FILE__) ) );
    wp_register_script( 'mediaelementplayerjs', includes_url( 'js/mediaelement/mediaelement-and-player.min.js', dirname(__FILE__)), array('jquery'),'', true);
    wp_register_style( 'rrze-video-css', plugins_url( 'rrze-video/css/rrze-video.css', dirname(__FILE__) ) );
    wp_register_script( 'rrze-video-js', plugins_url('rrze-video/js/scripts.min.js', dirname(__FILE__)), array('jquery'),'' , true);

    wp_localize_script( 'rrze-main-js', 'videoajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

}

*/