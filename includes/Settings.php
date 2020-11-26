<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;


/**
 * Settings-Klasse
 */
class Settings {

    protected $pluginFile;
   
    /**
     * Variablen Werte zuweisen.
     * @param string $pluginFile [description]
     */
    public function __construct($pluginFile) {
        $this->pluginFile = $pluginFile;
        $this->settingsPrefix = dirname(plugin_basename($this->pluginFile)) . '-';
    }

    /**
     * Er wird ausgeführt, sobald die Klasse instanziiert wird.
     * @return void
     */
    public function onLoaded() {
	add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);
    }

    /**
     * Enqueue Skripte und Style
     * @return void
     */
    public function adminEnqueueScripts($hook) {
	wp_register_style('rrze-video-adminstyle', plugins_url('css/rrze-video-admin.css', plugin_basename($this->pluginFile)));
	wp_enqueue_style('rrze-video-adminstyle');
    }

}