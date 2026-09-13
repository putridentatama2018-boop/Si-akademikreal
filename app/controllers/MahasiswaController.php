<?php

namespace App\Controllers;

use App\Services\MahasiswaService;

class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    // Menampilkan semua mahasiswa
    public function index()
    {
        $keyword = $_GET['q'] ?? '';

        if ($keyword !== '') {
            $mahasiswa = $this->service->search($keyword);
        } else {
            $mahasiswa = $this->service->all();
        }

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    // Form tambah mahasiswa
    public function create()
    {
        $prodiModel = new \App\Models\ProdiModel();

        $prodi = $prodiModel->all();

        $this->view('mahasiswa/create', [
            'prodi' => $prodi
        ]);
    }

    // store
        public function store()
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'status' => $_POST['status'] ?? 'aktif'
        ];

        $result = $this->service->create($data);

        if (!$result['success']) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => implode('<br>', $result['errors'])
            ];

            $this->redirect('/si-akademik/public/mahasiswa/create');
            return;
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data mahasiswa berhasil ditambahkan.'
        ];

        $this->redirect('/si-akademik/public/mahasiswa');
    }

    // Form edit mahasiswa
    public function edit(int $id)
    {
        $mahasiswa = $this->service->find($id);

        if (!$mahasiswa) {
            die('Data mahasiswa tidak ditemukan.');
        }

        $prodiModel = new \App\Models\ProdiModel();
        $prodi = $prodiModel->all();

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi
        ]);
    }

    // Mengubah mahasiswa
    public function update()
    {
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'status' => $_POST['status'] ?? 'aktif'
        ];

        $result = $this->service->update($id, $data);

        if (!$result['success']) {

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => implode('<br>', $result['errors'])
            ];

            $this->redirect('/si-akademik/public/mahasiswa/edit/' . $id);
            return;
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data mahasiswa berhasil diubah.'
        ];

        $this->redirect('/si-akademik/public/mahasiswa');
    }

    // Menghapus mahasiswa
    public function delete(int $id)
    {
        $result = $this->service->delete($id);

        if ($result) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Data mahasiswa berhasil dihapus.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data mahasiswa gagal dihapus.'
            ];
        }

        $this->redirect('/si-akademik/public/mahasiswa');
    }
}