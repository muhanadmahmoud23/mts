<?php

namespace App\classes;

require_once 'interfaces\DatabaseConnectionInterface.php';

use App\interfaces\DatabaseConnectionInterface;
use PDO;

class DatabaseManager
{
    private PDO $pdo;

    public function __construct(DatabaseConnectionInterface $db)
    {
        $db->connect();
        $this->pdo = $db->getPdo();
    }

    public function dropAllTables(): void
    {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS $table");
        }
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Insert($table,  $columns, array $values)
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($values);

        return (int) $this->pdo->lastInsertId();
    }

    public function get($table, $select, $where, $values)
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        $sql = "SELECT $select FROM $table";

        if (!empty($where))
            $sql .= " " . $where;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchColumn();
    }
}
