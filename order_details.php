<?php
// order_details.php
include 'includes/db_connect.php';
include 'includes/auth.php';
check_login();
include 'templates/header.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
} else {
    echo "Sipariş seçilmedi.";
    exit();
}

// Siparişin kullanıcıya ait olup olmadığını kontrol et
$user_id = $_SESSION['UserID'];
$sql_order = "SELECT * FROM Orders WHERE OrderID = $order_id AND UserID = $user_id";
$result_order = $conn->query($sql_order);
$order = $result_order->fetch_assoc();

if (!$order) {
    echo "Sipariş bulunamadı.";
    exit();
}
?>

<div class="container">
    <h2>Sipariş Detayları</h2>
    <p>Sipariş ID: <?php echo intval($order['OrderID']); ?></p>
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
</div>

<?php include 'templates/footer.php'; ?>
