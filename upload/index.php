<?php
include("../_db_config.php");

if ($_REQUEST['show'] && $_REQUEST['file'])
{
	$filename = basename($_REQUEST['file'];
	$file_extension = strtolower(substr(strrchr($filename,"."),1));

	switch( $file_extension ) {
	 case "gif": $ctype="image/gif"; break;
	 case "png": $ctype="image/png"; break;
	 case "jpeg":
	 case "jpg": $ctype="image/jpeg"; break;
	 default:
	}
	header('Content-type: ' . $ctype);
	$image = readfile(urldecode($_REQUEST['file']));
}	

$file = fopen('test.file', 'w');

fwrite($file, var_export($_FILES, true));

fclose($file);

$uploads_dir = '/var/www/html/primelogic/upload';
$tmp_name = $_FILES["photo"]['tmp_name'];
$name = time() . '.' . basename($_FILES["photo"]['name']);
$loc = $uploads_dir . '/' . $name;
move_uploaded_file($tmp_name, $loc);
   
header('Content-Type: application/json');
$db['location'] = 'https://' . HOST . '/primelogic/upload/' . $name ;
echo json_encode($db);
