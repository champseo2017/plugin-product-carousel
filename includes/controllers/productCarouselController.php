<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . '../models/ProductCarouselModel.php';

class ProductCarouselController {
    private $model;
    
    public function __construct() {
        $this->model = new ProductCarouselModel();
    }

    // ฟังก์ชันสำหรับเพิ่ม Carousel ใหม่
    public function addNewCarousel($title, $language = 'th') {
        $result = $this->model->addCarousel($title, $language);
        
        if (isset($result['error'])) {
            return ['error' => $result['error']];
        }

        $id = $result['id'];
        $new_title = $result['title'];

        return ['success' => "{$id} {$new_title}"];
    }

    public function addNewCarouselPage() {
        include plugin_dir_path( __FILE__ ) . '../views/addCarouselView.php';
    }
}