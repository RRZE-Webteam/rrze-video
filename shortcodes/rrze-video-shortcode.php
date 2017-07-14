<?php
namespace RRZE\PostVideo;

add_shortcode('fauvideo', 'RRZE\PostVideo\show_video_on_page'); 

function show_video_on_page( $atts ) {
    
    global $post;
    
    $yt_options             =   get_option('rrze_video_plugin_options');
    $show_youtube_player    =   (!empty($yt_options['youtube_activate_checkbox'])) ? $yt_options['youtube_activate_checkbox'] : 0;
    
    $rrze_video_shortcode = shortcode_atts( array(
        'url'           => '',
        'id'            => '',
        'width-video'   => '50',
        'showinfo'      => '1',
        'showtitle'     => '1',
        'titletag'      => 'h2',
        'youtube-res'   => '1',
        'rand'          => ''
    ), $atts );
    
    $url_shortcode          = $rrze_video_shortcode['url'];
    $id_shortcode           = $rrze_video_shortcode['id'];
    $width_shortcode        = $rrze_video_shortcode['width-video'];
    $taxonomy_genre         = $rrze_video_shortcode['rand'];
    $youtube_resolution     = $rrze_video_shortcode['youtube-res'];
    
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
    
    $box_id = uniqid();
   
    ?>
    
    <style type="text/css" scoped="scoped">
        @media (min-width: 768px) {
            .box<?php echo $box_id ?> {
              max-width:<?php echo $width_shortcode .'%' ?>;
            }
        }
    </style>  
    
    <?php
    
    /*
     * Wenn die url im shortcode gesetzt ist.
     * 
     * video_flag = 1 - Videos aus dem FAU-Videoportal
     * video-flag = 0 - Videos aus Youtube
     */
    
    if( !empty( $url_shortcode ) ) {
        
        $video_flag = assign_video_flag($url_shortcode);
       
        if($video_flag) {
            
            $oembed_url         = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de/webplayer/id/' . http_check_and_filter($url_shortcode) . '&format=json';
            $video_url          = json_decode(wp_remote_retrieve_body(wp_safe_remote_get($oembed_url)), true);       
            $video_file         = $video_url['file'];
            $preview_image      = 'https://cdn.video.uni-erlangen.de/Images/player_previews/'. http_check_and_filter($url_shortcode) .'_preview.img';
            $picture            = $preview_image;
            $showtitle          = ($rrze_video_shortcode['showtitle'] == 1) ? $video_url['title'] : '';
            $modaltitle         = $video_url['title'];
            $author             = ($rrze_video_shortcode['showinfo'] == 1) ? $video_url['author_name'] : '';
            $copyright          = ($rrze_video_shortcode['showinfo'] == 1) ? $video_url['provider_name'] : '';
            
            $id = uniqid();
            
            ob_start();
            include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-shortcode-template.php');
            return ob_get_clean();

        } else {

            $id = uniqid();
            $youtube_id = http_check_and_filter($url_shortcode);
            
            ob_start();
            include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-shortcode-youtube-template.php');
            return ob_get_clean();

        }  
        
    } else {
        
       /*
        * Wenn die id im shortcode gesetzt ist.
        * Dann wird der Datensatz aus dem Video Post Type gezogen
        * 
        * video_flag = 1 - Videos aus dem FAU-Videoportal
        * video-flag = 0 - Videos aus Youtube
        */
        
        //$shortcode_video = new \WP_Query($args_video);
        
        $shortcode_video = assign_wp_query_arguments( $url_shortcode, $id_shortcode , $args_video, $argumentsTaxonomy);
    
        if ( $shortcode_video->have_posts() ) : while ($shortcode_video->have_posts()) : $shortcode_video->the_post();

            $url = get_post_meta( $post->ID, 'url', true );
            
            $video_flag = assign_video_flag($url);

            if($video_flag) {
                $url_data           = get_post_meta( $post->ID, 'url', true );
                $video_id           = http_check_and_filter($url_data);
                $description        = get_post_meta( $post->ID, 'description', true );
                $genre              = wp_strip_all_tags(get_the_term_list($post->ID, 'genre', true ));
                $thumbnail          = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                $oembed_url         = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de/webplayer/id/' . $video_id . '&format=json';
                $video_url          = json_decode(wp_remote_retrieve_body(wp_safe_remote_get($oembed_url)), true);       
                $video_file         = $video_url['file'];
                $preview_image      = 'https://cdn.video.uni-erlangen.de/Images/player_previews/'. $video_id .'_preview.img';
                $picture            = (!$thumbnail) ? $preview_image : $thumbnail;
                $showtitle          = ($rrze_video_shortcode['showtitle'] == 1) ? $video_url['title'] : '';
                $modaltitle         = $video_url['title'];
                $author             = $video_url['author_name'];
                $copyright          = $video_url['provider_name'];
                $id = uniqid();
                
                ob_start();
                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-shortcode-template.php');
                return ob_get_clean();

            } else {

                $id = uniqid();

                $showtitle          = ($rrze_video_shortcode['showtitle'] == 1) ? get_the_title() : '';
                $modaltitle         = get_the_title();
                $youtube_data       = get_post_meta( $post->ID, 'url', true );
                $youtube_id         = http_check_and_filter($youtube_data);
                $description        = get_post_meta( $post->ID, 'description', true );
                
                ob_start();
                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-shortcode-youtube-template.php');
                return ob_get_clean();

            }

        endwhile;

        else :

            $no_posts = '<p>' . _e( 'Es wurden keine Videos gefunden!', 'rrze-video' ) . '</p>';
            echo $no_posts;

        endif;

        wp_reset_postdata();    

    }
}

