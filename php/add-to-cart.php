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

$query = "SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
$existingItem = $stmt->fetch();

if ($existingItem) {
    $newQuantity = $existingItem['quantity'] + $quantity;
    $query = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['quantity' => $newQuantity, 'user_id' => $user_id, 'product_id' => $product_id]);
} else {    
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
}

echo json_encode(['success' => true]);
?>
