<?php

namespace RRZE\PostVideo;

Class RRZE_Video_Functions {
    /**
     * Shared functions for widget and shortcode
     */
    function video_preview_image($poster,$args=array())
    {

        $plugin_settings               = get_option('rrze_video_plugin_options');
        $plugin_fallback_preview_image = plugins_url('assets/img/_preview.png',dirname(__DIR__));
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
        // fallback to local preview image first...
        $preview_image = ( ! $options['thumbnail'] ) ? $options['preview_fallback'] : $options['thumbnail'][0];

        // a dedicated poster(url|id) is given (but not "default"):
        if ( $poster != '' && $poster != 'default' ) {
            $check_image = esc_url( $poster );
            if ( ! empty( $check_image ) ) {
                // seems to be a url
                $preview_image = $check_image;
            }
        } elseif ( $poster == 'default' || ! empty( $plugin_settings['preview_image_vendor'] ) ) {
            // no poster is given, so check if it set to "default" or if
            // use vendor thumb is set in plugin settings:
            switch ( $options['provider'] ) {
                case 'youtube':
                    $youtube_url = 'https://img.youtube.com/vi/' . $options['id'];
                    switch( $options['resolution'] ){
                        case 1:
                            $thumb = '/maxresdefault.jpg';
                            break;
                        case 2:
                            $thumb = '/default.jpg';
                            break;
                        case 3:
                            $thumb = '/hqdefault.jpg';
                            break;
                        case 4:
                            $thumb = '/mqdefault.jpg';
                            break;
                        default:
                            $thumb = '/sddefault.jpg';
                    }
                    $preview_image = $youtube_url . $thumb;
                    break;
                case 'fau':
                    $preview_image = 'https://cdn.video.uni-erlangen.de/Images/player_previews/' . $this->get_video_id_from_url( $options['url'], 'fau' ) .'_preview.img';
                    break;
                // default: use fallback from above
            }
        }
        return $preview_image;
    }

    function get_video_id_from_url( $url, $provider=false )
    {
        $video_id = false;
        if ( $url != '' ) {
            if ( ! empty( wp_parse_url( $url ) ) ) {
                // check video providers
                $test_domain = wp_parse_url( $url );
                $test_host   = preg_replace( '/^(www|m)\./', '', $test_domain['host'] );

                if ($provider == 'fau') {

                    preg_match( '/^\/(clip|webplayer)\/id\/(\d+)/' , $test_domain['path'], $matches );
                    $video_id = $matches[2];

                } else {

                    // youtube:
                    if ( $test_host == 'youtube.com' ) {
                        preg_match( '/^v=(.*)/', $test_domain['query'], $matches );
                        $video_id = $matches[1];
                    }
                    if ( $test_host == 'youtu.be' ) {
                        preg_match( '/^\/(.*)$/', $test_domain['path'], $matches );
                        $video_id = $matches[1];
                    }
                    // vimeo:
                    // @@todo
                    // etc:
                    // @@todo
                }
            } else {
                // url kann auch nur die/eine ID sein zb. "DF2aRrr21-M"
                $video_id = $url;
            }
        }
        return $video_id;
    }

    function is_fau_video( $url )
    {
        $is_fau_video = false;
        // @@todo get the domains from settings/admin screen or general constants/vars?
        $fau_video_domains = array(
            'video.uni-erlangen.de',
            'video.fau.de',
            'fau.tv'
        );
        if ( ! empty( wp_parse_url( $url ) ) ) {
            $test_url    = wp_parse_url( $url );
            $test_domain = preg_replace( '/^www\./', '', $test_url['host'] );
            if ( in_array( $test_domain, $fau_video_domains ) ) {
               $is_fau_video = true;
            }
        }
        return $is_fau_video;
    }

    function fetch_fau_video( $url )
    {

        $fau_video =  array(
            'error'   => false,
            'video'   => false,
        );

        $fau_video_url = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de';
        preg_match( '/(clip|webplayer)\/id\/(\d+)/', $url, $matches);
        if( ! is_array( $matches ) ){
            $fau_video['error'] = 'no match in url';
        } else {
            $oembed_url    = $fau_video_url . '/' . $matches[1] . '/id/' . $matches[2] . '&format=json';
            $remote_get    = wp_safe_remote_get( $oembed_url );
            if ( is_wp_error( $remote_get ) ) {
                $fau_video['error'] = $remote_get->get_error_message();
            } else {
                $fau_video['video'] = json_decode( wp_remote_retrieve_body( $remote_get ), true);
            }
        }

        return $fau_video;

    }

    function assign_wp_query_arguments($url, $id, $argumentsID, $argumentsTaxonomy)
    {
        if ( ! empty( $id ) || ! empty( $url ) ) {
            $widget_video = new \WP_Query( $argumentsID );
        } else {
            $widget_video = new \WP_Query( $argumentsTaxonomy );
        }
        return $widget_video;
    }

    function get_video_title( $url, $id )
    {
        if ( ! empty( $id ) ) {
            return false;
        } elseif ( ! empty( $url ) ) {
            return false;
        } else {
            return true;
        }
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
