<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'class-product-model.php';

class Product_Controller {
    protected $model;

    public function __construct() {
        $this->model = new Product_Model();
    }

    // เมธอดเพื่อเพิ่มสินค้าโดยใช้ Product_Model
    public function add_new_product($product_data) {
        // ตรวจสอบขนาดไฟล์
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_size = $_FILES['image']['size'];
            $max_size = 15 * 1024 * 1024; // 15MB in bytes

            if ($image_size > $max_size) {
                // ส่งข้อผิดพลาดกลับ
                return ['error' => 'The uploaded file exceeds the maximum upload size of 128MB.'];
            }
        }
        // ถ้าไม่มีข้อผิดพลาด, เพิ่มสินค้า
        $product_id = $this->model->add_product($product_data);
        return ['success' => $product_id];
    }
}
