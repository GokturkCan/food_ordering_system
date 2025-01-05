<?php
// admin_panel.php
include 'includes/auth.php';
check_login('Admin');
include 'includes/db_connect.php';
include 'templates/header.php';
?>

<div class="container">
    <h2>Admin Paneli</h2>
    <ul class="admin-options">
        <li><a href="manage_restaurants.php">Restoranları Yönet</a></li>
        <li><a href="manage_users.php">Kullanıcıları Yönet</a></li>
        <li><a href="reports.php">Raporları Görüntüle</a></li>
    </ul>
</div>

<?php include 'templates/footer.php'; ?>
