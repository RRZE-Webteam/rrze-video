<?php


namespace RRZE\Video;

use RRZE\Video\Data;
use RRZE\Video\Player;

defined('ABSPATH') || exit;


/**
 * Laden und definieren der Posttypes
 */
class Widget extends Main
{
    protected $pluginFile;
    private $settings = '';

    public function __construct($pluginFile, $settings)
    {
        $this->pluginFile = $pluginFile;
        $this->settings = $settings;
    }

    public function onLoaded()
    {
        add_action('register_sidebar', array($this, 'register_widget'));
    }

    public function register_widget()
    {
        register_widget('RRZE\Video\Video_Widget');
    }
}


class Video_Widget extends \WP_Widget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'video_widget',
            'description' => __('Zeigt Videos in der Sidebar oder im Footer an.', 'rrze-video'),
        );
        parent::__construct('video_widget', 'RRZE Video Widget', $widget_ops);
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        global $post;
        extract($args);

        echo $before_widget;

        $arguments = array();

        $arguments['id']    = (!empty($instance['id'])) ? $instance['id'] : '';
        $arguments['url']   = (!empty($instance['url'])) ? $instance['url'] : '';
        $arguments['rand']   = (!empty($instance['genre'])) ? $instance['genre'] : '';
        $arguments['widget_title']   = (!empty($instance['widget_title'])) ? sanitize_text_field($instance['widget_title']) : '';


        $arguments['show'] = '';
        if (!empty($instance['showtitle'])) {
            $arguments['show'] .= "title,";
        }
        if (!empty($instance['meta'])) {
            $arguments['show'] .= "meta,";
        }
        if (!empty($instance['link'])) {
            $arguments['show'] .= "link,";
        }
        if (!empty($instance['info'])) {
            $arguments['show'] .= "info,";
        }
        if (!empty($instance['desc'])) {
            $arguments['show'] .= "desc";
        }

        $arguments = Data::sanitize_shortcodeargs($arguments);
        if (!empty($instance['widget_title'])) {
            $arguments['widgetargs']['title'] = $instance['widget_title'];
        }
        if (!empty($before_title)) {
            $arguments['widgetargs']['before'] = $before_title;
        }
        if (!empty($after_title)) {
            $arguments['widgetargs']['after'] = $after_title;
        }



        echo apply_filters(
            'rrze_video_widget_player_content',
            Player::get_player($arguments),
            $arguments
        );

        echo $after_widget;
    }


    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        $id    = !empty($instance['id'])  ? $instance['id'] : '';
        $url    = !empty($instance['url']) ? $instance['url'] : '';
        $rand    = !empty($instance['genre']) ? $instance['genre'] : '';
        $title = isset($instance['widget_title']) ? esc_html($instance['widget_title']) : '';

        $meta = isset($instance['meta']) ? true : false;
        $showtitle = isset($instance['showtitle']) ? true : false;
        $info = isset($instance['info']) ? true : false;
        $desc = isset($instance['desc']) ? true : false;
        $link = isset($instance['link']) ? true : false;





        $novideos = false;


        // find videos of post-type "video", use for id-selector
        $output_id_select = '';
        $query_args = array(
            'post_type' => 'video'
        );
        $local_videos = new \WP_Query($query_args);
        if ($local_videos->have_posts()) {
            // make a select
            $output_id_select .= '<label for="' . esc_attr($this->get_field_id('id')) . '">' . __('Bitte wählen Sie ein Video aus Ihrer lokalen Videothek aus:', 'rrze-video') . '</label>' . PHP_EOL;
            $output_id_select .= '<select class="widefat" id="' . esc_attr($this->get_field_id('id')) . '" name="' . $this->get_field_name('id') . '" >' . PHP_EOL;
            $output_id_select .= '<option value="">' . __('Video aus der Mediathek auswählen', 'rrze-video') . '</option>';
            while ($local_videos->have_posts()) {
                $local_videos->the_post();
                $selected = (get_the_id() == esc_attr($id)) ? ' selected' : '';
                $output_id_select .= '<option value="' . get_the_id() . '"' . $selected . '>' . get_the_title() . '</option>';
            }
            $output_id_select .= '</select>' . PHP_EOL;
        } else {
            $output_id_select .= __('Es sind noch keine Videos in Ihrer Mediathek vorhanden.', 'rrze-video');
            $novideos = true;
        }
        wp_reset_postdata();
        // : end post-type video videos select
