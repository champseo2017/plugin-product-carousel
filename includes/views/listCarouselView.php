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
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tailwind CSS ผ่าน CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-xl font-bold mb-4">Debug Product Carousel</h1>
        <pre><?php var_dump($result); ?></pre> <!-- แสดงข้อมูล $result ออกมา -->
    </div>
</body>
</html>
