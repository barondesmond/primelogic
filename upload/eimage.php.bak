<?php
include("../_db_config.php");
include("../_email.php");
include("../_location_api.php");

//$file =  $_REQUEST['file'];
$send = 'barondesmond@gmail.com';
$file = '/var/www/html/primelogic/upload/' . $argv[1];
$db = location_parse_file($argv[1]);
echo $file;
if (file_exists($file))
{
	echo "sending file" . $file;
	$html = "You can download image <A HREF=https://" . HOST . "/primelogic/upload/" . $argv[1] . ">here</a>";
	//$img[] = $file;
	email_report($send, "Dispatch Image " . $db['reference'], $html);
}
