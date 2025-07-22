<?php

namespace App\repositories;

require_once 'interfaces\AddressRepositoryInterface.php';
require_once 'interfaces\DatabaseConnectionInterface.php';

use App\classes\DatabaseManager;
use App\interfaces\AddressRepositoryInterface;
use App\interfaces\DatabaseConnectionInterface;
use PDO;

class AddressRepository implements AddressRepositoryInterface
{
    private PDO $pdo;
    private $dbManager;
    private const TABLE_NAME = 'addresses';

    public function __construct(DatabaseConnectionInterface $db, DatabaseManager $dbManager)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
        $this->dbManager = $dbManager;
    }

    public function getOrCreate(string $number, string $name): int
    {
        $id = $this->dbManager->get(self::TABLE_NAME, 'id', 'WHERE street_number = ? AND street_name = ?', [$number, $name]);

        if (!$id)
            $id = $this->dbManager->Insert(self::TABLE_NAME, 'street_number, street_name', [$number, $name]);

        return $id;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS addresses");
    }
}
