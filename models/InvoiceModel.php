<?php

namespace App\Classes;

use PDO;

class InvoiceModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create()
    {
        $this->pdo->exec("
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

    public function getOrCreate(string $invoiceNo, int $customerId, string $date, float $grandTotal): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM invoices WHERE customer_id = ? AND date = ? AND grand_total = ?");
        $stmt->execute([$customerId, $date, $grandTotal]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO invoices (customer_id, date, grand_total) VALUES (?, ?, ?)");
            $stmt->execute([$customerId, $date, $grandTotal]);
            $id = $this->pdo->lastInsertId();
        }

        return $id;
    }

    public function getAllInvoicesWithDetails(): array
    {
        $sql = "
        SELECT 
            invoices.id AS invoice_id,
            invoices.date AS invoice_date,
            customers.name AS customer_name,
            addresses.street_number || ' ' || addresses.street_name AS customer_address,
            products.name AS product_name,
            invoice_details.quantity,
            invoice_details.price
        FROM invoices
        JOIN customers ON invoices.customer_id = customers.id
        JOIN addresses ON customers.address_id = addresses.id
        JOIN invoice_details ON invoice_details.invoice_id = invoices.id
        JOIN products ON invoice_details.product_id = products.id
        ORDER BY invoices.id ASC
    ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAll()
    {
        $this->pdo->exec("DROP TABLE IF EXISTS invoices");
    }
}
