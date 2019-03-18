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

if ($argv['1'])
{
	$dev = 'Dev';
}

$sql = "SELECT UserAppAuth.*, DispTech.*  FROM UserAppAuth
INNER JOIN DispTech ON UserAppAuth.EmpNo = DispTech.ServiceMan and DispDate > DATEADD(day, -15, getdate())
LEFT JOIN TimeClockApp ON UserAppAuth.EmpNo = TimeClockApp.EmpNo and Disptech.Dispatch = TimeClockApp.Dispatch and DispTech.Counter = TimeClockApp.Counter
WHERE TimeClockApp.TimeClockID is NULL amd DispTech.Status != 'Pending' and DispTech.Status != 'Off Job'
ORDER BY DispDate ASC, DispTech.Counter ASC";
$res = mssql_query($sql);
while ($db = mssql_fetch_assoc($res))
{
	print_r($db);
	if ($db['DispDate'] != '')
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
			$resp = timeclock_db($db, $db['StartTime']);
			print_r($resp);
			if ($db['Status'] == 'Complete' && $db['StopTime'] == convert_date_time($db['DateOff'], $db['TimeOff']))
			{
				$db['ckeckinStatus'] == 'Stop';
				print_r($db);
				$resp = timeclock_db($db, $db['StopTime']);
				print_r($resp);
			}
		}
	}
	exit;
}
exit;


//Fix Sync Errors Dispatch

$sql = "SELECT UserAppAuth.*, DispTech.* FROM UserAppAuth
INNER JOIN DispTech$dev ON EmpNo = ServiceMan 
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
			$resp = timeclock_db($db, $db['StartTime']);
			print_r($resp);
			
		}
	}

}

echo "Jan 25 2019 12:00:00:000AM";


$newdate = date("Y-m-d", time()) . ' 00:00:00';
echo "the day is $newdate";
$yestertime = strtotime($newdate)-60;
$yesterdate = date("M d Y ", $yestertime) . '12:00:00:000AM';
echo "\r\nYesterdate is " . $yesterdate;
$sql = "SELECT TimeClockApp.* FROM TimeCLockApp
WHERE event IN ('Traveling', 'Working') and TimeClockApp.EmpNo is Not Null and StartTime < '$yestertime' and EmpActive = '1'";
echo $sql;
$res = mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	unset($db['TimeClockID']);
	$db['installationId'] = $db['installationID'];
	$db['checkinStatus'] = 'Stop';
	print_r($db);

if ($db['Screen'] == 'Dispatch')
{
	$error = dispatch_db($db, $dev, $yestertime );
	$error2 = timeclock_db($db, $yestertime);
	$error = array_merge($error, $error2);
}
else
{
	$error = timeclock_db($db);
	

}
print_r($error);

}