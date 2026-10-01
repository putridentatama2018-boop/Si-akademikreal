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

        .login-left .brand {
            position: absolute;
            top: 40px;
            left: 40px;
        }

        .login-left .brand-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .login-left .tagline {
            position: absolute;
            bottom: 48px;
            left: 40px;
            color: var(--primary-dark);
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.6;
            max-width: 260px;
        }

        .illustration-container {
            width: 100%;
            max-width: 420px;
        }

        .illustration-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* ======= Right Side ======= */
        .login-right {
            width: 65%;
            margin-left: -5%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 80px;
            background: var(--white);
            border-radius: 40px 0 0 40px;
            box-shadow: -1px 0 30px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .login-form-box {
            width: 100%;
            max-width: 400px;
        }

        .login-form-box h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 40px;
        }

        /* ======= Flash Alert ======= */
        .alert {
            padding: 12px 16px;
            margin-bottom: 24px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* ======= Inputs ======= */
        .form-group {
            position: relative;
            margin-bottom: 28px;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 6px;
            border: none;
            border-bottom: 1.5px solid var(--border-color);
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: transparent;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .form-group input:focus {
            border-bottom-color: var(--primary);
        }

        .form-group .toggle-password {
            position: absolute;
            right: 4px;
            bottom: 10px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 4px;
            transition: color 0.3s;
        }

        .form-group .toggle-password:hover {
            color: var(--primary);
        }

        /* ======= Options Row ======= */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            margin-top: -4px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* ======= Button ======= */
        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: var(--white);
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.3s, box-shadow 0.3s;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 16px rgba(214, 216, 218, 0.3);
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        /* ======= Responsive ======= */
        @media (max-width: 900px) {
            .login-left {
                display: none;
            }

            .login-right {
                flex: 1;
                padding: 40px 24px;
            }
        }

        @media (max-width: 480px) {
            .login-form-box h1 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- Left Side -->
    <div class="login-left">

        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
        </div>

        <div class="illustration-container">
            <img
                src="<?= BASE_URL ?>/assets/images/login.png"
                alt="Ilustrasi Sistem Akademik"
            >
        </div>

        <div class="tagline">
            Sistem Informasi<br>Akademik.
        </div>

    </div>

    <!-- Right Side -->
    <div class="login-right">

        <div class="login-form-box">

            <h1>Login</h1>

            <?php if ($flash): ?>
                <?php
                    $alertType = 'alert-danger';
                    $alertMessage = '';

                    if (is_array($flash)) {
                        $alertType = ($flash['type'] === 'success') ? 'alert-success' : 'alert-danger';
                        $alertMessage = $flash['message'];
                    } else {
                        $alertMessage = $flash;
                    }
                ?>
                <div class="alert <?= $alertType ?>">
                    <?= htmlspecialchars($alertMessage) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login" id="loginForm">

                <div class="form-group">
                    <label for="username">Email</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        placeholder="Masukkan email"
                        required
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                    >
                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Toggle password visibility"
                    >
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        const icon = this.querySelector('i');
        icon.classList.toggle('bi-eye-slash');
        icon.classList.toggle('bi-eye');
    });
</script>

</body>
</html>