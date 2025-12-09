<?php
session_start();
require_once 'php/db.php';

$whereClauses = [];
$params = [];

$requestData = $_GET;

if (isset($requestData['occasion']) && !empty($requestData['occasion'])) {
    $occasions = array_map(function($occasion) {
        return strtolower($occasion);
    }, $requestData['occasion']);
    $occasions = implode("', '", $occasions);
    $whereClauses[] = "LOWER(c.name) IN ('" . $occasions . "')";
}

if (isset($requestData['price'])) {
    if ($requestData['price'] == '50') {
        $whereClauses[] = "p.price < ?";
        $params[] = 50;
    } elseif ($requestData['price'] == '120') {
        $whereClauses[] = "p.price BETWEEN ? AND ?";
        $params[] = 50;
        $params[] = 120;
    } elseif ($requestData['price'] == 'more') {
        $whereClauses[] = "p.price > ?";
        $params[] = 120;
    }
}

if (isset($requestData['type']) && !empty($requestData['type'])) {
    $typeConditions = [];
    if (in_array('Цветы', $requestData['type'])) {
        $typeConditions[] = "c.parent_id = 1";  
    }
    if (in_array('Растения', $requestData['type'])) {
        $typeConditions[] = "c.parent_id = 2"; 
    }


    if (!empty($typeConditions)) {
        $whereClauses[] = "(" . implode(" OR ", $typeConditions) . ")";
    }
}

if (isset($requestData['category']) && !empty($requestData['category'])) {
    $categoryConditions = [];
    foreach ($requestData['category'] as $category) {
        $categoryConditions[] = "c.id_category = ?";
        $params[] = $category;
    }
    if (!empty($categoryConditions)) {
        $whereClauses[] = "(" . implode(" OR ", $categoryConditions) . ")";
    }
}

$sql = "SELECT DISTINCT p.* FROM shop_products p
        LEFT JOIN product_category_links pcl ON p.id_product = pcl.product_id
        LEFT JOIN product_categories c ON pcl.category_id = c.id_category";

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

if (isset($requestData['sort']) && $requestData['sort'] === 'price_asc') {
    $sql .= " ORDER BY p.price ASC";
} elseif (isset($requestData['sort']) && $requestData['sort'] === 'price_desc') {
    $sql .= " ORDER BY p.price DESC";
} else {
    $sql .= " ORDER BY p.id_product DESC";
}

$stmt = $pdo->prepare($sql);

if (!empty($params)) {
    $stmt->execute($params);
} else {
    $stmt->execute();
}

$catalogTitle = "ЦВЕТОЧНЫЕ БУКЕТЫ";
$catalogDescription = "Мы создаём букеты, опираясь на лучшие традиции флористики Нидерландов — страны цветов. Вдохновляясь их эстетикой и гармонией природы, мы дарим вам композиции, которые радуют глаз и сердце.";

