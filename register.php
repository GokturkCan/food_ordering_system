<?php
// register.php
include 'includes/db_connect.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Form verilerini al ve temizle
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // E-posta var mı kontrol et
    $check = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        $error = "Bu e-posta zaten kullanılıyor.";
    } else {
        // Kullanıcıyı ekle
        $sql = "INSERT INTO Users (Name, Email, Password, Role) VALUES ('$name', '$email', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $success = "Kayıt başarılı. <a href='login.php'>Giriş yapın</a>";
        } else {
            $error = "Hata: " . $conn->error;
        }
    }
}
?>

<div class="container">
    <h2>Kaydol</h2>
    <?php
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    if (isset($success)) {
        echo "<p class='success'>$success</p>";
    }
    ?>
    <form action="register.php" method="POST">
        <label for="name">İsim:</label>
        <input type="text" name="name" required>

        <label for="email">E-posta:</label>
        <input type="email" name="email" required>

        <label for="password">Şifre:</label>
        <input type="password" name="password" required>

        <label for="role">Rol:</label>
        <select name="role" required>
            <option value="Customer">Müşteri</option>
            <option value="Restaurant Manager">Restoran Yöneticisi</option>
        </select>

        <button type="submit" name="register">Kaydol</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
