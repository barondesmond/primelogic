<?php
//email spool cron

include("_email.php");

$start = time();
$end = time() . 86000;

define('SPOOLREAD', 'read');
define('DIRD', '/var/www/email/');
$ct_today=0;
while (time() < $end && $ct_today <99)
{
	if ($handle = opendir(DIRD)) 
	{
	 while (false !== ($entry = readdir($handle)))
     {
	     if ($entry != "." && $entry != "..")
	     {

			send_json_file(DIRD . $entry);
			$ct_today++;
            echo "$entry\n";
		 }
     }
    closedir($handle);
	}
}


function send_json_file($entry)
{
	
	$json = file_get_contents($entry);
	
	$db = json_decode($json);
	print_r($db);
	exit;
}	

?>