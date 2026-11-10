
<?php
include 'config.php';

$query = mysqli_query(
    $conn,
    "SELECT * FROM alat_elektromedis ORDER BY id DESC"
);

$total = mysqli_num_rows(
    mysqli_query($conn, "SELECT id FROM alat_elektromedis")
);

$tahun = mysqli_num_rows(
    mysqli_query($conn, "SELECT DISTINCT tahun FROM alat_elektromedis")
);

$merek = mysqli_num_rows(
    mysqli_query($conn, "SELECT DISTINCT merek FROM alat_elektromedis")
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventaris Alat Elektromedis | UMPKU</title>

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
        <h1>Dashboard Inventaris</h1>
        <p>Selamat datang di sistem pengelolaan data alat elektromedis.</p>
    </div>

    <section class="stats">

        <div class="stat-card">
            <h3>Total Alat Elektromedis</h3>
            <strong><?php echo $total; ?></strong>
        </div>

        <div class="stat-card">
            <h3>Jumlah Tahun Pengadaan</h3>
            <strong><?php echo $tahun; ?></strong>
        </div>

        <div class="stat-card">
            <h3>Jumlah Merek</h3>
            <strong><?php echo $merek; ?></strong>
        </div>

    </section>

    <section class="card">

        <div class="card-header">

            <h2>Data Inventaris Alat</h2>

            <a href="add.php" class="btn btn-primary">
                + Tambah Data Alat
            </a>

        </div>

        <div class="table-wrapper">

            <table>

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

                <?php if (mysqli_num_rows($query) > 0): ?>

                    <?php $no = 1; ?>

                    <?php while ($data = mysqli_fetch_assoc($query)): ?>

                    <tr>

                        <td><?php echo $no++; ?></td>

                        <td>
                            <strong>
                                <?php echo htmlspecialchars($data['nama_alat']); ?>
                            </strong>
                        </td>

                        <td>
                            <span class="badge-year">
                                <?php echo htmlspecialchars($data['tahun']); ?>
                            </span>
                        </td>

                        <td><?php echo htmlspecialchars($data['merek']); ?></td>

                        <td><?php echo htmlspecialchars($data['lokasi']); ?></td>

                        <td>
                            <div class="action">

                                <a class="btn btn-edit"
                                   href="edit.php?id=<?php echo $data['id']; ?>">
                                    Edit
                                </a>

                                <a class="btn btn-delete"
                                   href="delete.php?id=<?php echo $data['id']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    Hapus
                                </a>

                            </div>
                        </td>

                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="empty">
                            Belum ada data alat elektromedis.
                            Silakan tambahkan data baru.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

<footer class="footer">
    <p><strong>UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA</strong></p>
    <p>Sistem Inventaris Alat Elektromedis</p>
    <p>© <?php echo date('Y'); ?> | Program Studi D4 Elektromedis</p>
</footer>

</body>
</html>
