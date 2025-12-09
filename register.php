<?php
session_start();
require_once 'php/db.php'; 

$error_message = ''; 
$success_message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $name = htmlspecialchars(trim($_POST['name']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (strpos($password, ' ') !== false) {
        $error_message = "Пароль не должен содержать пробелов.";
    }

    else if (strlen($password) < 8) {
        $error_message = "Пароль должен быть не менее 8 символов.";
    }
    else if (!preg_match("/[A-Z]/", $password)) {
        $error_message = "Пароль должен содержать хотя бы одну заглавную букву.";
    }
    else if (!preg_match("/[a-z]/", $password)) {
        $error_message = "Пароль должен содержать хотя бы одну строчную букву.";
    }
    else if (!preg_match("/[0-9]/", $password)) {
        $error_message = "Пароль должен содержать хотя бы одну цифру.";
    }

    else if (strlen($name) < 3) {
        $error_message = "Имя пользователя должно содержать как минимум 2 символа.";
    }
    if ($password !== $confirm_password) {
        $error_message = "Пароли не совпадают.";
    } 

    if (empty($error_message)) {
        $query = "SELECT id_user FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $userByEmail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userByEmail) {
            $error_message = "Пользователь с таким email уже существует.";
        }
    }

    if (empty($error_message)) {
        $query = "SELECT id_user FROM users WHERE username = :username LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $name]);
        $userByName = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userByName) {
            $error_message = "Имя пользователя уже занято. Пожалуйста, выберите другое.";
        }
    }

    if (empty($error_message)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $insertQuery = "INSERT INTO users (email, username, password) VALUES (:email, :username, :password)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute([
                'email' => $email,
                'username' => $name,
                'password' => $hashed_password
            ]);

            $_SESSION['registration_success'] = "Вы успешно зарегистрированы! Пожалуйста, войдите.";
            header('Location: login.php');
            exit;

        } catch (PDOException $e) {
            $error_message = "Произошла ошибка при регистрации. Пожалуйста, попробуйте позже.";
        }
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
    <title>Регистрация</title>
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

    <main class="main-content">
        <div class="container">
            <form action="" method="POST" class="reg-form">
                <div class="reg-header">
                    <h1 class="reg-h1">зарегистрироваться</h1>
                    <p class="reg-text">
                        Зарегистрируйтесь, чтобы создать учетную запись в Teresia. Получайте скидку на каждый третий заказ при входе в систему и сохраняйте данные о доставке и отправителе для удобных покупок в будущем!
                    </p>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="message error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="message success-message"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>

                <div class="form-row">
                    <label class="label" for="email">Email</label>
                    <div class="form-row-wrapper">
                        <input class="form-row-field" type="email" id="email" name="email" required placeholder="Введите ваш email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label class="label" for="name">Имя пользователя</label>
                    <div class="form-row-wrapper">
                        <input class="form-row-field" type="text" id="name" name="name" required placeholder="Введите ваше имя пользователя" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label class="label" for="password">Пароль</label>
                    <div class="form-row-wrapper password-wrapper">
                        <input class="form-row-field" type="password" id="password" name="password" required placeholder="Введите пароль">
                        <i class='bx bx-hide toggle-password' onclick="togglePassword(this, 'password')"></i>
                    </div>
                </div>

                <div class="form-row">
                    <label class="label" for="confirm-password">Повторите пароль</label>
                    <div class="form-row-wrapper password-wrapper">
                        <input class="form-row-field" type="password" id="confirm-password" name="confirm_password" required placeholder="Повторите пароль">
                        <i class='bx bx-hide toggle-password' onclick="togglePassword(this, 'confirm-password')"></i>
                    </div>
                </div>
                
                <div id="password-error">Пароли не совпадают!</div>
                <button class="black-btn" type="submit">Зарегистрироваться</button>
                <hr class="horizontal-line">
                <p class="reg-head-text">У вас уже есть аккаунт?</p>
                <a href="login.php" class="reg-head-text link-decoration reverse-hover">Войти</a>
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