if (isset($requestData['type'])) {
    if (in_array('Цветы', $requestData['type'])) {
        $catalogTitle = "ЦВЕТЫ";
        $catalogDescription = "Откройте для себя разнообразие свежих цветов – от классических роз до экзотических лилий. Каждый цветок – это послание чувств.";
    } elseif (in_array('Растения', $requestData['type'])) {
        $catalogTitle = "РАСТЕНИЯ";
        $catalogDescription = "Зелёные друзья для вашего дома или офиса. Комнатные растения не только украсят интерьер, но и наполнят пространство жизнью.";
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
    <title>Каталог</title>
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

<main style="margin-top: 100px;">
    <div class="container">
        <div class="catalog">
            <section class="catalog-welcome">
                <h1 class="catalog-header"><?= htmlspecialchars($catalogTitle) ?></h1>
                <p class="catalog-header-text"><?= htmlspecialchars($catalogDescription) ?></p>
            </section>

            <div class="catalog-main-content">
                <aside class="catalog-sidebar">
                    <div class="filter">
                        <h2 class="filter-header">Категории & Фильтры</h2>
                        <div class="filter-categories">
                            <form method="GET" action="">

                                <details class="filter-category" <?= isset($requestData['sort']) ? 'open' : '' ?>>
                                    <summary class="filter-category-name">Сортировка<i class='bx bx-chevron-up'></i></summary>
                                    <div class="filter-category-wrapper">

                                        <div class="filter-category-text <?= empty($requestData['sort']) ? 'active' : '' ?>">
                                            <input type="radio" name="sort" value="" id="sort-default" <?= empty($requestData['sort']) ? 'checked' : '' ?>>
                                            <label for="sort-default">По умолчанию</label>
                                        </div>
                                        <div class="filter-category-text <?= (isset($requestData['sort']) && $requestData['sort'] === 'price_asc') ? 'active' : '' ?>">
                                            <input type="radio" name="sort" value="price_asc" id="sort-asc" <?= (isset($requestData['sort']) && $requestData['sort'] === 'price_asc') ? 'checked' : '' ?>>
                                            <label for="sort-asc">Сначала дешевле</label>
                                        </div>
                                        <div class="filter-category-text <?= (isset($requestData['sort']) && $requestData['sort'] === 'price_desc') ? 'active' : '' ?>">
                                            <input type="radio" name="sort" value="price_desc" id="sort-desc" <?= (isset($requestData['sort']) && $requestData['sort'] === 'price_desc') ? 'checked' : '' ?>>
                                            <label for="sort-desc">Сначала дороже</label>
                                        </div>
                                    </div>
                                </details>
                                <details class="filter-category" <?= isset($requestData['occasion']) ? 'open' : '' ?>>
                                    <summary class="filter-category-name">Случай<i class='bx bx-chevron-up'></i></summary>
                                    <div class="filter-category-wrapper">
                                        <?php 
                                        $occasions = ['День рождения', 'Сочувствие', 'Благодарность', 'Дружба', 'Просто так', 'Поздравление'];
                                        foreach ($occasions as $occasion): 
                                        ?>
                                            <div class="filter-category-text <?= isset($requestData['occasion']) && in_array($occasion, $requestData['occasion']) ? 'active' : '' ?>">
                                                <input type="checkbox" name="occasion[]" value="<?= $occasion ?>" id="<?= strtolower(str_replace(' ', '', $occasion)) ?>" <?= isset($requestData['occasion']) && in_array($occasion, $requestData['occasion']) ? 'checked' : '' ?>>
                                                <label for="<?= strtolower(str_replace(' ', '', $occasion)) ?>"><?= $occasion ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </details>

                                <details class="filter-category" <?= isset($requestData['price']) ? 'open' : '' ?>>
                                    <summary class="filter-category-name">Цена<i class='bx bx-chevron-up'></i></summary>
                                    <div class="filter-category-wrapper">
                                        <div class="filter-category-text <?= !isset($requestData['price']) || empty($requestData['price']) ? 'active' : '' ?>">
                                            <input type="radio" name="price" value="" id="price-all" <?= !isset($requestData['price']) || empty($requestData['price']) ? 'checked' : '' ?>>
                                            <label for="price-all">Любая цена</label>
                                        </div>
                                        <?php 
                                        $prices = ['50' => 'до 50 BYN', '120' => 'от 50 BYN до 120 BYN', 'more' => 'свыше 120 BYN'];
                                        foreach ($prices as $value => $label): 
                                        ?>
                                            <div class="filter-category-text <?= isset($requestData['price']) && $requestData['price'] == $value ? 'active' : '' ?>">
                                                <input type="radio" name="price" value="<?= $value ?>" id="price-<?= $value ?>" <?= isset($requestData['price']) && $requestData['price'] == $value ? 'checked' : '' ?>>
                                                <label for="price-<?= $value ?>"><?= $label ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </details>

                                <details class="filter-category" <?= isset($requestData['type']) ? 'open' : '' ?>>
                                    <summary class="filter-category-name">Тип<i class='bx bx-chevron-up'></i></summary>
                                    <div class="filter-category-wrapper">
                                        <div class="filter-category-text <?= isset($requestData['type']) && in_array('Цветы', $requestData['type']) ? 'active' : '' ?>">
                                            <input type="checkbox" name="type[]" value="Цветы" id="flowers" <?= isset($requestData['type']) && in_array('Цветы', $requestData['type']) ? 'checked' : '' ?>>
                                            <label for="flowers">Цветы</label>
                                        </div>
                                        <div class="filter-category-text <?= isset($requestData['type']) && in_array('Растения', $requestData['type']) ? 'active' : '' ?>">
                                            <input type="checkbox" name="type[]" value="Растения" id="plants" <?= isset($requestData['type']) && in_array('Растения', $requestData['type']) ? 'checked' : '' ?>>
                                            <label for="plants">Растения</label>
                                        </div>
                                    </div>
                                </details>
                            </form>
                        </div>
                    </div>
                </aside>

                <section class="catalog-grid">
                    <?php if ($stmt && $stmt->rowCount() > 0): ?>
                        <?php while ($product = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="catalog-item">
                                <a href="product.php?id=<?= $product['id_product'] ?>">
                                    <span class="catalog-item-name"><?= htmlspecialchars($product['name']) ?></span>
                                    <img class="catalog-item-img" src="./<?= htmlspecialchars($product['image_1']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <span class="catalog-item-price"><?= number_format($product['price'], 2, '.', '') ?> BYN</span>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-products">Товары не найдены.</p>
                    <?php endif; ?>
                </section>
            </div>
        </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach((input) => {
        input.addEventListener('change', function() {
            this.form.submit(); 
        });
    });
});
</script>

</body>
</html>