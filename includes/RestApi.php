<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

/**
 * Class Shortcode
 * @package RRZE\Video
 */
class RestApi
{
    public function __construct()
    {
        // add_action('rest_api_init', [$this, 'restApiInit']);
    }

    public function restApiInit()
    {
        register_rest_route('RRZE/Video/v1', '/rrze_video/', array(
            'methods'               => \WP_REST_Server::EDITABLE,
            'callback'              => [$this, 'apiGetVideo'],
            'permission_callback'   => [$this, 'apiAuthoringPermissionsCheck'],
            'args'                  => [
                'attributes' => [
                    'required' => true,
                    'type' => 'array',
                    'description' => 'Attributes of the video block',
                    'sanitize_callback' => [$this, 'sanitizeAttributesArray'],
                    'validate_callback' => [$this, 'validateAttributesArray'],
                ],
            ],
        ));
    }

    public function apiAuthoringPermissionsCheck($request)
    {
        if (!current_user_can('edit_posts')) {
            return new \WP_Error(
                'rrze_newsletter_rest_forbidden',
                esc_html__('You cannot use this resource.', 'rrze-video'),
                [
                    'status' => 403,
                ]
            );
        }
        return true;
    }

    public function sanitizeAttributesArray($attributes)
    {
        // You'll need to replace this with code that properly sanitizes
        // the $attributes array based on what it's expected to contain.
        return $attributes;
    }

    public function validateAttributesArray($attributes)
    {
        // Again, you'll need to replace this with code that validates
        // the $attributes array based on what it's expected to contain.
        return is_array($attributes);
    }

    public function apiGetVideo($request)
    {
        $attributes = $request->get_param('attributes');
        $result = Shortcode::instance()->shortcodeVideo($attributes);

        return $result;
    }
}
