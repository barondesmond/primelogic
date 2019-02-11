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


function timeclock_add($db)
{
	$sql = "SELECT * FORM UserAuthApp WHERE EmpNo = '" . $db['EmpNo'] . "'";
	$res = mssql_query($sql);
	$uaa = mssql_fetch_array($res, MSSQL_ASSOC);
	if (!isset($uaa))
	{
		return $error[] = 'Missing UserAuthApp ' . $db['EmpNo'];
	}
	if (validate_timeclock_update('0', $db['EmpNo'], $db['StartDate'], $db['StopDate']))
	{
		$db = array_merge($_REQUEST, $uaa);

		$db['StartTime'] = strtotime($db['StartDate']);
		$db['StopTime'] = strtotime($db['StopDate']);
		$db['EmpActive'] = '0';
		if ($db['Screen'] == 'Job')
		{
			$db['Name'] = $db['JD'];
		}
		if ($db['Screen'] == 'Dispatch')
		{
			$db['Dispatch'] = $db['JD'];
		}
		$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'event', 'StartTime', 'EmpActive',  'Screen');
		foreach ($array as $key)
		{
			if (isset($db[$key]) && $db[$key] != '')
			{
				$k .= $key . ',';
				$v .= "'" . str_replace("'", "''", $db[$key]) . "',";
			}

		}
		$k = substr($k, 0, strlen($k) - 1);
		$v = substr($v, 0, strlen($v) - 1);
		$sql2 = "INSERT INTO TimeClockApp ($k) VALUES ($v)";
	
		 @mssql_query($sql2);
		$error[] = mssql_get_last_message();
		
	}
	else
	{
		$error[] = 'Invalid parameters for timeclock_add';
		$error[] = var_export($db);
	}
return $error;

}


//tc is TimeClockID array 
//tk is TimeClockID key
//tv is TimeClock Start/Stop array
function validate_timeclock_update($TimeClockID, $EmpNo, $StartDate, $StopDate)
{
	$r1 = time() - 86400*30;
	$r2 = time() + 86400*30;

	$t1 = strtotime($StartDate);
	$t2 = strtotime($StopDate);

	if ($t1 > $t2 || $t1 < $r1 || $t2 > $r2 || $t1 > $r2 || $t2 < $r1 || (($t2-$t1) > 86400))
	{
		return false;
	}
	if (!isset($EmpNo))
	{
		return false;
	}
	$sql = "SELECT * FROM TimeClockApp WHERE ((StartTime < '$t1' and StopTime > '$t1') or (StartTime < '$t2' and StopTime > '$t2'))  and EmpNo = '$EmpNo' and TimeClockID != '$TimeClockID'";
	$res = mssql_query($sql);
	$db = mssql_fetch_array($res, MSSQL_ASSOC);
	if (isset($db['EmpNo']))
	{
		return false;
	}

return true;

}
function timeclock_update($tc)
{


	foreach ($tc as $tk => $tv)
	{
		$sql = "SELECT * FROM TimeClockApp WHERE TimeClockID = '$tk'";
		$res = mssql_query($sql);
		$tca = mssql_fetch_array($res, MSSQL_ASSOC);

		if (isset($tk) && isset($tv['StartDate']) && isset($tv['StopDate']) && validate_timeclock_update($tk, $tca['EmpNo'], $tv['StartDate'], $tv['StopDate']))
		{

			$sql = "UPDATE TimeClockApp SET StartTime = '" . strtotime($tv['StartDate']) . "', StopTime = '" . strtotime($tv['StopDate']) . "' WHERE TimeClockID = '" . $tk . "'";
			$res = mssql_query($sql);
			$error[] = mssql_get_last_message();
			$error[] = $sql;
		}
		else
		{
			$error[] = 'Invalid Parameters timeclock_update TimeClockID ' . $tk .'EmpNo ' .  $tca['EmpNo'] . ' StartDate ' . $tv['StartDate'] . ' StopDate ' . $tv['StopDate'];
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
if ($_REQUEST['timeclock_add'] && isset($_REQUEST['StartDate']) && isset($_REQUEST['StopDate']) && isset($_REQUEST['Screen']) && isset($_REQUEST['event']))
{
	$error = timeclock_add($_REQUEST);
	$data['error'] = $error;
}
elseif ($_REQUEST['timeclock_add'])
{
	$error[] = 'error timeclock add request';
	$error[] = var_export($_REQUEST);
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