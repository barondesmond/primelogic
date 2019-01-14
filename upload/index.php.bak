<?php
include("../_db_config.php");




$input = var_export($_SERVER, true);


$file = fopen('test.file', 'w');
fwrite($file, $input);
fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
