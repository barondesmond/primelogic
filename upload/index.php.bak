<?php
include("../_db_config.php");


$inp = file_get_contents('php://input');

$input = var_export($_SERVER, true);


$file = fopen('test.file', 'w');
fwrite($file, $input);
$input2 = var_export($_REQUEST, true);
fwrite($file, $input2);

$input2 = var_export($_GET, true);
fwrite($file, $input2);

$input2 = var_export($_POST, true);
fwrite($file, $input2);

fwrite($file, $inp);
fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
