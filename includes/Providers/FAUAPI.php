<?php

namespace RRZE\Video\Providers;

defined('ABSPATH') || exit;
use RRZE\Video\Utils\FSD_Data_Encryption;

class FAUAPI
{
    public static function getStreamingURI($clipId)
    {
        $transient_name = 'rrze_streaming_uri_' . $clipId;
        $transient_value = get_transient($transient_name);
        $environment = wp_get_environment_type();

        // Determine the IP address to use
        if ($environment === 'local' || $environment === 'development') {
            // Use client IP in development
            $ip = self::getClientIP();
        } else {
            // Use server IP in production
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $ip_long = ip2long($ip);

        if ($transient_value !== false) {
            return $transient_value;
        }

        $data_encryption = new FSD_Data_Encryption();
        $encrypted_api_key = get_option('rrze_video_api_key');
        $bearerToken = $data_encryption->decrypt($encrypted_api_key);

        $request_url = 'https://www.api.video.uni-erlangen.de/api/v1/clips/' . $clipId . '/?for=' . $ip_long;

        $response = wp_safe_remote_get(
            $request_url,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ]
            ]
        );

        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE && $data !== null && isset($data['data']['files']['video'])) {
            $video_data = [
                'url' => $data['data']['files']['video'],
                'vtt' => $data['data']['files']['vtt'],
                'audio' => $data['data']['files']['audio'],
                'title' => $data['data']['title'],
                'description' => $data['data']['description'],
                'language' => $data['data']['language'],
                'poster' => $data['data']['files']['posterImage'],
            ];

            set_transient($transient_name, $video_data, 21600);
            return $video_data;
        }

        error_log('Unexpected response format: ' . $body);
        return null;
    }

    /**
     * Get the client IP address.
     *
     * @return string|null Client IP address or null if not available.
     */
    private static function getClientIP()
    {
        $ip = null;

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
