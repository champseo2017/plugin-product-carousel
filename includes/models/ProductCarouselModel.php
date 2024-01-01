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

    public function mockData($numberOfItems = 50) {
        $mockedItems = [];
    
        for ($i = 0; $i < $numberOfItems; $i++) {
            $title = 'Test Carousel ' . mt_rand(1000, 9999); // สร้างชื่อที่มีค่าสุ่ม
            $language = 'th'; // ตั้งค่าภาษาเป็น 'th'
    
            $result = $this->addCarousel($title, $language);
    
            if (!isset($result['error'])) {
                $mockedItems[] = $result;
            } else {
                // จัดการกับข้อผิดพลาดที่เกิดขึ้นในกระบวนการเพิ่ม Carousel
                // ตัวอย่างเช่น, สามารถเพิ่มข้อความลงใน log หรืออื่นๆ
            }
        }
    
        return $mockedItems;
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
            SELECT {$wpdb->posts}.*, wp_postmeta.meta_value AS 'language' 
            FROM {$wpdb->posts} 
            INNER JOIN wp_postmeta ON {$wpdb->posts}.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = 'language'
            WHERE {$wpdb->posts}.post_type = 'product_carousel' AND wp_postmeta.meta_value = %s
            ORDER BY {$wpdb->posts}.post_date DESC
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
        // ปรับแก้ SQL query เพื่อนับจำนวนโพสต์ตามภาษา
        $total_query = "
            SELECT COUNT(*) 
            FROM {$wpdb->posts} 
            INNER JOIN wp_postmeta ON {$wpdb->posts}.ID = wp_postmeta.post_id 
            WHERE {$wpdb->posts}.post_type = 'product_carousel' 
            AND wp_postmeta.meta_key = 'language' 
            AND wp_postmeta.meta_value = %s
        ";
        $total = $wpdb->get_var($wpdb->prepare($total_query, $language));

        return [
            'data' => $carousels,
            'total' => $total,
            'page' => $page,
            'lastPage' => ($perPage > 0) ? ceil($total / $perPage) : 0,
        ];
    }

    public function deleteNonPublicCarousel($carouselId) {
        global $wpdb;
    
        // ดึงข้อมูลของ carousel ก่อนลบ
        $carousel = $wpdb->get_row($wpdb->prepare("SELECT ID, post_title FROM {$wpdb->posts} WHERE ID = %d AND post_type = %s", $carouselId, 'product_carousel'), ARRAY_A);
    
        if (is_null($carousel)) {
            return ['error' => 'Carousel not found'];
        }
    
        // SQL สำหรับลบ product_carousel ที่ไม่ใช่ public
        $sql = "DELETE FROM {$wpdb->posts} WHERE ID = %d AND post_type = %s AND post_status != %s";
        
        // ใช้ $wpdb->prepare เพื่อป้องกัน SQL Injection
        $prepared_query = $wpdb->prepare($sql, $carouselId, 'product_carousel', 'publish');
        
        // ทำการลบ
        $result = $wpdb->query($prepared_query);
        
        // ตรวจสอบและคืนค่าผลลัพธ์
        if ($result === false) {
            return ['error' => 'Error in deleting the non-public carousel'];
        } else {
             // คืนค่าข้อมูลของ carousel ที่ถูกลบ
            return [
                'success' => "Carousel deleted success {$carousel['ID']} {$carousel['post_title']}",
                'id' => $carousel['ID'],
                'title' => $carousel['post_title']
            ];
        }
    }
    
    
}
