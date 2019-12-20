<?php
include("../_db_config.php");
include("../_email.php");
include("../_location_api.php");

//$file =  $_REQUEST['file'];
$file = '/var/www/html/primelogic/upload/' . $argv[1];
$db = location_parse_file($argv[1]);
echo $file;
$up = "https://" . HOST . "/primelogic/upload/" . $argv[1];
$fl = "<A HREF='" . $up . "'>";
if (file_exists($file))
{
	echo "sending file" . $file;
	$html = "<P>You can download image " . $fl . "here</a>";
	$img[] = $file;
$sa[] = 'barondesmond@plisolutions.com';
$sa[] = 'dispatch@plisolutions.com';
	foreach ($sa as $send)
	{
		email_report($send, "Dispatch Image " . $db['reference'], $html, '' , '', '', $img);
	}
}
