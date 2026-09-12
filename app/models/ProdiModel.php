<?php

namespace App\Models;

use App\Core\Database;

class ProdiModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function all(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM prodi ORDER BY id ASC"
        );

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM prodi WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function create(string $kode, string $nama): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO prodi (kode, nama)
             VALUES (:kode, :nama)"
        );

        return $stmt->execute([
            'kode' => $kode,
            'nama' => $nama
        ]);
    }

    public function update(int $id, string $kode, string $nama): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE prodi
             SET kode = :kode,
                 nama = :nama
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'kode' => $kode,
            'nama' => $nama
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM prodi WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}