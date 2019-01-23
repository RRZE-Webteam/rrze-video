<?php
namespace RRZE\PostVideo;

add_action('admin_menu', 'RRZE\PostVideo\rrze_video_plugin_admin_settings');
function rrze_video_plugin_admin_settings()
{

    add_options_page('RRZE Video Plugin', 'RRZE Video Plugin', 'manage_options', 'rrze_video_settings', 'RRZE\PostVideo\rrze_video_option_page');
    add_action('admin_init', 'RRZE\PostVideo\rrze_video_settings');

}

function rrze_video_option_page()
{
?>

<div class="wrap">
    <h1><?php esc_html_e('Einstellungen › RRZE Video Plugin'); ?></h1>
    <form action="options.php" method="post">
        <?php settings_fields('rrze_video_plugin_options');?>
        <?php do_settings_sections('rrze_video_youtube_setting');?>
        <?php do_settings_sections('rrze_video_preview_image_setting');?>
        <p class="submit"><button class="button button-primary"><?php esc_html_e('Änderungen speichern') ?></button></p>
    </form>
</div>

<?php }

// ADMIN SETTINGS

function rrze_video_settings()
{

    //register_setting( $option_group, $option_name );
    register_setting( 'rrze_video_plugin_options', 'rrze_video_plugin_options' );

    add_settings_section('rrze_video_section_youtube', esc_html__('Youtube Player'), 'RRZE\PostVideo\rrze_video_section_youtube_settings_info', 'rrze_video_youtube_setting');
    add_settings_field('rrze_video_settings_yt_player', esc_html__('Youtube Player aktivieren'), 'RRZE\PostVideo\rrze_video_checkbox_settings_youtube_player_cb', 'rrze_video_youtube_setting', 'rrze_video_section_youtube', array('label_for'=>'rrze_video_settings_yt_player') );

    add_settings_section('rrze_video_section_preview_image', esc_html__('Vorschaubild Fallback'), 'RRZE\PostVideo\rrze_video_section_preview_image_settings_info', 'rrze_video_preview_image_setting');
    add_settings_field('rrze_video_settings_preview_image', esc_html__('Pfad zum Vorschaubild'), 'RRZE\PostVideo\rrze_video_settings_input_preview_image_cb', 'rrze_video_preview_image_setting', 'rrze_video_section_preview_image', array('label_for'=>'rrze_video_settings_preview_image') );

}

function rrze_video_section_youtube_settings_info()
{
    echo '<p>' . __('Falls der Default YouTube-Player statt des WordPress Mediaelement-Players genutzt werden soll, hier aktivieren') . '</p>';
}
function rrze_video_section_preview_image_settings_info()
{
    $options = get_option( 'rrze_video_plugin_options' );
    $preview_image_default = plugin_dir_url(__DIR__) . 'assets/img/_preview.png';
    if(esc_url($options['preview_image'])){
        $preview_image_default = $options['preview_image'];
    }
    $site_address  = wp_parse_url( get_site_url() );
    $image_address = wp_parse_url( $preview_image_default );
    if( $site_address['scheme'] == $image_address['scheme'] && $site_address['host'] == $image_address['host'] ){
        $img_preview_html = '<img src="' . $preview_image_default . '" alt="" style="width: 200px; height:auto; max-width:100%;"/>';
    } else {
        $img_preview_html = __('Bild ansehen');
    }
    $html = '<p class="hint">' . sprintf(__('Aktuell gewähltes Bild ist: <code>%s</code>'),$preview_image_default) . ':</p>' . PHP_EOL;
    $html .= '<div class="preview-image"><a href="' . $preview_image_default . '" target="_blank" rel="noopener">' . $img_preview_html . '</a></div>' . PHP_EOL;
    echo $html;
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
        $preview_image = '';
    }
    $html = '<input class="regular-text code" type="text" name="rrze_video_plugin_options[preview_image]" value="' . $preview_image . '" id="rrze_video_settings_preview_image" />' . PHP_EOL;
    echo $html;

}
