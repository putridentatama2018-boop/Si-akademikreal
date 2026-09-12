<?php

namespace App\Controllers;

use App\Models\MatakuliahModel;

class MatakuliahController
{
    public function index()
    {
        $matakuliahModel = new MatakuliahModel();

        $matakuliah = $matakuliahModel->all();

        include __DIR__ . '/../Views/matakuliah/index.php';
    }

    public function create()
    {
        $prodiModel = new \App\Models\ProdiModel();
        $prodi = $prodiModel->all();

        include __DIR__ . '/../Views/matakuliah/create.php';
    }

    public function store()
    {
        $kode = $_POST['kode'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodi_id = (int) ($_POST['prodi_id'] ?? 0);

        $matakuliahModel = new MatakuliahModel();

        $matakuliahModel->create(
            $kode,
            $nama,
            $sks,
            $prodi_id
        );

        header('Location: /si-akademik/public/matakuliah');
        exit();
    }

   public function edit(int $id)
    {
        $matakuliahModel = new MatakuliahModel();
        $matakuliah = $matakuliahModel->find($id);

        if (!$matakuliah) {
            http_response_code(404);
            echo "Data Mata Kuliah tidak ditemukan";
            exit();
        }

        $prodiModel = new \App\Models\ProdiModel();
        $prodi = $prodiModel->all();

        include __DIR__ . '/../Views/matakuliah/edit.php';
    }

    public function update(int $id)
    {
        $kode = $_POST['kode'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodi_id = (int) ($_POST['prodi_id'] ?? 0);

        $matakuliahModel = new MatakuliahModel();

        $matakuliahModel->update(
            $id,
            $kode,
            $nama,
            $sks,
            $prodi_id
        );

        header('Location: /si-akademik/public/matakuliah');
        exit();
    }

    public function delete(int $id)
    {
        $matakuliahModel = new MatakuliahModel();

        $matakuliahModel->delete($id);

        header('Location: /si-akademik/public/matakuliah');
        exit();
    }
}