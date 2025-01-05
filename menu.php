<?php
// menu.php
include 'includes/db_connect.php';
include 'templates/header.php';
session_start();

// Restoran ID'sini URL'den al
if (isset($_GET['restaurant_id'])) {
    $restaurant_id = intval($_GET['restaurant_id']);
    $_SESSION['selected_restaurant'] = $restaurant_id;
} elseif (isset($_SESSION['selected_restaurant'])) {
    $restaurant_id = $_SESSION['selected_restaurant'];
} else {
    echo "Seçili restoran yok.";
    exit();
}

// Restoran bilgilerini al
$sql_restaurant = "SELECT * FROM Restaurants WHERE RestaurantID = $restaurant_id";
$result_restaurant = $conn->query($sql_restaurant);
$restaurant = $result_restaurant->fetch_assoc();

if (!$restaurant) {
    echo "Restoran bulunamadı.";
    exit();
}

// Menü öğelerini al
$sql_menu = "SELECT MenuItems.*, Categories.Name as CategoryName FROM MenuItems 
            JOIN Categories ON MenuItems.CategoryID = Categories.CategoryID
            WHERE MenuItems.RestaurantID = $restaurant_id AND MenuItems.Stock > 0";
$result_menu = $conn->query($sql_menu);

// Son Görülen Restoranları Güncelle
if (!isset($_SESSION['recent_restaurants'])) {
    $_SESSION['recent_restaurants'] = array();
}

if (!in_array($restaurant_id, $_SESSION['recent_restaurants'])) {
    array_unshift($_SESSION['recent_restaurants'], $restaurant_id);
    if (count($_SESSION['recent_restaurants']) > 5) {
        array_pop($_SESSION['recent_restaurants']);
    }
}
?>

<div class="container">
    <h2><?php echo htmlspecialchars($restaurant['Name']); ?> Menüsü</h2>
    <ul class="menu-list">
        <?php
        if ($result_menu->num_rows > 0) {
            while($item = $result_menu->fetch_assoc()) {
                echo "<li>";
                echo "<h3>".htmlspecialchars($item['Name'])."</h3>";
                echo "<p>Kategori: ".htmlspecialchars($item['CategoryName'])."</p>";
                echo "<p>Fiyat: $".number_format($item['Price'], 2)."</p>";
                echo "<form method='POST' action='cart.php'>";
                echo "<input type='hidden' name='item_id' value='".intval($item['MenuItemID'])."'>";
                echo "<input type='number' name='quantity' value='1' min='1'>";
                echo "<button type='submit' name='add_to_cart'>Sepete Ekle</button>";
                echo "</form>";
                echo "</li>";
            }
        } else {
            echo "<li>Hiç menü öğesi mevcut değil.</li>";
        }
        ?>
    </ul>

    <!-- Son Görülen Restoranlar -->
    <div class="recently-viewed">
        <h3>Son Görülenler</h3>
        <ul>
            <?php
            foreach($_SESSION['recent_restaurants'] as $recent_id) {
                // Restoran ismini al
                $sql = "SELECT Name FROM Restaurants WHERE RestaurantID = $recent_id";
                $result = $conn->query($sql);
                $recent = $result->fetch_assoc();
                echo "<li><a href='menu.php?restaurant_id=".intval($recent_id)."'>".htmlspecialchars($recent['Name'])."</a></li>";
            }
            ?>
        </ul>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
