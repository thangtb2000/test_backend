<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With');

include_once('../config/db.php');
include_once('../model/product.php');

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$db = new db();
$conn = $db->connect();
$product = new product($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $fileName = $_FILES['excel_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['excel_file']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileNamePath);

        //get worksheet Group
        $worksheetGroup = $spreadsheet->getSheetByName("Groups");
        importDataToMySQL($conn, $worksheetGroup, 'Groups', ['A' => 'group_name', 'B' => 'title', 'C' => 'content']);


        //get worksheet Product
        $worksheet = $spreadsheet->getSheetByName("Products");
        importDataToMySQL($conn, $worksheet, 'Products', ['A' => 'group_ID', 'B' => 'name', 'C' => 'price']);
    }
}

function importDataToMySQL($conn, $worksheet, $tableName, $columnMapping)
{
    $sheetData = $worksheet->toArray(null, true, true, true);
    $isHeader = true;
    foreach ($sheetData as $row) {
        if ($isHeader) {
            $isHeader = false;
            continue;
        }
        $columns = [];
        $placeholders = [];
        $values = [];
        foreach ($columnMapping as $excelColumn => $mysqlColumn) {
            $columns[] = $mysqlColumn;
            $placeholders[] = '?'; // Placeholder cho giá trị
            $values[] = $row[$excelColumn];
        }
        $sql = "INSERT INTO $tableName (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
    }
}
