
<?php
session_start();
require_once __DIR__ . '/../config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';

    if ($nama === "" || $email === "" || $password === "") {

        $error = "Semua kolom wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid.";

    } elseif (strlen($password) < 8) {

        $error = "Password minimal 8 karakter.";

    } elseif ($password !== $konfirmasi) {

        $error = "Konfirmasi password tidak cocok.";

    } else {

        // CEK EMAIL
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            $error = "Email sudah digunakan.";

            mysqli_stmt_close($stmt);

        } else {

            mysqli_stmt_close($stmt);

            // ENKRIPSI PASSWORD
            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // SIMPAN AKUN
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (nama_lengkap, email, password)
                VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $nama,
                $email,
                $hash
            );

            if (mysqli_stmt_execute($stmt)) {

                $user_id = mysqli_insert_id($conn);

                mysqli_stmt_close($stmt);

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['nama_lengkap'] = $nama;
                $_SESSION['email'] = $email;

                header("Location: ../index.php");
                exit;

            } else {

                $error = "Registrasi gagal. Silakan coba lagi.";

                mysqli_stmt_close($stmt);

            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrasi | Medical Inventory System UMPKU</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="auth.css?v=20261118">
</head>

<body class="auth-page">

<div class="auth-background">
    <div class="auth-orb orb-one"></div>
    <div class="auth-orb orb-two"></div>
    <div class="auth-orb orb-three"></div>
    <div class="auth-grid"></div>
</div>

<div class="auth-shell">

    <!-- BRAND PANEL -->
    <section class="brand-panel">

        <div class="brand-content">

            <div class="brand-logo">
                <img src="../logo-umpku.png" alt="Logo UMPKU">
            </div>

            <span class="brand-label">MEDICAL EQUIPMENT MANAGEMENT</span>

            <h1>
                Join Our
                <span>Inventory System.</span>
            </h1>

            <p class="brand-description">
                Buat akun untuk mengakses sistem informasi
                pengelolaan dan pemantauan inventaris
                alat elektromedis UMPKU Surakarta.
            </p>

            <div class="brand-divider"></div>

            <div class="brand-features">

                <div class="brand-feature">
                    <span class="feature-icon">＋</span>
                    <div>
                        <strong>Registrasi Pengguna</strong>
                        <small>Buat akun baru dengan mudah</small>
                    </div>
                </div>

                <div class="brand-feature">
                    <span class="feature-icon">▤</span>
                    <div>
                        <strong>Pengelolaan Inventaris</strong>
                        <small>Akses informasi alat elektromedis</small>
                    </div>
                </div>

                <div class="brand-feature">
                    <span class="feature-icon">✓</span>
                    <div>
                        <strong>Akses Sistem</strong>
                        <small>Gunakan akun untuk masuk ke aplikasi</small>
                    </div>
                </div>

            </div>

        </div>

        <div class="brand-footer">
            UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA
            <span>PROGRAM STUDI D4 ELEKTROMEDIS</span>
        </div>

    </section>


    <!-- REGISTER FORM -->
    <main class="auth-form-area">

        <div class="auth-card register-card">

            <div class="auth-mobile-logo">
                <img src="../logo-umpku.png" alt="Logo UMPKU">
            </div>

            <div class="auth-heading">

                <span class="auth-eyebrow">
                    CREATE ACCOUNT
                </span>

                <h2>Buat Akun Baru</h2>

                <p>
                    Lengkapi informasi berikut untuk mendaftar.
                </p>

            </div>


            <?php if ($error): ?>
                <div class="auth-alert">
                    <span>!</span>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>


            <form method="POST" class="auth-form">

                <div class="auth-field">

                    <label for="nama_lengkap">Nama Lengkap</label>

                    <div class="input-wrapper">
                        <span class="input-icon">♙</span>

                        <input
                            type="text"
                            id="nama_lengkap"
                            name="nama_lengkap"
                            placeholder="Masukkan nama lengkap"
                            value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>"
                            maxlength="100"
                            autocomplete="name"
                            required
                        >
                    </div>

                </div>


                <div class="auth-field">

                    <label for="email">Email</label>

                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            maxlength="150"
                            autocomplete="email"
                            required
                        >
                    </div>

                </div>


                <div class="auth-field">

                    <label for="password">Password</label>

                    <div class="input-wrapper">
                        <span class="input-icon">▣</span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="password"
                            aria-label="Tampilkan password"
                        >
                            ◉
                        </button>

                    </div>

                    <small class="field-hint">
                        Gunakan minimal 8 karakter.
                    </small>

                </div>


                <div class="auth-field">

                    <label for="konfirmasi_password">
                        Konfirmasi Password
                    </label>

                    <div class="input-wrapper">
                        <span class="input-icon">▣</span>

                        <input
                            type="password"
                            id="konfirmasi_password"
                            name="konfirmasi_password"
                            placeholder="Ulangi password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="konfirmasi_password"
                            aria-label="Tampilkan password"
                        >
                            ◉
                        </button>

                    </div>

                </div>


                <button type="submit" class="auth-submit">

                    <span>DAFTAR SEKARANG</span>
                    <span class="submit-arrow">→</span>

                </button>

            </form>


            <div class="auth-switch">
                Sudah punya akun?
                <a href="login.php">Login di sini</a>
            </div>


            <div class="auth-bottom-divider"></div>

            <div class="auth-footer">
                <span>© <?= date('Y') ?> Elektromedis UMPKU</span>
                <span>Medical Inventory System</span>
            </div>

        </div>

    </main>

</div>


<script>

// TAMPILKAN / SEMBUNYIKAN PASSWORD
document.querySelectorAll('.password-toggle').forEach(button => {

    button.addEventListener('click', function () {

        const target = document.getElementById(this.dataset.target);

        if (target.type === 'password') {
            target.type = 'text';
            this.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            target.type = 'password';
            this.setAttribute('aria-label', 'Tampilkan password');
        }

    });

});

</script>

</body>
</html>
