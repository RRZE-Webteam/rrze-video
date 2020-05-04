<?php

namespace RRZE\PostVideo;

add_action( 'widgets_init', function(){
	register_widget( 'RRZE\PostVideo\RRZE_Video_Widget' );
});

add_action('wp_ajax_nopriv_get_js_player_action', 'RRZE\PostVideo\get_js_player_action');
add_action('wp_ajax_get_js_player_action'       , 'RRZE\PostVideo\get_js_player_action');

class RRZE_Video_Widget extends \WP_Widget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'video_widget',
            'description' => __('Zeigt Videos in der Sidebar oder im Footer an.','rrze-video'),
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
        $helpers = new RRZE_Video_Functions();

        $plugin_settings        = get_option('rrze_video_plugin_options');
        $show_youtube_player    = ( !empty( $plugin_settings['youtube_activate_checkbox'] )) ? $plugin_settings['youtube_activate_checkbox'] : 0;

        // bad according to WP coding standards:
        extract( $args );

        echo $before_widget;

        $form_id                     = ( !empty($instance['id']) )                      ? $instance['id']         : '';
        $form_url                    = ( !empty($instance['url']) )                     ? $instance['url']        : '';
        $form_title                  = ( !empty($instance['title']) )                   ? $instance['title']      : '';
        $form_showtitle              = ( !empty($instance['showtitle']) )               ? $instance['showtitle']  : '';
        $meta                        = ( !empty($instance['meta']) )                    ? $instance['meta']       : '';
        $taxonomy_genre              = ( !empty($instance['genre']) )                   ? $instance['genre']      : '';
        $form_show_custom_post_title = ( !empty($instance['show_custom_post_title']) )  ? $instance['show_custom_post_title']  : '';
        $video_src_type              = ( !empty($instance['video_src_type']) )          ? $instance['video_src_type'] : '';

        $showtitle = $form_title;

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

        $instance_id = uniqid();

        if ( !empty( $form_url ) ) {

            $is_fau_video = $helpers->is_fau_video($form_url);

            $helpers->enqueue_scripts();

            if ($is_fau_video) {

                // fau video:
                $fau_video = $helpers->fetch_fau_video( $form_url );

                if ( $fau_video['error'] != '' ) {
                    echo '<div id="message" class="error"><p>' . $fau_video['error'] . '</p></div>';
                } else {
                    $video_file     = $fau_video['video']['file'];
                    $preview_image_opts = array(
                        'provider'  => 'fau',
                        'url'       => $form_url,
                        'thumbnail' => $fau_video['video']['preview_image']
                    );
                    $preview_image  = $helpers->video_preview_image('',$preview_image_opts);

                    $desc           = ''; // used where?
                    $orig_video_url = $form_url; // used where?

                    // should we show the title from the widget?
                    if ( $form_showtitle == 1 ) {
                        $showtitle  = ( ! empty($form_title) ) ? $form_title : $fau_video['video']['title'];
                        $modaltitle = $showtitle;
                    } else {
                        $showtitle  = '';
                        $modaltitle = $fau_video['video']['title'];
                    }

                    $author    = ($meta == 1) ? $fau_video['video']['author_name']   : '';
                    $copyright = ($meta == 1) ? $fau_video['video']['provider_name'] : '';

                    include(plugin_dir_path(__DIR__) . 'templates/rrze-video-widget-fau-template.php');

                }

            } else {

                // youtube or else ...
                $video_id       = $helpers->get_video_id_from_url($form_url);
                $orig_video_url = $form_url;
                if ( $form_showtitle == 1 ) {
                    $showtitle  = $form_title;
                    $modaltitle = $showtitle;
                } else {
                    $showtitle  = '';
                    $modaltitle = ( ! empty( $form_title ) ) ? $form_title : __('Ein Video');
                }
                $preview_image_opts = array(
                    'provider'   => 'youtube',
                    'id'         => $video_id                );
                $preview_image  = $helpers->video_preview_image('',$preview_image_opts);

                include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-youtube-template.php');

            }

        } else {

            // no url, so check for video post type:

            $widget_video   = $helpers->assign_wp_query_arguments($form_url, $form_id, $argumentsID, $argumentsTaxonomy);
            $genre_title    = $helpers->get_video_title($form_url, $form_id);
            $single_title   = ( $genre_title ) ? $widget_video->posts[0]->post_title : '';

            if ( $widget_video->have_posts() ) {

                while ($widget_video->have_posts()) {

                    $widget_video->the_post();

                    $helpers->enqueue_scripts();

                    $url            = get_post_meta( $post->ID, 'url', true );
                    $desc           = get_post_meta( $post->ID, 'description', true );
                    $thumbnail      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                    if ( ! empty( $thumbnail ) ) {
                        $thumbnail = $thumbnail[0]; // array (url, width, height, is_intermediate)
                    }

                    $is_fau_video   = $helpers->is_fau_video($url);

                    if ( $is_fau_video ) {

                        //fau video:
                        $fau_video = $helpers->fetch_fau_video( $url );
                        if ( $fau_video['error'] != '' ) {
                            echo '<div id="message" class="error"><p>' . $fau_video['error'] . '</p></div>';
                        } else {
                            //$video_url      = json_decode(wp_remote_retrieve_body($remote_get), true);
                            $video_file     = $fau_video['video']['file'];
                            $preview_image_opts = array(
                                'provider' => 'fau',
                                'url'      =>  $url,
                                'thumbnail'=>  $fau_video['video']['preview_image']
                            );
                            $preview_image  = $helpers->video_preview_image('',$preview_image_opts);

                            // should we show the title?
                            if ( $form_show_custom_post_title == 1 ) {
                                $showtitle = ( ! empty( $single_title ) ) ? $single_title : get_the_title();
                                $modaltitle = $showtitle;
                            } elseif ( $form_showtitle == 1 && ! empty( $form_title ) ) {
                                $showtitle = $form_title;
                                $modaltitle = $showtitle;
                            } else {
                                $showtitle  = '';
                                $modaltitle = ( ! empty( $single_title ) ) ? $single_title : get_the_title();
                            }

                            $author    = ($meta == 1) ? $fau_video['video']['author_name'] : '';
                            $copyright = ($meta == 1) ? $fau_video['video']['provider_name'] : '';

                            include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-fau-template.php');
                        }


                    } else {

                        // youtube etc video
                        $video_post_url  = get_post_meta( $post->ID, 'url', true );
                        $youtube_title   = get_the_title();

                        $video_id        = $helpers->get_video_id_from_url($video_post_url);
                        $preview_image_opts = array(
                            'provider'   => 'youtube',
                            'id'         => $video_id,
                            'url'        => $url,
                            'thumbnail'  => $thumbnail
                        );
                        $preview_image   = $helpers->video_preview_image('',$preview_image_opts);

                        $orig_video_url = $video_post_url;

                        // should we show the title?
                        if ( $form_show_custom_post_title == 1 ) {
                            $showtitle = ( ! empty( $single_title ) ) ? $single_title : get_the_title();
                            $modaltitle = $showtitle;
                        } elseif ( $form_showtitle == 1 && ! empty( $form_title ) ) {
                            $showtitle = $form_title;
                            $modaltitle = $showtitle;
                        } else {
                            $showtitle  = '';
                            $modaltitle = ( ! empty( $single_title ) ) ? $single_title : get_the_title();
                        }

                        include( plugin_dir_path( __DIR__ ) . 'templates/rrze-video-widget-youtube-template.php');

                    }

                } // endwhile

                // add players js to footer:
                add_action('wp_footer', 'RRZE\PostVideo\js_player_ajax');

            } else {

                $no_posts = '<p>' . _e( 'Es wurden keine Videos gefunden!', 'rrze-video' ) . '</p>';
                echo $no_posts;

            }

            wp_reset_postdata();
        }
        echo $after_widget;
    }


    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {

        $title                   = ! empty( $instance['title'] )                 ? $instance['title']      : '';
        $id                      = ! empty( $instance['id'] )                    ? $instance['id']         : '';
        $url                     = ! empty( $instance['url'] )                   ? $instance['url']        : '';
        $showtitle               = ! empty( $instance['showtitle'])              ? $instance['showtitle']  : '';
        $meta                    = ! empty( $instance['meta'] )                  ? $instance['meta']       : '';
        $genre                   = ! empty( $instance['genre'] )                 ? $instance['genre']      : '';
        $show_custom_post_title  = ! empty( $instance['show_custom_post_title']) ? $instance['show_custom_post_title']  : '';
        $video_src_type          = ! empty( $instance['video_src_type'])         ? $instance['video_src_type']  : '';

        // find videos of post-type "video", use for id-selector
        $output_id_select = '';
        $query_args = array(
            'post_type' => 'Video'
        );
        $local_videos = new \WP_Query($query_args);
        if ( $local_videos->have_posts() ) {
            // make a select
            $output_id_select .= '<label for="' . esc_attr( $this->get_field_id( 'id' ) ) . '">' . esc_attr( 'ID:', 'rrze-video' ) . '</label>' . PHP_EOL;
            $output_id_select .= '<select class="widefat" id="' . esc_attr( $this->get_field_id( 'id' ) ) . '" name="' . $this->get_field_name( 'id' ) . '" >' . PHP_EOL;
            $output_id_select .= '<option value="">' . __('Video aus der Mediathek auswählen','rrze-video') . '</option>';
            while ($local_videos->have_posts()) {
                $local_videos->the_post();
                $selected = ( get_the_id() == esc_attr( $id ) ) ? ' selected' : '';
                $output_id_select .= '<option value="' . get_the_id() . '"' . $selected . '>' . get_the_title() . '</option>';
            }
            $output_id_select .= '</select>' . PHP_EOL;
        } else {
            $output_id_select .= __('Es sind noch keine Videos in Ihrer Mediathek vorhanden.', 'rrze-video');
        }
        wp_reset_postdata();
        // : end post-type video videos select
        ?>

         <fieldset class="rrze-fieldset">
            <legend class="rrze-legend"><?php _e('Videotitel','rrze-video'); ?></legend>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Titel:', 'rrze-video' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                <em><?php _e('Videotitel', 'rrze-video') ?></em>
            </p>
            <p>
                <input type="checkbox" id="<?php echo $this->get_field_id( 'showtitle' ); ?>" name="<?php echo $this->get_field_name( 'showtitle' ); ?>" value="1"<?php echo ( $showtitle == '1' ) ? ' checked' : ''; ?>>
                <label for="<?php echo $this->get_field_id( 'showtitle' ); ?>"><?php _e('Videotitel als Widgettitel anzeigen?','rrze-video') ?></label>
            </p>
        </fieldset>

        <fieldset class="rrze-fieldset">
            <legend class="rrze-legend"><?php _e('Videoquelle','rrze-video'); ?></legend>
            <div class="rrze-accordeon">
                <div class="rrze-accordeon-item">
                    <input id="<?php echo esc_attr( $this->get_field_id( 'video_src_type' ) ) . '-r1'; ?>" class="rrze-accordeon-toggle" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'video_src_type' ) ); ?>" value="local"<?php echo ( $video_src_type == 'local' || !empty($id) || !empty($genre) ) ? ' checked' : ''; ?>/>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'video_src_type' ) ) . '-r1'; ?>"><?php _e('Aus Mediathek','rrze-video'); ?></label>
                    <div class="rrze-accordeon-toggle-target">
                        <p><?php _e('Bitte wählen Sie ein Video aus Ihrer lokalen Videothek aus:','rrze-video') ?></p>
                        <p><?php echo $output_id_select; ?></p>
                        <p><?php _e('Oder wählen Sie eine Videokategorie aus Ihrer lokalen Videothek aus:','rrze-video') ?></p>
                        <p>
                            <label for="<?php echo $this->get_field_id('genre'); ?>"><?php _e('Zufallsvideo nach Genre:','rrze-video'); ?></label>
                            <select class="widefat" id="<?php echo $this->get_field_id('genre'); ?>" name="<?php echo $this->get_field_name('genre'); ?>">
                            <?php
                                $terms = get_terms( array(
                                    'taxonomy' => 'genre',
                                    'hide_empty' => true,
                                ) );
                                $opts_select = 0;
                                $opts_html   = '';
                                foreach($terms as $term) {
                                    if ($term->name == $genre) {
                                        $selected = ' selected';
                                        $opts_select++;
                                    } else {
                                        $selected = '';
                                    }
                                    $opts_html .= '<option value="' . $term->name . '"' . $selected . '>' . $term->name . '</option>' . PHP_EOL;
                                }
                                $initial_selected = ($opts_select == 0) ? ' selected' : '';
                                echo '<option value="0"' . $initial_selected . '>' . __('Genre auswählen','rrze-video') . '</option>' .PHP_EOL;
                                echo $opts_html;
                            ?>
                            </select>
                        </p>
                        <p>
                            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>" name="<?php echo $this->get_field_name( 'show_custom_post_title' ); ?>" value="1"<?php echo ( $show_custom_post_title == '1' ) ? ' checked' : ''; ?>>
                            <label for="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>"><?php _e('Titel des Videothek-Videos als Widgettitel anzeigen?','rrze-video' ) ?></label><br><em>(<?php _e('Überschreibt Titel-Einstellung von oben','rrze-video'); ?>)</em>
                        </p>
                    </div>
                </div>
                <div class="rrze-accordeon-item">
                    <input id="<?php echo esc_attr( $this->get_field_id( 'video_src_type' ) ) . '-r2'; ?>" class="rrze-accordeon-toggle" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'video_src_type' ) ); ?>" value="remote"<?php echo ( $video_src_type == 'remote' || !empty($url) ) ? ' checked' : ''; ?>/>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'video_src_type' ) ) . '-r2'; ?>"><?php _e('Externe Quelle','rrze-video'); ?></label>
                    <div class="rrze-accordeon-toggle-target">
                        <p><?php _e('Geben Sie eine URL zum gewünschten Video an:','rrze-video') ?></p>
                        <p>
                            <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_attr_e( 'Url:', 'rrze-video' ); ?></label>
                            <input class="widefat code" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>">
                            <em><?php _e('z. B. http://www.video.uni-erlangen.de/webplayer/id/13953','rrze-video') ?></em>
                        </p>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="rrze-fieldset">
            <legend class="rrze-legend"><?php _e('Video Optionen','rrze-video'); ?></legend>
            <p>
                <input type="checkbox" name="<?php echo $this->get_field_name( 'meta' ); ?>" id="<?php echo $this->get_field_id( 'meta' ); ?>" value="1"<?php echo ( $meta == '1' ) ? ' checked' : ''; ?>>
                <label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e('Video-Metainformationen anzeigen?','rrze-video') ?></label>
                <br><em>(<?php _e('Autor, Copyright, Quelle und Beschreibung, falls angegeben','rrze-video') ?>)</em>
            </p>
        </fieldset>

        <?php
    }

   /*
     * Im Widget-Screen werden die alten Eingaben mit
     * den neuen Eingaben ersetzt und gespeichert.
     */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance[ 'title' ]          = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'id' ]             = strip_tags( $new_instance[ 'id' ] );
        $instance[ 'url' ]            = strip_tags( $new_instance[ 'url' ] );
        $instance[ 'showtitle' ]      = strip_tags( $new_instance[ 'showtitle' ] );
        $instance[ 'show_custom_post_title' ] = strip_tags( $new_instance[ 'show_custom_post_title' ] );
        $instance[ 'meta' ]           = strip_tags( $new_instance[ 'meta' ] );
        $instance[ 'genre' ]          = strip_tags( $new_instance[ 'genre' ] );
        $instance[ 'video_src_type' ] = strip_tags( $new_instance[ 'video_src_type' ] );

        return $instance;
    }
}
