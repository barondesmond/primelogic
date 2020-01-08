<?php
include("_db_config.php");
include("_timeclockapp.php");




echo "Jan 25 2019 12:00:00:000AM";


$newdate = date("Y-m-d", time()) . ' 00:00:00';
echo "the day is $newdate";
$yestertime = strtotime($newdate)-60;
$yesterdate = date("M d Y ", $yestertime) . '12:00:00:000AM';
echo "\r\nYesterdate is " . $yesterdate;
$sql = "SELECT TimeClockApp.* FROM Time.dbo.TimeCLockApp
WHERE event IN ('Traveling', 'Working', 'Lunch') and TimeClockApp.EmpNo is Not Null and StartTime < '$yestertime' and EmpActive = '1'";
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