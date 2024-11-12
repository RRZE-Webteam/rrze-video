<?php

namespace RRZE\Video\Player;

defined('ABSPATH') || exit;
defined('META_EXPIRATION') || define('META_EXPIRATION', 60 * 60 * 4);

use RRZE\Video\Utils\Helper;

class OEmbed
{
    public static function get_known_provider()
    {
        return [
            'fau'    => [
                'domains'    => [
                    'video.uni-erlangen.de',
                    'video.fau.de',
                    'www.video.uni-erlangen.de',
                    'www.video.fau.de',
                    'fau.tv',
                    'www.fau.tv',
                ],
                'home'    => 'https://www.fau.tv',
                'api-endpoint'  => 'https://www.fau.tv/services/oembed'
            ],
            'youtube'    => [
                'domains'   => [
                    'www.youtube.com', 'youtube.com', 'youtu.be',
                ],
                'home'    => 'https://www.youtube.com',
                'api-endpoint'  => 'https://www.youtube.com/oembed'

            ],
            'vimeo' => [
                'domains'   => [
                    'vimeo.com', 'player.vimeo.com'
                ],
                'home'    => 'https://vimeo.com',
                'api-endpoint'  => 'https://vimeo.com/api/oembed.json'
            ],
        ];
    }


    public static function is_oembed_provider($url)
    {
        if ( empty( $url ) || ! is_string( $url ) ) {
            return '';
        }
        
        $known = self::get_known_provider();
        $url = esc_url_raw( $url );
        
        $searchdom = parse_url($url, PHP_URL_HOST);
        $res = '';

        foreach ($known as $name => $pdata) {
            foreach ($pdata['domains'] as $dom) {
                if ($dom == $searchdom) {
                    $res = $name;
                    break;
                }
            }
        }
        return $res;
    }

    public static function get_oembed_data($provider, $url)
    {
        if (!isset($provider)) {
            return;
        }

        if ($provider == 'fau') {
            return self::sanitize_oembed_data(self::fetch_fau_video($url));
        } elseif ($provider == 'youtube') {
            return self::sanitize_oembed_data(self::fetch_youtube_video($url));
        } else {
            return self::sanitize_oembed_data(self::fetch_defaultoembed_video($provider, $url));
        }
    }

    public static function fetch_defaultoembed_video($provider, $url)
    {
        $known = self::get_known_provider();
        $transient = 'rrze_video_default_' . md5($url);
        $videodata =  array(
            'error'   => false,
            'video'   => get_transient($transient),
        );
        if (false === $videodata['video']) {
            $endpoint_url = $known[$provider]['api-endpoint'] . '?url=' . $url;

            $oembed_url    = $endpoint_url;
            $remote_get    = wp_safe_remote_get($oembed_url, array('sslverify' => true));
            $videodata['oembed_api_url'] = $oembed_url;
            if (is_wp_error($remote_get)) {
                $videodata['error'] = $remote_get->get_error_message();
            } else {
                $videodata['video'] = json_decode(wp_remote_retrieve_body($remote_get), true);
                set_transient($transient, $videodata['video'], META_EXPIRATION);
            }
        }

        return $videodata;
    }

    public static function fetch_youtube_video($url)
    {
        $known = self::get_known_provider();
        $transient = 'rrze_video_youtube_' . md5($url);
        $videodata =  array(
            'error'   => false,
            'video'   => get_transient($transient),
        );

        if (false === $videodata['video']) {
            $endpoint_url = $known['youtube']['api-endpoint'] . '?url=' . $url;

            $oembed_url    = $endpoint_url;
            $remote_get    = wp_safe_remote_get($oembed_url, array('sslverify' => true));
            $videodata['oembed_api_url'] = $oembed_url;
            if (is_wp_error($remote_get)) {
                $videodata['error'] = $remote_get->get_error_message();
            } else {
                $videodata['video'] = json_decode(wp_remote_retrieve_body($remote_get), true);
                preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
                $videodata['video']['v'] = $matches[1];
                set_transient($transient, $videodata['video'], META_EXPIRATION);
            }
        }

        return $videodata;
    }

