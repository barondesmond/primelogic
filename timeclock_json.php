<?php
include("_db_config.php");

$sql = "SELECT * FROM TimeClockApp
INNER JOIN Employee ON TimeClockApp.EmpNo = Employee.EmpNo
INNER JOIN Jobs ON TimeClockApp.Name = Jobs.Name
INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.LocNo = Location.LocNo
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