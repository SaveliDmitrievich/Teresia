<?php
session_start();
require_once 'php/db.php'; 

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin']; 

        header('Location: main.php');
        exit;
    } else {
        $error_message = "Неверный email или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/base-settings.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Авторизация</title>
</head>
<body>

<header class="header">
    <div class="header-container">
        <nav class="header-nav">
            <div class="header-logo">
                <a class="header-item-link" href="main.php">Teresia</a> 
            </div>
            <ul class="header-list">
                <li class="header-item">
                    <a href="catalog.php?type[]=<?= urlencode('Цветы') ?>" class="header-item-link link-decoration">Цветы</a>
                </li>
                <li class="header-item">
                    <a href="catalog.php?type[]=<?= urlencode('Растения') ?>" class="header-item-link link-decoration">Растения</a>
                </li>
                <li class="header-item"><a href="about-us.php" class="header-item-link link-decoration">О нас</a></li>
                <li class="header-item"><a href="terms-conditions.php" class="header-item-link link-decoration">Условия и Положения</a></li>
            </ul>
            <ul class="header-icons">
                <li class="header-icon-user">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="header-item-link"><i class="bx bx-user-circle"></i></a>
                    <?php else: ?>
                        <a href="login.php" class="header-item-link"><i class="bx bx-user"></i></a>
                    <?php endif; ?>
                </li>
                <li class="header-icon-shopping-bag">
                    <a href="#" id="open-cart" class="header-item-link">
                        <i class="bx bx-shopping-bag"></i>
                        <span id="cart-count">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<main class="main-content log-main-content">
    <div class="container">
        <form action="login.php" method="POST" class="reg-form log-form">
            <div class="log">
                <div class="reg-header log-header">
                    <h1 class="reg-h1 log-h1">войти</h1>
                </div>
        
                <div class="form-row">
                    <label class="label" for="email">Email</label> 
                    <div class="form-row-wrapper">
                        <input class="form-row-field" type="email" id="email" name="email" required placeholder="Введите ваш email"> 
                    </div>
                </div>
                
                <div class="form-row">
                    <label class="label" for="password">Пароль</label>
                    <div class="form-row-wrapper">
                        <input class="form-row-field" type="password" id="password" name="password" required placeholder="Введите ваш пароль">
                        <i class='bx bx-hide toggle-password' onclick="togglePassword(this, 'password')"></i>
                    </div>
                </div>

                <button class="black-btn fr" type="submit">войти</button>
                
                <?php if (!empty($error_message)):?>
                    <div class="error-message" style="color: red; text-align: center; margin-top: 10px;">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <hr class="vertical-line">

            <div class="new-client">
                <p class="new-client-text">НОВЫЙ КЛИЕНТ?</p>
                <div class="subscribe">
                    <p class="subscribe-text">Бесплатно и легко, создайте аккаунт, чтобы не потерять свои данные!</p>
                    <i class='bx bx-leaf'></i>
                </div>
                <a href="register.php"><button class="white-btn" type="button">Создать аккаунт</button></a> 
            </div>
        </form>
    </div>
</main>

<div class="cart-modal">
    <div class="cart-modal-content">
        <h2>Ваша корзина</h2>
        <div id="cart-items-list">
        </div>
        <div id="total-price">Итого: 0.00 BYN</div> 
        <div class="cart-modal-actions">
            <button id="checkout-btn" class="black-btn">Оформить заказ</button>
            <button id="close-cart" class="white-btn">Закрыть</button>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="logo-top">
            <h2 class="logo-text">Teresia</h2>
        </div>
        
        <div class="footer-sections-wrapper">
            <div class="footer-section footer-about-text">
                <p class="head-ul">О Teresia</p>
                <p class="footer-description">
                    Teresia — это ваш источник свежих цветов и растений для любого случая. Мы создаём уникальные букеты и композиции, вдохновляясь природной красотой и лучшими традициями флористики, чтобы каждый момент стал особенным.
                </p>
            </div>

            <div class="footer-section">
                <p class="head-ul">Информация</p>
                <ul>
                    <li><a href="about-us.php" class="link-decoration">О нас</a></li>
                    <li><a href="terms-conditions.php" class="link-decoration">Условия и положения</a></li>
                    <li><a href="privacy-policy.php" class="link-decoration">Политика конфиденциальности</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <p class="head-ul">Быстрые ссылки</p>
                <ul>
                    <li><a href="catalog.php" class="link-decoration">Каталог</a></li>
                    <li><a href="main.php#review" class="link-decoration">Отзывы</a></li> 
                    <li><a href="main.php#category" class="link-decoration">Наши категории</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom-row">
            <p class="footer-copyright">
                &copy; 2025 Teresia. Все права защищены.
            </p>
        </div>
    </div>
</footer>

<script src="js/sticky-header.js"></script>
<script src="js/cart.js"></script>
<script src="js/toggle-password.js"></script>
</body>
</html>