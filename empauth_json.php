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
LEFT JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
WHERE Email != '' and Inactive = '0' $sel";
		$sa[] = $sql;


$res = mssql_query($sql);
$error[] = mssql_get_last_message();

$i=1;
$db = mssql_fetch_array($res, MSSQL_ASSOC);

	if ($db['authorized'] == '')
	{
		$sql2 = "INSERT INTO UserAppAuth (EmpNo, installationId) VALUES('" . $db['EmpNo'] . "', '" . $_REQUEST['installationId'] . "')";
		@mssql_query($sql2);
		$error[] = mssql_get_last_message();
		$sa[] = $sql2;
		$db['authorized'] = '0';
	}
	$db['id'] = $i;
	$i++;

if (!isset($db))
{
	$db['error'] = 'Employee Missing';
	$db['authorized'] = 0;
}
if ($db['installationId'] != $_REQUEST['installationId'] && $db['authorized'] == 1)
{
	$db['authorized'] = 0;
	$sql = "UPDATE UserAppAuth SET authorized = '0', installationID = '" . $_REQUEST['installationId'] . "' WHERE EmpNo = '" . $db['EmpNo'] . "'";
	@mssql_query($sql);
	$error[] = mssql_get_last_message();
		$sa[] = $sql;

}

header('Content-Type: application/json');
$db[error] = $error;
$db[sql] = $sa;
echo json_encode($db);

?>