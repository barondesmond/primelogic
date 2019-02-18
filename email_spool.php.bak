<?php
//email spool cron
include("_db_config.php");
include("_email.php");

$start = time();
$end = time() + 86000;

define('SPOOLREAD', 'read');
$ct_today=0;
$ignore = array('.', '..');

while (time() < $end && $ct_today <99)
{
	if ($handle = opendir(DIRD)) 
	{
	 while (false !== ($entry = readdir($handle)))
     {
	     if ($entry != "." && $entry != ".." && !in_array($entry, $ignore))
	     {

			if (send_json_file(DIRD . $entry))
			{
				$ct_today++;
				echo "$entry\n";
			}
			else
			 {
				$ignore[] = $entry;
			 }
		 }
     }
    closedir($handle);
	}
	sleep(10);
}


function send_json_file($entry)
{
	global $er_array;
	if (filesize($entry) > 1024*1024)
	{
		echo "filesize = " . filesize($entry);
	}
	$json = file_get_contents($entry);
	
	$db = json_decode($json, 1);
	print_r($db);
	if (email_report($db['email'], $db['subject'], $db['body'], $db['filename'], $db['cid'], $db['name'], $db['pdf'], $db['func']))
	{
		echo "file $entry sent";
		if (unlink($entry))
		{
			echo "file $entry deleted";
			return true;
		}
	}
	else
	{
		$ex = explode('/', $entry);
		//print_r($ex);
		$exp = explode('.', $ex[4]);
		//print_r($exp);

		if ($exp[0] < time()-86400)
		{
			//delete day old
			echo "Deleting $entry";
			if (unlink($entry))
			{
				echo "file $entry deleted";
			}
		}
		else
		{
			echo "$entry not old $exp[0]\r\n";
		}
	}

	sleep(1);
return false;	
}	

?>