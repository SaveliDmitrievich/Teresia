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
  <title>Политика конфиденциальности</title>
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

  <div class="container">
        <section class="about-section">
            <h1>Политика конфиденциальности</h1>
            <p>Дата обновления: 22.10.2024</p>

            <p>Teresia ценит и уважает важность защиты конфиденциальности наших клиентов, зарегистрированных пользователей и посетителей сайта, и разработала настоящую Политику конфиденциальности для информирования о:</p>
            <ul class="list">
                <li>персональной информации, которую мы можем собирать, когда вы посещаете наш сайт, отвечаете на наши электронные письма, оформляете заказы по почте, используете наши мобильные приложения, размещаете заказы через социальные сети (например, через приложение Facebook) или иным способом контактируете с нами;</li>
                <li>причинах сбора информации;</li>
                <li>способах сбора;</li>
                <li>использовании информации;</li>
                <li>выборе, который вы имеете в отношении использования вашей личной информации.</li>
            </ul>
            
            <p>Настоящая Политика конфиденциальности является частью Условий использования, регулирующих ваше использование нашего сайта, расположенного по адресу www.teresia.by. Сервисы предоставляются компанией Teresia (далее «Компания»).</p>

            <h2>Возрастные ограничения</h2>
            <p>Для использования наших услуг необходимо быть старше 18 лет или достигнуть возраста совершеннолетия в вашем регионе. Мы не собираем и не храним информацию о детях младше 13 лет.</p>

            <h2>Почему мы собираем информацию?</h2>
            <p>Teresia собирает личные данные для улучшения наших продуктов и обслуживания, общения с вами, обработки заказов, предоставления персонализированного покупательского опыта и информирования вас, а также получателей ваших подарков о специальных предложениях и скидках.</p>

            <h2>Какую информацию мы собираем?</h2>
            <p>Мы собираем информацию через наш сайт, электронные письма, почту, телефон, мобильные приложения и социальные сети, включая случаи, когда вы:</p>
            <ul class="list">
                <li>оформляете заказ;</li>
                <li>участвуете в форумах, опросах, конкурсах, акциях, отправляете контент, общаетесь в чате, участвуете в обсуждениях;</li>
                <li>совершаете другие действия, предоставляемые нашими услугами.</li>
            </ul>
            
            <p>В зависимости от вашего взаимодействия с нами, мы можем собирать следующие данные:</p>
            <ul class="list">
                <li>ваше имя, адрес, номер телефона, адрес электронной почты;</li>
                <li>платежные данные (номер карты, срок действия, адрес для выставления счета);</li>
                <li>пол и дата рождения, если вы предоставляете эту информацию;</li>
                <li>информация о заказанных товарах, промокоды, имена и контактные данные получателей ваших подарков.</li>
            </ul>

            <h2>Использование "Cookies" и "Action Tags"</h2>
            <p>Cookies — это небольшие файлы, отправляемые на ваш браузер с веб-сервера и сохраняющиеся на вашем устройстве. Мы используем их для определения, что вы являетесь нашим клиентом или пользователем, а также для предоставления удобных функций. Вы можете отключить cookies в настройках браузера, однако это может повлиять на работу сайта.</p>
            <p>Action Tags (или веб-маяки) используются для анонимного отслеживания активности на сайте, но не собирают личную информацию.</p>

            <h2>Как мы используем информацию?</h2>
            <p>Мы используем собранную информацию для:</p>
            <ul class="list">
                <li>общения с вами;</li>
                <li>обработки заказов;</li>
                <li>персонализации покупательского опыта;</li>
                <li>информирования вас и получателей ваших подарков о скидках и акциях.</li>
            </ul>

            <p>Мы можем передавать ваши данные третьим сторонам для маркетинговых целей, если у нас есть ваше согласие.</p>

            <h2>Как ограничить использование личной информации</h2>
            <p>Если вы не хотите, чтобы ваша личная информация передавалась третьим сторонам, или хотите настроить предпочтения по получению рекламных материалов, свяжитесь с нами по:</p>
            <ul class="list">
                <li>электронной почте: <a class="link-decoration reverse-hover" href="mailto:customerservice@teresia.by">customerservice@teresia.by</a>;</li>
              
                <li>телефону: +375 (29) 391-09-91.</li>
            </ul>

            <p>Также мы предлагаем вам отказаться от получения SMS-сообщений, отправив "STOP" на любой рекламный SMS.</p>

            <h2>Безопасность</h2>
            <p>Ваши данные, такие как номер карты, передаются через интернет с использованием защищенной технологии SSL. Мы также рекомендуем выбирать надежные пароли для входа в личный кабинет.</p>

            <h2>Дополнительные положения для Калифорнии</h2>
            <p>Жители Калифорнии могут требовать информацию о персональных данных, которые мы собираем, и о способах их использования. Также они могут запросить удаление данных. Свяжитесь с нами по адресу:</p>
            <ul class="list">
                <li>Почтовый адрес: Teresia LLC, Минск, Беларусь;</li>
                <li>Электронная почта: <a class="link-decoration reverse-hover" href="mailto:customerservice@teresia.by">customerservice@teresia.by</a>.</li>
            </ul>

            <p>Мы соблюдаем права потребителей в отношении их данных и предоставляем возможность отказаться от маркетинга.</p>

            <p>Для получения дополнительной информации о политике конфиденциальности, пожалуйста, ознакомьтесь с другими разделами данной политики.</p>
        </section>
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
