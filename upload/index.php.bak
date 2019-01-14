<?php
include("../_db_config.php");

$file = fopen('test' . '.file', 'w');
fwrite($file, $_POST['name'] . $_POST['uri'] . $_POST['photo']);
fclose($file);


header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/' . $_POST['name'];
echo json_encode($db);
