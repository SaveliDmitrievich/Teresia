<?php
session_start();
include('php/db.php'); 

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $query = "SELECT * FROM shop_products WHERE id_product = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$product_id]);

    if ($stmt->rowCount() > 0) {
        $product = $stmt->fetch();
    } else {
        echo "Товар не найден.";
        exit;
    }
} else {
    echo "Некорректный запрос.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/base-settings.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <title>Страница товара</title>
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

    <!-- МЕЙН БЛОК ТОВАРА -->
    <main>
        <div class="product-detail">
            <div class="swiper swiper-product">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img class="swiper-slide-img" src="./<?php echo htmlspecialchars($product['image_1']); ?>" alt="" />
                    </div>
                    <div class="swiper-slide">
                        <img class="swiper-slide-img" src="./<?php echo htmlspecialchars($product['image_2']); ?>" alt="" />
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="swiper-nav-btns">
                    <img src="img/icons/left-arrow.png" alt="Previous" class="swiper-button-prev">
                    <img src="img/icons/right-arrow.png" alt="Next" class="swiper-button-next">
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="product-price"><?php echo number_format($product['price'], 2); ?> BYN</p>
                <img src="img/icons/bouquet.svg" width="64" height="64" alt="" />
                <p class="product-p"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <button onclick="addToCart(<?= $product_id ?>, 1)" class="black-btn">Добавить в корзину</button>


            </div>
        </div>

        <div class="container">
            <div class="accordeon-container">
                <details class="details" open>
                    <summary class="details-title">Описание<i class='bx bx-chevron-up'></i></summary>
                    <p class="details-content">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>
                </details>
                    <details class="details">
                        <summary class="details-title">Cоветы по уходу<i class='bx bx-chevron-up'></i></summary>
                        <p class="details-content">
                            1) Найдите вазу: вы, возможно, заметили, что у ваших цветов больше нет корней, и что они не в почве. Спорим, они жаждут! Время поместить их в свою любимую вазу с пресной водой.<br /><br />
                            2) Обрежьте эти стебли: держите эти стебли подстрижены! Всегда вырежьте свои цветы под углом, чтобы они могли поглощать больше воды и не задохнуться на дне вазы. После первоначального разреза проверяйте дно ваших стеблей каждые пару дней, и если концы выглядят немного слизистыми, просто срезайте на дюйм или около того.<br /><br />
                            3) Удалите листья: снимите лишние листья, чтобы вся вода была направлена ​​в лепестки. Обратите особое внимание на листья под линией воды, потому что они разлагаются, вызывая рост гниения и бактерий, что сократит продолжительность жизни ваших цветов.<br /><br />
                            4) Удалите отработанные цветы: любые цветы, которые проходят их основной газ этилена, который стареет цветами вокруг них. Удалите любые цветы, которые начинают увядать, чтобы остальная часть вашего букета оставалась свежей и счастливой.<br /><br />
                            5) Держите их прохладными: не в последнюю очередь, держите свои цветы в прохладной области. Не ставьте их рядом с радиаторами, компьютерами, телевизорами, каминами, печами... вы понимаете.
                        </p>
                    </details>
            </div>
        </div>

        <hr />

        <div class="subscribe-block">
            <div class="vid">
                <video src="img/video-flower-garden.mp4" autoplay loop muted playsinline controls></video>
            </div>
            <div class="vid-info">
                <h1 class="vid-h1">Создано с любовью в Минске</h1>
                <p class="vid-text">
                    Мы напрямую сотрудничаем с фермами чтобы воплощать наши цветочные идеи, предлагая самые стильные и свежие композиции с доставкой прямо к вашей двери.
                </p>
                <a href="about-us.php">
                    <button class="black-btn mt-100">О нас</button>
                </a>
            </div>
        </div>

        <hr class="subscribe-block-hr" />

        <div class="container">
            <ul class="icons">
                <li class="icons-item">
                    <img src="./img/icons/truck.svg" alt="truck" class="icons-item-img" />
                    <h1 class="icons-item-h1">Доставка в день заказа</h1>
                    <p class="icons-item-text">Доступно в Минске. Мы обеспечиваем быструю доставку цветов в течение дня по городу.</p>
                </li>
                <li class="icons-item">
                    <img src="./img/icons/bouquet.svg" alt="bouquet" class="icons-item-img" />
                    <h1 class="icons-item-h1">Цветы от местных флористов</h1>
                    <p class="icons-item-text">Наши композиции создаются вручную профессиональными флористами прямо в Минске, используя лучшие цветы и уникальные стили оформления.</p>
                </li>
                <li class="icons-item">
                    <img src="./img/icons/box.svg" alt="gift-box" class="icons-item-img" />
                    <h1 class="icons-item-h1">Фирменная подарочная упаковка</h1>
                    <p class="icons-item-text">Большинство наших композиций доставляется в стильной коробке, чтобы подчеркнуть вашу заботу и сохранить цветы в идеальном состоянии.</p>
                </li>
                <li class="icons-item">
                    <img src="./img/icons/camera.svg" alt="camera" class="icons-item-img" />
                    <h1 class="icons-item-h1">Получите фото вашего букета</h1>
                    <p class="icons-item-text">Мы отправим вам фотографию вашего букета, чтобы вы могли убедиться, что получатель получит именно то, что вы выбрали.</p>
                </li>
            </ul>
        </div>
    </main>

    
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

    <script>
        function toggleCartItem(productId) {
    const button = document.getElementById('add-to-cart-btn');
    const isInCart = button.classList.contains('in-cart');

    const url = isInCart ? 'php/remove-from-cart.php' : 'php/add-to-cart.php';

    fetch(url, {
        method: 'POST',
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${encodeURIComponent(productId)}${!isInCart ? '&quantity=1' : ''}`
    })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            if (isInCart) {
            button.textContent = 'Добавить в корзину';
            button.classList.remove('in-cart');
            } else {
            button.textContent = 'Товар в корзине';
            button.classList.add('in-cart');
            }
            updateCartCount();
        } else {
            alert('Ошибка: ' + data.message);
        }
        })
        .catch(error => {
        console.error('Ошибка при переключении товара в корзине:', error);
        });
    }

    </script>
    <script src="js/sticky-header.js"></script>
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/swiper.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>