    public static function fetch_fau_video($url)
    {
        $known = self::get_known_provider();
        $fau_video =  array(
            'error'   => false,
            'video'   => false,
        );
        $fau_video['oembed_api_url'] = $known['fau']['api-endpoint'] . '?url=' . $url . '&format=json';

        if (false === preg_match('/(clip|webplayer)\/id\/(\d+)/', $url)) {
            $fau_video['error'] = __('The FAU-Video URL does not contain a valid video ID', 'rrze-video');
        } elseif (preg_match('/\/collection\/id\/[0-9]+$/', $url)) {
            $fau_video['error'] = __('The call refers to a video collection. This cannot be integrated. So call up the video directly under the URL:', 'rrze-video');
            $fau_video['error'] .= '<a href="' . $url . '">' . $url . '</a>';
        } elseif (preg_match('/\/course\/id\/[0-9]+$/', $url)) {
            $fau_video['error'] = __('The call refers to a collection of courses. This cannot be integrated. So call up the video directly under the URL:', 'rrze-video');
            $fau_video['error'] .= '<a href="' . $url . '">' . $url . '</a>';
        } else {
            $transient = 'rrze_video_fau_' . md5($url);
            $fau_video['video'] = get_transient($transient);
            if (false === $fau_video['video']) {
                $oembed_url    = $known['fau']['api-endpoint'] . '?url=' . $url . '&format=json';
                $remote_get    = wp_safe_remote_get($oembed_url, array('sslverify' => true));
                $fau_video['oembed_api_url'] = $oembed_url;
                if (is_wp_error($remote_get)) {
                    $fau_video['error'] = $remote_get->get_error_message();
                } else {
                    $fau_video['video'] = json_decode(wp_remote_retrieve_body($remote_get), true);

                    if ((isset($fau_video['video']['provider_videoindex_url'])) && (preg_match('/^\//', $fau_video['video']['provider_videoindex_url']))) {
                        $fau_video['video']['provider_videoindex_url'] = $endpoint_url = $known['fau']['home'] . $fau_video['video']['provider_videoindex_url'];
                    }
                    if ((isset($fau_video['video']['alternative_VideoFolien_size_large'])) && (preg_match('/^\//', $fau_video['video']['alternative_VideoFolien_size_large']))) {
                        $fau_video['video']['alternative_VideoFolien_size_large'] = $endpoint_url = $known['fau']['home'] . $fau_video['video']['alternative_VideoFolien_size_large'];
                    }
                    if ((isset($fau_video['video']['alternative_Audio'])) && (preg_match('/^\//', $fau_video['video']['alternative_Audio']))) {
                        $fau_video['video']['alternative_Audio'] = $endpoint_url = $known['fau']['home'] . $fau_video['video']['alternative_Audio'];
                    }

                    if (isset($fau_video['video']['status']) && ($fau_video['video']['status'] >= 400)) {
                        // neue Fehlerausgabe; Derzeit leider noch nicht implementiert
                        if (isset($fau_video['video']['message'])) {
                            $fau_video['error'] = $fau_video['video']['message'];
                        }
                    }

                    set_transient($transient, $fau_video['video'], META_EXPIRATION);
                }
            }
        }
        return $fau_video;
    }

    public static function sanitize_oembed_data($data)
    {
        $urllist = [
            'file',
            'url',
            'preview_image',
            'poster',
            'thumbnail_url',
            'alternative_Video_size_large_url',
            'alternative_Video_size_medium_url',
            'transcript',
            'provider_url'
        ];

        $textstrings = [
            'inLanguage',
            'author_name',
            'title',
            'provider_name',
            'type',
            'version',
            'name'
        ];

        $textareastrings  = [
            'description'
        ];

        $htmllist = [
            'html'
        ];

        $numbers = [
            'width',
            'height',
            'thumbnail_width',
            'thumbnail_height',
            'alternative_Video_size_large_width',
            'alternative_Video_size_large_height',
            'alternative_Video_size_medium_width',
            'alternative_Video_size_medium_height'
        ];

        if ( is_array( $data ) ) {
            if ( isset( $data['error'] ) ) {
                $data['error'] = wp_kses_post( $data['error'] );
            }
            if ( is_array( $data['video'] ) ) {
                foreach ( $data['video'] as $key => $value ) {
                    if ( in_array( $key, $urllist ) ) {
                        if ( ! empty( $data['video'][ $key ] ) && is_string( $data['video'][ $key ] ) ) {
                            $data['video'][ $key ] = esc_url_raw( $data['video'][ $key ] );
                        } else {
                            $data['video'][ $key ] = '';
                        }
                    } elseif ( in_array( $key, $textstrings ) ) {
                        $data['video'][ $key ] = esc_html( $data['video'][ $key ] );
                    } elseif ( in_array( $key, $textareastrings ) ) {
                        $data['video'][ $key ] = sanitize_textarea_field( $data['video'][ $key ] );
                    } elseif ( in_array( $key, $htmllist ) ) {
                        // Keep the value as is.
                        $data['video'][ $key ] = $data['video'][ $key ];
                    } elseif ( in_array( $key, $numbers ) ) {
                        $data['video'][ $key ] = intval( $data['video'][ $key ] );
                    } else {
                        $data['video'][ $key ] = esc_html( $data['video'][ $key ] );
                    }
                }
            }
        }
        
        return $data;
        
    }
}
