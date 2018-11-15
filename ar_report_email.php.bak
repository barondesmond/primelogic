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
	//days (0 current, 60 = 60-75, 90 = 90+)
	$da[0] = 'service@plisolutions.com, dispatch@plisolutions.com';
	$da[60] = 'gwen@plisolutions.com, nicole@plisolutions.com';
	$da[90] = 'gwen@plisolutions.com, shannon@plisolutions.com, arthur@plisolutions.com';
	//Salesman
	$sm['0003'] = 'david@plisolutions.com';
	$sm['0057'] = 'beau@plisolutions.com';
	//Dept
	$dp[30] = 'arthur@plisolutions.com';
	$dp[40] = 'arthur@plisolutions.com';
	$dp[50] = 'arthur@plisolutions.com';
	$dp[60] = 'clint@plisolutions.com, shannon@plisolutions.com';
	$dp[70] = 'arthur@plisolutions.com';
	
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