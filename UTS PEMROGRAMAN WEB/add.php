
<?php
require_once __DIR__ . '/auth/auth_check.php';
require_once __DIR__ . '/config.php';

$pesan = "";

$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$inisial = strtoupper(substr($nama, 0, 1));

if (isset($_POST['simpan'])) {

    $nama_alat = trim($_POST['nama_alat']);
    $tahun = (int) $_POST['tahun'];
    $merek = trim($_POST['merek']);
    $lokasi = trim($_POST['lokasi']);

    $query = "INSERT INTO alat_elektromedis
              (nama_alat, tahun, merek, lokasi)
              VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {

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
            $pesan = "Gagal menyimpan data alat. Silakan coba kembali.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $pesan = "Terjadi kesalahan saat mempersiapkan database.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Alat | Medical Inventory System UMPKU</title>

    <link rel="stylesheet" href="dashboard.css?v=20261118">
</head>

<body class="add-page">

<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-brand">
            <img src="logo-umpku.png" alt="Logo UMPKU">

            <div>
                <strong>MEDICAL</strong>
                <span>INVENTORY SYSTEM</span>
            </div>
        </div>

        <div class="sidebar-section">

            <span class="section-label">MENU UTAMA</span>

            <a href="dashboard.php" class="sidebar-link">
                <span class="menu-icon">▦</span>
                Dashboard
            </a>

            <a href="index.php" class="sidebar-link">
                <span class="menu-icon">▤</span>
                Data Inventaris
            </a>

            <a href="add.php" class="sidebar-link active">
                <span class="menu-icon">＋</span>
                Tambah Alat
            </a>

        </div>

        <!-- AKUN -->
        <div class="sidebar-section">

            <span class="section-label">AKUN</span>

            <div class="sidebar-user">

                <div class="avatar">
                    <?= htmlspecialchars($inisial) ?>
                </div>

                <div class="sidebar-user-info">
                    <strong><?= htmlspecialchars($nama) ?></strong>
                    <span>Pengguna Sistem</span>
                </div>

            </div>

            <form action="auth/logout.php" method="POST"
                  onsubmit="return confirm('Yakin ingin logout?')">

                <button type="submit" class="sidebar-logout">
                    <span class="menu-icon">↪</span>
                    Logout
                </button>

            </form>

        </div>

        <!-- FOOTER SIDEBAR -->
        <div class="sidebar-footer">

            <strong>UMPKU SURAKARTA</strong>
            <span>Program Studi D4 Elektromedis</span>
            <span>© <?= date('Y') ?></span>

        </div>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="topbar-left">

                <button class="mobile-menu" id="menuToggle"
                        type="button" aria-label="Buka menu">
                    ☰
                </button>

                <div>
                    <span class="topbar-title">Medical Inventory</span>

                    <span class="topbar-subtitle">
                        Sistem Informasi Alat Elektromedis
                    </span>
                </div>

            </div>

            <div class="topbar-right">

                <div class="topbar-date">
                    <?= date('d M Y') ?>
                </div>

                <div class="topbar-profile">

                    <div class="avatar">
                        <?= htmlspecialchars($inisial) ?>
                    </div>

                    <div class="topbar-profile-info">
                        <strong><?= htmlspecialchars($nama) ?></strong>
                        <span>Administrator</span>
                    </div>

                </div>

            </div>

        </header>


        <!-- CONTENT -->
        <div class="content-wrapper">

            <!-- PAGE HEADING -->
            <section class="add-page-heading">

                <div>

                    <span class="add-eyebrow">
                        INVENTORY MANAGEMENT
                    </span>

                    <h1>Tambah Alat Elektromedis</h1>

                    <p>
                        Tambahkan informasi peralatan elektromedis
                        baru ke dalam sistem inventaris UMPKU Surakarta.
                    </p>

                </div>

                <div class="add-heading-decoration">
                    <span>＋</span>
                    <strong>NEW EQUIPMENT</strong>
                    <small>MEDICAL INVENTORY SYSTEM</small>
                </div>

            </section>


            <!-- FORM CARD -->
            <section class="add-form-card">

                <!-- CARD HEADER -->
                <div class="add-card-header">

                    <div class="add-card-icon">
                        ▤
                    </div>

                    <div>
                        <h2>Formulir Alat Elektromedis</h2>

                        <p>
                            Lengkapi informasi berikut untuk mendaftarkan
                            alat ke database inventaris.
                        </p>
                    </div>

                </div>


                <?php if ($pesan != ""): ?>

                    <div class="add-alert">
                        <span>!</span>
                        <?= htmlspecialchars($pesan) ?>
                    </div>

                <?php endif; ?>


                <!-- FORM -->
                <form method="POST" class="add-equipment-form">

                    <div class="add-form-section-title">
                        <span>01</span>
                        Informasi Peralatan
                    </div>


                    <div class="add-form-grid">

                        <!-- NAMA ALAT -->
                        <div class="add-form-group">

                            <label for="nama_alat">
                                Nama Alat Elektromedis
                                <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="nama_alat"
                                name="nama_alat"
                                placeholder="Contoh: Patient Monitor"
                                value="<?= htmlspecialchars($_POST['nama_alat'] ?? '') ?>"
                                required
                            >

                            <small>
                                Masukkan nama peralatan elektromedis.
                            </small>

                        </div>


                        <!-- TAHUN -->
                        <div class="add-form-group">

                            <label for="tahun">
                                Tahun Pengadaan
                                <span>*</span>
                            </label>

                            <input
                                type="number"
                                id="tahun"
                                name="tahun"
                                placeholder="Contoh: 2024"
                                min="1901"
                                max="2155"
                                value="<?= htmlspecialchars($_POST['tahun'] ?? '') ?>"
                                required
                            >

                            <small>
                                Masukkan tahun pengadaan alat.
                            </small>

                        </div>


                        <!-- MEREK -->
                        <div class="add-form-group">

                            <label for="merek">
                                Merek Alat
                                <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="merek"
                                name="merek"
                                placeholder="Contoh: Mindray"
                                value="<?= htmlspecialchars($_POST['merek'] ?? '') ?>"
                                required
                            >

                            <small>
                                Masukkan merek atau produsen alat.
                            </small>

                        </div>


                        <!-- LOKASI -->
                        <div class="add-form-group">

                            <label for="lokasi">
                                Lokasi Alat
                                <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="lokasi"
                                name="lokasi"
                                placeholder="Contoh: Ruang ICU"
                                value="<?= htmlspecialchars($_POST['lokasi'] ?? '') ?>"
                                required
                            >

                            <small>
                                Masukkan lokasi penempatan alat.
                            </small>

                        </div>

                    </div>


                    <!-- INFORMATION -->
                    <div class="add-information">

                        <div class="add-information-icon">
                            i
                        </div>

                        <div>
                            <strong>Informasi Penyimpanan</strong>

                            <p>
                                Data yang disimpan akan otomatis masuk
                                ke database Medical Inventory System
                                dan ditampilkan pada halaman Data Inventaris.
                            </p>
                        </div>

                    </div>


                    <!-- ACTION BUTTON -->
                    <div class="add-form-actions">

                        <a href="index.php" class="add-back-button">
                            <span>←</span>
                            Kembali
                        </a>

                        <button
                            type="submit"
                            name="simpan"
                            class="add-save-button"
                        >
                            <span>＋</span>
                            Simpan Data Alat
                        </button>

                    </div>

                </form>

            </section>


            <!-- FOOTER -->
            <footer class="dashboard-footer">

                <span>
                    © <?= date('Y') ?>
                    Universitas Muhammadiyah PKU Surakarta
                </span>

                <span>
                    Medical Inventory System | D4 Elektromedis
                </span>

            </footer>

        </div>

    </main>

</div>


<script>

// SIDEBAR MOBILE
document.getElementById('menuToggle').addEventListener('click', function () {

    document.querySelector('.sidebar')
        .classList.toggle('sidebar-open');

});

</script>

</body>
</html>
