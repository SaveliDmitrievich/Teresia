<?php
session_start();
include('db.php'); 

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'count' => 0]); 
    exit; 
}

$user_id = $_SESSION['user_id'];

$query = "SELECT SUM(quantity) AS count FROM cart WHERE user_id = :user_id";
$stmt = $pdo->prepare($query); 
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC); 

$cart_count = (int) $result['count']; 

echo json_encode(['success' => true, 'count' => $cart_count]);
exit(); 
?>