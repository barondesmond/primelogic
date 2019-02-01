<?php
include("_db_config.php");
include("_user_app_auth.php");


$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($data);
	exit;
}

//tc is TimeClockID array 
//tk is TimeClockID key
//tv is TimeClock Start/Stop array
function timeclock_update($tc)
{


	foreach ($tc as $tk => $tv)
	{
		if (isset($tk) && isset($tv['StartDate']) && isset($tv['StopDate']))
		{
			$sql = "UPDATE TimeClockApp SET StartTime = '" . strtotime($tv['StartDate']) . "', StopTime = '" . strtotime($tv['StopDate']) . "' WHERE TimeClockID = '" . $tk . "'";
			$res = mssql_query($sql);
			$error[] = mssql_get_last_message();
			$error[] = $sql;
		}
		else
		{
			$error[] = 'Invalid Parameters timeclock_update';
		}
	}
if (!isset($error))
	{
	$error[] = 'error timclock update';
	$error[] = $tc;
	}
return $error;

}

if (isset($_REQUEST['TimeClockID']) && isset($_REQUEST['timeclock_update']))
{
	$error = timeclock_update($_REQUEST['TimeClockID']);
	$data['error'] = $error;
}
elseif ($_REQUEST['timeclock_update'])
{
	$error[] = 'error timeclock update';
	$error[] = var_export($_REQUEST['TimeClockID']);
}


$sql = "SELECT TImeClockApp.*, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized, Location.LocName, Jobs.JobNotes, LocationApi.latitude, LocationApi.longitude, TimeClockApp.Screen, Dispatch.Dispatch, DispLoc.LocName as DispatchName, Dispatch.Notes as DispatchNotes, DispLocApi.longitude as dispatchlongitude, DispLocApi.latitude as dispatchlatitude, DispLoc.Add1, DispLoc.Add2, DispLoc.City, DispLoc.State, DispLoc.Zip, DispLoc.Phone1  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo 
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo 
LEFT JOIN Jobs" . $dev . " as Jobs ON Jobs.Name = TimeClockApp.Name 
LEFT JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
LEFT JOIN Dispatch" . $dev . " as Dispatch ON TimeClockApp.Dispatch = Dispatch.Dispatch 
LEFT JOIN DispTech" . $dev . " as DispTech ON Dispatch.Dispatch = DispTech.Dispatch and TimeClockApp.event = DispTech.Status 
LEFT JOIN Location as DispLoc ON Dispatch.CustNo = DispLoc.CustNo and Dispatch.LocNo = DispLoc.LocNo 
LEFT JOIN LocationApi as DispLocApi ON DispLoc.LocName = DispLocApi.LocName
WHERE Posted is NULL";
$res = mssql_query($sql);
$data['error'][] = mssql_get_last_message();
$data['error'][] = $sql;
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$db['StartDate'] = date("s:i:H m/d/Y ", $db['StartTime']);
	$db['StopDate'] = date("s:i:H m/d/Y", $db['StopTime']);
	$data['TimeClock'][] = $db;
}

header('Content-Type: application/json');
echo json_encode($data);
?>