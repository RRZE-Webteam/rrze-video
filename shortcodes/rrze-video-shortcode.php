<?php
namespace RRZE\PostVideo;

add_shortcode('fauvideo', 'RRZE\PostVideo\show_video_on_page');

function show_video_on_page($atts)
{
    global $post;
    $plugin_settings        = get_option('rrze_video_plugin_options');
    $show_youtube_player    = (!empty( $plugin_settings['youtube_activate_checkbox'] )) ? $plugin_settings['youtube_activate_checkbox'] : 0;

    $rrze_video_shortcode = shortcode_atts(array(
        'url'                   => '',
        'id'                    => '',
        'width'                 => '640',
        'height'                => '360',
        'poster'                => '', // '',[url],'default'
        'showinfo'              => '1',
        'showtitle'             => '1',
        'titletag'              => 'h2',
        'youtube-support'       => '0',
        'youtube-resolution'    => '4',
        'rand'                  => ''
    ), $atts, 'fauvideo');

    $url_shortcode          = $rrze_video_shortcode['url'];
    $id_shortcode           = $rrze_video_shortcode['id'];
    $width_shortcode        = $rrze_video_shortcode['width'];
    $height_shortcode       = $rrze_video_shortcode['height'];
    $poster_shortcode       = $rrze_video_shortcode['poster'];
    $taxonomy_genre         = $rrze_video_shortcode['rand'];
    $youtube_support        = $rrze_video_shortcode['youtube-support'];
    $youtube_resolution     = $rrze_video_shortcode['youtube-resolution'];


    $args_video = array(
        'post_type'         =>  'Video',
        'p'                 =>  $id_shortcode,
        'posts_per_page'    =>  1,
        'orderby'           =>  'date',
        'order'             =>  'DESC'
    );

    $argumentsTaxonomy = array(
        'post_type' => 'Video',
        'posts_per_page' => 1,
        'orderby'   =>  'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'genre',
                'field'    => 'slug',
                'terms'    => array( $taxonomy_genre ),
            ),
        ),
    );

    $instance_id = uniqid();

    // warum?
    if (preg_match("/^[a-zA-Z.:\/ ]*$/", $width_shortcode, $matches)) {
        $width_shortcode = 640;
        $suffix = 'px';
    } elseif (preg_match("/(\d+)%/", $width_shortcode, $matches)) {
        $width_shortcode = 640;
        $suffix = 'px';
    } else {
        $suffix = 'px';
    }

   if ( ! empty( $url_shortcode ) ) {

        $is_fau_video = is_fau_video($url_shortcode);

        enqueue_scripts();

        if ($is_fau_video) {
            // FAU-Video
            // @@todo: General setting:
            $fau_video_url = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de';
            preg_match('/(clip|webplayer)\/id\/(\d+)/',$url_shortcode,$matches);
            $oembed_url    = $fau_video_url . '/' . $matches[1] . '/id/' . $matches[2] . '&format=json';

            $remote_get = wp_safe_remote_get($oembed_url);
            if ( is_wp_error( $remote_get ) ) {
                $error_string = $remote_get->get_error_message();
                return '<div id="message" class="error"><p>' . $error_string . '</p></div>';
            } else {
                $video_url     = json_decode(wp_remote_retrieve_body($remote_get), true);
                $video_file    = $video_url['file'];

                $preview_image  = video_preview_image($poster_shortcode);
                // @@todo: small + large size for image and preview?
                $picture        = $preview_image;
                //
                $showtitle      = ($rrze_video_shortcode['showtitle'] == 1) ? $video_url['title'] : '';
                $modaltitle     = $video_url['title'];
                $author         = ($rrze_video_shortcode['showinfo'] == 1) ? $video_url['author_name'] : '';
                $copyright      = ($rrze_video_shortcode['showinfo'] == 1) ? $video_url['provider_name'] : '';

                ob_start();
                include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-fau-template.php');
                return ob_get_clean();
            }

        } else {

            // other video platform
            // currently youtube only

            $video_id = get_video_id_from_url($url_shortcode);
            $showtitle  = ($rrze_video_shortcode['showtitle'] == 1) ? get_the_title() : '';
            $preview_image_opts = array(
                'provider'         => 'youtube',
                'id'               => $video_id,
                'resolution'       => $youtube_resolution
            );
            $preview_image = video_preview_image($poster_shortcode, $preview_image_opts);
            $picture = $preview_image;
            ob_start();
            include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-youtube-template.php');
            return ob_get_clean();

        }

    } else {

       /*
        * Wenn die id im shortcode gesetzt ist
        * Dann wird der Datensatz aus dem Video Post Type gezogen
        */

        $shortcode_video = assign_wp_query_arguments($url_shortcode, $id_shortcode, $args_video, $argumentsTaxonomy);

        if ($shortcode_video->have_posts()) {
            enqueue_scripts();
            while ($shortcode_video->have_posts()) {

                $shortcode_video->the_post();
                $url          = get_post_meta($post->ID, 'url', true);
                $is_fau_video = is_fau_video($url);
                $thumbnail    = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

                if ($is_fau_video) {
                    // FAU video
                    $url_data           = get_post_meta($post->ID, 'url', true);
                    $video_id           = http_check_and_filter($url_data);
                    $description        = get_post_meta($post->ID, 'description', true);
                    $genre              = wp_strip_all_tags(get_the_term_list($post->ID, 'genre', true));
                    //@@todo: Generalsetting:
                    $fau_video_url      = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de';
                    preg_match('/(clip|webplayer)\/id\/(\d+)/',$url,$matches);
                    $oembed_url         = $fau_video_url . '/' . $matches[1] . '/id/' . $matches[2] . '&format=json';
                    $video_url          = json_decode(wp_remote_retrieve_body(wp_safe_remote_get($oembed_url)), true);
                    $video_file         = $video_url['file'];

                    if (!$thumbnail) {
                        $preview_image  = video_preview_image($poster_shortcode);
                    } else {
                        $preview_image  = $thumbnail[0];
                    }
                    $picture            = $preview_image;

                    $showtitle          = ($rrze_video_shortcode['showtitle'] == 1) ? $video_url['title'] : '';
                    $modaltitle         = $video_url['title'];
                    $author             = $video_url['author_name'];
                    $copyright          = $video_url['provider_name'];

                    ob_start();
                    include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-fau-template.php');
                    $out = ob_get_clean();

                } else {

                    // other videos
                    $video_data         = get_post_meta($post->ID, 'url', true);
                    $video_id           = get_video_id_from_url($video_data);
                    $preview_image_opts = array(
                        'provider'   => 'youtube',
                        'id'         => $video_id,
                        'url'        => $url_shortcode,
                        'resolution' => $youtube_resolution,
                        'thumbnail'  => $thumbnail
                    );
                    $preview_image      = video_preview_image($poster_shortcode,$preview_image_opts);
                    $picture            = $preview_image;

                    $showtitle          = ($rrze_video_shortcode['showtitle'] == 1) ? get_the_title() : '';
                    $modaltitle         = get_the_title();
                    $description        = get_post_meta($post->ID, 'description', true);

                    ob_start();
                    include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-youtube-template.php');
                    $out = ob_get_clean();
                }
            }
        } else {
            $out = '<p>' . __('Es wurden keine Videos gefunden!', 'rrze-video') . '</p>';
        }

        wp_reset_postdata();

        return $out;
    }
}

