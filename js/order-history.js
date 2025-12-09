document.addEventListener('DOMContentLoaded', function() {
    const ordersListDiv = document.getElementById('ordersList');

    window.loadOrderHistory = function() {
        ordersListDiv.innerHTML = '<p class="order-history-empty">Загрузка истории заказов...</p>';

        fetch('php/get-user-orders.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (data.orders && data.orders.length > 0) {
                        ordersListDiv.innerHTML = '';

                        data.orders.forEach(order => {
                            const orderCard = document.createElement('div');
                            orderCard.classList.add('order-history-block');
                            orderCard.dataset.orderId = order.id_order;

                            const orderDate = new Date(order.order_date);
                            const formattedDate = orderDate.toLocaleDateString('ru-RU', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            let itemsHtml = '';
                            if (order.items && order.items.length > 0) {
                                order.items.forEach(item => {
                                    const price = parseFloat(item.price);
                                    const quantity = parseInt(item.quantity);
                                    const totalItemPrice = (!isNaN(price) && !isNaN(quantity)) ? (price * quantity).toFixed(2) : '0.00';

                                    itemsHtml += `
                                        <div class="order-item-card">
                                            <a href="product.php?id=${item.product_id}">
                                                <img class="order-item-img" src="./${item.product_image_url}" alt="${item.product_name}">
                                            </a>
                                            <div class="order-item-details">
                                                <a href="product.php?id=${item.product_id}">
                                                    <span class="order-item-name">${item.product_name}</span>
                                                </a>
                                                <p class="order-item-quantity">Количество: ${item.quantity}</p>
                                                <p class="order-item-total">Сумма: ${totalItemPrice} BYN</p>
                                            </div>
                                        </div>
                                    `;
                                });
                            } else {
                                itemsHtml = '<p>Товары по этому заказу не найдены.</p>';
                            }

                            orderCard.innerHTML = `
                                <div class="order-history-header">
                                    <div class="order-header-info">
                                        <span>ID Заказа: <strong>${order.id_order}</strong></span>
                                        <span>Дата: <strong>${formattedDate}</strong></span>
                                        <span>Итого: <strong>${parseFloat(order.total_price).toFixed(2)} BYN</strong></span>
                                    </div>
                                    <i class='bx bx-chevron-down order-toggle-icon'></i>
                                </div>
                                <div class="order-history-details-container">
                                    <div class="order-history-items-list">
                                        <h4>Состав заказа:</h4>
                                        ${itemsHtml}
                                    </div>
                                    <div class="order-shipping-info">
                                        <h4>Информация о доставке:</h4>
                                        <p><strong>Получатель:</strong> ${order.fullname}</p>
                                        <p><strong>Телефон:</strong> ${order.phone}</p>
                                        <p><strong>Адрес:</strong> ${order.address}</p>
                                        ${order.comment ? `<p><strong>Комментарий:</strong> ${order.comment}</p>` : ''}
                                    </div>
                                </div>
                            `;
                            ordersListDiv.appendChild(orderCard);
                        });

                        document.querySelectorAll('.order-history-block').forEach(card => {
                            if (!card.dataset.listenerAttached) {
                                card.addEventListener('click', function() {
                                    this.classList.toggle('expanded');
                                    const detailsContainer = this.querySelector('.order-history-details-container');
                                    if (this.classList.contains('expanded')) {
                                        detailsContainer.style.maxHeight = detailsContainer.scrollHeight + 50 + "px";
                                    } else {
                                        detailsContainer.style.maxHeight = "0";
                                    }
                                });
                                card.dataset.listenerAttached = 'true';
                            }
                        });

                    } else {
                        ordersListDiv.innerHTML = `
                            <div class="order-history-empty">
                                <p>У вас пока нет оформленных заказов.</p>
                                <img src="img/icons/empty-box.png" alt="Нет заказов">
                            </div>
                        `;
                    }
                } else {
                    ordersListDiv.innerHTML = `<p class="order-history-empty" style="color: red;">Ошибка при загрузке истории заказов.</p>`;
                }
            })
            .catch(error => {
                ordersListDiv.innerHTML = `<p class="order-history-empty" style="color: red;">Ошибка сети: Не удалось загрузить историю заказов.</p>`;
                console.error(error.message);
            });
    };


    const initialActiveTab = document.querySelector('.aside-menu ul li.active');
    if (initialActiveTab && initialActiveTab.dataset.tab === 'orders') {
        loadOrderHistory();
    }
});