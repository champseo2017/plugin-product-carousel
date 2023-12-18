<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'class-logger.php';
class Plugin_CORS {
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'handle_cors' ), 15 );
    }

    public function handle_cors() {
        remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
        add_filter( 'rest_pre_serve_request', array( $this, 'cors_headers' ) );
    }

    public function cors_headers( $value ) {
        // Get the allowed domains from the WordPress options
        $allowed_domains = get_option('allowed_domains', []);

        Plugin_Logger::log_to_debug("check HTTP_ORIGIN ". $_SERVER['HTTP_ORIGIN']);
    
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : $_SERVER['HTTP_HOST'];
    
        // Check if the origin is in the list of allowed domains
        if (in_array($origin, $allowed_domains)) {
            header('Access-Control-Allow-Origin: '.$origin);
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization');
        }
    
        return $value;
    }
    
}
