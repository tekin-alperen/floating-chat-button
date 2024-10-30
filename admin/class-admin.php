<?php
/**
 * The admin-specific functionality of the plugin.
 */
class Floating_Chat_Button_Admin {

    /**
     * Initialize the class
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our settings page
        if ('settings_page_floating-chat-button' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        wp_enqueue_style(
            'floating-chat-button-admin',
            FLOATING_CHAT_BUTTON_URL . 'admin/css/admin.css',
            array(),
            FLOATING_CHAT_BUTTON_VERSION
        );

        wp_enqueue_script(
            'floating-chat-button-admin',
            FLOATING_CHAT_BUTTON_URL . 'admin/js/admin.js',
            array('jquery', 'wp-color-picker'),
            FLOATING_CHAT_BUTTON_VERSION,
            true
        );
    }

    /**
     * Add plugin menu item to admin
     */
    public function add_plugin_menu() {
        add_options_page(
            __('Floating Chat Button Settings', 'floating-chat-button'),
            __('Floating Chat', 'floating-chat-button'),
            'manage_options',
            'floating-chat-button',
            array($this, 'display_plugin_settings_page')
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            'floating_chat_button_options',
            'floating_chat_button_settings',
            array($this, 'sanitize_settings')
        );

        add_settings_section(
            'floating_chat_button_general',
            __('General Settings', 'floating-chat-button'),
            array($this, 'render_section_general'),
            'floating-chat-button'
        );

        // Phone Number
        add_settings_field(
            'phone_number',
            __('Phone Number', 'floating-chat-button'),
            array($this, 'render_field_phone_number'),
            'floating-chat-button',
            'floating_chat_button_general'
        );

        // Button Position
        add_settings_field(
            'button_position',
            __('Button Position', 'floating-chat-button'),
            array($this, 'render_field_button_position'),
            'floating-chat-button',
            'floating_chat_button_general'
        );

        // Custom Message
        add_settings_field(
            'custom_message',
            __('Custom Message', 'floating-chat-button'),
            array($this, 'render_field_custom_message'),
            'floating-chat-button',
            'floating_chat_button_general'
        );

        // Button Color
        add_settings_field(
            'button_color',
            __('Button Color', 'floating-chat-button'),
            array($this, 'render_field_button_color'),
            'floating-chat-button',
            'floating_chat_button_general'
        );

        // Active Status
        add_settings_field(
            'is_active',
            __('Enable Button', 'floating-chat-button'),
            array($this, 'render_field_is_active'),
            'floating-chat-button',
            'floating_chat_button_general'
        );
    }

    /**
     * Sanitize settings before saving
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        if (isset($input['phone_number'])) {
            $sanitized['phone_number'] = sanitize_text_field($input['phone_number']);
        }
        
        if (isset($input['button_position'])) {
            $sanitized['button_position'] = sanitize_text_field($input['button_position']);
        }
        
        if (isset($input['custom_message'])) {
            $sanitized['custom_message'] = sanitize_textarea_field($input['custom_message']);
        }
        
        if (isset($input['button_color'])) {
            $sanitized['button_color'] = sanitize_hex_color($input['button_color']);
        }
        
        $sanitized['is_active'] = isset($input['is_active']) ? 1 : 0;
        
        return $sanitized;
    }

    /**
     * Render section description
     */
    public function render_section_general() {
        echo '<p>' . __('Configure your floating chat button settings below.', 'floating-chat-button') . '</p>';
    }

    /**
     * Render phone number field
     */
    public function render_field_phone_number() {
        $options = get_option('floating_chat_button_settings');
        $phone = isset($options['phone_number']) ? $options['phone_number'] : '';
        ?>
        <input type="text" 
               name="floating_chat_button_settings[phone_number]" 
               value="<?php echo esc_attr($phone); ?>" 
               class="regular-text"
               placeholder="phone number"
        />
        <p class="description"><?php _e('Enter phone number without spaces or special characters', 'floating-chat-button'); ?></p>
        <?php
    }

    /**
     * Render button position field
     */
    public function render_field_button_position() {
        $options = get_option('floating_chat_button_settings');
        $position = isset($options['button_position']) ? $options['button_position'] : 'right';
        ?>
        <select name="floating_chat_button_settings[button_position]">
            <option value="left" <?php selected($position, 'left'); ?>><?php _e('Left', 'floating-chat-button'); ?></option>
            <option value="right" <?php selected($position, 'right'); ?>><?php _e('Right', 'floating-chat-button'); ?></option>
        </select>
        <?php
    }

    /**
     * Render custom message field
     */
    public function render_field_custom_message() {
        $options = get_option('floating_chat_button_settings');
        $message = isset($options['custom_message']) ? $options['custom_message'] : '';
        ?>
        <textarea name="floating_chat_button_settings[custom_message]" 
                  class="large-text" 
                  rows="3"><?php echo esc_textarea($message); ?></textarea>
        <p class="description"><?php _e('Enter the message that will be pre-filled in chat', 'floating-chat-button'); ?></p>
        <?php
    }

    /**
     * Render button color field
     */
    public function render_field_button_color() {
        $options = get_option('floating_chat_button_settings');
        $color = isset($options['button_color']) ? $options['button_color'] : '#25D366';
        ?>
        <div class="color-picker-container">
            <input type="text" 
                   name="floating_chat_button_settings[button_color]" 
                   value="<?php echo esc_attr($color); ?>" 
                   class="color-picker"
            />
            <button type="button" class="button button-secondary reset-color">
                <?php _e('Reset to Default', 'floating-chat-button'); ?>
            </button>
        </div>
        <?php
    }

    /**
     * Render active status field
     */
    public function render_field_is_active() {
        $options = get_option('floating_chat_button_settings');
        $is_active = isset($options['is_active']) ? $options['is_active'] : 1;
        ?>
        <label>
            <input type="checkbox" 
                   name="floating_chat_button_settings[is_active]" 
                   value="1" 
                   <?php checked($is_active, 1); ?>
            />
            <?php _e('Enable floating chat button', 'floating-chat-button'); ?>
        </label>
        <?php
    }

    /**
     * Display the plugin settings page
     */
    public function display_plugin_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('floating_chat_button_options');
                do_settings_sections('floating-chat-button');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Add settings link to plugin page
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=floating-chat-button') . '">' . __('Settings', 'floating-chat-button') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}