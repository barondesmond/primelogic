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


$sql = "SELECT Employee.EmpNo as EmpNo, EmpName, Email, phone, UserAppAuth.installationID, UserAppAuth.authorized, TimeClockApp.EmpActive FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo and UserAppAuth.installationId = TImeClockApp.installationId and EmpActive = '1'
WHERE Employee.EmpNo = '" . $_REQUEST['EmpNo'] . "' and UserAppAuth.installationID = '" . $_REQUEST['installationId'] . "' ";


$res = mssql_query($sql);
$i=1;
$db = mssql_fetch_array($res, MSSQL_ASSOC);

if (!$db)
{
	$db['authorized'] = '0';
}

	$db['id'] = $i;
	$i++;



header('Content-Type: application/json');

echo json_encode($db);

?>