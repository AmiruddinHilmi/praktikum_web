
<?php
// Konfigurasi database
$host = "localhost";
$user = "root";
$password = "";
$database = "db_alat_elektromedis";

// Membuat koneksi ke database
$conn = mysqli_connect($host, $user, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
