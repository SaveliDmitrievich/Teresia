<?php
include('db.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Не передан ID товара']);
    exit();
}

$product_id = (int)$_GET['id'];

try {

    $stmt = $pdo->prepare("SELECT id_product, name, price, description, image_1, image_2 FROM shop_products WHERE id_product = ?");
    $stmt->execute([$product_id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['error' => 'Товар не найден']);
        exit();
    }

    echo json_encode(['product' => $product]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Ошибка при получении данных товара: ' . $e->getMessage()]);
}
?>
