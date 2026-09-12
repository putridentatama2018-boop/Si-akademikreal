<?php

use app\core\middleware\authmiddleware;

$routes = [

    'GET' => [

        // Halaman umum
        '/' => ['HomeController', 'index'],

        // Halaman login
        '/login' => ['AuthController', 'loginForm'],

        // Logout
        '/logout' => ['AuthController', 'logout'],

        // Dashboard
        '/dashboard' => [
            'DashboardController',
            'index',
            [authmiddleware::class]
        ],

        // MAHASISWA
        '/mahasiswa' => [
            'MahasiswaController',
            'index',
            [authmiddleware::class]
        ],

        '/mahasiswa/create' => [
            'MahasiswaController',
            'create',
            [authmiddleware::class]
        ],

        '/mahasiswa/edit/{id}' => [
            'MahasiswaController',
            'edit',
            [authmiddleware::class]
        ],

        '/mahasiswa/delete/{id}' => [
            'MahasiswaController',
            'delete',
            [authmiddleware::class]
        ],

        // PRODI
        '/prodi' => [
            'ProdiController',
            'index',
            [authmiddleware::class]
        ],

        '/prodi/create' => [
            'ProdiController',
            'create',
            [authmiddleware::class]
        ],

        '/prodi/edit/{id}' => [
            'ProdiController',
            'edit',
            [authmiddleware::class]
        ],

        // MATA KULIAH
        '/matakuliah' => [
            'MatakuliahController',
            'index',
            [authmiddleware::class]
        ],

        '/matakuliah/create' => [
            'MatakuliahController',
            'create',
            [authmiddleware::class]
        ],
    ],

    'POST' => [

        // Login
        '/login' => [
            'AuthController',
            'login'
        ],

        // MAHASISWA
        '/mahasiswa' => [
            'MahasiswaController',
            'store',
            [authmiddleware::class]
        ],

        '/mahasiswa/update' => [
            'MahasiswaController',
            'update',
            [authmiddleware::class]
        ],

        // PRODI
        '/prodi' => [
            'ProdiController',
            'store',
            [authmiddleware::class]
        ],

        // MATA KULIAH
        '/matakuliah' => [
            'MatakuliahController',
            'store',
            [authmiddleware::class]
        ],
    ]
];