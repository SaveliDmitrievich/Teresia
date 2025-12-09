<?php
session_start();
require_once __DIR__ . '/../php/auth.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчеты по заказам</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo">Teresia Admin</div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i> Главная</a></li>
                    <li><a href="products.php"><i class='bx bx-store-alt'></i> Управление товарами</a></li>
                    <li><a href="orders_report.php" class="active"><i class='bx bx-file'></i> Отчеты по заказам</a></li>
                    <li><a href="?logout=true"><i class='bx bx-log-out'></i> Выйти</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">
            <header class="admin-header">
                <h1>Отчеты по заказам</h1>
            </header>

            <section class="admin-report-filters">
                <label for="startDate">Дата начала:</label>
                <input type="date" id="startDate" name="start_date" class="form-row-field">
                <label for="endDate">Дата окончания:</label>
                <input type="date" id="endDate" name="end_date" class="form-row-field">
                <button id="generateReportBtn" class="admin-button black-btn"><i class='bx bx-receipt'></i> Сформировать отчет</button>
                <button id="exportCsvBtn" class="admin-button success" style="display: none;"><i class='bx bxs-file-export'></i> Выгрузить в CSV</button>
            </section>

            <section class="admin-report-results">
                <h2 id="reportTitle"></h2>
                <table class="admin-table" id="ordersReportTable">
                    <thead>
                        <tr>
                            <th>ID Заказа</th>
                            <th>Дата</th>
                            <th>Клиент</th>
                            <th>Телефон</th>
                            <th>Адрес</th>
                            <th>Комментарий</th>
                            <th>Товары</th>
                            <th>Общая сумма</th>
                        </tr>
                    </thead>
                    <tbody id="ordersReportBody">
                        <tr><td colspan="8">Выберите даты и нажмите "Сформировать отчет".</td></tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="js/admin_reports.js"></script>
</body>
</html>