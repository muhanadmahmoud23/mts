<?php

namespace App\Classes;

use PDO;

class CustomerModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                address_id INTEGER,
                FOREIGN KEY (address_id) REFERENCES addresses(id)
            )
        ");
    }

    public function getOrCreate(string $name, int $addressId): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM customers WHERE name = ? AND address_id = ?");
        $stmt->execute([$name, $addressId]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO customers (name, address_id) VALUES (?, ?)");
            $stmt->execute([$name, $addressId]);
            $id = $this->pdo->lastInsertId();
        }

        return $id;
    }

    public function deleteAll()
    {
        $this->pdo->exec("DROP TABLE IF EXISTS customers");
    }
}
