<?php
include("_db_config.php");
//api app

if ($_REQUEST['Name'] && $_REQUEST['Email'})
{
	$sel = " and EmpName = '". $ _GET['Name'] . ' and Email = ' . $_GET['Email'] . "'";
}
$js['title'] = 'Employee List';
$js['description'] = 'Employee Number, Email, Phone for authentication';
$sql = "SELECT EmpNo as id, EmpName, Email, phone FROM Employee WHERE Email != '' and Inactive = '0' $sel";
$res = mssql_query($sql);
$i=1;
while ($db = mssql_fetch_assoc($res))
{
	//$db['id'] = $i;
	$js[employees][] = $db;
	$i++;
}

header('Content-Type: application/json');

echo json_encode($js);

?>