<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

class Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'initializeSettings']);
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

    public function initializeSettings()
    {
        $this->addOption();
        $this->registerSettings();
    }

    public function addOption()
    {
        if (!get_option('rrze_video_api_key')) {
            add_option('rrze_video_api_key', '', '', 'yes');
        }
    }

    public function registerSettings()
    {
        register_setting(
            'rrze-video-settings-group',
            'rrze_video_api_key',
            [
                'type' => 'string',
                'sanitize_callback' => [$this, 'sanitizeApiKey'],
                'default' => null,
            ]
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

    public function settingsSectionCallback()
    {
        echo 'Enter your API token below:';
    }

    public function apiTokenFieldCallback()
    {
        $token = get_option('rrze_video_api_key');
        $displayValue = !empty($token) ? str_repeat('*', 48) : ''; 
        echo "<input type='text' name='rrze_video_api_key' value='" . esc_attr($displayValue) . "' />";
    }

    public function sanitizeApiKey($input)
    {
        $data_encryption = new FSD_Data_Encryption();
        $encrypted_api_key = get_option('rrze_video_api_key');

        // If input is a masked value, return the existing token
        if ($input === str_repeat('*', 48)) {
            return $encrypted_api_key;
        }

        // Sanitize and encrypt the new input value
        $input = sanitize_text_field($input);
        return $data_encryption->encrypt($input);
    }
}
