<?php
include("../_db_config.php");

$file = fopen($_POST['name'] . '.file', 'w');
fwrite($file, print_r($_POST);
fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/' . $_POST['name'];
echo json_encode($db);
