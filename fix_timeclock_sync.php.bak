<?php
include("_db_config.php");


function convert_date_time($date, $time)
{
	$expday = explode(' ', $date);
	$day = $expday[0] . ' ' . $expday[1] . ' ' . $expday[2] . 	$time;
	$StartTime = strtotime($day);
	if ($StartTime > 0)
	{
		return $StartTime;
	}
return false;
}


//Fix Sync Errors Dispatch

$sql = "SELECT UserAppAuth.*, DispTech.* FROM UserAppAuth
INNER JOIN DispTech ON EmpNo = ServiceMan 
LEFT JOIN TimeClockApp ON UserAppAuth.EmpNo = TimeClockApp.EmpNo and EmpActive = '1'
WHERE DispTech.Status IN ('Traveling', 'Working') and TimeClockApp.EmpNo is NULL";

$res = mssql_query($sql);
while ($db = mssql_fetch_assoc($res))
{
	print_r($db);

	if ($db['DispDate'] != '' && $db['TimeOn'] != '')
	{
		$db['Screen'] = 'Dispatch';
		$db['event'] = $db['Status'];
		$db['EmpActive'] = '1';
		print_r($db);
		if ($db['StartTime'] = convert_date_time($db['DispDate'], $db['TimeOn']))
		{
			print_r($db);
			exit;
		}
	}

}
