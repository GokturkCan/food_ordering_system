<?php
// update_restaurant.php
include 'includes/auth.php';
check_login('Restaurant Manager');
include 'includes/db_connect.php';
include 'templates/header.php';

// Yöneticiye ait restoranı bul
$user_id = $_SESSION['UserID'];
$sql_restaurant = "SELECT * FROM Restaurants WHERE UserID = $user_id";
$result_restaurant = $conn->query($sql_restaurant);
$restaurant = $result_restaurant->fetch_assoc();
$restaurant_id = $restaurant['RestaurantID'];

// Restoran bilgilerini güncelleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $sql_update = "UPDATE Restaurants SET Name='$name' WHERE RestaurantID=$restaurant_id";
    if ($conn->query($sql_update) === TRUE) {
        $message = "Restoran bilgileri güncellendi.";
        $restaurant['Name'] = $name;
    } else {
        $error = "Hata: " . $conn->error;
    }
}
?>

<div class="container">
    <h2>Restoran Bilgilerini Güncelle</h2>
    <?php
    if (isset($message)) {
        echo "<p class='success'>$message</p>";
    }
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    <form method="POST" action="update_restaurant.php">
        <label for="name">Restoran İsmi:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($restaurant['Name']); ?>" required>
        
        <button type="submit" name="update">Güncelle</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
