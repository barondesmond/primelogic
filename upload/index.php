<?php
include("../_db_config.php");
$req = file_get_contents('php://input');

$file = fopen('test' . '.file', 'w');
fwrite($file, $req);
fclose($file);


header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/' . $_POST['name'];
echo json_encode($db);
