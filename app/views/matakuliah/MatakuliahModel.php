<?php

namespace App\Models;

use App\Core\Database;

class MatakuliahModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function all(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT 
                matakuliah.*,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
             FROM matakuliah
             JOIN prodi ON matakuliah.prodi_id = prodi.id
             ORDER BY matakuliah.id ASC"
        );

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT 
                matakuliah.*,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
             FROM matakuliah
             JOIN prodi ON matakuliah.prodi_id = prodi.id
             WHERE matakuliah.id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function create(
        string $kode,
        string $nama,
        int $sks,
        int $prodi_id
    ): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO matakuliah
                (kode, nama, sks, prodi_id)
             VALUES
                (:kode, :nama, :sks, :prodi_id)"
        );

        return $stmt->execute([
            'kode' => $kode,
            'nama' => $nama,
            'sks' => $sks,
            'prodi_id' => $prodi_id
        ]);
    }

    public function update(
        int $id,
        string $kode,
        string $nama,
        int $sks,
        int $prodi_id
    ): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE matakuliah
             SET kode = :kode,
                 nama = :nama,
                 sks = :sks,
                 prodi_id = :prodi_id
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'kode' => $kode,
            'nama' => $nama,
            'sks' => $sks,
            'prodi_id' => $prodi_id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM matakuliah WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}