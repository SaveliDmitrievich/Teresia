<?php
session_start();
header('Content-Type: application/json');

require_once 'db.php'; 

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Пользователь не авторизован.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

try {

    $query_orders = "
        SELECT
            o.id_order,
            o.order_date,
            o.total_price,
            o.fullname,
            o.phone,
            o.address,
            o.comment
        FROM
            orders o
        WHERE
            o.user_id = :user_id
        ORDER BY
            o.order_date DESC
    ";
    $stmt_orders = $pdo->prepare($query_orders);
    $stmt_orders->execute(['user_id' => $user_id]);
    $user_orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

    $orders_with_details = [];

    foreach ($user_orders as $order) {
        $order_id = $order['id_order'];

        $query_order_details = "
            SELECT
                od.product_id,
                od.quantity,
                od.price,
                p.name AS product_name,
                p.image_1 AS product_image_url
            FROM
                order_details od
            JOIN
                shop_products p ON od.product_id = p.id_product
            WHERE
                od.order_id = :order_id
        ";
        $stmt_details = $pdo->prepare($query_order_details);
        $stmt_details->execute(['order_id' => $order_id]);
        $order_details = $stmt_details->fetchAll(PDO::FETCH_ASSOC);

        $order['items'] = $order_details;
        $orders_with_details[] = $order;
    }

    $response['success'] = true;
    $response['orders'] = $orders_with_details;

} catch (PDOException $e) {
    error_log("Ошибка при получении истории заказов пользователя (API): " . $e->getMessage());
    $response['message'] = 'Ошибка базы данных при загрузке заказов.';
}

echo json_encode($response);
?>