<?php
// restaurant_panel.php
include 'includes/auth.php';
check_login('Restaurant Manager');
include 'includes/db_connect.php';
include 'templates/header.php';
?>

<div class="container">
    <h2>Restoran Yöneticisi Paneli</h2>
    <ul class="manager-options">
        <li><a href="manage_menu.php">Menüyü Yönet</a></li>
        <li><a href="track_orders.php">Siparişleri Takip Et</a></li>
        <li><a href="update_restaurant.php">Restoran Bilgilerini Güncelle</a></li>
    </ul>
</div>

<?php include 'templates/footer.php'; ?>
