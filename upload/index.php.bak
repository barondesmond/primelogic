<?php
include("_db_config.php");

$file = fopen($_POST['name'], 'w');
fwrite($file, $_POST['photo']);
fclose($file);

header('Content-Type: application/json');
$db['location'] = URL . '/upload/' . $_POST['name'];
echo json_encode($db);
