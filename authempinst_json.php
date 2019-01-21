<?php
include("_db_config.php");

/* create*
CREATE TABLE UserAppAuth (
EmpNo INT PRIMARY KEY NOT NULL,
installationID varchar(255),
authorized INT NOT NULL DEFAULT '0'
)
*/

/*
INSERT INTO UserAppAuth (EmpNo, installationID) VALUES ('0195', 'askdfhahlkjsdhfladf');
*/
/*
$sql = "SELECT * FROM UserAppAuth WHERE EmpNo = '" . $_REQUEST['EmpNo'] . "'' and installationId = '" . $_REQUEST['installationId'] . "'";
$res = mssql_query($sql);
$errors[] = mssql_get_last_message();
if (!mssql_num_rows($res))
{
	$sql = "INSERT INTO UserAppAuth (EmpNo, installationId) VALUES('" . $_REQUEST['EmpNo'] . "','" . $_REQUEST['installationId'] . "'";
	mssql_query($sql);
	$errors[] = mssql_get_last_message();
	$sql = "UPDATE UserAppAuth SET installationId = '" . $_REQUEST['installationId'] . "', authorized = '0' WHERE EmpNo = '" . $_REQUEST['EmpNo'] . "' and installationid != '" . $_REQUEST['installationId'] . "'";
	mssql_query($sql);
	$errors[] = mssql_get_last_message();

}
*/

$time = time();

if ($_REQUEST['checkinStatus'] == 'Stop')
{
	$sql1 = "UPDATE TimeClockApp SET StopTime = '$time', EmpActive = '0' WHERE EmpNo = '" . $_REQUEST['EmpNo'] .  "' and installationId = '" . $_REQUEST['installationId'] . "' and EmpActive = '1'";
	@mssql_query($sql1);
	$error[] = mssql_get_last_message();
}
elseif ($_REQUEST['checkinStatus'] == 'Start')
{
	$_REQUEST['StartTime'] = $time;
	$_REQUEST['EmpActive'] = '1';
	$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'latitude', 'longitude', 'event', 'StartTime', 'EmpActive', 'violation', 'image', 'Screen');
	foreach ($array as $key)
	{
		if (isset($_REQUEST[$key]) && $_REQUEST[$key] != '')
		{
			$k .= $key . ',';
			$v .= "'" . str_replace("'", "''", $_REQUEST[$key]) . "',";
		}

	}
		$k = substr($k, 0, strlen($k) - 1);
		$v = substr($v, 0, strlen($v) - 1);
	$sql2 = "INSERT INTO TimeClockApp ($k) VALUES ($v)";
	
    @mssql_query($sql2);
	$error[] = mssql_get_last_message();
}
/*
$sql = "SELECT  Jobs.Name as Name, Location.LocName as LocName, Jobs.JobNotes as JobNotes FROM Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	WHERE JobStatus = '100' and Inactive = '0'
	ORDER BY Name ";
*/
	
	
$sql = "SELECT TImeClockApp.TimeClockID, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized, TimeClockApp.EmpActive, TimeClockApp.event, TimeClockApp.Name, Location.LocName, Jobs.JobNotes, LocationApi.latitude, LocationApi.longitude, TimeClockApp.Screen  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo and UserAppAuth.installationId = TImeClockApp.installationId and EmpActive = '1'
LEFT JOIN Jobs ON Jobs.Name = TimeClockApp.Name and Jobs.JobStatus = '100' and Jobs.Inactive = '0'
LEFT JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
WHERE Employee.EmpNo = '" . $_REQUEST['EmpNo'] . "' and UserAppAuth.installationID = '" . $_REQUEST['installationId'] . "' ";


$res = mssql_query($sql);
$error = mssql_get_last_message();
$i=1;
$db = mssql_fetch_array($res, MSSQL_ASSOC);

if (!$db)
{
	$db['authorized'] = '0';
}

	$db['id'] = $i;
	$i++;
if (isset($error))
{
	$db['error'][] = $error;
	$db['sql'][] = $sql1;
	$db['sql'][] = $sql2;
}


header('Content-Type: application/json');

$json =  json_encode($db);
if ($db['error'])
{
	error_log($json);
	
}
echo $json;
?>