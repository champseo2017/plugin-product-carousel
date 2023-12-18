<?php
/**
* Plugin Name: Product Carousel
* Description: An elegant and dynamic product carousel plugin for showcasing items in an interactive slider format. Ideal for e-commerce websites, this plugin offers a sleek and user-friendly way to display products attractively.
* Version: 1.0
* Author: Easy GG | Champion of User-Centric Web Solutions
* Author URI: https://github.com/champseo2017/plugin-product-carousel
*/


// ตรวจสอบว่าค่าคงที่ ABSPATH ถูกกำหนดหรือไม่
if ( ! defined( 'ABSPATH' ) ) {
    exit;
    // หยุดการทำงานของสคริปต์ถ้าไม่ได้ถูกเรียกจาก WordPress
}

add_action('plugins_loaded', function() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-rest-api.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-cors.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-settings.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-product-controller.php';

    $rest_api = new Plugin_REST_API();
    $cors = new Plugin_CORS();
    $settings = new Plugin_Settings();
    $product_controller = new Product_Controller();
});

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

function product_carousel_shortcode() {
    return '<div id="my-react-app"></div>';
    // ตำแหน่งที่ React component ของคุณจะถูก render
}
add_shortcode( 'product_carousel', 'product_carousel_shortcode' );
