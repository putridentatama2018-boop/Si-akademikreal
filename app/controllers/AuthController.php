<?php

namespace App\Controllers;

class AuthController
{
    // Menampilkan form login
    public function loginForm(): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    // Proses login
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Hardcode username dan password
        if ($username === 'admin' && $password === 'admin123') {

            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';

            // Flash message
            $_SESSION['flash'] = 'Selamat datang, Admin';

            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Jika login gagal
        $_SESSION['flash'] = 'Username atau password salah';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    // Logout
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Hapus status login
        $_SESSION = [];

        // Flash message setelah logout
        $_SESSION['flash'] = 'Anda telah logout';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}