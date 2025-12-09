<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пожалуйста, войдите в систему.']);
    exit;
}

$user_id = $_SESSION['user_id'];


$query = "SELECT c.product_id, c.quantity, p.name, p.price, p.image_1
          FROM cart c
          JOIN shop_products p ON c.product_id = p.id_product
          WHERE c.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode(['success' => true, 'items' => $cartItems]);
?>
