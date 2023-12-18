<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product_Model {
    // เพิ่มสินค้าใหม่
    public function add_product($product_data) {
        // ตัวอย่างการใช้ wp_insert_post หรือฟังก์ชัน WordPress อื่นๆ สำหรับบันทึกข้อมูล
        // สมมติว่า $product_data มีข้อมูลที่จำเป็นทั้งหมด
        $post_id = wp_insert_post(array(
            'post_title' => $product_data['title'],
            'post_content' => $product_data['description'], // เพิ่มรายละเอียดสินค้า
            'post_status' => 'publish',
            'post_type' => 'product', // ตัวอย่าง custom post type
            // เพิ่มฟิลด์เพิ่มเติมที่นี่
        ));

        if ($post_id) {
            // ตัวอย่างการเพิ่ม metadata
            update_post_meta($post_id, 'product_link', $product_data['link']);
            // เพิ่มรายละเอียดลิงก์สินค้า
            update_post_meta($post_id, 'product_description', $product_data['description']);
            // เพิ่ม metadata รูปภาพ
            if (isset($product_data['image_url'])) {
                update_post_meta($post_id, 'product_image', $product_data['image_url']);
            }
            // เพิ่ม metadata อื่นๆ ที่นี่
        }

        return $post_id;
    }
}
