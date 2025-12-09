<?php
session_start();
require_once __DIR__ . '/../php/auth.php';
requireAdmin();
require_once __DIR__ . '/../php/db.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Главная</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo">Teresia Admin</div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="dashboard.php" class="active"><i class='bx bxs-dashboard'></i> Главная</a></li>
                    <li><a href="products.php"><i class='bx bx-store-alt'></i> Управление товарами</a></li>
                    <li><a href="orders_report.php"><i class='bx bx-file'></i> Отчеты по заказам</a></li>
                    <li><a href="?logout=true"><i class='bx bx-log-out'></i> Выйти</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">
            <header class="admin-header">
                <h1>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            </header>
            <section class="admin-dashboard-widgets">
                <div class="widget">
                    <h3>Всего товаров</h3>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM shop_products");
                    $total_products = $stmt->fetchColumn();
                    echo "<p>$total_products</p>";
                    ?>
                </div>
                <div class="widget">
                    <h3>Новых заказов (за 24 часа)</h3>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_date >= NOW() - INTERVAL 24 HOUR");
                    $new_orders = $stmt->fetchColumn();
                    echo "<p>$new_orders</p>";
                    ?>
                </div>
                </section>
        </main>
    </div>
</body>
</html>