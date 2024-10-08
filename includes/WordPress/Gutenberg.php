<?php

namespace RRZE\Video\WordPress;

defined('ABSPATH') || exit;
use RRZE\Video\Shortcode;

/**
 * Class Shortcode
 * @package RRZE\Video
 */
class Gutenberg
{
    /**
     * Renders the video block for the frontend
     *
     * @param [type] $attributes
     * @return void
     */
    public static function rrze_video_render_block($attributes)
    {
        $result = Shortcode::instance()->shortcodeVideo($attributes);
        return $result;
    }
}
