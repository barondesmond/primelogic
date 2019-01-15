<?php
include("../_db_config.php");



$file = fopen('test.file', 'w');

fwrite($file, var_export($_FILES, true));

fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
