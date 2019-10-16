<?php
include("_db_config.php");
include("_location_api.php");
include("_dispatch.php");
include('_report.php');
include ('_pdf.php');
include ('_email.php');
include("_user_app_auth.php");
include("dispatch_pdf.php");

include("_job.php");
include("_employees.php");

$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}


function add_note($db, $dev='')
{
	$tcq = TimeClockQuery($db, $dev);
	$note = 'add' . $db['Screen'] . 'Note';
	$error = '';
	if ($db['Screen'] == 'Dispatch' && $db[$note] != '' && $db['checkinStatus'] == 'addNote' && $tcq['Dispatch'] == $db['Dispatch'])
	{
		$addNote = $tcq['DispatchNotes'] . "\r\n" . date("Y-m-d: H:i:s") . '-' . $db['EmpNo'] . "-"  . $tcq['EmpName'] . '-' .  $db[$note] . "\r\n";
		$sql = "UPDATE Dispatch$dev SET Notes = '" . str_replace("'", "''", $addNote) . "' WHERE Dispatch = '" . $db['Dispatch'] . "'  ";
	}
	elseif ($db['Screen'] == 'Dispatch')
	{
		return false;
	}
	if ($db['Screen'] == 'Job' && $db[$note] != '' && $db['checkinStatus'] == 'addNote')
	{
		$addNote = $tcq['JobNotes'] . "\r\n" . date("Y-m-d: H:i:s") . '-' . $db['EmpNo'] . "-" . $tcq['EmpName'] . '-' .  $db[$note] . "\r\n";
		$sql = "UPDATE Jobs$dev SET JobNotes = '" . str_replace("'", "''", $addNote) . "' WHERE Name = '" . $db['Name'] . "'";
	}
	elseif ($db['Screen'] == 'Job')
	{
		return false;
	}
	if ($db['Screen'] == 'Employee' && $db[$note] != '')
	{
		$addNote = $tcq['EmployeeNotes'] . "\r\n" . date("Y-m-d: H:i:s") . '-' . $db['EmpNo'] . "-" . '-' . $tcq['EmpName'] . '-' .  $db[$note] . "\r\n";
		$sql = "UPDATE TimeClockApp SET EmployeeNotes = '" . str_replace("'", "''", $addNote) . "' WHERE TimeClockID = '" . $tcq['TimeClockID'] . "'";
	}
	if (isset($sql) && $sql != '')
	{
		@mssql_query($sql);
		$error[] = mssql_get_last_message();
		$error[] = $sql;
		return $error;
	}
	if ($note && isset($db[$note]) && $db[$note] != '')
	{
		$error['error'] = 'No Screen Handler ' . $db['Screen'] . ' ' . $db[$note];
	}
return $error;

}

function dispatch_counter($Dispatch = '', $dev = '')
{
	$sql = "SELECT * FROM DispTech$dev WHERE Dispatch = '$Dispatch' ORDER BY Counter DESC";
	$res = mssql_query($sql);
	if ($db = mssql_fetch_assoc($res))
	{
	
		return $db['Counter'];
	}
}
		
