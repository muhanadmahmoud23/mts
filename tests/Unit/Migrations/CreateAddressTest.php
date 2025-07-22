<?php

namespace tests\Unit\Migrations;

use PHPUnit\Framework\TestCase;
use App\migrations\CreateAddress;
use PDO;

class CreateAddressTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO("sqlite:database.sqlite"); // In This Case We Will Test Only ADDRESS CREATION with SQLLITE
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testMigrateCreatesAddressesTable(): void
    {
        $migration = new CreateAddress();
        $migration->migrate($this->pdo);

        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='addresses'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($result, 'addresses table was not created');

        $stmt = $this->pdo->query("PRAGMA table_info(addresses)");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $columnNames = array_column($columns, 'name');

        $this->assertContains('id', $columnNames);
        $this->assertContains('street_number', $columnNames);
        $this->assertContains('street_name', $columnNames);
    }
}
