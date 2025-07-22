<?php

namespace App\repositories;

require_once 'interfaces\InvoiceDetailRepositoryInterface.php';
require_once 'interfaces\DatabaseConnectionInterface.php';

use App\classes\DatabaseManager;
use App\interfaces\DatabaseConnectionInterface;
use PDO;
use App\interfaces\InvoiceDetailRepositoryInterface;

class InvoiceDetailRepository implements InvoiceDetailRepositoryInterface
{
    private PDO $pdo;
    private $dbManager;
    private const TABLE_NAME = 'invoice_details';

    public function __construct(DatabaseConnectionInterface $db, DatabaseManager $dbManager)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
        $this->dbManager = $dbManager;
    }

    public function insert(int $invoiceId, int $productId, float $price, int $quantity, float $total): void
    {
        $this->dbManager->Insert(self::TABLE_NAME, 'invoice_id, product_id, price, quantity, total', [$invoiceId, $productId, $price, $quantity, $total]);
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS invoice_details");
    }
}
