<?php
if (!defined('ABSPATH')) {
    exit;
}

// Create an instance of Product_Controller
$product_controller = new Product_Controller();

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form data
    $product_data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'link' => $_POST['link'],
    ];
    
    $result = $product_controller->add_new_product($product_data);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Carousel Settings</title>
    <!-- Tailwind CSS ผ่าน CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="wrap max-w-4xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Product Carousel Settings</h1>

        <!-- Display success or error message -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            if (!empty($result['error'])) {
                echo '<p class="bg-red-100 text-red-700 border border-red-600 rounded p-4 mb-4">' . $result['error'] . '</p>';
            } elseif (!empty($result['success'])) {
                echo '<p class="bg-green-100 text-green-700 border border-green-600 rounded p-4 mb-4">Product with ID ' . $result['success'] . ' has been added successfully.</p>';
            } else {
                echo '<p class="bg-red-100 text-red-700 border border-red-600 rounded p-4 mb-4">There was an error adding the product.</p>';
            }
        }
        ?>

        <!-- Product Form -->
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input type="text" name="title" placeholder="Title" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea name="description" placeholder="Description" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="link">
                    Product Link
                </label>
                <input type="text" name="link" placeholder="Product Link" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Image</label>
    <input type="file" name="image" id="image-upload" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    <!-- ที่นี่สำหรับแสดงพรีวิว -->
    <div id="image-preview" class="mt-4"></div>
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" name="submit" value="Add Product" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </div>
    <script>
    document.getElementById('image-upload').addEventListener('change', function(event){
        var output = document.getElementById('image-preview');
        output.innerHTML = ''; // ล้างพรีวิวเก่า

        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px'; // จำกัดขนาดพรีวิว
                img.style.maxHeight = '200px';
                output.appendChild(img);
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    });
    </script>
</body>
</html>