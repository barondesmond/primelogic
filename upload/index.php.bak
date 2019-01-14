<?php
include("../_db_config.php");

$file = fopen('test' . '.file', 'w');
fwrite($file, var_dump($_POST));
fclose($file);


header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/' . $_POST['name'];
echo json_encode($db);
