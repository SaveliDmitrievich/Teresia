<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/base-settings.css">
  <link rel="stylesheet" href="css/styles.css">
  <title>О нас</title>
</head>
<body>

<!-- Header -->
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



<!-- КАРТИНКА СВЕРХУ МЕЙН -->
  <div class="about-us-main">
    <div class="about-us-content">
      <img class="about-us-img" src="./img/icons/leaf.png" alt="">
        <p class="about-us-text">2</p>
        <p class="about-us-text">Белорусских парня</p>
        <p class="about-us-text">Вдохновленные</p>  
        <p class="about-us-text">Парижем</p>
      <img class="about-us-img" src="./img/icons/leaf-reversed.png" alt="">
    </div>
  </div>


  <div class="container">
    <!-- БЛОК БЛОГ С ТЕКСТОМ И МОПСОМ -->
    <div class="blog">
      <div class="blog-img">
        <img src="./img/mops.webp" alt="">
      </div>
     <div class="blog-info">
      <h1 class="blog-h1">Мы — белорусские итальянцы, вдохновлённые Парижем.</h1>
      <p class="blog-text">Мы, Оливер Савелини и Луи Кузичь, белорусские итальянцы, вдохновлённые Парижем. Когда-то мы мечтали создать нечто прекрасное, что передавало бы эмоции без слов.</p>
      <p class="blog-text">Минск — наш дом, но во французской культуре цветов мы нашли особое вдохновение. Мы любили эстетику, историю, философию букета как послания. Однако, оглядываясь вокруг, мы понимали: здесь не хватает чего-то по-настоящему изысканного, того, что несёт в себе шарм европейской флористики.</p>
      <p class="blog-text">Так родилась Teresia — не просто цветочный сервис, а история, рассказанная лепестками. Мы создаём букеты, которые говорят за вас: о любви, благодарности, мечтах и воспоминаниях..</p>
      <p class="blog-text">
        <em class="quote">"Я бы купил у мужиков с такой биографией цветы." <br>
— Луи Кузичь</em>
      </p>
      
     </div>
    </div>


    <!-- icons-block -->
    <ul class="icons">
      <li class="icons-item">
        <img src="./img/icons/truck.svg" alt="truck" class="icons-item-img">
        <h1 class="icons-item-h1">Доставка в день заказа</h1>
        <p class="icons-item-text">Доступно в Минске. Мы обеспечиваем быструю доставку цветов в течение дня по городу.</p>
      </li>
      <li class="icons-item">
        <img src="./img/icons/bouquet.svg" alt="bouquet" class="icons-item-img">
        <h1 class="icons-item-h1">Цветы от местных флористов</h1>
        <p class="icons-item-text">Наши композиции создаются вручную профессиональными флористами прямо в Минске, используя лучшие цветы и уникальные стили оформления.</p>
      </li>
      <li class="icons-item">
        <img src="./img/icons/box.svg" alt="gift-box" class="icons-item-img">
        <h1 class="icons-item-h1">Фирменная подарочная упаковка</h1>
        <p class="icons-item-text">Большинство наших композиций доставляется в стильной коробке, чтобы подчеркнуть вашу заботу и сохранить цветы в идеальном состоянии.</p>
      </li>
      <li class="icons-item">
        <img src="./img/icons/camera.svg" alt="camera" class="icons-item-img">
        <h1 class="icons-item-h1">Получите фото вашего букета</h1>
        <p class="icons-item-text">Мы отправим вам фотографию вашего букета, чтобы вы могли убедиться, что получатель получит именно то, что вы выбрали.</p>
      </li>
    </ul>
</div>

    <!-- Модальное окно корзины -->
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
</body>
</html>
