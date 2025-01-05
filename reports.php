<?php
// reports.php
include 'includes/auth.php';
check_login('Admin');
include 'includes/db_connect.php';
include 'templates/header.php';
?>

<div class="container">
    <h2>Raporlar</h2>
    <h3>Günlük Sipariş Raporu</h3>
    <table>
        <tr>
            <th>Tarih</th>
            <th>Sipariş Sayısı</th>
            <th>Toplam Gelir</th>
        </tr>
        <?php
        $sql = "SELECT DATE(OrderDate) as order_date, COUNT(*) as total_orders, 
                       SUM(OrderItems.Quantity * OrderItems.Price) as revenue
                FROM Orders
                JOIN OrderItems ON Orders.OrderID = OrderItems.OrderID
                GROUP BY DATE(OrderDate)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($report = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($report['order_date'])."</td>";
                echo "<td>".intval($report['total_orders'])."</td>";
                echo "<td>$".number_format($report['revenue'], 2)."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Veri mevcut değil.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
