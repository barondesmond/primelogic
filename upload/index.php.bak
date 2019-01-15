<?php
include("../_db_config.php");


$inp = file_get_contents('php://input');

$file = fopen('test.file', 'w');

fwrite($file, 'php://input ' . $inp);

fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
