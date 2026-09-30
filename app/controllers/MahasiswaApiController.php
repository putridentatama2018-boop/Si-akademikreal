<?php

namespace App\Controllers;

use App\Repositories\MahasiswaRepository;
use App\Core\Response;

class MahasiswaApiController

{
    private MahasiswaRepository $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    // GET /api/mahasiswa
  public function index()
{
    $data = $this->repository->all();

    Response::json(
        200,
        true,
        'Data mahasiswa berhasil diambil',
        $data
    );
}

    // GET /api/mahasiswa/{id}
 public function show(int $id)
{
    $data = $this->repository->find($id);

    if (!$data) {
        Response::json(
            404,
            false,
            'Data mahasiswa tidak ditemukan'
        );
    }

    Response::json(
        200,
        true,
        'Data mahasiswa berhasil diambil',
        $data
    );
}

    // POST /api/mahasiswa
public function store()
{
    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $errors = [];

    if (empty($input['nim'])) {
        $errors['nim'] = 'NIM wajib diisi';
    }

    if (empty($input['nama'])) {
        $errors['nama'] = 'Nama wajib diisi';
    }

    if (empty($input['email'])) {
        $errors['email'] = 'Email wajib diisi';
    }

    if (empty($input['angkatan'])) {
        $errors['angkatan'] = 'Angkatan wajib diisi';
    }

    if (empty($input['prodi_id'])) {
        $errors['prodi_id'] = 'Program studi wajib diisi';
    }

    if (!empty($errors)) {
        Response::json(
            422,
            false,
            'Validasi gagal',
            null,
            $errors
        );
    }

    try {
        $result = $this->repository->create(
            $input['nim'],
            $input['nama'],
            $input['email'],
            (int) $input['angkatan'],
            (int) $input['prodi_id'],
            $input['status'] ?? 'aktif'
        );

        if ($result) {
            Response::json(
                201,
                true,
                'Data mahasiswa berhasil ditambahkan'
            );
        }

        Response::json(
            500,
            false,
            'Data mahasiswa gagal ditambahkan'
        );

    } catch (\PDOException $e) {
        Response::json(
            500,
            false,
            'Terjadi kesalahan pada server'
        );
    }
}

    // PUT /api/mahasiswa/{id}
public function update(int $id)
{
    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $errors = [];

    if (empty($input['nim'])) {
        $errors['nim'] = 'NIM wajib diisi';
    }

    if (empty($input['nama'])) {
        $errors['nama'] = 'Nama wajib diisi';
    }

    if (empty($input['email'])) {
        $errors['email'] = 'Email wajib diisi';
    }

    if (empty($input['angkatan'])) {
        $errors['angkatan'] = 'Angkatan wajib diisi';
    }

    if (empty($input['prodi_id'])) {
        $errors['prodi_id'] = 'Program studi wajib diisi';
    }

    if (!empty($errors)) {
        Response::json(
            422,
            false,
            'Validasi gagal',
            null,
            $errors
        );
    }

    $mahasiswa = $this->repository->find($id);

    if (!$mahasiswa) {
        Response::json(
            404,
            false,
            'Data mahasiswa tidak ditemukan'
        );
    }

    try {
        $result = $this->repository->update(
            $id,
            $input['nim'],
            $input['nama'],
            $input['email'],
            (int) $input['angkatan'],
            (int) $input['prodi_id'],
            $input['status'] ?? 'aktif'
        );

        if ($result) {
            Response::json(
                200,
                true,
                'Data mahasiswa berhasil diubah'
            );
        }

        Response::json(
            500,
            false,
            'Data mahasiswa gagal diubah'
        );

    } catch (\PDOException $e) {
        Response::json(
            500,
            false,
            'Terjadi kesalahan pada server'
        );
    }
}

   // DELETE /api/mahasiswa/{id}
public function destroy(int $id)
{
    $mahasiswa = $this->repository->find($id);

    if (!$mahasiswa) {
        Response::json(
            404,
            false,
            'Data mahasiswa tidak ditemukan'
        );
    }

    try {
        $result = $this->repository->delete($id);

        if ($result) {
            Response::json(
                200,
                true,
                'Data mahasiswa berhasil dihapus'
            );
        }

        Response::json(
            500,
            false,
            'Data mahasiswa gagal dihapus'
        );

    } catch (\PDOException $e) {
        Response::json(
            500,
            false,
            'Terjadi kesalahan pada server'
        );
    }
}
}