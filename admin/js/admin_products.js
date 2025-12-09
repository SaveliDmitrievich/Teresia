document.addEventListener('DOMContentLoaded', function() {
    const productsTableBody = document.getElementById('productsTableBody');
    const addProductBtn = document.getElementById('addProductBtn');
    const productModal = document.getElementById('productModal');
    const closeButton = productModal.querySelector('.close-button');
    const productForm = document.getElementById('productForm');
    const modalTitle = document.getElementById('modalTitle');
    const productIdInput = document.getElementById('productId');
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    let currentProductIdToDelete = null;

    const productImage1Input = document.getElementById('productImage1');
    const productImage2Input = document.getElementById('productImage2');

    const currentImage1 = document.getElementById('currentImage1');
    const currentImage2 = document.getElementById('currentImage2');
    const currentImage1PathInput = document.getElementById('currentImage1Path');
    const currentImage2PathInput = document.getElementById('currentImage2Path');

    const productMainCategorySelect = document.getElementById('productMainCategory');
    const productSubcategorySelect = document.getElementById('productSubcategory');

    let allSubcategoryOptions = []; 
    if (productSubcategorySelect) { 
        allSubcategoryOptions = Array.from(productSubcategorySelect.options);
    } else {
        console.error("Элемент с ID 'productSubcategory' не найден. Функционал подкатегорий может быть ограничен.");
    }


    function loadProducts() {
        fetch('api/shop_products.php')
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    productsTableBody.innerHTML = '';
                    if (data.products && data.products.length > 0) {
                        data.products.forEach(product => {
                            const row = productsTableBody.insertRow();
                            row.dataset.productId = product.id_product;
                            const imageUrl = product.image_1 ? `../${product.image_1}` : '../img/placeholder.png';
                            row.innerHTML = `
                                <td>${product.id_product}</td>
                                <td><img src="${imageUrl}" alt="${product.name}" class="product-thumb"></td>
                                <td>${htmlspecialchars(product.name)}</td>
                                <td>${parseFloat(product.price).toFixed(2)} BYN</td>
                                <td>${htmlspecialchars(product.main_category_name || 'Без категории')}</td>
                                <td>${htmlspecialchars(product.subcategory_name || '')}</td>
                                <td>
                                    <button class="admin-button edit-btn" data-id="${product.id_product}"><i class='bx bxs-edit'></i></button>
                                    <button class="admin-button delete-btn" data-id="${product.id_product}"><i class='bx bxs-trash'></i></button>
                                </td>
                            `;
                        });
                        attachEventListeners();
                    } else {
                        productsTableBody.innerHTML = '<tr><td colspan="6">Нет товаров для отображения.</td></tr>'; 
                    }
                } else {
                    alert('Ошибка при загрузке товаров: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка сети при загрузке товаров или невалидный ответ сервера.');
            });
    }

    function htmlspecialchars(str) {
        if (typeof str !== 'string') {
            return ''; 
        }
        let div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function attachEventListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.onclick = (e) => {
                const productId = e.currentTarget.dataset.id;
                editProduct(productId);
            };
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.onclick = (e) => {
                currentProductIdToDelete = e.currentTarget.dataset.id;
                deleteConfirmModal.style.display = 'flex';
            };
        });
    }

    if (productMainCategorySelect) { 
        productMainCategorySelect.addEventListener('change', function() {
            const selectedMainCategoryId = this.value;
            filterSubcategories(selectedMainCategoryId);
        });
    }


    function filterSubcategories(mainCategoryId) {
        if (!productSubcategorySelect || allSubcategoryOptions.length === 0) {
            console.warn("Элемент подкатегорий или его опции не найдены. Фильтрация невозможна.");
            return;
        }
        
        allSubcategoryOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });

        allSubcategoryOptions.forEach(option => {
            if (option.dataset.parentId === mainCategoryId) {
                option.style.display = '';
            }
        });

        if (productSubcategorySelect.options[productSubcategorySelect.selectedIndex] &&
            productSubcategorySelect.options[productSubcategorySelect.selectedIndex].dataset.parentId !== mainCategoryId) {
            productSubcategorySelect.value = "";
        }
    }


    addProductBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Добавить новый товар';
        productForm.reset();
        productIdInput.value = '';
        currentImage1.style.display = 'none';
        currentImage2.style.display = 'none';
        currentImage1PathInput.value = '';
        currentImage2PathInput.value = '';
        productImage1Input.value = '';
        productImage2Input.value = '';

        if (productMainCategorySelect) productMainCategorySelect.value = "";
        if (productSubcategorySelect) productSubcategorySelect.value = "";
        filterSubcategories(""); 

        productModal.style.display = 'flex';
    });

    closeButton.addEventListener('click', function() {
        productModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == productModal) {
            productModal.style.display = 'none';
        }
        if (event.target == deleteConfirmModal) {
            deleteConfirmModal.style.display = 'none';
        }
    });

    function editProduct(id) {
        modalTitle.textContent = 'Редактировать товар';
        productForm.reset();
        productIdInput.value = id;

        currentImage1.style.display = 'none';
        currentImage2.style.display = 'none';

        currentImage1PathInput.value = '';
        currentImage2PathInput.value = '';

        productImage1Input.value = '';
        productImage2Input.value = '';

        fetch('api/shop_products.php')
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.products) {
                    const product = data.products.find(p => p.id_product == id);

                    if (product) {
                        document.getElementById('productName').value = product.name;
                        document.getElementById('productDescription').value = product.description;
                        document.getElementById('productPrice').value = parseFloat(product.price);

                        if (productMainCategorySelect) {
                            productMainCategorySelect.value = product.main_category_id || "";
                        }
                        filterSubcategories(product.main_category_id || "");


                        if (productSubcategorySelect) {
                            productSubcategorySelect.value = product.subcategory_id || "";
                        }

                        if (product.image_1) {
                            currentImage1.src = `../${product.image_1}`;
                            currentImage1.style.display = 'block';
                            currentImage1PathInput.value = product.image_1;
                        }
                        if (product.image_2) {
                            currentImage2.src = `../${product.image_2}`;
                            currentImage2.style.display = 'block';
                            currentImage2PathInput.value = product.image_2;
                        }


                        productModal.style.display = 'flex';
                    } else {
                        alert('Товар с ID ' + id + ' не найден.');
                    }
                } else {
                    alert('Ошибка при получении данных товаров: ' + (data.message || ''));
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка сети при получении данных товара или невалидный ответ сервера.');
            });
    }

    productForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('api/shop_products.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                productModal.style.display = 'none';
                loadProducts();
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Ошибка сети при сохранении товара или невалидный ответ сервера.');
        });
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (currentProductIdToDelete) {
            fetch('api/shop_products.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${currentProductIdToDelete}`
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    deleteConfirmModal.style.display = 'none';
                    loadProducts();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка сети при удалении товара или невалидный ответ сервера.');
            });
        }
    });

    cancelDeleteBtn.addEventListener('click', function() {
        deleteConfirmModal.style.display = 'none';
        currentProductIdToDelete = null;
    });

    loadProducts();
});