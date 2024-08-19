<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

class Error
{
           /**
     * Handles errors related to the absence of video content.
     * 
     * This function generates an error message based on the absence of a valid video ID or URL.
     *
     * @param array $arguments Associative array containing video details.
     * @param mixed $id        The video or container ID.
     * @return string Error message wrapped in an HTML div with error styling.
     */
    public static function handleNoVideoError($arguments, $id)
    {
        $content = '<div class="rrze-video rrze-video-container-' . $id . ' alert clearfix clear alert-danger">';
        $content .= '<strong>';
        $content .= __('Error getting the video', 'rrze-video');
        $content .= '</strong><br>';
        if (empty($arguments['id'])) {
            $content .= __('The video ID used is invalid or could not be assigned to a video.', 'rrze-video');
        } elseif ($arguments['rand']) {
            $content .= __('No video from the specified category could be found.', 'rrze-video');
            $content .= ': "' . $arguments['rand'] . '"';
        } else {
            $content .= __('Neither a valid ID nor a valid URL was specified for a video.', 'rrze-video');
        }
        $content .= '</div>';

        return $content;
    }

        /**
     * Returns an error message wrapped in an HTML div element.
     * 
     * @param string $message The error message to display.
     * @return string Error message wrapped in an HTML div with error styling.
     */
    public static function handleError($message)
    {
        return '<div class="rrze-video alert clearfix clear alert-danger">' . $message . '</div>';
    }

        /**
     * Generates an error message with a header, wrapped in an HTML div element.
     * 
     * @param mixed $id      The video or container ID.
     * @param string $header The header/title of the error message.
     * @param string $message The detailed error message.
     * @return string Error message with a header wrapped in an HTML div with error styling.
     */
    public static function generateErrorContent($id, $header, $message)
    {
        return '<div class="rrze-video rrze-video-container-' . $id . ' alert clearfix clear alert-danger">' .
            '<strong>' . $header . '</strong><br>' . $message . '</div>';
    }
}