?>


        <div class="rrze-video-admin">
            <fieldset>
                <legend><?php _e('Videoquelle', 'rrze-video'); ?></legend>
                <div class="rrze-accordeon">
                    <p><strong><?php _e('Auswahl über URL', 'rrze-video'); ?>:</strong></p>

                    <div class="remotevideo-setting">
                        <p><?php _e('Geben Sie eine URL zum gewünschten Video an:', 'rrze-video') ?></p>
                        <label for="<?php echo esc_attr($this->get_field_id('url')); ?>"><?php _e('URL', 'rrze-video'); ?>:</label>
                        <input class="widefat code" id="<?php echo esc_attr($this->get_field_id('url')); ?>" name="<?php echo esc_attr($this->get_field_name('url')); ?>" type="text" value="<?php echo esc_attr($url); ?>">

                        <p><em><?php _e('z. B. https://www.video.uni-erlangen.de/webplayer/id/13953', 'rrze-video') ?></em><br>
                            Unterstützte Video-Quellen sind derzeit: Videoportal der FAU, YouTube, Vimeo.
                        </p>
                    </div>

                    <?php if (!$novideos) { ?>

                        <p><strong><?php _e('Alternativ Auswahl über Videothek', 'rrze-video'); ?>:</strong></p>
                        <div class="localvideo-setting">
                            <?php echo $output_id_select; ?>
                            <p>
                                <label for="<?php echo $this->get_field_id('genre'); ?>"><?php _e('Oder wählen Sie eine Kategorie aus Ihrer lokalen Videothek aus um aus dieser ein zufälliges Video anzuzeigen:', 'rrze-video'); ?></label>
                                <select class="widefat" id="<?php echo $this->get_field_id('genre'); ?>" name="<?php echo $this->get_field_name('genre'); ?>">
                                    <?php
                                    $terms = get_terms(array(
                                        'taxonomy' => 'genre',
                                        'hide_empty' => true,
                                    ));
                                    $opts_select = 0;
                                    $opts_html   = '';
                                    foreach ($terms as $term) {
                                        if ($term->name == $rand) {
                                            $selected = ' selected';
                                            $opts_select++;
                                        } else {
                                            $selected = '';
                                        }
                                        $opts_html .= '<option value="' . $term->name . '"' . $selected . '>' . $term->name . '</option>' . PHP_EOL;
                                    }
                                    $initial_selected = ($opts_select == 0) ? ' selected' : '';
                                    echo '<option value="0"' . $initial_selected . '>' . __('Kategorie auswählen', 'rrze-video') . '</option>' . PHP_EOL;
                                    echo $opts_html;
                                    ?>
                                </select>
                            </p>

                        </div>

                    <?php } ?>



                </div>
            </fieldset>
            <fieldset class="displayoptions">
                <legend><?php _e('Video Optionen', 'rrze-video'); ?></legend>
                <p>
                    <label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Widget Titel:', 'rrze-video'); ?></label><br>
                    <input class="widefat" type='text' id='<?php echo $this->get_field_id('widget_title'); ?>' name='<?php echo $this->get_field_name('widget_title'); ?>' value='<?php echo $title; ?>'>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('showtitle'); ?>" id="<?php echo $this->get_field_id('showtitle'); ?>" value="1" <?php checked($showtitle, 1); ?>>
                    <label for="<?php echo $this->get_field_id('showtitle'); ?>"><?php _e('Titel anzeigen', 'rrze-video') ?></label>
                    <br><em><?php _e('Videotitel oberhalb des Videos anzeigen; überschreibt Widget-Titel falls gesetzt.', 'rrze-video') ?></em>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('link'); ?>" id="<?php echo $this->get_field_id('link'); ?>" value="1" <?php checked($link, 1); ?>>
                    <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link auf Quelle', 'rrze-video') ?></label>
                    <br><em><?php _e('Link zur Originalquelle bei dem Videoprovider angeben', 'rrze-video') ?></em>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('meta'); ?>" id="<?php echo $this->get_field_id('meta'); ?>" value="1" <?php checked($meta, 1); ?>>
                    <label for="<?php echo $this->get_field_id('meta'); ?>"><?php _e('Metaangaben anzeigen', 'rrze-video') ?></label>
                    <br><em><?php _e('Autor, Copyright, Quelle und Beschreibung, falls angegeben', 'rrze-video') ?></em>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('info'); ?>" id="<?php echo $this->get_field_id('info'); ?>" value="1" <?php checked($info, 1); ?>>
                    <label for="<?php echo $this->get_field_id('info'); ?>"><?php _e('Vollständige Info angeben', 'rrze-video') ?></label>
                    <br><em><?php _e('Metaangaben, Link und Beschreibung ausgeben', 'rrze-video') ?></em>
                </p>
            </fieldset>
        </div>
<?php
    }

    /*
     * Im Widget-Screen werden die alten Eingaben mit
     * den neuen Eingaben ersetzt und gespeichert.
     */
    public function update($new_instance, $old_instance)
    {

        $instance = $old_instance;
        $instance['id']             = sanitize_key($new_instance['id']);
        $instance['url']            = esc_url_raw($new_instance['url']);
        $instance['widget_title']   = sanitize_text_field($new_instance['widget_title']);
        $instance['meta']           =  $new_instance['meta'];
        $instance['showtitle']      =  $new_instance['showtitle'];
        $instance['desc']           =  $new_instance['desc'];
        $instance['link']           = $new_instance['link'];
        $instance['info']           = $new_instance['info'];
        $instance['genre']          = strip_tags($new_instance['genre']);


        return $instance;
    }
}
