<?php
include("../_db_config.php");

$file = fopen('test' . '.file', 'w');
while(list($key, $value) = each($_REQUEST))
{

fwrite($file, $key . '=' . $value);
}
fclose($file);


header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/' . $_POST['name'];
echo json_encode($db);
