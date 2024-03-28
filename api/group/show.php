<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    include_once('../../config/db.php');
    include_once('../../model/group.php');

    
    $db = new db(); 
    $connect = $db->connect();
    $group = new group($connect);
    $group->id = isset($_GET['id']) ? $_GET['id'] : die();
    
    $group->show();
    
    $group_item = array(
        'ID' => $group->id,
        'group_name' => $group->group_name,
        'title' => $group->title,
        'content' => $group->content
    );
    
    print_r(json_encode($group_item))

?>