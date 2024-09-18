<?php

namespace RRZE\Video\WordPress;

defined('ABSPATH') || exit;

use RRZE\Video\Player\OEmbed;
use RRZE\Video\Utils;
use RRZE\Video\Utils\Helper;
use WP_Error;

class RESTAPI
{
    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('rrze-video/v1', '/process-url', array(
                'methods' => 'POST',
                'callback' => [$this, 'process_url_callback'],
                'permission_callback' => 'is_user_logged_in',
            ));

            register_rest_route('rrze-video/v1', '/process-id', array(
                'methods' => 'POST',
                'callback' => [$this, 'process_id_callback'],
                'permission_callback' => 'is_user_logged_in',
            ));
        });
    }

    public function process_url_callback($request)
    {
        $url = $request->get_param('url');

        if ($isoembed = OEmbed::is_oembed_provider($url)) {
            $oembeddata = OEmbed::get_oembed_data($isoembed, $url);

            if (!empty($oembeddata['error'])) {
                return rest_ensure_response([
                    'error' => $oembeddata['error'],
                    'message' => 'An error occurred while processing the oEmbed data.',
                ]);
            } elseif (empty($oembeddata['video'])) {
                return rest_ensure_response([
                    'error' => 'no_video_data',
                    'message' => 'No video data was found.',
                ]);
            } else {
                $arguments['video'] = $oembeddata['video'];
                $arguments['oembed_api_url'] = $oembeddata['oembed_api_url'] ?? '';
                $arguments['oembed_api_error'] = $oembeddata['error'] ?? '';
                return rest_ensure_response($arguments);
            }
        }

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return new WP_Error('invalid_url', 'Die übermittelte URL ist ungültig.', array('status' => 400));
        }

        $response = [
            'message' => 'Die URL wurde erfolgreich verarbeitet.',
            'processed_url' => $url,
        ];

        return rest_ensure_response($response);
    }

    public function process_id_callback($request)
    {
        $id = $request->get_param('rand') ?: $request->get_param('id');

        // Set `rand` only if `rand` was provided.
        if ($request->get_param('rand')) {
            $arguments['rand'] = $id;
        }

        // Set `id` only if `id` was provided.
        if ($request->get_param('id')) {
            $arguments['id'] = $id;
        }

        Utils\Utils::getUrlByIdOrRandom($arguments);
        $url = $arguments['url'] ?? null;

        // Check if URL is valid
        if (!$url) {
            return new WP_Error('invalid_id', 'Die übermittelte ID ist ungültig.', ['status' => 400]);
        }

        // Check if the URL is from an oEmbed provider
        if ($isoembed = OEmbed::is_oembed_provider($url)) {
            $oembeddata = OEmbed::get_oembed_data($isoembed, $url);

            // Handle oEmbed data response and errors
            if (!empty($oembeddata['error'])) {
                return rest_ensure_response([
                    'error' => $oembeddata['error'],
                    'message' => 'An error occurred while processing the oEmbed data.',
                ]);
            } elseif (empty($oembeddata['video'])) {
                return rest_ensure_response([
                    'error' => 'no_video_data',
                    'message' => 'No video data was found.',
                ]);
            } else {
                $response = [
                    'video' => $oembeddata['video'],
                    'oembed_api_url' => $oembeddata['oembed_api_url'] ?? '',
                    'oembed_api_error' => $oembeddata['error'] ?? '',
                ];

                return rest_ensure_response($response);
            }
        }

        // Return a successful response if the URL is not from an oEmbed provider
        $response = [
            'message' => 'Die ID wurde erfolgreich verarbeitet.',
            'processed_url' => $url,
        ];

        return rest_ensure_response($response);
    }
}
