<?php

namespace App\interfaces;

interface AddressRepositoryInterface
{
    public function getOrCreate(string $number, string $name): int;
    public function deleteAll(): void;
}
