<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/product.php');


$db = new db();
$connect = $db->connect();
$product = new product($connect);

$data = json_decode(file_get_contents("php://input"));
if ($data !== null) {
    $product->id = $data->id;
    $product->group_ID = $data->group_ID;
    $product->name = $data->name;
    $product->price = $data->price;
    if ($product->update()) {
        echo json_encode(array('message', 'Product updated successfully'));
    } else {
        echo json_encode(array('message', 'Product not updated '));
    }
}
