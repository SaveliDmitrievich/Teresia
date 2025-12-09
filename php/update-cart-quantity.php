<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пожалуйста, войдите в систему.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

if (!is_numeric($quantity) || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Неверное количество']);
    exit;
}

$query = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'quantity' => $quantity,
    'user_id' => $user_id,
    'product_id' => $product_id
]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Не удалось обновить количество']);
}
?>
