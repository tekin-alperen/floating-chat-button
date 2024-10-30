<?php
/**
 * Floating Chat Button
 *
 * @package     Floating_Chat_Button
 * @author      Alperen Tekin
 * @copyright   2024 A4T7 Software
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Floating Chat Button
 * Plugin URI:  https://yourwebsite.com/floating-chat-button
 * Description: Add a floating chat button to your website with customizable options.
 * Version:     1.0.0
 * Author:      Alperen Tekin
 * Author URI:  https://github.com/tekin-alperen
 * Text Domain: floating-chat-button
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Plugin version
define('FLOATING_CHAT_BUTTON_VERSION', '1.0.0');

// Plugin directory path
define('FLOATING_CHAT_BUTTON_DIR', plugin_dir_path(__FILE__));

// Plugin directory URL
define('FLOATING_CHAT_BUTTON_URL', plugin_dir_url(__FILE__));

/**
 * The core plugin class
 */
class Floating_Chat_Button {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Floating_Chat_Button    $instance    The single instance of the class.
     */
    private static $instance = null;

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Floating_Chat_Button_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * Get an instance of the plugin
     *
     * @return    Floating_Chat_Button    The single instance of this class
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initialize the plugin
     */
    private function __construct() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        // Include the loader class
        require_once FLOATING_CHAT_BUTTON_DIR . 'includes/class-loader.php';
        
        // Include admin and public classes
        require_once FLOATING_CHAT_BUTTON_DIR . 'admin/class-admin.php';
        require_once FLOATING_CHAT_BUTTON_DIR . 'public/class-public.php';

        $this->loader = new Floating_Chat_Button_Loader();
    }

    /**
     * Register admin hooks
     */
    private function define_admin_hooks() {
        $plugin_admin = new Floating_Chat_Button_Admin();
        
        // Add menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_menu');
        
        // Register settings
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        
        // Add settings link to plugins page
        $this->loader->add_filter('plugin_action_links_' . plugin_basename(__FILE__), $plugin_admin, 'add_settings_link', 10, 2);
    }

    /**
     * Register public hooks
     */
    private function define_public_hooks() {
        $plugin_public = new Floating_Chat_Button_Public();
        
        // Enqueue styles and scripts
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        // Add chat button to footer
        $this->loader->add_action('wp_footer', $plugin_public, 'display_chat_button');
    }

    /**
     * Run the loader to execute all hooks
     */
    public function run() {
        $this->loader->run();
    }
}

/**
 * Begins execution of the plugin
 */
function run_floating_chat_button() {
    $plugin = Floating_Chat_Button::get_instance();
    $plugin->run();
}

function floating_chat_button_load_textdomain() {
    load_plugin_textdomain(
        'floating-chat-button',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
add_action('plugins_loaded', 'floating_chat_button_load_textdomain');

// Start the plugin
run_floating_chat_button();