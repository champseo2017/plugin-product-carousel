<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class MenuController {
    private $settings;
    public function __construct() {
        $this->settings = new SettingsController();
        $this->productCarousel = new ProductCarouselController();
        // ลงทะเบียน action ที่จะเพิ่มหน้าเมนูในแอดมิน
        add_action( 'admin_menu', array( $this, 'add_plugin_settings_page' ) );
    }

    public function add_plugin_settings_page() {
        // เพิ่มหน้าเมนูใหม่ในแอดมินของ WordPress
        add_menu_page(
            'Product Carousel Settings', // ชื่อหน้าเมนูที่จะแสดงในแอดมิน
            'Product Carousel Settings', // ข้อความที่แสดงในแท็บเมนู
            'manage_options', // ความสามารถที่ผู้ใช้ต้องมีเพื่อเข้าถึงหน้าเมนูนี้
            'domain-carousel-settings', // ชื่อเฉพาะของหน้าเมนู (slug)
            array( $this, 'display_domain_settings_page' ), // ฟังก์ชันที่จะเรียกเมื่อหน้าเมนูถูกแสดง
            'dashicons-admin-generic', // ไอคอนที่จะแสดงในเมนู
            20  // ตำแหน่งของเมนูในแอดมิน
        );
        // เพิ่ม submenu ที่ชื่อว่า "addNewCarousel"
        add_submenu_page(
            'domain-carousel-settings', // ชื่อ slug ของเมนูหลัก
            'Add New Carousel',        // ชื่อหน้าของ submenu
            'Add New Carousel',        // ข้อความที่แสดงในเมนู
            'manage_options',          // สิทธิ์ที่จำเป็นในการเข้าถึง submenu นี้
            'add-new-carousel',        // ชื่อ slug ของ submenu
            array( $this, 'display_add_new_carousel_page' ) // ฟังก์ชันที่จะเรียกเมื่อหน้า submenu ถูกแสดง
        );
         // เพิ่ม submenu สำหรับการแสดงรายการ Carousel
        add_submenu_page(
            'domain-carousel-settings',
            'List Carousel',
            'List Carousel',
            'manage_options',
            'list-carousel',
            array( $this, 'listCarouselsPage' )
        );
        // เพิ่ม submenu 'Update Carousel'
        add_submenu_page(
            null, // ทำให้ submenu นี้ไม่แสดงในเมนูแอดมิน
            'Update Carousel',  // ชื่อหน้าของ submenu
            'Update Carousel',   // ข้อความที่แสดงในเมนู (จะไม่ถูกแสดง)
            'manage_options',   // สิทธิ์ที่จำเป็นในการเข้าถึง submenu นี้
            'update-carousel', // ชื่อ slug ของ submenu
            array( $this, 'updateCarousel' ) // ฟังก์ชันที่จะเรียกเมื่อหน้า submenu ถูกแสดง
        );
    }

    public function display_domain_settings_page() {
       return $this->settings->add_plugin_settings_page();
    }

    public function display_add_new_carousel_page() {
        return $this->productCarousel->addNewCarouselPage();
     }

     public function listCarouselsPage() {
        return $this->productCarousel->listPage();
     }

     public function updateCarousel() {
        return $this->productCarousel->updateCarouselPage();
     }
      
}
