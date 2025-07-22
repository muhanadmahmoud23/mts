<?php

namespace App\repositories;

require_once 'interfaces\ProductRepositoryInterface.php';
require_once 'interfaces\DatabaseConnectionInterface.php';

use App\classes\DatabaseManager;
use App\interfaces\DatabaseConnectionInterface;
use PDO;
use App\interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private PDO $pdo;
    private $dbManager;
    private const TABLE_NAME = 'products';

    public function __construct(DatabaseConnectionInterface $db, DatabaseManager $dbManager)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
        $this->dbManager = $dbManager;
    }

    public function getOrCreate(string $name, int $categoryId, float $price): int
    {
        $id = $this->dbManager->get(self::TABLE_NAME, 'id', 'WHERE name = ?', [$name]);

        if (!$id)
            $id = $this->dbManager->Insert(self::TABLE_NAME, 'name, category_id, price', [$name, $categoryId, $price]);

        return $id;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS products");
    }
}
