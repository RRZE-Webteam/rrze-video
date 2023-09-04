<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

/**
 * Class Shortcode
 * @package RRZE\Video
 */
class Shortcode
{
    private static $instance = null;

    private $settings = [];

    /**
     * Singleton
     * @return object
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        include_once(plugin()->getPath() . "settings/shortcode.php");
        $this->settings = $settings ?? [];
    }

    public function loaded()
    {
        add_shortcode('rrzevideo', [$this, 'shortcodeVideo']);
        add_shortcode('fauvideo', [$this, 'shortcodeVideo']);
    }

    /**
     * Gibt die Default-Werte eines gegebenen Feldes aus den Shortcodesettings zurück
     * @return array [description]
     */
    public function getShortcodeDefaults($field = '')
    {
        $res = [];
        if (empty($field) || empty($this->settings[$field])) {
            return $res;
        }
        foreach ($this->settings[$field] as $name => $value) {
            $res[$name] = $value['default'];
        }
        return $res;
    }

    /**
     * Sanitize shortcode atts & display shortcode output. Also adds the aspect-ratio as inline-style
     * @param array $atts
     * @return array
     */
    public function shortcodeVideo($atts)
    {
        $defaults = $this->getShortcodeDefaults('rrzevideo');
        $args = shortcode_atts($defaults, $atts);
        $args = $this->translateParameters($args);
        $args = $this->sanitizeArgs($args, 'rrzevideo');


        return apply_filters(
            'rrze_video_player_content',
            Player::instance()->get_player($args),
            $args
        );
    }

    // Copies old direkt parameters of the shortcode into show/hide-Parameter
    private function translateParameters($args)
    {
        if (!isset($args)) {
            return;
        }
        $show = '';
        if (isset($args['show'])) {
            $show = $args['show'];
        }

        // First we copy arguments, that stay as they was
        $validpars = 'id, url, class, titletag, poster, rand, aspectratio, textAlign';

        $oldargs = explode(',', $validpars);
        foreach ($oldargs as $value) {
            $key = esc_attr(trim($value));
            if ((!empty($key)) && (isset($args[$key]))) {
                $res[$key] = $args[$key];
            }
        }

        $oldparams = 'showtitle,showinfo';
        $oldargs = explode(',', $oldparams);
        foreach ($oldargs as $value) {
            $key = esc_attr(strtolower(trim($value)));
            $newkey = preg_replace('/^show/', '', $key);
            if ((!empty($key)) && (isset($args[$key]))) {
                if (($args[$key] == 1)
                    || ($args[$key] == "ja")
                    || ($args[$key] == "jo")
                    || ($args[$key] == "yes")
                    || ($args[$key] == "true")
                    || ($args[$key] == "+")
                    || ($args[$key] == "x")
                ) {

                    if (!empty($show)) {
                        $show .= ', ' . $newkey;
                    } else {
                        $show = $newkey;
                    }
                }
            }
        }
        if (!empty($show)) {
            $res['show'] = $show;
        } else {
            $res['show'] = '';
        }

        return $res;
    }

    /**
     * Sanitized all arguments for shortcodes,
     * based on the type defined in settings for the shortcodes.
     * @param array $args
     * @param string $field
     * @return array Empty array or sanitized arguments array.
     */
    public function sanitizeArgs(array $args, string $field = ''): array
    {
        if (empty($args) || empty($field) || empty($this->settings[$field])) {
            return [];
        }
        $settings = $this->settings[$field];

        foreach ($args as $name => $value) {
            $type = $settings[$name]['type'] ?? '';
            switch ($type) {
                case 'textarea':
                    $value = sanitize_textarea_field($value);
                    break;
                case 'text':
                case 'string':
                    $value = sanitize_text_field($value);
                    break;
                case 'aspectratio':
                    $value = sanitize_text_field($value);

                    // Check if the value matches the regex
                    if (!preg_match("/^(\d*\.?\d+)\/(\d*\.?\d+)$/", $value)) {
                        Helper::debug('The following invalid aspect ratio was entered inside a video shortcode: ' . $value . '. Using the default value 16/9 instead.', 'i');
                        $value = '16/9';
                    } 
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
                        || ($value == "x")
                    ) {
                        $value = true;
                    } elseif (($value == 0)
                        || empty($value)
                        || ($value == "-")
                        || ($value == "nein")
                        || ($value == "nope")
                        || ($value == "false")
                        || ($value == "no")
                    ) {
                        $value = false;
                    } else {
                        $value = true;
                    }
                    break;
                default:
                    // nix aendern
                    break;
            }
            $args[$name] = $value;
        }
        return $args;
    }
}
