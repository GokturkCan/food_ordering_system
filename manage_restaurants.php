<?php
// manage_restaurants.php
include 'includes/auth.php';
check_login('Admin');
include 'includes/db_connect.php';
include 'templates/header.php';

// Restoran onaylama işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $restaurant_id = intval($_POST['restaurant_id']);
        $sql = "UPDATE Restaurants SET Status='Approved' WHERE RestaurantID=$restaurant_id";
        $conn->query($sql);
        $message = "Restoran onaylandı.";
    }

    if (isset($_POST['delete'])) {
        $restaurant_id = intval($_POST['restaurant_id']);
        $sql = "DELETE FROM Restaurants WHERE RestaurantID=$restaurant_id";
        $conn->query($sql);
        $message = "Restoran silindi.";
    }
}
?>

<div class="container">
    <h2>Restoranları Yönet</h2>
    <?php
    if (isset($message)) {
        echo "<p class='success'>$message</p>";
    }
    ?>
    <table>
        <tr>
            <th>Restoran ID</th>
            <th>İsim</th>
            <th>Yönetici</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
        <?php
        $sql = "SELECT Restaurants.*, Users.Name as ManagerName FROM Restaurants 
                JOIN Users ON Restaurants.UserID = Users.UserID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($restaurant = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".intval($restaurant['RestaurantID'])."</td>";
                echo "<td>".htmlspecialchars($restaurant['Name'])."</td>";
                echo "<td>".htmlspecialchars($restaurant['ManagerName'])."</td>";
                echo "<td>".htmlspecialchars($restaurant['Status'])."</td>";
                echo "<td>";
                if ($restaurant['Status'] != 'Approved') {
                    echo "<form method='POST' action='manage_restaurants.php' style='display:inline;'>
                            <input type='hidden' name='restaurant_id' value='".intval($restaurant['RestaurantID'])."'>
                            <button type='submit' name='approve'>Onayla</button>
                          </form> ";
                }
                echo "<form method='POST' action='manage_restaurants.php' style='display:inline;'>
                        <input type='hidden' name='restaurant_id' value='".intval($restaurant['RestaurantID'])."'>
                        <button type='submit' name='delete' onclick=\"return confirm('Silmek istediğinize emin misiniz?')\">Sil</button>
                      </form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Hiç restoran bulunamadı.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
