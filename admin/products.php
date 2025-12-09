<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../php/auth.php';
requireAdmin();
require_once __DIR__ . '/../php/db.php';

$categories = [];
try {
    $stmt = $pdo->query("SELECT id_category, name, parent_id FROM product_categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Ошибка при загрузке категорий для формы: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel='stylesheet'>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo">Teresia Admin</div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i> Главная</a></li>
                    <li><a href="products.php" class="active"><i class='bx bx-store-alt'></i> Управление товарами</a></li>
                    <li><a href="orders_report.php"><i class='bx bx-file'></i> Отчеты по заказам</a></li>
                    <li><a href="?logout=true"><i class='bx bx-log-out'></i> Выйти</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">
            <header class="admin-header">
                <h1>Управление товарами</h1>
                <button id="addProductBtn" class="admin-button"><i class='bx bx-plus'></i> Добавить товар</button>
            </header>

            <section class="admin-product-list">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Основная категория</th>
                            <th>Подкатегория</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                    </tbody>
                </table>
            </section>

            <div id="productModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" id="closeProductModal">&times;</span>
                    <h2 id="modalTitle">Добавить новый товар</h2>
                    <form id="productForm" enctype="multipart/form-data">
                        <input type="hidden" id="productId" name="id">

                        <label for="productName">Название:</label>
                        <input type="text" id="productName" name="name" class="form-row-field" required>

                        <label for="productDescription">Описание:</label>
                        <textarea id="productDescription" name="description" class="form-row-field"></textarea>

                        <label for="productPrice">Цена:</label>
                        <input type="number" id="productPrice" name="price" step="0.01" class="form-row-field" required>
                        
                        <label for="productMainCategory">Основная категория:</label>
                        <select id="productMainCategory" name="main_category_id" class="form-row-field" required>
                            <option value="">Выберите основную категорию</option>
                            <?php
                            foreach ($categories as $category) {
                                if ($category['parent_id'] === null) {
                                    echo "<option value='{$category['id_category']}'>" . htmlspecialchars($category['name']) . "</option>";
                                }
                            }
                            ?>
                        </select>

                        <label for="productSubcategory">Подкатегория:</label>
                        <select id="productSubcategory" name="subcategory_id" class="form-row-field">
                            <option value="">Выберите подкатегорию (необязательно)</option>
                            <?php
                            foreach ($categories as $category) {
                                if ($category['parent_id'] !== null) {
                                    echo "<option value='{$category['id_category']}' data-parent-id='{$category['parent_id']}'>" . htmlspecialchars($category['name']) . "</option>";
                                }
                            }
                            ?>
                        </select>

                        <label for="productImage1">Изображение 1:</label>
                        <input type="file" id="productImage1" name="image_1" accept="image/*">
                        <img id="currentImage1" src="" alt="Текущее изображение 1" style="max-width: 150px; display: none;">
                        <input type="hidden" id="currentImage1Path" name="image_1_current">

                        <label for="productImage2">Изображение 2 (опционально):</label>
                        <input type="file" id="productImage2" name="image_2" accept="image/*">
                        <img id="currentImage2" src="" alt="Текущее изображение 2" style="max-width: 150px; display: none;">
                        <input type="hidden" id="currentImage2Path" name="image_2_current">

                        <button type="submit" class="admin-button black-btn" id="saveProductBtn">Сохранить</button>
                    </form>
                </div>
            </div>

            <div id="deleteConfirmModal" class="modal">
                <div class="modal-content">
                    <h2 class="modal-text">Вы уверены, что хотите удалить этот товар?</h2>
                    <button id="confirmDeleteBtn" class="admin-button delete m">Удалить</button>
                    <button id="cancelDeleteBtn" class="admin-button white m">Отмена</button>
                </div>
            </div>

        </main>
    </div>
    <script src="js/admin_products.js"></script>
</body>
</html>