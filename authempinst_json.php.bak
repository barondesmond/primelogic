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
	$auth['unauthorized'] = 'UseAppAuth';
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
		$sql = "UPDATE Service.dbo.Dispatch$dev SET Notes = '" . str_replace("'", "''", $addNote) . "' WHERE Dispatch = '" . $db['Dispatch'] . "'  ";
	}
	elseif ($db['Screen'] == 'Dispatch')
	{
		return false;
	}
	if ($db['Screen'] == 'Job' && $db[$note] != '' && $db['checkinStatus'] == 'addNote')
	{
		$addNote = $tcq['JobNotes'] . "\r\n" . date("Y-m-d: H:i:s") . '-' . $db['EmpNo'] . "-" . $tcq['EmpName'] . '-' .  $db[$note] . "\r\n";
		$sql = "UPDATE Service.dbo.Jobs$dev SET JobNotes = '" . str_replace("'", "''", $addNote) . "' WHERE Name = '" . $db['Name'] . "'";
	}
	elseif ($db['Screen'] == 'Job')
	{
		return false;
	}
	if ($db['Screen'] == 'Employee' && $db[$note] != '')
	{
		$addNote = $tcq['EmployeeNotes'] . "\r\n" . date("Y-m-d: H:i:s") . '-' . $db['EmpNo'] . "-" . '-' . $tcq['EmpName'] . '-' .  $db[$note] . "\r\n";
		$sql = "UPDATE Time.dbo.TimeClockApp SET EmployeeNotes = '" . str_replace("'", "''", $addNote) . "' WHERE TimeClockID = '" . $tcq['TimeClockID'] . "'";
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
	$sql1 = "UPDATE Time.dbo.TimeClockApp SET StopTime = '$time', EmpActive = '0' $complete WHERE EmpNo = '" . $db['EmpNo'] .  "' and installationId = '" . $db['installationId'] . "' and EmpActive = '1'";
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
	$sql2 = "INSERT INTO Time.dbo.TimeClockApp ($k) VALUES ($v)";
	
    @mssql_query($sql2);
	$error[] = mssql_get_last_message();
	$error[] = $sql2;
}
return $error;

}


function TimeClockQuery($req, $dev='')
{
$sql = "SELECT TImeClockApp.TimeClockID, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized, TimeClockApp.EmpActive, TimeClockApp.Screen, TimeClockApp.event, TimeClockApp.Name, TimeClockApp.Dispatch, Location.LocName, Jobs.JobNotes, LocationApi.latitude, LocationApi.longitude, TimeClockApp.Screen, Dispatch.Dispatch, DispLoc.LocName as DispatchName, Dispatch.Notes as DispatchNotes, DispLocApi.longitude as dispatchlongitude, DispLocApi.latitude as dispatchlatitude, DispLoc.Add1, DispLoc.Add2, DispLoc.City, DispLoc.State, DispLoc.Zip, DispLoc.Phone1, DispTech.Counter, Jobs.JobID, Location.Add1 as JobAdd1, Location.Add2 as JobAdd2, Location.City as JobCity, Location.State as JobState, Location.Zip as JobZip, Location.Phone1 as JobPhone1  FROM Service.dbo.Employee
INNER JOIN Time.dbo.UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
LEFT JOIN Time.dbo.TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo and UserAppAuth.installationId = TImeClockApp.installationId and EmpActive = '1'
LEFT JOIN Service.dbo.Jobs" . $dev . " as Jobs ON Jobs.Name = TimeClockApp.Name and Jobs.JobStatus = '100' and Jobs.Inactive = '0' and TimeClockApp.JobID = Jobs.JobID
LEFT JOIN Service.dbo.Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
LEFT JOIN Time.dbo.LocationApi ON Location.LocName = LocationApi.LocName
LEFT JOIN Service.dbo.Dispatch" . $dev . " as Dispatch ON TimeClockApp.Dispatch = Dispatch.Dispatch 
LEFT JOIN Service.dbo.DispTech" . $dev . " as DispTech ON Dispatch.Dispatch = DispTech.Dispatch and TimeClockApp.event = DispTech.Status and DispTech.Complete != 'Y' and DispTech.ServiceMan = '" . $req['EmpNo'] . "' and TimeClockApp.Counter = DispTech.Counter 
LEFT JOIN Service.dbo.Location as DispLoc ON Dispatch.CustNo = DispLoc.CustNo and Dispatch.LocNo = DispLoc.LocNo 
LEFT JOIN Time.dbo.LocationApi as DispLocApi ON DispLoc.LocName = DispLocApi.LocName

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


function authempinst($d)
{

if ($_REQUEST['Screen'] == 'Dispatch')
{
	$error = dispatch_db($_REQUEST, $d);
	if (isset($error['error']))
	{
		error_log($error);
	}
	if (!isset($error['error']))
	{
		if ($error2 = timeclock_db($_REQUEST))
		{
			$error = array_merge($error, $error2);
		}
	}
	
}
elseif (isset($_REQUEST['Screen']))
{
	if ($error = timeclock_db($_REQUEST))
	{
		//error
	}

}


return $error;
}

if ($_REQUEST['dev'] == 'true')
{
	$d = 'Dev';
}
else
{
	$d = '';
}


if (isset($_REQUEST['checkinStatus']) && ($_REQUEST['checkinStatus'] == 'Start' || $_REQUEST['checkinStatus'] == 'Stop'))
{
	$error = authempinst($d);
}
if (isset($_REQUEST['checkinStatus']) && $_REQUEST['checkinStatus'] == 'Switch')
{
	$_REQUEST['checkinStatus'] = 'Stop';
	$error = authempinst($d);
	if (!$error['error'])
	{
		$_REQUEST['checkinStatus'] = 'Start';
		$_REQUEST['event'] = 'Working';
		$_REQUEST['Counter'] = dispatch_counter($_REQUEST['Dispatch'], $d);
		$error2 = authempinst($d);
	}
}


if (isset($_REQUEST['Screen']) && isset($_REQUEST['checkinStatus']) && $_REQUEST['checkinStatus'] == 'addNote' && $error2 = add_note($_REQUEST, $d))
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
	$db['error'] = $error;
}

	$db['id'] = $i;
	$i++;
if (isset($error['error']))
{
	$db['error'] = $error['error'];
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