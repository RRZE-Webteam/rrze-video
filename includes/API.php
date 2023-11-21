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

        $data_encryption = new FSD_Data_Encryption();
        $encrypted_api_key = get_option('rrze_video_api_key');
        $bearerToken = $data_encryption->decrypt($encrypted_api_key);
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
            // Helper::debug('API response: ' . $body);
            $video_data = [
                'url' => $data['data']['files']['video'],
                'vtt' => $data['data']['files']['vtt'],
                'audio' => $data['data']['files']['audio'],
                'title' => $data['data']['title'],
                'description' => $data['data']['description'],
                'language' => $data['data']['language'],
            ];

            set_transient($transient_name, $video_data, 21600);
            return $video_data;
        }

        return;

        error_log('Unexpected response format: ' . $body);

        return null;
    }
}