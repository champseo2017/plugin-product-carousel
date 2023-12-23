<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product_Model {
    public function add_product($product_data) { // ไม่จำเป็นต้องรับ $image_file
        $post_id = wp_insert_post(array(
            'post_title' => $product_data['title'],
            'post_content' => $product_data['description'],
            'post_status' => 'publish',
            'post_type' => 'product',
        ));

        if ($post_id) {
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $attachment_id = media_handle_upload('image', $post_id);
                
                if (is_wp_error($attachment_id)) {
                    // จัดการกับข้อผิดพลาดในการอัปโหลด
                    error_log('Error uploading image: ' . $attachment_id->get_error_message());
                } else {
                    // อัพเดท post meta ด้วย ID ของ attachment
                    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
                }
            }

            // อัปเดต metadata อื่นๆ
            update_post_meta($post_id, 'product_link', $product_data['link']);
            update_post_meta($post_id, 'product_description', $product_data['description']);
        }

        return $post_id;
    }
}
