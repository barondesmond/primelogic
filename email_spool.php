<?php
//email spool cron

$start = time();
$end = time() . 86000;
include("_email.php");

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


function send_json_file($entry)
{
	$st = fopen($entry, "r");
	$json = fread($st);

	$db = json_decode($json);
	print_r($db);
	fclose($st);
	exit;
}	

?>