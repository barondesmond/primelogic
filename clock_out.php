<?php


$sql = "SELECT TimeClockApp.* FROM Time.dbo.TimeCLockApp
WHERE event IN ('Traveling', 'Working') and TimeClockApp.EmpNo is Not Null and EmpNo = '" . $argv[1]  . "'  and EmpActive = '1'";
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
	$error2 = timeclock_db($db, time());
	$error = array_merge($error, $error2);
}
else
{
	$error = timeclock_db($db);
	

}
print_r($error);