
<?php
include 'config.php';

// Mengambil seluruh data dari database
$query = mysqli_query($conn, "SELECT * FROM alat_elektromedis ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Alat Elektromedis</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f4f6f9;
        }

        h2 {
            color: #163b65;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #163b65;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .tambah {
            display: inline-block;
            padding: 10px 15px;
            background: #198754;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .edit {
            color: blue;
        }

        .hapus {
            color: red;
        }
    </style>
</head>

<body>

    <h2>DATA INVENTARIS ALAT ELEKTROMEDIS</h2>

    <p>Aplikasi CRUD menggunakan PHP Native dan MySQL</p>

    <a href="add.php" class="tambah">+ Tambah Data Alat</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Alat</th>
            <th>Tahun</th>
            <th>Merek</th>
            <th>Lokasi</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>

            <td>
                <?php echo htmlspecialchars($data['nama_alat']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($data['tahun']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($data['merek']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($data['lokasi']); ?>
            </td>

            <td>
                <a class="edit" href="edit.php?id=<?php echo $data['id']; ?>">
                    Edit
                </a>

                |

                <a class="hapus"
                   href="delete.php?id=<?php echo $data['id']; ?>"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                    Hapus
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</body>
</html>
