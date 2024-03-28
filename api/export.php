<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once('../config/db.php');
$db = new db();
$conn = $db->connect();

$spreadsheet = new Spreadsheet();

$groupSheet = $spreadsheet->getActiveSheet();
$groupSheet->setTitle('Groups');

$productSheet = $spreadsheet->createSheet();
$productSheet->setTitle('Products');

$queryGroup = "SELECT * FROM `Groups`";
$stmtGroup = $conn->prepare($queryGroup);
$stmtGroup->execute();
$groupData = $stmtGroup->fetchAll(PDO::FETCH_ASSOC);

$queryProduct = "SELECT * FROM Products";
$stmtProduct = $conn->prepare($queryProduct);
$stmtProduct->execute();
$productData = $stmtProduct->fetchAll(PDO::FETCH_ASSOC);

$rowIndex = 1;
foreach ($groupData as $row) {
    $groupSheet->setCellValue("A" . $rowIndex, $row['ID']);
    $groupSheet->setCellValue("B" . $rowIndex, $row['group_name']);
    $groupSheet->setCellValue("C" . $rowIndex, $row['title']);
    $groupSheet->setCellValue("D" . $rowIndex, $row['content']);
    $rowIndex++;
}

$rowIndex = 1;
foreach ($productData as $row) {
    $productSheet->setCellValue("A" . $rowIndex, $row['ID']);
    $productSheet->setCellValue("B" . $rowIndex, $row['name']);
    $productSheet->setCellValue("C" . $rowIndex, $row['price']);
    $productSheet->setCellValue("D" . $rowIndex, $row['group_ID']);
    $rowIndex++;
}

$tempFile = tempnam(sys_get_temp_dir(), 'excel');

$writer = new Xlsx($spreadsheet);
ob_clean();
$writer->save($tempFile);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="exported_data.xlsx"');
header('Cache-Control: max-age=0');

readfile($tempFile);

unlink($tempFile);
