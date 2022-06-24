<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\Player;

/**
 * Class Widget
 * @package RRZE\Video
 */
class Widget extends \WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'video_widget',
            'description' => __('Displays videos in the sidebar or in the footer.', 'rrze-video'),
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
        extract($args);

        echo $before_widget;

        $arguments = [];

        $arguments['id'] = $instance['id'] ?? '';
        $arguments['url'] = $instance['url'] ?? '';
        $arguments['rand'] = $instance['genre'] ?? '';
        $arguments['widget_title'] = !empty($instance['widget_title']) ? sanitize_text_field($instance['widget_title']) : '';

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
            'rrze_video_player_content',
            Player::instance()->get_player($arguments),
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
        $id = $instance['id'] ?? '';
        $url = $instance['url'] ?? '';
        $rand = $instance['genre'] ?? '';
        $title = !empty($instance['widget_title']) ? esc_html($instance['widget_title']) : '';

        $meta = !empty($instance['meta']) ? 1 : 0;
        $showtitle = !empty($instance['showtitle']) ? 1 : 0;
        $info = !empty($instance['info']) ? 1 : 0;
        $desc = !empty($instance['desc']) ? 1 : 0;
        $link = !empty($instance['link']) ? 1 : 0;

        $novideos = false;

        // find videos of post-type "video", use for id-selector
        $output_id_select = '';
        $query_args = array(
            'post_type' => 'video'
        );
        $local_videos = new \WP_Query($query_args);
        if ($local_videos->have_posts()) {
            // make a select
            $output_id_select .= '<label for="' . esc_attr($this->get_field_id('id')) . '">' . __('Please select a video from your local video library:', 'rrze-video') . '</label>' . PHP_EOL;
            $output_id_select .= '<select class="widefat" id="' . esc_attr($this->get_field_id('id')) . '" name="' . $this->get_field_name('id') . '" >' . PHP_EOL;
            $output_id_select .= '<option value="">' . __('Select a video from the video library', 'rrze-video') . '</option>';
            while ($local_videos->have_posts()) {
                $local_videos->the_post();
                $selected = (get_the_id() == esc_attr($id)) ? ' selected' : '';
                $output_id_select .= '<option value="' . get_the_id() . '"' . $selected . '>' . get_the_title() . '</option>';
            }
            $output_id_select .= '</select>' . PHP_EOL;
        } else {
            $output_id_select .= __('There are no videos in your video library yet.', 'rrze-video');
            $novideos = true;
        }
        wp_reset_postdata();
        // : end post-type video videos select
?>
        <div class="rrze-video-admin">
            <fieldset>
                <legend><?php _e('Video source', 'rrze-video'); ?></legend>
                <div class="rrze-accordeon">
                    <p><strong><?php _e('Selection via URL', 'rrze-video'); ?>:</strong></p>

                    <div class="remotevideo-setting">
                        <p><?php _e('Enter a URL to the video you want:', 'rrze-video') ?></p>
                        <label for="<?php echo esc_attr($this->get_field_id('url')); ?>"><?php _e('URL', 'rrze-video'); ?>:</label>
                        <input class="widefat code" id="<?php echo esc_attr($this->get_field_id('url')); ?>" name="<?php echo esc_attr($this->get_field_name('url')); ?>" type="text" value="<?php echo esc_attr($url); ?>">

                        <p><em><?php _e('e.g. https://www.video.uni-erlangen.de/webplayer/id/13953', 'rrze-video') ?></em><br>
                            <?php _e('Supported video sources are currently: FAU video portal, YouTube and Vimeo.', 'rrze-video') ?>
                        </p>
                    </div>

                    <?php if (!$novideos) { ?>

                        <p><strong><?php _e('Alternative chose a video by list', 'rrze-video'); ?>:</strong></p>
                        <div class="localvideo-setting">
                            <?php echo $output_id_select; ?>
                            <p>
                                <label for="<?php echo $this->get_field_id('genre'); ?>"><?php _e('Or select a category from your local video library to display a random video from this:', 'rrze-video'); ?></label>
                                <select class="widefat" id="<?php echo $this->get_field_id('genre'); ?>" name="<?php echo $this->get_field_name('genre'); ?>">
                                    <?php
                                    $terms = get_terms([
                                        'taxonomy' => 'genre',
                                        'hide_empty' => true,
                                    ]);
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
                                    echo '<option value="0"' . $initial_selected . '>' . __('Choose a category', 'rrze-video') . '</option>' . PHP_EOL;
                                    echo $opts_html;
                                    ?>
                                </select>
                            </p>

                        </div>

                    <?php } ?>

                </div>
            </fieldset>
            <fieldset class="displayoptions">
                <legend><?php _e('Video options', 'rrze-video'); ?></legend>
                <p>
                    <label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Widget Title:', 'rrze-video'); ?></label><br>
                    <input class="widefat" type='text' id='<?php echo $this->get_field_id('widget_title'); ?>' name='<?php echo $this->get_field_name('widget_title'); ?>' value='<?php echo $title; ?>'>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('showtitle'); ?>" id="<?php echo $this->get_field_id('showtitle'); ?>" value="1" <?php checked($showtitle, 1); ?>>
                    <label for="<?php echo $this->get_field_id('showtitle'); ?>"><?php _e('Show title', 'rrze-video') ?></label>
                    <br><em><?php _e('Show video title above the video; override widget title if set.', 'rrze-video') ?></em>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('link'); ?>" id="<?php echo $this->get_field_id('link'); ?>" value="1" <?php checked($link, 1); ?>>
                    <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link to source', 'rrze-video') ?></label>
                    <br><em><?php _e('Provide a link to the original source at the video provider.', 'rrze-video') ?></em>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('meta'); ?>" id="<?php echo $this->get_field_id('meta'); ?>" value="1" <?php checked($meta, 1); ?>>
                    <label for="<?php echo $this->get_field_id('meta'); ?>"><?php _e('Show metadata', 'rrze-video') ?></label>
                    <br><em><?php _e('Author, copyright, link to source and description if given.', 'rrze-video') ?></em>
                </p>
                <p>
                    <input type="checkbox" name="<?php echo $this->get_field_name('info'); ?>" id="<?php echo $this->get_field_id('info'); ?>" value="1" <?php checked($info, 1); ?>>
                    <label for="<?php echo $this->get_field_id('info'); ?>"><?php _e('Show complete information', 'rrze-video') ?></label>
                    <br><em><?php _e('Metadata, link to source and description if given.', 'rrze-video') ?></em>
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
        $id = $new_instance['id'] ?? '';
        $url = $new_instance['url'] ?? '';
        $genre = $new_instance['genre'] ?? '';
        $title = !empty($new_instance['widget_title']) ? sanitize_text_field($new_instance['widget_title']) : '';

        $meta = !empty($new_instance['meta']) ? true : false;
        $showtitle = !empty($new_instance['showtitle']) ? true : false;
        $info = !empty($new_instance['info']) ? true : false;
        $desc = !empty($new_instance['desc']) ? true : false;
        $link = !empty($new_instance['link']) ? true : false;

        $instance = $old_instance;

        $instance['id'] = sanitize_key($id);
        $instance['url'] = esc_url_raw($url);
        $instance['genre'] = strip_tags($genre);
        $instance['widget_title'] =  esc_html($title);

        $instance['meta'] = $meta;
        $instance['showtitle'] = $showtitle;
        $instance['info'] = $info;
        $instance['desc'] = $desc;
        $instance['link'] = $link;

        return $instance;
    }
}
