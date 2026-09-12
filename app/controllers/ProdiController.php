<?php

namespace App\Controllers;

use App\Models\ProdiModel;

class ProdiController
{
    public function index()
    {
        $prodiModel = new ProdiModel();

        $prodi = $prodiModel->all();

        include __DIR__ . '/../Views/prodi/index.php';
    }

    public function create()
    {
        include __DIR__ . '/../Views/prodi/create.php';
    }

    public function store()
    {
        $kode = $_POST['kode'] ?? '';
        $nama = $_POST['nama'] ?? '';

        $prodiModel = new ProdiModel();

        $prodiModel->create($kode, $nama);

        header('Location: /si-akademik/public/prodi');
        exit();
    }

    public function edit(int $id)
    {
        $prodiModel = new ProdiModel();

        $prodi = $prodiModel->find($id);

        if (!$prodi) {
            http_response_code(404);
            echo "Data Prodi tidak ditemukan";
            exit();
        }

        include __DIR__ . '/../Views/prodi/edit.php';
    }

    public function update(int $id)
    {
        $kode = $_POST['kode'] ?? '';
        $nama = $_POST['nama'] ?? '';

        $prodiModel = new ProdiModel();

        $prodiModel->update($id, $kode, $nama);

        header('Location: /si-akademik/public/prodi');
        exit();
    }

    public function delete(int $id)
    {
        $prodiModel = new ProdiModel();

        $prodiModel->delete($id);

        header('Location: /si-akademik/public/prodi');
        exit();
    }
}