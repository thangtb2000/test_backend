<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/group.php');


$db = new db(); 
$connect = $db->connect();
$group = new group($connect);

$data = json_decode(file_get_contents("php://input"));
if($data !== null){
    
    $group->id = $data->id;
    $group->group_name = $data->group_name;
    $group->title = $data->title;
    $group->content = $data->content;
    if($group->update()){
        echo json_encode(array('message', 'Group updated successfully'));
    }else{
        echo json_encode(array('message', 'Group not updated '));
        
    }
}



?>

