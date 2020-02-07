<?php
include("_db_config.php");

include("_email.php");

if (isset($argv))
{
	print_r($argv);
	$email = $argv[1];
	$subject = $argv[2];
	$body = $argv[3];
	$pdf = $argv[4];
	$filename = '';
	$cid = '';
	$name = '';
	$func = '';
}
if (isset($_REQUEST))
{
	foreach ($_REQUEST as $key=>$val)
	{
		${$key} = $val;
	}
}
email_report($email, $subject, $body, $filename, $cid, $name, $pdf, $func);
