<?php

namespace App\classes;

use PDO;

class Database
{
    private ?PDO $pdo = null;

    public function connect(): void
    {
        if (!$this->pdo) {
            $this->pdo = new PDO("sqlite:database.sqlite");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec('PRAGMA busy_timeout = 5000000');
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function close(): void
    {
        $this->pdo = null;
    }
}
