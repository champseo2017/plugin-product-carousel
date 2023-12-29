<?php
if (!defined('ABSPATH')) {
    exit;
}

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new ProductCarouselController();
    $result = $controller->addNewCarousel($_POST['title'], $_POST['language']);

    if (isset($result['error'])) {
        $error = $result['error'];
    } else if (isset($result['success'])) {
        $success = $result['success'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tailwind CSS ผ่าน CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-xl font-bold mb-4">เพิ่ม Product Carousel ใหม่</h1>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-5">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-5">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">
                    ชื่อ Carousel:
                </label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="language" class="block text-gray-700 text-sm font-bold mb-2">
                    ภาษา:
                </label>
                <select id="language" name="language" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="th">ไทย</option>
                    <option value="en">อังกฤษ</option>
                    <option value="zh">จีน</option>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    เพิ่ม Carousel
                </button>
            </div>
        </form>
    </div>
</body>
</html>
