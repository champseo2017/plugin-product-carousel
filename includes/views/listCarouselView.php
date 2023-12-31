<?php
if (!defined('ABSPATH')) {
    exit;
}

$controller = new ProductCarouselController();

$language = isset($_GET['language']) ? $_GET['language'] : 'th';
$page = max(1, isset($_GET['pg']) ? (int)$_GET['pg'] : 1);
$perPage = max(10, isset($_GET['totalPage']) ? (int)$_GET['totalPage'] : 10);

$result = $controller->listCarouselsPage();
$data = $result['data'];
$total = $result['total'];
$currentPage = $result['currentPage'];
$lastPage = $result['lastPage'];
$totalPages = ceil($total / $perPage);
$rowNumber = ($currentPage - 1) * $perPage + 1;
?>
<head>
    <link href="<?php echo plugins_url('css/lestCarouselStyles.css', __FILE__); ?>" rel="stylesheet">
    <link href="<?php echo plugins_url('css/global.css', __FILE__); ?>" rel="stylesheet">
</head>
    <div class="global-container">
        <h1 class="global-heading">List Product Carousel</h1>
        <div class="global-card-container">
            <div class="global-label">
                <label for="title">
                    เลือกภาษา
                </label>
            </div>
            <form method="get">
                <input type="hidden" name="page" value="list-carousel">
                <input type="hidden" name="pg" value="<?php echo $page; ?>">
                <input type="hidden" name="totalPage" value="<?php echo $perPage; ?>">
                <select name="language" onchange="this.form.submit()" class="global-selector">
                    <option value="th" <?php echo $language == 'th' ? 'selected' : ''; ?>>Thai</option>
                    <option value="en" <?php echo $language == 'en' ? 'selected' : ''; ?>>English</option>
                    <option value="zh" <?php echo $language == 'zh' ? 'selected' : ''; ?>>Chinese</option>
                </select>
                <!-- ส่วนแสดงข้อมูลตาราง -->
                <table class="lestCarousel-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>STATUS</th>
                            <th>Date Created</th>
                            <th>Date Modified</th>
                            <th>LANGUAGE</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="8" class="data-not-found">Data not found</td>
                            </tr>
                        <?php else: ?>
                            <?php $rowNumber = ($currentPage - 1) * $perPage + 1; ?> <!-- คำนวณหมายเลขแถวเริ่มต้น -->
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $rowNumber++; ?></td> <!-- แสดงหมายเลขแถวและเพิ่มขึ้นทีละหนึ่ง -->
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td><?php echo ucfirst(strtolower(htmlspecialchars($item['status']))); ?></td>
                                    <td><?php echo htmlspecialchars($item['date_created']); ?></td>
                                    <td><?php echo htmlspecialchars($item['date_modified']); ?></td>
                                    <td><?php echo strtoupper(htmlspecialchars($item['language'])); ?></td>
                                    <td>
                                        <button class="lestCarousel-btn-edit">Edit</button>
                                        <button class="lestCarousel-btn-delete">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="pagination-container">
                    <div class="pagination-total">
                        <!-- เพิ่มตัวเลือกสำหรับ totalPage -->
                        <div class="total-page-selector">
                            <select name="totalPage" onchange="this.form.submit()" class="page-size-selector">
                                <option value="10" <?php echo $perPage == 10 ? 'selected' : ''; ?>>10 per page</option>
                                <option value="25" <?php echo $perPage == 25 ? 'selected' : ''; ?>>25 per page</option>
                                <option value="50" <?php echo $perPage == 50 ? 'selected' : ''; ?>>50 per page</option>
                                <option value="100" <?php echo $perPage == 100 ? 'selected' : ''; ?>>100 per page</option>
                            </select>
                        </div>
                        <div class="total-count">Total Items: <?php echo $total; ?></div>             
                    </div>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=list-carousel&pg=<?php echo $currentPage - 1; ?>&language=<?php echo $language; ?>" class="page-link">Previous</a>
                        <?php endif; ?>

                        <?php
                        $startPage = max(1, $currentPage - 5);
                        $endPage = min($totalPages, $currentPage + 4);

                        if ($startPage > 1) {
                            echo '<a href="?page=list-carousel&pg=1&language='.$language.'" class="page-link">1</a>';
                            echo '<span class="page-link">...</span>';
                        }

                        for ($i = $startPage; $i <= $endPage; $i++) {
                            echo '<a href="?page=list-carousel&pg='.$i.'&language='.$language.'" class="page-link '.($i == $currentPage ? 'active' : '').'">'.$i.'</a>';
                        }

                        if ($endPage < $totalPages) {
                            echo '<span class="page-link">...</span>';
                            echo '<a href="?page=list-carousel&pg='.$totalPages.'&language='.$language.'" class="page-link">'.$totalPages.'</a>';
                        }
                        ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=list-carousel&pg=<?php echo $currentPage + 1; ?>&language=<?php echo $language; ?>" class="page-link">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
