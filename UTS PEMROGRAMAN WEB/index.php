<?php
require_once __DIR__ . '/auth/auth_check.php';
require_once __DIR__ . '/config.php';

// Mengambil data inventaris
$query = mysqli_query(
    $conn,
    "SELECT * FROM alat_elektromedis ORDER BY id DESC"
);

// Data pengguna
$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$inisial = strtoupper(substr($nama, 0, 1));

// Total inventaris
$total = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah FROM alat_elektromedis"
))['jumlah'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Inventaris | Medical Inventory System UMPKU</title>
    <!-- Ganti angka v jika ingin memaksa browser memuat CSS terbaru -->
    <link rel="stylesheet" href="dashboard.css?v=20261118">
</head>
<body class="inventory-page">
<div class="dashboard-layout">

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
                <span class="menu-icon">▦</span> Dashboard
            </a>
            <a href="index.php" class="sidebar-link active">
                <span class="menu-icon">▤</span> Data Inventaris
            </a>
            <a href="add.php" class="sidebar-link">
                <span class="menu-icon">＋</span> Tambah Alat
            </a>
        </div>

        <div class="sidebar-section">
            <span class="section-label">AKUN</span>
            <div class="sidebar-user">
                <div class="avatar"><?= htmlspecialchars($inisial) ?></div>
                <div class="sidebar-user-info">
                    <strong><?= htmlspecialchars($nama) ?></strong>
                    <span>Pengguna Sistem</span>
                </div>
            </div>
            <form action="auth/logout.php" method="POST"
                  onsubmit="return confirm('Yakin ingin logout?')">
                <button type="submit" class="sidebar-logout">
                    <span class="menu-icon">↪</span> Logout
                </button>
            </form>
        </div>

        <div class="sidebar-footer">
            <strong>UMPKU SURAKARTA</strong>
            <span>Program Studi D4 Elektromedis</span>
            <span>© <?= date('Y') ?></span>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu" id="menuToggle" type="button"
                        aria-label="Buka menu">☰</button>
                <div>
                    <span class="topbar-title">Medical Inventory</span>
                    <span class="topbar-subtitle">Sistem Informasi Alat Elektromedis</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-date"><?= date('d M Y') ?></div>
                <div class="topbar-profile">
                    <div class="avatar"><?= htmlspecialchars($inisial) ?></div>
                    <div class="topbar-profile-info">
                        <strong><?= htmlspecialchars($nama) ?></strong>
                        <span>Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <section class="inv-page-heading">
                <div>
                    <span class="inv-eyebrow">MEDICAL EQUIPMENT MANAGEMENT</span>
                    <h1>Data Inventaris Alat</h1>
                    <p>Pusat pengelolaan dan pemantauan data inventaris peralatan elektromedis UMPKU Surakarta.</p>
                </div>
                <div class="inv-heading-decoration">
                    <span>UMPKU</span>
                    <small>MEDICAL INVENTORY</small>
                </div>
            </section>

            <section class="inv-panel">
                <div class="inv-panel-heading">
                    <div class="inv-heading-icon">▤</div>
                    <div>
                        <h2>Daftar Inventaris</h2>
                        <p>Informasi lengkap seluruh alat elektromedis yang terdaftar dalam sistem.</p>
                    </div>
                </div>

                <div class="inv-summary">
                    <div class="inv-summary-left">
                        <div class="inv-summary-icon">▤</div>
                        <div>
                            <strong><?= (int)$total ?></strong>
                            <span>Total Alat Terdaftar</span>
                        </div>
                    </div>
                    <div class="inv-summary-status">
                        <span></span> Database Inventaris
                    </div>
                </div>

                <div class="inv-toolbar">
                    <div class="inv-search">
                        <span>⌕</span>
                        <input type="text" id="searchTable"
                               placeholder="Cari nama alat, merek, atau lokasi..."
                               autocomplete="off">
                    </div>
                    <div class="inv-table-count">
                        <span id="visibleCount"><?= (int)$total ?></span> data inventaris
                    </div>
                </div>

                <div class="inv-table-wrapper">
                    <table id="inventoryTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Alat</th>
                                <th>Tahun</th>
                                <th>Merek</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($query && mysqli_num_rows($query) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($data = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td class="inv-number"><?= $no++ ?></td>
                                    <td>
                                        <div class="inv-equipment">
                                            <div class="inv-equipment-icon">▤</div>
                                            <strong><?= htmlspecialchars($data['nama_alat']) ?></strong>
                                        </div>
                                    </td>
                                    <td><span class="inv-year"><?= htmlspecialchars($data['tahun']) ?></span></td>
                                    <td><span class="inv-brand"><?= htmlspecialchars($data['merek']) ?></span></td>
                                    <td>
                                        <div class="inv-location">
                                            <span>⌖</span><?= htmlspecialchars($data['lokasi']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inv-actions">
                                            <a href="edit.php?id=<?= (int)$data['id'] ?>" class="inv-edit">Edit</a>
                                            <a href="delete.php?id=<?= (int)$data['id'] ?>"
                                               class="inv-delete"
                                               onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="inv-empty">
                                        <div>▤</div>
                                        <strong>Belum Ada Data Inventaris</strong>
                                        <p>Gunakan menu Tambah Alat di sidebar untuk memasukkan data baru.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <div id="noSearchResult" class="inv-empty" hidden>
                        <div>⌕</div>
                        <strong>Data Tidak Ditemukan</strong>
                        <p>Silakan gunakan kata kunci pencarian lain.</p>
                    </div>
                </div>

                <div class="inv-panel-footer">
                    <span>Menampilkan data inventaris dari database MySQL.</span>
                    <span>Medical Inventory System · UMPKU</span>
                </div>
            </section>

            <footer class="dashboard-footer">
                <span>© <?= date('Y') ?> Universitas Muhammadiyah PKU Surakarta</span>
                <span>Medical Inventory System | D4 Elektromedis</span>
            </footer>
        </div>
    </main>
</div>

<script>
const searchInput = document.getElementById('searchTable');
const tableRows = Array.from(document.querySelectorAll('#inventoryTable tbody tr'))
    .filter(row => row.querySelector('.inv-equipment'));
const noResult = document.getElementById('noSearchResult');
const visibleCount = document.getElementById('visibleCount');

searchInput.addEventListener('input', function () {
    const keyword = this.value.toLowerCase().trim();
    let count = 0;

    tableRows.forEach(row => {
        const match = row.textContent.toLowerCase().includes(keyword);
        row.style.display = match ? '' : 'none';
        if (match) count++;
    });

    visibleCount.textContent = count;
    noResult.hidden = count !== 0 || keyword === '';
});

document.getElementById('menuToggle').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('sidebar-open');
});
</script>
</body>
</html>
