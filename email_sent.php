<?php
include("_email.php");
$salt = 'wtf';

if ($_GET['email'] != '' && $_GET['job'] != '' && !isset($_GET['psswd']) && !isset($_GET['sent']))
{
	$psswd = md5($_GET['email'] . $salt);
	if (email_validate($_GET['email']))
	{	
		Header('email_sent.php?email=' . $_GET['email'] . '&job=' . $_GET['job'] . '&psswd=' . $psswd);
		exit;
	}
}
else
{

	echo "help first";
	print_r($_GET);
	exit;
}
	
if ($_GET['psswd'] != '' && $_GET['email'] != '' && $_GET['job'] != '' && md5($_GET['email'] . $salt) == $_GET['psswd'])
{
	print_r($_GET);
	$res = system("/usr/bin/php /usr/bin/php/job_detail_report.php '" . $_GET['job'] . "'" . $_GET['email'] . "'");
	echo $res;
	Header('email_sent.php.php"email=' . $_GET['email'] . '&sent=true');
	exit;
}
elseif ($_GET['psswd'] != '')
{
	echo "help";
	print_r($_GET);
	exit;
}


if (email_validate($_GET['email']))
{
	echo "Email sent to " . $_GET['email'];
}
else
{
	echo "Email not validated " . $_GET['email'];
}
?>