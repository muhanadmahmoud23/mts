<?php

namespace App\interfaces;

interface InvoiceRepositoryInterface
{
    public function getOrCreate(string $invoiceNo, int $customerId, string $date, float $grandTotal): int;

    public function getAllInvoicesWithDetails(): array;

    public function deleteAll(): void;
}
