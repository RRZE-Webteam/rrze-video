<?php

namespace RRZE\Video\Player;

defined('ABSPATH') || exit;

/**
 * Class IFrames
 * @package RRZE\Video
 */
class IFrames
{
    public static function get_known_iframe_provider()
    {
        return [
            'br'    => [
                'domains'    => [
                    'www.br.de',
                    'br.de',
                ],
                'home'    => 'https://www.br.de',
                'name'    => 'BR Mediathek',
            ],
            'ard'   => [
                'domains'    => [
                    'www.ardmediathek.de',
                ],
                'home'    => 'https://www.ardmediathek.de',
                'name'    => 'ARD Mediathek',
            ],
        ];
    }

    public static function get_iframe($url)
    {
        if (!isset($url)) {
            return '';
        }
        $provider = self::is_iframe_provider($url);
        if ($provider) {
            return self::get_iframe_data($provider, $url);
        } else {
            $data =  array(
                'error'   => __('The video provider is unknown. It is therefore not possible to embed the video.', 'rrze-video'),
                'video'   => [],
            );
            return $data;
        }
    }

    public static function is_iframe_provider($url)
    {
        if (!isset($url)) {
            return '';
        }

        $known = self::get_known_iframe_provider();
        $url = esc_url_raw($url);
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

    public static function get_iframe_data($provider, $url)
    {
        if (!isset($provider)) {
            return;
        }

        if ($provider == 'br') {
            return self::fetch_iframe_br($url);
        } elseif ($provider == 'ard') {
            return self::fetch_iframe_ard($url);
        }
        return;
    }

    public static function fetch_iframe_ard($url)
    {
        $known = self::get_known_iframe_provider();
        $data =  [
            'error' => false,
            'video' => [],
        ];

        if (preg_match('/\/[A-Za-z0-9]+\/?$/', $url)) {
            $embedurl = preg_replace('/\/([a-z0-9\-\/]+)\/([a-z0-9\-:\.]+)\/?$/', '/embed/$2', $url);

            $data['video']['html'] = '<iframe class="remoteembed ard" allowfullscreen src="' . $embedurl . '" frameBorder="0" scrolling="no" title="'.__('Video from ARD Mediathek','rrze-video').'"></iframe>';
            $data['video']['orig_url'] = $url;
            $data['video']['embed_url'] = $embedurl;
            $data['video']['provider_url'] = $known['ard']['home'];
            $data['video']['provider_name'] = $known['ard']['name'];
            $data['video']['provider'] = 'ard';
        } else {
            $data['error'] = __('The URL for the ARD Mediathek is invalid or incorrect.', 'rrze-video');
        }
        return $data;
    }

    public static function fetch_iframe_br($url)
    {
        $known = self::get_known_iframe_provider();
        $data =  [
            'error' => false,
            'video' => [],
        ];

        if (preg_match('/\/mediathek\/video\/[a-z0-9\-:\.]+$/', $url)) {
            $embedurl = preg_replace('/\/mediathek\/video\/([a-z0-9\-:\.]+)$/', '/mediathek/embed/$1', $url);

            $data['video']['html'] = '<iframe class="remoteembed" allowfullscreen src="' . $embedurl . '" title="'.__('Video from BR Mediathek','rrze-video').'"></iframe>';
            $data['video']['orig_url'] = $url;
            $data['video']['embed_url'] = $embedurl;
            $data['video']['provider_url'] = $known['br']['home'];
            $data['video']['provider_name'] = $known['br']['name'];
            $data['video']['provider'] = 'br';
        } else {
            $data['error'] = __('The URL for the BR Mediathek is invalid or incorrect.', 'rrze-video');
        }
        return $data;
    }
}
