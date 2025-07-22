<?php

namespace App\Classes;

use App\Interfaces\AddressRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\InvoiceRepositoryInterface;
use App\Interfaces\InvoiceDetailRepositoryInterface;

class ExcelDataHandle
{
    private AddressRepositoryInterface $addressRepo;
    private CustomerRepositoryInterface $customerRepo;
    private CategoryRepositoryInterface $categoryRepo;
    private ProductRepositoryInterface $productRepo;
    private InvoiceRepositoryInterface $invoiceRepo;
    private InvoiceDetailRepositoryInterface $invoiceDetailRepo;
    private Helper $helper;

    public function __construct(
        AddressRepositoryInterface $addressRepo,
        CustomerRepositoryInterface $customerRepo,
        CategoryRepositoryInterface $categoryRepo,
        ProductRepositoryInterface $productRepo,
        InvoiceRepositoryInterface $invoiceRepo,
        InvoiceDetailRepositoryInterface $invoiceDetailRepo,
        Helper $helper
    ) {
        $this->addressRepo = $addressRepo;
        $this->customerRepo = $customerRepo;
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->invoiceDetailRepo = $invoiceDetailRepo;
        $this->helper = $helper;
    }

    public function normalizeAndSaveExcelData(array $rows): void
    {

        foreach ($rows as $row) {
            if (stripos($row[0], 'invoice') !== false) continue;

            [
                $invoiceNumber,
                $invoiceDate,
                $customerName,
                $customerAddress,
                $productName,
                $quantity,
                $price,
                $total,
                $grandTotal
            ] = [
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                (int)$row[5],
                (float)$row[6],
                (float)$row[7],
                (float)$row[8]
            ];

            //Split Address and Product Start
            [$streetNumber, $streetName] = $this->helper->splitAddress($customerAddress);
            $parts = explode(' - ', $productName, 2);

            if (count($parts) === 2) {
                $category_name = trim($parts[0]);
                $product_name = trim($parts[1]);
            } else {
                $category_name = 'Uncategorized';
                $product_name = trim($productName);
            }
            //Split Address and Product End

            //DB INSERT
            $addressId = $this->addressRepo->getOrCreate($streetNumber, $streetName);
            $customerId = $this->customerRepo->getOrCreate($customerName, $addressId);
            $categoryId = $this->categoryRepo->getOrCreate($category_name);
            $productId = $this->productRepo->getOrCreate($product_name, $categoryId, $price);
            $invoiceId = $this->invoiceRepo->getOrCreate($invoiceNumber, $customerId, $invoiceDate, $grandTotal);

            $this->invoiceDetailRepo->insert($invoiceId, $productId, $price, $quantity, $total);
        }
    }
}
