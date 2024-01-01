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
        
        // $result = $this->model->mockData();
        // return ['success' => "Done"];
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

    public function listCarouselsPage() {

        $language = isset($_GET['language']) ? $_GET['language'] : 'th';
        $page = max(1, isset($_GET['pg']) ? (int)$_GET['pg'] : 1);
        $perPage = max(10, isset($_GET['totalPage']) ? (int)$_GET['totalPage'] : 10);
    
        $result = $this->model->listCarousels($language, $page, $perPage);
        
        // ดึงข้อมูลที่ต้องการ 
        $data = $result['data']; 
        $total = $result['total'];
        $currentPage = $result['page'];
        $lastPage = $result['lastPage'];
        
        return [
            'success' => true,
            'data' => $data,
            'total' => $total,
            'currentPage' => $currentPage,
            'lastPage' => $lastPage,
        ];
    
    }

    public function listPage() {
        include plugin_dir_path( __FILE__ ) . '../views/listCarouselView.php';
    }

    public function deleteCarousel() {
        // ตรวจสอบว่ามีค่า carouselId ที่ส่งมาจาก POST หรือไม่
        if (isset($_POST['id'])) {
            $carouselId = $_POST['id'];
            $result = $this->model->deleteNonPublicCarousel($carouselId);
            return $result;
        } else {
            // ไม่พบ carouselId ในค่า POST
            return ['error' => 'Carousel ID is required.'];
        }
    }
}