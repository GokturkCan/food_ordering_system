<?php
// profile.php
include 'includes/db_connect.php';
include 'includes/auth.php';
check_login();
include 'templates/header.php';
?>

<div class="container">
    <h2>Profiliniz</h2>
    <p>İsim: <?php echo htmlspecialchars($_SESSION['Name']); ?></p>
    <p>E-posta: <?php echo htmlspecialchars($_SESSION['Email']); ?></p>
    <p>Rol: <?php echo htmlspecialchars($_SESSION['Role']); ?></p>
    
    <h3>Sipariş Geçmişi</h3>
    <table>
        <tr>
            <th>Sipariş ID</th>
            <th>Tarih</th>
            <th>Toplam</th>
            <th>Durum</th>
            <th>Detaylar</th>
        </tr>
        <?php
        $user_id = $_SESSION['UserID'];
        $sql_orders = "SELECT * FROM Orders WHERE UserID = $user_id ORDER BY OrderDate DESC";
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
                echo "<td>".htmlspecialchars($order['OrderDate'])."</td>";
                echo "<td>$".number_format($total, 2)."</td>";
                echo "<td>".htmlspecialchars($order['OrderStatus'])."</td>";
                echo "<td><a href='order_details.php?order_id=".intval($order['OrderID'])."'>Görüntüle</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Hiç siparişiniz bulunmuyor.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
