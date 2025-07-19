<?php
require 'vendor/autoload.php';
require 'classes/ExcelReader.php';
require 'classes/Database.php';
require 'classes/FrontHelper.php';
require 'classes/ExcelInsertionHandle.php';

use App\Classes\ExcelReader;
use App\Classes\Database;
use App\Classes\FrontHelper;
use App\Classes\InvoiceModel;
use App\Classes\ExcelInsertionHandle;

try {
    $loader = new FrontHelper();
    $db = new Database();


    $loader->simpleLoading("Getting Excel Data...", function () use (&$excel_data) {
        $excel_reader = new ExcelReader('data.xlsx');
        $excel_data = $excel_reader->getDataArray();
    });

    $loader->simpleLoading("Connecting to database...", function () use ($db) {
        $db->connect();
    });

    $loader->simpleLoading("Deleting old data...", function () use ($db, $excel_data) {
        $manager = new ExcelInsertionHandle($db->getPdo());
        $manager->InsertExcelDataToDataBase($excel_data);
    });

    echo '<xmp>✅ Data inserted into SQLite successfully!</xmp>';

    $loader->simpleLoading("Fetching all invoices...", function () use ($db, &$invoices) {
        $invoiceModel = new InvoiceModel($db->getPdo());
        $invoices = $invoiceModel->getAllInvoicesWithDetails();
    });


    $loader->simpleLoading("Closing database...", function () use ($db) {
        $db->close();
    });

    echo "---------------------------";
    echo '<xmp>All Invoices Before Normilization</xmp>';

    require 'views/invoices_table.php';
    echo json_encode($invoices, JSON_PRETTY_PRINT);

    echo "---------------------------";
    echo "<h2>All Invoices After Normilization</h2>";
    echo "---------------------------";

    require 'views/invoice_table_after_normilization.php';
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
