<?php

namespace App\migrations;

use interfaces\MigrationInterface;
use PDO;

class CreateInvoice implements MigrationInterface
{
    public function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS invoices (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_id INTEGER,
                grand_total DOUBLE,
                date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id)
            )
        ");
    }
}
