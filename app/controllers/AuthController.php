<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Response;

require_once __DIR__ . '/../models/user.php';

class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // Menampilkan form login
    public function loginForm(): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    // Proses login web
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Login sementara menggunakan akun hardcode
        if ($username === 'admin' && $password === 'admin123') {

            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Selamat datang, Admin'
            ];

            header('Location: ' . BASE_URL . '/mahasiswa');
            exit;
        }

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

        $_SESSION = [];

        $_SESSION['flash'] = 'Anda telah logout';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    // API Register - Acara 19
    public function register(): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        // Validasi JSON
        if (!is_array($data)) {
            Response::json(
                400,
                false,
                'Format JSON tidak valid'
            );
            return;
        }

        // Validasi data wajib
        if (
            empty($data['username']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            Response::json(
                400,
                false,
                'Username, email, dan password wajib diisi'
            );
            return;
        }

        // Validasi email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Response::json(
                400,
                false,
                'Format email tidak valid'
            );
            return;
        }

        // Hash password
        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        // Simpan user
        $id = $this->model->create($data);

        // Response berhasil
        Response::json(
            201,
            true,
            'Registrasi berhasil',
            [
                'id' => $id
            ]
        );
    }
}