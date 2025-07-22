<?php

namespace interfaces;

use PDO;

interface MigrationInterface
{
    public function migrate(PDO $pdo): void;
}
