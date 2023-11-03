<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

class Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'addOption']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addSettingsPage()
    {
        add_submenu_page(
            'options-general.php',
            __('Video Settings', 'rrze-video'),
            __('RRZE Video Settings', 'rrze-video'),
            'manage_options',
            'rrze-video',
            [$this, 'renderSettingsPage']
        );
    }

    public function renderSettingsPage()
    {
        ?>
            <div class="wrap">
                <h1><?php echo get_admin_page_title(); ?></h1>
                <p class="about-text"><?php _e("Settings options for the RRZE Video plugin.", "rrze-video"); ?></p>

                <hr />
                
                <!-- Form to enter the API token -->
                <form action="options.php" method="post">
                    <?php
                    settings_fields('rrze-video-settings-group');
                    do_settings_sections('rrze-video');
                    submit_button();
                    ?>
                </form>
                
            </div>
        <?php
    }


    public function registerSettings()
    {
        register_setting(
            'rrze-video-settings-group',
            'rrze_video_api_key',
            array(
                'type' => 'string',
                'sanitize_callback' => [$this, 'sanitizeApiKey'],
                'default' => NULL,
            )
        );

        add_settings_section(
            'rrze-video-settings-section',
            'API Token',
            [$this, 'settingsSectionCallback'],
            'rrze-video'
        );

        add_settings_field(
            'rrze-video-api-token-field',
            'API Token',
            [$this, 'apiTokenFieldCallback'],
            'rrze-video',
            'rrze-video-settings-section'
        );
    }


    public function addOption()
    {
        add_option(
            'rrze_video_api_key'
        );
    }

    public function settingsSectionCallback()
    {
        echo 'Enter your API token below:';
    }

    public function apiTokenFieldCallback()
    {
        $token = get_option('rrze_video_api_key');
        $displayValue = !empty($token) ? str_repeat('*', 45) : ''; 
        echo "<input type='text' name='rrze_video_api_key' value='" . esc_attr($displayValue) . "' />";
    }


    public function updateToken($newToken)
    {
        if (empty($newToken)) {
            return;
        }
        $newToken = sanitize_text_field($newToken);

        update_option(
            'rrze_video_api_key',
            $newToken
        );
    }

    public function sanitizeApiKey($input)
    {
        // If input is a masked value, return the existing token
        if ($input === str_repeat('*', 45)) {
            return get_option('rrze_video_api_key');
        }
        // Otherwise, sanitize and return the new input value
        return sanitize_text_field($input);
    }

}
