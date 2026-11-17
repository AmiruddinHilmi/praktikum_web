
<?php
session_start();
require_once __DIR__ . '/../config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, nama_lengkap, email, password
         FROM users WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($data && password_verify($password, $data['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['email'] = $data['email'];

        header("Location: ../index.php");
        exit;

    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Medical Inventory System UMPKU</title>

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
                Medical Inventory
                <span>System.</span>
            </h1>

            <p class="brand-description">
                Sistem informasi pengelolaan dan pemantauan
                inventaris peralatan elektromedis Universitas
                Muhammadiyah PKU Surakarta.
            </p>

            <div class="brand-divider"></div>

            <div class="brand-features">

                <div class="brand-feature">
                    <span class="feature-icon">▤</span>
                    <div>
                        <strong>Data Inventaris</strong>
                        <small>Pengelolaan alat elektromedis</small>
                    </div>
                </div>

                <div class="brand-feature">
                    <span class="feature-icon">◷</span>
                    <div>
                        <strong>Monitoring Data</strong>
                        <small>Informasi inventaris terpusat</small>
                    </div>
                </div>

                <div class="brand-feature">
                    <span class="feature-icon">✓</span>
                    <div>
                        <strong>Akses Terintegrasi</strong>
                        <small>Sistem pengguna yang terkelola</small>
                    </div>
                </div>

            </div>

        </div>

        <div class="brand-footer">
            UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA
            <span>PROGRAM STUDI D4 ELEKTROMEDIS</span>
        </div>

    </section>


    <!-- LOGIN FORM -->
    <main class="auth-form-area">

        <div class="auth-card">

            <div class="auth-mobile-logo">
                <img src="../logo-umpku.png" alt="Logo UMPKU">
            </div>

            <div class="auth-heading">

                <span class="auth-eyebrow">
                    WELCOME BACK
                </span>

                <h2>Selamat Datang</h2>

                <p>
                    Silakan masuk untuk mengakses sistem inventaris.
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

                    <label for="email">Email</label>

                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
                            placeholder="Masukkan password"
                            autocomplete="current-password"
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

                </div>


                <button type="submit" class="auth-submit">

                    <span>LOGIN</span>
                    <span class="submit-arrow">→</span>

                </button>

            </form>


            <div class="auth-switch">
                Belum punya akun?
                <a href="register.php">Daftar sekarang</a>
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

// ANIMASI TOMBOL PASSWORD
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
