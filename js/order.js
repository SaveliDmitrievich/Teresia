document.addEventListener('DOMContentLoaded', function() {

    let autoRedirectTimeout;

    function loadCart() {
        fetch('php/get-cart.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const cartItemsContainer = document.querySelector('.cart-items');
                const totalTextElement = document.querySelector('.order-summary-total-fixed strong');

                if (!cartItemsContainer || !totalTextElement) {
                    return;
                }

                if (data.success) {
                    const cartItems = data.items;
                    let cartHtml = '';
                    let totalPrice = 0;

                    cartItems.forEach(item => {
                        const price = parseFloat(item.price);
                        if (!isNaN(price)) {
                            const totalItemPrice = (price * item.quantity).toFixed(2);

                            cartHtml += `
                                <div class="order-item-card">
                                    <a href="product.php?id=${item.id_product}">
                                        <img class="order-item-img" src="./${item.image_1}" alt="${item.name}">
                                    </a>
                                    <div class="order-item-details">
                                        <a href="product.php?id=${item.id_product}">
                                            <span class="order-item-name">${item.name}</span>
                                        </a>
                                        <p class="order-item-quantity">Количество: ${item.quantity}</p>
                                        <p class="order-item-total">Сумма: ${totalItemPrice} BYN</p>
                                    </div>
                                </div>
                            `;

                            totalPrice += price * item.quantity;
                        } else {
                            console.error('Некорректная цена товара:', item.price);
                        }
                    });

                    cartItemsContainer.innerHTML = cartHtml;
                    totalTextElement.textContent = totalPrice.toFixed(2) + ' BYN';

                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = cartItems.length;
                    }

                } else {
                    cartItemsContainer.innerHTML = '<p>Ваша корзина пуста.</p>';
                    totalTextElement.textContent = '0.00 BYN';

                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = '0';
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка при загрузке корзины:', error);
                const cartItemsContainer = document.querySelector('.cart-items');
                if (cartItemsContainer) {
                    cartItemsContainer.innerHTML = '<p style="color: red;">Не удалось загрузить содержимое корзины.</p>';
                }
            });
    }

    loadCart();

    function redirectToHomeInstant() {
        const orderSuccessModal = document.getElementById('orderSuccessModal');
        if (orderSuccessModal) {
            orderSuccessModal.style.display = 'none';
        }
        document.body.style.backgroundColor = '';

        if (autoRedirectTimeout) {
            clearTimeout(autoRedirectTimeout);
        }

        window.location.href = 'main.php';
    }

    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('php/submit-order.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const orderSuccessModal = document.getElementById('orderSuccessModal');
                        if (orderSuccessModal) {
                            orderSuccessModal.style.display = 'flex';
                        }
                        document.body.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';

                        loadCart();

                        autoRedirectTimeout = setTimeout(() => {
                            window.location.href = 'main.php';
                        }, 5000); 

                    } else {
                        alert('Ошибка при оформлении заказа: ' + (data.message || 'Неизвестная ошибка'));
                    }
                })
                .catch(error => {
                    console.error('Ошибка при отправке формы:', error);
                    alert('Произошла ошибка при отправке формы заказа.');
                });
        });
    }

    function closeOrderSuccessModal() {
        const orderSuccessModal = document.getElementById('orderSuccessModal');
        if (orderSuccessModal) {
            orderSuccessModal.style.display = 'none';
        }
        document.body.style.backgroundColor = '';
        if (autoRedirectTimeout) {
            clearTimeout(autoRedirectTimeout);
        }
    }

    const closeButton = document.querySelector('#orderSuccessModal .close-button');
    if (closeButton) {
        closeButton.addEventListener('click', closeOrderSuccessModal);
    }

    const returnToHomeButton = document.getElementById('returnToHomeButton');
    if (returnToHomeButton) {
        returnToHomeButton.addEventListener('click', redirectToHomeInstant);
    }
});