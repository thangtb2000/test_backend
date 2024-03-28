<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/group.php');


$db = new db();
$connect = $db->connect();
$group = new group($connect);

$data = json_decode(file_get_contents("php://input"));
if ($data !== null) {
    $group->id = $data->id;
    if ($group->delete()) {
        echo json_encode(array('message', 'Group delete successfully'));
    } else {
        echo json_encode(array('message', 'Group not delete '));
    }
}
