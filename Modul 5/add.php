
<?php
include 'config.php';

$pesan = "";

if (isset($_POST['simpan'])) {

    $nama_alat = trim($_POST['nama_alat']);
    $tahun = (int) $_POST['tahun'];
    $merek = trim($_POST['merek']);
    $lokasi = trim($_POST['lokasi']);

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
        mysqli_stmt_close($stmt);
        header("Location: index.php");
        exit;
    } else {
        $pesan = "Gagal menyimpan data alat.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Alat Elektromedis | UMPKU</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="header">

    <div class="brand">
        <img src="logo-umpku.png" alt="Logo UMPKU">

        <div class="brand-info">
            <h2>MEDICAL INVENTORY SYSTEM</h2>
            <p>Sistem Informasi Inventaris Alat Elektromedis</p>
        </div>
    </div>

</header>

<nav class="navbar">
    <a href="index.php">Dashboard</a>
    <a href="add.php">Tambah Alat</a>
</nav>

<main class="container">

    <div class="page-heading">
        <h1>Tambah Data Alat</h1>
        <p>Masukkan informasi alat elektromedis ke dalam sistem inventaris.</p>
    </div>

    <section class="card form-card">

        <div class="card-header">
            <h2>Formulir Alat Elektromedis</h2>
        </div>

        <?php if ($pesan != ""): ?>
            <div class="alert">
                <?php echo htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama Alat Elektromedis</label>
                <input
                    type="text"
                    name="nama_alat"
                    placeholder="Contoh: Patient Monitor"
                    required
                >
            </div>

            <div class="form-group">
                <label>Tahun Pengadaan</label>
                <input
                    type="number"
                    name="tahun"
                    placeholder="Contoh: 2024"
                    min="1901"
                    max="2155"
                    required
                >
            </div>

            <div class="form-group">
                <label>Merek Alat</label>
                <input
                    type="text"
                    name="merek"
                    placeholder="Contoh: Mindray"
                    required
                >
            </div>

            <div class="form-group">
                <label>Lokasi Alat</label>
                <input
                    type="text"
                    name="lokasi"
                    placeholder="Contoh: Ruang ICU"
                    required
                >
            </div>

            <div class="form-actions">

                <button type="submit"
                        name="simpan"
                        class="btn btn-primary">
                    Simpan Data
                </button>

                <a href="index.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </div>

        </form>

    </section>

</main>

<footer class="footer">
    <p><strong>UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA</strong></p>
    <p>Sistem Inventaris Alat Elektromedis</p>
    <p>© <?php echo date('Y'); ?> | Program Studi D4 Elektromedis</p>
</footer>

</body>
</html>
