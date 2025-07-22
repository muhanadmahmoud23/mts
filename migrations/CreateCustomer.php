<?php

namespace App\migrations;

use interfaces\MigrationInterface;
use PDO;

class CreateCustomer implements MigrationInterface
{
    public function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                address_id INTEGER,
                FOREIGN KEY (address_id) REFERENCES addresses(id)
            )
        ");
    }
}
