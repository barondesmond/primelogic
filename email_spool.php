<?php
//email spool cron

include("_email.php");

$start = time();
$end = time() . 86000;

define('SPOOLREAD', 'read');


while (time() < $end)
{
	if ($handle = opendir('/var/www/email')) 
	{
	 while (false !== ($entry = readdir($handle)))
     {
	     if ($entry != "." && $entry != "..")
	     {

			send_json_file($entry);
			
            echo "$entry\n";
		 }
     }
    closedir($handle);
	}
}


function send_json_file($entry)
{
	$st = fopen('/var/www/email/' . $entry, "r");
	$json = fread($st);

	$db = json_decode($json);
	print_r($db);
	fclose($st);
	exit;
}	

?>