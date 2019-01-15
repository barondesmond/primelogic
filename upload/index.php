<?php
include("../_db_config.php");


$inp = file_get_contents('php://input');

$input = var_export($_SERVER, true);


$file = fopen('test.file', 'w');

fwrite($file, 'php://input ' . $inp);
echo "files ";
$input3 = var_export($_FILES, true);
fwrite($file, $input3);

fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