function dispatch_db($db, $dev='')
{

	if ($db['checkinStatus'] != 'Start' && $db['checkinStatus'] != 'Stop')
	{
		$error['error'] = 'invalid checkinStatus';
		return $error;
	}
	if ($db['Dispatch'] == 'null' || !$db['Dispatch'] || $db['Dispatch'] == '')
	{
		$error['error'] = 'invalid Dispatch';
		return $error;		
	}
	if ($db['Counter'] == 'null' || !$db['Counter'] || $db['Counter'] == '')
	{
		$error['error'] = 'invalid Dispatch';
		return $error;	
	}
	$up = '';
	$dd = '';
	$where = '';
	//UPDATE DispTechDev SET DispDate = getdate()  WHERE Dispatch = '6555775' and Counter = '000' and Status = 'Traveling' and ServiceMan = '0195'
	//..DateOff
    //UPDATE DispTechDev SET DispTime = '" . date("H:i:s", time()) . "' WHERE Dispatch = '6555775' and Counter = '000' and Status = 'Traveling' and ServiceMan = '0195'
    //..TimeOn, TimeOff
	$array  = array('Dispatch', 'ServiceMan', 'Counter', 'Status', 'Dispatcher', 'PromDate', 'TPromDate', 'TPromTime', 'Zone', 'Priority', 'Terms', 'TechTime', 'SortDate', 'SortTime', 'Mobile', 'POReceived', 'TimeEntryCreated', 'HoursPayed');
	$q = '';
	$blank = array('DispTime', 'TimeOn', 'TimeOff', 'Complete');

	for ($i=0; $i < count($array); $i++)
	{
		$q .= $array[$i] . ',';
	}
	$q = substr($q, 0, strlen($q) - 1);

	if ($db['checkinStatus'] == 'Start')
	{
		$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status = 'Pending'";
		$res_sel = mssql_query($sel);
		if (!mssql_num_rows($res_sel))
		{
			$db['error'] = "Invalid DispTech$dev state";
			return $db;
		}
	}
	if ($db['checkinStatus'] == 'Stop')
	{
		$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status = '" . $db['event'] . "'";
		$res_sel = mssql_query($sel);
		if (!mssql_num_rows($res_sel))
		{
			$db['error'] = "Invalid DispTech$dev state";
			return $db;
		}
	}

	$sdb = mssql_fetch_array($res_sel, MSSQL_ASSOC);
	if (!$sdb)
	{
		$db['error'][] = 'Missing DispTech' . $dev;
		$db['error'][] = $sel;
		$db['error'][] = $sdb;
		return $db;
	}
	if ($sdb['checkinStatus'] == 'Stop' && $sdb['DispDate'] == '')
	{
		$db['error'][] = 'Missing DispDate on checkinStatus Stop';
		return $db;
	}
	$where = " WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan='" . $db['EmpNo'] . "' and Status IN ('Traveling', 'Working', 'Pending') and Counter = '" . $db['Counter'] . "' ";

	if ($db['checkinStatus'] == 'Start')
	{
		$up = "UPDATE DispTech$dev SET Status = '" . $db['event'] . "' ";
		if ($db['event'] == 'Traveling' && $sdb['Status'] == 'Pending')
		{
			$dd = ", DispDate = getdate(), DispTime = '"  . date("H:i:s", time()) . "' ";
		}
		elseif ($db['event'] == 'Working' && $sdb['Status'] == 'Traveling')
		{
			$dd = ", TimeOn = '" . date("H:i:s", time()) . "' ";
			$tcq = TimeClockQuery($db, $dev);
			$tsql = "UPDATE TimeClockApp SET event = '" . $db['event'] . "' WHERE TimeClockID = '" . $tcq['TimeClockID'] . "'";
			@mssql_query($tsql);
			$error[] = mssql_get_last_message();
			$error[] = $tsql;
			if ($up != '' && $dd != '' && $where != '')
			{
				$sql = $up . $dd . $where;
				$res = @mssql_query($sql);
				$error[] = mssql_get_last_message();
				$error[] = $sql;
				$error['error'] = 'Changed Event Updated TimeClockApp';
				return $error;
			}
			else
			{
				$error['error'] = "missing $up $dd $where ";
				return $error;
			}
			
		}
		elseif ($db['event'] == 'Working' && $sdb['Status'] == 'Pending')
		{
			$dd = ", DispDate = getdate(), DispTime = '" . date("H:i:s", time()) . "', TimeOn = '" . date("H:i:s", time()) . "' ";
		}
		else
		{
			$db['error'][] = 'missing event';
			$db['error'][] = $sdb;
			$db['error'][] = $db;
			return $db;
		}
	
	}
	if ($db['checkinStatus'] == 'Stop' )
	{
		$dd = ", DateOff = getdate(), TimeOff = '" . date("H:i:s", time()) . "' ";
		$up = "UPDATE DispTech$dev SET Status = 'Complete' ";
		$dd .= " , Complete = 'Y' ";

		if ($sdb['Status'] == 'Traveling')
		{
			$dd .= " , TimeOn = '" . date("H:i:s", time()) . "' ";
		}
		
	}
	if ($up != '' && $dd != '' && $where != '')
	{
		$sql = $up . $dd . $where;
		$res = @mssql_query($sql);
		$rows = mssql_rows_affected(MSSQL_CONNECTION);
		$error[] = 'rows affected ' . $rows;
		$error[] = mssql_get_last_message();
		$error[] = $sql;
	
		
	}
	else
	{
		$db['error'] = "missing $up $dd $where ";
		return $db;
	}
	if ($db['checkinStatus'] == 'Stop' && $db['Complete'] != 'Y')
	{
		$v = '';
		for ($i=0; $i< count($array); $i++)
		{
			if ($array[$i] == 'Counter')
			{
				$sdb[$array[$i]] = (int) $sdb[$array[$i]];
				$sdb[$array[$i]] = dispatch_counter($sdb['Dispatch'], $dev);
				$sdb[$array[$i]]++;
				$sdb[$array[$i]] = str_pad($sdb[$array[$i]], 3, "0", STR_PAD_LEFT);
			}
			if ($array[$i] == 'Status')
			{
				$sdb['Status'] = 'Pending';
			}
			$v .= "'" . $sdb[$array[$i]] . "',";
		}
		$v = substr($v, 0, strlen($v) - 1);		

		for ($i=0;$i<count($blank); $i++)
		{
			$q .= " ," . $blank[$i] . " ";
			$v .= " ,'' ";
		}

		$ins = "INSERT INTO DispTech$dev ($q) VALUES($v)";
		$res2 = mssql_query($ins);
		$error[] = mssql_get_last_message();
		$error[] = $ins;
	}
	elseif ($db['checkinStatus'] == 'Stop' && $db['Complete'] == 'Y')
	{
		//dont insert new disptech
		$sql = "UPDATE Dispatch$dev SET Complete = getdate() WHERE Dispatch = '" . $db['Dispatch'] . "'";
		$res = mssql_query($sql);
		$rows = mssql_rows_affected(MSSQL_CONNECTION);
		$error[] = 'rows affected ' . $rows;
		$error[] = mssql_get_last_message();
		$error[] = $sql;
		system("/usr/bin/php /var/www/html/primelogic/dispatch.php '$db[Dispatch]' >/dev/null &");


	}

return $error;
}

