<?php

require_once __DIR__ . '/../app/Core/Database.php';

header('Content-Type: application/json');

try {

    $db = \App\Core\Database::getInstance();

    // GET - Mengambil data mahasiswa
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $stmt = $db->query("
            SELECT
                id,
                nim,
                nama,
                email,
                prodi_id,
                angkatan,
                status
            FROM mahasiswa
            ORDER BY id ASC
        ");

        $data = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        exit;
    }

    // POST - Menambahkan mahasiswaa
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Membaca data JSON dari Postman
        $input = json_decode(file_get_contents('php://input'), true);

        // Cek JSON
        if (!$input) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Data JSON tidak valid',
                'data' => null
            ]);

            exit;

            
        }

        // Ambil data
        $nim = $input['nim'] ?? '';
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $prodi_id = $input['prodi_id'] ?? '';
        $angkatan = $input['angkatan'] ?? '';
        $status = $input['status'] ?? 'aktif';

        // Validasi data wajib
        if (
            $nim === '' ||
            $nama === '' ||
            $email === '' ||
            $prodi_id === '' ||
            $angkatan === ''
        ) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Data mahasiswa belum lengkap',
                'data' => null
            ]);

            exit;
        }

        // Cek NIM sudah ada atau belum
        $cek = $db->prepare("
            SELECT id
            FROM mahasiswa
            WHERE nim = ?
        ");

        $cek->execute([$nim]);

        if ($cek->fetch()) {

            http_response_code(409);

            echo json_encode([
                'success' => false,
                'message' => 'NIM sudah terdaftar',
                'data' => null
            ]);

            exit;
        }

        // Simpan data mahasiswa
        $stmt = $db->prepare("
            INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $nim,
            $nama,
            $email,
            $prodi_id,
            $angkatan,
            $status
        ]);

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => [
                'id' => $db->lastInsertId(),
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'prodi_id' => $prodi_id,
                'angkatan' => $angkatan,
                'status' => $status
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        exit;
    }

    // Method tidak didukung
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan',
        'data' => null
    ]);

} catch (\PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server',
        'data' => null
    ]);
}