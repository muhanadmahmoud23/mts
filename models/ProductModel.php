<?php

namespace App\Classes;

use PDO;

class ProductModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER,
                name VARCHAR(255),
                price DOUBLE,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )
        ");
    }

    public function getOrCreate(string $name, int $categoryId, float $price): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->execute([$name]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO products (name, category_id, price) VALUES (?, ?, ?)");
            $stmt->execute([$name, $categoryId, $price]);
            $id = $this->pdo->lastInsertId();
        }

        return $id;
    }

    public function deleteAll()
    {
        $this->pdo->exec("DROP TABLE IF EXISTS products");
    }
}
