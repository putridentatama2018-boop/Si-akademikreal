<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}