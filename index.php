<?php
require 'vendor/autoload.php';

require_once 'classes/DatabaseManager.php';
require_once 'classes/ExcelReader.php';
require_once 'classes/SQLiteConnection.php';
require_once 'classes/FrontHelper.php';
require_once 'repositories/InvoiceRepository.php';
require_once 'classes/ExcelDataHandle.php';
require_once 'classes/MigrationManager.php';
require_once 'repositories/AddressRepository.php';
require_once 'repositories/CustomerRepository.php';
require_once 'repositories/CategoryRepository.php';
require_once 'repositories/ProductRepository.php';
require_once 'repositories/InvoiceDetailRepository.php';
require_once 'classes/Helper.php';

use App\classes\DatabaseManager;
use App\Classes\ExcelReader;
use App\Classes\SQLiteConnection;
use App\classes\FrontHelper;
use App\repositories\InvoiceRepository;
use App\Classes\ExcelDataHandle;
use App\classes\MigrationManager;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InvoiceDetailRepository;
use App\Classes\Helper;

try {
    $loader = new FrontHelper();
    $db = new SQLiteConnection();

    $loader->simpleLoading("Getting Excel Data...", function () use (&$excel_data) {
        $excel_reader = new ExcelReader('data.xlsx');
        $excel_data = $excel_reader->getDataArray();
    });

    $loader->simpleLoading("Connecting to database...", function () use ($db) {
        $db->connect();
    });

    $loader->simpleLoading("Deleting old data...", function () use ($db, &$manager) {
        $manager = new DatabaseManager($db);
        $manager->dropAllTables();
    });

    $loader->simpleLoading("Create Schema...", function () use ($db, $excel_data) {
        $migration_manager = new MigrationManager($db);
        $migration_manager->run();
    });

    $loader->simpleLoading("Insert Data Into DB...", function () use ($db, $excel_data, $manager, &$invoiceRepo) {
        $addressRepo = new AddressRepository($db, $manager);
        $customerRepo = new CustomerRepository($db, $manager);
        $categoryRepo = new CategoryRepository($db, $manager);
        $productRepo = new ProductRepository($db, $manager);
        $invoiceRepo = new InvoiceRepository($db, $manager);
        $invoiceDetailRepo = new InvoiceDetailRepository($db, $manager);
        $Helper = new Helper($db);

        $excelHandler = new ExcelDataHandle(
            $addressRepo,
            $customerRepo,
            $categoryRepo,
            $productRepo,
            $invoiceRepo,
            $invoiceDetailRepo,
            $Helper
        );

        $excelHandler->normalizeAndSaveExcelData($excel_data);
    });

    echo '<xmp>✅ Data inserted into SQLite successfully!</xmp>';

    $loader->simpleLoading("Fetching all invoices...", function () use ($db, &$invoices, $invoiceRepo) {
        $invoices = $invoiceRepo->getAllInvoicesWithDetails();
    });


    $loader->simpleLoading("Closing database...", function () use ($db) {
        $db->close();
    });

    echo "---------------------------";
    echo '<xmp>All Invoices Before Normilization</xmp>';

    require 'views/invoices_table.php';

    echo "---------------------------";
    echo "<h2>All Invoices After Normilization</h2>";
    echo "---------------------------";

    require 'views/invoice_table_after_normilization.php';
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
