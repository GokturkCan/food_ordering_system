<?php
// includes/auth.php
session_start();

function check_login($role = null) {
    if (!isset($_SESSION['UserID'])) {
        header("Location: login.php");
        exit();
    }
    if ($role && $_SESSION['Role'] != $role) {
        echo "Erişim Reddedildi.";
        exit();
    }
}
?>
