<?php

namespace App\migrations;

use interfaces\MigrationInterface;
use PDO;

class CreateProduct implements MigrationInterface
{
    public function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER,
                name VARCHAR(255),
                price DOUBLE,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )
        ");
    }
}
