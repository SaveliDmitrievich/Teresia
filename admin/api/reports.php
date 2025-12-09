<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../php/auth.php';
require_once __DIR__ . '/../../php/db.php';
requireAdmin();

$response = ['success' => false, 'message' => '', 'orders' => []];

$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

if (empty($startDate) || empty($endDate) || !strtotime($startDate) || !strtotime($endDate)) {
    $response['message'] = 'Пожалуйста, укажите корректные даты начала и окончания.';
    echo json_encode($response);
    exit();
}

try {
    $query = "
        SELECT
            o.id_order,
            o.order_date,
            o.fullname,
            o.phone,
            o.address,
            o.comment,
            o.total_price
        FROM
            orders o
        WHERE
            DATE(o.order_date) BETWEEN :start_date AND :end_date
        ORDER BY
            o.order_date DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {
        $order_id = $order['id_order'];
        $items_query = "
            SELECT
                p.name AS product_name,
                od.quantity,
                od.price
            FROM
                order_details od
            JOIN
                shop_products p ON od.product_id = p.id_product
            WHERE
                od.order_id = :order_id
        ";
        $items_stmt = $pdo->prepare($items_query);
        $items_stmt->execute(['order_id' => $order_id]);
        $order['items'] = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

        $items_string = [];
        foreach ($order['items'] as $item) {
            $items_string[] = htmlspecialchars("{$item['product_name']} (x{$item['quantity']}, {$item['price']} BYN)");
        }
        $order['items_formatted'] = implode('; ', $items_string);
    }

    $response['success'] = true;
    $response['orders'] = $orders;

} catch (PDOException $e) {
    $response['message'] = 'Ошибка базы данных при формировании отчета.';
}

echo json_encode($response);
?>