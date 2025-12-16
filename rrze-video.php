<?php
/*
Plugin Name:    RRZE Video
Plugin URI:     https://github.com/RRZE-Webteam/rrze-video
Description:    Embedding videos via a shortcode or widget based on the Plyr video player.
Version:        5.0.11
Author:         RRZE-Webteam
Author URI:     http://blogs.fau.de/webworking/
License:        GNU General Public License Version 3
License URI:    https://www.gnu.org/licenses/gpl-3.0.html
Domain Path:    /languages
Text Domain:    rrze-video
*/

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\Utils\Plugin;
use RRZE\Video\UI\Gutenberg;

const RRZE_PHP_VERSION = '7.4';
const RRZE_WP_VERSION = '6.0';

/**
 * Composer autoload
 */
// require_once __DIR__ . '/vendor/autoload.php';
// add_action(
//     'doing_it_wrong_run',
//     static function ( $function_name ) {
//         if ( '_load_textdomain_just_in_time' === $function_name ) {
//             $backtrace = debug_backtrace();
//             error_log( print_r( $backtrace, true ) );
//         }
//     }
// );
/**
 * SPL Autoloader (PSR-4).
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__;
    $baseDir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Register plugin hooks.
register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');
add_action('init', fn() => load_plugin_textdomain('rrze-video', false, dirname(plugin_basename(__FILE__)) . '/languages'), 1);
add_action('init', __NAMESPACE__ . '\create_block_rrze_video_block_init', 10);
add_action('plugins_loaded', __NAMESPACE__ . '\loaded');

/**
 * System requirements verification.
 * @return string Return an error message.
 */
function systemRequirements(): string
{
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        $error = sprintf(
        /* translators: 1: Server PHP version number, 2: Required PHP version number. */
            'The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.',
            PHP_VERSION,
            RRZE_PHP_VERSION
        );
    } elseif (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(
        /* translators: 1: Server WordPress version number, 2: Required WordPress version number. */
            'The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.',
            $GLOBALS['wp_version'],
            RRZE_WP_VERSION
        );
    }
    return $error;
}

/**
 * Activation callback function.
 */
function activation()
{
    if ($error = systemRequirements()) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            sprintf(
            /* translators: 1: The plugin basename, 2: The error string. (No domain used here) */
                'Plugins: %1$s: %2$s',
                plugin_basename(__FILE__),
                $error
            )
        );
    } else {
        UI\Roles::addRoleCaps();
        flush_rewrite_rules();
    }
}

/**
 * Deactivation callback function.
 * Remove Roles and Caps.
 */
function deactivation()
{
    UI\Roles::removeRoleCaps();
    flush_rewrite_rules();
}

/**
 * Instantiate Plugin class.
 * @return object Plugin
 */
function plugin()
{
    static $instance;
    if (null === $instance) {
        $instance = new Plugin(__FILE__);
    }

    return $instance;
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_rrze_video_block_init()
{
    $gutenberg_instance = new Gutenberg();

    register_block_type(__DIR__ . '/build/blocks', [
        'render_callback' => [$gutenberg_instance, 'rrze_video_render_block']
    ]);

    $script_handle = generate_block_asset_handle('rrze/rrze-video', 'editorScript');
    wp_set_script_translations($script_handle, 'rrze-video', plugin_dir_path(__FILE__) . 'languages');
}

/**
 * Execute on 'plugins_loaded' API/action.
 * @return void
 */
function loaded(): void
{
    plugin()->loaded();
    if ($error = systemRequirements()) {
        add_action('admin_init', function () use ($error) {
            if (current_user_can('activate_plugins')) {
                $pluginData = get_plugin_data(plugin()->getFile(), true, false);
                $pluginName = $pluginData['Name'];
                $tag = is_plugin_active_for_network(plugin()->getBaseName()) ? 'network_admin_notices' : 'admin_notices';
                add_action($tag, function () use ($pluginName, $error) {
                    printf(
                        '<div class="notice notice-error"><p>' .
                        /* translators: 1: The plugin name, 2: The error string. */
                        'Plugins: %1$s: %2$s' .
                        '</p></div>',
                        esc_html($pluginName),
                        esc_html($error)
                    );
                });
            }
        });
        return;
    }
    new Main;

}
