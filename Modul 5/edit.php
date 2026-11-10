
<?php
include 'config.php';

// Mengambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID alat tidak valid!");
}

$id = (int) $_GET['id'];

// Mengambil data berdasarkan ID
$stmt = mysqli_prepare($conn, 
    "SELECT * FROM alat_elektromedis WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die("Data alat tidak ditemukan!");
}

// Proses update data
if (isset($_POST['update'])) {

    $nama_alat = $_POST['nama_alat'];
    $tahun = (int) $_POST['tahun'];
    $merek = $_POST['merek'];
    $lokasi = $_POST['lokasi'];

    $query = "UPDATE alat_elektromedis
              SET nama_alat = ?, tahun = ?, merek = ?, lokasi = ?
              WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "sissi",
        $nama_alat,
        $tahun,
        $merek,
        $lokasi,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Alat</title>
</head>
<body>

    <h2>Edit Data Alat Elektromedis</h2>

    <form method="POST">

        <label>Nama Alat:</label><br>
        <input type="text" name="nama_alat"
        value="<?php echo htmlspecialchars($data['nama_alat']); ?>"
        required>
        <br><br>

        <label>Tahun:</label><br>
        <input type="number" name="tahun"
        value="<?php echo htmlspecialchars($data['tahun']); ?>"
        min="1901" max="2155" required>
        <br><br>

        <label>Merek:</label><br>
        <input type="text" name="merek"
        value="<?php echo htmlspecialchars($data['merek']); ?>"
        required>
        <br><br>

        <label>Lokasi:</label><br>
        <input type="text" name="lokasi"
        value="<?php echo htmlspecialchars($data['lokasi']); ?>"
        required>
        <br><br>

        <button type="submit" name="update">
            Update Data
        </button>

    </form>

    <br>
    <a href="index.php">Kembali</a>

</body>
</html>
