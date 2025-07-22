<?php

namespace  App\interfaces;

use PDO;

interface  DatabaseConnectionInterface
{
    public function connect(): void;
    public function getPdo(): PDO;
    public function close(): void;
}
