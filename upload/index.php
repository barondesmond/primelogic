<?php
include("../_db_config.php");
$bytesToRead = 4096000;
$input = fread(STDIN, $bytesToRead ); // reads 4096K bytes from STDIN
if ($input === FALSE) {
   // handle "failed to read STDIN"
}
// assuming it's json you accept:
$requestParams = json_decode($input , true);
if ($requestParams === FALSE) {
   // not proper JSON received - set response headers properly
   header("HTTP/1.1 400 Bad Request"); 
   // respond with error
   die("Bad Request");
}

$file = fopen('test.file', 'w');
fwrite($file, $input);
fclose($file);

header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/upload/photo.jpg' ;
echo json_encode($db);
