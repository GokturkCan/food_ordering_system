<?php
// manage_menu.php
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

// Menü öğesi ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_item'])) {
        $item_name = mysqli_real_escape_string($conn, trim($_POST['item_name']));
        $category_id = intval($_POST['category_id']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);

        $sql_add = "INSERT INTO MenuItems (Name, Price, Stock, RestaurantID, CategoryID) 
                    VALUES ('$item_name', $price, $stock, $restaurant_id, $category_id)";
        if ($conn->query($sql_add) === TRUE) {
            $message = "Menü öğesi eklendi.";
        } else {
            $error = "Hata: " . $conn->error;
        }
    }

    // Menü öğesi silme işlemi
    if (isset($_POST['delete'])) {
        $item_id = intval($_POST['item_id']);
        $sql_delete = "DELETE FROM MenuItems WHERE MenuItemID=$item_id AND RestaurantID=$restaurant_id";
        if ($conn->query($sql_delete) === TRUE) {
            $message = "Menü öğesi silindi.";
        } else {
            $error = "Hata: " . $conn->error;
        }
    }
}
?>

<div class="container">
    <h2>Menüyü Yönet</h2>
    <?php
    if (isset($message)) {
        echo "<p class='success'>$message</p>";
    }
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    
    <h3>Yeni Menü Öğesi Ekle</h3>
    <form method="POST" action="manage_menu.php">
        <label for="item_name">Ürün İsmi:</label>
        <input type="text" name="item_name" required>

        <label for="category_id">Kategori:</label>
        <select name="category_id" required>
            <?php
            $sql_categories = "SELECT * FROM Categories";
            $result_categories = $conn->query($sql_categories);
            while($category = $result_categories->fetch_assoc()) {
                echo "<option value='".intval($category['CategoryID'])."'>".htmlspecialchars($category['Name'])."</option>";
            }
            ?>
        </select>

        <label for="price">Fiyat:</label>
        <input type="number" name="price" step="0.01" required>

        <label for="stock">Stok:</label>
        <input type="number" name="stock" required>

        <button type="submit" name="add_item">Ekle</button>
    </form>

    <h3>Mevcut Menü Öğeleri</h3>
    <table>
        <tr>
            <th>Ürün ID</th>
            <th>İsim</th>
            <th>Kategori</th>
            <th>Fiyat</th>
            <th>Stok</th>
            <th>İşlemler</th>
        </tr>
        <?php
        $sql_menu = "SELECT MenuItems.*, Categories.Name as CategoryName FROM MenuItems 
                    JOIN Categories ON MenuItems.CategoryID = Categories.CategoryID
                    WHERE MenuItems.RestaurantID = $restaurant_id";
        $result_menu = $conn->query($sql_menu);

        if ($result_menu->num_rows > 0) {
            while($item = $result_menu->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".intval($item['MenuItemID'])."</td>";
                echo "<td>".htmlspecialchars($item['Name'])."</td>";
                echo "<td>".htmlspecialchars($item['CategoryName'])."</td>";
                echo "<td>$".number_format($item['Price'], 2)."</td>";
                echo "<td>".intval($item['Stock'])."</td>";
                echo "<td>
                        <form method='POST' action='manage_menu.php' style='display:inline;'>
                            <input type='hidden' name='item_id' value='".intval($item['MenuItemID'])."'>
                            <button type='submit' name='delete' onclick=\"return confirm('Silmek istediğinize emin misiniz?')\">Sil</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Hiç menü öğesi bulunamadı.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
