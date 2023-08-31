<?php

use ClockifyToFormat\Clockify\DetailedExport;
use ClockifyToFormat\Export\DefaultExcel;

require_once "vendor/autoload.php";

$filename = $argv[0];
$csvFile = $argv[1] ?? '';
$excelFile = $argv[2] ?? '';

if(empty($csvFile)){
    throw new RuntimeException('CSV file parameter (#1) is empty');
}

if(empty($excelFile)){
    $excelFile = pathinfo($csvFile, PATHINFO_FILENAME) . '.xlsx';
}

$csvPath = __DIR__ . DIRECTORY_SEPARATOR . $csvFile;
$excelPath = __DIR__ . DIRECTORY_SEPARATOR . $excelFile;

$export = DetailedExport::fromCsv($csvPath);

$excel = DefaultExcel::fromDetailedExport($export);

$excel->saveToFile($excelPath);
