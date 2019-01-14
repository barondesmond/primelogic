<?php
include("../_db_config.php");
$req = file_get_contents('php://input');
error_log($req);
$file = fopen('test' . '.file', 'w');
fwrite($file, $req);
fclose($file);


header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
