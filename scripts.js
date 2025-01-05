// js/scripts.js

document.addEventListener('DOMContentLoaded', () => {
    /**
     * Form Doğrulama Fonksiyonları
     */

    // 1. Kayıt Formu Doğrulaması
    const registerForm = document.querySelector('form[action="register.php"]');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const password = registerForm.querySelector('input[name="password"]').value.trim();
            const name = registerForm.querySelector('input[name="name"]').value.trim();
            const email = registerForm.querySelector('input[name="email"]').value.trim();
            const role = registerForm.querySelector('select[name="role"]').value;

            // İsim alanı boş olamaz
            if (name === '') {
                e.preventDefault();
                alert('İsim alanı boş bırakılamaz.');
                return;
            }

            // Geçerli e-posta formatı kontrolü
            if (!validateEmail(email)) {
                e.preventDefault();
                alert('Lütfen geçerli bir e-posta adresi girin.');
                return;
            }

            // Şifre en az 6 karakter olmalıdır
            if (password.length < 6) {
                e.preventDefault();
                alert('Şifre en az 6 karakter olmalıdır.');
                return;
            }

            // Rol seçimi kontrolü
            const validRoles = ['Admin', 'Customer', 'Restaurant Manager'];
            if (!validRoles.includes(role)) {
                e.preventDefault();
                alert('Geçersiz bir rol seçtiniz.');
                return;
            }
        });
    }

    // 2. Giriş Formu Doğrulaması
    const loginForm = document.querySelector('form[action="login.php"]');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const email = loginForm.querySelector('input[name="email"]').value.trim();
            const password = loginForm.querySelector('input[name="password"]').value.trim();

            // Geçerli e-posta formatı kontrolü
            if (!validateEmail(email)) {
                e.preventDefault();
                alert('Lütfen geçerli bir e-posta adresi girin.');
                return;
            }

            // Şifre alanı boş olamaz
            if (password === '') {
                e.preventDefault();
                alert('Şifre alanı boş bırakılamaz.');
                return;
            }
        });
    }

    // 3. Sepete Ekleme Formu Doğrulaması
    const addToCartForms = document.querySelectorAll('form[action="cart.php"]');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const quantityInput = form.querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value, 10);

            // Miktar en az 1 olmalıdır
            if (isNaN(quantity) || quantity < 1) {
                e.preventDefault();
                alert('Miktar en az 1 olmalıdır.');
                quantityInput.focus();
                return;
            }

            // İsteğe bağlı: Maksimum stok kontrolü (eğer PHP'de stok doğrulaması yapılmıyorsa)
            const maxStock = parseInt(quantityInput.getAttribute('max'), 10) || Infinity;
            if (quantity > maxStock) {
                e.preventDefault();
                alert(`Seçilen miktar stok miktarını aşıyor. Maksimum: ${maxStock}`);
                quantityInput.focus();
                return;
            }
        });
    });

    // 4. Menü Yönetimi Formu Doğrulaması (manage_menu.php)
    const manageMenuForm = document.querySelector('form[action="manage_menu.php"]');
    if (manageMenuForm) {
        manageMenuForm.addEventListener('submit', (e) => {
            const itemName = manageMenuForm.querySelector('input[name="item_name"]').value.trim();
            const categoryId = manageMenuForm.querySelector('select[name="category_id"]').value;
            const price = parseFloat(manageMenuForm.querySelector('input[name="price"]').value);
            const stock = parseInt(manageMenuForm.querySelector('input[name="stock"]').value, 10);

            // Ürün İsmi boş olamaz
            if (itemName === '') {
                e.preventDefault();
                alert('Ürün ismi boş bırakılamaz.');
                return;
            }

            // Geçerli kategori seçimi
            if (isNaN(categoryId) || categoryId < 1) {
                e.preventDefault();
                alert('Geçerli bir kategori seçin.');
                return;
            }

            // Fiyat pozitif olmalıdır
            if (isNaN(price) || price <= 0) {
                e.preventDefault();
                alert('Fiyat pozitif bir değer olmalıdır.');
                return;
            }

            // Stok negatif olamaz
            if (isNaN(stock) || stock < 0) {
                e.preventDefault();
                alert('Stok miktarı negatif olamaz.');
                return;
            }
        });
    }

    // 5. Restoran Bilgilerini Güncelleme Formu Doğrulaması (update_restaurant.php)
    const updateRestaurantForm = document.querySelector('form[action="update_restaurant.php"]');
    if (updateRestaurantForm) {
        updateRestaurantForm.addEventListener('submit', (e) => {
            const restaurantName = updateRestaurantForm.querySelector('input[name="name"]').value.trim();

            // Restoran ismi boş olamaz
            if (restaurantName === '') {
                e.preventDefault();
                alert('Restoran ismi boş bırakılamaz.');
                return;
            }
        });
    }

    /**
     * Yardımcı Fonksiyonlar
     */

    /**
     * E-posta formatını doğrulayan fonksiyon
     * @param {string} email - Doğrulanacak e-posta adresi
     * @returns {boolean} - Geçerli ise true, değilse false
     */
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email.toLowerCase());
    }

    /**
     * (İsteğe Bağlı) Diğer form doğrulamaları veya dinamik işlemler eklenebilir.
     * Mevcut PHP kodlarına ekleme yapmadığınız sürece ekstra işlevsellik eklemeye gerek yoktur.
     */
});
