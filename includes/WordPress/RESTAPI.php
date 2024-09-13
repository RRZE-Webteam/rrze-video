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

            register_rest_route('rrze-video/v1', '/get-url-by-id', array(
                'methods' => 'POST',
                'callback' => [$this, 'get_url_by_id_callback'],
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

    public function get_url_by_id_callback($request)
    {
        // Retrieve the ID or random key from the request parameters
        $id = $request->get_param('id');
        $rand = $request->get_param('rand');

        // Validate the ID
        if (empty($id) || !is_numeric($id)) {
            return new WP_Error('invalid_id', 'Die übermittelte ID ist ungültig.', array('status' => 400));
        }

        // Prepare the arguments array with ID or random key
        $arguments = [
            'id' => (int) $id,
            'rand' => $rand,
        ];

        // Use the utility function to get URL by ID or randomly
        Utils\Utils::getUrlByIdOrRandom($arguments);

        // Check if URL was retrieved successfully
        if (empty($arguments['url'])) {
            return new WP_Error('url_not_found', 'Keine URL wurde für die angegebene ID gefunden.', array('status' => 404));
        }

        $url = $arguments['url'];

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
    }
}
