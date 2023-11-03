<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

class API
{
    public static function getStreamingURI($clipId)
    {
        $transient_name = 'rrze_streaming_uri_'. $clipId;
        $transient_value = get_transient($transient_name);

        if ($transient_value !== false) {
            return $transient_value;
        }

        $bearerToken = get_option('rrze_video_api_key');
        $response = wp_safe_remote_get(
            'https://api.video.uni-erlangen.de/api/v1/clips/'. $clipId,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ],
                'sslverify' => false, 
            ]
        );

        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE && $data !== null && isset($data['data']['files']['video'])) {
            $video_url = $data['data']['files']['video'];
            set_transient($transient_name, $video_url, 21600);
            return $video_url;
        }

        return;

        error_log('Unexpected response format: ' . $body);

        return null;
    }
}