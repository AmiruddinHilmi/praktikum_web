
<?php
require_once __DIR__ . '/auth/auth_check.php';
require_once __DIR__ . '/config.php';

// DATA PENGGUNA
$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$inisial = strtoupper(substr($nama, 0, 1));

// STATISTIK INVENTARIS
$total = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah FROM alat_elektromedis"
))['jumlah'];

$tahun = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(DISTINCT tahun) AS jumlah FROM alat_elektromedis"
))['jumlah'];

$merek = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(DISTINCT merek) AS jumlah FROM alat_elektromedis"
))['jumlah'];

$lokasi = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(DISTINCT lokasi) AS jumlah FROM alat_elektromedis"
))['jumlah'];

// GRAFIK TAHUN
$yearQuery = mysqli_query(
    $conn,
    "SELECT tahun, COUNT(*) AS jumlah
     FROM alat_elektromedis
     GROUP BY tahun
     ORDER BY tahun ASC"
);

$yearLabels = [];
$yearValues = [];

while ($row = mysqli_fetch_assoc($yearQuery)) {
    $yearLabels[] = (string)$row['tahun'];
    $yearValues[] = (int)$row['jumlah'];
}

// GRAFIK MEREK
$brandQuery = mysqli_query(
    $conn,
    "SELECT merek, COUNT(*) AS jumlah
     FROM alat_elektromedis
     GROUP BY merek
     ORDER BY jumlah DESC, merek ASC"
);

$brandLabels = [];
$brandValues = [];

while ($row = mysqli_fetch_assoc($brandQuery)) {
    $brandLabels[] = $row['merek'];
    $brandValues[] = (int)$row['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Medical Inventory System UMPKU</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="dashboard.css?v=20261118">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="dashboard-page">

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

            <a href="dashboard.php" class="sidebar-link active">
                <span class="menu-icon">▦</span>
                Dashboard
            </a>

            <a href="index.php" class="sidebar-link">
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

            <!-- HEADING -->
            <section class="welcome-section">

                <div>
                    <span class="eyebrow">OVERVIEW</span>

                    <h1>Dashboard Inventaris</h1>

                    <p>
                        Selamat datang kembali,
                        <strong><?= htmlspecialchars($nama) ?></strong>.
                        Berikut ringkasan inventaris alat elektromedis.
                    </p>
                </div>

                <a href="index.php" class="primary-button">
                    Lihat Data Inventaris
                </a>

            </section>


            <!-- STATISTIK -->
            <section class="statistics-grid">

                <div class="stat-card stat-orange">

                    <div class="stat-top">
                        <div class="stat-icon">▤</div>
                        <span class="stat-caption">TOTAL INVENTARIS</span>
                    </div>

                    <strong class="stat-number">
                        <?= (int)$total ?>
                    </strong>

                    <span class="stat-description">
                        Total alat elektromedis
                    </span>

                </div>


                <div class="stat-card stat-blue">

                    <div class="stat-top">
                        <div class="stat-icon">◷</div>
                        <span class="stat-caption">TAHUN PENGADAAN</span>
                    </div>

                    <strong class="stat-number">
                        <?= (int)$tahun ?>
                    </strong>

                    <span class="stat-description">
                        Tahun pengadaan berbeda
                    </span>

                </div>


                <div class="stat-card stat-green">

                    <div class="stat-top">
                        <div class="stat-icon">▦</div>
                        <span class="stat-caption">JENIS MEREK</span>
                    </div>

                    <strong class="stat-number">
                        <?= (int)$merek ?>
                    </strong>

                    <span class="stat-description">
                        Merek alat terdaftar
                    </span>

                </div>


                <div class="stat-card stat-purple">

                    <div class="stat-top">
                        <div class="stat-icon">⌖</div>
                        <span class="stat-caption">LOKASI</span>
                    </div>

                    <strong class="stat-number">
                        <?= (int)$lokasi ?>
                    </strong>

                    <span class="stat-description">
                        Lokasi penyimpanan berbeda
                    </span>

                </div>

            </section>


            <!-- GRAFIK -->
            <section class="charts-grid">

                <div class="panel chart-panel">

                    <div class="panel-header">

                        <div>
                            <h2>Statistik Pengadaan</h2>
                            <p>Jumlah alat berdasarkan tahun pengadaan</p>
                        </div>

                        <span class="panel-tag">TAHUN</span>

                    </div>

                    <div class="chart-container">
                        <canvas id="yearChart"></canvas>
                    </div>

                </div>


                <div class="panel chart-panel">

                    <div class="panel-header">

                        <div>
                            <h2>Distribusi Merek</h2>
                            <p>Komposisi merek alat elektromedis</p>
                        </div>

                        <span class="panel-tag">MEREK</span>

                    </div>

                    <div class="chart-container doughnut-container">
                        <canvas id="brandChart"></canvas>
                    </div>

                </div>

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

// DATA DATABASE
const yearLabels = <?= json_encode($yearLabels) ?>;
const yearValues = <?= json_encode($yearValues) ?>;

const brandLabels = <?= json_encode($brandLabels) ?>;
const brandValues = <?= json_encode($brandValues) ?>;


// GRAFIK PENGADAAN
new Chart(document.getElementById('yearChart'), {

    type: 'bar',

    data: {
        labels: yearLabels,

        datasets: [{
            label: 'Jumlah Alat',
            data: yearValues,
            backgroundColor: '#F36B16',
            borderRadius: 7,
            maxBarThickness: 45
        }]
    },

    options: {

        responsive: true,
        maintainAspectRatio: false,

        interaction: {
            intersect: false,
            mode: 'index'
        },

        plugins: {

            legend: {
                display: false
            },

            tooltip: {
                backgroundColor: '#172B4D',
                padding: 12,
                titleFont: {
                    family: 'Inter',
                    size: 12
                },
                bodyFont: {
                    family: 'Inter',
                    size: 12
                }
            }

        },

        scales: {

            x: {
                grid: {
                    display: false
                },

                ticks: {
                    font: {
                        family: 'Inter',
                        size: 11
                    }
                }
            },

            y: {
                beginAtZero: true,

                ticks: {
                    precision: 0,
                    font: {
                        family: 'Inter',
                        size: 11
                    }
                },

                grid: {
                    color: '#E9EDF3'
                }
            }

        }

    }

});


// GRAFIK DISTRIBUSI MEREK
new Chart(document.getElementById('brandChart'), {

    type: 'doughnut',

    data: {
        labels: brandLabels,

        datasets: [{

            data: brandValues,

            backgroundColor: [
                '#F36B16',
                '#2997C8',
                '#20A47B',
                '#7969D9',
                '#E4B52B',
                '#D95D75'
            ],

            borderWidth: 3,
            borderColor: '#FFFFFF',
            hoverOffset: 7

        }]
    },

    options: {

        responsive: true,
        maintainAspectRatio: false,

        plugins: {

            legend: {

                position: 'bottom',

                labels: {
                    padding: 18,
                    usePointStyle: true,
                    pointStyle: 'rectRounded',

                    font: {
                        family: 'Inter',
                        size: 11
                    }
                }

            },

            tooltip: {
                backgroundColor: '#172B4D',
                padding: 12
            }

        },

        cutout: '65%'

    }

});


// SIDEBAR MOBILE
document.getElementById('menuToggle').addEventListener('click', function () {

    document.querySelector('.sidebar')
        .classList.toggle('sidebar-open');

});

</script>

</body>
</html>
