<?php

namespace RRZE\PostVideo;

Class RRZE_Video_Functions {
    /**
     * Shared functions for widget and shortcode
     */
    function video_preview_image($poster,$args=array())
    {

        $plugin_settings               = get_option('rrze_video_plugin_options');
        $plugin_fallback_preview_image = plugins_url('../assets/img/_preview.png',dirname(__FILE__));
        $settings_preview_image        = esc_url($plugin_settings['preview_image']);
        $preview_image_fallback        = ( ! empty( $settings_preview_image ) ) ? $settings_preview_image : $plugin_fallback_preview_image;

        $options_default = array(
            'provider'          => false,
            'id'                => false,
            'url'               => false,
            'resolution'        => false,
            'thumbnail'         => false,
            'preview_fallback'  => $preview_image_fallback
        );
        $options = array_merge($options_default,$args);

        // Preview image handling
        $preview_image = false;
        if ($poster == '') {
            $preview_image = ( !$options['thumbnail'] ) ? $options['preview_fallback'] : $options['thumbnail'][0];
        } else if ($poster == 'default') {
            switch ($options['provider']) {
                case 'youtube':
                    $youtube_url = 'https://img.youtube.com/vi/' . $options['id'];

                    switch($options['resolution']){
                        case 1:
                            $preview_image = $youtube_url . '/maxresdefault.jpg';
                            break;
                        case 2:
                            $preview_image = $youtube_url . '/default.jpg';
                            break;
                        case 3:
                            $preview_image = $youtube_url . '/hqdefault.jpg';
                            break;
                        case 4:
                            $preview_image = $youtube_url . '/mqdefault.jpg';
                            break;
                        default:
                            $preview_image = $youtube_url . '/sddefault.jpg';
                    }
                    break;
                default:
                    $preview_image = 'https://cdn.video.uni-erlangen.de/Images/player_previews/' . $this->get_video_id_from_url( $options['url'] ) .'_preview.img';
            }
        } else {
            // check if it is a URL
            $preview_image = esc_url( $poster );
            if ( empty( $preview_image ) ) {
                // fall back to local placeholder
                $preview_image = $plugin_fallback_preview_image;
            } else {
                // check if is image? check if it is local/media?
            }
        }

        return $preview_image;
    }

    function get_video_id_from_url($url,$provider=false)
    {
        $video_id = false;
        if ( $url != '' ) {
            if ( ! empty( wp_parse_url($url) ) ) {
                // check video providers
                $test_domain = wp_parse_url($url);
                $test_host   = preg_replace('/^(www|m)\./','',$test_domain['host']);

                if ($provider == 'fau') {

                    preg_match('/^\/(clip|webplayer)\/id\/(\d+)/',$test_domain['path'],$matches);
                    $video_id = $matches[2];

                } else {

                    // youtube:
                    if ($test_host == 'youtube.com') {
                        preg_match('/^v=(.*)/',$test_domain['query'],$matches);
                        $video_id = $matches[1];
                    }
                    if ($test_host == 'youtu.be') {
                        preg_match('/^\/(.*)$/',$test_domain['path'],$matches);
                        $video_id = $matches[1];
                    }
                    // vimeo:
                    // @@todo
                    // etc:
                    // @@todo
                }
            } else {
                // url kann auch nur die/eine ID sein zb. "DF2aRrr21-M" (aarggh)
                $video_id = $url;
            }
        }
        return $video_id;
    }

    function is_fau_video($url)
    {
        $is_fau_video = false;
        // @@todo get the domains from settings/admin screen or general constants/vars?
        $fau_video_domains = array(
            'video.uni-erlangen.de',
            'video.fau.de',
            'fau.tv'
        );
        if (!empty(wp_parse_url($url))) {
            $test_url    = wp_parse_url($url);
            $test_domain = preg_replace('/^www\./','',$test_url['host']);
            if (in_array($test_domain,$fau_video_domains)) {
               $is_fau_video = true;
            }
        }
        return $is_fau_video;
    }

    function assign_wp_query_arguments($url, $id, $argumentsID, $argumentsTaxonomy)
    {
        if (!empty($id) || !empty($url)) {
            $widget_video = new \WP_Query($argumentsID);
        } else {
            $widget_video = new \WP_Query($argumentsTaxonomy);
        }
        return $widget_video;
    }

    function enqueue_scripts()
    {
        wp_enqueue_script('rrze-main-js');
        wp_enqueue_style('mediaelementplayercss');
        wp_enqueue_script('mediaelementplayerjs');
        wp_enqueue_script('rrze-video-js');
        wp_enqueue_style('rrze-video-css');
    }

}