function video_preview_image($poster,$args=array())
{

    $plugin_settings = get_option('rrze_video_plugin_options');
    $preview_image_fallback = esc_url($plugin_settings['preview_image']);

    $preview_image_fallback = (!empty($preview_image_fallback)) ? $preview_image_fallback : plugin_dir_url(__DIR__) . 'assets/img/_preview.png';

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
        if (empty( $preview_image  )) {
            // fall back to local placeholder
            $preview_image = plugin_dir_url(__DIR__) . 'assets/img/_preview.png';
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

// WIP: make generic ajax function for the player variants
add_action('wp_ajax_nopriv_get_js_player_action', 'RRZE\PostVideo\get_js_player_action');
add_action('wp_ajax_get_js_player_action'       , 'RRZE\PostVideo\get_js_player_action');
add_action('wp_footer'                          , 'RRZE\PostVideo\js_player_ajax');

function js_player_ajax($player)
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

function get_js_player_action(){
    // dummy callback from wp_ajax api
    // empty on purpose.
}

function enqueue_scripts()
{
    wp_enqueue_script('rrze-main-js');
    wp_enqueue_style('mediaelementplayercss');
    wp_enqueue_script('mediaelementplayerjs');
    wp_enqueue_script('rrze-video-js');
    wp_enqueue_style('rrze-video-css');
}
