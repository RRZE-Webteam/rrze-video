<?php

namespace RRZE\Video\WordPress;
defined('ABSPATH') || exit;

use RRZE\Video\Player\OEmbed;
use RRZE\Video\Utils;
use RRZE\Video\Utils\Helper;
use WP_Error;

class RESTAPI
{
    public function __construct() {
        add_action('rest_api_init', function () {
            register_rest_route('custom/v1', '/process-url', array(
                'methods' => 'POST',
                'callback' => [$this, 'process_url_callback'], // Referenzierung der Methode
                'permission_callback' => 'is_user_logged_in',
            ));
        });
    }

    public function process_url_callback($request) {
        $url = $request->get_param('url');

        if ($isoembed = OEmbed::is_oembed_provider($url)) {
            $oembeddata = OEmbed::get_oembed_data($isoembed, $url);

            if (!empty($oembeddata['error'])) {
                // Return the error in the response
                return rest_ensure_response([
                    'error' => $oembeddata['error'],
                    'message' => 'An error occurred while processing the oEmbed data.',
                ]);
            } elseif (empty($oembeddata['video'])) {
                // Return a response indicating no video data was found
                return rest_ensure_response([
                    'error' => 'no_video_data',
                    'message' => 'No video data was found.',
                ]);
            } else {
                $arguments['video'] = $oembeddata['video'];
                $arguments['oembed_api_url'] = $oembeddata['oembed_api_url'] ?? '';
                $arguments['oembed_api_error'] = $oembeddata['error'] ?? '';
                
                // Return the arguments array as the response
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
}
