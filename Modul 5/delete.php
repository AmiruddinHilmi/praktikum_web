
<?php
include 'config.php';

// Memeriksa ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID alat tidak valid!");
}

$id = (int) $_GET['id'];

// Menghapus data berdasarkan ID
$query = "DELETE FROM alat_elektromedis WHERE id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
?>
