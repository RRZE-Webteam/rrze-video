<?php

namespace RRZE\PostVideo;

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
                $preview_image = 'https://cdn.video.uni-erlangen.de/Images/player_previews/' . get_video_id_from_url( $options['url'] ) .'_preview.img';
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

function get_js_player_action(){
    // dummy callback from wp_ajax api
    // empty on purpose.
}

function js_player_ajax()
{
    $players = array(
        'mediaelement',
        'youtube',
        'fauvideo'
    );
    ?>
    <script type="text/javascript" >
        jQuery(document).ready(function($){
<?php
    foreach( $players as $player) {
?>
            $('a[data-player-type="<?php echo $player; ?>"]').click(function(){

                var video_id  = $(this).attr('data-video-id');
                var id        = $(this).attr('data-box-id');
                var video_url = $(this).attr('data-video-url');     // nur bei FAU video?
                var poster    = $(this).attr('data-preview-image'); // nur bei FAU video?

                $.ajax({
                    url: videoajax.ajaxurl,
                    data: {
                        'action'    : 'get_js_player_action',
                        'video_id'  : video_id,
                        'id'        : id,
                        'poster'    : poster,
                        'video_url' : video_url
                    },
                    success: function(data){
<?php
    switch( $player ) {
        case 'mediaelement' :
?>
                    var video = '<video class="player" width="640" height="360" controls="controls" preload="none">' +
                        '<source src="https://www.youtube.com/watch?v=' + video_id + '" type="video/youtube" />' +
                        '</video>';
                        $(".videocontent" + id)
                        .html(video)
                        .find(".player")
                        .mediaelementplayer({
                        alwaysShowControls: true,
                            features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
                        });
<?php
            break;
        case 'youtube' :
?>
                    var iframe = document.createElement("iframe");
                        iframe.setAttribute("frameborder", "0");
                        iframe.setAttribute("allowfullscreen", "");
                        iframe.setAttribute("src", "https://www.youtube.com/embed/" + video_id + "?rel=0&showinfo=0");

                        $(".embed-container" + id)
                            .html(iframe)
                                .find(".youtube-video"); // <-- ??
<?php
            break;
        case 'fauvideo' :
?>
                     var video = '<video class="player img-responsive center-block" style="width:100%;height:100%;" width="639" height="360" poster="' + poster + '" controls="controls" preload="none">' +
                            '<source src="' + video_url + '" type="video/mp4" />' + '</video>';
                        $(".videocontent" + id)
                            .html(video)
                                .find(".player")
                                    .mediaelementplayer({
                                        alwaysShowControls: true,
                                        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
                                    });
<?php
    }
?>
                   },
                    error: function(errorThrown){
                        window.console.log(errorThrown);
                    }
                });

            });
<?php
    } // endforeach;
?>
        });
    </script><?php
}
