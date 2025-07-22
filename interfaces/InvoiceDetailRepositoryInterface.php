<?php

namespace App\interfaces;

interface InvoiceDetailRepositoryInterface
{
    public function insert(int $invoiceId, int $productId, float $price, int $quantity, float $total): void;

    public function deleteAll(): void;
}
