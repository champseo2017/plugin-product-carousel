<?php
if (!defined('ABSPATH')) {
    exit;
}

class ProductCarouselModel
{

    public function addCarousel($title, $language = 'th')
    {
        // ตรวจสอบและทำความสะอาดข้อความใน title โดยใช้ CarouselValidation
        $title = CarouselValidation::validateText($title);

        // ตรวจสอบว่า title ไม่ว่างเปล่า
        if (!CarouselValidation::required($title)) {
            return ['error' => 'Title required field'];
        }

        // ตรวจสอบว่ารหัสภาษาที่ระบุถูกต้อง
        if (!CarouselValidation::validLanguage($language)) {
            return ['error' => 'Required languages: th, en, zh'];
        }

        // ตรวจสอบว่ามี Carousel ที่มี title เดียวกันในภาษานั้นอยู่แล้วหรือไม่
        if ($this->carouselExists($title, $language)) {
            return ['error' => 'Carousel with this name in the specified language already exists.'];
        }

        // สร้างอาร์เรย์ข้อมูลสำหรับการสร้างโพสต์ใหม่
        $postarr = array(
            'post_title' => $title, // ชื่อโพสต์
            'post_content' => '', // เนื้อหาโพสต์ (ว่างเปล่า)
            'post_status' => 'draft', // สถานะโพสต์เป็นร่าง (draft)
            'post_type' => 'product_carousel', // ประเภทโพสต์เป็น 'product_carousel'
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
                'title' => $title,
            ];
        } else {
            return ['error' => 'Unable to add Carousel'];
        }
    }

    private function carouselExists($title, $language)
    {
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

    public function listCarousels($language, $page = 1, $perPage = 10) {
        global $wpdb;

        $offset = ($page - 1) * $perPage;
        // สร้างคำสั่ง SQL สำหรับดึงข้อมูล Carousel
        $query = "
            SELECT wp_posts.*, wp_postmeta.meta_value AS 'language' 
            FROM wp_posts 
            LEFT JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = 'language') 
            WHERE wp_posts.post_type = 'product_carousel' AND wp_postmeta.meta_value = %s
            LIMIT %d, %d
        ";
        // เตรียมคำสั่ง SQL โดยใส่ค่าแทนที่ placeholders (%s, %d, %d) ด้วยค่าที่ปลอดภัย
        // %s สำหรับภาษา, %d สำหรับตำแหน่งเริ่มต้น (offset) และ %d สำหรับจำนวนโพสต์ต่อหน้า (perPage)
        $prepared_query = $wpdb->prepare($query, $language, $offset, $perPage);
        // ดึงข้อมูลจากฐานข้อมูลตามคำสั่งที่เตรียมไว้
        $posts = $wpdb->get_results($prepared_query);

        // จัดการข้อมูลโพสต์
        $carousels = [];
        foreach ($posts as $post) {
            $carousels[] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'status' => $post->post_status,
                'date_created' => $post->post_date,
                'date_modified' => $post->post_modified,
                'language' => $post->language,
            ];
        }

        // นับจำนวนโพสต์ทั้งหมด
        $total_query = "SELECT COUNT(*) FROM wp_posts WHERE post_type = 'product_carousel'";
        $total = $wpdb->get_var($total_query);

        return [
            'data' => $carousels,
            'total' => $total,
            'page' => $page,
            'lastPage' => ($perPage > 0) ? ceil($total / $perPage) : 0,
        ];
    }
}
