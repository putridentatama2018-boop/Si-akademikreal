<?php

namespace App\Repositories;

use PDO;

class MahasiswaRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Menampilkan semua mahasiswa
    public function all(): array
    {
        $sql = "
            SELECT
                mahasiswa.id,
                mahasiswa.nim,
                mahasiswa.nama,
                mahasiswa.email,
                mahasiswa.angkatan,
                mahasiswa.status,
                mahasiswa.prodi_id,
                mahasiswa.foto,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
            ORDER BY mahasiswa.id ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Mencari mahasiswa berdasarkan ID
    public function find(int $id): ?array
    {
        $sql = "
            SELECT
                mahasiswa.id,
                mahasiswa.nim,
                mahasiswa.nama,
                mahasiswa.email,
                mahasiswa.angkatan,
                mahasiswa.status,
                mahasiswa.prodi_id,
                mahasiswa.foto,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    // Mencari mahasiswa berdasarkan NIM atau nama
    public function search(string $keyword): array
    {
        $sql = "
            SELECT
                mahasiswa.id,
                mahasiswa.nim,
                mahasiswa.nama,
                mahasiswa.email,
                mahasiswa.angkatan,
                mahasiswa.status,
                mahasiswa.prodi_id,
                mahasiswa.foto,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.nim LIKE :keyword_nim
               OR mahasiswa.nama LIKE :keyword_nama
            ORDER BY mahasiswa.id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'keyword_nim' => '%' . $keyword . '%',
            'keyword_nama' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchAll();
    }

    // Mengecek apakah NIM sudah terdaftar
    public function existsByNim(string $nim, ?int $excludeId = null): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM mahasiswa
            WHERE nim = :nim
        ";

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->db->prepare($sql);

        $params = [
            'nim' => $nim
        ];

        if ($excludeId !== null) {
            $params['exclude_id'] = $excludeId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Menambahkan mahasiswa
    public function create(
        string $nim,
        string $nama,
        string $email,
        int $angkatan,
        int $prodi_id,
        string $status = 'aktif',
        ?string $foto = null
    ): bool {
        $sql = "
            INSERT INTO mahasiswa
            (
                nim,
                nama,
                email,
                angkatan,
                prodi_id,
                status,
                foto
            )
            VALUES
            (
                :nim,
                :nama,
                :email,
                :angkatan,
                :prodi_id,
                :status,
                :foto
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'angkatan' => $angkatan,
            'prodi_id' => $prodi_id,
            'status' => $status,
            'foto' => $foto
        ]);
    }

    // Mengubah mahasiswa
    public function update(
        int $id,
        string $nim,
        string $nama,
        string $email,
        int $angkatan,
        int $prodi_id,
        string $status,
        ?string $foto = null
    ): bool {
        $sql = "
            UPDATE mahasiswa
            SET
                nim = :nim,
                nama = :nama,
                email = :email,
                angkatan = :angkatan,
                prodi_id = :prodi_id,
                status = :status,
                foto = :foto
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'angkatan' => $angkatan,
            'prodi_id' => $prodi_id,
            'status' => $status,
            'foto' => $foto
        ]);
    }

    // Menghapus mahasiswa
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM mahasiswa
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }
}