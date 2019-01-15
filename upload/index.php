<?php
include("../_db_config.php");



$file = fopen('test.file', 'w');

fwrite($file, var_export($_FILES, true));

fclose($file);

$uploads_dir = '/upload';
$tmp_name = $_FILES["photo"]['tmp_name'];
$name = basename($_FILES["photo"]['name']);
$loc = $tmp_name . '/' . $name;
move_uploaded_file($tmp_name, $loc);
   
header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . $loc ;
echo json_encode($db);
