<?php
// cart.php
include 'includes/db_connect.php';
include 'templates/header.php';
session_start();

// Sepeti başlat
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Sepete ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $item_id = intval($_POST['item_id']);
        $quantity = intval($_POST['quantity']);

        if ($quantity > 0) {
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id] += $quantity;
            } else {
                $_SESSION['cart'][$item_id] = $quantity;
            }
            $message = "Ürün sepete eklendi.";
        } else {
            $error = "Geçersiz miktar.";
        }
    }

    // Sepetten çıkarma işlemi
    if (isset($_POST['remove'])) {
        $item_id = intval($_POST['item_id']);
        unset($_SESSION['cart'][$item_id]);
        $message = "Ürün sepetten kaldırıldı.";
    }

    // Sepeti temizleme işlemi
    if (isset($_POST['clear'])) {
        $_SESSION['cart'] = array();
        $message = "Sepet temizlendi.";
    }
}

// Sepet öğelerini al
$cart_items = array();
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT MenuItems.*, Categories.Name as CategoryName FROM MenuItems 
            JOIN Categories ON MenuItems.CategoryID = Categories.CategoryID
            WHERE MenuItems.MenuItemID IN ($ids)";
    $result = $conn->query($sql);

    while($item = $result->fetch_assoc()) {
        $item['quantity'] = $_SESSION['cart'][$item['MenuItemID']];
        $item['subtotal'] = $item['Price'] * $item['quantity'];
        $total += $item['subtotal'];
        $cart_items[] = $item;
    }
}
?>

<div class="container">
    <h2>Sepetiniz</h2>
    <?php
    if (isset($message)) {
        echo "<p class='success'>$message</p>";
    }
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    <?php if (!empty($cart_items)): ?>
        <table>
            <tr>
                <th>Ürün</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Adet</th>
                <th>Toplam</th>
                <th>İşlem</th>
            </tr>
            <?php foreach($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['Name']); ?></td>
                    <td><?php echo htmlspecialchars($item['CategoryName']); ?></td>
                    <td>$<?php echo number_format($item['Price'], 2); ?></td>
                    <td><?php echo intval($item['quantity']); ?></td>
                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    <td>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="item_id" value="<?php echo intval($item['MenuItemID']); ?>">
                            <button type="submit" name="remove">Kaldır</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Toplam</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                <td></td>
            </tr>
        </table>
        <form method="POST" action="order.php">
            <button type="submit" name="place_order">Sipariş Ver</button>
        </form>
        <form method="POST" action="cart.php">
            <button type="submit" name="clear">Sepeti Temizle</button>
        </form>
    <?php else: ?>
        <p>Sepetiniz boş.</p>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
