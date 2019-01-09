<?php
include("_db_config.php");

/* create*
CREATE TABLE UserAppAuth (
EmpNo INT PRIMARY KEY NOT NULL,
installationID varchar(255),
authorized INT NOT NULL DEFAULT '0'
)
*/

/*
INSERT INTO UserAppAuth (EmpNo, installationID) VALUES ('0195', 'askdfhahlkjsdhfladf');
*/


if ($_REQUEST['EmpName'] && $_REQUEST['Email'])
{
	$sel = " and EmpName = '" . $_REQUEST['EmpName'] . "' and Email = '" . $_REQUEST['Email'] . "'";
}


$sql = "SELECT Employee.EmpNo as EmpNo, EmpName, Email, phone, UserAppAuth.installationID, UserAppAuth.authorized FROM Employee
LEFT JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo and UserAppAuth.installationID = '" . $_REQUEST['installationID'] . "'
WHERE Email != '' and Inactive = '0' $sel";

echo $sql;
exit;
$res = mssql_query($sql);
$i=1;
while ($db = mssql_fetch_assoc($res))
{
	if (!isset($db['authorized']))
	{
		$sql = "INSERT INTO UserAppAuth (EmpNo, installationID) VALUES(" . $db['EmpNo'] . ", " . $_REQUEST['installationID'] . ")";
		@mssql_query($sql);
		$db['authorized'] = '0';
	}
	$db['id'] = $i;
	$i++;
}
if (!$db)
{
	$db['error'] = 'Employee Missing';
}

header('Content-Type: application/json');

echo json_encode($db);

?>