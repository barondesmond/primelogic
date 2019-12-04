<?php
include("_db_config.php");
include("_email.php");
include("_report.php");
include("pdf.php");
include("_invoice.php");

define('SPOOLWRITE', 'write');

/*
LocationInactive 0
CustomerInactive 0
ReceiveNotifications -1
EmailTasks(1-6) 2,255
*/
if (!$argv['1'])
{
	define('EMAIL_SEND', '');
}
elseif ($argv['1'])
{
	define('EMAIL_SEND', $argv[1]);
}
if ($argv['2'])
{
	define('LOCATION_ONLY', 'SEND');
}
else
{
	define('LOCATION_ONLY', '');
}
$html = location_basis($argv['3']);
//echo $html;

foreach ($sm as $emp => $emails)
{
	$day = '31';
	$day2 = '60';
	if (!isset($email))
	{
		$email_send = $emails;
	}
	else
	{
		$email_send = $email;
	}
	foreach ($email_send as $send)
	{
		//echo "Email = $send \n";
		if (EMAIL_SEND == '' && LOCATION_ONLY== '')
		{
			email_report($send, "Priority Location Invoice Email Need Fixing", $html);
		}
	}
}
if (EMAIL_SEND != '')
{
	email_report(EMAIL_SEND, "Priority Location Invoice Email Need Fixing", $html);
}
?>

