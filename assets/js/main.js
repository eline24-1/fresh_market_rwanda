// Fresh Market Rwanda — main.js

document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    if (menuToggle && navbar) {
        menuToggle.addEventListener('click', function () {
            navbar.classList.toggle('open');
        });
    }

    // Admin sidebar toggle
    const adminToggle = document.querySelector('.admin-menu-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (adminToggle && sidebar) {
        adminToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    }

    // Payment option styling
    const paymentOptions = document.querySelectorAll('.payment-option');
    paymentOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio) {
            if (radio.checked) option.classList.add('selected');
            radio.addEventListener('change', function () {
                paymentOptions.forEach(o => o.classList.remove('selected'));
                if (radio.checked) option.classList.add('selected');
            });
        }
    });

    // Toggle Mobile Money number field
    const momoFields = document.getElementById('momo-fields');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    if (momoFields && paymentRadios.length) {
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                momoFields.style.display = (radio.value === 'mobile_money' && radio.checked) ? 'block' : 'none';
            });
        });
    }

    // Confirm delete actions in admin
    document.querySelectorAll('.confirm-delete').forEach(link => {
        link.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
