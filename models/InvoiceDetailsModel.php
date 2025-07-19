<?php

namespace App\Classes;

use PDO;

class InvoiceDetailModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create()
    {
        $this->pdo->exec("
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

    public function insert(int $invoiceId, int $productId, float $price, int $quantity, float $total): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO invoice_details (invoice_id, product_id, price, quantity, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$invoiceId, $productId, $price, $quantity, $total]);
    }

    public function deleteAll()
    {
        $this->pdo->exec("DROP TABLE IF EXISTS invoice_details");
    }
}
