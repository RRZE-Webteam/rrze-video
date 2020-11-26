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
	// Nothing... yet
    }


  

}