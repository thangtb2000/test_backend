<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    include_once('../../config/db.php');
    include_once('../../model/product.php');
    
    $db = new db(); 
    $connect = $db->connect();
    $product = new product($connect);
    $product->id = isset($_GET['id']) ? $_GET['id'] : die();
    
    $product->show();
    
    $product_item = array(
        'ID' => $product->id,
        'name' => $product->name,
        'group_ID' => $product->group_ID,
        'price' => $product->price
    );
    
    print_r(json_encode($product_item))

?>