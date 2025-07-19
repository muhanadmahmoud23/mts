<?php

namespace App\Classes;

use PDO;

class CategoryModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255)
            )
        ");
    }

    public function getOrCreate(string $name): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            $id = $this->pdo->lastInsertId();
        }

        return $id;
    }

    public function deleteAll()
    {
        $this->pdo->exec("DROP TABLE IF EXISTS categories");
    }
}
