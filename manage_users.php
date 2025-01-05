<?php
// manage_users.php
include 'includes/auth.php';
check_login('Admin');
include 'includes/db_connect.php';
include 'templates/header.php';

// Kullanıcı silme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $user_id = intval($_POST['user_id']);
        $sql = "DELETE FROM Users WHERE UserID=$user_id";
        $conn->query($sql);
        $message = "Kullanıcı silindi.";
    }
}
?>

<div class="container">
    <h2>Kullanıcıları Yönet</h2>
    <?php
    if (isset($message)) {
        echo "<p class='success'>$message</p>";
    }
    ?>
    <table>
        <tr>
            <th>Kullanıcı ID</th>
            <th>İsim</th>
            <th>E-posta</th>
            <th>Rol</th>
            <th>İşlemler</th>
        </tr>
        <?php
        $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($user = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".intval($user['UserID'])."</td>";
                echo "<td>".htmlspecialchars($user['Name'])."</td>";
                echo "<td>".htmlspecialchars($user['Email'])."</td>";
                echo "<td>".htmlspecialchars($user['Role'])."</td>";
                echo "<td>";
                echo "<form method='POST' action='manage_users.php' style='display:inline;'>
                        <input type='hidden' name='user_id' value='".intval($user['UserID'])."'>
                        <button type='submit' name='delete' onclick=\"return confirm('Silmek istediğinize emin misiniz?')\">Sil</button>
                      </form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Hiç kullanıcı bulunamadı.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
