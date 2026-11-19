
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>
