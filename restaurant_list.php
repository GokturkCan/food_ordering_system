<?php
// restaurant_list.php
include 'includes/db_connect.php';
include 'templates/header.php';

// Sayfalama Ayarları
$limit = 10; // Sayfa başına gösterilecek restoran sayısı
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Toplam restoran sayısını al
$sql_total = "SELECT COUNT(*) as total FROM Restaurants";
$result_total = $conn->query($sql_total);
$total = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

// Restoranları al
$sql = "SELECT * FROM Restaurants LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<div class="container">
    <h2>Mevcut Restoranlar</h2>
    <ul class="restaurant-list">
        <?php
        if ($result->num_rows > 0) {
            while($restaurant = $result->fetch_assoc()) {
                echo "<li><a href='menu.php?restaurant_id=".intval($restaurant['RestaurantID'])."'>".htmlspecialchars($restaurant['Name'])."</a></li>";
            }
        } else {
            echo "<li>Hiç restoran mevcut değil.</li>";
        }
        ?>
    </ul>

    <!-- Sayfalama Linkleri -->
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='restaurant_list.php?page=$i'>$i</a> ";
            }
        }
        ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
