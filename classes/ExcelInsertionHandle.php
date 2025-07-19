<?php

namespace App\classes;

require_once 'models/AddressModel.php';
require_once 'models/CategoryModel.php';
require_once 'models/CustomerModel.php';
require_once 'models/InvoiceDetailsModel.php';
require_once 'models/InvoiceModel.php';
require_once 'models/ProductModel.php';
require_once 'classes/helper.php';

use PDO;

class ExcelInsertionHandle
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function InsertExcelDataToDataBase($excel_data)
    {
        $this->deleteAll();
        $this->createSchema();
        $this->normalizeAndSaveExcelData($excel_data);
    }

    public function deleteAll(): void
    {
        $this->pdo->beginTransaction();
        try {
            (new InvoiceDetailModel($this->pdo))->deleteAll();
            (new InvoiceModel($this->pdo))->deleteAll();
            (new ProductModel($this->pdo))->deleteAll();
            (new CategoryModel($this->pdo))->deleteAll();
            (new CustomerModel($this->pdo))->deleteAll();
            (new AddressModel($this->pdo))->deleteAll();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function createSchema(): void
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->exec('PRAGMA busy_timeout = 5000');
            (new AddressModel($this->pdo))->create();
            (new CustomerModel($this->pdo))->create();
            (new CategoryModel($this->pdo))->create();
            (new ProductModel($this->pdo))->create();
            (new InvoiceModel($this->pdo))->create();
            (new InvoiceDetailModel($this->pdo))->create();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            if (strpos($e->getMessage(), 'database is locked') !== false) {
                echo "🔒 Database is currently locked. Retrying in 2 seconds...\n";
                sleep(2);
            } else {
                throw $e;
            }
        }
    }

    public function normalizeAndSaveExcelData(array $rows): void
    {
        $this->pdo->beginTransaction();

        $addressModel = new AddressModel($this->pdo);
        $customerModel = new CustomerModel($this->pdo);
        $categoryModel = new CategoryModel($this->pdo);
        $productModel = new ProductModel($this->pdo);
        $invoiceModel = new InvoiceModel($this->pdo);
        $invoiceDetailModel = new InvoiceDetailModel($this->pdo);
        $Helper = new Helper();

        $categoryId = $categoryModel->getOrCreate('Uncategorized');

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

            [$streetNumber, $streetName] = $Helper->splitAddress($customerAddress);

            $addressId = $addressModel->getOrCreate($streetNumber, $streetName);
            $customerId = $customerModel->getOrCreate($customerName, $addressId);
            $productId = $productModel->getOrCreate($productName, $categoryId, $price);
            $invoiceId = $invoiceModel->getOrCreate($invoiceNumber, $customerId, $invoiceDate, $grandTotal);

            $invoiceDetailModel->insert($invoiceId, $productId, $price, $quantity, $total);
        }

        $this->pdo->commit();
    }
}
