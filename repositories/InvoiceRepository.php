<?php

namespace App\repositories;

require_once 'interfaces/InvoiceRepositoryInterface.php';
require_once 'interfaces/DatabaseConnectionInterface.php';
require_once 'classes/DatabaseManager.php';

use App\classes\DatabaseManager;
use App\interfaces\DatabaseConnectionInterface;
use App\interfaces\InvoiceRepositoryInterface;
use PDO;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    private PDO $pdo;
    private $dbManager;
    private const TABLE_NAME = 'invoices';

    public function __construct(DatabaseConnectionInterface $db, DatabaseManager $dbManager)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
        $this->dbManager = $dbManager;
    }

    public function getOrCreate(string $invoiceNo, int $customerId, string $date, float $grandTotal): int
    {
        $id = $this->dbManager->get(self::TABLE_NAME, 'id', 'WHERE customer_id = ? AND date = ? AND grand_total = ?', [$customerId, $date, $grandTotal]);

        if (!$id)
            $id = $this->dbManager->Insert(self::TABLE_NAME, 'customer_id, date, grand_total', [$customerId, $date, $grandTotal]);

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
            invoice_details.price,
            categories.name as category_name
        FROM invoices
        JOIN customers ON invoices.customer_id = customers.id
        JOIN addresses ON customers.address_id = addresses.id
        JOIN invoice_details ON invoice_details.invoice_id = invoices.id
        JOIN products ON invoice_details.product_id = products.id
        JOIN categories ON products.category_id = categories.id
        ORDER BY invoices.id ASC
    ";

        return $this->dbManager->fetchAll($sql);
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS invoices");
    }
}
