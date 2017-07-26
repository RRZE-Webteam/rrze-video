<?php

namespace RRZE\PostVideo;

add_action( 'widgets_init', function(){
	register_widget( 'RRZE\PostVideo\RRZE_Video_Widget' );
});

class RRZE_Video_Widget extends \WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'video_widget',
            'description' => __('Zeigt Videos in der Sidebar oder im Footer an.'),
        );
        parent::__construct( 'video_widget', 'RRZE Video Widget', $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
             
        global $post;
        
        $yt_options             =   get_option('rrze_video_plugin_options');
        $show_youtube_player    =   (!empty($yt_options['youtube_activate_checkbox'])) ? $yt_options['youtube_activate_checkbox'] : 0;
        
        extract( $args );
        
        echo $before_widget;
        
        $form_id                = (!empty($instance['id'])) ? $instance['id'] :'';
        $form_url               = (!empty($instance['url'])) ? $instance['url'] :'';
        $form_title             = (!empty($instance['title'])) ? $instance['title'] :'';
        $form_showtitle         = (!empty($instance['showtitle'])) ? $instance['showtitle'] :''; 
        $width                  = ! empty($instance['width'] ) ? $instance['width'] : 270;
        $height                 = ! empty($instance['height'] ) ? $instance['height'] : 150;
        $meta                   = (!empty($instance['meta'])) ? $instance['meta'] :'';
        $taxonomy_genre         = (!empty($instance['genre'])) ? $instance['genre'] :'';
        $youtube_resolution     = (!empty($instance['resolution'])) ? $instance['resolution'] :'';
        
        $argumentsID = array(
            'post_type'         =>  'Video',
            'p'                 =>  $form_id,
            'posts_per_page'    =>  1,
            'orderby'           =>  'date',
            'order'             =>  'DESC',
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
            @media (min-width: 320px) {
                .box-widget<?php echo $box_id ?> {
                  max-width:<?php echo $width .'px' ?>;
                }
            }
        </style>  

        <?php
        
        if( !empty( $form_url ) ) {

            $video_flag = self::assign_video_flag($form_url);

            if($video_flag) {

                $oembed_url         = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de/webplayer/id/' . self::http_check_and_filter($form_url) . '&format=json';
                $video_url          = json_decode(wp_remote_retrieve_body(wp_safe_remote_get($oembed_url)), true);       
                $video_file         = $video_url['file'];
                $preview_image      = 'https://cdn.video.uni-erlangen.de/Images/player_previews/'. self::http_check_and_filter($form_url) .'_preview.img';
                $picture            = $preview_image;
                $description        = '';
                
                if ( empty( $form_title ) && $form_showtitle == 1 ) {
                    $showtitle  = $video_url['title'];
                    $modaltitle = $video_url['title']; 
                } elseif( empty( $form_title ) && $form_showtitle == 0  ) {
                    $showtitle  = '';
                    $modaltitle = $video_url['title']; 
                } elseif( !empty( $form_title ) && $form_showtitle == 1  )  {
                    $showtitle  = $form_title;
                    $modaltitle = $form_title;
                } else {
                    $showtitle  = '';
                    $modaltitle = $form_title;
                }
                
                $author             = ($meta == 1) ? $video_url['author_name'] : '';
                $copyright          = ($meta == 1) ? $video_url['provider_name'] : '';
                $id = uniqid();

                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-template.php');
                
                echo $after_widget;

            } else {

                $id = uniqid();
                $youtube_id = http_check_and_filter($form_url);

                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-youtube-template.php');
                
                echo $after_widget;

            }  

        } else {
          
        $widget_video = self::assign_wp_query_arguments($form_url, $form_id, $argumentsID, $argumentsTaxonomy);
       
        if ( $widget_video->have_posts() ) : while ($widget_video->have_posts()) : $widget_video->the_post();
        
            $url = get_post_meta( $post->ID, 'url', true );
            
            $video_flag = self::assign_video_flag($url);
        
            if ($video_flag) {
               
                $showtitle          = get_the_title();
                $description        = get_post_meta( $post->ID, 'description', true );
                $url_data           = get_post_meta( $post->ID, 'url', true );
                $video_url          = self::http_check_and_filter($url_data);
                $thumbnail          = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                $oembed_url         = 'https://www.video.uni-erlangen.de/services/oembed/?url=https://www.video.uni-erlangen.de/webplayer/id/' . $video_url . '&format=json';
                $video              = json_decode(wp_remote_retrieve_body(wp_safe_remote_get($oembed_url)), true);       
                $video_file         = $video['file'];
                $preview_image      = $video['preview_image'];
                $picture            = (!$thumbnail) ? $preview_image : $thumbnail;
                
                if ( empty( $form_title ) && $form_showtitle == 1 ) {
                    $showtitle  = $video['title'];
                    $modaltitle = $video['title']; 
                } elseif( empty( $form_title ) && $form_showtitle == 0  ) {
                    $showtitle  = '';
                    $modaltitle = $video['title']; 
                } elseif( !empty( $form_title ) && $form_showtitle == 1  )  {
                    $showtitle  = $form_title;
                    $modaltitle = $form_title;
                } else {
                    $showtitle  = '';
                    $modaltitle = $form_title;
                }
                
                $author             = ($meta == 1) ? $video['author_name'] : '';
                $copyright          = ($meta == 1) ? $video['provider_name'] : '';

                $id = uniqid(); 

                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-template.php');
                
                echo $after_widget;

            } else {
                
                $id = uniqid();

                $showtitle          = get_the_title();
                $youtube_data       = get_post_meta( $post->ID, 'url', true );
                $youtube_title      = get_the_title();
                $youtube_id         = self::http_check_and_filter($youtube_data);
                $description        = get_post_meta( $post->ID, 'description', true );
                
                if ( empty( $form_title ) && $form_showtitle == 1 ) {
                    $showtitle  =  $youtube_title; 
                    $modaltitle =  $youtube_title; 
                } elseif( empty( $form_title ) && $form_showtitle == 0  ) {
                    $showtitle  = '';
                    $modaltitle =  $youtube_title; 
                } elseif( !empty( $form_title ) && $form_showtitle == 1  )  {
                    $showtitle  = $form_title;
                    $modaltitle = $form_title;
                } else {
                    $showtitle  = '';
                    $modaltitle = $form_title;
                }

                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-youtube-template.php');
                
                echo $after_widget;

            } 
       
        endwhile; 

        else :

            $no_posts = '<p>' . _e( 'Es wurden keine Videos gefunden!', 'rrze-video' ) . '</p>';
            echo $no_posts;

        endif;

        wp_reset_postdata();  
    }
}
    

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $id         = ! empty( $instance['id'] ) ? $instance['id'] : '';     
        $url        = ! empty( $instance['url'] ) ? $instance['url'] : '';
        $width      = ! empty( $instance['width'] ) ? $instance['width'] : 270;
        $height     = ! empty( $instance['height'] ) ? $instance['height'] : 150;
        $showtitle  = ! empty( $instance['showtitle']) ? $instance['showtitle'] : '';   
        $meta       = ! empty( $instance['meta'] ) ? $instance['meta'] : '';   
        $genre      = ! empty( $instance['genre'] ) ? $instance['genre'] :'';
        $resolution = ! empty( $instance['resolution'] ) ? $instance['resolution'] : '';
        ?>
        
         <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'rrze-video' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" placeholder="title" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        <em><?php _e('Der Video-Titel.' ) ?></em>
        </p>
        
         <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_attr_e( 'ID:', 'rrze-video' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" placeholder="id" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>">
        <em><?php _e('Die Datensatz-ID.' ) ?></em>
        </p>
        
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_attr_e( 'Url:', 'rrze-video' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" placeholder="url" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>">
        <em><?php _e('Die URL.' ) ?></em>
        </p>
        
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_attr_e( 'width:', 'rrze-video' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>">
        <em><?php _e('Die Breite des Preview-Images' ) ?></em>
        </p>
        
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_attr_e( 'height:', 'rrze-video' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>">
        <em><?php _e('Die Höhe des Preview-Images' ) ?></em>
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id( 'showtitle' ); ?>"><?php _e('Zeige Widget Videotitel:' ) ?></label>
        <select class='widefat' id="<?php echo $this->get_field_id( 'showtitle' ); ?>"
        name="<?php echo $this->get_field_name( 'showtitle' ); ?>" type="text">
        <option value="" selected="selected"><?php _e('Auswählen') ?></option> 
        <option value='1'<?php echo ( $showtitle == '1' ) ? 'selected' : ''; ?>>
            Ein
          </option>
          <option value='0'<?php echo ( $showtitle == '0' ) ? 'selected' : ''; ?>>
            Aus
          </option> 
        </select>                
        </p> 
        
        <p>
        <label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e('Zeige Metainformationen:' ) ?></label>
        <select class='widefat' id="<?php echo $this->get_field_id('meta'); ?>"
        name="<?php echo $this->get_field_name( 'meta' ); ?>" type="text">
        <option value="" selected="selected"><?php _e('Auswählen') ?></option>   
        <option value='1'<?php echo ( $meta == '1' ) ? 'selected' : ''; ?>>
            Ein
          </option>
          <option value='0'<?php echo ( $meta == '0' )?'selected' : ''; ?>>
            Aus
          </option> 
        </select>                
        </p>  
        
        <?php 
        
        $terms = get_terms( array(
            'taxonomy' => 'genre',
            'hide_empty' => true,
        ) );
        
        ?>
        
        <p>
        <label for="<?php echo $this->get_field_id('genre'); ?>">Zufallsvideo nach Genre:</label>
        <select class='widefat' id="<?php echo $this->get_field_id('genre'); ?>"
        name="<?php echo $this->get_field_name('genre'); ?>" type="text">
            <option value="0" selected="selected"><?php _e('Genre auswählen') ?></option>
        <?php        
        
        foreach($terms as $term) {
            
            if($term->name == $genre) {
            ?>
                <option value=<?php echo $term->name ?> selected><?php echo $term->name; ?></option>
            <?php    
            }
            else {
            ?>
                <option value=<?php echo $term->name ?> ><?php echo $term->name; ?></option>
            <?php
            }
        }
        ?>
        </select>                
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'resolution' ); ?>"><?php _e('Auflösung des Youtube-Bildes:' ) ?></label>
        <select class='widefat' id="<?php echo $this->get_field_id( 'resolution' ); ?>"
        name="<?php echo $this->get_field_name( 'resolution' ); ?>" type="text">
        <option value="" selected="selected"><?php _e('Auswählen') ?></option> 
        <option value='1'<?php echo ( $resolution == '1' ) ? 'selected' : ''; ?>>
            maxresultion
          </option>
          <option value='2'<?php echo ( $resolution == '2' ) ? 'selected' : ''; ?>>
            default
          </option>
           <option value='3'<?php echo ( $resolution == '3' ) ? 'selected' : ''; ?>>
            hqdefault
          </option> 
           <option value='4'<?php echo ( $resolution == '4' ) ? 'selected' : ''; ?>>
            mqdefault
          </option>
           <option value='5'<?php echo ( $resolution == '5' ) ? 'selected' : ''; ?>>
            sddefault
          </option> 
        </select>                
        </p> 
        <?php
    }

   /*
     * Im Widget-Screen werden die alten Eingaben mit
     * den neuen Eingaben ersetzt und gespeichert.  
     */
    public function update( $new_instance, $old_instance ) { 
        
        $instance = $old_instance;
        $instance[ 'title' ]        = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'id' ]           = strip_tags( $new_instance[ 'id' ] );
        $instance[ 'url' ]          = strip_tags( $new_instance[ 'url' ] );
        $instance[ 'width' ]        = strip_tags( $new_instance[ 'width' ] );
        $instance[ 'height' ]       = strip_tags( $new_instance[ 'height' ] );
        $instance[ 'showtitle' ]    = strip_tags( $new_instance[ 'showtitle' ] );
        $instance[ 'meta' ]         = strip_tags( $new_instance[ 'meta' ] );
        $instance[ 'genre' ]        = strip_tags( $new_instance[ 'genre' ] );
        $instance[ 'resolution' ]   = strip_tags( $new_instance[ 'resolution' ] );
        
        return $instance;
    } 

    public function http_check_and_filter($url) {

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

    public function assign_video_flag($url) {

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
    
    public function assign_wp_query_arguments($url, $id, $argumentsID, $argumentsTaxonomy) {
        
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
}