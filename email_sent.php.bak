<?php
include("_email.php");
$salt = 'wtf';


if (email_validate($_GET['email']) && $_GET['job'] != '')
{
	$res = system("/usr/bin/php /usr/bin/php/job_detail_report.php '" . $_GET['job'] . "'" .  ' ' . "'" . $_GET['email'] . "'");

	echo "Email sent to " . $_GET['email'];
}
else
{
	echo "Email not validated " . $_GET['email'];
}
?>