function timeclock_db($db)
{


$time = time();

if ($db['checkinStatus'] == 'Stop')
{
	if (isset($db['Complete']) && $db['Complete'] == 'Y')
	{
		$complete = ", customer = '" . $db['customer'] . "', customerimage = '" . $db['customerimage'] . "'";
	}
	else
	{
		$complete = '';
	}
	$sql1 = "UPDATE TimeClockApp SET StopTime = '$time', EmpActive = '0' $complete WHERE EmpNo = '" . $db['EmpNo'] .  "' and installationId = '" . $db['installationId'] . "' and EmpActive = '1'";
	@mssql_query($sql1);
	$error[] = mssql_get_last_message();
	$error[] = $sql1;
}
elseif ($db['checkinStatus'] == 'Start')
{
	$db['StartTime'] = $time;
	$db['EmpActive'] = '1';
	$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'latitude', 'longitude', 'event', 'StartTime', 'EmpActive', 'violation', 'image', 'Screen', 'Counter', 'JobID');
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
	$error[] = $sql2;
}
return $error;

}


function TimeClockQuery($req, $dev='')
{
$sql = "SELECT TImeClockApp.TimeClockID, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized, TimeClockApp.EmpActive, TimeClockApp.Screen, TimeClockApp.event, TimeClockApp.Name, TimeClockApp.Dispatch, Location.LocName, Jobs.JobNotes, LocationApi.latitude, LocationApi.longitude, TimeClockApp.Screen, Dispatch.Dispatch, DispLoc.LocName as DispatchName, Dispatch.Notes as DispatchNotes, DispLocApi.longitude as dispatchlongitude, DispLocApi.latitude as dispatchlatitude, DispLoc.Add1, DispLoc.Add2, DispLoc.City, DispLoc.State, DispLoc.Zip, DispLoc.Phone1, DispTech.Counter, Jobs.JobID, Location.Add1 as JobAdd1, Location.Add2 as JobAdd2, Location.City as JobCity, Location.State as JobState, Location.Zip as JobZip, Location.Phone1 as JobPhone1  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo and UserAppAuth.installationId = TImeClockApp.installationId and EmpActive = '1'
LEFT JOIN Jobs" . $dev . " as Jobs ON Jobs.Name = TimeClockApp.Name and Jobs.JobStatus = '100' and Jobs.Inactive = '0' and TimeClockApp.JobID = Jobs.JobID
LEFT JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
LEFT JOIN Dispatch" . $dev . " as Dispatch ON TimeClockApp.Dispatch = Dispatch.Dispatch 
LEFT JOIN DispTech" . $dev . " as DispTech ON Dispatch.Dispatch = DispTech.Dispatch and TimeClockApp.event = DispTech.Status and DispTech.Complete != 'Y' and DispTech.ServiceMan = '" . $req['EmpNo'] . "' and TimeClockApp.Counter = DispTech.Counter 
LEFT JOIN Location as DispLoc ON Dispatch.CustNo = DispLoc.CustNo and Dispatch.LocNo = DispLoc.LocNo 
LEFT JOIN LocationApi as DispLocApi ON DispLoc.LocName = DispLocApi.LocName

WHERE Employee.EmpNo = '" . $req['EmpNo'] . "' and UserAppAuth.installationID = '" . $req['installationId'] . "' ";


$res = mssql_query($sql);
$error[] = mssql_get_last_message();
$error[] = $sql;
$i=1;
$db = mssql_fetch_array($res, MSSQL_ASSOC);
	if ($db['Dispatch'] != '')
	{
		$db = dispatch_hours($db, $dev);
	}
	if ($db['Screen'] == 'Dispatch')
	{
		$loc = location_api($db['DispatchName']);

		$db['latitude'] = $loc['latitude'];
		$db['longitude'] = $loc['longitude'];
		if ($req['latitude']!='null' && $req['latitude'] != '' &&  $db['latitude'] != '' && $db['latitude'] != 'null')
		{
			$db['distance'] = distance($req['latitude'], $req['longitude'], $db['latitude'], $db['longitude']);
		}
	}
	if ($db['Screen'] == 'Job')
	{
		$db['Add1'] = $db['JobAdd1'];
		$db['Add2'] = $db['JobAdd2'];
		$db['City'] = $db['JobCity'];
		$db['State'] = $db['JobState'];
		$db['Zip'] = $db['JobZip'];
		$loc = location_api($db['LocName']);

		$db['latitude'] = $loc['latitude'];
		$db['longitude'] = $loc['longitude'];
		if ($req['latitude']!='null' && $req['latitude'] != '' &&  $db['latitude'] != '' && $db['latitude'] != 'null')
		{
			$db['distance'] = distance($req['latitude'], $req['longitude'], $db['latitude'], $db['longitude']);
		}
	}

if (!$db)
	{
		$db['error'] = $error;
	}
return $db;
}

