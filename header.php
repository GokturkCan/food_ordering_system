<!-- templates/header.php -->
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Food Ordering System</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Ana Sayfa</a>
            <?php
            if (isset($_SESSION['Email'])) {
                echo "<a href='profile.php'>Profil</a>";
                if ($_SESSION['Role'] == 'Admin') {
                    echo "<a href='admin_panel.php'>Admin Paneli</a>";
                } elseif ($_SESSION['Role'] == 'Restaurant Manager') {
                    echo "<a href='restaurant_panel.php'>Restoran Yöneticisi Paneli</a>";
                }
                echo "<a href='logout.php'>Çıkış Yap</a>";
            } else {
                echo "<a href='login.php'>Giriş Yap</a>";
                echo "<a href='register.php'>Kaydol</a>";
            }
            ?>
        </nav>
    </header>
