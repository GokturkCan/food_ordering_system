<?php
// includes/db_connect.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root"; // AMPPS varsayılan kullanıcı adı
$password = "Deneme1234";     // AMPPS varsayılan şifresi (boş olabilir)
$dbname = "food_ordering"; // Oluşturduğunuz veritabanı adı

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
