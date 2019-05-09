<?php
namespace RRZE\PostVideo;

add_shortcode('fauvideo', 'RRZE\PostVideo\show_video_on_page');

add_action('wp_ajax_nopriv_get_js_player_action', 'RRZE\PostVideo\get_js_player_action');
add_action('wp_ajax_get_js_player_action'       , 'RRZE\PostVideo\get_js_player_action');

function show_video_on_page( $atts )
{
    global $post;
    $helpers = new RRZE_Video_Functions();
    $plugin_settings     = get_option( 'rrze_video_plugin_options' );
    $show_youtube_player = ( ! empty( $plugin_settings['youtube_activate_checkbox'] ) ) ? $plugin_settings['youtube_activate_checkbox'] : 0;

    $rrze_video_shortcode = shortcode_atts( array(
        'url'                   => '',
        'id'                    => '',
        'poster'                => '',
        'showinfo'              => '1',
        'showtitle'             => '1',
        'titletag'              => 'h2',
        'youtube-support'       => '0',
        'rand'                  => ''
    ), $atts, 'fauvideo' );

    $url_shortcode          = $rrze_video_shortcode['url'];
    $id_shortcode           = $rrze_video_shortcode['id'];
    //$width_shortcode        = $rrze_video_shortcode['width'];
    //$height_shortcode       = $rrze_video_shortcode['height'];
    $poster_shortcode       = $rrze_video_shortcode['poster'];
    $taxonomy_genre         = $rrze_video_shortcode['rand'];
    //$youtube_support        = $rrze_video_shortcode['youtube-support'];
    //$youtube_resolution     = $rrze_video_shortcode['youtube-resolution'];
    $error = false;
    $html  = '';


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

    if ( ! empty( $url_shortcode ) ) {

        $is_fau_video = $helpers->is_fau_video($url_shortcode);

        $helpers->enqueue_scripts();

        if ($is_fau_video) {

            // FAU-Video
            $fau_video = $helpers->fetch_fau_video( $url_shortcode );
            if ( $fau_video['error'] != '' ) {
                $error = true;
                $html = '<div id="message" class="error"><p>' . $fau_video['error'] . '</p></div>';
            } else {
                $video_file    = $fau_video['video']['file'];
                $preview_image_opts = array(
                    'provider' => 'fau',
                    'url' => $url_shortcode,
                    'thumbnail' => $fau_video['video']['preview_image']
                );
                $preview_image  = $helpers->video_preview_image($poster_shortcode,$preview_image_opts);

                // @@todo: small + large size for image and preview?
                $picture        = $preview_image;
                //
                $showtitle      = ($rrze_video_shortcode['showtitle'] == 1) ? $fau_video['video']['title'] : '';
                $modaltitle     = $fau_video['video']['title'];
                $author         = ($rrze_video_shortcode['showinfo'] == 1) ? $fau_video['video']['author_name'] : '';
                $copyright      = ($rrze_video_shortcode['showinfo'] == 1) ? $fau_video['video']['provider_name'] : '';

                ob_start();
                include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-fau-template.php');
                $html = ob_get_clean();
            }

        } else {

            // other video platform
            // currently youtube only

            $video_id = $helpers->get_video_id_from_url($url_shortcode);
            $showtitle  = ($rrze_video_shortcode['showtitle'] == 1) ? get_the_title() : '';
            $preview_image_opts = array(
                'provider'         => 'youtube',
                'id'               => $video_id,
                'resolution'       => $youtube_resolution
            );
            $preview_image = $helpers->video_preview_image($poster_shortcode, $preview_image_opts);
            $picture = $preview_image;
            ob_start();
            include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-youtube-template.php');
            $html = ob_get_clean();

        }

    } else {

       /*
        * Wenn die id im shortcode gesetzt ist
        * Dann wird der Datensatz aus dem Video Post Type gezogen
        */

        $shortcode_video = $helpers->assign_wp_query_arguments($url_shortcode, $id_shortcode, $args_video, $argumentsTaxonomy);

        if ($shortcode_video->have_posts()) {

            $helpers->enqueue_scripts();

            while ($shortcode_video->have_posts()) {

                $shortcode_video->the_post();
                $url          = get_post_meta($post->ID, 'url', true);
                $is_fau_video = $helpers->is_fau_video($url);
                $thumbnail    = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                if ( ! empty($thumbnail) ) {
                    $thumbnail = $thumbnail[0];
                }
                $description  = get_post_meta($post->ID, 'description', true);

                if ($is_fau_video) {

                    // FAU video
                    $genre              = wp_strip_all_tags(get_the_term_list($post->ID, 'genre', true));

                    $fau_video          = $helpers->fetch_fau_video($url);
                    if ( $fau_video['error'] != '' ) {
                        $error = true;
                        $out = '<div id="message" class="error"><p>' . $fau_video['error'] . '</p></div>';
                    } else {
                        $video_file     = $fau_video['video']['file'];
                        if (!$thumbnail) {
                            $preview_image_opts = array(
                                'provider'  => 'fau',
                                'url'       => $url,
                                'thumbnail' => $fau_video['video']['preview_image']
                            );
                            $preview_image  = $helpers->video_preview_image($poster_shortcode,$preview_image_opts);
                        } else {
                            $preview_image  = $thumbnail;
                        }
                        $picture = $preview_image;

                        $showtitle      = ($rrze_video_shortcode['showtitle'] == 1) ? $fau_video['video']['title'] : '';
                        $modaltitle     = ($fau_video['video']['title'] != '')      ? $fau_video['video']['title'] : get_the_title();
                        $author         = $fau_video['video']['author_name'];
                        $copyright      = $fau_video['video']['provider_name'];

                        ob_start();
                        include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-fau-template.php');
                        $out = ob_get_clean();
                    }

                } else {

                    // other videos
                    $video_data    = get_post_meta($post->ID, 'url', true);
                    $video_id      = $helpers->get_video_id_from_url($video_data);
                    $preview_image_opts = array(
                        'provider'   => 'youtube',
                        'id'         => $video_id,
                        'url'        => $url_shortcode,
                        'resolution' => $youtube_resolution,
                        'thumbnail'  => $thumbnail
                    );
                    $preview_image = $helpers->video_preview_image($poster_shortcode,$preview_image_opts);
                    $picture       = $preview_image;

                    $showtitle     = ($rrze_video_shortcode['showtitle'] == 1) ? get_the_title() : '';
                    $modaltitle    = $showtitle;

                    ob_start();
                    include(plugin_dir_path(__DIR__) . 'templates/rrze-video-shortcode-youtube-template.php');
                    $out = ob_get_clean();
                }
            }
        } else {
            $error = true;
            $out = '<p>' . __('Es wurden keine Videos gefunden!', 'rrze-video') . '</p>';
        }

        wp_reset_postdata();

        $html = $out;
    }

    if ( empty( $error ) ){
        // add players js to footer:
        add_action('wp_footer', 'RRZE\PostVideo\js_player_ajax');
    }

    return $html;
}
