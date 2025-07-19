<?php

namespace App\Classes;

use PDO;

class AddressModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS addresses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                street_number VARCHAR(20),
                street_name VARCHAR(255)
            )
        ");
    }

    public function getOrCreate(string $number, string $name): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM addresses WHERE street_number = ? AND street_name = ?");
        $stmt->execute([$number, $name]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO addresses (street_number, street_name) VALUES (?, ?)");
            $stmt->execute([$number, $name]);
            $id = $this->pdo->lastInsertId();
        }

        return $id;
    }

    public function deleteAll()
    {
        $this->pdo->exec("DROP TABLE IF EXISTS addresses");
    }
}
