<?php
session_start();
require_once __DIR__ . '/../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id_user, username, password, is_admin FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        if ($user['is_admin'] === 1) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = true; 
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "У вас нет прав администратора.";
        }
    } else {
        $error = "Неверный логин или пароль.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в Админ-панель</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-login-container">
        <h2>Вход в Админ-панель</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <label for="username">Логин:</label>
            <input type="text" id="username" name="username" class="form-row-field" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" class="form-row-field" required>
            <button type="submit" name="login_admin" class="admin-button black-btn">Войти</button>
        </form>
    </div>
</body>
</html>