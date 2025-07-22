<?php

namespace App\classes;
require_once 'interfaces\DatabaseConnectionInterface.php';

use App\interfaces\DatabaseConnectionInterface;
use App\migrations\CreateAddress;
use App\migrations\CreateCategory;
use App\migrations\CreateCustomer;
use App\migrations\CreateInvoice;
use App\migrations\CreateInvoiceDetails;
use App\migrations\CreateProduct;
use PDO;

class MigrationManager
{
    private PDO $pdo;

    public function __construct(DatabaseConnectionInterface $db)
    {
        $db->connect(); 
        $this->pdo = $db->getPdo();
    }

    public function run(): void
    {
        $migrations = [
            new CreateAddress,
            new CreateCustomer,
            new CreateCategory,
            new CreateProduct,
            new CreateInvoice,
            new CreateInvoiceDetails,
        ];

        foreach ($migrations as $migration) {
            $migration->migrate($this->pdo);
        }
    }
}
