<?php
session_start();
require_once 'php/db.php'; 

try {
    $stmt = $pdo->prepare("
        SELECT id_product, name, price, image_1 
        FROM shop_products 
        WHERE id_product BETWEEN 47 AND 69 
        ORDER BY RAND() 
        LIMIT 8
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/swiper-bundle.min.css">
    <link rel="stylesheet" href="css/base-settings.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Главная</title>
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


    <!-- Main Content -->
    <section class="main-content section-content">
        <div class="container">
            <div class="welcome-block">
                <p class="welcome-p">Добро пожаловать</p>
                <h1 class="welcome-h1">В цветочный магазин Teresia</h1>
                <img class="welcome-img" src="img/icons/snich.png" alt="Воровка">
                <p class="welcome-p-text">
                    Цветы с любовью – в любой уголок Минска за один день. <br>
                    Дарите радость там, где вы – мы доставим туда, где они.
                </p>

                <div class="welcome-buttons">
                    <a href="catalog.php?type[]=<?= urlencode('Цветы') ?>">
                        <button class="black-btn welcome-btn" type="submit">Букеты</button>
                    </a>
                    <a href="catalog.php?type[]=<?= urlencode('Растения') ?>">
                        <button class="white-btn welcome-btn" type="submit">Растения</button>
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- BESTSELLERS -->
    <div class="container">
        <div class="bestsellers">
            <div class="bestsellers-info">
                <h1 class="bestsellers-info-h1">Наш выбор</h1>
                <p class="bestsellers-info-text">
                    Подборка букетов, которые мы особенно рекомендуем. Вдохновляйтесь и находите идеальный вариант с Teresia.
                </p>
                <a href="catalog.php">
                    <button class="black-btn bestsellers-btn" type="submit">Смотреть</button>
                </a>
            </div>


            <div class="swiper-bestsellers swiper">
                <div class="swiper-wrapper">

                    <?php foreach ($products as $product): ?>
                        <div class="swiper-slide">
                            <a class="bestsellers-item" href="product.php?id=<?= $product['id_product'] ?>">
                                <span class="bestsellers-item-name"><?= htmlspecialchars($product['name']) ?></span>
                                <img class="bestsellers-img" src="./<?= htmlspecialchars($product['image_1']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span class="bestsellers-item-price"> <?= number_format($product['price'], 2, '.', '') ?> BYN</span>
                            </a>
                        </div>
                    <?php endforeach; ?>

                </div>

                <img src="img/icons/left-arrow.png" alt="" class="swiper-button-prev">
                <img src="img/icons/right-arrow.png" alt="" class="swiper-button-next">
            </div>
        </div>
    </div>


    <!-- ABOUT US -->
    <div class="info-block">
        <hr>
        <p class="info-block-text">
            Созданные для тех, кто ценит роскошь, наши букеты воплощают стиль и изящество. <br> 
            Мы с любовью создаём их в сердце Минска и доставляем в любой район города.
        </p>
        <a href="about-us.php" class="about-us-link link-decoration reverse-hover">УЗНАТЬ БОЛЬШЕ</a>
        <hr class="info-block-hr">
    </div>


    <!--КАТЕГОРИИ ЦВЕТОВ СЛАЙДЕР-->
<div class="category-block" id="category">
    <p class="category-h1">ЦВЕТЫ & ПОДАРКИ НА ЛЮБОЙ СЛУЧАЙ</p>

    <div class="swiper swiper-category">
        <div class="swiper-category-wrapper swiper-wrapper">

            <div class="category-slide swiper-slide">
                <a href="catalog.php?occasion[]=<?= urlencode('День рождения') ?>">
                    <div class="image-container">
                        <img src="./img/categories/Birthday_Category_Homepage.jpg" alt="День рождения">
                    </div>
                    <p class="category-slide-p">День рождения</p>
                </a>
            </div>

            <div class="category-slide swiper-slide">
                <a href="catalog.php?occasion[]=<?= urlencode('Сочувствие') ?>">
                    <div class="image-container">
                        <img src="./img/categories/Sympathy_Category_Homepage.jpg" alt="Сочувствие">
                    </div>
                    <p class="category-slide-p">Сочувствие</p>
                </a>
            </div>

            <div class="category-slide swiper-slide">
                <a href="catalog.php?occasion[]=<?= urlencode('Благодарность') ?>">
                    <div class="image-container">
                        <img src="./img/categories/Sympathy1_Category_Homepage.jpg" alt="Благодарность">
                    </div>
                    <p class="category-slide-p">Благодарность</p>
                </a>
            </div>

            <div class="category-slide swiper-slide">
                <a href="catalog.php?occasion[]=<?= urlencode('Дружба') ?>">
                    <div class="image-container">
                        <img src="./img/categories/Friendship_Category_Homepage.jpg" alt="Дружба">
                    </div>
                    <p class="category-slide-p">Дружба</p>
                </a>
            </div>

            <div class="category-slide swiper-slide">
                <a href="catalog.php?occasion[]=<?= urlencode('Просто так') ?>">
                    <div class="image-container">
                        <img src="./img/categories/JustBecause_Category_Homepage.jpg" alt="Просто так">
                    </div>
                    <p class="category-slide-p">Просто так</p>
                </a>
            </div>

            <div class="category-slide swiper-slide">
                <a href="catalog.php?occasion[]=<?= urlencode('Поздравление') ?>">
                    <div class="image-container">
                        <img src="./img/categories/Congratulations_Category_Homepage.jpg" alt="Поздравление">
                    </div>
                    <p class="category-slide-p">Поздравление</p>
                </a>
            </div>

        </div>

        <!-- Скроллбар -->
        <div class="swiper-scrollbar-wrapper">
            <div class="swiper-scrollbar swiper-scrollbar-category"></div>
        </div>
    </div>
</div>


    <!-- ПОДПИСКА НА ЦВЕТЫ + ВИДЕО -->
    <hr>
    <div class="subscribe-block">
        <div class="vid">
            <video src="./img/video-home-page.mp4" autoplay loop muted playsinline controls></video>
        </div>
        <div class="vid-info">
            <h1 class="vid-h1">
                Цветы с любовью к деталям и доставкой с заботой
            </h1>
            <p class="vid-text">
                Поддерживайте свежесть настроения с потрясающими букетами, доставленными вовремя. 
                Будь то для вас или для кого-то другого — это самый простой способ превратить маленький 
                жест в незабываемое переживание.
            </p>
            <a href="catalog.php"><button class="black-btn vid-btn">начать</button></a>
        </div>
    </div>
    <hr class="subscribe-block-hr">


    <!-- ОТЗЫВЫ -->
    <div class="container">
        <h1 class="review-h1">
            Тысячи восторженных отзывов — ваше доверие, наша гордость.
        </h1>
        <div class="review" id="review">
            <div class="review-item">
              <div class="review-image-container">
                  <img src="./img/customer-review/Kara_CustomerReview_Homepage.jpg" alt="" class="review-img">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-01-a.jpg" alt="" class="review-img review-back-first">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-01-b (1).jpg" alt="" class="review-img review-back-second">
              </div>
                <p class="review-name">Elizaveta</p>
                <p class="review-signature">Проверенный покупатель</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                </div>
                <p class="review-text">
                    «Я заказываю в Teresia уже около года, и каждый раз с нетерпением жду их фирменную коробку. 
                    Цветы меняются с каждым сезоном, радуя своей свежестью и красотой, а моя коллекция ваз 
                    теперь просто великолепна. Сервис — безупречный!»
                </p>
            </div>
            
            <div class="review-item">
              <div class="review-image-container">
                  <img src="./img/customer-review/Lew_CustomerReview_Homepage_1c8bd4e7-da5c-4ad4-a306-b6c4648ba56f.jpg" alt="" class="review-img">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-02-a.jpg" alt="" class="review-img review-back-first">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-02-b.jpg" alt="" class="review-img review-back-second">
              </div>

                <p class="review-name">Polina</p>
                <p class="review-signature">Проверенный покупатель</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                </div>
                <p class="review-text">
                    «Я заказала ярко-розовую композицию на важную годовщину (разве они бывают незначительными?) — 
                    и она превзошла все мои ожидания. Композиция была великолепной, а доставка — просто потрясающей.»
                </p>
            </div>
            
            <div class="review-item">
              <div class="review-image-container">
                  <img src="./img/customer-review/Jennifer_CustomerReview_Homepage.jpg" alt="" class="review-img">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-03-a.jpg" alt="" class="review-img review-back-first">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-03-b.jpg" alt="" class="review-img review-back-second">
              </div>
                <p class="review-name">Maria</p>
                <p class="review-signature">Проверенный покупатель</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                </div>
                <p class="review-text">
                    «Teresia — воплощение нежности и утончённости. Быстрая доставка, безупречный сервис — 
                    всё создано с любовью. Обязательно закажу снова!»
                </p>
            </div>

            <div class="review-item">
              <div class="review-image-container">
                  <img src="./img/customer-review/JannaV_CustomerReview_Homepage_f6bba64e-2c2f-4b75-bd80-998d265ae77a.jpg" alt="" class="review-img">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-04-a.jpg" alt="" class="review-img review-back-first">
                  <img src="./img/customer-review/UrbanStems-customer-review-card-04-b.jpg" alt="" class="review-img review-back-second">
              </div>
                <p class="review-name">Veroniсa</p>
                <p class="review-signature">Проверенный покупатель</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                </div>
                <p class="review-text">
                    «Цветы были просто великолепны, и моей лучшей подруге они очень понравились. 
                    Очень рекомендую Teresia.»
                </p>
            </div>
        </div>
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
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/swiper.js"></script>
    <script src="js/cart.js"></script>
    
</body>
</html>