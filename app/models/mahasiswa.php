<?php

namespace App\Models;

class MahasiswaModel extends Model
{
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
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function nimExists(string $nim, ?int $excludeId = null): bool
{
    if ($excludeId !== null) {
        $sql = "
            SELECT COUNT(*)
            FROM mahasiswa
            WHERE nim = :nim
            AND id != :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'nim' => $nim,
            'id' => $excludeId
        ]);
    } else {
        $sql = "
            SELECT COUNT(*)
            FROM mahasiswa
            WHERE nim = :nim
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'nim' => $nim
        ]);
    }

    return (int) $stmt->fetchColumn() > 0;
}

 public function create(
    string $nim,
    string $nama,
    string $email,
    int $angkatan,
    int $prodi_id,
    string $status = 'aktif'
): bool {

    // Cek apakah NIM sudah digunakan
    if ($this->nimExists($nim)) {
        return false;
    }

    $sql = "
        INSERT INTO mahasiswa
        (nim, nama, email, angkatan, prodi_id, status)
        VALUES
        (:nim, :nama, :email, :angkatan, :prodi_id, :status)
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'nim' => $nim,
        'nama' => $nama,
        'email' => $email,
        'angkatan' => $angkatan,
        'prodi_id' => $prodi_id,
        'status' => $status
    ]);
}
    public function update(
        int $id,
        string $nim,
        string $nama,
        string $email,
        int $angkatan,
        int $prodi_id,
        string $status
    ): bool {
        // Cek apakah NIM sudah digunakan mahasiswa lain
        if ($this->nimExists($nim, $id)) {
            return false;
        }
        $sql = "
            UPDATE mahasiswa
            SET
                nim = :nim,
                nama = :nama,
                email = :email,
                angkatan = :angkatan,
                prodi_id = :prodi_id,
                status = :status
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
            'status' => $status
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM mahasiswa WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}