<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;

// Hapus flash message setelah dibaca
unset($_SESSION['flash']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>

    <h1>Dashboard</h1>

    <?php if ($flash): ?>
        <div style="
            padding: 10px;
            background: #d4edda;
            color: #155724;
            margin-bottom: 20px;
        ">
            <?= htmlspecialchars($flash) ?>
        </div>
    <?php endif; ?>

    <p>Selamat datang di Sistem Akademik.</p>

    <a href="<?= BASE_URL ?>/mahasiswa">
        Data Mahasiswa
    </a>

    <br><br>

    <a href="<?= BASE_URL ?>/logout" style="display: inline-block; background-color: #dc3545; color: #ffffff; padding: 6px 16px; border-radius: 4px; text-decoration: none; font-weight: 500;">
        Logout
    </a>

</body>
</html>