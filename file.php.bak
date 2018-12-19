<?php
include("_email.php");

	$er_array = array('email', 'subject', 'body', 'filename', 'cid', 'name', 'pdf', 'func');
		for($i=0;$i<count($er_array);$i++)
		{
			$db[$er_array[$i]] = $argv[$i];
		}
		$enc =json_encode($db);
		$file = DIRD . time() . '.' . $db['email'] . '.' . urlencode($db['subject') .  '.email';
		$stream = fopen($file, 'w');
		fwrite($stream, $enc);
		fclose($stream);
		return $file;


?>