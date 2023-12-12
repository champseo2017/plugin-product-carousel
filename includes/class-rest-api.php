<?php
class Plugin_REST_API {
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {
        register_rest_route( 'product-carousel/api/v1', '/testGet', array(
            'methods' => 'GET',
            'callback' => array( $this, 'handle_request' ),
            'permission_callback' => array( $this, 'permissions_check' )
        ));
    }

    public function handle_request( $request ) {
        // จัดการคำขอ API ที่นี่
    }

    public function permissions_check( $request ) {
        // ตรวจสอบสิทธิ์สำหรับคำขอ
        return true;
    }
}
