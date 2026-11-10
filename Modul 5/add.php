
<?php
include 'config.php';

$pesan = "";

if (isset($_POST['simpan'])) {

    $nama_alat = $_POST['nama_alat'];
    $tahun = $_POST['tahun'];
    $merek = $_POST['merek'];
    $lokasi = $_POST['lokasi'];

    $query = "INSERT INTO alat_elektromedis
              (nama_alat, tahun, merek, lokasi)
              VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "siss",
        $nama_alat,
        $tahun,
        $merek,
        $lokasi
    );

    if (mysqli_stmt_execute($stmt)) {
        $pesan = "Data alat berhasil ditambahkan!";
    } else {
        $pesan = "Gagal menambahkan data: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Alat Elektromedis</title>
</head>
<body>

    <h2>Tambah Data Alat Elektromedis</h2>

    <?php if ($pesan != ""): ?>
        <p><?php echo htmlspecialchars($pesan); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nama Alat:</label><br>
        <input type="text" name="nama_alat" required>
        <br><br>

        <label>Tahun:</label><br>
        <input type="number" name="tahun"
               min="1901" max="2155" required>
        <br><br>

        <label>Merek:</label><br>
        <input type="text" name="merek" required>
        <br><br>

        <label>Lokasi:</label><br>
        <input type="text" name="lokasi" required>
        <br><br>

        <button type="submit" name="simpan">
            Simpan Data
        </button>

    </form>

    <br>
    <a href="index.php">Kembali ke Halaman Utama</a>

</body>
</html>
