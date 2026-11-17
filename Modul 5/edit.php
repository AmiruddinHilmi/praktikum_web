
<?php
require_once __DIR__ . '/auth/auth_check.php';
require_once __DIR__ . '/config.php';

// IDENTITAS PENGGUNA
$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$inisial = strtoupper(substr($nama, 0, 1));

// VALIDASI ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// AMBIL DATA ALAT
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM alat_elektromedis WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: index.php");
    exit;
}

$pesan = "";
$jenisPesan = "";

// PROSES UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama_alat = trim($_POST['nama_alat'] ?? '');
    $tahun = (int) ($_POST['tahun'] ?? 0);
    $merek = trim($_POST['merek'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');

    if (
        $nama_alat === "" ||
        $tahun < 1901 ||
        $tahun > 2155 ||
        $merek === "" ||
        $lokasi === ""
    ) {

        $pesan = "Periksa kembali seluruh data yang dimasukkan.";
        $jenisPesan = "error";

        $data['nama_alat'] = $nama_alat;
        $data['tahun'] = $tahun;
        $data['merek'] = $merek;
        $data['lokasi'] = $lokasi;

    } else {

        $update = mysqli_prepare(
            $conn,
            "UPDATE alat_elektromedis
             SET nama_alat = ?, tahun = ?, merek = ?, lokasi = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "sissi",
            $nama_alat,
            $tahun,
            $merek,
            $lokasi,
            $id
        );

        if (mysqli_stmt_execute($update)) {

            mysqli_stmt_close($update);

            header("Location: index.php?status=updated");
            exit;

        } else {

            $pesan = "Gagal memperbarui data. Silakan coba lagi.";
            $jenisPesan = "error";

            mysqli_stmt_close($update);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Alat | Medical Inventory System UMPKU</title>

    <link rel="stylesheet" href="dashboard.css?v=20261118">

    <style>
        /* ==================================
           EDIT PAGE - MEDICAL INVENTORY
        ================================== */

        .edit-page-content {
            max-width: 1100px;
            margin: 0 auto;
        }

        .edit-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .edit-heading .eyebrow {
            display: inline-block;
            color: #F36B16;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.8px;
            margin-bottom: 9px;
        }

        .edit-heading h1 {
            color: #172B4D;
            font-size: 30px;
            font-weight: 800;
            margin: 0 0 9px;
        }

        .edit-heading p {
            color: #8290A4;
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
        }

        .edit-form-card {
            background: #FFFFFF;
            border: 1px solid #E5EAF1;
            border-radius: 17px;
            padding: 30px;
            box-shadow: 0 5px 22px rgba(23,43,77,.045);
        }

        .edit-card-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 23px;
            margin-bottom: 25px;
            border-bottom: 1px solid #EDF0F5;
        }

        .edit-heading-icon {
            width: 49px;
            height: 49px;
            min-width: 49px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: #FFF0E6;
            color: #F36B16;
            font-size: 23px;
        }

        .edit-card-heading h2 {
            color: #172B4D;
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .edit-card-heading p {
            color: #8290A4;
            font-size: 12px;
            margin: 0;
        }

        .edit-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 15px;
            margin-bottom: 25px;
            border-radius: 10px;
            background: #F6F8FC;
            color: #66768D;
            font-size: 12px;
        }

        .edit-info span {
            color: #F36B16;
            font-size: 17px;
        }

        .edit-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .edit-form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .edit-form-group.full-width {
            grid-column: 1 / -1;
        }

        .edit-form-group label {
            color: #263B59;
            font-size: 13px;
            font-weight: 700;
        }

        .edit-form-group input {
            width: 100%;
            height: 49px;
            padding: 0 15px;
            border: 1px solid #DDE5EF;
            border-radius: 9px;
            outline: none;
            background: #FBFCFE;
            color: #172B4D;
            font-family: inherit;
            font-size: 13px;
            transition: .25s ease;
        }

        .edit-form-group input::placeholder {
            color: #A0AABC;
        }

        .edit-form-group input:hover {
            border-color: #BAC8D9;
        }

        .edit-form-group input:focus {
            border-color: #F36B16;
            background: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(243,107,22,.09);
        }

        .edit-form-group small {
            color: #94A3B8;
            font-size: 11px;
        }

        .edit-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 15px;
            margin-bottom: 22px;
            border-radius: 9px;
            font-size: 12px;
        }

        .edit-alert.error {
            color: #B42318;
            background: #FFF1F0;
            border: 1px solid #F5C6CB;
        }

        .edit-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 11px;
            margin-top: 30px;
            padding-top: 22px;
            border-top: 1px solid #EDF0F5;
        }

        .edit-btn {
            min-height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 22px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: .25s ease;
        }

        .edit-btn-primary {
            color: #FFFFFF;
            background: #F36B16;
            box-shadow: 0 4px 12px rgba(243,107,22,.16);
        }

        .edit-btn-primary:hover {
            background: #DF580B;
            transform: translateY(-2px);
            box-shadow: 0 7px 17px rgba(243,107,22,.23);
        }

        .edit-btn-secondary {
            color: #344563;
            background: #EDF1F7;
        }

        .edit-btn-secondary:hover {
            background: #DDE5EF;
            transform: translateY(-2px);
        }

        .edit-footer {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 30px;
            padding: 20px 0 5px;
            border-top: 1px solid #E4EAF1;
            color: #8290A4;
            font-size: 11px;
        }

        @media (max-width: 700px) {

            .edit-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .edit-heading h1 {
                font-size: 24px;
            }

            .edit-form-card {
                padding: 21px 17px;
            }

            .edit-form-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .edit-form-group.full-width {
                grid-column: auto;
            }

            .edit-form-actions {
                flex-direction: column-reverse;
            }

            .edit-btn {
                width: 100%;
            }

            .edit-footer {
                flex-direction: column;
            }
        }
    </style>

</head>

<body>

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

            <a href="index.php" class="sidebar-link active">
                <span class="menu-icon">▤</span>
                Data Inventaris
            </a>

            <a href="add.php" class="sidebar-link">
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
                    <strong>
                        <?= htmlspecialchars($nama) ?>
                    </strong>

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
                    <span class="topbar-title">
                        Medical Inventory
                    </span>

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
                        <strong>
                            <?= htmlspecialchars($nama) ?>
                        </strong>

                        <span>Administrator</span>
                    </div>

                </div>

            </div>

        </header>


        <!-- CONTENT -->
        <div class="content-wrapper">

            <div class="edit-page-content">

                <!-- PAGE HEADING -->
                <section class="edit-heading">

                    <div>
                        <span class="eyebrow">
                            INVENTORY MANAGEMENT
                        </span>

                        <h1>Edit Data Inventaris</h1>

                        <p>
                            Perbarui informasi alat elektromedis
                            yang telah terdaftar dalam sistem.
                        </p>
                    </div>

                </section>


                <!-- FORM CARD -->
                <section class="edit-form-card">

                    <div class="edit-card-heading">

                        <div class="edit-heading-icon">
                            ✎
                        </div>

                        <div>
                            <h2>Formulir Alat Elektromedis</h2>
                            <p>
                                Perubahan informasi data inventaris alat
                            </p>
                        </div>

                    </div>


                    <div class="edit-info">
                        <span>ⓘ</span>
                        <div>
                            Mengedit data inventaris dengan ID
                            <strong>#<?= (int)$id ?></strong>.
                            Pastikan informasi yang dimasukkan sudah benar.
                        </div>
                    </div>


                    <?php if ($pesan !== ""): ?>
                        <div class="edit-alert <?= $jenisPesan ?>">
                            <span>!</span>
                            <?= htmlspecialchars($pesan) ?>
                        </div>
                    <?php endif; ?>


                    <form method="POST">

                        <div class="edit-form-grid">

                            <div class="edit-form-group full-width">

                                <label for="nama_alat">
                                    Nama Alat Elektromedis
                                </label>

                                <input
                                    type="text"
                                    id="nama_alat"
                                    name="nama_alat"
                                    placeholder="Contoh: Patient Monitor"
                                    value="<?= htmlspecialchars($data['nama_alat']) ?>"
                                    maxlength="150"
                                    required
                                >

                            </div>


                            <div class="edit-form-group">

                                <label for="tahun">
                                    Tahun Pengadaan
                                </label>

                                <input
                                    type="number"
                                    id="tahun"
                                    name="tahun"
                                    placeholder="Contoh: 2024"
                                    value="<?= htmlspecialchars((string)$data['tahun']) ?>"
                                    min="1901"
                                    max="2155"
                                    required
                                >

                            </div>


                            <div class="edit-form-group">

                                <label for="merek">
                                    Merek Alat
                                </label>

                                <input
                                    type="text"
                                    id="merek"
                                    name="merek"
                                    placeholder="Contoh: Mindray"
                                    value="<?= htmlspecialchars($data['merek']) ?>"
                                    maxlength="100"
                                    required
                                >

                            </div>


                            <div class="edit-form-group full-width">

                                <label for="lokasi">
                                    Lokasi Alat
                                </label>

                                <input
                                    type="text"
                                    id="lokasi"
                                    name="lokasi"
                                    placeholder="Contoh: Ruang ICU"
                                    value="<?= htmlspecialchars($data['lokasi']) ?>"
                                    maxlength="150"
                                    required
                                >

                            </div>

                        </div>


                        <!-- ACTION BUTTONS -->
                        <div class="edit-form-actions">

                            <a href="index.php"
                               class="edit-btn edit-btn-secondary">
                                ← Kembali
                            </a>

                            <button
                                type="submit"
                                class="edit-btn edit-btn-primary"
                                onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan data ini?')"
                            >
                                ✓ Simpan Perubahan
                            </button>

                        </div>

                    </form>

                </section>


                <!-- FOOTER -->
                <footer class="edit-footer">

                    <span>
                        © <?= date('Y') ?>
                        Universitas Muhammadiyah PKU Surakarta
                    </span>

                    <span>
                        Medical Inventory System | D4 Elektromedis
                    </span>

                </footer>

            </div>

        </div>

    </main>

</div>


<script>

// SIDEBAR MOBILE
const menuToggle = document.getElementById('menuToggle');

if (menuToggle) {
    menuToggle.addEventListener('click', function () {
        document.querySelector('.sidebar')
            .classList.toggle('sidebar-open');
    });
}

</script>

</body>
</html>
