<?php
include("_db_config.php");

$sql = "SELECT * FROM TimeClockApp WHERE Posted = ''";
$res = mssql_query($sql);
$data['error'][] = mssql_get_last_message();
$data['error'][] = $sql;
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$data['TimeClock'][] = $db;
}

header('Content-Type: application/json');
echo json_encode($data);
?>