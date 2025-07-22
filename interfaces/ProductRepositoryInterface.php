<?php

namespace App\interfaces;

interface ProductRepositoryInterface
{
    public function getOrCreate(string $name, int $categoryId, float $price): int;

    public function deleteAll(): void;
}
