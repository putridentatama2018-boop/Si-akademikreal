<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Buku
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT
                id,
                judul,
                penulis,
                penerbit,
                tahun_terbit
            FROM buku
            ORDER BY id ASC
        ");

        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                judul,
                penulis,
                penerbit,
                tahun_terbit
            FROM buku
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }
}