<?php

use app\core\middleware\authmiddleware;

$routes = [

    'GET' => [

        // =========================
        // HALAMAN UMUM
        // =========================

        '/' => [
            'HomeController',
            'index'
        ],

        // =========================
        // LOGIN
        // =========================

        '/login' => [
            'AuthController',
            'loginForm'
        ],

        '/logout' => [
            'AuthController',
            'logout'
        ],

        // =========================
        // DASHBOARD
        // =========================

        '/dashboard' => [
            'DashboardController',
            'index',
            [authmiddleware::class]
        ],

        // =========================
        // MAHASISWA
        // =========================

        '/mahasiswa' => [
            'MahasiswaController',
            'index',
            [authmiddleware::class]
        ],

        '/api/mahasiswa' => [
            'MahasiswaApiController',
            'index'
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

        // Route lama delete melalui GET
        '/mahasiswa/delete/{id}' => [
            'MahasiswaController',
            'delete',
            [authmiddleware::class]
        ],


        // =========================
        // PRODI
        // =========================

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


        // =========================
        // MATA KULIAH
        // =========================

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


    // =========================
    // POST
    // =========================

    'POST' => [

        // LOGIN
        '/login' => [
            'AuthController',
            'login'
        ],

        // REGISTER
        '/register' => [
            'AuthController',
            'register'
        ],

        // MAHASISWA
        '/mahasiswa' => [
            'MahasiswaController',
            'store',
            [authmiddleware::class]
        ],

        // Route lama untuk form edit
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

    ],


    // =========================
    // PUT
    // ACARA 27 - UPDATE MAHASISWA
    // =========================

    'PUT' => [

        '/mahasiswa' => [
            'MahasiswaController',
            'update',
            [authmiddleware::class]
        ],

    ],


    // =========================
    // DELETE
    // ACARA 27 - DELETE MAHASISWA
    // =========================

   'DELETE' => [

    '/mahasiswa' => [
        'MahasiswaController',
        'destroy',
        [authmiddleware::class]
    ],

],

];