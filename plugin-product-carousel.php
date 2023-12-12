<?php
/**
* Plugin Name: Product Carousel
* Description: A simple WordPress plugin with React.js
* Version: 1.0
* Author: Your Name
*/

// ตรวจสอบว่าค่าคงที่ ABSPATH ถูกกำหนดหรือไม่
if ( ! defined( 'ABSPATH' ) ) {
    exit;
    // หยุดการทำงานของสคริปต์ถ้าไม่ได้ถูกเรียกจาก WordPress
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-rest-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cors.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-settings.php';

new Plugin_REST_API();
new Plugin_CORS();
new Plugin_Settings();

function my_react_plugin_script() {
    $dir = plugin_dir_path( __FILE__ ) . 'dist/assets/';

    $script_files = scandir( $dir );
    foreach ( $script_files as $file ) {
        if ( preg_match( '/index.*\.js$/', $file ) ) {
            wp_enqueue_script( 'my-react-plugin', plugin_dir_url( __FILE__ ) . 'dist/assets/' . $file, array( 'wp-element' ), '1.0.0', true );
            break;
        }
    }
}
add_action( 'wp_enqueue_scripts', 'my_react_plugin_script' );
