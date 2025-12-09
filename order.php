<?php
session_start();
require_once('php/db.php'); 

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/base-settings.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Оформление заказа</title>

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

<div class="container">
    <div class="order-form" style="margin: 70px auto;">
      <div class="order-text">
        <h1 class="reg-h1 mt50">Оформление заказа</h1>
        <p class="reg-text">Пожалуйста, проверьте и заполните информацию для доставки вашего заказа.</p>
      </div>

        <div class="checkout-container">
            <div class="checkout-form">
                <form id="checkoutForm" action="php/submit-order.php" method="POST">
                    <div class="form-row">
                        <label for="fullname" class="label">Имя и фамилия</label>
                        <div class="form-row-wrapper">
                            <input type="text" name="fullname" id="fullname" class="form-row-field checkout-input" placeholder="Введите ваше имя и фамилию" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="phone" class="label">Телефон</label>
                        <div class="form-row-wrapper">
                            <input type="tel" name="phone" id="phone" class="form-row-field checkout-input" pattern="[\+]?[\d\s\-]{7,15}" placeholder="Введите ваш телефон" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="address" class="label">Адрес доставки</label>
                        <div class="form-row-wrapper">
                            <input type="text" name="address" id="address" class="form-row-field checkout-input" placeholder="Введите адрес доставки" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="comment" class="label">Комментарий к заказу (необязательно)</label>
                        <div class="form-row-wrapper">
                            <textarea name="comment" id="comment" class="form-row-field checkout-textarea" style="height: 100px;" placeholder="Введите комментарий к заказу (необязательно)"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <button type="submit" class="black-btn">Подтвердить заказ</button>
                    </div>
                </form>

            </div>

            <div class="order-summary">
                <h2 class="order-summary-title">Ваши товары</h2>
                <div class="cart-items">
                    </div>
                  </div>
                </div>
                <div class="order-summary-total-fixed">
        <p>Итоговая сумма: <strong>0 BYN</strong></p>
    </div>



    </div>
</div>

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

<div class="order-success-modal" id="orderSuccessModal">
    <div class="order-success-modal-content">
        <h2>Спасибо за заказ!</h2>
        <p>Ваш заказ успешно оформлен. Мы свяжемся с вами в ближайшее время.</p>
        <div class="order-success-modal-actions">
            <button class="black-btn" id="returnToHomeButton">Вернуться на главную</button>
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
    
<script src="js/cart.js"></script>
<script src="js/order.js"></script>
<script src="js/sticky-header.js"></script>
</body>
</html>