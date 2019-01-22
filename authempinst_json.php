<?php
include("_db_config.php");

function dispatch_db($db, $dev='')
{

	//UPDATE DispTechDev SET DispDate = getdate()  WHERE Dispatch = '6555775' and Counter = '000' and Status = 'Traveling' and ServiceMan = '0195'
	//..DateOff
    //UPDATE DispTechDev SET DispTime = '" . date("H:i:s", time()) . "' WHERE Dispatch = '6555775' and Counter = '000' and Status = 'Traveling' and ServiceMan = '0195'
    //..TimeOn, TimeOff
	$array  = array('Dispatch', 'ServiceMan', 'Counter', 'Status', 'Dispatcher', 'PromDate', 'TPromDate', 'TPromTime', 'Zone', 'Priority', 'Terms', 'TechTime', 'SortDate', 'SortTime', 'Mobile', 'POReceived', 'TimeEntryCreated', 'HoursPayed');
	$q = '';
	for ($i=0; $i < count($array); $i++)
	{
		$q .= $array[$i] . ',';
	}
	$q = substr($q, 0, strlen($q) - 1);

	$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status IN ('Traveling', 'Working', 'Pending')";
	$res_sel = mssql_query($sel);
	if (!mssql_num_rows($res_sel))
	{
		$db['error'] = "Invalid DispTech$dev state";
		return $db;
	}
	$sdb = mssql_fetch_assoc($res_sel);

	$where = "WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan='" . $db['EmpNo'] . "' and Status IN ('Traveling', 'Working', 'Pending') and Counter = '" . $sdb['Counter'] . "'";

	if ($db['checkinStatus'] == 'Start')
	{
		$up = "UPDATE DispTech$dev SET Status = " . $db['event'] . "'";
		if ($db['event'] == 'Traveling' && $sbd['Status'] == 'Pending')
		{
			$dd = ", DispDate = getdate(), DispTime = '"  . date("H:i:s", time()) . "' ";
		}
		if ($db['event'] == 'Working' && $sdb['Status'] == 'Traveling')
		{
			$dd = ", TimeOn = '" . date("H:i:s", time()) . "' ";
		}
	
	}
	if ($db['checkInStatus'] == 'Stop' )
	{
		$up = "UPDATE DispTech$dev SET Status = '" . $db['event'] . "'";
		$dd = ", DateOff = getdate(), TimeOff = '" . date("H:i:s", time()) . "'";
		if ($db['Status'] == 'Complete')
		{
			$dd .= " , Complete = 'Y' ";
		}
		if ($sdb['Status'] == 'Traveling')
		{
			$dd .= " , TimeOn = " . date("H:i:s", time()) . "'";
		}
	}
	$sql = $up . $dd . $where;
	$res = @mssql_query($sql);
	$error[] = mssql_get_last_message();
	$error[] = $sql;
	if (mssql_rows_affected($res) > 0 && $db['checkInStatus'] = 'Stop')
	{
		for ($i=0; $i< count($array); $i++)
		{
			if ($array[$i] == 'Counter')
			{
				$db[$array[$i]] = (int) $db[$array[$i]];
				$db[$array[$i]]++;
				$db[$array[$i]] = tr_pad($db[$array[$i]], 3, "0", STR_PAD_LEFT);
			}

			$v .= "'" . $db[$array[$i]] . "',";
		}
		$v = substr($v, 0, strlen($v) - 1);		
		$ins = "INSERT INTO DispTech$dev ($q) VALUES($v)";
		$res2 = mssql_query($ins);
		$error[] = mssql_get_last_message();
		$error[] = $ins;
	}

return $error;
}

function timeclock_db($db)
{


$time = time();

if ($db['checkinStatus'] == 'Stop')
{
	$sql1 = "UPDATE TimeClockApp SET StopTime = '$time', EmpActive = '0' WHERE EmpNo = '" . $db['EmpNo'] .  "' and installationId = '" . $db['installationId'] . "' and EmpActive = '1'";
	@mssql_query($sql1);
	$error[] = mssql_get_last_message();
}
elseif ($db['checkinStatus'] == 'Start')
{
	$db['StartTime'] = $time;
	$db['EmpActive'] = '1';
	$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'latitude', 'longitude', 'event', 'StartTime', 'EmpActive', 'violation', 'image', 'Screen');
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
return $error;

}

if ($_REQUEST['dev'] == true)
{
	$d = 'Dev';
}

if ($_REQUEST['Screen'] == 'Dispatch')
{
	$error = dispatch_db($_REQUEST, $d);
	if (!$error['error'])
	{
		if ($error = timeclock_db($_REQUEST))
		{
			//error
		}
	}
	
}
else
{
	if ($error = timeclock_db($_REQUEST))
	{
		//error
	}
}

	
$sql = "SELECT TImeClockApp.TimeClockID, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized, TimeClockApp.EmpActive, TimeClockApp.Screen, TimeClockApp.event, TimeClockApp.Name, TimeClockApp.Dispatch, Location.LocName, Jobs.JobNotes, LocationApi.latitude, LocationApi.longitude, TimeClockApp.Screen, Dispatch.Dispatch, DispLoc.LocName as DispatchName, Dispatch.Notes as DispatchNotes, DispLocApi.longitude as dispatchlongitude, DispLocApi.latitude as dispatchlatitude  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo and UserAppAuth.installationId = TImeClockApp.installationId and EmpActive = '1'
LEFT JOIN Jobs ON Jobs.Name = TimeClockApp.Name and Jobs.JobStatus = '100' and Jobs.Inactive = '0'
LEFT JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
LEFT JOIN Dispatch ON TimeClockApp.Dispatch = Dispatch.Dispatch 
LEFT JOIN DispTech" . $d . " as DispTech ON Dispatch.Dispatch = DispTech.Dispatch and TimeClockApp.event = DispTech.Status and DispTech.Complete != 'Y' and DispTech.ServiceMan = '" . $_REQUEST['EmpNo'] . "'
LEFT JOIN Location as DispLoc ON Dispatch.CustNo = DispLoc.CustNo and Dispatch.LocNo = DispLoc.LocNo 
LEFT JOIN LocationApi as DispLocApi ON DispLoc.LocName = DispLocApi.LocName

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