function http_check_and_filter($url) {
    
    if ( strpos($url, "https://youtu.be/" ) !== false) {
        $filtered_id = substr( $url, strpos( $url, "." ) + 4 );
        return $filtered_id;
    } elseif ( strpos($url, "https://www.youtube.com/watch?v" ) !== false ) {
        $filtered_id = substr( $url, strpos( $url, "=" ) + 1 );
        return $filtered_id;
    } elseif ( strpos($url, "http://www.video.uni-erlangen.de/webplayer/id/" ) !== false || strpos($url, "https://www.video.uni-erlangen.de/webplayer/id/" ) !== false) {
        $filtered_id = substr( $url, strrpos( $url, "/" ) + 1 );
        return $filtered_id;
    } else {
        return $url;
    }
}

function assign_video_flag($url) {
    
    if ( strpos($url, "https://youtu.be/" ) !== false) {
        $video_flag = 0;
        return $video_flag;
    } elseif ( strpos($url, "https://www.youtube.com/watch?v" ) !== false ) {
        $video_flag = 0;
        return $video_flag;
    } elseif ( strpos($url, "http://www.video.uni-erlangen.de/webplayer/id/" ) !== false || strpos($url, "https://www.video.uni-erlangen.de/webplayer/id/" ) !== false) {
        $video_flag = 1;
        return $video_flag;
    } elseif (  strlen( $url) > 5 ) {
        $video_flag = 0;
        return $video_flag;
    } elseif (  strlen( $url) == 5 ) {
        $video_flag = 1;
        return $video_flag;
    } else {
        $video_flag = 1;
        return $video_flag;
    }
}

 function assign_wp_query_arguments($url, $id, $argumentsID, $argumentsTaxonomy) {
        
    if( !empty( $id ) ) {
        $widget_video = new \WP_Query($argumentsID);
        return $widget_video;
    } elseif( !empty( $url ) ) {
        $widget_video = new \WP_Query($argumentsID);
        return $widget_video;
    } else {
        $widget_video = new \WP_Query($argumentsTaxonomy);
        return $widget_video;
    }
}