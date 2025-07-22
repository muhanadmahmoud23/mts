<?php

namespace App\migrations;

use interfaces\MigrationInterface;
use PDO;

class CreateInvoiceDetails implements MigrationInterface
{
    public function migrate(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS invoice_details (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                invoice_id INTEGER,
                product_id INTEGER,
                price DOUBLE,
                quantity INTEGER,
                total DOUBLE,
                FOREIGN KEY (invoice_id) REFERENCES invoices(id),
                FOREIGN KEY (product_id) REFERENCES products(id)
            )
        ");
    }
}
