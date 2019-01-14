<?php
include("../_db_config.php");
$req = json_decode(file_get_contents('php://input'), true);

$file = fopen('test' . '.file', 'w');
fwrite($file, 'Opening');
while(list($key, $value) = each($req))
{

fwrite($file, $key . '=' . $value);
}
fwrite($file, 'Closing');
fclose($file);


header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/' . $_POST['name'];
echo json_encode($db);
