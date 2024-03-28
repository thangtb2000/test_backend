<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/product.php');


$db = new db();
$connect = $db->connect();
$product = new product($connect);

$data = json_decode(file_get_contents("php://input"));
if ($data !== null) {
    $product->id = $data->id;
    if ($product->delete()) {
        echo json_encode(array('message', 'product delete successfully'));
    } else {
        echo json_encode(array('message', 'product not delete '));
    }
}
