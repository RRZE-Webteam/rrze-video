<?php
namespace RRZE\PostVideo;

add_action('admin_menu', 'RRZE\PostVideo\rrze_video_plugin_admin_settings');
function rrze_video_plugin_admin_settings()
{

    add_options_page('RRZE Video Plugin', 'RRZE Video Plugin', 'manage_options', 'rrze_video_settings', 'RRZE\PostVideo\rrze_video_option_page');
    add_action('admin_init', 'RRZE\PostVideo\rrze_video_settings');

}

function rrze_video_option_page()
{ ?>

<div class="wrap">
    <h1><?php esc_html_e('RRZE Video Einstellungen'); ?></h1>
    <form action="options.php" method="post">
        <?php settings_fields('rrze_video_plugin_options');?>
        <?php do_settings_sections('rrze_video_youtube_plugin');?>
        <p><button class="button button-primary"><?php esc_html_e('Änderungen speichern') ?></button></p>
    </form>
</div>

<?php }

// ADMIN SETTINGS

function rrze_video_settings()
{

    //register_setting( $option_group, $option_name );
    register_setting( 'rrze_video_plugin_options', 'rrze_video_plugin_options' );
    add_settings_section('plugin_main', esc_html__('Allgemeine Einstellungen'), 'RRZE\PostVideo\rrze_video_section_general_settings', 'rrze_video_youtube_plugin');

}

function rrze_video_section_general_settings()
{

    add_settings_field('rrze_video_settings_yt_player', esc_html__('Youtube Player aktivieren'), 'RRZE\PostVideo\rrze_video_checkbox_settings_youtube_player_cb', 'rrze_video_youtube_plugin', 'plugin_main', array('label_for'=>'rrze_video_settings_yt_player') );
    add_settings_field('rrze_video_settings_preview_image', esc_html__('Pfad zum Vorschaubild'), 'RRZE\PostVideo\rrze_video_settings_input_preview_image_cb', 'rrze_video_youtube_plugin', 'plugin_main', array('label_for'=>'rrze_video_settings_preview_image') );

}

// settings_fields callbacks:
function rrze_video_checkbox_settings_youtube_player_cb()
{

    // YT player support option
    $options = get_option( 'rrze_video_plugin_options' );
    $checked = ( isset($options['youtube_activate_checkbox']) && $options['youtube_activate_checkbox'] == 1) ? 1 : 0;
    $html = '<input type="checkbox" id="rrze_video_settings_yt_player" name="rrze_video_plugin_options[youtube_activate_checkbox]" value="1"' . checked( 1, $checked, false ) . '/>' . PHP_EOL;

    echo $html;
}

function rrze_video_settings_input_preview_image_cb()
{

    // Default placeholder image
    $options = get_option( 'rrze_video_plugin_options' );
    if(isset($options['preview_image']) && $options['preview_image'] != '' ){
        $preview_image = $options['preview_image'];
    } else {
        $preview_image = plugin_dir_url(__DIR__) . 'assets/img/_preview.png';
    }
    $html = '<input type="text" name="rrze_video_plugin_options[preview_image]" value="' . $preview_image . '" id="rrze_video_settings_preview_image" />' . PHP_EOL;
    echo $html;

}
