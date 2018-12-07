<?php
include("_email.php");
$salt = 'wtf';

if ($_GET['email'] != '' && $_GET['job'] != '' && !$_GET['psswd'] && !$_GET['sent'])
{
	$psswd = md5($_GET['email'] . $salt);
	if (validate_email($_GET['email']))
	{	
		Header('email_sent.php?email=' . $_GET['email'] . '&job=' . $_GET['job'] . '&psswd=' . $psswd);
		exit;
	}
}
if ($_GET['psswd'] && $_GET['email'] && $_GET['job'] && md5($_GET['email'] . $salt) == $_GET['psswd'])
{

	system("/usr/bin/php /usr/bin/php/job_detail_report.php '" . $_GET['job'] . "'" . $_GET['email'] . "'");
	Header('email_sent.php.php"email=' . $_GET['email']);
	exit;
}	


if (validate_email($_GET['email']))
{
	echo "Email sent to " . $_GET['email'];
}
else
{
	echo "Email not validated " . $_GET['email'];
}
?>