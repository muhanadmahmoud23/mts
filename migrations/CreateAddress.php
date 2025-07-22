<?php

namespace App\migrations;

require_once 'interfaces\MigrationInterface.php';

use interfaces\MigrationInterface;
use PDO;

class CreateAddress implements MigrationInterface
{
    public function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS addresses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                street_number VARCHAR(20),
                street_name VARCHAR(255)
            )
        ");
    }
}
