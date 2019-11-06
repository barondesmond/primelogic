<?php
include("_db_config.php");
include("_user_app_auth.php");
include("_timeclockapp.php");

    // Sort the multidimensional array
     //usort($results, "time_sort");
     // Define the time sort function
     function time_sort($a,$b) {
          return $a['StartTime']<$b['StartTime'];
     }



function timeclock_json_track($db, $str)
{
	$file = '/var/www/html/primelogic/track/' . $_REQUEST['EmpNo'] . $str;
	$track = fopen($file, 'w');
	$db['request'] = $_REQUEST;
	fwrite($track, json_encode($db));
	fclose($track);
}
if (!isset($_REQUEST['StartTime']))
{
	$_REQUEST['StartTime'] = '0';
}
if (!isset($_REQUEST['StopTime']))
{
	$_REQUEST['StopTime'] = time();
}





if (isset($_REQUEST['Dev']))
{
	$dev = $_REQUEST['Dev'];
}
else
{
	$dev = '';
}

if (isset($_REQUEST['TimeClockID']) && isset($_REQUEST['timeclock_update']))
{
	$data = timeclock_update($_REQUEST['TimeClockID'], $dev);
	timeclock_json_track($data, 'timeclock_update');

	header('Content-Type: application/json');

	echo json_encode($data);
	exit;

}
elseif (isset($_REQUEST['timeclock_update']))
{
	$error['error'][] = 'error timeclock update request';
	header('Content-Type: application/json');
	$data = array_merge($error, $_REQUEST);
	echo json_encode($data);
	exit;


}
if (isset($_REQUEST['timeclock_add']) && isset($_REQUEST['StartDate']) && isset($_REQUEST['StopDate']) && isset($_REQUEST['event']) && isset($_REQUEST['Screen']))
{
	$_REQUEST['EmpNo'] = $_REQUEST['timeclock_add'];
	$data = timeclock_add($_REQUEST, $dev);
	header('Content-Type: application/json');
	
	echo json_encode($data);
	exit;

}
elseif (isset($_REQUEST['timeclock_add']))
{
	$error['error'][] = 'error timeclock add request';
	header('Content-Type: application/json');
	$data = array_merge($error, $_REQUEST);
	echo json_encode($data);
	exit;
}

$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}

$sql = "SELECT TImeClockApp.*, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized,  TimeClockApp.Screen  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo 
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo 
WHERE Posted is NULL and StartTime > " . $_REQUEST['StartTime'] . " and StopTime < " . $_REQUEST['StopTime'] . " 
";

/*
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
WHERE Posted is NULL and StartTime > " . $_REQUEST['StartTime'] . " and StopTime < " . $_REQUEST['StopTime'] . " 
ORDER BY TimeClockApp.StartTime ASC, TimeClockApp.TimeClockID ASC ";
*/


$res = mssql_query($sql);
$data['error'][] = mssql_get_last_message();
$data['error'][] = $sql;

while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$db['StartDate'] = date("Y:m:d H:i:s ", $db['StartTime']);
	$db['StopDate'] = date("Y:m:d H:i:s", $db['StopTime']);
	$data['TimeClock'][$db['TimeClockID']] = $db;
$sql3 = "SELECT * FROM PRHours WHERE  StartTime = '" . $_REQUEST['StartTime'] . "' and StopTime = '" . $_REQUEST['StopTime'] . "'  and EmpNo = '" . $db['EmpNo'] . "' and PayItemID = 'TCHours' ";
$res3 = @mssql_query($sql3);
$post = @mssql_fetch_array($res3, MSSQL_ASSOC);
if (isset($post['PayItemID']))
{
	$data['Post'][$db['EmpNo']] = $post;
}

}

  uasort($data['TimeClock'], "time_sort");


$sql = "SELECT TImeClockApp.*, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, UserAppAuth.installationId, UserAppAuth.authorized, Location.LocName, Jobs.JobNotes, LocationApi.latitude, LocationApi.longitude, TimeClockApp.Screen, Dispatch.Dispatch, DispLoc.LocName as DispatchName, Dispatch.Notes as DispatchNotes, DispLocApi.longitude as dispatchlongitude, DispLocApi.latitude as dispatchlatitude, DispLoc.Add1, DispLoc.Add2, DispLoc.City, DispLoc.State, DispLoc.Zip, DispLoc.Phone1  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo 
LEFT JOIN TimeClockAppHist as TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo 
LEFT JOIN Jobs" . $dev . " as Jobs ON Jobs.Name = TimeClockApp.Name 
LEFT JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
LEFT JOIN Dispatch" . $dev . " as Dispatch ON TimeClockApp.Dispatch = Dispatch.Dispatch 
LEFT JOIN DispTech" . $dev . " as DispTech ON Dispatch.Dispatch = DispTech.Dispatch and TimeClockApp.event = DispTech.Status 
LEFT JOIN Location as DispLoc ON Dispatch.CustNo = DispLoc.CustNo and Dispatch.LocNo = DispLoc.LocNo 
LEFT JOIN LocationApi as DispLocApi ON DispLoc.LocName = DispLocApi.LocName
WHERE Posted is NULL and StartTime > " . $_REQUEST['StartTime'] . " and StopTime < " . $_REQUEST['StopTime'] . " 
ORDER BY TimeClockApp.StartTime ASC, TimeClockApp.TimeClockID ASC ";
$res = mssql_query($sql);
$data['error'][] = mssql_get_last_message();
$data['error'][] = $sql;

while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$db['StartDate'] = date("Y:m:d H:i:s ", $db['StartTime']);
	$db['StopDate'] = date("Y:m:d H:i:s", $db['StopTime']);
	$data['TimeClockHist'][$db['TimeClockID']] = $db;


}

header('Content-Type: application/json');
echo json_encode($data);
?>