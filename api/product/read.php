<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
include_once('../../config/db.php');
include_once('../../model/product.php');

$db = new db();
$connect = $db->connect();
$product = new product($connect);
$read = $product->read();

$num = $read->rowCount();
if ($num > 0) {
    $product_array = array();
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        $product_item = array(
            'ID' => $row['ID'],
            'name' => $row['name'],
            'group_ID' => $row['group_name'],
            'price' => $row['price']
        );

        $product_array[] = $product_item;
    }
    echo json_encode($product_array);
}
