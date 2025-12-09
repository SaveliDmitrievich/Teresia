<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Необходима авторизация.']);
    exit();
}

$user_id = $_SESSION['user_id'];


$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$comment = trim($_POST['comment'] ?? '');

if (empty($fullname) || empty($phone) || empty($address)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Пожалуйста, заполните все обязательные поля.']);
    exit();
}

$cart_query = $pdo->prepare("
    SELECT c.product_id, c.quantity, p.price
    FROM cart c
    JOIN shop_products p ON c.product_id = p.id_product
    WHERE c.user_id = ?
");
$cart_query->execute([$user_id]);
$cart_result = $cart_query->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_result)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ваша корзина пуста.']);
    exit();
}

$total_price = 0;
$cart_items = [];
foreach ($cart_result as $row) {
    $product_id = (int) $row['product_id'];
    $quantity = (int) $row['quantity'];
    $price = (float) $row['price'];

    $total_price += $price * $quantity;
    $cart_items[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price' => $price
    ];
}

try {
    $pdo->beginTransaction();

    $order_query = $pdo->prepare("
        INSERT INTO orders (user_id, order_date, total_price, fullname, phone, address, comment)
        VALUES (?, NOW(), ?, ?, ?, ?, ?)
    ");
    $order_query->execute([$user_id, $total_price, $fullname, $phone, $address, $comment]);
    $order_id = $pdo->lastInsertId();

    $order_details_query = $pdo->prepare("
        INSERT INTO order_details (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($cart_items as $item) {
        $order_details_query->execute([
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        ]);
    }

    $clear_cart_query = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear_cart_query->execute([$user_id]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Заказ успешно оформлен.',
        'order_id' => $order_id
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка при оформлении заказа. Попробуйте позже.']);
}

exit();
?>
