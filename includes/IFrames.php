<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

class IFrames
{
    static function get_known_iframe_provider()
    {
        return [
            'br'    => [
                'domains'    => [
                    'www.br.de',
                    'br.de'
                ],
                'home'    => 'https://www.br.de',
                'name'    => 'BR Mediathek'
            ],
            'ard'   => [
                'domains'    => [
                    'www.ardmediathek.de'
                ],
                'home'    => 'https://www.ardmediathek.de',
                'name'    => 'ARD Mediathek'
            ],
        ];
    }

    static function get_iframe($url)
    {
        if (!isset($url)) {
            return '';
        }
        $provider = self::is_iframe_provider($url);
        if ($provider) {
            return self::get_iframe_data($provider, $url);
        } else {
            $videodata =  array(
                'error'   => __('Der Video-Provider ist unbekannt. Ein Embedding des Videos ist daher nicht möglich.', 'rrze-video'),
                'video'   => false,
            );
            return $videodata;
        }
    }

    static function is_iframe_provider($url)
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

    static function get_iframe_data($provider, $url)
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
    static function fetch_iframe_ard($url)
    {
        $known = self::get_known_iframe_provider();
        $videodata =  array(
            'error'   => false,
            'video'   => false,
        );

        if (preg_match('/\/[A-Za-z0-9]+\/?$/', $url)) {
            $embedurl = preg_replace('/\/([a-z0-9\-\/]+)\/([a-z0-9\-:\.]+)\/?$/', '/embed/$2', $url);

            $videodata['video']['html'] = '<iframe class="remoteembed ard" allowfullscreen src="' . $embedurl . '" frameBorder="0" scrolling="no"></iframe>';
            $videodata['video']['orig_url'] = $url;
            $videodata['video']['embed_url'] = $embedurl;
            $videodata['video']['provider_url'] = $known['ard']['home'];
            $videodata['video']['provider_name'] = $known['ard']['name'];
            $videodata['video']['provider'] = 'ard';
        } else {
            $videodata['error'] = __('Die URL für die ARD Mediathek ist ungültig oder fehlerhaft.', 'rrze-video');
        }
        return $videodata;
    }

    static function fetch_iframe_br($url)
    {
        $known = self::get_known_iframe_provider();
        $videodata =  array(
            'error'   => false,
            'video'   => false,
        );

        if (preg_match('/\/mediathek\/video\/[a-z0-9\-:\.]+$/', $url)) {
            $embedurl = preg_replace('/\/mediathek\/video\/([a-z0-9\-:\.]+)$/', '/mediathek/embed/$1', $url);

            $videodata['video']['html'] = '<iframe class="remoteembed" allowfullscreen src="' . $embedurl . '"></iframe>';
            $videodata['video']['orig_url'] = $url;
            $videodata['video']['embed_url'] = $embedurl;
            $videodata['video']['provider_url'] = $known['br']['home'];
            $videodata['video']['provider_name'] = $known['br']['name'];
            $videodata['video']['provider'] = 'br';
        } else {
            $videodata['error'] = __('Die URL für die BR Mediathek ist ungültig oder fehlerhaft.', 'rrze-video');
        }
        return $videodata;
    }
}
