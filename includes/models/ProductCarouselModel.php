<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . '../helper/validation/carousel/carouselValidation.php';

class ProductCarouselModel {

    public function addCarousel($title, $language = 'th') {
        // ตรวจสอบและทำความสะอาดข้อความใน title โดยใช้ CarouselValidation
        $title = CarouselValidation::validateText($title);

        // ตรวจสอบว่า title ไม่ว่างเปล่า
        if (!CarouselValidation::required($title)) {
            return ['error' => 'Title required field'];
        }

        // ตรวจสอบว่ารหัสภาษาที่ระบุถูกต้อง
        if (!CarouselValidation::validLanguage($language)) {
            return ['error' => 'Required languages: TH, EN, ZH'];
        }

        // ตรวจสอบว่ามี Carousel ที่มี title เดียวกันในภาษานั้นอยู่แล้วหรือไม่
        if ($this->carouselExists($title, $language)) {
            return ['error' => 'Carousel with this name in the specified language already exists.'];
        }

        // สร้างอาร์เรย์ข้อมูลสำหรับการสร้างโพสต์ใหม่
        $postarr = array(
            'post_title'   => $title,         // ชื่อโพสต์
            'post_content' => '',             // เนื้อหาโพสต์ (ว่างเปล่า)
            'post_status'  => 'draft',        // สถานะโพสต์เป็นร่าง (draft)
            'post_type'    => 'product_carousel' // ประเภทโพสต์เป็น 'product_carousel'
        );

        // เพิ่มโพสต์ใหม่ในฐานข้อมูล WordPress และคืนค่า ID ของโพสต์
        $post_id = wp_insert_post($postarr);

        // ตรวจสอบว่าการเพิ่มโพสต์มีข้อผิดพลาดหรือไม่
        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, 'language', $language);
            $post = get_post($post_id); 
            $title = $post->post_title;
            return [
                'success' => true,
                'id' => $post_id,
                'title' => $title
            ];
        } else {
            return ['error' => 'Unable to add Carousel'];
        }
    }

    private function carouselExists($title, $language) {
        $args = array(
            'post_type' => 'product_carousel',
            'post_status' => 'any',
            'meta_query' => array(
                array(
                    'key' => 'language',
                    'value' => $language,
                ),
            ),
            'posts_per_page' => 1,
            'title' => $title, // ตรวจสอบ title
        );

        $query = new WP_Query($args);

        return $query->have_posts();
    }
}