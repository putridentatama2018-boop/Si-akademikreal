<?php

namespace App\Services;

use App\Repositories\MahasiswaRepository;

class MahasiswaService
{
    private MahasiswaRepository $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    // Menampilkan semua mahasiswa
    public function all(): array
    {
        return $this->repository->all();
    }

    // Mencari mahasiswa
    public function search(string $keyword): array
    {
        return $this->repository->search($keyword);
    }

    // Mencari mahasiswa berdasarkan ID
    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }

    // Menambahkan data mahasiswa
    public function create(array $data): array
    {
        // Validasi data
        $errors = $this->validate($data);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        try {

            $result = $this->repository->create(
                $data['nim'],
                $data['nama'],
                $data['email'],
                (int) $data['angkatan'],
                (int) $data['prodi_id'],
                $data['status'] ?? 'aktif'
            );

            if ($result) {
                return [
                    'success' => true,
                    'errors' => []
                ];
            }

            return [
                'success' => false,
                'errors' => ['Data gagal disimpan.']
            ];

        } catch (\PDOException $e) {

            // Menyimpan detail error ke file log
            error_log(
                date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL,
                3,
                __DIR__ . '/../../storage/logs/app.log'
            );

            // Pesan aman untuk pengguna
            return [
                'success' => false,
                'errors' => ['Data gagal disimpan.']
            ];
        }
    }

    // Mengubah data mahasiswa
    public function update(int $id, array $data): array
    {
        $errors = $this->validate($data);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        try {

            $result = $this->repository->update(
                $id,
                $data['nim'],
                $data['nama'],
                $data['email'],
                (int) $data['angkatan'],
                (int) $data['prodi_id'],
                $data['status'] ?? 'aktif'
            );

            return [
                'success' => $result,
                'errors' => $result
                    ? []
                    : ['Data mahasiswa gagal diubah.']
            ];

        } catch (\PDOException $e) {

            // Menyimpan detail error ke file log
            error_log(
                date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL,
                3,
                __DIR__ . '/../../storage/logs/app.log'
            );

            return [
                'success' => false,
                'errors' => ['Data mahasiswa gagal diubah.']
            ];
        }
    }

    // Menghapus data mahasiswa
    public function delete(int $id): bool
    {
        try {

            return $this->repository->delete($id);

        } catch (\PDOException $e) {

            // Menyimpan detail error ke file log
            error_log(
                date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL,
                3,
                __DIR__ . '/../../storage/logs/app.log'
            );

            return false;
            
        }
    }

    // Validasi data mahasiswa
    private function validate(array $data): array
    {
        $errors = [];

        // Validasi NIM
        if (empty(trim($data['nim'] ?? ''))) {

            $errors[] = 'NIM wajib diisi.';

        } else {

            // Mengecek NIM duplikat
            if ($this->repository->existsByNim(trim($data['nim']))) {
                $errors[] = 'NIM sudah terdaftar.';
            }
        }

        // Validasi nama
        if (empty(trim($data['nama'] ?? ''))) {
            $errors[] = 'Nama wajib diisi.';
        }

        // Validasi email
        if (empty(trim($data['email'] ?? ''))) {

            $errors[] = 'Email wajib diisi.';

        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

            $errors[] = 'Format email tidak valid.';
        }

        // Validasi angkatan
        if (empty($data['angkatan'])) {
            $errors[] = 'Angkatan wajib diisi.';
        }

        // Validasi program studi
        if (empty($data['prodi_id'])) {
            $errors[] = 'Program studi wajib dipilih.';
        }

        return $errors;
    }
}