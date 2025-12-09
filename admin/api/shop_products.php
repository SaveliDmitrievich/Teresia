<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../php/auth.php';
require_once __DIR__ . '/../../php/db.php';
requireAdmin();

$response = ['success' => false, 'message' => ''];

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        try {
            $stmt = $pdo->query("SELECT id_product, name, price, description, image_1, image_2 FROM shop_products ORDER BY id_product DESC");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $productIds = array_column($products, 'id_product');
            $productCategories = [];

            if (!empty($productIds)) {
                $placeholders = implode(',', array_fill(0, count($productIds), '?'));
                $stmt_categories = $pdo->prepare("
                    SELECT 
                        pcl.product_id, 
                        pc.id_category, 
                        pc.name AS category_name, 
                        pc.parent_id 
                    FROM product_category_links pcl
                    JOIN product_categories pc ON pcl.category_id = pc.id_category
                    WHERE pcl.product_id IN ($placeholders)
                ");
                $stmt_categories->execute($productIds);
                $rawProductCategories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rawProductCategories as $cat) {
                    $productCategories[$cat['product_id']][] = $cat;
                }
            }


            $processedProducts = [];
            foreach ($products as $product) {
                $productId = $product['id_product'];
                $product['main_category_id'] = null;
                $product['main_category_name'] = 'Без категории';
                $product['subcategory_id'] = null;
                $product['subcategory_name'] = null;

                if (isset($productCategories[$productId])) {
                    foreach ($productCategories[$productId] as $cat) {
                        if ($cat['parent_id'] === null) { 
                            $product['main_category_id'] = $cat['id_category'];
                            $product['main_category_name'] = $cat['category_name'];
                        } else {
                            if ($product['main_category_id'] === null) {

                                $parentStmt = $pdo->prepare("SELECT id_category, name FROM product_categories WHERE id_category = ?");
                                $parentStmt->execute([$cat['parent_id']]);
                                $parentCat = $parentStmt->fetch(PDO::FETCH_ASSOC);
                                if ($parentCat) {
                                    $product['main_category_id'] = $parentCat['id_category'];
                                    $product['main_category_name'] = $parentCat['name'];
                                }
                            }
                            $product['subcategory_id'] = $cat['id_category'];
                            $product['subcategory_name'] = $cat['category_name'];
                        }
                    }
                }
                $processedProducts[] = $product;
            }

            $response['success'] = true;
            $response['products'] = $processedProducts;
        } catch (PDOException $e) {
            $response['message'] = 'Ошибка при получении товаров: ' . $e->getMessage();
            error_log("Ошибка при получении товаров: " . $e->getMessage()); 
        }
        break;

    case 'POST':
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? 0;
        
        $main_category_id = $_POST['main_category_id'] ?? null;
        $subcategory_id = $_POST['subcategory_id'] ?? null;
        
        if (empty($name) || !is_numeric($price) || $price <= 0 || empty($main_category_id)) {
            $response['message'] = 'Некорректные данные товара. Проверьте название, цену и выберите основную категорию.';
            echo json_encode($response);
            exit();
        }

        try {
            $uploadDir = __DIR__ . '/../../img/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePaths = [];
            $imageFields = ['image_1', 'image_2']; 

            $currentProductImagePaths = [];
            if ($id) {
                $stmt_current_images = $pdo->prepare("SELECT image_1, image_2 FROM shop_products WHERE id_product = ?");
                $stmt_current_images->execute([$id]);
                $currentProductImagePaths = $stmt_current_images->fetch(PDO::FETCH_ASSOC);
            }

            foreach ($imageFields as $field) {
                $imagePath = null; 
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES[$field]['tmp_name'];
                    $file_extension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                    $new_file_name = uniqid('product_') . '.' . $file_extension;
                    $destination = $uploadDir . $new_file_name;

                    if (move_uploaded_file($tmp_name, $destination)) {
                        $imagePath = 'img/products/' . $new_file_name;
                        if ($id && isset($currentProductImagePaths[$field]) && !empty($currentProductImagePaths[$field])) {
                            $oldFilePath = __DIR__ . '/../../' . $currentProductImagePaths[$field];
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath); 
                            }
                        }
                    } else {
                        $response['message'] = 'Ошибка при загрузке изображения ' . $field . ': ' . $_FILES[$field]['error'];
                        echo json_encode($response);
                        exit();
                    }
                } else if ($id && isset($_POST[$field . '_current']) && !empty($_POST[$field . '_current'])) {
                    $imagePath = $_POST[$field . '_current'];
                }
                $imagePaths[$field] = $imagePath;
            }

            $image1 = $imagePaths['image_1'] ?? null;
            $image2 = $imagePaths['image_2'] ?? null;

            if ($id) {
                $sql = "UPDATE shop_products SET name = ?, description = ?, price = ?, image_1 = ?, image_2 = ? WHERE id_product = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $description, $price, $image1, $image2, $id]);
                
                $stmt_delete_links = $pdo->prepare("DELETE FROM product_category_links WHERE product_id = ?");
                $stmt_delete_links->execute([$id]);

                $stmt_insert_main_link = $pdo->prepare("INSERT INTO product_category_links (product_id, category_id) VALUES (?, ?)");
                $stmt_insert_main_link->execute([$id, $main_category_id]);
                
                if ($subcategory_id && $subcategory_id !== $main_category_id) { 
                    $stmt_insert_sub_link = $pdo->prepare("INSERT INTO product_category_links (product_id, category_id) VALUES (?, ?)");
                    $stmt_insert_sub_link->execute([$id, $subcategory_id]);
                }

                $response['success'] = true;
                $response['message'] = 'Товар успешно обновлен.';
            } else {
                $sql = "INSERT INTO shop_products (name, description, price, image_1, image_2) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $description, $price, $image1, $image2]);
                $new_product_id = $pdo->lastInsertId(); 
                
                $stmt_insert_main_link = $pdo->prepare("INSERT INTO product_category_links (product_id, category_id) VALUES (?, ?)");
                $stmt_insert_main_link->execute([$new_product_id, $main_category_id]);
                
                if ($subcategory_id && $subcategory_id !== $main_category_id) { 
                    $stmt_insert_sub_link = $pdo->prepare("INSERT INTO product_category_links (product_id, category_id) VALUES (?, ?)");
                    $stmt_insert_sub_link->execute([$new_product_id, $subcategory_id]);
                }

                $response['success'] = true;
                $response['message'] = 'Товар успешно добавлен.';
            }

        } catch (PDOException $e) {
            $response['message'] = 'Ошибка при сохранении товара: ' . $e->getMessage();
            error_log("Ошибка при сохранении товара: " . $e->getMessage()); 
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'] ?? null;

        if (!$id) {
            $response['message'] = 'ID товара не указан.';
            break;
        }

        try {
            $stmt_img = $pdo->prepare("SELECT image_1, image_2 FROM shop_products WHERE id_product = ?");
            $stmt_img->execute([$id]);
            $images_to_delete = $stmt_img->fetch(PDO::FETCH_ASSOC);

            $stmt_delete_links = $pdo->prepare("DELETE FROM product_category_links WHERE product_id = ?");
            $stmt_delete_links->execute([$id]);

            $stmt = $pdo->prepare("DELETE FROM shop_products WHERE id_product = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $uploadDir = __DIR__ . '/../../';
                if ($images_to_delete) {
                    foreach ($images_to_delete as $imagePath) {
                        if (!empty($imagePath) && file_exists($uploadDir . $imagePath)) {
                            unlink($uploadDir . $imagePath);
                        }
                    }
                }
                $response['success'] = true;
                $response['message'] = 'Товар успешно удален.';
            } else {
                $response['message'] = 'Товар с указанным ID не найден.';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Ошибка при удалении товара: ' . $e->getMessage();
            error_log("Ошибка при удалении товара: " . $e->getMessage()); 
        }
        break;

    default:
        $response['message'] = 'Неподдерживаемый метод запроса.';
        break;
}

echo json_encode($response);