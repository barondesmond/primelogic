<?php
include("_db_config.php");
//api app


$js['title'] = 'Employee List';
$js['description'] = 'Employee Number, Email, Phone for authentication';
$sql = "SELECT EmpName, Email, phone FROM Employee WHERE Email != '' and Inactive = '0'";
$res = mssql_query($sql);
while ($db = mssql_fetch_assoc($res))
{
	$js[employees][] = $db;
}

header('Content-Type: application/json');

echo json_encode($js);

?>