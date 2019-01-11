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


$time = time();

if ($_REQUEST['checkinStatus'] == 'Stop')
{
	$sql = "UPDATE TimeClockApp SET StopTime = '$time', EmpActive = '0' WHERE EmpNo = '" . $_REQUEST['EmpNo'] .  "' and installationId = '" . $_REQUEST['installationId'] . "' and EmpActive = '1'";
	$res = @mssql_query($sql);
	$error[] = mssql_get_last_message();
}
if ($_REQUEST['checkinStatus'] == 'Start');
{
	$sql = "INSERT INTO TimeClockApp (EmpNo, InstallationId, Name, latitude, longitude, event, StartTime, EmpActive) VALUES ('" . $_REQUEST['EmpNo'] . "', '" . $_REQUEST['installationId'] . "', '" . $_REQUEST['Name'] ."',
	        '" . $_REQUEST['latitude'] . "','" . $_REQUEST['longitude'] . "','" . $_REQUEST['event'] . "','" . $time . "','1');";
    $res = @mssql_query($sql);
	$error[] = mssql_get_last_message();
}
/*
$sql = "SELECT  Jobs.Name as Name, Location.LocName as LocName, Jobs.JobNotes as JobNotes FROM Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	WHERE JobStatus = '100' and Inactive = '0'
	ORDER BY Name ";
*/
	
	
$sql = "SELECT Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationID, UserAppAuth.authorized, TimeClockApp.EmpActive, TimeClockApp.event, TimeClockApp.Name, Location.LocName, Jobs.JobNotes  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo and UserAppAuth.installationId = TImeClockApp.installationId and EmpActive = '1'
LEFT JOIN Jobs ON Jobs.Name = TimeClockApp.Name and Jobs.JobStatus = '100' and Jobs.Inactive = '0'
LEFT JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
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
}


header('Content-Type: application/json');

$json =  json_encode($db);
if ($db['error'])
{
	error_log($json);
}
echo $json;
?>