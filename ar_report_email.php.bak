<?php
include("_db_config.php");
include("_report.php");
include("_email.php");

$emp = '';
$dept = '';

if ($argv[1])
{
	$email = $argv[1];
}
else
{
	$email = 'barondesmond@gmail.com';
}

	
foreach ($da as $day=> $emails)
{
	if ($day == '90')
	{
		$day2 = '1095';
	}
	elseif ($day == '60')
	{
		$day2 = '75';
	}
	else
	{
		$day2 = $day + 30;
	}
	report_basis($day, $day2, $emp, $dept, $email);
}

foreach ($sm as $emp => $emails)
{
	$day = '31';
	$day2 = '60';
	report_basis($day, $day2, $emp, $dept, $email);
}
unset($emp);

foreach ($dp as $dept => $emails)
{
	$day = '31';
	$day2 = '60';
	report_basis($day, $day2, $emp, $dept, $email);
}



?>