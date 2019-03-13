<?php
include("_db_config.php");
include("_timeclockapp.php");


function convert_date_time($date, $time)
{
	$expday = explode(' ', $date);
	$day = $expday[0] . ' ' . $expday[1] . ' ' . $expday[2] . 	$time;
	$StartTime = strtotime($day);
	echo "Start Date " . date("Y:m:d H:i:s", $StartTime);
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

	if ($db['DispDate'] != '' && $db['TimeOn'] != '')
	{
		$db['Screen'] = 'Dispatch';
		$db['event'] = $db['Status'];
		$db['EmpActive'] = '1';
		if ($db['StartTime'] = convert_date_time($db['DispDate'], $db['TimeOn']))
		{
			$db['violation'] = 'Sync Error';
			$db['checkinStatus'] = 'Start';
			$db['installationId'] = $db['installationID'];
			print_r($db);
			$resp = timeclock_db($db);
			print_r($resp);
			
		}
	}

}
