<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;

// Hapus flash setelah ditampilkan
unset($_SESSION['flash']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .login-box {
            width: 350px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            padding: 8px 15px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h1>Login Sistem Akademik</h1>

    <?php if ($flash): ?>
        <div class="alert">
            <?= htmlspecialchars($flash) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login">

        <label>Username</label>
        <input
            type="text"
            name="username"
            required
        >

        <label>Password</label>
        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">
            Masuk
        </button>

    </form>

</div>

</body>
</html>