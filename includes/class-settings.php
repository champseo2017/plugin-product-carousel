<?php
require_once plugin_dir_path( __FILE__ ) . 'class-logger.php';
class Plugin_Settings {
   
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
    }

    public function log_to_debug( $message ) {
        if ( defined('WP_DEBUG') && WP_DEBUG ) {
            if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) {
                error_log( print_r( $message, true ) );
            }
        }
    }

    public function add_plugin_settings_page() {
        add_menu_page(
            'Product Carousel Settings',
            'Carousel Settings',
            'manage_options',
            'product-carousel-settings',
            array( $this, 'display_plugin_settings_page' ),
            'dashicons-admin-generic',
            20
        );
    }

    public function display_plugin_settings_page() {
        include plugin_dir_path( __FILE__ ) . 'settings/views/form.php';
    }
    

    public function register_plugin_settings() {
        register_setting( 'plugin-settings-group', 'allowed_domains', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_allowed_domains')
        ));
    }

    public function sanitize_allowed_domains($inputs) {
        $sanitized_inputs = array();
        // Check if $inputs is an array
        if (is_array($inputs)) {
            foreach ($inputs as $input) {
                // Perform your sanitization logic here
                if (filter_var($input, FILTER_VALIDATE_URL)) {
                    $sanitized_inputs[] = $input;
                }
            }
        }
        // Plugin_Logger::log_to_debug("ข้อความของคุณที่นี่");
        return $sanitized_inputs;
    }
      
}
