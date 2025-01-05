<?php
// order.php
include 'includes/db_connect.php';
include 'templates/header.php';
session_start();

// Kullanıcı giriş yapmış mı kontrol et
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if (empty($_SESSION['cart'])) {
        $error = "Sepetiniz boş.";
    } else {
        $user_id = $_SESSION['UserID'];
        $restaurant_id = $_SESSION['selected_restaurant'];

        // Kullanıcının adres ve ödeme yöntemlerini al
        // Bu örnekte varsayılan adres ve ödeme yöntemi kullanılıyor
        // Gerçek uygulamada kullanıcıdan seçilen adres ve ödeme yöntemini almalısınız
        $address_id = 1; // TODO: Kullanıcının adreslerini al
        $payment_method_id = 1; // TODO: Kullanıcının ödeme yöntemlerini al

        // İşlemi başlat
        $conn->begin_transaction();

        try {
            // Orders tablosuna ekle
            $sql_order = "INSERT INTO Orders (UserID, PaymentMethodID, OrderDate) VALUES ($user_id, $payment_method_id, NOW())";
            $conn->query($sql_order);
            $order_id = $conn->insert_id;

            // OrderItems tablosuna ekle
            foreach ($_SESSION['cart'] as $item_id => $quantity) {
                // Menü öğesinin fiyatını al
                $sql_price = "SELECT Price FROM MenuItems WHERE MenuItemID = $item_id";
                $result_price = $conn->query($sql_price);
                $item = $result_price->fetch_assoc();
                $price = $item['Price'];

                $sql_orderitem = "INSERT INTO OrderItems (OrderID, MenuItemID, Quantity, Price) VALUES ($order_id, $item_id, $quantity, $price)";
                $conn->query($sql_orderitem);

                // Stok kontrolü ve güncelleme
                $sql_stock = "UPDATE MenuItems SET Stock = Stock - $quantity WHERE MenuItemID = $item_id AND Stock >= $quantity";
                if ($conn->query($sql_stock) === FALSE) {
                    throw new Exception("Stok güncelleme hatası.");
                }
            }

            // İşlemi tamamla
            $conn->commit();

            // Sepeti temizle
            $_SESSION['cart'] = array();

            $success = "Siparişiniz başarıyla verildi. Sipariş Numaranız: " . $order_id;
        } catch (Exception $e) {
            // Hata durumunda işlemi geri al
            $conn->rollback();
            $error = "Sipariş verilirken hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<div class="container">
    <h2>Sipariş İşlemleri</h2>
    <?php
    if (isset($success)) {
        echo "<p class='success'>$success</p>";
    }
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    <a href="cart.php">Sepete Dön</a>
</div>

<?php include 'templates/footer.php'; ?>
