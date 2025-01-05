<?php
// track_orders.php
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
?>

<div class="container">
    <h2>Siparişleri Takip Et</h2>
    <table>
        <tr>
            <th>Sipariş ID</th>
            <th>Müşteri</th>
            <th>Tarih</th>
            <th>Durum</th>
            <th>Toplam</th>
            <th>İşlemler</th>
        </tr>
        <?php
        $sql_orders = "SELECT Orders.*, Users.Name as CustomerName FROM Orders 
                       JOIN Users ON Orders.UserID = Users.UserID
                       WHERE Orders.RestaurantID = $restaurant_id
                       ORDER BY Orders.OrderDate DESC";
        $result_orders = $conn->query($sql_orders);

        if ($result_orders->num_rows > 0) {
            while($order = $result_orders->fetch_assoc()) {
                // Siparişin toplamını hesapla
                $order_id = $order['OrderID'];
                $sql_total = "SELECT SUM(Quantity * Price) as Total FROM OrderItems WHERE OrderID = $order_id";
                $result_total = $conn->query($sql_total);
                $total = $result_total->fetch_assoc()['Total'];

                echo "<tr>";
                echo "<td>".intval($order['OrderID'])."</td>";
                echo "<td>".htmlspecialchars($order['CustomerName'])."</td>";
                echo "<td>".htmlspecialchars($order['OrderDate'])."</td>";
                echo "<td>".htmlspecialchars($order['OrderStatus'])."</td>";
                echo "<td>$".number_format($total, 2)."</td>";
                echo "<td><a href='order_details_manager.php?order_id=".intval($order['OrderID'])."'>Güncelle</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Hiç sipariş bulunamadı.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
