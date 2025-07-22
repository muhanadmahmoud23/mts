<?php

namespace App\migrations;

use interfaces\MigrationInterface;
use PDO;

class CreateCategory implements MigrationInterface
{
    public function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255)
            )
        ");
    }
}
