<?php
// login.php
include 'includes/db_connect.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Form verilerini al ve temizle
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Kullanıcıyı bul
    $sql = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Şifreyi doğrula
        if (password_verify($password, $user['Password'])) {
            // Oturum değişkenlerini ayarla
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['Name'] = $user['Name'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['Role'] = $user['Role'];

            // Role göre yönlendir
            if ($user['Role'] == 'Admin') {
                header("Location: admin_panel.php");
            } elseif ($user['Role'] == 'Restaurant Manager') {
                header("Location: restaurant_panel.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Geçersiz şifre.";
        }
    } else {
        $error = "Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
    }
}
?>

<div class="container">
    <h2>Giriş Yap</h2>
    <?php
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    <form action="login.php" method="POST">
        <label for="email">E-posta:</label>
        <input type="email" name="email" required>

        <label for="password">Şifre:</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Giriş Yap</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
