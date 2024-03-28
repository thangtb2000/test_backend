<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
include_once('../../config/db.php');
include_once('../../model/group.php');

$db = new db(); 
$connect = $db->connect();
$group = new group($connect);
$read = $group->read();

$num = $read->rowCount();
if ($num > 0) {
    $group_array = array(); 
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        $group_item = array(
            'ID' => $row['ID'],
            'group_name' => $row['group_name'],
            'title' => $row['title'],
            'content' => $row['content']
        );

        $group_array[] = $group_item; 
    }
    echo json_encode($group_array); 
}
