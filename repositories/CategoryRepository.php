<?php

namespace App\repositories;

require_once 'interfaces\CategoryRepositoryInterface.php';
require_once 'interfaces\DatabaseConnectionInterface.php';

use App\classes\DatabaseManager;
use App\interfaces\CategoryRepositoryInterface;
use App\interfaces\DatabaseConnectionInterface;
use PDO;

class CategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;
    private $dbManager;
    private const TABLE_NAME = 'categories';

    public function __construct(DatabaseConnectionInterface $db, DatabaseManager $dbManager)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
        $this->dbManager = $dbManager;
    }

    public function getOrCreate(string $name): int
    {
        $id = $this->dbManager->get('categories', 'id', 'WHERE name LIKE ?', ["%$name%"]);

        if (!$id)
            $id = $this->dbManager->Insert(self::TABLE_NAME, 'name', [$name]);

        return $id;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS categories");
    }
}
