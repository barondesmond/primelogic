<?php
include("_db_config.php");
include("_timeclockapp.php");


if ($argv['1'])
{
	$dev = 'Dev';
}

$sql = "SELECT * FROM Time.dbo.TimeClockApp WHERE FORMAT (dateadd(S, StartTime, '1970-01-01'), 'dd-MM-yy') != (dateadd(S, StopTime, '1970-01-01'),'dd-MM-yy')

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