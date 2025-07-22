<?php

namespace App\interfaces;

interface CategoryRepositoryInterface
{
    public function getOrCreate(string $name): int;
    public function deleteAll(): void;
}
