document.addEventListener('DOMContentLoaded', function () {
  updateCartCount(); 
  markCartItems();


  const openCartButton = document.getElementById('open-cart');
  if (openCartButton) { 
      openCartButton.addEventListener('click', function () {
          const cartModal = document.querySelector('.cart-modal');
          if (cartModal) {
              cartModal.style.display = 'flex';
              loadCartItems(); 
          }
      });
  }

  const closeCartButton = document.getElementById('close-cart');
  if (closeCartButton) { 
      closeCartButton.addEventListener('click', function () {
          const cartModal = document.querySelector('.cart-modal');
          if (cartModal) {
              cartModal.style.display = 'none';
          }
      });
  }

  const checkoutButton = document.getElementById('checkout-btn');
  if (checkoutButton) { 
      checkoutButton.addEventListener('click', function() {
          const cartItems = document.querySelectorAll('.cart-item');
          if (cartItems.length > 0) {
              window.location.href = 'order.php';
          } else {
              alert('Ваша корзина пуста. Добавьте товары в корзину перед оформлением заказа.');
          }
      });
  }
});

function addToCart(productId, quantity = 1) {
  const button = document.querySelector(`button[onclick*="addToCart(${productId}"]`);
  fetch('php/add-to-cart.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          updateCartCount(); 
          if (button) {
              button.textContent = 'Товар в корзине';
              button.disabled = true;
              button.classList.add('in-cart');
              button.classList.remove('black-btn');
          }
      } else {
          alert('Ошибка: ' + data.message);
      }
  })
  .catch(error => {
      console.error('Ошибка при добавлении в корзину:', error);
  });
}

function loadCartItems() {
  const container = document.getElementById('cart-items-list');
  const checkoutBtn = document.getElementById('checkout-btn');
  const totalPriceElement = document.getElementById('total-price');

  fetch('php/get-cart.php')
      .then(response => response.json())
      .then(data => {
          container.innerHTML = '';

          if (data.success && data.items && data.items.length > 0) {
              let totalAmount = 0;

              checkoutBtn.disabled = false;
              checkoutBtn.classList.remove('disabled-btn');
              checkoutBtn.title = '';

              data.items.forEach(item => {
                  const existingItem = document.getElementById(`cart-item-${item.product_id}`);

                  if (existingItem) {
                      const quantityInput = existingItem.querySelector('input[type="number"]');
                      quantityInput.value = item.quantity;
                      const totalPrice = existingItem.querySelector('.cart-item-price');
                      const itemTotal = item.price * item.quantity;
                      totalPrice.textContent = itemTotal.toFixed(2) + " BYN";
                      totalAmount += itemTotal;
                  } else {
                      const total = (item.price * item.quantity).toFixed(2);
                      const cartItem = document.createElement('div');
                      cartItem.classList.add('cart-item');
                      cartItem.id = `cart-item-${item.product_id}`;

                      cartItem.innerHTML = `
                          <div class="cart-item-details">
                              <img src="./${item.image_1}" alt="${item.name}" class="cart-item-image">
                              <span class="cart-item-name">${item.name}</span>
                              <span class="cart-item-price">${total} BYN</span>
                          </div>
                          <div class="cart-item-quantity">
                              <input type="number" value="${item.quantity}" min="1" max="99" onchange="updateQuantity(${item.product_id}, this.value)">
                              <span class="cart-item-remove" onclick="removeFromCart(${item.product_id})">Удалить</span>
                          </div>
                      `;

                      container.appendChild(cartItem);
                      totalAmount += item.price * item.quantity;
                  }
              });

              totalPriceElement.textContent = `Итого: ${totalAmount.toFixed(2)} BYN`;

          } else {
              container.innerHTML = `
                  <div class="empty-cart-message">
                      <p id="cart-empty">Корзина пуста.</p>
                      <img id="cart-empty-img" src="img/icons/cart-empty.png" alt="Корзина пуста">
                  </div>
              `;

              checkoutBtn.disabled = true;
              checkoutBtn.classList.add('disabled-btn');
              checkoutBtn.title = 'Добавьте товары в корзину, чтобы оформить заказ';
          }
      })
      .catch(error => {
          console.error('Ошибка при загрузке корзины:', error);
          container.innerHTML = `<p>Ошибка при загрузке корзины: ${error.message}</p>`;
      });
}

