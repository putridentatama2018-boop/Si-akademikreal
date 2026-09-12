<?php

namespace App\Controllers;

use App\Repositories\MahasiswaRepository;

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $keyword = $_GET['q'] ?? '';

        if ($keyword !== '') {
            $mahasiswa = $this->repository->search($keyword);
        } else {
            $mahasiswa = $this->repository->all();
        }

        $this->view('mahasiswa/index', [
        'mahasiswa' => $mahasiswa
    ]);
    }

    public function create()
    {
        $prodiModel = new \App\Models\ProdiModel();

        $prodi = $prodiModel->all();

     $this->view('mahasiswa/create', [
    'prodi' => $prodi
    ]);
    }

    public function store()
    {
        $this->repository->create(
            $_POST['nim'],
            $_POST['nama'],
            $_POST['email'],
            (int) $_POST['angkatan'],
            (int) $_POST['prodi_id'],
            $_POST['status']
        );

        $this->redirect('/si-akademik/public/mahasiswa');
    }

    public function edit(int $id)
    {
        $mahasiswa = $this->repository->find($id);

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

    public function update()
    {
        $id = (int) ($_POST['id'] ?? 0);

        $this->repository->update(
            $id,
            $_POST['nim'],
            $_POST['nama'],
            $_POST['email'],
            (int) $_POST['angkatan'],
            (int) $_POST['prodi_id'],
            $_POST['status']
        );

        $this->redirect('/si-akademik/public/mahasiswa');
    }

   public function delete(int $id)
    {
    $this->repository->delete($id);

    $this->redirect('/si-akademik/public/mahasiswa');
    }
}