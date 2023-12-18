<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product_Controller {
    protected $model;

    public function __construct() {
        require_once plugin_dir_path( __FILE__ ) . 'class-product-model.php';
        $this->model = new Product_Model();
    }

    // เมธอดเพื่อเพิ่มสินค้าโดยใช้ Product_Model
    public function add_new_product($product_data) {
        return $this->model->add_product($product_data);
    }
}
