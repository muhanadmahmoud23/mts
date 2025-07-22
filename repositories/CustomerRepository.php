<?php

namespace App\repositories;

require_once 'interfaces\CustomerRepositoryInterface.php';
require_once 'interfaces\DatabaseConnectionInterface.php';

use App\classes\DatabaseManager;
use App\interfaces\CustomerRepositoryInterface;
use App\interfaces\DatabaseConnectionInterface;
use PDO;

class CustomerRepository implements CustomerRepositoryInterface
{
    private PDO $pdo;
    private $dbManager;
    private const TABLE_NAME = 'customers';

    public function __construct(DatabaseConnectionInterface $db, DatabaseManager $dbManager)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
        $this->dbManager = $dbManager;
    }

    public function getOrCreate(string $name, int $addressId): int
    {
        $id = $this->dbManager->get(self::TABLE_NAME, 'id', 'WHERE name = ? AND address_id = ?', [$name,$addressId]);

        if (!$id)
            $id = $this->dbManager->Insert(self::TABLE_NAME, 'name, address_id', [$name, $addressId]);


        return $id;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS customers");
    }
}
