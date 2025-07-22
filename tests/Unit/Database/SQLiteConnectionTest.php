<?php

namespace tests\Unit\DB;

require 'classes\SQLiteConnection.php';

use PHPUnit\Framework\TestCase;
use App\classes\SQLiteConnection;
use PDO;

class SQLiteConnectionTest extends TestCase
{
    public function testConnectEstablishesConnection(): void
    {
        $connection = new SQLiteConnection();
        $connection->connect();

        $this->assertInstanceOf(PDO::class, $connection->getPdo());
    }

    public function testGetPdoReturnsSameInstanceAfterConnect(): void
    {
        $connection = new SQLiteConnection();
        $connection->connect();

        $pdo1 = $connection->getPdo();
        $pdo2 = $connection->getPdo();

        $this->assertSame($pdo1, $pdo2);
    }

    public function testCloseResetsPdo(): void
    {
        $connection = new SQLiteConnection();
        $connection->connect();
        $connection->close();

        $reflection = new \ReflectionClass($connection);
        $property = $reflection->getProperty('pdo');
        $property->setAccessible(true);

        $this->assertNull($property->getValue($connection));
    }
}
