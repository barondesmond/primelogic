<?php
include("_db_config.php");
include("_email.php");
include("_report.php");
include("pdf.php");
include("_invoice.php");
/*
LocationInactive 0
CustomerInactive 0
ReceiveNotifications -1
EmailTasks(1-6) 2,255
*/
define('EMAIL_SEND', '');

$html = location_basis();
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
		echo "Email = $send \n";
		email_report($send, "Priority Location Invoice Email Need Fixing", $html);
	}
}

email_report('dispatch@plisolutions.com', "Priority Location Invoice Email Need Fixing", $html);
?>

