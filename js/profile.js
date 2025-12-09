const menuItems = document.querySelectorAll('.menu-ul li');
const tabs = document.querySelectorAll('.box.tab');
const logoutMenuItem = document.querySelector('li[data-tab="logout"]');
const logoutModal = document.getElementById('logoutModal');
const confirmLogoutButton = document.getElementById('confirmLogout');
const cancelLogoutButton = document.getElementById('cancelLogout');

menuItems.forEach(item => {
    item.addEventListener('click', () => {
        if (item === logoutMenuItem) {
            logoutModal.style.display = 'flex';
            return; 
        }

        menuItems.forEach(i => i.classList.remove('active'));
        tabs.forEach(tab => tab.classList.remove('active-tab'));
        item.classList.add('active');

        const targetTabId = item.getAttribute('data-tab');
        const targetTabContent = document.getElementById(targetTabId);
        if (targetTabContent) {
            targetTabContent.classList.add('active-tab');
        }

        if (targetTabId === 'orders') {
            if (typeof loadOrderHistory === 'function') {
                loadOrderHistory();
            } else {
                console.error("Функция loadOrderHistory не найдена. ");
            }
        }
    });
});

cancelLogoutButton.addEventListener('click', function() {
    logoutModal.style.display = 'none';
    document.querySelector('.aside-menu ul li[data-tab="account"]').click();
});

confirmLogoutButton.addEventListener('click', function() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    const logoutInput = document.createElement('input');
    logoutInput.type = 'hidden';
    logoutInput.name = 'logout';
    form.appendChild(logoutInput);
    document.body.appendChild(form);
    form.submit();
});

window.toggleEdit = function(textId, inputId) {
    const textElement = document.getElementById(textId);
    const inputElement = document.getElementById(inputId);

    if (textElement.style.display !== 'none') {
        textElement.style.display = 'none';
        inputElement.style.display = 'inline-block';
        inputElement.focus();
    } else {
        const newValue = inputElement.value.trim();
        textElement.textContent = newValue !== '' ? newValue : 'Не указано';
        textElement.style.display = 'inline-block';
        inputElement.style.display = 'none';
        updateFieldOnServer(inputId, newValue);
    }
};

function updateFieldOnServer(fieldId, value) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = ''; 

    let fieldName = fieldId.replace(/^(input)/, '').replace(/(Text)$/, '').toLowerCase();
    if (fieldName === 'firstname') {
        fieldName = 'first_name';
    }
    if (fieldName === 'lastname') {
        fieldName = 'last_name';
    }

    const inputHidden = document.createElement('input');
    inputHidden.type = 'hidden';
    inputHidden.name = fieldName;
    inputHidden.value = value;
    form.appendChild(inputHidden);
    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const ordersTab = document.getElementById('orders');
    const ordersMenuItem = document.querySelector('.aside-menu ul li[data-tab="orders"]');

    if (ordersTab && ordersTab.classList.contains('active-tab')) {
        if (typeof loadOrderHistory === 'function') {
            loadOrderHistory();
        }
    }
});