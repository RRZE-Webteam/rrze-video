<?php
/**
Plugin Name: RRZE Video
Plugin URI: https://github.com/RRZE-Webteam/rrze-video
Description: Plugin zum Embedding von Videos 
Version: 3.1.6
Author: RRZE-Webteam
Author URI: http://blogs.fau.de/webworking/
License: GNU GPLv2
License URI: https://gnu.org/licenses/gpl.html
Text Domain: rrze-video
 */

	

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\Main;

// Laden der Konfigurationsdatei
require_once __DIR__ . '/config/config.php';


// Autoloader (PSR-4)
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
        require $file;
    }
});

const RRZE_PHP_VERSION = '7.4';
const RRZE_WP_VERSION = '5.5';

register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');
add_action('plugins_loaded', __NAMESPACE__ . '\loaded');

/**
 * load translations
 */
function loadTextdomain()
{
    load_plugin_textdomain('rrze-video', false, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/**
 * Check system
 * @return string [description]
 */
function systemRequirements(): string
{
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        $error = sprintf(__('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'rrze-rsvp'), PHP_VERSION, RRZE_PHP_VERSION);
    } elseif (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(__('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'rrze-rsvp'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }
    return $error;
}

/**
 * Create things on activation
 */
function activation()
{
    loadTextdomain();

    if ($error = systemRequirements()) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(sprintf(__('Plugins: %1$s: %2$s', 'rrze-log'), plugin_basename(__FILE__), $error));
    }

    Roles::addRoleCaps();

    flush_rewrite_rules();
}

/**
 * Remove Roles and Caps
 */
function deactivation() {
    Roles::removeRoleCaps();

    flush_rewrite_rules();
}

/**
 * Initialise Plugin Object
 * @return object
 */
function plugin(): object
{
    static $instance;
    if (null === $instance) {
        $instance = new Plugin(__FILE__);
    }
    return $instance;
}

/**
 * Start the Plugin
 * @return void
 */
function loaded()
{
    // add_action('init', __NAMESPACE__ . '\loadTextdomain');
    loadTextdomain();

    plugin()->onLoaded();

    if ($error = systemRequirements()) {
        add_action('admin_init', function () use ($error) {
            if (current_user_can('activate_plugins')) {
                $pluginData = get_plugin_data(plugin()->getFile());
                $pluginName = $pluginData['Name'];
                $tag = is_plugin_active_for_network(plugin()->getBaseName()) ? 'network_admin_notices' : 'admin_notices';
                add_action($tag, function () use ($pluginName, $error) {
                    printf(
                        '<div class="notice notice-error"><p>' . __('Plugins: %1$s: %2$s', 'rrze-rsvp') . '</p></div>',
                        esc_html($pluginName),
                        esc_html($error)
                    );
                });
            }
        });
        return;
    }

    $main = new Main(__FILE__);
    $main->onLoaded();
}


    