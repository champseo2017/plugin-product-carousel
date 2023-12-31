<?php
if (!defined('ABSPATH')) {
    exit;
}

class ProductCarouselModel
{
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

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

    public function updateCarousel($carouselId, $title, $language, $status) {
        $title = CarouselValidation::validateText($title);
        $language = CarouselValidation::validateText($language);
        $status = CarouselValidation::validateText($status);
    
        if (get_post_type($carouselId) !== 'product_carousel') {
            return ['error' => 'Invalid carousel ID'];
        }
    
        $current_time = current_time('mysql');
        $gmt_time = current_time('mysql', 1);
    
        // อัปเดตชื่อ Carousel และวันที่แก้ไข
        $this->wpdb->update(
            $this->wpdb->posts, 
            ['post_title' => $title, 'post_modified' => $current_time, 'post_modified_gmt' => $gmt_time], 
            ['ID' => $carouselId]
        );
    
        // อัปเดตภาษาในเมตาดาต้า
        update_post_meta($carouselId, 'language', $language);
    
        // อัปเดตสถานะ Carousel และวันที่แก้ไข
        $this->wpdb->update(
            $this->wpdb->posts, 
            ['post_status' => $status, 'post_modified' => $current_time, 'post_modified_gmt' => $gmt_time], 
            ['ID' => $carouselId]
        );
    
        // ตรวจสอบข้อผิดพลาด
        if ($this->wpdb->last_error) {
            return ['error' => 'Update failed'];
        }
    
        return ['success' => 'Update Success'];
    }
    
    public function mockData($numberOfItems = 50) {
        $mockedItems = [];
    
        for ($i = 0; $i < $numberOfItems; $i++) {
            $title = 'Test Carousel ' . mt_rand(1000, 9999); // สร้างชื่อที่มีค่าสุ่ม
            $language = 'th'; // ตั้งค่าภาษาเป็น 'th'
    
            $result = $this->addCarousel($title, $language);
    
            if (!isset($result['error'])) {
                $mockedItems[] = $result;
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

        $offset = ($page - 1) * $perPage;
        // สร้างคำสั่ง SQL สำหรับดึงข้อมูล Carousel
        $query = "
            SELECT {$this->wpdb->posts}.*, wp_postmeta.meta_value AS 'language' 
            FROM {$this->wpdb->posts} 
            INNER JOIN wp_postmeta ON {$this->wpdb->posts}.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = 'language'
            WHERE {$this->wpdb->posts}.post_type = 'product_carousel' AND wp_postmeta.meta_value = %s
            ORDER BY {$this->wpdb->posts}.post_date DESC
            LIMIT %d, %d
        ";

        // เตรียมคำสั่ง SQL โดยใส่ค่าแทนที่ placeholders (%s, %d, %d) ด้วยค่าที่ปลอดภัย
        // %s สำหรับภาษา, %d สำหรับตำแหน่งเริ่มต้น (offset) และ %d สำหรับจำนวนโพสต์ต่อหน้า (perPage)
        $prepared_query = $this->wpdb->prepare($query, $language, $offset, $perPage);
        // ดึงข้อมูลจากฐานข้อมูลตามคำสั่งที่เตรียมไว้
        $posts = $this->wpdb->get_results($prepared_query);

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
            FROM {$this->wpdb->posts} 
            INNER JOIN wp_postmeta ON {$this->wpdb->posts}.ID = wp_postmeta.post_id 
            WHERE {$this->wpdb->posts}.post_type = 'product_carousel' 
            AND wp_postmeta.meta_key = 'language' 
            AND wp_postmeta.meta_value = %s
        ";
        $total = $this->wpdb->get_var($this->wpdb->prepare($total_query, $language));

        return [
            'data' => $carousels,
            'total' => $total,
            'page' => $page,
            'lastPage' => ($perPage > 0) ? ceil($total / $perPage) : 0,
        ];
    }

    public function deleteNonPublicCarousel($carouselId) {
    
        // ดึงข้อมูลของ carousel ก่อนลบ
        $carousel = $this->wpdb->get_row($this->wpdb->prepare("SELECT ID, post_title FROM {$this->wpdb->posts} WHERE ID = %d AND post_type = %s", $carouselId, 'product_carousel'), ARRAY_A);
    
        if (is_null($carousel)) {
            return ['error' => 'Carousel not found'];
        }
    
        // SQL สำหรับลบ product_carousel ที่ไม่ใช่ public
        $sql = "DELETE FROM {$this->wpdb->posts} WHERE ID = %d AND post_type = %s AND post_status != %s";
        
        // ใช้ $wpdb->prepare เพื่อป้องกัน SQL Injection
        $prepared_query = $this->wpdb->prepare($sql, $carouselId, 'product_carousel', 'publish');
        
        // ทำการลบ
        $result = $this->wpdb->query($prepared_query);
        
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

    // ฟังก์ชันสำหรับดึงข้อมูล carousel โดยใช้ ID
    public function getCarouselData($carouselId) {
        $post = get_post($carouselId);

        // ตรวจสอบว่าโพสต์มีอยู่จริงและเป็นประเภท 'product_carousel'
        if ($post && $post->post_type === 'product_carousel') {
            $postarr = array(
                'post_title' => $post->post_title,
                'post_content' => $post->post_content,
                'post_status' => $post->post_status,
                'post_type' => $post->post_type,
                'language' => get_post_meta($carouselId, 'language', true)
            );
            // ส่งกลับข้อมูล carousel ในรูปแบบ 'success'
            return ['success' => true, 'data' => $postarr];
        }

        // ส่งกลับข้อผิดพลาดหากไม่พบ carousel
        return ['error' => 'Carousel not found.'];
    }
    
    
}
