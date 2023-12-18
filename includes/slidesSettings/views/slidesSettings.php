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
        'image_url' => $_POST['image_url']
    ];

    // Add the new product
    $product_id = $product_controller->add_new_product($product_data);
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
        <?php if (isset($product_id) && $product_id): ?>
            <p class="bg-green-100 text-green-700 border border-green-600 rounded p-4 mb-4">Product with ID <?php echo $product_id; ?> has been added successfully.</p>
        <?php elseif (isset($_POST['submit'])): ?>
            <p class="bg-red-100 text-red-700 border border-red-600 rounded p-4 mb-4">There was an error adding the product.</p>
        <?php endif; ?>

        <!-- Product Form -->
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image_url">
                    Image URL
                </label>
                <input type="text" name="image_url" placeholder="Image URL" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" name="submit" value="Add Product" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </div>
</body>
</html>