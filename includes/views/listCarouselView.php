<?php
if (!defined('ABSPATH')) {
    exit;
}

$controller = new ProductCarouselController();

// สำหรับ debug, คุณสามารถส่งพารามิเตอร์ทดสอบไปยังฟังก์ชัน listCarouselsPage
$result = $controller->listCarouselsPage(); // ตัวอย่างเช่น, ภาษาไทย หน้าที่ 1
$data = $result['data'];
$total = $result['total'];
$currentPage = $result['currentPage'];
$lastPage = $result['lastPage'];
?>
<head>
<link href="<?php echo plugins_url('css/lestCarouselStyles.css', __FILE__); ?>" rel="stylesheet">
</head>
    <div class="lestCarousel-container">
        <h1 class="lestCarousel-heading">List Product Carousel</h1>
    </div>
