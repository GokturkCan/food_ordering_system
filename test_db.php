<?php
// test_db.php
include 'includes/db_connect.php';

// Bağlantıyı kontrol et
if ($conn->ping()) {
    echo "Veritabanına başarıyla bağlandınız!";
} else {
    echo "Veritabanına bağlantı başarısız: " . $conn->connect_error;
}

// Bağlantıyı kapat
$conn->close();
?>
