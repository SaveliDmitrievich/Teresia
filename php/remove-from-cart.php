<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пожалуйста, войдите в систему.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];


$query = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);

echo json_encode(['success' => true]);
?>
