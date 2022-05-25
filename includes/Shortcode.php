<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

use function RRZE\Video\Config\getShortcodeDefaults;

/**
 * Class Shortcode
 * @package RRZE\Video
 */
class Shortcode
{
    public function __construct()
    {
        add_shortcode('fauvideo', [$this, 'shortcode_video'], 10, 2);
        add_shortcode('rrzevideo', [$this, 'shortcode_video'], 10, 2);
    }

    public static function shortcode_video($atts, $content = null)
    {
        $defaults = getShortcodeDefaults('fauvideo');
        $arguments = shortcode_atts($defaults, $atts);
        $arguments = self::translate_parameters($arguments);
        $arguments = Data::sanitize_shortcodeargs($arguments);

        return apply_filters(
            'rrze_video_player_content',
            Player::instance()->get_player($arguments),
            $arguments
        );
    }

    // Copies old direkt parameters of the shortcode into show/hide-Parameter
    private static function translate_parameters($arguments)
    {
        if (!isset($arguments)) {
            return;
        }
        $show = '';
        if (isset($arguments['show'])) {
            $show = $arguments['show'];
        }


        // First we copy arguments, that stay as they was
        $validpars = 'id, url, class, titletag, poster, rand';

        $oldargs = explode(',', $validpars);
        foreach ($oldargs as $value) {
            $key = esc_attr(trim($value));
            if ((!empty($key)) && (isset($arguments[$key]))) {
                $res[$key] = $arguments[$key];
            }
        }

        $oldparams = 'showtitle,showinfo';
        $oldargs = explode(',', $oldparams);
        foreach ($oldargs as $value) {
            $key = esc_attr(strtolower(trim($value)));
            $newkey = preg_replace('/^show/', '', $key);
            if ((!empty($key)) && (isset($arguments[$key]))) {
                if (($arguments[$key] == 1)
                    || ($arguments[$key] == "ja")
                    || ($arguments[$key] == "true")
                    || ($arguments[$key] == "+")
                    || ($arguments[$key] == "x")
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
}