function updateQuantity(productId, newQuantity) {
  newQuantity = parseInt(newQuantity);
  if (newQuantity < 1) return;

  fetch('php/update-cart-quantity.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(newQuantity)}`
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          updateCartCount(); 
          loadCartItems();
      } else {
          alert('Ошибка при обновлении количества');
      }
  })
  .catch(error => {
      console.error('Ошибка при обновлении количества:', error);
  });
}

function removeFromCart(productId) {
  fetch('php/remove-from-cart.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `product_id=${encodeURIComponent(productId)}`
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          const itemElement = document.getElementById(`cart-item-${productId}`);
          if (itemElement) {
              itemElement.remove();
          }

          const button = document.querySelector(`button[onclick*="addToCart(${productId}"]`);
          if (button) {
              button.textContent = 'Добавить в корзину';
              button.disabled = false;
              button.classList.remove('in-cart');
              button.classList.add('black-btn');
          }

          updateCartCount(); 
          updateTotalPrice();

          const remainingItems = document.querySelectorAll('.cart-item');
          if (remainingItems.length === 0) {
              document.getElementById('cart-items-list').innerHTML = `
                  <div class="empty-cart-message">
                      <p id="cart-empty">Корзина пуста.</p>
                      <img id="cart-empty-img" src="img/icons/cart-empty.png" alt="Корзина пуста">
                  </div>
              `;
              const checkoutBtn = document.getElementById('checkout-btn');
              checkoutBtn.disabled = true;
              checkoutBtn.classList.add('disabled-btn');
              checkoutBtn.title = 'Добавьте товары в корзину, чтобы оформить заказ';
          }
      } else {
          alert('Ошибка при удалении товара');
      }
  })
  .catch(error => {
      console.error('Ошибка при удалении товара:', error);
  });
}

function updateTotalPrice() {
  const totalPriceElement = document.getElementById('total-price');
  let totalAmount = 0;

  const items = document.querySelectorAll('.cart-item');
  items.forEach(item => {
      const quantityInput = item.querySelector('input[type="number"]');
      const priceElement = item.querySelector('.cart-item-price');
      const priceText = priceElement.textContent.replace(' BYN', '').trim(); //число без валюты
      const price = parseFloat(priceText);
      const quantity = parseInt(quantityInput.value);
      
      if (!isNaN(price) && !isNaN(quantity)) {
          totalAmount += price * quantity;
      } else {
          console.warn("Некорректные данные для расчета суммы:", priceText, quantity);
      }
  });

  totalPriceElement.textContent = `Итого: ${totalAmount.toFixed(2)} BYN`;

}

function updateCartCount() {
  fetch('php/get-cart-count.php')
      .then(response => response.json())
      .then(data => {
          const cartCountElement = document.getElementById('cart-count');
          if (cartCountElement) {
              cartCountElement.textContent = data.count;
          } else {
              console.warn("Элемент #cart-count не найден на странице.");
          }
      })
      .catch(error => {
          console.error('Ошибка при получении количества товаров в корзине:', error);
          const cartCountElement = document.getElementById('cart-count');
          if (cartCountElement) {
              cartCountElement.textContent = '0'; 
          }
      });
}

function markCartItems() {
  fetch('php/get-cart.php')
      .then(response => response.json())
      .then(data => {
          if (data.success && data.items) {
              data.items.forEach(item => {
                  const button = document.querySelector(`button[onclick*="addToCart(${item.product_id}"]`);
                  if (button) {
                      button.textContent = 'Товар в корзине';
                      button.disabled = true;
                      button.classList.add('in-cart');
                      button.classList.remove('black-btn');
                  }
              });
          }
      })
      .catch(error => {
          console.error('Ошибка при получении корзины:', error);
      });
}