<?php

namespace App\interfaces;

interface CustomerRepositoryInterface
{
    public function getOrCreate(string $name, int $addressId): int;
    public function deleteAll(): void;
}
