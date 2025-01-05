<?php
// order_details_manager.php
include 'includes/auth.php';
check_login('Restaurant Manager');
include 'includes/db_connect.php';
include 'templates/header.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
} else {
    echo "Sipariş seçilmedi.";
    exit();
}

// Siparişin restoranına ait olup olmadığını kontrol et
$user_id = $_SESSION['UserID'];
$sql_restaurant = "SELECT * FROM Restaurants WHERE UserID = $user_id";
$result_restaurant = $conn->query($sql_restaurant);
$restaurant = $result_restaurant->fetch_assoc();
$restaurant_id = $restaurant['RestaurantID'];

$sql_order = "SELECT * FROM Orders WHERE OrderID = $order_id AND RestaurantID = $restaurant_id";
$result_order = $conn->query($sql_order);
$order = $result_order->fetch_assoc();

if (!$order) {
    echo "Sipariş bulunamadı.";
    exit();
}

// Sipariş durumunu güncelleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $sql_update = "UPDATE Orders SET OrderStatus='$new_status' WHERE OrderID=$order_id";
    if ($conn->query($sql_update) === TRUE) {
        $message = "Sipariş durumu güncellendi.";
        $order['OrderStatus'] = $new_status;
    } else {
        $error = "Hata: " . $conn->error;
    }
}
?>

<div class="container">
    <h2>Sipariş Detayları</h2>
    <?php
    if (isset($message)) {
        echo "<p class='success'>$message</p>";
    }
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    <p>Sipariş ID: <?php echo intval($order['OrderID']); ?></p>
    <p>Müşteri ID: <?php echo intval($order['UserID']); ?></p>
    <p>Tarih: <?php echo htmlspecialchars($order['OrderDate']); ?></p>
    <p>Durum: <?php echo htmlspecialchars($order['OrderStatus']); ?></p>
    
    <h3>Ürünler:</h3>
    <table>
        <tr>
            <th>Ürün</th>
            <th>Adet</th>
            <th>Fiyat</th>
            <th>Toplam</th>
        </tr>
        <?php
        $sql_items = "SELECT OrderItems.*, MenuItems.Name as ItemName FROM OrderItems 
                      JOIN MenuItems ON OrderItems.MenuItemID = MenuItems.MenuItemID
                      WHERE OrderItems.OrderID = $order_id";
        $result_items = $conn->query($sql_items);
        $total = 0;

        while($item = $result_items->fetch_assoc()) {
            $subtotal = $item['Quantity'] * $item['Price'];
            $total += $subtotal;
            echo "<tr>";
            echo "<td>".htmlspecialchars($item['ItemName'])."</td>";
            echo "<td>".intval($item['Quantity'])."</td>";
            echo "<td>$".number_format($item['Price'], 2)."</td>";
            echo "<td>$".number_format($subtotal, 2)."</td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td colspan="3"><strong>Toplam</strong></td>
            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
    </table>

    <h3>Durumu Güncelle</h3>
    <form method="POST" action="order_details_manager.php?order_id=<?php echo intval($order_id); ?>">
        <label for="status">Yeni Durum:</label>
        <select name="status" required>
            <option value="Pending" <?php if($order['OrderStatus'] == 'Pending') echo 'selected'; ?>>Beklemede</option>
            <option value="Processing" <?php if($order['OrderStatus'] == 'Processing') echo 'selected'; ?>>İşleniyor</option>
            <option value="Completed" <?php if($order['OrderStatus'] == 'Completed') echo 'selected'; ?>>Tamamlandı</option>
            <option value="Cancelled" <?php if($order['OrderStatus'] == 'Cancelled') echo 'selected'; ?>>İptal Edildi</option>
        </select>
        <button type="submit" name="update_status">Güncelle</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>