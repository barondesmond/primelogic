<?php
include("_db_config.php");

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
	$db['StartDate'] = date("Y-m-d H:i:s", $db['StartTime']);
	$db['StopDate'] = date("Y-m-d H:i:s", $db['StopTime']);
	$data['TimeClock'][] = $db;
}

header('Content-Type: application/json');
echo json_encode($data);
?>