<?php 
session_start();
require_once 'php/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();   
    session_destroy(); 
    header("Location: main.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fields = ['first_name', 'last_name', 'phone', 'address1', 'address2', 'address3'];
    $updateData = [];       
    $updateFields = [];     
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {

            $updateFields[] = "`$field` = ?"; 
            $updateData[] = $_POST[$field];
        }
    }
 if (!empty($updateFields)) {
        $updateData[] = $user_id;
        $stmt = $pdo->prepare("UPDATE users SET " . implode(", ", $updateFields) . " WHERE id_user = ?");
        $stmt->execute($updateData);
    }

    header("Location: profile.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Ошибка: пользователь не найден.";
    exit();
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
    <title>Личный кабинет</title>
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

    <div class="user-bg"></div>
    <main style="display: flex;">
        <aside class="aside-menu">
            <h1 class="menu-h1">ПРИВЕТСТВУЕМ</h1>
            <ul class="menu-ul">
                <li class="active" data-tab="account">Детали аккаунта</li>
                <li data-tab="addresses">Адреса доставки</li>
                <li data-tab="orders">Заказы</li> 
                <li data-tab="logout">Выйти</li>
            </ul>
        </aside>

        <section class="box-section">
            <article id="account" class="box tab active-tab">
                <h1 class="box-h1">Детали аккаунта</h1>
                <hr>
                <dl class="box-grid">
                    <div>
                        <dt class="box-label">Имя пользователя</dt>
                        <dd class="box-text"><?php echo $user['username']; ?></dd>
                    </div>
                    <div class="row2">
                        <dt class="box-label">Email</dt>
                        <dd class="box-text"><?php echo $user['email']; ?></dd>
                    </div>

                    <div>
                        <dt class="box-label">Имя</dt>
                        <dd class="box-text-wrapper">
                            <span class="box-text" id="first_nameText"><?php echo isset($user['first_name']) && $user['first_name'] ? $user['first_name'] : 'Не указано'; ?></span>
                            <input type="text" class="address-input" id="inputFirst_name" name="first_name" value="<?php echo isset($user['first_name']) && $user['first_name'] ? $user['first_name'] : ''; ?>" style="display: none;">
                            <i class="bx bx-edit edit-icon" onclick="toggleEdit('first_nameText', 'inputFirst_name')"></i>
                        </dd>
                    </div>

                    <div>
                        <dt class="box-label">Фамилия</dt>
                        <dd class="box-text-wrapper">
                            <span class="box-text" id="last_nameText"><?php echo isset($user['last_name']) && $user['last_name'] ? $user['last_name'] : 'Не указано'; ?></span>
                            <input type="text" class="address-input" id="inputLast_name" name="last_name" value="<?php echo isset($user['last_name']) && $user['last_name'] ? $user['last_name'] : ''; ?>" style="display: none;">
                            <i class="bx bx-edit edit-icon" onclick="toggleEdit('last_nameText', 'inputLast_name')"></i>
                        </dd>
                    </div>

                    <div>
                        <dt class="box-label">Телефон</dt>
                        <dd class="box-text-wrapper">
                            <span class="box-text" id="phoneText"><?php echo isset($user['phone']) && $user['phone'] ? $user['phone'] : 'Не указано'; ?></span>
                            <input type="text" class="address-input" id="inputPhone" name="phone" value="<?php echo isset($user['phone']) && $user['phone'] ? $user['phone'] : ''; ?>" style="display: none;">
                            <i class="bx bx-edit edit-icon" onclick="toggleEdit('phoneText', 'inputPhone')"></i>
                        </dd>
                    </div>
                </dl>
            </article>

            <article id="addresses" class="box tab">
                <h1 class="box-h1">Адреса доставки</h1>
                <hr>
                <dl class="box-grid" id="addressesGrid">
                    <div>
                        <dt class="box-label">Адрес 1</dt>
                        <dd class="box-text-wrapper">
                            <span class="box-text" id="address1Text"><?php echo isset($user['address1']) ? $user['address1'] : 'Не указано'; ?></span>
                            <input type="text" class="address-input" id="inputAddress1" name="address1" value="<?php echo isset($user['address1']) ? $user['address1'] : ''; ?>" style="display: none;">
                            <i class="bx bx-edit edit-icon" onclick="toggleEdit('address1Text', 'inputAddress1')"></i>
                        </dd>
                    </div>

                    <div class="row2">
                        <dt class="box-label">Адрес 2</dt>
                        <dd class="box-text-wrapper">
                            <span class="box-text" id="address2Text"><?php echo isset($user['address2']) ? $user['address2'] : 'Не указано'; ?></span>
                            <input type="text" class="address-input" id="inputAddress2" name="address2" value="<?php echo isset($user['address2']) ? $user['address2'] : ''; ?>" style="display: none;">
                            <i class="bx bx-edit edit-icon" onclick="toggleEdit('address2Text', 'inputAddress2')"></i>
                        </dd>
                    </div>

                    <div>
                        <dt class="box-label">Адрес 3</dt>
                        <dd class="box-text-wrapper">
                            <span class="box-text" id="address3Text"><?php echo isset($user['address3']) ? $user['address3'] : 'Не указано'; ?></span>
                            <input type="text" class="address-input" id="inputAddress3" name="address3" value="<?php echo isset($user['address3']) ? $user['address3'] : ''; ?>" style="display: none;">
                            <i class="bx bx-edit edit-icon" onclick="toggleEdit('address3Text', 'inputAddress3')"></i>
                        </dd>
                    </div>
                </dl>
            </article>

            <article id="orders" class="box tab">
                <h1 class="box-h1 order-history-title">История ваших заказов</h1>
                <hr>
                <div id="ordersList" class="orders-list">
                    <p class="order-history-empty">Нажмите на вкладку "Заказы" для загрузки истории.</p>
                </div>
            </article>

              <div id="logoutModal" class="modal">
                <div class="modal-content">
                  <h2 class="modal-text">Вы уверены, что хотите выйти?</h2>
                  <button id="confirmLogout" class="black-btn m">Выйти</button>
                  <button id="cancelLogout" class="white-btn m">Отмена</button>
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
            
            <footer class="footer" >
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
        </section>
    </main>
 
    <script src="js/profile.js"></script>
    <script src="js/order-history.js"></script> <script src="js/sticky-header.js"></script>
    <script src="js/cart.js"></script> 
</body>
</html>