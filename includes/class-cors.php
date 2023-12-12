<?php
class Plugin_CORS {
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'handle_cors' ), 15 );
    }

    public function handle_cors() {
        remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
        add_filter( 'rest_pre_serve_request', array( $this, 'cors_headers' ) );
    }

    public function cors_headers( $value ) {
        $domain = $_SERVER['HTTP_HOST'];

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $domain = $_SERVER['HTTP_ORIGIN']; 
        }

        header('Access-Control-Allow-Origin: '.$domain);
        header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
        header( 'Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization' );
        return $value;
    }
}