//Main Api Render

if ($_REQUEST['dev'] == 'true')
{
	$d = 'Dev';
}
else
{
	$d = '';
}

if ($_REQUEST['Screen'] == 'Dispatch')
{
	$error = dispatch_db($_REQUEST, $d);
	if (!isset($error['error']))
	{
		if ($error2 = timeclock_db($_REQUEST))
		{
			$error = array_merge($error, $error2);
		}
	}
	
}
elseif (isset($_REQUEST['Screen'])
{
	if ($error = timeclock_db($_REQUEST))
	{
		//error
	}

}



$note = "add" . $_REQUEST['Screen'] . "Note";
if (isset($_REQUEST['Screen']) && $error2 = add_note($_REQUEST, $d))
{
	if ($error)
	{
		$error = array_merge($error, $error2);
	}
	else
	{
		$error = $error2;
	}
}

$db = TimeClockQuery($_REQUEST, $d);
$i=0;
if (!isset($db))
{
	$db['authorized'] = '0';
}

	$db['id'] = $i;
	$i++;
if (isset($error))
{
	$db['error'][] = $error;

}
if (isset($db['authorized']) && $db['authorized'] == '1')
{
	$js2 = dispatch_query($_REQUEST['EmpNo'], $_REQUEST['dev']);
	$db['dispatchs'] = $js2['dispatchs'];
	if (!isset($_REQUEST['ServiceMan']))
	{
		$_REQUEST['ServiceMan'] = '';
	}
	if (!isset($_REQUEST['order']))
	{
		$_REQUEST['order'] = 'LocName';
	}
	$js3 = jobs_query($d, $_REQUEST['ServiceMan'], $_REQUEST['order']);
	$db['jobs'] = $js3['jobs'];
}


header('Content-Type: application/json');

$json =  json_encode($db);
if (isset($db['error']))
{
	error_log($json);
	
}
echo $json;
if (isset($_REQUEST['EmpNo']))
{
	if (isset($_REQUEST['checkinStatus']) && isset($_REQUEST['event']) && isset($_REQUEST['Dispatch']))
	{
		$str = $_REQUEST['checkinStatus'] . $_REQUEST['event'] . $_REQUEST['Dispatch'];
	}
	else
	{
		$str = '';
	}
	$file = '/var/www/html/primelogic/track/' . $_REQUEST['EmpNo'] . $str;
	$track = fopen($file, 'w');
	$db['request'] = $_REQUEST;
	fwrite($track, json_encode($db));
	fclose($track);
}
?>