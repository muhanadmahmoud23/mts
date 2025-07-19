<?php

namespace App\classes;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelReader
{
    protected $file;
    protected $worksheet;

    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception("File not found: $file");
        }

        $this->file = $file;
        $spreadsheet = IOFactory::load($this->file);
        $this->worksheet = $spreadsheet->getActiveSheet();
    }

    public function getDataArray(): array
    {
        $highestRow = $this->worksheet->getHighestRow();
        $highestColumn = $this->worksheet->getHighestColumn();

        $data = [];

        for ($row = 1; $row <= $highestRow; ++$row) {
            $rowData = [];
            for ($col = 'A'; $col <= $highestColumn; ++$col) {
                $cellValue = $this->worksheet->getCell($col . $row)->getValue();
                $rowData[] = $cellValue;
            }
            $data[] = $rowData;
        }

        return $data;
    }
}
