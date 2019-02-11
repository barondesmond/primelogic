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
function validate_timeclock_update($TimeClockID, $StartDate, $StopDate)
{
	$r1 = time() - 86400*30;
	$r2 = time() + 86400*30;

	$t1 = strtotime($StartDate);
	$t2 = strtotime($StopDate);
	$sql = "SELECT * FROM TimeClockApp WHERE TimeClockID = '$TimeClockID'";
	$res = mssql_query($sql);
	$tca = mssql_fetch_array($res, MSSQL_ASSOC);
	if ($t1 > $t2 || $t1 < $r1 || $t2 > $r2 || $t1 > $r2 || $t2 < $r1)
	{
		return false;
	}
	$sql = "SELECT * FROM TimeClockApp WHERE ((StartTime < '$t1' and StopTime > '$t1') or (StartTime < '$t2' and StopTime > '$t2'))  and EmpNo = '" . $tca['EmpNo'] . "'";
	$res = mssql_query($sql);
	$db = mssql_fetch_array($res, MSSQL_ASSOC);
	if (isset($db))
	{
		return false;
	}

return true;

}
function timeclock_update($tc)
{


	foreach ($tc as $tk => $tv)
	{
		if (isset($tk) && isset($tv['StartDate']) && isset($tv['StopDate']) && validate_timeclock_update($tk, $tv['StartDate'], $tv['StopDate']))
		{

			$sql = "UPDATE TimeClockApp SET StartTime = '" . strtotime($tv['StartDate']) . "', StopTime = '" . strtotime($tv['StopDate']) . "' WHERE TimeClockID = '" . $tk . "'";
			$res = mssql_query($sql);
			$error[] = mssql_get_last_message();
			$error[] = $sql;
		}
		else
		{
			$error[] = 'Invalid Parameters timeclock_update TimeClockID ' . $tk .'EmpNo ' .  $tv['EmpNo'] . ' StartDate ' . $tv['StartDate'] . ' StopDate ' . $tv['StopDate'];
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
	$error[] = 'error timeclock update request';
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
	$db['StartDate'] = date("Y:m:d H:i:s ", $db['StartTime']);
	$db['StopDate'] = date("Y:m:d H:i:s", $db['StopTime']);
	$data['TimeClock'][] = $db;
}

header('Content-Type: application/json');
echo json_encode($data);
?>