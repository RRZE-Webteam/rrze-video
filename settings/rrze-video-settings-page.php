<?php
namespace RRZE\PostVideo;
add_action('admin_menu', 'RRZE\PostVideo\rrze_video_plugin_admin_settings');
function rrze_video_plugin_admin_settings() {

    add_options_page('RRZE Video Plugin', 'RRZE Video Plugin', 'manage_options', 'rrze_video_settings', 'RRZE\PostVideo\rrze_video_option_page');
    add_action('admin_init', 'RRZE\PostVideo\rrze_video_settings');

}
?>
<?php function rrze_video_option_page(){?>
<div>

    <form action="options.php" method="post">

        <?php settings_fields('rrze_video_plugin_options');?>
        <?php do_settings_sections('rrze_video_youtube_plugin');?>
        <p>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e('Änderungen speichern') ?>"  />
        </p>
    </form>

</div>

<?php } ?>
<?php

// ADMIN SETTINGS


function rrze_video_settings(){

    register_setting( 'rrze_video_plugin_options', 'rrze_video_plugin_options' );
    add_settings_section('plugin_main', 'RRZE Video Einstellungen', 'RRZE\PostVideo\rrze_video_section_text', 'rrze_video_youtube_plugin');
    add_settings_field('Checkbox Element', 'Aktivieren', 'RRZE\PostVideo\rrze_video_checkbox_element_callback', 'rrze_video_youtube_plugin', 'plugin_main' );

}

function rrze_video_checkbox_element_callback() {

    $options = get_option( 'rrze_video_plugin_options' );

    $checked = ( isset($options['youtube_activate_checkbox']) && $options['youtube_activate_checkbox'] == 1) ? 1 : 0;

    $html = '<input type="checkbox" id="youtube_activate_checkbox" name="rrze_video_plugin_options[youtube_activate_checkbox]" value="1"' . checked( 1, $checked, false ) . '/>';

    echo $html;
}
?>
<?php 

function rrze_video_section_text() {
    
    echo '<p>' . esc_html_e(' Aktivierung des Youtube Players') . '</p>';
    
}



function plugin_rvce_options_validate($input) {
    
    $options = get_option('rrze_video_plugin_options');
    return $options;
    
}